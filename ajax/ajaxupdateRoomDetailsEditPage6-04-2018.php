<?php include_once("../../config/auto_loader.php");
//////////////////////////////////////executing query////////////////////////////////////////////////////
$dataValue = explode('|',$_REQUEST['dataValue']);
$uniqueCode = $_REQUEST['uniqueCode'];
$reservation_date = explode(' to ',$_REQUEST['reservation_date']);
$checkinDate = $reservation_date[0];
$checkoutDate = $reservation_date[1];
$days =  abs((strtotime($reservation_date['0']) - strtotime($reservation_date['1']))/ 86400 );
if($days == '0'){
	$_SESSION['editCart']['noOfDays'] = '1';
}else {
	$_SESSION['editCart']['noOfDays'] = $days;
}
$_SESSION['editCart']['noOfDays'] = $_SESSION['editCart']['noOfDays'];
$resRoom = executeSql("SELECT rt.name as room_name, rp.name as rate_name, rd.* from `".TBL_RATE_DETAILS."` rd left join `".TBL_ROOM_TYPE."` rt on rd.room_id = rt.id left join `".TBL_RATE_PLAN."` rp on rd.rate_plan_id = rp.id where rd.status='1' and rt.status='1' and rd.rate_assign_id='".addslashes($dataValue['5'])."'  and rd.rate_plan_id='".addslashes($dataValue['4'])."' and rd.rate_id='".addslashes($dataValue['3'])."' and room_id='".addslashes($dataValue['2'])."' order by rd.room_id");	

$ratePlan = executeSql("SELECT * FROM `".TBL_RATE_PLAN."` where id_shop='".addslashes($_SESSION['shop'])."' and status='1' and id='".addslashes($dataValue['4'])."'");
$rowPlan = $db->fetch_object2($ratePlan);
if(num_rows($resRoom) >0){

$rowRoom = $db->fetch_object2($resRoom);
$priceValue = 0;
$inclusionFood =0;
$pkg_extra = 0;
/////////////////////////////////////making calculation////////////////////////////////////////////////
if($dataValue['6'] == '1'){
	for($i=0;$i<$_REQUEST['room_quantity'];$i++){			
		if($_REQUEST['adult_no'] == '1'){
			$priceValue += $rowRoom->pkg_price;	
			$inclusionFood += $rowRoom->inclusion_food;		
		}elseif($_REQUEST['adult_no'] == '2'){
			$priceValue += $rowRoom->pkg_price;	
			$inclusionFood += ($rowRoom->inclusion_food)*2;	
		}elseif($_REQUEST['adult_no'] == '3'){
			$priceValue += $rowRoom->pkg_price+$rowRoom->extra_bed_price;	
			$inclusionFood += ($rowRoom->inclusion_food)*3;
		}
		if($_REQUEST['child_no'] == '1'){
			$priceValue += $rowRoom->extra_bed_price;
			$inclusionFood += $rowRoom->inclusion_food;	
		}elseif($_REQUEST['child_no'] == '2'){					
			$priceValue += $rowRoom->extra_bed_price+$rowRoom->extra_bed_price;
			$inclusionFood += ($rowRoom->inclusion_food)*2;		
		}	
		$pkg_extra += $rowRoom->pkg_extra_price;				
	}

}else{

	for($i=0;$i<$_REQUEST['room_quantity'];$i++){			
		if($_REQUEST['adult_no'] == '1'){
			$priceValue += $rowRoom->single_pax_price;	
			$inclusionFood += $rowRoom->inclusion_food;				
		}elseif($_REQUEST['adult_no'] == '2'){
			$priceValue += $rowRoom->double_pax_price;	
			$inclusionFood += ($rowRoom->inclusion_food)*2;	
		}elseif($_REQUEST['adult_no'] == '3'){
			$priceValue += $rowRoom->double_pax_price+$rowRoom->extra_bed_price;
			$inclusionFood += ($rowRoom->inclusion_food)*3;
		}
		if($_REQUEST['child_no'] == '1'){
			$priceValue += $rowRoom->extra_bed_price;
			$inclusionFood += $rowRoom->inclusion_food;	
		}elseif($_REQUEST['child_no'] == '2'){					
			$priceValue += $rowRoom->extra_bed_price+$rowRoom->extra_bed_price;	
			$inclusionFood += ($rowRoom->inclusion_food)*2;		
		}					
	}

}

//////////////////////////////removing modified session////////////////////////////////////////////////////////
$_SESSION['editCart']['room_quantity'][$uniqueCode] = $_REQUEST['room_quantity'];
$_SESSION['editCart']['adult_no'][$uniqueCode] = $_REQUEST['adult_no'];
$_SESSION['editCart']['infant_no'][$uniqueCode] = $_REQUEST['infant_no'];
$_SESSION['editCart']['child_no'][$uniqueCode] = $_REQUEST['child_no'];
$_SESSION['editCart']['room_price'][$uniqueCode] = $priceValue;
$_SESSION['editCart']['inclusion_food'][$uniqueCode] = $inclusionFood;
$_SESSION['editCart']['pkg_extra'][$uniqueCode] = $pkg_extra;

/*
if($rowPlan->tax_detail=='2'){
///////////////////////////tax configuration////////////////////////////////////////////////
if(($_SESSION['editCart']['tarrif_price'][$uniqueCode]) >7500){
		$resTax = executeSql("SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($_SESSION['shop'])."' and status='1' and price_range='".addslashes('>7500')."'");
		$rowTax = $db->fetch_object2($resTax);
		
		if($rowTax->tax_detail=='1'){
		$_SESSION['editCart']['room_tax_price'][$uniqueCode] =  round(($_SESSION['editCart']['room_price'][$uniqueCode]*$_SESSION['editCart']['noOfDays']*($rowTax->tax_percent/100)),0,PHP_ROUND_HALF_UP);
		}else{
		$_SESSION['editCart']['room_tax_price'][$uniqueCode] =  round((($_SESSION['editCart']['room_price'][$uniqueCode]+$_SESSION['editCart']['inclusion_food'][$uniqueCode])*$_SESSION['editCart']['noOfDays']*($rowTax->tax_percent/100)),0,PHP_ROUND_HALF_UP);
		}
	
		
		
	}else{
	
		$resTax = executeSql("SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($_SESSION['shop'])."' and status='1' and price_range='".addslashes('<=7500')."'");
	
		$rowTax = $db->fetch_object2($resTax);
		
		if($rowTax->tax_detail=='1'){
		$_SESSION['editCart']['room_tax_price'][$uniqueCode] =  round(($_SESSION['editCart']['room_price'][$uniqueCode]*$_SESSION['editCart']['noOfDays']*($rowTax->tax_percent/100)),0,PHP_ROUND_HALF_UP);
		}else{
		$_SESSION['editCart']['room_tax_price'][$uniqueCode] =  round((($_SESSION['editCart']['room_price'][$uniqueCode]+$_SESSION['editCart']['inclusion_food'][$uniqueCode])*$_SESSION['editCart']['noOfDays']*($rowTax->tax_percent/100)),0,PHP_ROUND_HALF_UP);
		}
		
	}
///////////////////////////tax configuration////////////////////////////////////////////////
}else{
$_SESSION['editCart']['room_tax_price'][$uniqueCode] = '0';

}
*/




				






/////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '||| |||<strong><i class="fa fa-inr"></i> '.$priceValue*$_SESSION['editCart']['noOfDays'].'</strong>&nbsp;&nbsp;<span class="pricePopUp_open" onclick="pricePopUp(this.id);" id="pricePopUp_'.$uniqueCode.'" ><i class="fa fa-pencil"></i></span>';	  
}else {
echo '||| |||Error.';

}


foreach($_SESSION['editCart']['dataValue'] as $uniqueCode =>$dataCode){			
		$totalAdult += $_SESSION['editCart']['adult_no'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode];
		$totalChild += $_SESSION['editCart']['child_no'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode];
		$totalInfant += $_SESSION['editCart']['infant_no'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode];
		$totalPrice +=  $_SESSION['editCart']['room_price'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
		$totalPriceTarrif +=  $_SESSION['editCart']['tarrif_price'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
		$totalPriceFood +=  $_SESSION['editCart']['inclusion_food'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
		$totalPriceExtra +=  $_SESSION['editCart']['pkg_extra'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
		$totalRoom += $_SESSION['editCart']['room_quantity'][$uniqueCode];				
		//$taxablePrice += $_SESSION['editCart']['room_tax_price'][$uniqueCode];
		
		
$Newcheckin = $_SESSION['editCart']['Newcheckin']	;
$query14	=	"SELECT * from `".TBL_RATE_SEASON."` WHERE ((start_date <=  '".$Newcheckin."' and end_date >= '".$Newcheckin."') OR ( start_date between '".$Newcheckin."' and '".$Newcheckin."') OR ( end_date between '".$Newcheckin."' and '".$Newcheckin."')) and id_shop='".$_SESSION['shop']."'";

$result14 = executeSql($query14,$link);
$query14count = mysql_num_rows($result14);	

$query14data = mysql_fetch_array($result14);
$seasonIdnew	= $query14data['id'];	

//echo "SELECT * FROM `".TBL_TAX_CONFIGURATION_TWO."` where id_shop='".addslashes($_SESSION['shop'])."' and `id_hotel` = '".addslashes($dataValue['1'])."' and  `room_id` = '".addslashes($dataValue['2'])."' and  `seasonId` = '".addslashes($seasonIdnew)."'";
$resTax= executeSql("SELECT * FROM `".TBL_TAX_CONFIGURATION_TWO."` where id_shop='".addslashes($_SESSION['shop'])."' and `id_hotel` = '".addslashes($dataValue['1'])."' and  `room_id` = '".addslashes($dataValue['2'])."' and  `seasonId` = '".addslashes($seasonIdnew)."'");

$rowTax = $db->fetch_object2($resTax);
 $rowTax->tax_room;
//echo "<br>Room price".$rowOrderDetail->total_price*$row->no_of_days."tax %= ".$rowTax->tax_room;
					
					
					//$roomTax	+=	$rowOrderDetail->total_price*$row->no_of_days*($rowTax->tax_room/100);
					
					
					
		
		
		
		
		$taxablePrice	+=	round($totalPrice*($rowTax->tax_room/100));
		
		
}
$_SESSION['editCart']['totalRoom']= $totalRoom;
$_SESSION['editCart']['totalAdult']= $totalAdult;
$_SESSION['editCart']['totalChild']= $totalChild;
$_SESSION['editCart']['totalInfant']= $totalInfant;
$_SESSION['editCart']['totalPrice'] = $totalPrice;
$_SESSION['editCart']['taxablePrice'] = $taxablePrice;
$_SESSION['editCart']['totalPriceTarrif'] = $totalPriceTarrif;
$_SESSION['editCart']['totalPriceFood'] = $totalPriceFood;
$_SESSION['editCart']['totalPriceExtra'] = $totalPriceExtra;
$_SESSION['editCart']['discountPrice'] = 0;







$_SESSION['editCart']['finalPrice']  = round((($_SESSION['editCart']['totalPrice']-$_SESSION['editCart']['discountPrice'])+$_SESSION['editCart']['taxablePrice']),0,PHP_ROUND_HALF_UP);

echo '|||<table class="table" >
              <tr>
                <th style="width:50%">Subtotal:</th>
                <td id="subtotal"><i class="fa fa-inr"></i> '.$_SESSION['editCart']['totalPrice'].'</td>
              </tr>
			  <tr>
                <th>Discount:</th>
                <td id="discount"><i class="fa fa-inr"></i> '.round($_SESSION['editCart']['discountPrice'],2).'</td>
              </tr>
              <tr>
                <th>Tax </th>
                <td id="tax"><i class="fa fa-inr"></i>  '.$_SESSION['editCart']['taxablePrice'].'</td>
              </tr>              
              <tr>
                <th>Total:</th>
                <td id="totalPrice"><i class="fa fa-inr"></i>  '.$_SESSION['editCart']['finalPrice'].'</td>
              </tr>
			  <tr>
                <th>Amount Received:</th>
                <td id="amountReceived" ><i class="fa fa-inr"></i>  '.round($_SESSION['editCart']['amountReceived'],2).'</td>
              </tr>
			  <tr>
                <th>Balance:</th>
                <td id="balance"><i class="fa fa-inr"></i> '.($_SESSION['editCart']['finalPrice']-$_SESSION['editCart']['amountReceived']).'</td>
              </tr>
            </table>';
?>