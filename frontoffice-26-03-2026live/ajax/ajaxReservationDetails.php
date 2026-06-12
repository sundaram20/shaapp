<?php
	include_once("../../config/auto_loader.php"); 
	//extract($_POST);
	//print_r($_REQUEST);
	//echo $_REQUEST['resID'];
	 $sql = "SELECT * FROM `".FO_RESERVATIONS."` where `id`='".addslashes(encryptor('decrypt',$_REQUEST['resID']))."'";
	$_SESSION['eId']	=	$_REQUEST['eId'];
	$db->query($sql);
	if($db->num_rows() > 0){
		$row = $db->fetch_object();
	$hotelname	=	selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->id_mst_hotels."'");
	$Guestname	=	selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->id_mst_guest."'");
	
	$room_tariff_price	= $row->room_tariff_price;
	$discount	=	$row->discount;
	$total_addon_price	=$row->total_addon_price;
	$total_tax=$row->total_tax;
	$amount_received	=	$row->amount_received;
	$balance	= $row->balance;
	$res_paymentStatus= $row->id_mst_attributes_payment_status;
	$booking_status= $row->booking_status;
	
	//echo "Select sum(tariff_price_per_day_per_room) as tariff , sum(tax_per_day_per_room) as taxes `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($_REQUEST['resID'])."' group by id_mst_room_types,id_fo_rate_plan,adults_per_room";
	//echo "Select sum(tariff_price_per_day_per_room) as tariff , sum(tax_per_day_per_room) as taxes, `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($_REQUEST['resID'])."' group by id_mst_room_types,id_fo_rate_plan,adults_per_room ";
	$sqlOrderDetail = mysqli_query($connNew,"Select sum(tariff_price_per_day_per_room) as tariff , sum(tax_per_day_per_room) as taxes, `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes(encryptor('decrypt',$_REQUEST['resID']))."' group by id_mst_room_types,id_fo_rate_plan,adults_per_room ");
			
			
			if(mysqli_num_rows($sqlOrderDetail) >0 ){
			$RoomWiseArray=array();
			$rrcounter=1;
			$roomdetails='';
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
					$id_hotel	=$row->id_mst_hotels;
					$id_room=$row->id_mst_room_types;
					//print_r($rowOrderDetail);
				 $perday_tariff	=	round(($rowOrderDetail->tariff/$rowOrderDetail->room_quantity)/$row->no_of_days);
				$perday_taxes	=	round(($rowOrderDetail->taxes/$rowOrderDetail->room_quantity)/$row->no_of_days);
				$tariffperroomtax=$perday_tariff+$perday_taxes;
				
	//room type========================
	$selectneww="SELECT * FROM ".TBL_ASSIGN_HOTEL_ROOM."  where id_mst_hotels = '".$row->id_mst_hotels."' " ;
	$resneww = mysqli_query($connNew,$selectneww);

	$dataArr.=  '<option value="">Select Type</option>';
	
	while($rowneww = mysqli_fetch_object($resneww)){
		$roomno = $rowneww->id_mst_room_types;
		
                $selectnew="SELECT * FROM ".TBL_ROOM_TYPE."  where id=$roomno";
				$resnew = mysqli_query($connNew,$selectnew);
				while($rownew = mysqli_fetch_object($resnew)){
					$romm = $rownew->name;
					$id = $rownew->id;
					
				if($id == $rowOrderDetail->id_mst_room_types){
						$selected="selected";
					}
					else{
						$selected="";
					}
				$dataArr.=  '<option '.$selected.' value="'.$id.'" >'.$romm.'</option>';
		
				}	
			}
		//room type========================
		
		//Plan===========================
	
	$dataArrplan='';
	//$dataArrplan.=  '<option value="">Select </option>';
	
	//$selectnewp="SELECT ".TBL_RATE_PLAN.".id,".TBL_RATE_PLAN.".name FROM ".TBL_ROOM_PLAN_LINKS." JOIN ".TBL_RATE_PLAN." ON ".TBL_ROOM_PLAN_LINKS.".id_plan = ".TBL_RATE_PLAN.".id where ".TBL_ROOM_PLAN_LINKS.".id_hotel = '$id_hotel' AND ".  TBL_ROOM_PLAN_LINKS.".id_room = '$id_room' group BY ".TBL_RATE_PLAN.".name";
	
	//$selectnewp="SELECT * FROM '".TBL_RATE_PLAN."' where id=".$select" " ;
	
	
	$dataArrplan.=  '<option value="">Select one</option><option value="1">Multiplan</option>';
   
				/*$selectnewp="SELECT ".TBL_RATE_PLAN.".id,".TBL_RATE_PLAN.".name FROM ".TBL_ROOM_PLAN_LINKS." JOIN ".TBL_RATE_PLAN."
				ON ".TBL_ROOM_PLAN_LINKS.".id_plan = ".TBL_RATE_PLAN.".id where ".TBL_ROOM_PLAN_LINKS.".id_hotel = '$id_hotel' AND ". 
				TBL_ROOM_PLAN_LINKS.".id_room = '$id_room' group BY ".TBL_RATE_PLAN.".name";
				
				*/
				$selectnewp="SELECT ".TBL_RATE_PLAN.".id,".TBL_RATE_PLAN.".name FROM  ".TBL_RATE_PLAN."
				where status='1' and id_shop='".addslashes($_SESSION['shop'])."'";
				
				$resnewp = mysqli_query($connNew,$selectnewp);
				
				while($rownewp = mysqli_fetch_object($resnewp)){
					
					if($rownewp->id==$rowOrderDetail->id_fo_rate_plan){
						$selected ='selected="selected"';
					}else{
						$selected ="";
					}
					
					$dataArrplan.=  '<option '.$selected.' value="'.$rownewp->id.'" >'.$rownewp->name.'</option>';
				}


			
		//Plan===========================
			for ($i=1; $i<=100; $i++)
    				{
        
            $roomQty .='<option value="'.$i.'"';
			 if($rowOrderDetail->room_quantity ==$i){
			 $roomQty .='selected="selected"';
			 }
			 
			 $roomQty .='>'.$i.'</option>';
       
    }
				
					 $roomdetails  .=	'<tr><td><select class="form-control parsley-error" name="roomtype[]" id="roomtype'.$rrcounter.'" onchange="roomtypechange(this.id,this.value);"  style="width: 140px;">"'.$dataArr.'"</select> <input value="" type="hidden" name="roomno[]" id="roomno'.$rrcounter.'" class="form-control" ></td><td><select class="form-control parsley-error" name="plan[]" id="plan'.$rrcounter.'" onchange="getval(this.id)" style="width: 110px;">"'.$dataArrplan.'"</select><input type="hidden" name="plantype[]" id="plantype'.$rrcounter.'" value=""></td>
					 
					 <td class="form-group" style="display:inline-flex"><select name="noofRooms[]" id="noofRooms'.$rrcounter.'" onchange="countcolorchange(this.id);tariffCalculation(this.id,this.value);" class="form-control parsley-error"  style="width: 80px;"><option value="">Rooms</option>"'.$roomQty.'"</select></td>
					 
					 
					 <td><select class="form-control parsley-error" name="adultperroom[]" id="adultperroom'.$rrcounter.'" style="width: 70px;"><option value="1">1</option><option selected="selected"class="2">2</option><option class="3">3</option></select></td><td><select class="form-control parsley-error" name="childbelow[]" id="childbelow'.$rrcounter.'" style="width: 70px;"><option selected="selected" value="0">0</option><option value="1">1</option><option class="2">2</option></select></td><td><select class="form-control parsley-error" name="childabove[]" id="childabove'.$rrcounter.'"  style="width: 70px;"><option selected="selected" value="0">0</option><option value="1">1</option><option class="2">2</option></select></td>
					 
					 <td style="display:inline-flex"><input type="text" class="form-control parsley-error" style="width: 60px;" name="tariffperroom[]" value="'.$perday_tariff.'" onkeyup="tariffCalculation(this.id,this.value);"  id="tariffperroom'.$rrcounter.'" /><a class="btn btn-success btn-sm" id="night'.$rrcounter.'" onclick="id_get_night(this.id)"><i class="fa fa-edit"></i></a></td>
					 
					 <td><input type="text" class="form-control parsley-error" style="width: 70px;" name="taxes[]" id="taxes'.$rrcounter.'" value="'.$perday_taxes.'" readonly /></td>
					 
					 <td style="display:inline-flex"><input type="text" class="form-control parsley-error" style="width: 60px" name="tariffperroomtax[]" id="tariffperroomtax'.$rrcounter.'" onkeyup="tariffCalculation(this.id,this.value);" value="'.$tariffperroomtax.'" /><a class="btn btn-success btn-sm" id="itform'.$rrcounter.'"  onclick="id_get_night(this.id)"><i class="fa fa-edit"></i></a></td>
					 
					 <td><input type="text" class="form-control parsley-error" style="width: 70px;" value="'.$rowOrderDetail->tariff.'" name="chargespernight[]" id="chargespernight'.$rrcounter.'"  readonly /></td><td><button class="btn btn-danger roomsRates_remove" type="button"><i class="fa fa-trash"></i></button></td></tr>';
				$rrcounter++;	
					//$RoomWiseArray['room'][$rowOrderDetail->id_mst_room_types][$rowOrderDetail->id_fo_rate_plan];
				}
	
	
			}
	
	 $roomdetails;
	
$Theader	=	'<table class="table-hover">
              
              <tbody>
             <input type="hidden" name="rrcounter" id="rrcounter" value="0" text="">
              </tbody>
              
            </table>';
		 $selectOtherCharges="SELECT * FROM ".FO_RESERVATION_ADDONS_DETAILS."  where `id_fo_reservations` = '".addslashes(encryptor('decrypt',$_REQUEST['resID']))."' and id_mst_hotels = '".$row->id_mst_hotels."' " ;
	$resOtherCharges = mysqli_query($connNew,$selectOtherCharges);

	
	$Addon	=	'<thead id="addonshideandshow">
                <tr>
                  <th>Item</th>
                  <th>Addional Description</th>
                  <th>Qty</th>
                  <th>Unit</th>
                  <th>Rate</th>
                  <th>Tax%</th>
                  <th>Tax Value</th>
                  <th>Amount</th>
                </tr>
              </thead>';
	while($rowOtherCharges = mysqli_fetch_object($resOtherCharges)){	
		$Addon	.=	'<tr><td><select class="form-control parsley-error" style="width: 120px;" name="item[]" id="item1"><option selected="selected" value="">Select Item</option><option value="Item1">Item1</option><option class="Item2">Item2</option></select></td>
		<td><input type="text" class="form-control parsley-error" style="width:140px;" name="additionalcharges[]"  id="additionalcharges1"  
		value="'.$rowOtherCharges->additional_description.'"></td>
		<td><input type="text" class="form-control parsley-error" style="width:100px;" name="qty[]" id="qty1"  value="'.$rowOtherCharges->qty.'"></td>
		<td><input type="text" class="form-control parsley-error" style="width:100px;" name="unit1[]" id="unit1"  value="'.$rowOtherCharges->unit.'"></td>
		
		<td><input type="text" class="form-control parsley-error" style="width:110px;" name="rate[]" id="rate1" value="'.$rowOtherCharges->rate.'"></td>
		<td><input type="text" class="form-control parsley-error" style="width:110px;" name="tax[]" id="tax1"  value="'.$rowOtherCharges->tax_percent.'"></td>
		<td><input type="text" class="form-control parsley-error" style="width:110px;" name="taxvalue[]" id="taxvalue1"  value="'.$rowOtherCharges->tax_value.'"></td>
		<td><input type="text" class="form-control parsley-error" style="width:110px;" name="amount[]" id="amount1" value="'.$rowOtherCharges->amount.'"></td><td><button class="btn btn-danger addons_remove" type="button"><i class="fa fa-trash"></i></button></td></tr>';	
			
	}
			
	
	$data = array('Id'=>$row->id,'res_id'=>$row->booking_no,'additional_charges'=>$row->total_addon_price,'booking_date'=>date('d-m-Y',strtotime($row->booking_date)),'Hotel'=>$hotelname,'id_mst_hotels'=>$row->id_mst_hotels,'Room'=>'101','Guest'=>'shubhi','CheckIn'=>date('d-m-Y',strtotime($row->checkin)),'CheckOut'=>date('d-m-Y',strtotime($row->checkout)),'Email'=>'shubhi@gmail.com','id_mst_guest'=>$row->id_mst_guest,'id_mst_company'=>$row->id_mst_company,'rrcounter'=>$rrcounter,'sub_total'=>$row->sub_total,'discount'=>$row->discount,'total_addon_price'=>$row->total_addon_price,'total_tax'=>$row->total_tax,'amount_received'=>$row->amount_received,'balance'=>$row->balance,'roomdetails'=>$roomdetails,'net_booking_amount'=>$row->net_booking_amount,'special_requests'=>$row->special_requests,'internal_remarks'=>$row->internal_remarks,'id_mst_attributes_company_group'=>$row->id_mst_attributes_company_group,'res_bookerName'=>$row->id_mst_company_contacts,'res_bookingthrough'=>$row->id_mst_attributes_booking_through,'res_segment'=>$row->id_mst_attributes_segments,'res_bookingsourcee'=>$row->id_mst_attributes_booking_source,'res_paymentStatus'=>$res_paymentStatus,'booking_status'=>$booking_status,
	'res_amendment'=>$row->id_mst_attributes_amendment,'res_arrivingtime'=>$row->arrival_time,'res_modeoftravel'=>$row->id_mst_attributes_mode_of_travel,'pickup_details'=>$row->pickup_details,'arrival_from'=>$row->arrival_from,'other_reference'=>$row->other_reference,'departing_to'=>$row->departing_to,'tableheader'=>$Theader,'OtherChargesAddon'=>$Addon);
		echo json_encode($data);
	
	}else{
		
		$data = array("error"=>"sorry record not found");
		echo json_encode($data);
	}
	
	
?>