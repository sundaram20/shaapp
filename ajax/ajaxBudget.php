<?php include_once("../../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_BUDGET_MASTER,'update');

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//print_r($_REQUEST);

 $hotelId = $_REQUEST['hotelId'];

//$room_id = implode(',',$_REQUEST['roomId'])	;

$season = $_REQUEST['seasonId'];

//$id = encryptor(decrypt,$_REQUEST['hotelId']);



$start_date	=	selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");		

$end_date	=selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");

if($_REQUEST['hotelId']!=''){
	$editRowvalue = executeSql("SELECT * FROM `".TBL_BUDGET_MASTER."` as a  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."' and  a.`month`='".$start_date."' and a.`id_user`='".$_REQUEST['hotelId']."'");
}

//////////////////////////////getting rate data on edit//////////////////////////////////////////////////////

$CountNumber_row	=	num_rows($editRowvalue); 

if($_REQUEST['hotelId']!='' && $CountNumber_row > 0){

	 //EDIT
////////////////////////////show grid data////////////////////////////////////////////////////////
$availableData .= '<div class="box box-success  table-responsive no-padding">

				  <table class="table table-hover" style="margin-bottom:none !important;">

		

		<tr> 

		<th>Hotel</th>

		<th>Apr - '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>		

		<th>May- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Jun- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Jul- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>				  

		<th>Aug- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Sep- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Oct- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Nov- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Dec- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Jan- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Feb- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Mar- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Total</th>	

		</tr>';

 $resCat_rooms1 = selectSql(TBL_HOTELS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`  ');

while($rowHotelResult = $db->fetch_object2($resCat_rooms1)){
	
	$availableData .= '<tr>';
    $editstart_date =	$editrow->start_date;
	$editend_date 	=	$editrow->end_date;

	$availableData .= '<input type="hidden" id="data_id" name="data_id[]" value="'.$rowHotelResult->id.'" >';	
	$availableData .= '<input type="hidden" id="bugetHotel" name="bugetHotel[]" value="'.$rowHotelResult->id.'" >';
	$availableData .= '<td>'.$rowHotelResult->name.'</td>'; 

	$start_date	=	selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");		
	$end_date	=selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");								 
	$sqlqu = "SELECT * FROM `".TBL_BUDGET_MASTER."` as a  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."' and  a.`id_user`='".$_REQUEST['hotelId']."' and a.`seasonId`='".$_REQUEST['seasonId']."' and a.`id_hotel`='".$rowHotelResult->id."' ORDER BY a.month";

	$editRowvalue = executeSql($sqlqu);

	if(num_rows($editRowvalue) > 0){
		while($resultCat = $db->fetch_object2($editRowvalue)){
		 $availableData .= '<td>
		  <input type="text" class="form-control  buget_qty" id="buget_qty|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id.'" name="buget_qty|'.$rowHotelResult->id.'[]" value="'.$resultCat->qty.'"  automcomplete="off" data-parsley-type="number" style="width:60px;">
		  </td>';
		}
		
		$editTotalsql = executeSql("SELECT a.*, sum(qty) as totals  FROM `".TBL_BUDGET_MASTER."` as a  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."' and  a.`id_user`='".$_REQUEST['hotelId']."' and a.`seasonId`='".$_REQUEST['seasonId']."' and a.`id_hotel`='".$rowHotelResult->id."'");
		$totalresultCat = $db->fetch_object2($editTotalsql);
		$availableData .= '<td style=" background-color:#367fa9;"><input type="text" class="form-control  total" id="total" name="total" value="'.$totalresultCat->totals.'" style="width:60px;background-color:#367fa9; color:#fff;"></td>';	

	}else{

		$start = $month = strtotime($start_date);
		$end = strtotime($end_date);
		while($month < $end){
	     $DateValue	=	date('Y-m-d', $month);
	     $month = strtotime("+1 month", $month);
		 $availableData .= '<td>

					  <input type="text" class="form-control  buget_qty" id="buget_qty|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id.'" name="buget_qty|'.$rowHotelResult->id.'[]" value="'.$editsingle_pax_price.'"  automcomplete="off" data-parsley-type="number" style="width:60px;">

				  </td>';
	}
 $editTotalsql = executeSql("SELECT a.*, sum(qty) as totals  FROM `".TBL_BUDGET_MASTER."` as a  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."' and  a.`id_user`='".$_REQUEST['hotelId']."' and a.`seasonId`='".$_REQUEST['seasonId']."' and a.`id_hotel`='".$rowHotelResult->id."'");

		$totalresultCat = $db->fetch_object2($editTotalsql);

		$availableData .= '<td style=" background-color:#367fa9;"><input type="text" class="form-control  total" id="total" name="total" value="'.$totalresultCat->totals.'" style="width:60px;background-color:#367fa9; color:#fff;"></td>';

}//end of while
$availableData .='</tr>';

$availableData .='<tr>';				
$availableData .='<td>Budget Value</td>';

	 $editRowvalue2 = executeSql("SELECT * FROM `".TBL_BUDGET_MASTER."` as a  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."'  and a.`id_user`='".$_REQUEST['hotelId']."' and a.`seasonId`='".$_REQUEST['seasonId']."' and a.`id_hotel`='".$rowHotelResult->id."' ORDER BY a.month ");

		if(num_rows($editRowvalue2)>0){
			 while($resultCat2 = $db->fetch_object2($editRowvalue2)){
			 $availableData .= ' <td><input type="text" class="form-control  tax" id="buget_value|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id.'" name="buget_value|'.$rowHotelResult->id.'[]" value="'.$resultCat2->month_value.'"  automcomplete="off" data-parsley-type="number" style="width:60px;"></td>';

	 

	$availableData .= '<input type="hidden" id="MonthDate" name="MonthDate|'.$rowHotelResult->id.'[]" value="'.$DateValue1.'" >';	

	$availableData .= '<input type="hidden" id="id" name="id|'.$rowHotelResult->id.'[]" value="'.$resultCat2->id.'" >';	
		}

			

			

	 $editRowMonthvalue = executeSql("SELECT a.*, sum(month_value) as Monthtotals FROM `".TBL_BUDGET_MASTER."` as a  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."'  and a.`id_user`='".$_REQUEST['hotelId']."' and a.`seasonId`='".$_REQUEST['seasonId']."' and a.`id_hotel`='".$rowHotelResult->id."'");

			 $resulteditRowMonthvalue = $db->fetch_object2($editRowMonthvalue);

				 

			$availableData .= '<td style=" background-color:#367fa9;"><input type="text" class="form-control  total" id="Totalbuget_value" name="Totalbuget_value" value="'.$resulteditRowMonthvalue->Monthtotals.'" style="width:60px; background-color:#367fa9; color:#fff;"></td>';
}else{
	$start = $month = strtotime($start_date);
	$end = strtotime($end_date);

while($month < $end){
    $DateValue1	=	date('Y-m-d', $month);
    $month = strtotime("+1 month", $month);
	 $availableData .= ' <td><input type="text" class="form-control  tax" id="buget_value|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id.'" name="buget_value|'.$rowHotelResult->id.'[]" value="'.$editsingle_pax_price.'"  automcomplete="off" data-parsley-type="number" style="width:60px;"></td>';
	$availableData .= '<input type="hidden" id="MonthDate" name="MonthDate|'.$rowHotelResult->id.'[]" value="'.$DateValue1.'" >';	 
}
	$editTotalsql = executeSql("SELECT a.*, sum(qty) as totals  FROM `".TBL_BUDGET_MASTER."` as a  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."' and  a.`id_user`='".$_REQUEST['hotelId']."' and a.`seasonId`='".$_REQUEST['seasonId']."' and a.`id_hotel`='".$rowHotelResult->id."'");

		$totalresultCat = $db->fetch_object2($editTotalsql);

		$availableData .= '<td style=" background-color:#367fa9;"><input type="text" class="form-control  total" id="total" name="total" value="'.$totalresultCat->totals.'" style="width:60px; background-color:#367fa9; color:#fff;"></td>';	

			}
	$availableData .='</tr>';
}

// -------------------Bottom Total START----------------------------------------------------	
$availableData .='<tr style=" background-color:#367fa9; color:#fff;">';					
$availableData .='<td style="text-align: right;vertical-align: middle;  background-color:#367fa9; color:#fff;">Total RN</td>';
$start = $month = strtotime($start_date);
$end = strtotime($end_date);
while($month < $end){
    $DateValue1	=	date('Y-m-d', $month);
    $month = strtotime("+1 month", $month);
	$editTotalsql = executeSql("SELECT a.*, sum(qty) as totals  FROM `".TBL_BUDGET_MASTER."` as a  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."' and  a.`id_user`='".$_REQUEST['hotelId']."' and a.`seasonId`='".$_REQUEST['seasonId']."' and a.`month`='".$DateValue1."'");

		$totalresultCat = $db->fetch_object2($editTotalsql);

		$SubTotal	+=	$totalresultCat->totals;

		$availableData .= '<td><input type="text" class="form-control  total" id="total" name="total" value="'.$totalresultCat->totals.'" style="width:60px; background-color:#367fa9; color:#fff;"></td>';					
}

$availableData .= '<td><input type="text" class="form-control  total" id="total" name="total" value="'.$SubTotal.'" style="width:60px; background-color:#367fa9; color:#fff;"></td>';	
$availableData .='</tr>';
$availableData .='<tr style=" background-color:#367fa9; color:#fff;">';					
$availableData .='<td style="text-align: right;vertical-align: middle; background-color:#367fa9; color:#fff;">Total Value</td>';
$start = $month = strtotime($start_date);
$end = strtotime($end_date);
	while($month < $end){
     $DateValue1	=	date('Y-m-d', $month);
     $month = strtotime("+1 month", $month);
 	$editTotalsql = executeSql("SELECT a.*, sum(month_value) as totals  FROM `".TBL_BUDGET_MASTER."` as a  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."' and  a.`id_user`='".$_REQUEST['hotelId']."' and a.`seasonId`='".$_REQUEST['seasonId']."' and a.`month`='".$DateValue1."'");

		$totalresultCat = $db->fetch_object2($editTotalsql);

		$availableData .= '<td><input type="text" class="form-control  total" id="total" name="total" value="'.$totalresultCat->totals.'" style="width:60px; background-color:#367fa9; color:#fff;"></td>';					
	$SubTotal1	+=	$totalresultCat->totals;
}

		

	$availableData .= '<td ><input type="text"  class="form-control  total" id="total" name="total" value="'.$SubTotal1.'" style="width:60px; background-color:#367fa9; color:#fff;"></td>';	

			

	$availableData .='</tr>';					
// -------------------Bottom Total END----------------------------------------------------
}else{ //EDIT
////////////////////////////show grid data////////////////////////////////////////////////////////
$availableData .= '<div class="box box-success  table-responsive no-padding">

				  <table class="table table-hover" style="margin-bottom:none !important;">

		

		<tr>

		<th>Hotel</th>

		<th>Apr - '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>		

		<th>May- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Jun- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Jul- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>				  

		<th>Aug- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Sep- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Oct- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Nov- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Dec- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Jan- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Feb- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Mar- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		</tr>';

 $resCat_rooms1 = selectSql(TBL_HOTELS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name` ');
	while($rowHotelResult = $db->fetch_object2($resCat_rooms1)){
		$availableData .= '<tr id="rateMaster|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'">';
	    $editstart_date 		=	$editrow->start_date;
		$editend_date 			=	$editrow->end_date;

$availableData .= '<input type="hidden" id="data_id" name="data_id[]" value="'.$rowHotelResult->id.'" >';	

//$availableData .= '<input type="hidden" id="data_id|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="data_id[]" value="|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" >';	

$availableData .= '<input type="hidden" id="bugetHotel" name="bugetHotel[]" value="'.$rowHotelResult->id.'" >';
$availableData .= '<td>'.$rowHotelResult->name.'</td>'; 
$start_date	=	selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");		
$end_date	=selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");								 
$start = $month = strtotime($start_date);
$end = strtotime($end_date);
while($month < $end){
     $DateValue	=	date('Y-m-d', $month);
     $month = strtotime("+1 month", $month);
	 $availableData .= '<td>

				  <input type="text" class="form-control  buget_qty" id="buget_qty|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id.'" name="buget_qty|'.$rowHotelResult->id.'[]" value="'.$editsingle_pax_price.'"  automcomplete="off" data-parsley-type="number" style="width:60px;">

				  </td>';

}

$availableData .='</tr>';
$availableData .='<tr>';				
$availableData .='<td>Budget Value</td>';
$start = $month = strtotime($start_date);
$end = strtotime($end_date);
while($month < $end){
     $DateValue1	=	date('Y-m-d', $month);
     $month = strtotime("+1 month", $month);
	 $availableData .= ' <td><input type="text" class="form-control  tax" id="buget_value|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id.'" name="buget_value|'.$rowHotelResult->id.'[]" value="'.$editsingle_pax_price.'"  automcomplete="off" data-parsley-type="number" style="width:60px;"></td>';
	$availableData .= '<input type="hidden" id="MonthDate" name="MonthDate|'.$rowHotelResult->id.'[]" value="'.$DateValue1.'" >';	 
}
	$availableData .='</tr>';
	}
}				 

											 

$availableData .='</table>	';
//}
$availableData .= '  
            </div>';
echo $availableData;

?>
<script type="text/javascript">

function Creditallow(id_company,rate_id){

 var form1=$("#availabiltyForm");	

 var dataString = $("#availabiltyForm").serialize();	

	if(form1.parsley().validate()){

		$.ajax({

		   type: "POST",

		   url: 'ajax/ajaxCreditallow.php',

		   data: dataString+'&id_company='+id_company+'&rate_id='+rate_id, 

		   success: function (result) {					

				$( "#Creditallow_value" ).html(result);								

			}

		})

	}

}





//////////////////////check availabilty -book-now.php///////////////////////////////////////////////// 



function ajaxCheckAvailability() {

          //alert('test');

  		  var form=$("#availabiltyForm");		  

		  form.parsley().validate();		  

  		  $('.loading').show(); 

		  $.ajax({

			   type: "POST",

			   url: 'ajax/ajaxcheckAvailability.php',

			   data: form.serialize(), 

			   success: function (result) {

					$('#availabilty').html(result)

				},

			  complete: function(){

				$('.loading').hide();

			  }

		})

	return false;

 }

/////////////////////////////////show events on date -book-now.php/////////////////////////////////////////////

function getEvents(dated){

//$('#eventsPopup').popup('show');

 $('#eventsPopup').popup({

            //pagecontainer: '.container',

        	transition: 'all 0.3s',

            autoopen: true,            

        });

}







/////////////////////////////////show plan Details on date -book-now.php/////////////////////////////////////////////





$("#view").click(function (){

 var form1=$("#availabiltyForm");	

 var form2=$("#addRoomForm");

 var dataString = $("#availabiltyForm, #addRoomForm").serialize();	

	if(form1.parsley().validate() && form2.parsley().validate()){

		$.ajax({

		   type: "POST",

		   url: 'ajax/ajaxGetPlanDetails.php',

		   data: dataString, 

		   success: function (result) {					

				$( "#ajaxPlanData" ).html(result);

				$('#planDetail').popup({

        			 transition: 'all 0.3s',

           			 autoopen: true,            

        		});

				 //$("#hotelId").val('1').attr('selected','selected');					

			}

		})

	}

})


</script>

