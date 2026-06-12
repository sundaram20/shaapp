<?php include_once("../../config/auto_loader.php");
//////////////////////////////////////executing query////////////////////////////////////////////////////
 $OrderUniqueID	=$_REQUEST['OrderUniqueID'];

					  
if($_SESSION['editCart'][$OrderUniqueID]['reservation_date']){
$_SESSION['editCart'][$OrderUniqueID]['reservation_date'] = $_REQUEST['reservation_date'];	
$reservation_date 	= explode(' to ',$_SESSION['editCart'][$OrderUniqueID]['reservation_date']);
$checkinDate 		= $reservation_date[0];
$checkoutDate 		= $reservation_date[1];
$days =  abs((strtotime($reservation_date['0']) - strtotime($reservation_date['1']))/ 86400 );
if($days == '0'){
	$noOfDays = '1';
}else {
	$noOfDays = $days;
}

$StartDateList	=strtotime($checkinDate);

for($i=0;$i<$noOfDays;$i++){
	
	$UniqueDate = date ("d-m-Y", $StartDateList);
	
unset($_SESSION['tarrif']);
unset($_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom']);
	
$StartDateList	=strtotime("+1 day", strtotime($UniqueDate));
}


}else{
$_SESSION['editCart'][$OrderUniqueID]['reservation_date'] = $_REQUEST['reservation_date'];
}

$reservation_date = explode(' to ',$_REQUEST['reservation_date']);
$checkin_date = $reservation_date[0];
$checkout_date = $reservation_date[1];
$StartDateList	=strtotime($reservation_date['0']);

$id_company = $_SESSION['editCart'][$OrderUniqueID]['id_company'];
$rate_id = 	$_REQUEST['rate_id'];	

$dataValue = explode('|',$_REQUEST['dataValue']);

$_SESSION['editCart'][$OrderUniqueID]['noOfDays'] = $_SESSION['editCart'][$OrderUniqueID]['noOfDays'];




$resRoom = executeSql("SELECT rt.name as room_name, rp.name as rate_name, rd.* from `".TBL_RATE_DETAILS."` rd left join `".TBL_ROOM_TYPE."` rt on rd.room_id = rt.id left join `".TBL_RATE_PLAN."` rp on rd.rate_plan_id = rp.id where rd.status='1' and rt.status='1' and rd.rate_assign_id='".addslashes($dataValue['5'])."'  and rd.rate_plan_id='".addslashes($dataValue['4'])."' and rd.rate_id='".addslashes($dataValue['3'])."' and room_id='".addslashes($dataValue['2'])."' order by rd.room_id");	

$ratePlan = executeSql("SELECT * FROM `".TBL_RATE_PLAN."` where id_shop='".addslashes($_SESSION['shop'])."' and status='1' and id='".addslashes($dataValue['4'])."'");
$rowPlan = $db->fetch_object2($ratePlan);
$RateidExistCount	=	num_rows($resRoom);
	if($RateidExistCount >0){

$rowRoom = $db->fetch_object2($resRoom);
$priceValue = 0;
$inclusionFood =0;
$pkg_extra = 0;
/////////////////////////////////////making calculation////////////////////////////////////////////////
if($dataValue['6'] == '1'){	
	
		if($_REQUEST['adult_no'] == '1'){
			$NewpriceValue += $rowRoom->single_pax_price;
			$priceValue += $rowRoom->pkg_price;	
			$extra_bed_price	=	0;
			
		}elseif($_REQUEST['adult_no'] == '2'){
			$NewpriceValue += $rowRoom->double_pax_price;
			$priceValue += $rowRoom->pkg_price;
			$extra_bed_price	=	0;	
			
		}elseif($_REQUEST['adult_no'] == '3'){
			
			$NewpriceValue += $rowRoom->double_pax_price;
			$extra_bed_price	=	$rowRoom->extra_bed_price*1;
		}
		if($_REQUEST['child_no'] == '0'){
			$priceValue += $rowRoom->extra_bed_price;
			$extra_bed_price_child	=	0;
			
		}elseif($_REQUEST['child_no'] == '1'){					
			$priceValue += $rowRoom->extra_bed_price+$rowRoom->extra_bed_price;
			$extra_bed_price_child	=	$rowRoom->extra_bed_price;	
		}elseif($_REQUEST['child_no'] == '2'){					
			$priceValue += $rowRoom->extra_bed_price+$rowRoom->extra_bed_price;
			$extra_bed_price_child	=	$rowRoom->extra_bed_price*2;	
		}	
	
}else{
	
	
		if($_REQUEST['adult_no'] == '1'){
			$NewpriceValue += $rowRoom->single_pax_price;
			$extra_bed_price	=	0;	
			
		}elseif($_REQUEST['adult_no'] == '2'){
			$NewpriceValue += $rowRoom->double_pax_price;
			$extra_bed_price	=	0;	
			
		}elseif($_REQUEST['adult_no'] == '3'){
			$NewpriceValue += $rowRoom->double_pax_price;
			$extra_bed_price	=	$rowRoom->extra_bed_price*1;	
		}
		if($_REQUEST['child_no'] == '0'){
			$extra_bed_price_child	=	0;
			$priceValue += $rowRoom->extra_bed_price;
			
		}elseif($_REQUEST['child_no'] == '1'){					
			$priceValue += $rowRoom->extra_bed_price+$rowRoom->extra_bed_price;	
			$extra_bed_price_child	=	$rowRoom->extra_bed_price;	
			
		}elseif($_REQUEST['child_no'] == '2'){					
			$priceValue += $rowRoom->extra_bed_price+$rowRoom->extra_bed_price;	
			$extra_bed_price_child	=	$rowRoom->extra_bed_price*2;	
			
		}					
	

}
	}else{
		
		$NewpriceValue=$_REQUEST['tarrif'];
		}
											
											
											
									
											
$hotel_id = $_REQUEST['hotel_id'];
$dataValue = explode('|',$_REQUEST['dataValue']);
$uniqueCode = $_REQUEST['uniqueCode'];



$reservation_date = explode(' to ',$_REQUEST['reservation_date']);
$checkinDate = $reservation_date[0];
$checkoutDate = $reservation_date[1];

$rate_plan_id	=	$_REQUEST['rate_plan_id'];






$days =  abs((strtotime($reservation_date['0']) - strtotime($reservation_date['1']))/ 86400 );
if($days == '0'){
	$_SESSION['editCart'][$OrderUniqueID]['noOfDays'] = '1';
}else {
	$_SESSION['editCart'][$OrderUniqueID]['noOfDays'] = $days;
}
$_SESSION['editCart'][$OrderUniqueID]['noOfDays'] = $_SESSION['editCart'][$OrderUniqueID]['noOfDays'];

	


for($i=0;$i<$_SESSION['editCart'][$OrderUniqueID]['noOfDays'];$i++){
$UniqueDate = date ("d-m-Y", $StartDateList);
	

echo $_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDate][$uniqueCode] 			= 	$NewpriceValue+$extra_bed_price_child+$extra_bed_price;



$_SESSION['editCart'][$OrderUniqueID]['rate_plan_id'][$UniqueDate][$uniqueCode] 	=	$_REQUEST['rate_plan_id'];
$_SESSION['editCart'][$OrderUniqueID]['room_type_id'][$UniqueDate][$uniqueCode]		=	$_REQUEST['room_type_id'];
$_SESSION['editCart'][$OrderUniqueID]['Total_room'][$uniqueCode]					=	$_REQUEST['room_quantity'];
$_SESSION['editCart'][$OrderUniqueID]['meal'][$UniqueDate][$uniqueCode] 			=	$_REQUEST['meal'];


$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDate][$uniqueCode] = $_REQUEST['room_quantity'];
$_SESSION['editCart'][$OrderUniqueID]['adult_no'][$UniqueDate][$uniqueCode] 	 = $_REQUEST['adult_no'];
$_SESSION['editCart'][$OrderUniqueID]['infant_no'][$UniqueDate][$uniqueCode] 	 = $_REQUEST['infant_no'];
$_SESSION['editCart'][$OrderUniqueID]['child_no'][$UniqueDate][$uniqueCode] 	 = $_REQUEST['child_no'];
//$_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom'][$UniqueDate][$uniqueCode] 	  = $_REQUEST['TaxPerdayPerroom'];



$_SESSION['editCart'][$OrderUniqueID]['pkg_extra'][$UniqueDate][$uniqueCode] 				= $pkg_extra;

$_SESSION['editCart'][$OrderUniqueID]['room_price'][$UniqueDate][$uniqueCode]				= $NewpriceValue;

$_SESSION['editCart'][$OrderUniqueID]['extra_bed_price'][$UniqueDate][$uniqueCode]			= $extra_bed_price;
$_SESSION['editCart'][$OrderUniqueID]['extra_bed_price_child'][$UniqueDate][$uniqueCode]	= $extra_bed_price_child;


	$StartDateList	=	strtotime("+1 day", strtotime($UniqueDate));
	
}
$tarrif = $NewpriceValue+$extra_bed_price_child+$extra_bed_price;

function array_flatten($array) { 
  if (!is_array($array)) { 
    return FALSE; 
  } 
  $result = array(); 
  foreach ($array as $key => $value) { 
    if (is_array($value)) { 
      $result = array_merge($result, array_flatten($value)); 
    } 
    else { 
      $result[$key] = $value; 
    } 
  } 
  return $result; 
} 



$k						=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['tarrif']);
$extra_bed_price_child	=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['extra_bed_price_child']);
$extra_bed_price		=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['extra_bed_price']);

$adult_no				=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['adult_no']);
$infant_no				=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['infant_no']);
$child_no				=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['child_no']);
$room_quantityNew		=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['Total_room']);

//unset($_SESSION['editCart'][$OrderUniqueID]['tarrif']);
unset($_SESSION['editCart'][$OrderUniqueID]['child_no']);
//unset($_SESSION['editCart'][$OrderUniqueID]['child_no']);

	//print_r($_SESSION['editCart'][$OrderUniqueID]['child_no']);	
		echo 'dataValue=='.$_SESSION['editCart'][$OrderUniqueID]['dataValue'][$reservation_date[0]][$uniqueCode];
foreach($_SESSION['editCart'][$OrderUniqueID]['dataValue'][$reservation_date[0]] as $uniqueCode22 =>$dataCode){
		
	$StartDateListFor22	=strtotime($checkinDate);
	
	for($i=0;$i<1;$i++){
		$UniqueDateFor22 = date ("d-m-Y", $StartDateListFor22); 
	
		
		echo "TOTAL=".$totalAdult		+=	$adult_no[$uniqueCode22]* $_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor22][$uniqueCode22];
		$totalInfant	+=	$infant_no[$uniqueCode22]* $_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor22][$uniqueCode22];
		$totalChild22	+=	($child_no[$uniqueCode22]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor22][$uniqueCode22]);
		$totalRoom 		+= 	$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor22][$uniqueCode22];
	}
	
	
	}
	foreach($_SESSION['editCart'][$OrderUniqueID]['dataValue'][$reservation_date[0]] as $uniqueCode =>$dataCode){
		
	$StartDateListFor	=strtotime($checkinDate);
	
	for($i=0;$i<$noOfDays;$i++){	

		$UniqueDateFor = date ("d-m-Y", $StartDateListFor); 
 
 	
	$_SESSION['editCart'][$OrderUniqueID]['child_no'][$UniqueDateFor][$uniqueCode]	=	$child_no[$uniqueCode];
	$_SESSION['editCart'][$OrderUniqueID]['infant_no'][$UniqueDateFor][$uniqueCode]	=	$infant_no[$uniqueCode];
	
	$_SESSION['editCart'][$OrderUniqueID]['adult_no'][$UniqueDateFor][$uniqueCode]	=	$adult_no[$uniqueCode];
	
	
	
		
		


	   

	    $_SESSION['editCart'][$OrderUniqueID]['extra_bed_price_child'][$UniqueDateFor][$uniqueCode]	=	$extra_bed_price_child[$uniqueCode];
				
	 	$_SESSION['editCart'][$OrderUniqueID]['extra_bed_price'][$UniqueDateFor][$uniqueCode]		=	$extra_bed_price[$uniqueCode];
		$_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom'][$UniqueDateFor][$uniqueCode]	=	$TaxPerdayPerroom[$uniqueCode];
		
		
		//echo "TotalTraffic==".$_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]=		$k[$uniqueCode];
		//$_SESSION['editCart'][$OrderUniqueID]['rate_plan_id'][$UniqueDateFor][$uniqueCode]=		$rate_plan_id[$uniqueCode];
		
		echo "TotalTraffic==".$TotalTraffic=	($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode]);
		
		$totalPrice +=  ($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode]);
		
		$AlltotalPrice +=  ($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode])*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode];
				
		//$totalPrice +=  ($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$uniqueCode]+$_SESSION['editCart'][$OrderUniqueID]['meal'][$uniqueCode]+$_SESSION['editCart'][$OrderUniqueID]['extra_bed_price'][$uniqueCode]+$_SESSION['editCart'][$OrderUniqueID]['extra_bed_price_child'][$uniqueCode])*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['noOfDays'];
		
		
		$totalPriceTarrif +=  $_SESSION['editCart'][$OrderUniqueID]['tarrif_price'][$UniqueDateFor][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDate][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['noOfDays'];
		
		
		
		
		
		$dataValue = explode('|',$_SESSION['editCart'][$OrderUniqueID]['dataValue'][$reservation_date[0]][$uniqueCode]);
				
$checkin_date = explode('to',$_SESSION['editCart'][$OrderUniqueID]['reservation_date']); 
$Newcheckin	=	date("Y-m-d", strtotime($checkin_date['1']));
		
		
$query14	=	"SELECT * from `".TBL_RATE_SEASON."` WHERE ((start_date <=  '".$Newcheckin."' and end_date >= '".$Newcheckin."') OR ( start_date between '".$Newcheckin."' and '".$Newcheckin."') OR ( end_date between '".$Newcheckin."' and '".$Newcheckin."')) and id_shop='".$_SESSION['shop']."'";

$result14 = executeSql($query14,$link);
$query14count = mysql_num_rows($result14);	

$query14data = mysql_fetch_array($result14);
$seasonIdnew	= $query14data['id'];	

$SingleRowUniqueCode	=$_REQUEST['uniqueCode'];
	
		
		
		
		$SelectTaxDateSQL		= executeSql("SELECT * FROM `".TBL_TAX_DATE_RULE."` where id_shop='".addslashes($_SESSION['shop'])."'  order by start_date desc");
		$SelectTaxDateRow 		= $db->fetch_object2($SelectTaxDateSQL);
		$SlectedDateNewTax_id	= $SelectTaxDateRow->id;		
		$uniqueCodeRequest		= $_REQUEST['uniqueCode'];
		
		$price 					= ($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]);
		
		$resNewTax= executeSql("SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($_SESSION['shop'])."' AND ((tax_slabs_from <=  '".$price."' and tax_slabs_to  >= '".$price."') OR ( tax_slabs_from between '".$price."' and '".$price."') OR ( tax_slabs_to between '".$price."' and '".$price."')) and tax_uniqueid='".$SlectedDateNewTax_id."'  order by start_date desc");
		
		if(num_rows($resNewTax) >0 ){
				$rowNewTax = $db->fetch_object2($resNewTax);
		
		echo "New Tax Calculation -Tax %". $rowNewTax->tax_percent.'=='.$_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]."totalPrice= >".round($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]*($rowNewTax->tax_percent/100));
		
		
				
		$TaxInclusiveStatus1	=	selectColumn(TBL_RATE_PLAN,'tax_detail'," WHERE `id` = '".$_SESSION['editCart'][$OrderUniqueID]['rate_plan_id'][$UniqueDateFor][$uniqueCode]."'");
		
			if($TaxInclusiveStatus1	== '2'  && $TaxInclusiveStatus1	!= '1'   &&  $TaxInclusiveStatus1	!= '3' ){
				$taxablePrice	+=	($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode])*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode]*($rowNewTax->tax_percent/100);
				
				$_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom'][$UniqueDateFor][$uniqueCode] = ($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode])*($rowNewTax->tax_percent/100);	
			}
			if($TaxInclusiveStatus1	== '1' ){	
			//$_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCodeRequest]=0;
				$_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom'][$UniqueDateFor][$uniqueCode]  = 0;
				$SingleRowTaxValue	=0;
				}
				
			
			$TaxInclusiveStatus44	=	selectColumn(TBL_RATE_PLAN,'tax_detail'," WHERE `id` = '".$_SESSION['editCart'][$OrderUniqueID]['rate_plan_id'][$UniqueDateFor][$uniqueCodeRequest]."'");
		
		
		
		if($TaxInclusiveStatus44 == '2' ){
			$price2 					= ($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCodeRequest]);
			$resNewTax2= executeSql("SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($_SESSION['shop'])."' AND ((tax_slabs_from <=  '".$price2."' and tax_slabs_to  >= '".$price2."') OR ( tax_slabs_from between '".$price2."' and '".$price2."') OR ( tax_slabs_to between '".$price2."' and '".$price2."')) and tax_uniqueid='".$SlectedDateNewTax_id."'  order by start_date desc");
		
			if(num_rows($resNewTax2) >0 ){
				$rowNewTax2 = $db->fetch_object2($resNewTax2);
				$SingleRowTaxValue  = ($price2)*($rowNewTax2->tax_percent/100);	
			}
		
		}
			
			
		}
		
	$_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom'][$UniqueDateFor][$uniqueCode]=$_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom'][$UniqueDateFor][$uniqueCode];
	
	//}//New Tax Rules END 
		
		
		
		
		$StartDateListFor	=	strtotime("+1 day", strtotime($UniqueDateFor));
		
		
		
		}
		

$StartDateListFor	=strtotime($checkinDate);
 	
	
 	}
if($RateidExistCount >0){
	$disabled = 'disabled="disabled"';
echo '|||<td id="trafficprice_"'.$_REQUEST['uniqueCode'].'"><input type="text" class="form-control input-sm"   name="tarrif[]"  id="tarrif|'.$_REQUEST['uniqueCode'].'" value="'.round((($tarrif)),0,PHP_ROUND_HALF_UP).'" style="width: 80px;" data-parsley-type="digits" onKeyUp="getRateEdit(\'tarrif|'.$_REQUEST['uniqueCode'].'\');"  '.$disabled.'></td>';
}else{
	
	echo '||| ';
	}



		
		
echo '|||<td id="TaxPerdayPerroom_"'.$_REQUEST['uniqueCode'].'"><input type="text" class="form-control input-sm"  name="TaxPerdayPerroom[]"  id="TaxPerdayPerroom|'.$_REQUEST['uniqueCode'].'"  value="'.$SingleRowTaxValue.'" style="width: 80px;"  readonly></td>';

 

echo '|||<td id="price_"'.$_REQUEST['uniqueCode'].'"><strong><i class="fa fa-inr"></i> '.(($tarrif)*$_SESSION['editCart'][$OrderUniqueID]['noOfDays'])*$_REQUEST['room_quantity'].'</strong>&nbsp;&nbsp;<td>';
;

$_SESSION['editCart'][$OrderUniqueID]['charges_price'][$uniqueCode]	=$_REQUEST['charges_price'];

$_SESSION['editCart'][$OrderUniqueID]['charges_total'][$uniqueCode]	=$_REQUEST['charges_total'];


$TotalAdditionalChargesPrice	=	array_sum($_SESSION['editCart'][$OrderUniqueID]['charges_price']);
$TotalAdditionalChargesTaxValue	=	array_sum($_SESSION['editCart'][$OrderUniqueID]['charges_total']);

$_SESSION['editCart'][$OrderUniqueID]['totalRoom']= $totalRoom;
$_SESSION['editCart'][$OrderUniqueID]['totalAdult']= $totalAdult;
$_SESSION['editCart'][$OrderUniqueID]['totalChild']= $totalChild22;
$_SESSION['editCart'][$OrderUniqueID]['totalInfant']= $totalInfant;
$_SESSION['editCart'][$OrderUniqueID]['totalPrice'] = $AlltotalPrice;
$_SESSION['editCart'][$OrderUniqueID]['taxablePrice'] = $taxablePrice;
$_SESSION['editCart'][$OrderUniqueID]['totalPriceTarrif'] = $totalPriceTarrif;
$_SESSION['editCart'][$OrderUniqueID]['totalPriceFood'] = $totalPriceFood;
$_SESSION['editCart'][$OrderUniqueID]['totalPriceExtra'] = $totalPriceExtra;
$_SESSION['editCart'][$OrderUniqueID]['discountPrice'];
$_SESSION['editCart'][$OrderUniqueID]['finalPrice']  = round((($_SESSION['editCart'][$OrderUniqueID]['totalPrice']+$TotalAdditionalChargesPrice+$TotalAdditionalChargesTaxValue-$_SESSION['editCart'][$OrderUniqueID]['discountPrice'])+$_SESSION['editCart'][$OrderUniqueID]['taxablePrice']),0,PHP_ROUND_HALF_UP);

echo '|||<table class="table" >
              <tr>
                <th style="width:50%">Subtotal:</th>
                <td id="subtotal"><i class="fa fa-inr"></i> '.$_SESSION['editCart'][$OrderUniqueID]['totalPrice'].'</td>
              </tr>
			  <tr>
                <th>Additional Charges:</th>
                <td id="addchargesvalue"><i class="fa fa-inr"></i> '. round($TotalAdditionalChargesPrice,2).'</td>
              </tr>
			  <tr>
                <th>Discount:</th>
                <td id="discount"><i class="fa fa-inr"></i> '.round($_SESSION['editCart'][$OrderUniqueID]['discountPrice'],2).'</td>
              </tr>
              <tr>
                <th>Tax </th>
                <td id="tax"><i class="fa fa-inr"></i>  '.($_SESSION['editCart'][$OrderUniqueID]['taxablePrice']+$TotalAdditionalChargesTaxValue).'</td>
              </tr>              
              <tr>
                <th>Total:</th>
                <td id="totalPrice"><i class="fa fa-inr"></i>  '.$_SESSION['editCart'][$OrderUniqueID]['finalPrice'].'</td>
              </tr>
			  <tr>
                <th>Amount Received:</th>
                <td id="amountReceived" ><i class="fa fa-inr"></i>  '.round($_SESSION['editCart'][$OrderUniqueID]['amountReceived'],2).'</td>
              </tr>
			  <tr>
                <th>Balance:</th>
                <td id="balance"><i class="fa fa-inr"></i> '.($_SESSION['editCart'][$OrderUniqueID]['finalPrice']-$_SESSION['editCart'][$OrderUniqueID]['amountReceived']).'</td>
              </tr>
            </table>';
			
?>