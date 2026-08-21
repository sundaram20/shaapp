<?php 

	include_once("../config/auto_loader.php");
	

 $resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");
 $rowShop = $db->fetch_object2($resShop);
// echo "SELECT `".FO_RESERVATIONS."`.* FROM `".FO_RESERVATIONS."`  WHERE `".FO_RESERVATIONS."`.`id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'";
 $sqlOrder = executeSQl("SELECT `".FO_RESERVATIONS."`.* FROM `".FO_RESERVATIONS."`  WHERE `".FO_RESERVATIONS."`.`id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'"); 
 $rowOrder = $db->fetch_object2($sqlOrder);

 $interenal_remarks = $rowOrder->res_internal_remarks ?? '';
 $special_notes = $rowOrder->res_special_notes ?? '';
//print_r($rowOrder);
 if($resultHotelDetail->image!=''){
  	$hotelLogo='<img src="../../uploaded_files/hotel_gallery/'.$resultHotelDetail->image.'"  style="width:100px;height:100px;" />';
 }
 else{
	  	$hotelLogo='';
 }
 //test;

?>
 <?php $sqlGuestDetail = executeSQl("SELECT * FROM `mst_guest` WHERE id= '".addslashes($rowOrder->id_mst_guest)."'"); 
		 $rowGuestDetail = $db->fetch_object2($sqlGuestDetail);  ?>
<?php $resHotelDetail = selectSql('mst_hotels',"where status='1' AND id='".$rowOrder->id_mst_hotels."' ",' ORDER BY `name`'); 
		  $resultHotelDetail = $db->fetch_object2($resHotelDetail);  ?>
<?php $resContact = selectSql('mst_company_contacts',"where id='".addslashes($rowOrder->id_mst_company_contacts)."'",''); 
		  $resultContact = $db->fetch_object2($resContact); ?>
<?php $resCompany = selectSql('mst_company',"where id='".addslashes($rowOrder->id_mst_company)."'",''); 
		  $resultCompany = $db->fetch_object2($resCompany); 
		   $CompanyName	=	$resultCompany->name;
		  
			
		
		$res_payment_status=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'payment_status' AND id= '".$rowOrder->res_payment_status."'"); 	
$id_mst_attributes_titleContact	=	selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$resultContact->id_mst_attributes_title."'");				
	$Title=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_titleContact."'"); 				

	$company_contacts=$Title.' '.ucwords(strtolower($resultContact->first_name)).' '.ucwords(strtolower($resultContact->last_name));

  

//$groupName= selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$resultCompany->id_default_group."'");
 
if($resultCompany->id_default_group=='27' ||  $resultCompany->id_default_group=='29' || $resultCompany->id_default_group=='30' || $resultCompany->id_default_group=='31' ||   $resultCompany->id_default_group=='32' ||  $resultCompany->id_default_group=='33' ||   $resultCompany->id_default_group=='34'  ||  $resultCompany->id_default_group=='35' ||  $resultCompany->id_default_group=='37' ||  $resultCompany->id_default_group=='38' ||  $resultCompany->id_default_group=='39' || $resultCompany->id_default_group=='0'  ){
	$CompanyNameView='';
	$cAddress	=	'';
	$resultCompanymobile = '';
     $resultCompanyemail= '';
	  //$resultCompanygst= '-';
	
	
	}else{
		$resultCompany_address=preg_replace("#<br />#", "", $resultCompany->address);//strip_tags($resultCompany->address,"<style><b><br><strong><img><table><p><br><tr><td><span><ul><li><ol><u>");
	    if($rowOrder->other_reference!='')
	    {
	      $CompanyNameView=selectColumn('mst_company','name'," WHERE `id` = '".addslashes($rowOrder->id_mst_company)."' and `status`=1 ");
        } else {
        		      $CompanyNameView=selectColumn('mst_company','name'," WHERE `id` = '".addslashes($rowOrder->id_mst_company)."' and  `status`=1 ");

        }
	//$cAddress	=	wordwrap(addslashes($resultCompany_address),65,"<br>\n");

        if($CompanyNameView!=''){
        	        $cAddress= $resultCompany_address;
	$resultCompanymobile = $resultCompany->mobile;
     $resultCompanyemail= $resultCompany->email;
	  $resultCompanygst= $resultCompany->gst_no;
        }
        else{
        	        $cAddress= "";
	$resultCompanymobile = "";
     $resultCompanyemail= "";
	  $resultCompanygst= "";
        }

		}
if($resultHotelDetail->image!=''){
  	$hotelLogo = '<img src="'.$_SERVER['DOCUMENT_ROOT'].'/uploaded_files/hotel_gallery/'.$resultHotelDetail->image.'" style="width: auto; height: 70px; margin-top: 5px;" />';

 }
 else{
	  	$hotelLogo='';
 }	  
?>


<?php 
$resultHotelDetailemail	=str_replace(";",",",$resultHotelDetail->email);
$EmailHotels	=	explode(',',$resultHotelDetailemail);



$countemail=1;
foreach($EmailHotels as $dataEmail){
	
	
	$emailList.= $dataEmail;
	if(count($EmailHotels)>1){
				if($countemail==2){
					$emailList.='<br/>';
					$countemail=1;
					}else{
						$emailList.=',';
						$countemail++;}
				
				
				
				} else{
					$emailList.='<br/>';
				}

}

$content = '';

	
						
 $content .= '
              <table class="table" style="padding-top:0;margin-top:-10px;border:0px solid red;" width="100%">
						<tr>
						  <td width="30%" style="padding:0!important;">';
							/* $content .= '<address style="width:170px;padding:0!important;margin:0!important;position:absolute;top:0;">
							<img src="../../uploaded_files/shop/tch_logo.png" height="70px; margin-top : 1rem!important;"  class="img-responsive" alt="logo" title="logo" />
						 
						  </address>'*/
						   $content .= '<div style="margin-top:1.5rem">
						   '.$hotelLogo.'
						  </div>
						 	</td>
						   <td width="70%" style="padding:0!important; border:0px solid red; float:right;text-align:right;" >
						   
							<p style="font-family:Tahoma; font-size:14px; font-style: normal; font-variant: normal; font-weight: bold; line-height: 18.4px;text-align:right;text-transform:uppercase; margin-bottom:0px"><b>  '.$ShopShortCode.'  '.$resultHotelDetail->name.'<br> '.$resultHotelDetail->city.', '.selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".addslashes($resultHotelDetail->state)."'").'</b>
							</p>';
							
							$content .= '<span style="font-size:12px;line-height:18px;"><b >Address : </b>'.$resultHotelDetail->address.'<br>'.
								
								$resultHotelDetail->city.' - '.$resultHotelDetail->pincode.' '.
								selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".addslashes($resultHotelDetail->state)."'").','.selectColumn('mst_country_lang','name'," WHERE `id_country` = '".addslashes($resultHotelDetail->id_mst_country_lang)."'").'  <br></span>';

if ($resultHotelDetail->primary_contact_type == 1) {
    $phone = $resultHotelDetail->primary_mobile;
} elseif ($resultHotelDetail->primary_contact_type == 2) {
    $phone = $resultHotelDetail->primary_landline;
}
							$content .= '<span style="font-size:12px;line-height:18px;"><b>Contact No: </b> '.$phone.'; ';
							$content .= ''.($resultHotelDetail->phone2!=""?"<b> , </b>".$resultHotelDetail->phone2."<br/>":"");
							$content .= '<b>Email: </b> '.$emailList;
							if($resultHotelDetail->page_url!=""){
								$content .= ($resultHotelDetail->page_url!=""?"<strong>Hotel Website :</strong>".$resultHotelDetail->page_url."<br/>":"");
								}
								$content .= '<!--<b style="font-weight:bold!important;">Brand Website: </b>'.$rowShop->website_url.'--></span>
						 </td>
						<tr>
					</table>
					
       <br>'; 
	?>
<?php 

	
$Booking_Status =selectColumn('fo_booking_status','id'," WHERE `id` = '".addslashes($rowOrder->booking_status)."'");

//if($Booking_Status	!=	'2'){

$BookingStatus	=	selectColumn('fo_booking_status','name'," WHERE `id` = '".addslashes($rowOrder->booking_status)."'");
$BookingStatus  =  $BookingStatus;
//}else{
if($Booking_Status	==	'2'){
$BookingStatusTend	=	selectColumn('fo_booking_status','name'," WHERE `id` = '".addslashes($rowOrder->booking_status)."'");
$BookingStatusTend 	= 'Time Limit Till: '.dateformat_date($rowOrder->payment_date);
}
//}


$AmendmentCount	= $rowOrder->code;
			
			if($AmendmentCount >0){					
				$AmendmentTotalCount	= '-'.$rowOrder->code;
				}	
$content .= '<table class="table" border="0" style="margin-bottom:0;margin-top:1.5rem;width:100%">
    <tr>
        <td colspan="2" style="color:#961b1e;font-size:14px;">
            <div style="text-align:center;">
                <b>Your Reservation Number is '.$rowOrder->booking_no.$AmendmentTotalCount.'</b>
            </div>
        </td>
    </tr>';


		$content .='</table>';
$content .= '<table class="table" border="0" style="margin-bottom:0;margin-top:1.5rem">';
						if($resultHotelDetail->image_pdf!=''){ 
						 $content .= ' <tr>
						  <td colspan="2" style="text-align:center;"><div>';
						$content .= '<img src="../../uploaded_files/hotel_gallery/'.$resultHotelDetail->image_pdf.'"  class="img-responsive" style="margin:10px 0px;height:240px !important;width:730px;border:2px solid #961b1e;"/>';  
						  
						  //$content .= '<img src="'.$SITE_URL.'/images/ayatana-order.jpg" class="img-responsive" style="width:730px;border:2px solid #961b1e;">
						 $content .= '</td>
						 </tr>
						';}
						  
				$id_mst_guest=$rowOrder->id_mst_guest;

		// $id_mst_nationality = selectColumn(TBL_COUNTRY_LANG,'id_mst_nationality'," WHERE `id_country` = '".addslashes($rowGuestDetail->id_country)."'");	 
			$id_mst_attributes_title	=	selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$rowGuestDetail->id_mst_attributes_title."'");				
	$Title=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title."'"); 				
	$Firstname	=	selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$id_mst_guest."'");
	$Lastname	=	selectColumn(TBL_GUEST,'last_name'," WHERE `id` = '".$id_mst_guest."'");
	$guestName=$Title.' '.ucwords(strtolower($Firstname)).' '.ucwords(strtolower($Lastname));			 
						
					
						$content .=	'<tr><td colspan="2" style="line-height:23px;font-size:10px;font-weight:bold;"><>Dear ';
						//if($rowOrder->id_company_person != 0){
						$content .= $guestName;
								
							if($resultContact->first_name!='self'){//'.$resultContact->first_name.'
								//$content .= $resultContact->title.' '.ucwords(strtolower($resultContact->first_name)).' '.ucwords(strtolower($resultContact->last_name)); 
								//$content .=	$CompanyName;
							}else{
								
								//$content .= ' Sir/Madam,'; 
								}
							if($resultCompany->address !=''){
								//$content .= '<br>Address : '.$resultCompany->address;
							}	

							if($resultCompany->email !=''){
								//$content .= '<br>Email : '.$resultCompany->email;
							}	
								
						
						//}else{
						//$content .= ' Sir/Madam,'; 


						//}   


							/*if($rowGuestDetail->first_name != ''){

                         $content .= $rowGuestDetail->title.' '.$rowGuestDetail->first_name.' '.$rowGuestDetail->last_name.','; 
								} else{
										$content .= ' Sir/Madam,'; 
								}*/

		$content .=	'</td>';
		
		
		
		
					$content .=	'<tr>
									<td colspan="2" style="font-size:12px;line-height:18px;">       
										We are pleased to provide you with the room reservation status and summary as below:</span>
											
							       </td>
							       </tr>';

					 if($resultHotelDetail->checkin_time && $resultHotelDetail->checkout_time!=''){
					 
					 $content .= ' <tr>
							           <td colspan="2" style="margin-top:2px;padding-bottom:5px;color:#961b1e;font-size:12px;">Check-In time : '.$resultHotelDetail->checkin_time.'  and Check-Out time :'.$resultHotelDetail->checkout_time.' </td>
							           
					              </tr>'; 
					          }


					

		


			    	

		$content .='</table>';
$content .='<table class="table" border="0" style="width:400px!important;">';

		
		
			
		$content .='<tr>';
		$content .='<td  style="width:350px!important;padding-top:0!important;font-size:9px;">';
			/*if($resultHotelDetail->checkin_time!=''){
	    	$content .='<div style="margin-top:2px;">CHECKIN TIME : <span style="color:#000;font-size:10px;">'.$resultHotelDetail->checkin_time.' </span></div>';
	     } */
		
			$content .='<div style="margin-top:2px;"><span style="font-weight:bold">STATUS : </span><span style="color:#000;font-size:10px;;">'.htmlspecialchars($BookingStatus).' </span></div>';
		
		// if($Booking_Status	==	'2'){
		// $content .='<div style="margin-top:2px;"><span style="font-weight:bold">CUT OFF DATE : </span><span style="color:#000;font-size:10px;">'.$BookingStatusTend.' </span></div>';
		// }
		if($rowOrder->other_reference ==''){
			$content .='<div style="margin-top:2px;"><span style="font-weight:bold">GUEST NAME : </span><span style="color:#000;font-size:10px;">'.htmlspecialchars($guestName).' </span></div>';
			//$content .='<div style="margin-top:2px;"><span style="font-weight:bold">NATIONALITY : </span><span style="color:#000;font-size:10px;">'.selectColumn('mst_nationalities','name'," WHERE `id` = '".$id_mst_nationality."'").'</span></div>';


				$content .='<div style="margin-top:2px;"><span style="font-weight:bold">GUEST PHONE NUMBER : </span><span style="color:#000;font-size:10px;">'.$rowGuestDetail->primary_mobile.'</span></div>';
			 			 
				$content .='<div style="margin-top:2px;"><span style="font-weight:bold">GUEST EMAIL ADDRESS : </span><span style="color:#000;font-size:10px;">'.htmlspecialchars($rowGuestDetail->email).'</span></div>';
		}else{
			$content .='<div style="margin-top:2px;"><span style="font-weight:bold">GUEST NAME : </span><span style="color:#000;text-transform:capitalize;">'.$rowGuestDetail->title.' '.$rowGuestDetail->first_name.' '.$rowGuestDetail->last_name.' </span> </div>';

			
				
				$content .='<div style="margin-top:2px;"><span style="font-weight:bold">NATIONALITY : </span><span style="color:#000;font-size:10px;">Indian</span></div>';
				
            

				$content .='<div style="margin-top:2px;"><span style="font-weight:bold">GUEST PHONE NUMBER : </span><span style="color:#000;font-size:10px;">'.$rowGuestDetail->primary_mobile.'</span></div>';
				
			 			 
				$content .='<div style="margin-top:2px;"><span style="font-weight:bold">GUEST EMAIL ADDRESS : </span><span style="color:#000;font-size:10px;">'.$rowGuestDetail->email.'</span></div>';
			
			$content .='<div style="margin-top:2px;"><span style="font-weight:bold">BOOKER: </span><span style="color:#000;font-size:10px;">'.$company_contacts.'</span></div>';
			
				$content .='<div style="margin-top:2px;"><span style="font-weight:bold">BOOKER MOBILE : </span><span style="color:#000;font-size:10px;">'.$resultContact->primary_mobile.'</span></div>';
			}
		$content .='</td>';
		//======================================================================
		
		$content .='<td  style="width:350px!important;padding-top:0!important;font-size:9px;">';

		
		$content .='<div style="margin-top:2px;"><span style="font-weight:bold">BOOKING DATE : </span><span style="color:#000;font-size:10px;">'.dateformat_date($rowOrder->doc_date).'</span></div>';
		
		if($rowOrder->other_reference ==''){
			$content .='<div style="margin-top:2px;"><span style="font-weight:bold">COMPANY : </span><span style="color:#000;font-size:10px;">'.ucwords(strtolower($CompanyNameView)).' </span></div>';
			$content .='<div style="margin-top:2px;"><span style="font-weight:bold">COMPANY ADDRESS : </span><span style="word-wrap:break-word!important;color:#000;font-size:10px;">'.ucwords(strtolower($cAddress)).' </span></div>';
			$content .='<div style="margin-top:2px;"><span style="font-weight:bold">COMPANY PHONE NUMBER : </span><span style="color:#000;font-size:10px;">'.$resultCompanymobile.'</span></div>';
			$content .='<div style="margin-top:2px;"><span style="font-weight:bold">COMPANY EMAIL ADDRESS : </span><span style="color:#000;font-size:10px;">'.$resultCompanyemail.'</span></div>';
			if($resultCompanygst!=''){
			$content .='<div style="margin-top:2px;"><span style="font-weight:bold">COMPANY GST : </span><span style="color:#000;font-size:10px;">'.$resultCompanygst.'</span></div>';
			}
		}else{
			
			$content .='<div style="margin-top:2px;"><span style="font-weight:bold">COMPANY : </span><span style="color:#000;text-transform:capitalize;">'.ucwords(strtolower($CompanyNameView)).'</span></div>';


 $content .='<div style="margin-top:2px;"><span style="font-weight:bold">COMPANY ADDRESS : </span><span style="color:#000;font-size:10px;">'.ucwords(strtolower($cAddress)).' </span></div>';
				$content .='<div style="margin-top:2px;"><span style="font-weight:bold">COMPANY PHONE NUMBER : </span><span style="color:#000;font-size:10px;">'.$resultCompany->mobile.'</span></div>';
				$content .='<div style="margin-top:2px;"><span style="font-weight:bold">COMPANY EMAIL ADDRESS : </span><span style="color:#000;font-size:10px;">'.$resultCompany->email.'</span></div>';
			 			 
			                                                     
			}
		
		
		
		$content .='</td>';
		$content .='</tr>';
		$content .='</table>
      	 <hr  style="border:0.3px solid grey;">';
				
	

$content .= '<table class="table" border="0" >
						<tr>
						   <td  style="font-size:9px;width:350px;padding-top:0!important;"><span style="font-weight:bold">CHECK-IN  DATE : </span><span style="color:#000;font-size:10px;">'.dateformat_date($rowOrder->checkin).'</span></td>
						   <td  style="font-size:9px;width:200px;padding-top:0!important;"><span style="font-weight:bold">CHECK-OUT DATE : </span><span style="color:#000;font-size:10px;">'.dateformat_date($rowOrder->checkout).'</span></d>
						  					
						</tr>
						<tr >
						   <td  style="padding-top:0!important;font-size:9px;"><span style="font-weight:bold">ARRIVAL DETAILS : </span><span style="color:#000;font-size:10px;"></span></td>
						   <td  style="padding-top:0!important;font-size:9px;"><span style="font-weight:bold">DEPARTURE DETAILS : </span><span style="color:#000;font-size:10px;"></span></d>
						  					
						</tr>
							<!--<tr>
						   <td  style="padding-top:0!important;font-size:9px;"><span style="font-weight:bold">PICKUP DETAILS : </span><span style="color:#000;font-size:10px;"></span></td>
						   <td  style="padding-top:0!important;font-size:9px;"><span style="font-weight:bold">DROP DETAILS : </span><span style="color:#000;font-size:10px;"></span></d>
						  					
						</tr>-->
						
					</table>';
      	  	 $content .=' <hr  style="border:0.3px solid grey;">';	

	  if($rowOrder->pickup =='Yes'){
$content .= '<table class="table"  border="0" style="width:100%;padding:0!important;margin-top:-5px!important;">';					
$content .= '<tr>
<td colspan="2" style="width:100%;padding-top:0!important;font-size:9px;"><span style="text-align:left;font-weight:bold;">PICKUP DETAILS  : </span></b><span style="text-align:right;font-size:9px;color:#000;">'.$rowOrder->pickup_details.'</span></td></tr>';
$content .= '</table><hr  style="border:0.3px solid grey;">';

}

 if($rowOrder->guestdrop =='Yes'){
$content .= '<table class="table"  border="0" style="width:100%;padding:0!important;margin-top:-5px!important;">';					
$content .= '<tr>
<td colspan="2" style="width:100%;padding-top:0!important;font-size:9px;"><span style="text-align:left;font-weight:bold;">DROP DETAILS  : </span></b><span style="text-align:right;font-size:9px;color:#000;">'.$rowOrder->drop_details.'</span></td></tr>';
$content .= '</table><hr  style="border:0.3px solid #grey;">';

}


	 




//BOOKING DETAILS START======================================================

                
	  ?>
<?php 
				$counter = '1';
				$i=0;
				//$sqlOrderDetail = executeSQl("SELECT * FROM `fo_reservations_details`  WHERE `id_fo_reservations` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."' group by id_mst_room_types,id_fo_rate_plan ");

/*

				$get_folio_query = executeSQl("select id_fo_folio_to from fo_reservations_details where id_fo_reservations = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."' group by id_fo_folio_to");
				//$id_folio_text = "'0'";
				while ($get_folio = mysqli_fetch_object($get_folio_query)) {
					$id_folio_text .= ",'".$get_folio->id_fo_folio_to."'";
				}


				$receipt_amount = 0;
				if ($id_folio_text != '0') { //echo "select * from fo_receipt where id_fo_folio in (".$id_folio_text.")";die;
					$get_receipt_query = executeSQl("select * from fo_receipt where id_reservation= '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'");
					while ($receipt = mysqli_fetch_object($get_receipt_query)) {
						$receipt_amount += $receipt->amount;
					}
				}

*/

$reservationId = addslashes(encryptor('decrypt', $_REQUEST['id']));

$sql = "
    SELECT SUM(amount) AS total_amount
    FROM fo_receipt
    WHERE 
        id_reservation = '$reservationId'
        OR id_fo_folio IN (
            SELECT DISTINCT id_fo_folio_to
            FROM fo_reservations_details
            WHERE id_fo_reservations = '$reservationId'  AND id_fo_folio_to != 0
        )
";

$result = executeSQl($sql);
$row = mysqli_fetch_object($result);

$receipt_amount = $row->total_amount ?? 0;

				
				//while($rowOrderDetail=$db->fetch_object2($sqlOrderDetail)){
					//$resRoom = executeSql("SELECT rt.name as room_name, rp.name as rate_name, rd.* from `".TBL_RATE_DETAILS."` rd left join `mst_room_types` rt on rd.id_mst_room_types = rt.id left join `".'fo_rate_plan'."` rp on rd.id_fo_rate_plan = rp.id where rd.status='1' and rt.status='1' and rd.rate_assign_id='".addslashes($rowOrderDetail->rate_assign_id)."'   and rd.rate_id='".addslashes($rowOrderDetail->rate_id)."' and id_mst_room_types='".addslashes($rowOrderDetail->id_mst_room_types)."' order by rd.id_mst_room_types");	
					//echo "SELECT rt.name as room_name, rp.name as rate_name, rd.* from `fo_reservations_details` rd left join `mst_room_types` rt on rd.id_mst_room_types = rt.id left join `".'fo_rate_plan'."` rp on rd.id_fo_rate_plan = rp.id where  rd.`id_fo_reservations`='".$rowOrderDetail->id."'   and id_mst_room_types='".addslashes($rowOrderDetail->id_mst_room_types)."' order by rd.id_mst_room_types ";die;

					//$resRoom = executeSQl("SELECT rt.name as room_name, rp.name as rate_name, rd.* from `fo_reservations_details` rd left join `mst_room_types` rt on rd.id_mst_room_types = rt.id left join `".'fo_rate_plan'."` rp on rd.id_fo_rate_plan = rp.id where  rd.`id_fo_reservations`='".addslashes(encryptor('decrypt',$_REQUEST['id']))."'    group by id_mst_room_types order by rd.id_mst_room_types");

$resRoom = executeSQl("SELECT rt.name as room_name, rp.name as rate_name, rd.* 
    from `fo_reservations_details` rd 
    left join `mst_room_types` rt on rd.id_mst_room_types = rt.id 
    left join `fo_rate_plan` rp on rd.id_fo_rate_plan = rp.id 
    where rd.`id_fo_reservations`='".addslashes(encryptor('decrypt',$_REQUEST['id']))."'    
    group by id_mst_room_types, id_fo_rate_plan, adults_per_room, child_below_5_year, child_above_5_year
    order by rd.id_mst_room_types, rd.id_fo_rate_plan, rd.adults_per_room");
					
				//if(num_rows($resRoom) >0){
						//$rowRoom = $db->fetch_object2($resRoom);
						$sub_total = 0;
						$total_tax = 0;
				if(mysqli_num_rows($resRoom) >0 ){
			
				while($rowOrderDetail = mysqli_fetch_object($resRoom)) {
					//print_r($rowOrderDetail);die;
					 if($rowOrderDetail->id_fo_rate_plan >0){//echo '======'.$rowOrderDetail->id_fo_rate_plan;die;
			$resRatePlan = executeSQl("SELECT * from fo_rate_plan where id='".addslashes($rowOrderDetail->id_fo_rate_plan)."'"); 
			  $resultRatePlan = mysqli_fetch_object($resRatePlan);
			  $remarks='';
			   
			 
				if($resultRatePlan->name =='EP'){
			   $remarks	=	'Room Only';
			  }else{
			 
			 if(($resultRatePlan->rate_name =='MAP' || $resultRatePlan->rate_name =='MAPAI') ){
				$remarks	=	'Breakfast and Dinner';	
			}else{

			  $remarks	=	strtolower($resultRatePlan->remarks);
			}
			  }
				 
				$remarks	=	 $rowOrderDetail->rate_name;
				 
				 
				}
				
					
					
					
					
				//$room_detail_query = executeSQl("SELECT sum(tariff_price_per_day_per_room) as tariff_price_per_day_per_room, sum(tax_per_day_per_room) as tax_per_day_per_room from `fo_reservations_details` where id_fo_reservations = '".$rowOrderDetail->id_fo_reservations."' and id_mst_room_types = '".$rowOrderDetail->id_mst_room_types."'");
					
					$room_detail_query = executeSQl("SELECT sum(tariff_price_per_day_per_room) as tariff_price_per_day_per_room, 
    sum(tax_per_day_per_room) as tax_per_day_per_room 
    from `fo_reservations_details` 
    where id_fo_reservations = '".$rowOrderDetail->id_fo_reservations."' 
    and id_mst_room_types = '".$rowOrderDetail->id_mst_room_types."'
    and id_fo_rate_plan = '".$rowOrderDetail->id_fo_rate_plan."'
    and adults_per_room = '".$rowOrderDetail->adults_per_room."'
    and child_below_5_year = '".$rowOrderDetail->child_below_5_year."'
    and child_above_5_year = '".$rowOrderDetail->child_above_5_year."'");
					
				$room_details = mysqli_fetch_object($room_detail_query);
					
				//$room_count_detail_query = executeSQl("SELECT * from `fo_reservations_details` where id_fo_reservations = '".$rowOrderDetail->id_fo_reservations."' and id_mst_room_types = '".$rowOrderDetail->id_mst_room_types."' GROUP BY order_by_room");
					
					$room_count_detail_query = executeSQl("SELECT * from `fo_reservations_details` 
    where id_fo_reservations = '".$rowOrderDetail->id_fo_reservations."' 
    and id_mst_room_types = '".$rowOrderDetail->id_mst_room_types."' 
    and id_fo_rate_plan = '".$rowOrderDetail->id_fo_rate_plan."'
    and adults_per_room = '".$rowOrderDetail->adults_per_room."'
    and child_below_5_year = '".$rowOrderDetail->child_below_5_year."'
    and child_above_5_year = '".$rowOrderDetail->child_above_5_year."'
    GROUP BY order_by_room");
					
				$room_quantity = 0;
					$adults_per_room      = $rowOrderDetail->adults_per_room;
					$child_below_5_year   = $rowOrderDetail->child_below_5_year;
					$child_above_5_year   = $rowOrderDetail->child_above_5_year;
				
				while ($room_count_details = mysqli_fetch_object($room_count_detail_query)) {
					$room_quantity += '1';$room_count_details->room_quantity;
					
				}
				$sub_total += $room_details->tariff_price_per_day_per_room ?? 0;
				$total_tax += $room_details->tax_per_day_per_room ?? 0;
				$avg = (($room_details->tariff_price_per_day_per_room ?? 0) / $room_quantity);

					$checkin  = dateformat_date($rowOrder->checkin);
                    $checkout = dateformat_date($rowOrder->checkout);

                    $checkin_date  = new DateTime($checkin);
                    $checkout_date = new DateTime($checkout);

                    $interval = $checkin_date->diff($checkout_date);

                    $number_of_days = $interval->days;

					$Tarrif_per_night = $avg/$number_of_days;
					
                    //echo $number_of_days;

			$priceValue = 0;
					if($_SESSION['shop_code']=='cch'){
					$currency = 'GBP';
					}else{
					$currency = 'INR';
					}
					$content .='
					<table class="table" border="0" style="">
					
					
					
					
                <tr >
				  
			
<td style="width:350px;font-size:9px;padding-top:0!important;"><span style="font-weight:bold">ACCOMMODATION : </span><span style="color:#000;font-size:10px;"> '.ucwords(strtolower($rowOrderDetail->room_name)).'</span></td>


<td style="font-size:9px;padding-top:0!important;"><span style="font-weight:bold">NUMBER OF ROOMS: </span><span style="color:#000;font-size:10px;"> '.$room_quantity.'</span></td>





</tr>


<tr>
<td style="width:350px;font-size:9px;padding-top:0!important;"><span style="font-weight:bold">NUMBER OF ADULT PER ROOM: </span><span style="color:#000;font-size:10px;">'.$adults_per_room.'</span></td>
<td style="font-size:9px;padding-top:0!important;"><span style="font-weight:bold">TARIFF PER NIGHT:  </span><span style="color:#000;font-size:10px;">'.$currency.' '.round(($Tarrif_per_night),2,PHP_ROUND_HALF_UP).'</span></td>
<!--<td style="font-size:9px;padding-top:0!important;"><span style="font-weight:bold">TARIFF PER ROOM:  </span><span style="color:#000;font-size:10px;"> '.$currency.' '.round(($avg),2,PHP_ROUND_HALF_UP).'</span></td>-->
</tr>
      <tr>
				  
		


<td style="width:350px;font-size:9px;padding-top:0!important;"><span style="font-weight:bold">NUMBER OF KIDS 0-5 YEARS PER ROOM : </span><span style="color:#000;font-size:10px;">'.$child_below_5_year.'</span></td>

<td style="font-size:9px;padding-top:0!important;"><span style="font-weight:bold">ROOM TOTAL : </span><span style="color:#000;font-size:10px;"> '.$currency.' '.round($room_details->tariff_price_per_day_per_room,2,PHP_ROUND_HALF_UP).'</span></td>
</tr>



<tr>

<td style="width:350px;font-size:9px;padding-top:0!important;"><span style="font-weight:bold">NUMBER OF KIDS 5-12 YEARS PER ROOM : </span><span style="color:#000;font-size:10px;">'.$child_above_5_year.'</span></td>
<!--<td style="font-size:9px;padding-top:0!important;"><span style="font-weight:bold">TAXES : </span><span style="color:#000;font-size:10px;">Prices are all inclusive of Taxes</span></td>-->

 <!--<td style="font-size:9px;padding-top:0!important;"><span style="font-weight:bold">TAXES : </span><span style="color:#000;font-size:10px;">'.$currency.' '.round(($total_tax),2,PHP_ROUND_HALF_UP).'</span></td>-->
 
  <td style="font-size:9px;padding-top:0!important;"><span style="font-weight:bold">TAXES : </span><span style="color:#000;font-size:10px;">'.$currency.' '.round(($room_details->tax_per_day_per_room),2,PHP_ROUND_HALF_UP).'</span></td>
 
 <!--<td style="font-size:9px;padding-top:0!important;"><span style="font-weight:bold">TAXES : </span><span style="color:#000;font-size:10px;">'.$currency.' '.round(((($room_details->tariff_price_per_day_per_room+$room_details->tax_per_day_per_room)*$room_details->room_quantity)*$rowOrder->no_of_days),2,PHP_ROUND_HALF_UP).'</span></td>-->



</tr>';
$content .= '</table>				
					 <hr  style="border:0.3px solid grey;margin:0!important;">';

$content .= '<table class="table" border="0" width="100%" ><tr>
     <td colspan="2" style="padding-top:0!important;font-size:9px;border:0px solid red;"><span style="text-align:left;font-weight:bold;">INCLUSIONS : </span></b><span style="font-size:10px;color:#000;">'.ucwords(strtoupper($remarks)).'</span></td></tr>';



					
	$content .= '</table>				
					 <hr  style="border:0.3px solid grey;margin:0!important;">';
					
					
					
					$counter++;
					$i++;
				}
					}			
				//}
				$others_sub_total = 0;
				$others_tax = 0;
				$items = [];
				$resAddOn = executeSQl("SELECT * from `fo_reservations_addons_details` where id_fo_reservations = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'");
				while ($res = mysqli_fetch_object($resAddOn)) {
					$others_sub_total += $res->amount;
					$others_tax += ($res->tax_value * $res->qty);
					
					if (!empty($res->item)) {
						$desc = trim($res->additional_description);
						if ($desc != '') {
            $items[] = ucwords(strtolower($res->item)) . ' - ' . ucfirst(strtolower($desc));
        }else{
					$items[] = ucwords(strtolower($res->item)); 
						}
					}
				}
		$item_list = implode(', ', $items);

				$content .='	
		  
		  			</table>';
			$content .=' <table class="table" border="0" style="" >
                <tr >
<td style="font-size:9px;padding-top:0!important;"><span style="font-weight:bold">'.$roomLabel.' SUB TOTAL :  </span><span style="color:#000;font-size:10px;"> '.$currency.' '.round($sub_total,2,PHP_ROUND_HALF_UP).'</span></td>
<td style="font-size:9px;padding-top:0!important;"><span style="font-weight:bold">GRAND TOTAL : </span><span style="color:#000;font-size:10px;"> '.$currency.' '.round($sub_total + $total_tax + $others_sub_total + $others_tax).'</span></td>


</tr>';
$TotalPayment 	= round(($sub_total + $total_tax + $others_sub_total + $others_tax) - $receipt_amount);

if($TotalPayment=='0'){
$PaymentStatus	='Payment Received';

}else{

$PaymentStatus	='Pending';
}
$content .='<tr>
<td style="width:350px;font-size:9px;padding-top:0!important;"><span style="font-weight:bold">'.$roomLabel.' TAXES :  </span><span style="color:#000;font-size:10px;">'.$currency.' '.round(($total_tax),2,PHP_ROUND_HALF_UP).'</span></td>

<!-- 
<td style="width:350px;font-size:9px;padding-top:0!important;"><span style="font-weight:bold">'.$roomLabel.' TAXES :  </span><span style="color:#000;font-size:10px;">'.$currency.' '.round(($RoomTaxValue),2,PHP_ROUND_HALF_UP).'</span></td> -->

<td style="font-size:9px;padding-top:0!important;"><span style="font-weight:bold">AMOUNT PAID :  </span><span style="color:#000;font-size:10px;"> '.$currency.' '.round($receipt_amount).'</span></td>


</tr>

<!--<tr>
<td style="width:350px;font-size:9px;padding-top:0!important;"><span style="font-weight:bold">ITEM : </span><span style="color:#000;font-size:10px;">'.$item.'</span></td>
</tr>-->

<tr >
<td style="width:350px;font-size:9px;padding-top:0!important;"><span style="font-weight:bold">OTHER SERVICES SUB TOTAL : </span><span style="color:#000;font-size:10px;">'.$currency.' '.round($others_sub_total).'</span></td>

<td style="font-size:9px;padding-top:0!important;"><span style="font-weight:bold">BALANCE :  </span><span style="color:#000;font-size:10px;"> '.$currency.' '.round(($sub_total + $total_tax + $others_sub_total + $others_tax) - $receipt_amount).'</span></td>

</tr>

<tr >
<td style="width:350px;font-size:9px;padding-top:0!important;"><span style="font-weight:bold">OTHER SERVICES TAXES : </span><span style="color:#000;font-size:10px;">'.$currency.' '.round($others_tax).'</span></td>

<td style="width:350px;font-size:9px;padding-top:0!important;"><span style="font-weight:bold">PAYMENT STATUS : </span><span style="color:#000;font-size:10px;"> '.$res_payment_status . (!empty(trim($rowOrder->res_payment_instruction)) ? ' ('.$rowOrder->res_payment_instruction.')' : '') .'  </span></td>


</tr>



<tr >
<td style="width:350px;font-size:9px;padding-top:0!important;"><span style="font-weight:bold">DISCOUNT : </span><span style="color:#000;font-size:10px;">'.$currency.' '.round($rowOrder->total_discounts).'</span></td>
<td style="font-size:9px;padding-top:0!important;">   <span style="color:#000;font-size:10px;"> </span></td>

</tr>';				
			
	$content .= '</table><hr  style="border:0.3px solid grey;">';



									
$sql_fo_receipt	=	"SELECT * FROM `fo_receipt` where  id_reservation='".addslashes(encryptor('decrypt',$_REQUEST['id']))."'";
$res_fo_receipt 	= 	mysqli_query($connNew,$sql_fo_receipt);
if(mysqli_num_rows($res_fo_receipt)>0){
$i=1;
	while($row_fo_receipt = mysqli_fetch_object($res_fo_receipt)){
if($row_fo_receipt->is_advance == 1){
              $Pmode	= $row_fo_receipt->payment_mode.' (Advance)';
            }else{
               $Pmode = $row_fo_receipt->payment_mode;
            }
			$content .=' <table class="table" border="0" style="" >
                <tr >
<td style="width:350px;font-size:9px;padding-top:0!important;"><span style="font-weight:bold">PAYMENT MODE : </span><span style="color:#000;font-size:10px;"> '.ucwords(strtolower($Pmode)).'</span></td>

<td style="font-size:9px;padding-top:0!important;"><span style="font-weight:bold">RECEIPT AMOUNT:  </span><span style="color:#000;font-size:10px;">'.$currency.' '.($row_fo_receipt->amount).'</span></td>
 </tr><tr >
<td style="width:350px;font-size:9px;padding-top:0!important;"><span style="font-weight:bold">RECEIPT DATE : </span><span style="color:#000;font-size:10px;"> '.date('d-m-Y',strtotime($row_fo_receipt->doc_date)).'</span></td>


</tr>';

	}

		
$content .='	
		  
		  			</table>';$content .= '<hr  style="border:0.3px solid grey;">';
}





	if ($interenal_remarks !='' || $special_notes != '' || $item_list != '') {
		//$content .=' <table class="table" border="0" style="">';
		
		if ($item_list !='') {
			
			$content .='<div style="margin-top: 3px!important; font-size : 11px!important;"><b>Other Charges Item</b> : '.ucwords(strtolower($item_list)).'  </div>';
			//$content .=' <tr><td colspan="2" style="text-align:left"><span style="color:#000;font-size:10px;"><b>Internal Remarks</b> :</span><span style="color:#000;font-size:10px;">'.addslashes($interenal_remarks).' </span></td></tr>';
		}
		
		if ($interenal_remarks !='') {
			
			$content .='<div style="margin-top: 3px!important; font-size : 11px!important;"><b>Internal Remarks</b> : '.ucwords(strtolower($interenal_remarks)).'  </div>';
			//$content .=' <tr><td colspan="2" style="text-align:left"><span style="color:#000;font-size:10px;"><b>Internal Remarks</b> :</span><span style="color:#000;font-size:10px;">'.addslashes($interenal_remarks).' </span></td></tr>';
		}
		if ($special_notes != '') {
			$content .='<div style="margin-top: 3px!important; font-size : 11px!important;"><b>Special Notes</b> :'.ucwords(strtolower($special_notes)).'</div>';
			
			//$content .=' <tr><td  colspan="2" style="text-align:left"><span style="color:#000;font-size:10px;"><b>Special Notes</b> :</span><span style="color:#000;font-size:10px;">'.addslashes($special_notes).'</span></td></tr>';
		}
		
		$content .= '<hr style="border:0.3px solid grey;">';
	}
				
				
		  
				
//BOOKING DETAILS END======================================================		
//style="page-break-before: always; margin-top: 20px;"
$cancellation_policy	 = $resultHotelDetail->cancellation_policy;
if($cancellation_policy !=''){	
	
$content .= '<table class="table"  border="0" style="padding:0!important;margin:0!important;" >
					
						<tr style="margin:0!important;padding:0!D"><td>';
						
						
						 $content .='<br><strong style="font-size:12px; text-transform:uppercase;color:#961b1e; margin-top:50px;  font-family: Gotham!important;">Cancellation Policy</strong>
	 
	 <div class="row" style="color:#961b1e;">
	  <div class="col-sm-12 text-muted  no-shadow" style="font-size:9px;line-height:20px;">
		<p><b style="text-transform:uppercase;"><strong></strong></b></p>';
		$content .=' '.$cancellation_policy;
		/*$content .=' <ul>
		 <li>
		 No cancellation charges are applicable in case the booking is cancelled 7 days prior to the arrival date on BAR rate only. Bank charges
 applicable in case of any refund on BAR rate only.</li>
<li>In case of No show or cancelled booking within 7 days of arrival, retention will be charged equivalent to full stay.</li>
<li>The reservation stands non-refundable and non-amendable during long weekends, festivals, Christmas/New year
 period and Blackout dates.</li>
<li>100% cancellation charges will be applicable and no amendment is allowed for the reservations made on Advance
 Purchase Rate, Website Packages or Best Available Rate with a promo code discount.</li>
		 </ul>';*/
	  $content .='</div>
	</div>';
	$content .= '</td></tr></table>';
	
}










   $content .='<div class="" style=" ">
 

<div style="margin-top: 3px!important; font-size : 11px!important;">Thank you for choosing '.ucwords(strtolower($resultHotelDetail->name)).'  and we look forward to host you soon.</div>
</p>';
$content .='<span style="font-size : 12px!important;"> Kind Regards,</span><br>';
	
		//$content .=ucwords(strtolower(selectColumn(TBL_USERS,'name','WHERE id="'.$_SESSION['userId'].'" '))).'<br>';
		//$content .='<span style="font-size : 12px!important;">Reservation Team</span>';//ucwords(strtolower(selectColumn(TBL_USERS,'name','WHERE id="'.$rowOrder->last_modified_by.'" '))).'';
		
	




	$content .='<div style="margin-top: 0px; font-size : 11px!important;">';
	$content .= $rowShop->address;
	if($_SESSION['shop_code']=='cch'){
		$countryCode = '+44';
	}else{
		$countryCode = '+91';
	}
	$content .='</div></p>
	<a href="tel:'.$countryCode.' '.$rowShop->phone.'" style="font-size : 12px!important; margin-top: -10px;"><img src="icons/phone.png" title="Call us" style="margin-top:4px;" height="12px ">&nbsp;'.$countryCode.' '.$rowShop->phone.'</a><br> 	
	
	<br >';
	
	
	
	

	 
	 
	 //selectColumn(TBL_USERS,'name'," WHERE `id` = '".addslashes($_SESSION['userId'])."'").' <br/> '.selectColumn(TBL_USERS,'company'," WHERE `id` = '".addslashes($_SESSION['userId'])."'").'
	$content .=	$rowShop->social_links.'<br>
	<!-- //Please forward us your valuable feedback on '.$rowShop->feedback_url.' -->
  </div>
</div>';  

				
		



$content .='
						<table class="table" border="0" style="margin-bottom:0;margin-top:20px;">
						
						 
						
						 
						</table>';


if($_SESSION['shop_code']=='abr'){
	
	$color = '#348feb';
}else{
$color = '#961b1e;';
}


$content2 = '';
$content2 .='<header>
  
</header>

<div>
 <p> '.$content.'</p>
</div>

<style>

@page { 
                    margin: 0cm 0cm;
					
                }
               
			   header {
    position: fixed;
    top: 0cm;
    left: 0cm;
    right: 0cm;
    height: 15px;
    padding: 0;
    background-color: '.$color.';
}
body {
    margin-left: 1cm; 
    margin-right: 1cm; 
    margin-top: 1cm; 
    margin-bottom: 1cm; 
}
			   
                footer {
                    position: fixed;
                    bottom: 0cm;
                    left: 0cm;
                    right: 0cm;
height: 30px;

background-color: #961b1e;
text-align: center;
vertical-align: middle;
color: #f5ca6d;                   
                   
                }

	.address-font{			
	font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 14px; font-style: normal; font-variant: normal;  line-height: 20px;	
	}
	color:#961b1e;
	.hotel-name{
		font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: bold; line-height: 26.4px;text-transform:uppercase;
		}
	h1 { font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 24px; font-style: normal; font-variant: normal; font-weight: 700; line-height: 26.4px; } h3 { font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: 700; line-height: 15.4px; }
	 p { font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: 400; line-height: 20px; }
	 
	  blockquote { font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 21px; font-style: normal; font-variant: normal; font-weight: 400; line-height: 30px; } pre { font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 13px; font-style: normal; font-variant: normal; font-weight: 400; line-height: 18.5714px; }



	 </style>
	 
';
 	?>
<?php 
//echo $content2;die;
$dompdf = new DOMPDF();

//$sendMail = new sendMail;
 // echo $content2;die;
if($_REQUEST['location']=='set'){
	$Filename =str_replace(array( '\'', '_', ' / ' , ';', '<', '>',' ' ), '-', urldecode($resultHotelDetail->name)).'-'.addslashes(encryptor('decrypt',$_REQUEST['id']));
	

$dompdf->load_html($content2);
//debugData($dompdf);

$dompdf->render();
	$gen = $dompdf->output();
	$dompdf->stream($Filename.'.pdf', array("Attachment" => true));
	file_put_contents('../mailattach/'.$Filename.'.pdf', $gen);
	echo "ok";
}
else{
	$Filename =str_replace(array( '\'', '_', ' / ' , ';', '<', '>' ), '-', $rowGuestDetail->title.' '.urldecode(ucwords(strtolower($rowGuestDetail->first_name)).' '.ucwords(strtolower($rowGuestDetail->last_name))).' Reservation ID# '.addslashes($rowOrder->reference));
$dompdf->load_html($content2);
$dompdf->render();
$dompdf->stream($Filename.'.pdf', array("Attachment" => 0	));
}
