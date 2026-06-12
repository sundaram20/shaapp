<?php
include_once("../../config/auto_loader.php");

include_once("../functions/function.php");

		 $TodaysDate	=	date('Y-m-d');
		 $sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
		 $numRowsNightAudit =  mysqli_num_rows($sqlNightAudit);
		 $rowNightAudit =  mysqli_fetch_object($sqlNightAudit);
		 $Dated = date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated))); 
$result = "";
//print_r($_REQUEST);
$sqlCheckout	= "SELECT b.status,r.checkout,r.checkin,r.booking_no,b.mdoc_no as fo_bill_mdoc_no,b.id_fo_folio_to,r.id_mst_guest,b.id as id_fo_bill 
FROM ".FO_BILL." as b LEFT JOIN  `".FO_RESERVATIONS."` as r ON  b.`id_reservations`=r.id  where b.status='1' 
AND DATE(r.checkout)<='".$Dated."' and DATE(b.date_created)>'2023-01-01'";



$ResCheckout	=	mysqli_query($connNew,$sqlCheckout);
		
if(mysqli_num_rows($ResCheckout)>0){
	
			
	$ic=1;
	while($rowResCheckout =  mysqli_fetch_object($ResCheckout)){
		
			
	 
	  $id_ReservationDetails	=	selectColumn(FO_RESERVATIONS_DETAILS,'id'," WHERE `id_fo_folio_to` = '".$rowResCheckout->id_fo_folio_to."'");
		
	if($id_ReservationDetails != "")	{
	
		$mdoc_no=selectColumn('fo_folio','mdoc_no',"WHERE  id = '".$rowResCheckout->id_fo_folio_to."' ");
		
		$GuestName	=	selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$rowResCheckout->id_mst_guest."'").' '.selectColumn(TBL_GUEST,'last_name'," WHERE `id` = '".$rowResCheckout->id_mst_guest."'");;
		
		$id_mst_room_no_allocation	=	selectColumn(FO_RESERVATIONS_DETAILS,'id_mst_room_no_allocation'," WHERE `id_fo_bill` = '".$rowResCheckout->id_fo_bill."'");
		$RoomNo= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$id_mst_room_no_allocation."'");
		
		
	
		$result .='<tr style="text-align: center; font-size: 14px;">
				                    <td class="wd-5">'.$ic.'</td>
									<td  class="wd-10">'.$GuestName.'</td>
									<td  class="wd-10">'.$RoomNo.'</td>
				                    <td  class="wd-10">'.$rowResCheckout->booking_no.'</td>
				                    <td class="wd-15">'.$rowResCheckout->fo_bill_mdoc_no.'</td> 
									<td class="wd-15">'.$mdoc_no.'</td>
				                    <td  class="wd-10">'.date('d-m-Y',strtotime($rowResCheckout->checkin)).'</td> 
				                    <td class="wd-10">'.date('d-m-Y',strtotime($rowResCheckout->checkout)).'</td> 
				                   
				                </tr>';
								$ic++;
		}						
	
	}
		
	if ( $result != "") {
		
		$html	='
		<table id="myTable2" border="1"
        class="table table-striped   table-responsive table-bordered dataTable no-footer max-h2"
        style="background:#fff;display: table-caption;"><thead>
		<tr style="text-align: center; font-size: 14px;">
            <th class="wd-5" colspan="8">Checkout Pending</th><tr>
          <tr style="text-align: center; font-size: 14px;">
            <th class="wd-5">S.NO</th>
            <th class="wd-10">Guest Name</th>
            <th class="wd-10">Room No</th>
            <th class="wd-10">Res#</th>
            <th class="wd-15">BILL</th>
            <th class="wd-15">FOLIO</th>
            <th class="wd-10">Checkin</th>
            <th class="wd-10">Checkout</th>


          </tr>
        </thead> <tbody>'.$result.'</tbody> </table>';
			$DateArray['status']=2;
			$DateArray['ContentData']=$html;
			$DateArray['msg']= 'One or more Room checkouts are pending please complete the checkout and proceed day close';	
			echo json_encode($DateArray);		
			exit;
			
	}
	
}
//===ROOM SHOW OFF START============================================================================

//print_r($_REQUEST);
//$sqlCheckout	= "SELECT b.status,r.checkout,r.checkin,r.booking_no,b.mdoc_no as fo_bill_mdoc_no,b.id_fo_folio_to,r.id_mst_guest,b.id as id_fo_bill FROM ".FO_BILL." as b LEFT JOIN  `".FO_RESERVATIONS."` as r ON  b.`id_reservations`=r.id where b.status='1' 
//AND DATE(r.checkout)<='".$Dated."' and DATE(b.date_created)>'2023-01-01'";

$newDate	=	date('Y-m-d',strtotime($Dated));
	$searchDocumentType = " and DATE(`".FO_RESERVATIONS."`.checkin)='".addslashes($newDate)."' ";
	$searchDocumentTypeDetails = " AND DATE(`".FO_RESERVATIONS_DETAILS."`.dated)='".addslashes($newDate)."' ";
	
	
  $sqlCheckout="SELECT ".FO_RESERVATIONS_DETAILS.".*,`".FO_RESERVATIONS."`.* 
  FROM ".FO_RESERVATIONS_DETAILS." LEFT JOIN `".FO_RESERVATIONS."` ON   `".FO_RESERVATIONS_DETAILS."`.id_fo_reservations=".FO_RESERVATIONS.".id  
  where   
   ".FO_RESERVATIONS_DETAILS.".checkin_status ='0' AND ".FO_RESERVATIONS_DETAILS.".no_showoff ='0'  and `".FO_RESERVATIONS."`.booking_status IN ('1','2') 
  ".$searchDocumentType." ".$searchDocumentTypeDetails."
 
  group by id_fo_reservations,id_mst_room_types order by `".FO_RESERVATIONS."`.id desc";

$ResCheckout	=	mysqli_query($connNew,$sqlCheckout);		
if(mysqli_num_rows($ResCheckout)>0){
	
		$html	='
		<table id="myTable2" border="1"
        class="table table-striped   table-responsive table-bordered dataTable no-footer max-h2"
        style="background:#fff;display: table-caption;"><thead>
		<tr style="text-align: center; font-size: 14px;">
            <th class="wd-5" colspan="7">Checkin Pending</th><tr>
          <tr style="text-align: center; font-size: 14px;">
            <th class="wd-5">S.NO</th>
            <th class="wd-10">Guest Name</th>           
            <th class="wd-10">Res#</th>
			<th class="wd-10">Room Name</th>
			 <th class="wd-10">Checkin Pending</th>            
            <th class="wd-10">Checkin</th>
            <th class="wd-10">Checkout</th>


          </tr>
        </thead> <tbody>';	
	$ic=1;
	
	
	
	
	$room_quantity='0';
	
	
	while($rowResCheckout =  mysqli_fetch_object($ResCheckout)){
		$mdoc_no=selectColumn('fo_folio','mdoc_no',"WHERE  id = '".$rowResCheckout->id_fo_folio_to."' ");
		
		$GuestName	=	selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$rowResCheckout->id_mst_guest."'").' '.selectColumn(TBL_GUEST,'last_name'," WHERE `id` = '".$rowResCheckout->id_mst_guest."'");;
		
		$id_mst_room_no_allocation	=	selectColumn(FO_RESERVATIONS_DETAILS,'id_mst_room_no_allocation'," WHERE `id_fo_bill` = '".$rowResCheckout->id_fo_bill."'");
		$RoomNo= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$id_mst_room_no_allocation."'");
		
		
		$roomname	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowResCheckout->id_mst_room_types."'");
		
		$room_quantity	+=	1;
		
		
		$roomCheckinArray=array();
		$roomPIcked=0;
		
		
		
		$sqlOrderDetail2 = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($rowResCheckout->id_fo_reservations)."' and id_mst_room_types='".addslashes($rowResCheckout->id_mst_room_types)."'  group by order_by_room ");
			
		if(mysqli_num_rows($sqlOrderDetail2) >0 ){
			$RoomWiseArray=array();
			$roomNoshowoffArray=array();
			$rrcounter=1;
			$roomdetails='';
				while($rowOrderDetail2= mysqli_fetch_object($sqlOrderDetail2)){
					
							if($rowOrderDetail2->checkin_status==1 ){	
							$roomCheckinArray[]=$rowOrderDetail2->id_mst_room_no_allocation;
							}
							
							if($rowOrderDetail2->no_showoff==1){	
							$roomNoshowoffArray[]=$rowOrderDetail2->id_mst_room_no_allocation;
							}	
							
	}	
	
		}
		
	$sqlOrderDetailRoomData = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($rowResCheckout->id_fo_reservations)."' and  id_mst_room_types='".addslashes($rowResCheckout->id_mst_room_types)."' group by order_by_room ");
		
				
		
	if(mysqli_num_rows($sqlOrderDetailRoomData) >0 ){
			$RoomWiseArray=array();
			$roomname=array();
			$room_quantity='';
			$adults_per_room='';
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetailRoomData)){ 
				$roomname[]	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_room_types."'");
				$room_quantity	+=	1;//$rowOrderDetail->room_quantity;	
				$adults_per_room	+=	$rowOrderDetail->adults_per_room;	
				
				
				
				
				
			
				}
		}	
		
		
		
		
		
		
		
		
		$room	=	implode(',',array_unique($roomname));
		
		
		
		
		
if(is_array($roomCheckinArray) ){
			$roomPIcked = count($roomCheckinArray);
			}else{
			$roomPIcked = 0;
			}
		if(is_array($roomNoshowoffArray) ){
			$roomNoshowoffArrayCount = count($roomNoshowoffArray);
			}else{
			$roomNoshowoffArrayCount = 0;
			}
			
		$html	.='<tr style="text-align: center; font-size: 14px;">
				                    <td class="wd-5">'.$ic.'</td>
									<td  class="wd-10">'.$GuestName.'</td>
									
				                    <td  class="wd-10">'.$rowResCheckout->booking_no.'</td>
									<td  class="wd-10">'.$room.'</td>
									
				                     <td class="wd-10">'.(($room_quantity-$roomPIcked)-($roomNoshowoffArrayCount)).'</td>
				                    <td  class="wd-10">'.date('d-m-Y',strtotime($rowResCheckout->checkin)).'</td> 
				                    <td class="wd-10">'.date('d-m-Y',strtotime($rowResCheckout->checkout)).'</td> 
				                   
				                </tr>';
								$ic++;
	
	}$html	.='</tbody> </table>';
			$DateArray['status']=2;
			$DateArray['ContentData']=$html;
			$DateArray['msg']= 'One or more Room checkin are pending please complete the checkin+ and proceed day close';	
			echo json_encode($DateArray);		
			exit;
			

			
}


//===ROOM SHOW OFF START============================================================================

//die;

					$TodaysDate	=	date('Y-m-d');

					 $sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
					 $numRowsNightAudit =  mysqli_num_rows($sqlNightAudit);
					 $rowNightAudit =  mysqli_fetch_object($sqlNightAudit);
					 $Dated = date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated))); 
						
			$DateArray=array();			
		if(strtotime($TodaysDate)!=strtotime($Dated)  ){
			
				 $sql = "INSERT INTO `night_audit` SET `id_shop` = '".$_SESSION['shop']."',night_audit_date='".$Dated."'  ";
				
					if(mysqli_query($connNew,$sql)){
					$post_tariff_date	=	date('Y-m-d',strtotime('+1 day',strtotime($Dated)));//$Dated;
					$id_post_tariff='1';
					$id_fo_bill	= '';	
					$shop=$_SESSION['shop'];
					 $DateArray['status2']= $y= postAutoTariff($post_tariff_date,$id_post_tariff,$id_fo_bill,$shop,$connNew);
					//deubugData($y);
					
					include_once("nightAduitSendSalesSummary.php");
					
					$DateArray['status']=1;
					$DateArray['msg']="Night Audit Done Successfully";
					//$DateArray['dated']=date('d-m-Y',strtotime($Dated));
					$DateArray['dated'] = date('d-m-Y',strtotime('+1 day',strtotime($Dated)));
				}
				else{
					$DateArray['status']=0;
					$DateArray['msg']='Invalid Date';
				}
			
		}else{
			$DateArray['status']=0;
			$DateArray['msg']= 'Night Audit Already Update.';
			
			}
						
						
	echo json_encode($DateArray);



	
 ?>


