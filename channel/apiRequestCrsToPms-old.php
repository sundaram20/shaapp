<?php 
include_once("../config/appConfig.php");
include_once("../functions/inventoryUpdateFunctions.php");


/////////////////////////////////////////////////////////////
$postData = file_get_contents('php://input');
//or below one//
//$postData = file_get_contents('BookingCRSToPMS.xml');
/////////////////////////////////////////////////////////

//$jsondeocde = json_decode($postData, true);
//echo '<pre>';print_r($jsondeocde);echo '</pre>';
//die;
$postData = str_replace("'", ' ', $postData);
$postData = str_replace('&','&amp;',  $postData);

$id_shop=2;
$channelId='1';


 $xml = simplexml_load_string($postData);

if($postData){
$xmlarray = json_decode(json_encode($xml), true);

	
		 $apiUserName	=	$xmlarray['Header']['Username'];
	     $apiPassword	=	$xmlarray['Header']['Password'];

 
$query =	mysqli_query($appConnect ,"select * from app_shops_api Where user_name='".$apiUserName."' and password='".$apiPassword."'");
$appNumberOfRows=	mysqli_num_rows($query);
if($appNumberOfRows=='1'){	
	$row=	mysqli_fetch_object($query);
	$id_app_shops	= $row->id_app_shops;
	include_once("../config/api_auto_loader.php");
	include_once("guestDocConfig.php");
	
	//API CODE START====================================================================================
	

	
	
	//executeSql("Insert into api_request set channel_id = '1' , request='".$postData."',date_created='".date('Y-m-d H:i:s')."',company_name='".$CompanyName."',response_status='Just Recieved',id_pms_response='0',failed_at='".date('Y-m-d H:i:s')."',count='1',booking_type='Commit' ,booking_referance_id='".$HotelReservations_ID."'");
	//////////////////////////////////////
	//	response needs to write - afsal	//
	//////////////////////////////////////
	
	
 //echo '<pre>';
 //echo '<pre>';//print_r($xmlarray);
//die;
	//echo '<pre>';print_r($xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['RoomStays']['Total']);
$id = $xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['UniqueID']['@attributes']['ID'];
	
$ReferenceID = $xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['UniqueID']['@attributes']['ReferenceID'];	
	//print_r($id); die;
$hotel_name = $xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['BasicPropertyInfo'] ['@attributes']['ChainCode'];

	 $mappingHotelCode	=	$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['BasicPropertyInfo']['@attributes']['HotelCode'];


$queryChannel = executeSql("SELECT * FROM fs_hotel_mapping WHERE booking_engine_id ='".$mappingHotelCode."' and channel_id='".$channelId."' ");
$resChannel = mysqli_fetch_object($queryChannel);



	$sql = "SELECT * FROM fs_hotel_mapping WHERE booking_engine_id ='".$mappingHotelCode."' and channel_id='".$channelId."'";
	$result = mysqli_query($connNew, $sql);
	if (mysqli_num_rows($result) == 0) {

		$data = [
			'EchoToken' => $ReferenceID,
			'PmsResID_Value' => $rese_id,
			'status' => 'Hotel Code not exist '.$mappingHotelCode,

			];
		
		

		executeSql("Insert into api_request set channel_id = '1' , request='".$postData."',date_created='".date('Y-m-d H:i:s')."',company_name='".$CompanyName."',booking_referance_id='".$ReferenceID."',response_status='Hotel Code not exist',id_pms_response='".$otherRefrenceId."',failed_at='".date('Y-m-d H:i:s')."',booking_type='Commit'");

		/*echo '<?xml version="1.0" encoding="UTF-8"?>
		<OTA_HotelResNotifRS xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xmlns:xsd="http://www.w3.org/2001/XMLSchema"
		xmlns="http://www.opentravel.org/OTA/2003/05" hotel_name="'.$hotel_name.'"  Version="1.0">
		<Success>"'.$mappingHotelCode.'" Code not exist'.$mappingHotelCode.'</Success>
		</OTA_HotelResNotifRS>';*/

		//////////////////////////////////////////////////
		//	mail sent function needs to write - afsal	//
		//////////////////////////////////////////////////

		exit;
	  } 
	  else{
		$row = mysqli_fetch_assoc($result);
		$hotel_id = $row["hotel_id"];  
	  }



$BookingResStatus 	= $xmlarray['Body']['OTA_HotelResNotifRQ']['@attributes']['ResStatus'];
$otherRefrenceId   =  @$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['UniqueID']['@attributes']['ID'];
$date_created	= $xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['@attributes']['BookingDateTime'];
$BookingDate	=     $xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['@attributes']['BookingDateTime'];



//CANCEL BOOKING===========================================	
if(@$xmlarray['Body']['OTA_HotelResNotifRQ']['@attributes']['ResStatus']=='Cancel'){ 
	
	//$otherRefrenceId = @$xmlarray['UniqueID']['@attributes']['ID'];
	$otherRefrenceId   =  @$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['UniqueID']['@attributes']['ID'];

	$sql = "SELECT id,booking_no,checkin,checkout FROM fo_reservations WHERE other_reference='$otherRefrenceId'";
	$result = mysqli_query($connNew, $sql);
//echo json_encode($result);
$row = mysqli_num_rows($result);
if($row > 0 ){
	$res = mysqli_fetch_assoc($result);
	$id_order = $res['id'];
}
else{
	$id_order = 0;
}

	if($id_order > 0 && $id_order!=''){

		 $updateSql = "UPDATE ".FO_RESERVATIONS." SET booking_status='4',last_modified='".date('Y-m-d H:i:s')."'  WHERE id='".$id_order."' AND other_reference='".$otherRefrenceId."' ";

		
		  if (mysqli_query($connNew, $updateSql)) {
			
			  $updateDetailsSql = "UPDATE fo_reservations_details SET id_mst_room_no_allocation='0'  WHERE id_fo_reservations='".$id_order."'";
			  mysqli_query($connNew, $updateDetailsSql);
			 $checkinInv	= date('Y-m-d',strtotime($res['checkin']));
		     $checkoutInv	= date('Y-m-d',strtotime($res['checkout']));
			if($id_app_shops!='57'){
				updateOTA('1', $checkinInv,$checkoutInv,$connNew);
			}
			  $data = [
			'EchoToken' => $ReferenceID,
			'CrsResID_Value' => $otherRefrenceId,
			'PmsResID_Value' => $id_order,
			'PmsBooking_no' => $res['booking_no'],				  
    		'status' => 'cancel success',

			];
	
		$json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
echo $json; 
			
			executeSql("Insert into api_request set channel_id = '1' , request='".$postData."',date_created='".date('Y-m-d H:i:s')."',company_name='".$CompanyName."',booking_referance_id='0',response_status='cancel success',id_pms_response='".$otherRefrenceId."',failed_at='".date('Y-m-d H:i:s')."',booking_type='cancel'");
			//////////////////////////////////////////
			//	response needs to be write - afsal	// - Done!
			//////////////////////////////////////////

			/*echo '<?xml version="1.0" encoding="UTF-8"?>
				<OTA_HotelResNotifRS xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
				xmlns:xsd="http://www.w3.org/2001/XMLSchema"
				xmlns="http://www.opentravel.org/OTA/2003/05" EchoToken="'.$referenceID.'" CrsResID_Value="'.$id_order.'"  PmsResID_Value="'.$otherRefrenceId.'" TimeStamp="'.date('Y-m-d H:i:s').'" Version="1.0">
				<Success>Success</Success>
				</OTA_HotelResNotifRS>';*/
			 
		}else{
			 $data = [
			'EchoToken' => $ReferenceID,
			'CrsResID_Value' => $otherRefrenceId,
			'PmsResID_Value' => $rese_id,
    		'status' => 'cancel Failed',

			];
	
		$json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
echo $json; 
			executeSql("Insert into api_request set channel_id = '1' , request='".$postData."',date_created='".date('Y-m-d H:i:s')."',company_name='".$CompanyName."',booking_referance_id='0',response_status='cancel failed',id_pms_response='".$otherRefrenceId."',failed_at='".date('Y-m-d H:i:s')."',booking_type='cancel'");
			/*echo '<?xml version="1.0" encoding="UTF-8"?>
						<OTA_HotelResNotifRS xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
						xmlns:xsd="http://www.w3.org/2001/XMLSchema"
						xmlns="http://www.opentravel.org/OTA/2003/05" EchoToken="'.$referenceID.'" CrsResID_Value="'.$id_order.'"  PmsResID_Value="'.$otherRefrenceId.'" TimeStamp="'.date('Y-m-d H:i:s').'" Version="1.0">
						<Success>Failed</Success>
						</OTA_HotelResNotifRS>';*/
			//////////////////////////////////////
			//	cancel fail condition - afsal	// - Done!
			//////////////////////////////////////
		}
		
	}else{

			$data = [
			'EchoToken' => $ReferenceID,
			'CrsResID_Value' => $otherRefrenceId,
			'PmsResID_Value' => $rese_id,
			'status' => 'cancel Request, but reservation does not exist',

			];
			$json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
echo $json;

		executeSql("Insert into api_request set channel_id = '1' , request='".$postData."',date_created='".date('Y-m-d H:i:s')."',company_name='".$CompanyName."',booking_referance_id='0',response_status='cancel Request, but reservation does not exist',id_pms_response='".$otherRefrenceId."',failed_at='".date('Y-m-d H:i:s')."',booking_type='cancel'");
			/*echo '<?xml version="1.0" encoding="UTF-8"?>
						<OTA_HotelResNotifRS xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
						xmlns:xsd="http://www.w3.org/2001/XMLSchema"
						xmlns="http://www.opentravel.org/OTA/2003/05" EchoToken="'.$referenceID.'" CrsResID_Value="'.$id_order.'"  PmsResID_Value="'.$otherRefrenceId.'" TimeStamp="'.date('Y-m-d H:i:s').'" Version="1.0">
						<Success>cancel Request, but reservation does not exist</Success>
						</OTA_HotelResNotifRS>';*/
			//////////////////////////////////////////////////////////////////
			//	cancel request but no reservation exist condition - afsal	// - Done!
			//////////////////////////////////////////////////////////////////
	}
	
	exit;

}
//CANCEL BOOKING===========================================



 $company 			  	  =  $xmlarray['reservations']['reservation']['0']['company'];



//$queryCompanyId = executeSql("SELECT * FROM fs_company_mapping WHERE booking_engine_name like '%".$company."%' and //channel_id='".$channelId."' ");
//$resCompanyId = mysqli_fetch_object($queryCompanyId);



$CheckGuestArray	=	array_key_exists('0',$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['ResGuests']['ResGuest']);
 
	if($CheckGuestArray	==1){
		
		$CheckGuestArray=$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['ResGuests']['ResGuest'];
		
		}else{
			
				$CheckGuestArray	=	array();
				$CheckGuestArray[]	=	$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['ResGuests']['ResGuest'];	
					
			}
		//print_r($CheckGuestArray);	
 foreach($CheckGuestArray as $countGuest=>$dataGuest){
	 
	$NamePrefix 		=  $CheckGuestArray[0]['Profiles']['ProfileInfo']['Profile']['Customer']['PersonName']['NamePrefix'];
	$first_name1 		=  $CheckGuestArray[0]['Profiles']['ProfileInfo']['Profile']['Customer']['PersonName']['GivenName'];
	// if($CheckGuestArray[0]['Profiles']['ProfileInfo']['Profile']['Customer']['PersonName']['MiddleName']!=''){
	// //$mname	=	' '.$CheckGuestArray[0]['Profiles']['ProfileInfo']['Profile']['Customer']['PersonName']['MiddleName'];
	// }
	  if($CheckGuestArray[0]['Profiles']['ProfileInfo']['Profile']['Customer']['PersonName']['GivenName']){
	$first_name1 		=  $CheckGuestArray[0]['Profiles']['ProfileInfo']['Profile']['Customer']['PersonName']['GivenName'];
	 
	 }else{
	$first_name1 		=  '';
	 
	 }
	 if($CheckGuestArray[0]['Profiles']['ProfileInfo']['Profile']['Customer']['PersonName']['Surname']){
	$last_name 		=  $CheckGuestArray[0]['Profiles']['ProfileInfo']['Profile']['Customer']['PersonName']['Surname'];
	 
	 }else{
	$last_name 		=  '';
	 
	 }
	$first_name 		=  $first_name1;//.$mname;
	//$last_name 		=  $CheckGuestArray[0]['Profiles']['ProfileInfo']['Profile']['Customer']['PersonName']['Surname'];
	if($CheckGuestArray[0]['Profiles']['ProfileInfo']['Profile']['Customer']['Email']){
	$email 		=  $CheckGuestArray[0]['Profiles']['ProfileInfo']['Profile']['Customer']['Email'];
	}else{
		$email 		='';
		}
	$telephone 		=  $CheckGuestArray[0]['Profiles']['ProfileInfo']['Profile']['Customer']['Telephone']['@attributes']['PhoneNumber'];
	
	$guest_reg_date = date('Y-m-d');
	$first_name = $first_name1;
	$last_name = $last_name;
	$email = $email;
	$primary_mobile = $telephone;
	$date_created = $date_created;
	
	
 $sqlGuestTitle = "SELECT id FROM ".TBL_ATTRIBUTES." WHERE field_value='".$NamePrefix."' and table_name ='title' AND status='1'";
$resultGuestTitle = mysqli_query($connNew, $sqlGuestTitle);
$rowGuestTitle= mysqli_num_rows($resultGuestTitle);
if($rowGuestTitle > 0 ){
	$resGuestTitle = mysqli_fetch_assoc($resultGuestTitle);
	$GuestTitle = $resGuestTitle['id'];
}
	 

	/*$sql = "SELECT id FROM mst_guest WHERE first_name='$first_name'";
$result = mysqli_query($connNew, $sql);
//echo json_encode($result);
$row = mysqli_num_rows($result);
if($row > 0 ){
	$res = mysqli_fetch_assoc($result);
	
	$guest_id = $res['id'];
}
else{*/
	
	$guest_reg_no	=addslashes($guestResultConfig['prefix']).addslashes($guestResultConfig['doc_no']).addslashes($guestResultConfig['suffix']);
	
				 
	
	
	//print_r($guestResultConfig);die;
	if(@$xmlarray['Body']['OTA_HotelResNotifRQ']['@attributes']['ResStatus']!='Modify'){ 
	$sql = "INSERT INTO mst_guest (guest_reg_date,id_shop,first_name,last_name,email,primary_mobile,date_created,doc_type,doc_no,id_mst_doc_type_configuration,guest_reg_no,status,id_mst_attributes_title) VALUES ('".$guest_reg_date."','".$id_shop."','".$first_name."','".$last_name."','".$email."','".$primary_mobile."','".$date_created."','501','".$guestResultConfig['doc_no']."','".$guestResultConfig['id_mst_doc_type_configuration']."','".$guest_reg_no."','1','".$GuestTitle."')";
	if (mysqli_query($connNew, $sql)) {
		$guest_id = mysqli_insert_id($connNew);
		//echo "New Guest created successfully.";
	  } else {
		//echo "Error: " . $sql . "<br>" . mysqli_error($connNew);
	  }
	}else{
		$updateInventory = executeSql("UPDATE  `mst_guest`  SET 
								`id_mst_attributes_title`	='".($GuestTitle)."'								
								where 
								`id`			='".$guest_id."'");
		
		}
//}
	//first_name = '".$first_name."' , last_name='".$last_name."', email='".$email."', mobile='".$telephone."',id_shop='".$id_shop."',status='1'");
 }
$sql = "SELECT * FROM mst_hotels WHERE name='".$hotel_name."'";
$result = mysqli_query($connNew, $sql);

if (mysqli_num_rows($result) > 0) {
  // output data of each row
  while($row = mysqli_fetch_assoc($result)) {
   		$hotel_code = $row["hotel_code"];
  }
}



//echo '11cc1t';die;

$id_configuration_type = "27";
$doc_date = date('Y-m-d');
$mdoc_no = $hotel_code.'/'.($max_id+1);
$doc_type = "801";
$id_shop_group = "1";
$id_mst_shop = "2";
$id_mst_country_lang = "0";
$id_mst_hotels = $hotel_id;


	$id_doc_type_configuration='801';

	include_once("../frontoffice/functions/function.php");
 	$id_doc_type='801'; //DOCUMENT TYPE FOLIO 803
	$doc_table_name=FO_RESERVATIONS;
	$date = date('Y-m-d');
	$id_subsection='1';
	$docConfig	=	docTypeConfig($id_doc_type,$date,$id_subsection,$doc_table_name,$connNew,$id_shop=2);//echo 'qwqweww12q';die;
	//print_r($docConfig);
	//die;


$mappingHotelCode	=	$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['RoomStays']['RoomStay'][0]['BasicPropertyInfo']['@attributes']['HotelCode'];

$CompanyName = $xmlarray['Body']['OTA_HotelResNotifRQ']['POS']['Source']['BookingChannel']['CompanyName'];
$CompanyGroupName = $xmlarray['Body']['OTA_HotelResNotifRQ']['POS']['Source']['BookingChannel']['CompanyGroupName'];
	
$CrsCompanyId = $xmlarray['Body']['OTA_HotelResNotifRQ']['POS']['Source']['BookingChannel']['@attributes']['CrsCompanyId'];

 $sqlCompanyGroup = "SELECT id FROM ".TBL_ATTRIBUTES." WHERE field_value='".$CompanyGroupName."' and status='1' and id_shop='".addslashes($id_shop)."' AND table_name='company_group'";
$resultCompanyGroup = mysqli_query($connNew, $sqlCompanyGroup);
$rowCompanyGroup= mysqli_num_rows($resultCompanyGroup);
if($rowCompanyGroup > 0 ){
	$resCompanyGroup = mysqli_fetch_assoc($resultCompanyGroup);
	$id_mst_attributes_company_group = $resCompanyGroup['id'];
}else{
	if(@$xmlarray['Body']['OTA_HotelResNotifRQ']['@attributes']['ResStatus']!='Modify'){
	$sqlGroup = "INSERT INTO ".TBL_ATTRIBUTES." (field_value,status,id_shop,table_name) VALUES ('".$CompanyGroupName."','1','".$id_shop."','company_group')";
	if (mysqli_query($connNew, $sqlGroup)) {
		$id_mst_attributes_company_group = mysqli_insert_id($connNew);
		//echo "New Company created successfully. Last inserted ID is: ";
	  } else {
		//echo "Error: " . $sql . "<br>" . mysqli_error($connNew);
		


	  }
	}
}



$sql = "SELECT id FROM mst_company WHERE name='".$CompanyName."'";
$result = mysqli_query($connNew, $sql);
//echo json_encode($result);
$row = mysqli_num_rows($result);
if($row > 0 ){
	$res = mysqli_fetch_assoc($result);
	$company_id = $res['id'];
	
	$insertCompanyGrid = "UPDATE mst_company SET `id_company_crs`='".$CrsCompanyId."' ";
	$insertCompanyGrid .=" WHERE id='".$company_id."'";
	mysqli_query($connNew,$insertCompanyGrid);
}
else{
	//if(@$xmlarray['Body']['OTA_HotelResNotifRQ']['@attributes']['ResStatus']!='Modify'){
	$sql = "INSERT INTO mst_company (name,status,id_mst_attributes_company_group,id_shop,id_company_crs) VALUES ('".$CompanyName."','1','".$id_mst_attributes_company_group."','".$id_shop."','".$CrsCompanyId."')";
	if (mysqli_query($connNew, $sql)) {
		$company_id = mysqli_insert_id($connNew);
		//echo "New Company created successfully. Last inserted ID is: ";
	  } else {
		//echo "Error: " . $sql . "<br>" . mysqli_error($connNew);
	  }
	//}
}
$id_mst_company = $company_id;

//Company Contact==Start================================================================
$CompanyContactname = $xmlarray['Body']['OTA_HotelResNotifRQ']['POS']['Source']['BookingChannel']['CompanyContact']['PersonName']['GivenName'];
$Surname = $xmlarray['Body']['OTA_HotelResNotifRQ']['POS']['Source']['BookingChannel']['CompanyContact']['PersonName']['Surname'];
$NamePrefix = $xmlarray['Body']['OTA_HotelResNotifRQ']['POS']['Source']['BookingChannel']['CompanyContact']['PersonName']['NamePrefix'];
$PhoneNumber = $xmlarray['Body']['OTA_HotelResNotifRQ']['POS']['Source']['BookingChannel']['CompanyContact']['PersonName']['PhoneNumber'];
//$Email = $xmlarray['Body']['OTA_HotelResNotifRQ']['POS']['Source']['BookingChannel']['CompanyContact']['PersonName']['Email'];
 $id_crs_company_contact = $xmlarray['Body']['OTA_HotelResNotifRQ']['POS']['Source']['BookingChannel']['CompanyContact']['@attributes']['CrsCompanyContactId'];	
	
if($xmlarray['Body']['OTA_HotelResNotifRQ']['POS']['Source']['BookingChannel']['CompanyContact']['PersonName']['Email']){
	$Email = $xmlarray['Body']['OTA_HotelResNotifRQ']['POS']['Source']['BookingChannel']['CompanyContact']['PersonName']['Email'];
	}else{
		$Email ='';
		}	
	
if($xmlarray['Body']['OTA_HotelResNotifRQ']['POS']['Source']['BookingChannel']['CompanyContact']['PersonName']['PhoneNumber']){
	$PhoneNumber = $xmlarray['Body']['OTA_HotelResNotifRQ']['POS']['Source']['BookingChannel']['CompanyContact']['PersonName']['PhoneNumber'];
	}else{
		$PhoneNumber ='';
		}		
	

//$id_mst_attributes_title	=	selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$rowRoomNumbers->id_mst_guest."'");				
	$id_mst_attributes_title=selectColumn(TBL_ATTRIBUTES,'id'," WHERE  status = '1' and `table_name` = 'title' AND field_value= '".$NamePrefix."'"); 
	
	
  $sqlCompanyContact = "SELECT id FROM mst_company_contacts WHERE id_crs_company_contact='".$id_crs_company_contact."'";
$resultCompanyContact = mysqli_query($connNew, $sqlCompanyContact);
//echo json_encode($result);
$rowCompanyContact= mysqli_num_rows($resultCompanyContact);
if($rowCompanyContact > 0 ){
	$resCompanyContact = mysqli_fetch_assoc($resultCompanyContact);
	$CompanyContact_id = $resCompanyContact['id'];
	$id_company_contacts= $resCompanyContact['id'];
	
	/*executeSql("UPDATE  `mst_company_contacts`  SET 
								`id_crs_company_contact`	='".$id_crs_company_contact."'							
								where 
								`id`			='".$id_company_contacts."'");*/
	
	
	
}
else{
	//if(@$xmlarray['Body']['OTA_HotelResNotifRQ']['@attributes']['ResStatus']!='Modify'){
	$sqlContact = "INSERT INTO mst_company_contacts (first_name,status,id_mst_attributes_company_group,id_shop,id_mst_company,id_shop_group,email,last_name,primary_mobile,id_mst_attributes_title,id_crs_company_contact) VALUES ('".$CompanyContactname."','1','".$id_mst_attributes_company_group."','".$id_shop."','".$id_mst_company."','1','".$Email."','".$Surname."','".$PhoneNumber."','".$id_mst_attributes_title."','".$id_crs_company_contact."')";         
	if (mysqli_query($connNew, $sqlContact)) {
		$id_company_contacts = mysqli_insert_id($connNew);
		//echo "New Company created successfully. Last inserted ID is: ";
	  } else {
		//echo "Error: " . $sqlContact . "<br>" . mysqli_error($connNew);
	  }
	//}
}
//Company Contact= END=================================================================
	
	
$other_reference = $otherRefrenceId;
$CheckRoomArray	=	array_key_exists('0',$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['RoomStays']['RoomStay']);

	if($CheckRoomArray	==1){
		
		$multiRoom=$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['RoomStays']['RoomStay'];
		
		}else{
			
				$multiRoom	=	array();
				$multiRoom[]	=	$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['RoomStays']['RoomStay'];	
					
			}
			$total_adults = 0;
			
			$roomRates=array();
			$subtotalData=array();
			foreach($multiRoom as $countstart=>$datarooms){
				$hotel_idMapping 		=  $multiRoom[0]['BasicPropertyInfo']['@attributes']['HotelCode'];
				$checkout=	date('Y-m-d',	strtotime($multiRoom[0]['TimeSpan']['@attributes']['End']));
				$checkin=$multiRoom[0]['TimeSpan']['@attributes']['Start'];
				
				
$datediff = (int)$checkout - (int)$checkin;
$daysNew =  abs((strtotime($checkin) - strtotime($checkout))/ 86400 );
if($daysNew == '0'){
	$no_of_days = '1';
}else {
	$no_of_days = $daysNew;
}

 //print_r($datarooms['RoomRates']);

				$adults[]			= 	$datarooms['RoomRates']['RoomRate']['@attributes']['Adult'];
				$roomRates = $datarooms['RoomRates'];
				
//print_r($roomRates);
				$subtotal=array();
				$NumberOfUnits='';
				foreach($roomRates as $ArrayKeys=>$roomRatesArray){ //print_r($roomRatesArray);
				//echo '<br>KEYS===='.$ArrayKeys;
				 $NumberOfUnits=$roomRatesArray['@attributes']['NumberOfUnits'];
				 $CheckRateArray	=	array_key_exists('0',$roomRatesArray['Rates']['Rate']);
					//print_r($roomRates);
					if($CheckRateArray	==1){
						//print_r($roomRatesArray);
						foreach($roomRatesArray['Rates']['Rate'] as $IncRatesArray=>$roomRatesArrayValue){
							
							//echo '====='.$IncRatesArray;
							
						
						//print_r($roomRatesArrayValue);
						
						
					$subtotal[]= $roomRatesArrayValue['Base']['@attributes']['AmountBeforeTax'];					
					$total_tax[]= $roomRatesArrayValue['Base']['Taxes']['@attributes']['Amount'];
					$subAmountReceived= $roomRatesArrayValue['Base']['@attributes']['AmountReceived'];
						}
					}else{
						$subtotal[]= $roomRatesArray['Rates']['Rate']['Base']['@attributes']['AmountBeforeTax'];					
					    $total_tax[]= $roomRatesArray['Rates']['Rate']['Base']['Taxes']['@attributes']['Amount'];
						$subAmountReceived= $roomRatesArray['Rates']['Rate']['Base']['@attributes']['AmountReceived'];
						
						}
				}
				$subtotalData[]= array_sum($subtotal)*$NumberOfUnits;
				
			//	

			}
			
			 $subtotal=array_sum($subtotalData);
			
			
			//echo count($roomRates);
			//echo json_encode($total_tax);
			 $amount_received=$subAmountReceived;//die;
			$total_adults = array_sum($adults);
			$total_rooms = count($multiRoom);
			$subtotal = $subtotal;
			$total_tax = array_sum($total_tax)*$NumberOfUnits;
			$net_booking_amount = $subtotal + $total_tax;
			$balance = round($net_booking_amount-$amount_received);
			
			$id_mst_guest=$guest_id;
			$date_created = date('Y-m-d');
			$last_modified = date('Y-m-d');
			$booking_date = date('Y-m-d');
			$last_modified_by = 9;
			
			$query =	mysqli_query($connNew ,"select * From ".FO_RESERVATIONS." Where other_reference='".$other_reference."'");
 			$appNumberOfRows=	mysqli_num_rows($query);
			
			if($appNumberOfRows>'0'){ 
				//if(@$xmlarray['Body']['OTA_HotelResNotifRQ']['@attributes']['ResStatus']=='Modify'){ 
			//if(@$xmlarray['Body']['OTA_HotelResNotifRQ']['@attributes']['ResStatus']=='Modify'){ 
				//echo 'Modify'; die;
				

//executeSql("Insert into api_request set channel_id = '1' , request='".$postData."',date_created='".date('Y-m-d H:i:s')."',company_name='0',booking_referance_id='0',response_status='0',id_pms_response='0',failed_at='".date('Y-m-d H:i:s')."',booking_type='modify'");

				//////////////////////////////////////////////
				//	Modify response needs to send - afsal	//
				//////////////////////////////////////////////

				include_once("apiRequestCrsToPmsModify-old.php");
				exit;
			}
			else	
			{
					
			$query =	mysqli_query($connNew ,"select * From ".FO_RESERVATIONS." Where other_reference='".$other_reference."'");
 $appNumberOfRows=	mysqli_num_rows($query);
if($appNumberOfRows=='0'){	
	//$row=	mysqli_fetch_object($query);																									
			
//Get API Details//
	
		  
	  $bookingThrough	=$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['MiscellaneousInfo']['bookingThrough'];
	  $Segment	=$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['MiscellaneousInfo']['Segment'];
	  $amendmentIn	=$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['MiscellaneousInfo']['amendmentIn'];
	  $bookingSource	=$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['MiscellaneousInfo']['bookingSource'];
	  $bookingStatus	=$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['MiscellaneousInfo']['bookingStatus'];
	  
	  $paymentStatus	=$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['MiscellaneousInfo']['paymentStatus'];
	 // $specialInstructions	=$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['MiscellaneousInfo']['specialInstructions'];
	  $billingInstructions	=$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['MiscellaneousInfo']['billingInstructions'];
	  
	 // $internal_remarks	=$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['MiscellaneousInfo']['HotelRemarks'];
	
	$internal_remarks = $xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['MiscellaneousInfo']['HotelRemarks'];
if (is_array($internal_remarks)) {
    $internal_remarks = $internal_remarks[0] ?? ''; // Access first element or set as empty if not present
}
$internal_remarks= empty($internal_remarks) ? "" : $internal_remarks;	
	
		
		$specialInstructions = $xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['MiscellaneousInfo']['specialInstructions'];
if (is_array($specialInstructions)) {
    $specialInstructions = $specialInstructions[0] ?? ''; // Access first element or set as empty if not present
}
$specialInstructions= empty($specialInstructions) ? "" : $specialInstructions;
	  
	  //$booking_status		=	selectColumn(TBL_ATTRIBUTES,'id'," WHERE `table_name` = 'bookingstatus' and field_value='".addslashes($bookingStatus)."'");
	$booking_status		=	selectColumn('fo_booking_status','id'," WHERE name='".addslashes($bookingStatus)."'");
  
	  
	  
$res_bookingthrough		=	selectColumn(TBL_ATTRIBUTES,'id'," WHERE `table_name` = 'booking_through' and field_value='".addslashes($bookingThrough)."'");
$res_segment		=	selectColumn(TBL_ATTRIBUTES,'id'," WHERE `table_name` = 'segment' and  status='1' and field_value='".addslashes($Segment)."'");
$id_mst_attributes_amendment		=	selectColumn(TBL_ATTRIBUTES,'id'," WHERE `table_name` = 'amendment_in' and status='1' and field_value='".addslashes($amendmentIn)."'");

$res_bookingsourcee =	selectColumn(TBL_ATTRIBUTES,'id'," WHERE `table_name` = 'booking_source' and status='1' and field_value='".addslashes($bookingSource)."'");
	
	
	$pay_sts=	selectColumn(TBL_ATTRIBUTES,'id'," WHERE `table_name` = 'payment_status' and status='1' and field_value='".addslashes($paymentStatus)."'");

/*$paymentStatus		=	selectColumn(TBL_ATTRIBUTES,'name'," WHERE id_lang='1' AND status='1' and id_order_state='".addslashes($row->payment_status)."'");
$bookingThrough		=	selectColumn(TBL_ATTRIBUTES,'name'," WHERE status='1' and id='".addslashes($row->booking_hrough)."'");

$Segment		=	selectColumn(TBL_ATTRIBUTES,'name'," WHERE status='1' and id='".addslashes($row->segment_id)."'");
$amendmentIn		=	selectColumn(TBL_ATTRIBUTES,'name'," WHERE status='1' and am_id='".addslashes($row->amendment_remarks_id)."'");
$bookingSource		=	selectColumn(TBL_ATTRIBUTES,'name'," WHERE status='1' and id='".addslashes($row->id_booking_source)."'");
*/		
				$sql = "INSERT INTO ".FO_RESERVATIONS."  SET
				
				
			`id_mst_shops`='2',
			`id_shop_group`='1',
			`id_mst_country_lang`='0',
			`id_cart`='0',
			`id_mst_currency_base`='0',
			`id_mst_currency_transaction`='0',
			`conversion_rate`='1',
			`sub_total`='".$subtotal."',
			`net_booking_amount`='".$net_booking_amount."',
			`booking_confirm_date`='".$confirm."',
			`tentative_hold_date`='".$bk_stsa."',
			`other_reference`='".$other_reference."',
			`reference`='".$ReferenceID."',
			`id_mst_attributes_cancellation`='".$res_cancellation."',
			`id_mst_attributes_amendment`='".$id_mst_attributes_amendment."',
			`no_of_days`='".$no_of_days."',
			`booking_no`='".addslashes($docConfig['prefix']).addslashes($docConfig['po_no']).addslashes($docConfig['suffix'])."',
			`id_doc_type_configuration`	='".addslashes($docConfig['id_doc_type_configuration'])."',
			`doc_no`='".addslashes($docConfig['po_no'])."',
			`doc_date`='".date('Y-m-d',strtotime($date))."',
			`mdoc_no`=	'".addslashes($docConfig['prefix']).addslashes($docConfig['po_no']).addslashes($docConfig['suffix'])."',
			`doc_type` = '".addslashes($id_doc_type)."',
			`id_mst_hotels`='".$id_mst_hotels."',
			`id_mst_guest`='".$id_mst_guest."',
			`id_mst_attributes_company_group`='".$id_mst_attributes_company_group."',
			`id_mst_company`='".$id_mst_company."',
			`id_mst_company_contacts`='".$id_company_contacts."',
			`id_mst_attributes_payment_status`='".$pay_sts."',
			`booking_status`='".$booking_status."',
			`room_tariff_price`='".$totalprice."',
			`discount`='".$dis."',
			`total_addon_price`='".$total_addon_amount."',
			`total_tax`='".$total_tax."',
			`amount_received`='".$amount_received."',
			`balance`='".$net_booking_amount."',
			`booking_date`='".date('Y-m-d',$BookingDate)."',
			`checkin`='".$checkin."',
			`checkout`='".$checkout."',
			`arrival_time`='".$res_arrivingtime."',
			`arrival_from`='".$res_arrivingfrom."',
			`departing_to`='".$pickupd."',
			`pickup`='".$res_pickuprequired."',
			`pickup_details`='".$pickup_details."',
			`id_mst_attributes_mode_of_travel`='".$pickupa."',
			`special_requests`='".$spe_rqt."',
			`res_special_notes`='".$specialInstructions."',
			
			`res_internal_remarks`='".$internal_remarks."',
			`id_mst_attributes_segments`='".$res_segment."',
			`id_mst_attributes_booking_source`='".$res_bookingsourcee."',
			`id_mst_attributes_booking_through`='".$res_bookingthrough."',						
			`food_plan_price`='".$total_food_price."',
			`extra_bed_price`='".$total_extrabed_price."',
			`total_adults`='".$total_adults."',
			`total_child_with_bed`='".$total_extrabed_price."',
			`total_child_without_bed`='".$total_child_without_bed."',
			
			`date_created` = '".currenDateTime()."',
			`created_by` = '".$last_modified_by."',
			`last_modified` = '".currenDateTime()."',
			`last_modified_by` = '".$last_modified_by."' ";
					//echo $sql;die;
					
			
			
				
				
				//echo $sql; //die;
			

if (mysqli_query($connNew, $sql)) {
	$rese_id = mysqli_insert_id($connNew);

	/* $data = [
			'EchoToken' => $ReferenceID,
			'CrsResID_Value' => $otherRefrenceId,
			'PmsResID_Value' => $rese_id,
    		'status' => 'success',

			];
	
		$json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
echo $json; 

	executeSql("Insert into api_request set channel_id = '1' , request='".$postData."',date_created='".date('Y-m-d H:i:s')."',company_name='".$CompanyName."',booking_referance_id='0',response_status='success',id_pms_response='".$otherRefrenceId."',failed_at='".date('Y-m-d H:i:s')."',booking_type='commit'");
	//afsal
	//echo "New Order Inserted successfully. Last inserted ID is: ".$rese_id;
  } else {
	//echo "Error: " . $sql . "<br>" . mysqli_error($connNew);
	 $data = [
			'EchoToken' => $ReferenceID,
			'CrsResID_Value' => $otherRefrenceId,
			'PmsResID_Value' => $rese_id,
			'Error' => mysqli_error($connNew),
    		'status' => 'Booking Failed',

			];
	
		$json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
echo $json; 

	executeSql("Insert into api_request set channel_id = '1' , request='".$postData."',date_created='".date('Y-m-d H:i:s')."',company_name='".$CompanyName."',booking_referance_id='0',response_status='Booking failed',id_pms_response='".$otherRefrenceId."',failed_at='".date('Y-m-d H:i:s')."',booking_type='commit'");*/

  }
	$unique_codes = 'xml_'.rand();
	
	
	
	$kkk	=	array_key_exists('0',$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['RoomStays']['RoomStay']);

	if($kkk	==1){
		
		$RoomStayCount=$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['RoomStays']['RoomStay'];
		$RoomStayCount1=$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['RoomStays']['RoomStay'];
		}else{
				$RoomStayCount	=	array();
				$RoomStayCount[]	=	$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['RoomStays']['RoomStay'];	
				$RoomStayCount1[]	=	$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['RoomStays']['RoomStay'];	
			}
			
							
		
			
			

foreach($RoomStayCount as $stay=>$k){
	
	 $TotalRoomS['NumberOfUnits']	+=	$RoomStayCount[$stay]['RoomRates']['RoomRate']['@attributes']['NumberOfUnits'];
	 $TotalRoomS['RatePlanCode']	=	$RoomStayCount[$stay]['RoomRates']['RoomRate']['@attributes']['RatePlanCode'];
	 $TotalRoomS['RoomTypeCode'] 	=   $RoomStayCount[$stay]['RoomRates']['RoomRate']['@attributes']['RoomTypeCode'];
	 
	 $SetTypeCode					=	 $TotalRoomS['RoomTypeCode'];	 
	 $SetTypeCodeNew[$SetTypeCode]['NumberOfUnits']	+=$RoomStayCount[$stay]['RoomRates']['RoomRate']['@attributes']['NumberOfUnits'];
}




//print_r($RoomStayCount);
	
	//echo $y=1;die;
	foreach($RoomStayCount as $counts=>$datarooms){
	
	
	//print_r($datarooms['RoomRates']['RoomRate']['Rates']);
	
	
	$roomRateArrayCheck	=	array_key_exists('0',$datarooms['RoomRates']['RoomRate']['Rates']['Rate']);
	if($roomRateArrayCheck==1){
	
	$NewData	=	$datarooms['RoomRates']['RoomRate']['Rates']['Rate'][0]['Base'];
		 $adult			= 	$datarooms['RoomRates']['RoomRate']['@attributes']['Adult'];
		 $RatePlanCode = $datarooms['RoomRates']['RoomRate']['@attributes']['RatePlanCode'];
		
		
			$subtotal1 = $NewData['@attributes']['AmountBeforeTax'];
			 $total_tax1 = $NewData['Taxes']['@attributes']['Amount'];
			$EffectiveDate = $NewData['@attributes']['EffectiveDate'];
		
	}else{
		
		$NewData = $datarooms['RoomRates']['RoomRate']['Rates']['Rate']['Base'];
	
		 $adult			= 	$datarooms['RoomRates']['RoomRate']['@attributes']['Adult'];
		 $RatePlanCode = $datarooms['RoomRates']['RoomRate']['@attributes']['RatePlanCode'];
		
		
			$subtotal1 = $NewData['@attributes']['AmountBeforeTax'];
			 $total_tax1 = $NewData['Taxes']['@attributes']['Amount'];
			$EffectiveDate = $NewData['@attributes']['EffectiveDate'];
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	 $RoomStayCount[$counts]['Rates']['Rate']['Base']['@attributes']['AmountAfterTax'];
	 $RatePLanInclusive	= $datarooms['RatePlans']['RatePlan']['RatePlanInclusions']['@attributes']['TaxInclusive'];

	//foreach($xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['RoomStays']['RoomStay'] as $counts=>$datarooms){
		
		
		$IsArray	=	array_key_exists('0',$datarooms['RoomRates']['RoomRate']);

		if($IsArray	==1){
		
		$RoomRateArray=$datarooms['RoomRates']['RoomRate'];
		
		}else{
				$RoomRateArray	=	array();
				$RoomRateArray[]=$datarooms['RoomRates']['RoomRate'];
			}
	foreach($RoomRateArray as $RoomRatecounts=>$RoomRatedata){
		
	//	echo $totalpriceRoom	=	$RoomRateArray[$RoomRatecounts]['Rates']['Rate']['Base']['@attributes']['AmountAfterTax'];
			
	//debugData($RoomRateArray);
	
	$checkout='';$checkin='';
	
	
		
	
	if($RatePLanInclusive=='true'){
	$totalpriceRoom	=	$RoomRateArray[$RoomRatecounts]['Rates']['Rate']['Base']['@attributes']['AmountAfterTax'];
	
	$NewpriceValue	 =	$RoomRateArray[$RoomRatecounts]['Rates']['Rate']['Base']['@attributes']['AmountAfterTax'];
	}else{
	$totalpriceRoom	=	$RoomRateArray[$RoomRatecounts]['Rates']['Rate']['Base']['@attributes']['AmountBeforeTax'];
	$NewpriceValue	 =	$RoomRateArray[$RoomRatecounts]['Rates']['Rate']['Base']['@attributes']['AmountBeforeTax'];
		}
	
	$roomCodeRoom	  =    $RoomRateArray[$RoomRatecounts]['@attributes']['RoomTypeCode'];
	
	//echo '=='.$checkin		   =	$RoomStayCount['TimeSpan']['@attributes']['Start'];
	//$checkout		  =	date('Y-m-d',strtotime('+1 day',strtotime($RoomStayCount[$RoomRatecounts]['TimeSpan']['@attributes']['End'])));
	
	$checkout=	date('Y-m-d',	strtotime($multiRoom[0]['TimeSpan']['@attributes']['End']));
	$checkin=$multiRoom[0]['TimeSpan']['@attributes']['Start'];
	
	$roomTypeRoom	  =	$xmlarray['reservations']['reservation']['0']['room'][$counts]['name'];

	//room type validation
	

	$adults			= 	$RoomRateArray[$RoomRatecounts]['@attributes']['Adults'];
	$child 			 = 	$xmlarray['reservations']['reservation']['0']['room'][$RoomRatecounts]['numberofchild'];
	
	$RatePlansMappingID	=$RoomRateArray[$RoomRatecounts]['@attributes']['MealPlan'];
	
	//$RoomStayCount[$counts]['Total']['@attributes']['AmountBeforeTax'];
	$RoomType_NumberOfUnits=$RoomRateArray[$RoomRatecounts]['@attributes']['NumberOfUnits'];
 	$dated 			 = $checkin;
	
	
	
	//$noDaysin=  count($xmlarray['reservations']['reservation']['0']['room'][$counts]['price']);
	
	$daysNew 		   =  abs((strtotime($checkin) - strtotime($checkout))/ 86400 );
		if($daysNew == '0'){
			$noDaysin = '1';
		}else {
			$noDaysin = $daysNew;
		}
	 $NewpriceValue	=	$NewpriceValue;////round($NewpriceValue/$noDaysin);
	
		$SqlCheckOrderBY1 = mysqli_query($connNew,"SELECT * FROM fo_reservations_details WHERE id_fo_reservations='".$id_fo_reservations."' AND `id_mst_hotels`='".$id_mst_hotels."' AND `id_mst_guest`='".$id_mst_guest."'    order by id desc ");
		$NumRowOrderBY1	=	mysqli_num_rows($SqlCheckOrderBY1); 
		$resRoomOrderBY1= mysqli_fetch_object($SqlCheckOrderBY1);
		if($NumRowOrderBY1	>0){ 
			$order_by_room=$resRoomOrderBY1->order_by_room;
			$order_by_room++;
			$Set=1;
			$Setorder_by_room =$order_by_room;
			
		}
		
	
	foreach($multiRoom as $countstart=>$datarooms){ 
	
		
while(strtotime($dated)!=strtotime($checkout)){
	

				
		//echo '<br>'.date('Y-m-d',strtotime($dated));
		
		$SQLRateId = mysqli_query(
    $connNew,
    "SELECT * FROM fs_rate_mapping 
     WHERE booking_engine_id = '".mysqli_real_escape_string($connNew, $RatePlansMappingID)."' 
     AND channel_id = '".mysqli_real_escape_string($connNew, $channelId)."'"
);

if(mysqli_num_rows($SQLRateId) > 0){
    $FetchArrayRateId   = mysqli_fetch_object($SQLRateId);
    $Room_rate_plan_id  = $FetchArrayRateId->rate_id;
} else {
    $Error .= 'Rate mapping - '.$RatePlansMappingID.' Not Found<br>';
}
		
		
		
				
		$SelectTaxDateSQL	=  mysqli_query($connNew,"SELECT * FROM `".TBL_TAX_DATE_RULE."` where id_shop='".addslashes($id_shop)."'  order by start_date desc");
		$SelectTaxDateRow 		= mysqli_fetch_object($SelectTaxDateSQL);
		$SlectedDateNewTax_id	= $SelectTaxDateRow->id;	
			//$resNewTaxInclution=  mysqli_query($connNew,"SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($id_shop)."' AND ((tax_inc_slabs_from <=  '".$NewpriceValue."' and tax_inc_slabs_to  >= '".$NewpriceValue."') OR ( tax_inc_slabs_from between '".$NewpriceValue."' and '".$NewpriceValue."') OR ( tax_inc_slabs_to between '".$NewpriceValue."' and '".$NewpriceValue."')) and tax_uniqueid='".$SlectedDateNewTax_id."'  order by start_date desc");
			
			$resNewTaxInclution= executeSql("SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($id_shop)."' AND ((tax_slabs_from <=  '".$NewpriceValue."' and tax_slabs_to  >= '".$NewpriceValue."') OR ( tax_slabs_from between '".$NewpriceValue."' and '".$NewpriceValue."') OR ( tax_slabs_to between '".$NewpriceValue."' and '".$NewpriceValue."')) and tax_uniqueid='".$SlectedDateNewTax_id."'  order by start_date desc");

			if(num_rows($resNewTaxInclution) >0 ){
				$rowNewTaxInclution = mysqli_fetch_object($resNewTaxInclution);
				//$tax_new_percent	='1.'.$rowNewTaxInclution->tax_percent;
				//$totalpriceRoom=round($NewpriceValue/$tax_new_percent);
				//$tax_perday_perroom= $NewpriceValue-$totalpriceRoom;
				
				$resRatePlanDetail 		= selectSql('fs_rate_plan',"where id='".addslashes($Room_rate_plan_id)."' ",' ORDER BY `name`'); 
				$resRateDetail = mysqli_fetch_object($resRatePlanDetail);
				$tax_detail=$resRateDetail->tax_detail;
				
				     $tax_new_percent	=$rowNewTaxInclution->tax_percent;
					 $totalpriceRoom=round($NewpriceValue);
					$taxparsentamount=round(($NewpriceValue*$tax_new_percent)/100);
					$tax_perday_perroom= $taxparsentamount;
				/*if($tax_detail=='2'){ 
					$tax_new_percent	=$rowNewTaxInclution->tax_percent;
					 $totalpriceRoom=round($NewpriceValue);
					$taxparsentamount=round(($NewpriceValue*$tax_new_percent)/100);
					$tax_perday_perroom= $taxparsentamount;
				//}else{*/
				
					//$tax_new_percent	='1.'.$rowNewTaxInclution->tax_percent;
					//$totalpriceRoom=round($NewpriceValue/$tax_new_percent);
					//$tax_perday_perroom= $NewpriceValue-$totalpriceRoom;
				//}
				$totalpriceRoom=round($totalpriceRoom*$RoomType_NumberOfUnits,2);
				$SubTotalAssignDetail	+=($totalpriceRoom);
			    $SubTotalTax	+=round($tax_perday_perroom*$RoomType_NumberOfUnits,2);
			}
			
						
	
$queryRoomId = mysqli_query($connNew,"SELECT * FROM fs_room_mapping WHERE hotel_mapping_id ='".$resChannel->id."' and booking_engine_id='".$roomCodeRoom."'");

		$room_quantity = 1;
		
		if(mysqli_num_rows($queryRoomId) > 0){
			$resRoomId = mysqli_fetch_object($queryRoomId);

		$id_mst_room_types=$resRoomId->room_id;
			//$Error = '';
		}else{
			$Error .= 'Room Type - '.$roomCodeRoom.' Not Found<Br>';
		}
		
		$rate_id=0;
		

	$hotel_idMapping 		=  $multiRoom[0]['BasicPropertyInfo']['@attributes']['HotelCode'];
	$checkout=	date('Y-m-d',	strtotime($multiRoom[0]['TimeSpan']['@attributes']['End']));
	$checkin=$multiRoom[0]['TimeSpan']['@attributes']['Start'];
	$adults[]			= 	$datarooms['RoomRates']['RoomRate']['@attributes']['Adult'];
	
	
		
		
		
		/*$sql = "SELECT * FROM fo_rate_plan WHERE name='$RatePlanCode'";
		$result = mysqli_query($connNew, $sql);
		
		if (mysqli_num_rows($result) > 0) {
		  // output data of each row
		  while($row = mysqli_fetch_assoc($result)) {
				   $idrate_plan = $row["id"];
		  }
		} 
		else{
			echo '<?xml version="1.0" encoding="UTF-8"?>
			<OTA_HotelResNotifRS xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
			xmlns:xsd="http://www.w3.org/2001/XMLSchema"
			xmlns="http://www.opentravel.org/OTA/2003/05" hotel_name="'.$hotel_name.'"  Version="1.0">
			<Success>"'.$RatePlanCode.'" not exist</Success>
			</OTA_HotelResNotifRS>';

			//////////////////////////////////////////////	
			//	 mail function needs to write - afsal	//
			//////////////////////////////////////////////

			//exit;
		}*/

	$id_fo_reservations = $rese_id;
	$id_mst_hotels = $hotel_id;
	$id_mst_guest=$guest_id;
	$plan ="1";
	$id_rate="0";
	//$id_fo_rate_plan=$idrate_plan;
	//$dated=$EffectiveDate;
	$room_quantity="1";
	$adults_per_room=$adult;
	$tariff_price_per_day_per_room=$subtotal1;
	$tax_per_day_per_room=$total_tax1;
	$unique_code=$unique_codes;
	$checkin_status="0";
	$date_created = date('Y-m-d');


	
	//Tax iD======INSERT=================================================================		
			
			$SelectTaxDateSQL = executeSql(
    "SELECT * FROM `" . TBL_TAX_DATE_RULE . "` 
     WHERE id_shop='" . addslashes($id_shop) . "' 
       AND start_date <= CURDATE() AND status='1' 
     ORDER BY start_date DESC"
);
$SelectTaxDateRow = $db->fetch_object2($SelectTaxDateSQL);
$SlectedDateNewTax_id = $SelectTaxDateRow->id ?? 0;

//1 for inclusive | 2 for exclusive	

    
			
	$tax_detail	=	selectColumn(TBL_RATE_PLAN,'tax_detail'," WHERE `id` = '".$Room_rate_plan_id."'");
		
	if($tax_detail=='1'){//1 for inclusive
		
		
	 $price =$subtotal1;		
	
       $resNewTaxInclution = mysqli_query(
        $connNew,
        "SELECT * FROM `" . TBL_TAX_RULE . "` 
         WHERE id_shop='" . addslashes($id_shop) . "' 
           AND ((tax_inc_slabs_from <= '$price' AND tax_inc_slabs_to >= '$price') 
             OR (tax_inc_slabs_from BETWEEN '$price' AND '$price') 
             OR (tax_inc_slabs_to BETWEEN '$price' AND '$price')) 
           AND tax_uniqueid='$SlectedDateNewTax_id' 
         ORDER BY start_date DESC LIMIT 1"
    );	
		 
		 
	}else{//2 for exclusive	
		
		
	$price =$subtotal1;		
	 $resNewTaxInclution = mysqli_query(
        $connNew,
        "SELECT * FROM `" . TBL_TAX_RULE . "` 
         WHERE id_shop='" . addslashes($id_shop) . "' 
           AND ((tax_slabs_from <= '$price' AND tax_slabs_to >= '$price') 
             OR (tax_slabs_from BETWEEN '$price' AND '$price') 
             OR (tax_slabs_to BETWEEN '$price' AND '$price')) 
           AND tax_uniqueid='$SlectedDateNewTax_id' 
         ORDER BY start_date DESC LIMIT 1"
    );
	}
			
					
			
	

    $tax_percent = 0;
    $tax_rule_id = 0;
    if (mysqli_num_rows($resNewTaxInclution) > 0) {
        $rowNewTaxInclution = mysqli_fetch_object($resNewTaxInclution);
        $tax_percent = $rowNewTaxInclution->tax_percent;
        $tax_rule_id = $rowNewTaxInclution->id;

        $tax_multiplier = 1 + ($tax_percent / 100);
        $tariff_excl_tax = round($price / $tax_multiplier, 2, PHP_ROUND_HALF_UP);
        $tax_amount = round($price - $tariff_excl_tax, 2);
    } else {
        $tariff_excl_tax = $price;
        $tax_amount = 0;
    }

     $RoomListArray[$roomInc] = [
        'tariff_per_room_excl_tax' => $tariff_excl_tax,
        'tariff_per_room_incl_tax' => $price,
        'tax_amount' => $tax_amount,
        'tax_percent' => $tax_percent,
        'tax_rule_id' => $tax_rule_id
    ];

    


//Tax iD=======================================================================

//Tax iD=======================================================================
	
	
	if($Set==''){ $order_by_room='1';
		/*$SqlCheckOrderDetails = mysqli_query($connNew,"SELECT * FROM fo_reservations_details WHERE id_fo_reservations='".$id_fo_reservations."' AND `id_mst_hotels`='".$id_mst_hotels."' AND `id_mst_guest`='".$id_mst_guest."' AND `id_mst_room_types`='".$id_mst_room_types."' AND  `dated`='".addslashes(date("Y-m-d",strtotime($dated)))."' AND `room_quantity`='".$RoomType_NumberOfUnits."' AND `adults_per_room`='".$adults_per_room."'");
		$NumRow	=	mysqli_num_rows($SqlCheckOrderDetails); 
		$resRoomOrderDetails= mysqli_fetch_object($SqlCheckOrderDetails);
		//Count Inc===============
		$SqlCheckOrderBY = mysqli_query($connNew,"SELECT * FROM fo_reservations_details WHERE id_fo_reservations='".$id_fo_reservations."' AND `id_mst_hotels`='".$id_mst_hotels."' AND `id_mst_guest`='".$id_mst_guest."'  order by id desc ");
		$NumRowOrderBY	=	mysqli_num_rows($SqlCheckOrderBY); 
		$resRoomOrderBY= mysqli_fetch_object($SqlCheckOrderBY);
		if($NumRowOrderBY	==0){ 
			$order_by_room='1';$order_by_room;
		}else{
			
		$order_by_room='1';
		
			echo '===Step2'.$RoomType_NumberOfUnits.'==='.$order_by_room;
			
			}*/
	}
		
		if($NumRow	==0){
			
			//$order_by_room='0';
			for($r=1;$r<=$RoomType_NumberOfUnits;$r++){
				
		 $Insert_into_Order_Details= "INSERT INTO fo_reservations_details (id_fo_reservations,id_mst_hotels,id_mst_guest,id_mst_room_types,plan,id_rate,id_fo_rate_plan,dated,room_quantity,adults_per_room,tariff_price_per_day_per_room,tax_per_day_per_room,unique_code,checkin_status,id_shop,order_by_room,tax_percent,id_tax_configuration) 
	Values('$id_fo_reservations','$id_mst_hotels','$id_mst_guest',$id_mst_room_types,'$Room_rate_plan_id','$id_rate','$Room_rate_plan_id','$dated','$RoomType_NumberOfUnits','$adults_per_room','$subtotal1','$tax_per_day_per_room','$unique_code','$checkin_status','".addslashes($id_shop)."','$order_by_room','$tax_percent','$tax_rule_id')";
	//exit;
	 	
	$Insert_into_Order_Details_Run = executeSql($Insert_into_Order_Details);
	//mysqli_query($connNew,$Insert_into_Order_Details);
		$order_by_room++;
			}

	
//FO_INVENTORY =======================	
		 $sqla = "SELECT * FROM ".FO_INVENTORY." WHERE id_mst_room_types='".$id_mst_room_types."' and allocation_date='".date('Y-m-d',strtotime($dated))."' and id_mst_hotels = '".$id_mst_hotels."' ";
						$resnew = mysqli_query($connNew,$sqla);
						//$rownew = mysqli_fetch_object($resnew);
						
						while($rownew = mysqli_fetch_object($resnew)){ 
							$crs_available = $rownew ->crs_available - $RoomType_NumberOfUnits ; 
							$confirmed = $rownew->confirmed + $RoomType_NumberOfUnits ; 
							
							$insertGrid = "UPDATE ".FO_INVENTORY." SET `crs_available`='".$crs_available."',`confirmed`='".$confirmed."' ";
							$insertGrid .=" WHERE id_mst_room_types='".$id_mst_room_types."' and allocation_date='".date('Y-m-d',strtotime($dated))."' and id_mst_hotels = '".$id_mst_hotels."'";
						//echo '<br>'.$insertGrid;
						mysqli_query($connNew,$insertGrid);	
							
						}
			//FO_INVENTORY =======================	



		}

	
		$dated = date('Y-m-d',strtotime('+1 day',strtotime($dated)));
		if($Set=='1'){
			$order_by_room = $Setorder_by_room;
		}
		} 
	}
}
	
}
	
	
	//Other Charges Start=================================================================
$OtherChargesArray	=	array_key_exists('0',$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['OtherCharges']['OtherCharge']);
if($OtherChargesArray==1){
	$OtherChargesArrayList	=$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['OtherCharges']['OtherCharge'];
	}else{
	$OtherChargesArrayList[]	=$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['OtherCharges']['OtherCharge'];
	}
	
	if($xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['OtherCharges']!=''){
	$total_addon_price='';
foreach($OtherChargesArrayList as $otherInc=>$OtherChargesArrayValue){
		
		
 $addons_details = " INSERT INTO `".FO_RESERVATION_ADDONS_DETAILS."` SET
	
		`id_fo_reservations` = '".$id_fo_reservations."', 
		`id_shop` = '".$id_shop."',
		`id_mst_hotels` = '".$id_mst_hotels."',
		`id_mst_room_no_allocation` = '".$pid."',
		`item` = '".$OtherChargesArrayValue['chargesitem']."',
		`additional_description` = '".$OtherChargesArrayValue['chargesDescription']."',
		`qty` = '".$OtherChargesArrayValue['chargesNumberCount']."',
		`unit` = '".$OtherChargesArrayValue['chargesNumberOfDays']."',
		`rate` = '".$OtherChargesArrayValue['chargesPrice']."',
		`tax_percent` = '".$OtherChargesArrayValue['chargesTaxPercentage']."',
		`tax_value` = '".$OtherChargesArrayValue['chargesTaxTotal']."',
		`amount` = '".$OtherChargesArrayValue['chargesNetAmount']."' ";
		
		$total_addon_price +=$OtherChargesArrayValue['chargesNetAmount'];
		$total_addon_tax +=$OtherChargesArrayValue['tax_value'];
		
		
	mysqli_query($connNew,$addons_details);


}
}else{
		$total_addon_price = '';
		$total_addon_tax ='';
		
	
	}
 $TotalAddonAmount	=	$total_addon_price;
 
		$SqlCheckOrderDetails = mysqli_query($connNew,"SELECT sum(tariff_price_per_day_per_room) as tariff_price_per_day_per_room,sum(tax_per_day_per_room) as tax_per_day_per_room FROM fo_reservations_details WHERE id_fo_reservations='".$id_fo_reservations."' ");
		$NumRow	=	mysqli_num_rows($SqlCheckOrderDetails); 
		$resRoomOrderDetails= mysqli_fetch_object($SqlCheckOrderDetails);
$balance	=$resRoomOrderDetails->tariff_price_per_day_per_room;
$total_tax	=$resRoomOrderDetails->tax_per_day_per_room;
$updateInventory = executeSql("UPDATE  `".FO_RESERVATIONS."`  SET 
								`total_addon_price`	='".($total_addon_price)."',								
								`total_addon_tax`	='".($total_addon_tax)."',
								`balance`	='".($balance+$total_tax+$TotalAddonAmount)."',
								`total_tax`	='".($total_tax+$total_addon_tax)."',
								`net_booking_amount`	='".($balance+$total_tax+$TotalAddonAmount)."'
								where 
								`id`			='".$id_fo_reservations."'");
		
		

//Other Charges Start=================================================================
	
	}
				if($id_app_shops!='57'){
				updateOTA('1', $checkin,$checkout,$connNew);
				}
				
$Booking_no	= addslashes($docConfig['prefix']).addslashes($docConfig['po_no']).addslashes($docConfig['suffix']);
	$data = [
			'EchoToken' => $ReferenceID,
			'CrsResID_Value' => $otherRefrenceId,
			'PmsResID_Value' => $rese_id,
			'PmsBooking_no' => $Booking_no,
			'Error' => $Error ?? '',
    		'status' => 'success',

			];
	
		$json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
         echo $json;
		 executeSql("Insert into api_request set channel_id = '1' , request='".$postData."',date_created='".date('Y-m-d H:i:s')."',company_name='".$CompanyName."',booking_referance_id='0',response_status='success',id_pms_response='".$otherRefrenceId."',failed_at='".date('Y-m-d H:i:s')."',booking_type='commit'");
	
		  
	exit;
	}

	

	//API CODE START====================================================================================
	
}else{

	 $data = [
			'EchoToken' => $ReferenceID,
			'CrsResID_Value' => $otherRefrenceId,
			'PmsResID_Value' => $rese_id,
    		'status' => 'Invalid user name and password',

			];
	
		$json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
echo $json; 

	executeSql("Insert into api_request set channel_id = '1' , request='".$postData."',date_created='".date('Y-m-d H:i:s')."',company_name='".$CompanyName."',booking_referance_id='0',response_status='Invalid user name and password',id_pms_response='".$otherRefrenceId."',failed_at='".date('Y-m-d H:i:s')."',booking_type='commit'");

	//////////////////////////////////////////////////////////////
	//	api request and response needed to write here - afsal	// - Done!
	//////////////////////////////////////////////////////////////

//echo 'Invalid Username and Password';

}
}

?>