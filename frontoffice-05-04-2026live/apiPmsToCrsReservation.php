<?php 
include_once("../../config/auto_loader.php");
//function apiPmsToCrsReservation($id){
	
	
			  /* $url ="https://roomstatushub.in/crs/channel/apiRequestPmsToCrs.php";
		
				$headers = array(
				    "Content-type: text/xml",
				    "Content-length: " . strlen($idsxml),
				    "Connection: close",
				);

				$ch = curl_init(); 
				curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT, 60);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $idsxml);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

				$dataResp = curl_exec($ch);
	
			$responsexml = simplexml_load_string($dataResp);
			 $responsexml = json_decode(json_encode($responsexml), true);*/
			 
//return 'Set';
$CreationTime   = date("Y-m-d").'T'.date("H:i:s");
$xmlfile 		= '_IDSAPI.xml';
$ChannelID	    = $ChannelID;//3; // 3 for IDS 
//if($_REQUEST['hotel_id']==''){
    $apiHotelID=$apiHotelID;
//}else{
 //   $apiHotelID=$_REQUEST['hotel_id'];
//}

$sqlHotelMapping = mysqli_query($connNew,"SELECT * FROM ".TBL_HOTEL_MAPPING." WHERE hotel_id='".$apiHotelID."' AND channel_id='".$ChannelID."' AND status=1");		
$HotelMappingID	 =	mysqli_num_rows($sqlHotelMapping);
$HotelMappingresult	 =	mysqli_fetch_object($sqlHotelMapping);
$mappingHotelCode	= $HotelMappingresult->booking_engine_id;
 $HotelName			=	selectColumn(TBL_HOTELS,'name'," WHERE status='1' AND `id` = '".$HotelMappingresult->hotel_id."'");

$created_by_pms			=	selectColumn('fo_reservations','created_by_pms'," WHERE  `id` = '".$BookingID."'");


$activeModifyReservation	= $HotelMappingresult->bookingflow;

if($HotelMappingID>0  && (($activeModifyReservation=='0' && $created_by_pms=='1') || ($activeModifyReservation=='0' && $created_by_pms=='0'))){	

//if($HotelMappingID>0){	

	   


$maxofApiid= mysqli_query($connNew,"SELECT max(id) as maxid FROM `api_request` WHERE 1");
$maxofvalue=mysqli_fetch_object($maxofApiid);
$unicId		=	$maxofvalue->maxid+1;
	
	 if($_POST['booking_status']==4){
		 $ResStatus		= 'Cancel';
		 $BookingID	    = addslashes(encryptor('decrypt',$_POST['eId']));
		}	
  $sql = "SELECT * FROM `fo_reservations` where `id` = '".$BookingID."'";
	
	$db->query($sql);
	if($db->num_rows() > 0){
		$row = $db->fetch_object();
	}
	$BookingStatus		=	selectColumn('fo_booking_status','name'," WHERE `status`=1 AND `id` = '".$row->booking_status."'");
	
  if($row !== false) {
	  $CompanyName		=	selectColumn(TBL_COMPANY,'name'," WHERE `status`=1 AND `id` = '".$row->id_mst_company."'");
	  $id_company_crs		=	selectColumn(TBL_COMPANY,'id_company_crs'," WHERE `status`=1 AND `id` = '".$row->id_mst_company."'");
	 /*$id_default_group		=	selectColumn(TBL_COMPANY,'id_mst_attributes_company_group'," WHERE `status`=1 AND `id` = '".$row->id_mst_company."'");
	  if($id_default_group=='0'){
		  $groupName= 'Default/Guest';
	  }else{
		   $groupName= selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$id_default_group."'");
	  }*/
if($row->other_reference!=''){
	$ResStatus		= 'Modify';
	//$BookingID	    = addslashes(encryptor('decrypt',$_POST['eId']));
}else{
	//$BookingID	    = addslashes(encryptor('decrypt',$OrderLastInsertedID));
	$ResStatus		= 'Commit';
	}	
	  
	  
	  $Reference		  =	$row->reference;
	  
	  	$sqlBookerDetail = executeSQl("SELECT * FROM `mst_guest` WHERE id= '".addslashes($row->id_mst_guest)."'"); 
		 $rowBookerDetail 	 = $db->fetch_object2($sqlBookerDetail);
	  
	
		  $username=$HotelMappingresult->channel_user_name;		  
		 $password=$HotelMappingresult->channel_password;
		 
		  
// this variable will contain the XML sitemap that will be saved in $xmlfile
	$sec	='Roomstatushub'.date('Y-m-d');
 	$tokenGeneric = $sec.$_SERVER["SERVER_NAME"]; // It can be 'stronger' of course
    /* Encoding token */
     $token = hash('sha256', $tokenGeneric);

$idsxml = '<Envelope>
	<Header>
		<Username>'.$username.'</Username>
		<Password>'.trim($password).'</Password>
	</Header>
	<Body><OTA_HotelResNotifRQ EchoToken="'.$token.'" TimeStamp="'.$CreationTime.'" Version="5.000" ResStatus="'.$ResStatus.'">';

$idsxml .= '<POS>
        <Source>
          <RequestorID Type="22" />
          <BookingChannel Type="5" Primary="true" CrsCompanyId="'.$id_company_crs.'">
            <CompanyName>'.$CompanyName.'</CompanyName>
			<CompanyGroupName>'.$groupName.' </CompanyGroupName>
			<CompanyContact>                  
                    <PersonName>
                      <NamePrefix>'.$rowBookerDetail->title.'</NamePrefix>
                      <GivenName>'.$rowBookerDetail->first_name.'</GivenName>
                      <Surname> '.$rowBookerDetail->last_name.'</Surname>
                      <PhoneNumber> '.$rowBookerDetail->mobile.'</PhoneNumber>
                       <Email>'.$rowBookerDetail->email.'</Email>
                    </PersonName>  
				</CompanyContact>
          </BookingChannel>
        </Source>
      </POS>
	  <HotelReservations>
        <HotelReservation RoomStayReservation="true" CreateDateTime="'.$CreationTime.'" CreatorID="WelcomeHeritage" LastModifyDateTime="'.$CreationTime.'" LastModifierID="" ResStatus="'.$ResStatus.'"  BookingDateTime="'.$row->doc_date.'">
          <UniqueID Type="14" PmsReferenceID="'.$BookingID.'" PmsReference="'.$row->booking_no.'" CrsReferenceID="'.$row->other_reference.'"  CrsReference="'.$row->reference.'"/>
		  <BasicPropertyInfo ChainCode="'.$HotelName.'" HotelCode="'.$mappingHotelCode.'" BookingStatus="'.$BookingStatus.'" />';
	  $idsxml  .= '<TimeSpan Start="'.date('Y-m-d',strtotime($row->checkin)).'" End="'.date('Y-m-d',strtotime($row->checkout)).'" />';
	  
	  $sqlOtherChargesDetail 		= executeSql("Select * from `fo_reservations_addons_details` where id_fo_reservations='".addslashes($row->id)."' ");
		
		$NUmber 				=	num_rows($sqlOtherChargesDetail);
			if($NUmber >0){
				$idsxml .= '<OtherCharges>';
				$othernumber=1;
				while($rowOtherChargesDetail	= $db->fetch_object2($sqlOtherChargesDetail)){		  
		  
            $idsxml .= '<OtherCharge >
						<chargesUnits>'.$othernumber++.'</chargesUnits>
						<chargesid>'.$rowOtherChargesDetail->id_othercharges_detail.'</chargesid>
						<chargesDescription>'.$rowOtherChargesDetail->charges_description_id.'</chargesDescription>
						<chargesMealsType>'.$rowOtherChargesDetail->meals_type.'</chargesMealsType>
						<chargesPrice>'.$rowOtherChargesDetail->charges_price.'</chargesPrice>
						<chargesTaxPercentage>'.$rowOtherChargesDetail->charges_tax_percentage.'</chargesTaxPercentage>
						<chargesTaxTotal>'.$rowOtherChargesDetail->charges_tax_percentage.'</chargesTaxTotal>
						
						<ChargesType>'.$rowOtherChargesDetail->charges_method.'</ChargesType>
						<chargesNumberCount>'.$rowOtherChargesDetail->charges_numbercount.'</chargesNumberCount>						
						<chargesNumberOfDays>'.$rowOtherChargesDetail->charges_noofdays.'</chargesNumberOfDays>
						<chargesNetAmount>'.$rowOtherChargesDetail->charges_net.'</chargesNetAmount>
			</OtherCharge>';
				}
          $idsxml .= '</OtherCharges>';
				}
			
/*$bookingStatus		=	selectColumn(TBL_HTL_BOOKING_STATUS,'name'," WHERE id='".addslashes($row->booking_status)."'");
$paymentStatus		=	selectColumn(TBL_ORDER_STATE,'name'," WHERE id_lang='1' AND status='1' and id_order_state='".addslashes($row->payment_status)."'");
$bookingThrough		=	selectColumn(TBL_BOOKINGTHROUGH_MASTER,'name'," WHERE status='1' and id='".addslashes($row->booking_hrough)."'");

$Segment		=	selectColumn(TBL_SEGMENT_MASTER,'name'," WHERE status='1' and id='".addslashes($row->segment_id)."'");
$amendmentIn		=	selectColumn(TBL_AMENDMENT_REMARKS,'name'," WHERE status='1' and am_id='".addslashes($row->amendment_remarks_id)."'");
$bookingSource		=	selectColumn(TBL_BOOKINGSOURCE_MASTER,'name'," WHERE status='1' and id='".addslashes($row->id_booking_source)."'");
$specialInstructions=$row->requests;
$billingInstructions=$row->payment_reference;

	  $idsxml .= '<MiscellaneousInfo>';
	  $idsxml .= '<bookingThrough>'.$bookingThrough.'</bookingThrough>';
	  $idsxml .= '<Segment>'.$Segment.'</Segment>';
	  $idsxml .= '<amendmentIn>'.$amendmentIn.'</amendmentIn>';
	  $idsxml .= '<bookingSource>'.$bookingSource.'</bookingSource>';
	  
	  $idsxml .= '<bookingStatus>'.$bookingStatus.'</bookingStatus>';
	  $idsxml .= '<paymentStatus>'.$paymentStatus.'</paymentStatus>';
	  $idsxml .= '<specialInstructions>'.$specialInstructions.'</specialInstructions>';
	  $idsxml .= '<billingInstructions>'.$billingInstructions.'</billingInstructions>';
	  
	  $idsxml .= '</MiscellaneousInfo>';*/
	  
		  $idsxml .= '<RoomStays>';
$sqlOrderDetailArray = executeSql("Select * from `fo_reservations_details` where id_fo_reservations='".addslashes($row->id)."' ");
			
			if($DetailTotalNoRowsArray	=	mysqli_num_rows($sqlOrderDetailArray) >0 ){
			$DetailsArrayReservation=array();
				while($rowOrderDetailArray	= mysqli_fetch_object($sqlOrderDetailArray)){
					
$DetailsArrayReservation[$rowOrderDetailArray->id_mst_room_types][$rowOrderDetailArray->id_fo_rate_plan][$rowOrderDetailArray->adults_per_room][$rowOrderDetailArray->order_by_room]['id_mst_room_types']=$rowOrderDetailArray->id_mst_room_types;
$DetailsArrayReservation[$rowOrderDetailArray->id_mst_room_types][$rowOrderDetailArray->id_fo_rate_plan][$rowOrderDetailArray->adults_per_room][$rowOrderDetailArray->order_by_room]['id_fo_rate_plan']=$rowOrderDetailArray->id_fo_rate_plan;
$DetailsArrayReservation[$rowOrderDetailArray->id_mst_room_types][$rowOrderDetailArray->id_fo_rate_plan][$rowOrderDetailArray->adults_per_room][$rowOrderDetailArray->order_by_room]['adults_per_room']=$rowOrderDetailArray->adults_per_room;
$DetailsArrayReservation[$rowOrderDetailArray->id_mst_room_types][$rowOrderDetailArray->id_fo_rate_plan][$rowOrderDetailArray->adults_per_room][$rowOrderDetailArray->order_by_room]['child_without_bed']=$rowOrderDetailArray->child_without_bed;
$DetailsArrayReservation[$rowOrderDetailArray->id_mst_room_types][$rowOrderDetailArray->id_fo_rate_plan][$rowOrderDetailArray->adults_per_room][$rowOrderDetailArray->order_by_room]['DATA'][$rowOrderDetailArray->dated]['dated']=$rowOrderDetailArray->dated;


$DetailsArrayReservation[$rowOrderDetailArray->id_mst_room_types][$rowOrderDetailArray->id_fo_rate_plan][$rowOrderDetailArray->adults_per_room][$rowOrderDetailArray->order_by_room]['DATA'][$rowOrderDetailArray->dated]['tariff_price_per_day_per_room']+=$rowOrderDetailArray->tariff_price_per_day_per_room;
$DetailsArrayReservation[$rowOrderDetailArray->id_mst_room_types][$rowOrderDetailArray->id_fo_rate_plan][$rowOrderDetailArray->adults_per_room][$rowOrderDetailArray->order_by_room]['DATA'][$rowOrderDetailArray->dated]['tax_per_day_per_room']+=$rowOrderDetailArray->tax_per_day_per_room;
$DetailsArrayReservation[$rowOrderDetailArray->id_mst_room_types][$rowOrderDetailArray->id_fo_rate_plan][$rowOrderDetailArray->adults_per_room][$rowOrderDetailArray->order_by_room]['DATA'][$rowOrderDetailArray->dated]['checkin']=date('Y-m-d',strtotime($row->checkin));
$DetailsArrayReservation[$rowOrderDetailArray->id_mst_room_types][$rowOrderDetailArray->id_fo_rate_plan][$rowOrderDetailArray->adults_per_room][$rowOrderDetailArray->order_by_room]['DATA'][$rowOrderDetailArray->dated]['checkout']=date('Y-m-d',strtotime($row->checkout));


				}
			}
// print_r($DetailsArrayReservation);die;
 
 foreach($DetailsArrayReservation as $GroupListRoomType){
	 
	 foreach($GroupListRoomType as $GroupListRatePlan){
	 
	 foreach($GroupListRatePlan as $GroupListAdultPerRoom){
	// print_r($GroupListAdultPerRoom);
	 	foreach($GroupListAdultPerRoom as $GroupListDated){
		$booking_engine_id	=	selectColumn('fs_hotel_mapping','booking_engine_id'," WHERE hotel_id ='".$apiHotelID."' and channel_id='".$ChannelID."'");

$Hotel_Mapping_id	 =	selectColumn('fs_hotel_mapping','id'," WHERE hotel_id ='".$apiHotelID."' and channel_id='".$ChannelID."'");

//$HotelName			=	selectColumn('fs_hotel','name'," WHERE status='1' AND `id` = '".$apiHotelID."'");
//$HotelName = mysqli_real_escape_string($connNew,$HotelName);
	$Room_Mapping_id      =	selectColumn('fs_room_mapping','booking_engine_id'," WHERE hotel_mapping_id ='".$Hotel_Mapping_id."' and room_id='".$GroupListDated['id_mst_room_types']."'");

	$Rate_Mapping_id	=	selectColumn('fs_rate_mapping','booking_engine_id'," WHERE rate_id ='".$GroupListDated['id_fo_rate_plan']."' AND  id_hotel ='".$apiHotelID."' and channel_id='".$ChannelID."'");

	$RatePlanCategory 	=	selectColumn('fo_rate_plan','name'," WHERE `id` = '".$GroupListDated['id_fo_rate_plan']."'");
	
		$Tax_Inclusive_status	=	selectColumn(TBL_RATE_PLAN,'tax_detail'," WHERE `id` = '".$GroupListDated['id_fo_rate_plan']."'");	
			if($Tax_Inclusive_status==2){
				$Status_Tax_Inclusive_view='false';
			}else{
				$Status_Tax_Inclusive_view='true';	
			}			
			$idsxml  .= '<RoomStay>
              <RatePlans>
                <RatePlan RatePlanCode="'.$Rate_Mapping_id.'">
                  <RatePlanInclusions TaxInclusive="'.$Status_Tax_Inclusive_view.'" />
                </RatePlan>
              </RatePlans> 
      		';
			
		
					
			
$idsxml  .= '<RoomRates>
                <RoomRate RoomTypeCode="'.$Room_Mapping_id.'" NumberOfUnits="1" RatePlanCode="'.$Rate_Mapping_id.'" RatePlanCategory="'.$RatePlanCategory.'" MealPlan="'.$Rate_Mapping_id.'" Adult="'.$GroupListDated['adults_per_room'].'">
                  <Rates>';
				  
			
	
				
					
					foreach($GroupListDated as $GroupListDated2){	
				foreach($GroupListDated2 as $GroupListDated2){	
						
			
                   $idsxml  .= '<Rate EffectiveDate="'.date ("Y-m-d", strtotime($GroupListDated2['dated'])).'"  RateTimeUnit="Day" UnitMultiplier="1">
                      <Base AmountBeforeTax="'.$GroupListDated2['tariff_price_per_day_per_room'].'" AmountAfterTax="'.($GroupListDated2['tariff_price_per_day_per_room']+$GroupListDated2['tax_per_day_per_room']).'"  CurrencyCode="INR">
                        <Taxes Amount="'.round($GroupListDated2['tax_per_day_per_room']).'" CurrencyCode="INR" />
                      </Base>
                    </Rate>';
				}
		}
				
					
		
		$idsxml  .= '</Rates>
                </RoomRate>
              </RoomRates>';								
		//$charges_tax_valuew		=	selectColumn('fo_reservations_addons_details','sum(charges_tax_value)'," WHERE  `id_fo_reservations` = '".addslashes($row->id)."'");	  
				$idsxml  .= '<GuestCounts IsPerRoom="true">';
					$idsxml .='  <GuestCount AgeQualifyingCode="10" Count="'.$GroupListDated['adults_per_room'].'" />';
					$idsxml .='  <GuestCount AgeQualifyingCode="8" Count="'.$GroupListDated['child_without_bed'].'" />';
$idsxml .='</GuestCounts>';
				//$idsxml  .= '<TimeSpan Start="'.$checkin[0].'" End="'.$checkout[0].'" />';
				$idsxml  .= '<Total AmountBeforeTax="'.$AmountBeforeTax.'" AmountAfterTax="'.$AmountAfterTax.'" CurrencyCode="INR">
						<Taxes Amount="'.($TotalTax>0?round($TotalTax/$rowOrderDetail->room_quantity):0).'" CurrencyCode="INR" />
					</Total>';

					
					$idsxml  .= '<ResGuestRPHs>
						<ResGuestRPH RPH="1" />
					</ResGuestRPHs>
					
				</RoomStay>';
		}
	 		}
	 	}
	 }
 
 

 
 
 
 
 
	}
 				
$idsxml .='
  </RoomStays>';

$sqlGuestDetail = executeSQl("SELECT * FROM `mst_guest` WHERE  id= '".addslashes($row->id_mst_guest)."'"); 

		 $rowGuestDetail 	 = $db->fetch_object2($sqlGuestDetail);		 		 
		// $CountryName		=	selectColumn(TBL_COUNTRY_LANG,'name'," WHERE `id_country` = '".$rowGuestDetail->id_country."'"); 
		 if($rowGuestDetail->title!=''){
		    $guestPreix    ='<NamePrefix>'.$rowGuestDetail->title.'</NamePrefix>'; 
		     
		 }else{
		     
		     $guestPreix    ='<NamePrefix />';
		 }
		 
$idsxml .= '<ResGuests>';					
				$idsxml .= '<ResGuest ResGuestRPH="1">
					<Profiles>
						<ProfileInfo>
							<UniqueID Type="1" ID="'.addslashes($row->id_mst_guest).'" >
								<CompanyName Code="14" CodeContext="crs" />
							</UniqueID>
							<Profile ProfileType="1">
								<Customer>
									<PersonName>
										'.$guestPreix.'
										<GivenName>'.$rowGuestDetail->first_name.'</GivenName>
										<Surname>'.$rowGuestDetail->last_name.'</Surname>
									</PersonName>
									<Telephone PhoneNumber="'.$rowGuestDetail->mobile.'" PhoneTechType="1" />
									<Email>'.$rowGuestDetail->email.'</Email>
									<Address Type="1" Remark="Home">
										<AddressLine />
										<CityName />
										<PostalCode />
										<StateProv />
										<CountryName Code="'.$CountryName.'" />
									</Address>									
								</Customer>
							</Profile>
						</ProfileInfo>
					</Profiles>
				</ResGuest>';
				
			$idsxml .= '</ResGuests>';
		$idsxml .= '<ResGlobalInfo>';
	/*$idsxml .= '<CancelPenalties>
		<CancelPenalty PolicyCode="DCX" />
	</CancelPenalties>';*/
	$idsxml .= '<HotelReservationIDs>';
	if($UserBookingType=='OTA'){
		$idsxml .= '<HotelReservationID ResID_Value="96095" ResID_Type="13" ResID_Source="OTA" ForGuest="true" />';
	}else{
		if($ResStatus== 'Modify'){
			$resvalue=	str_replace('IDS','',$row->id_pms_reference);
			$resvalue=	str_replace($mappingHotelCode,'',$resvalue);
			$idsxml .= '<HotelReservationID ResID_Type="10" ResID_Value="'.$resvalue.'" ResID_Source="PMS" ForGuest="true"/>';
			}
		$idsxml .= '<HotelReservationID ResID_Type="14" ResID_Value="'.$BookingID.'" ResID_Source="CRS" ForGuest="true"/>';
	}
	$idsxml .= '</HotelReservationIDs>
</ResGlobalInfo>';


$idsxml .= ' </HotelReservation>
      </HotelReservations>
    </OTA_HotelResNotifRQ>
	</Body>
</Envelope>';	
 $idsxml = str_replace('&','and',$idsxml);

mysqli_query($connNew,"Insert into api_request set channel_id= '".$ChannelID."', type=1, request='".$idsxml."', id_hotel='".$apiHotelID."', id_order='".$BookingID."', booking_type='".$ResStatus."',booking_referance_id='".$row->other_reference."', date_created='".date('Y-m-d H:i:s')."'");
$lastinsertId	=	mysqli_insert_id($connNew);


 $url ="https://roomstatushub.in/crs/channel/apiRequestPmsToCrs.php";
		
				$headers = array(
				    "Content-type: text/xml",
				    "Content-length: " . strlen($idsxml),
				    "Connection: close",
				);

				$ch = curl_init(); 
				curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT, 60);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $idsxml);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

				$dataResp = curl_exec($ch);
	
			 $responsexml2 = simplexml_load_string($dataResp);
			 $responsexml = json_decode(json_encode($responsexml2), true);
	
	$PmsReferenceID	= $responsexml['UniqueID']['@attributes']['PmsReferenceID'];
	$PmsReference	=$responsexml['UniqueID']['@attributes']['PmsReference'];
	$CrsReferenceID	=$responsexml['UniqueID']['@attributes']['CrsReferenceID'];
	$CrsReference	= $responsexml['UniqueID']['@attributes']['CrsReference'];

	
	mysqli_query($connNew,"UPDATE fo_reservations SET  other_reference='".$CrsReferenceID."' , reference='".$CrsReference."' WHERE id='".$PmsReferenceID."' ");
	
	mysqli_query($connNew,"UPDATE api_request SET  response_ack='".$dataResp."'  WHERE id='".$lastinsertId."' AND channel_id = '1'  ");

}
//}
?>
    
	