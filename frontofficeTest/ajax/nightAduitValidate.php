<?php
include_once("../../config/auto_loader.php");

include_once("../functions/function.php");

		 $TodaysDate	=	date('Y-m-d');
		 $sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
		 $numRowsNightAudit =  mysqli_num_rows($sqlNightAudit);
		 $rowNightAudit =  mysqli_fetch_object($sqlNightAudit);
		 //echo $Dated = date('Y-m-d',strtotime($rowNightAudit->dated)); 
$Dated = date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated)));
//print_r($_REQUEST);
$sqlCheckout	= "SELECT b.status,r.checkout,r.checkin,r.booking_no,b.mdoc_no as fo_bill_mdoc_no,b.id_fo_folio_to,r.id_mst_guest,b.id as id_fo_bill FROM ".FO_BILL." as b LEFT JOIN  `".FO_RESERVATIONS."` as r ON  b.`id_reservations`=r.id where b.status='1' 
AND DATE(r.checkout)<='".$Dated."' and DATE(b.date_created)>'2023-01-01'";


$ResCheckout	=	mysqli_query($connNew,$sqlCheckout);		
if(mysqli_num_rows($ResCheckout)>0){
	
			
	$ic=1;
	while($rowResCheckout =  mysqli_fetch_object($ResCheckout)){
		$mdoc_no=selectColumn('fo_folio','mdoc_no',"WHERE  id = '".$rowResCheckout->id_fo_folio_to."' ");
		
		$GuestName	=	selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$rowResCheckout->id_mst_guest."'").' '.selectColumn(TBL_GUEST,'last_name'," WHERE `id` = '".$rowResCheckout->id_mst_guest."'");;
		
		$id_mst_room_no_allocation	=	selectColumn(FO_RESERVATIONS_DETAILS,'id_mst_room_no_allocation'," WHERE `id_fo_bill` = '".$rowResCheckout->id_fo_bill."'");
		$RoomNo= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$id_mst_room_no_allocation."'");
		
		
		

		$html	.='<tr style="text-align: center; font-size: 14px;">
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
			/*$DateArray['status']='2';
			$DateArray['ContentData']=$html;
			$DateArray['msg']= 'One or more Room checkouts are pending please complete the checkout and proceed to checkin';	
			echo json_encode($DateArray);		
			exit;*/
	
		$DateArray['status']='1';
			$DateArray['ContentData']=$html;
			$DateArray['msg']= 'No Record';	
			echo json_encode($DateArray);		
			exit;
			

			
}else{
			$DateArray['status']='1';
			$DateArray['ContentData']=$html;
			$DateArray['msg']= 'No Record';	
			echo json_encode($DateArray);		
			exit;
	
	
	}


					 			
		
						
						
	echo json_encode($DateArray);



	
 ?>


