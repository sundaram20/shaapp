<?php 

include_once("../../config/auto_loader.php");
	$sql = executeSql(" SELECT * FROM fo_reservations order by id desc ");

 				 				 
								if($db->num_rows2($sql) > 0){
								$BookingArray=array(); $sin=1;
								  while($row = $db->fetch_object2($sql)){
									//  print_r($row);
									  
				 $id_mst_attributes_title	=	selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$row->id_mst_guest."'");				
	$Title=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title."'"); 				
						  
									  
									  
									  
									$guest = $Title.' '.selectColumn("mst_guest",'first_name'," WHERE `id` = '".$row->id_mst_guest."'").' '.selectColumn("mst_guest",'last_name'," WHERE `id` = '".$row->id_mst_guest."'");
									$hotel= selectColumn("mst_hotels",'name'," WHERE `id` = '".$row->id_mst_hotels."'");
									 $id= "'".encryptor('encrypt',$row->id)."'";
							 $booking_status		=	selectColumn('fo_booking_status','name'," WHERE  id='".addslashes($row->booking_status)."'");		  
									//  $booking_status		=	selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `table_name` = 'bookingstatus' and id='".addslashes($row->booking_status)."'");
  
								
							$id= "'".encryptor('encrypt',$row->id)."'";	
					//	$loop[]=         array("id"=>$row->id,'name'=$guest,'booking_no' => $row->booking_no,'hotel' => $hotel,,'booking_date' => ,'booking_status' => $booking_status);		
				$ids= "'".encryptor('encrypt',$row->id)."'";
				$Type= "'Edit'";			
     $Datalist.='<tr>
        <td class="sorting_1">'.$sin++.'</td>
        <td> '.$guest.' </td>
        <td>'.$row->booking_no.'</td>
		<td>'.$row->reference.'</td>
        <td>'.$hotel.'</td>
        <td>'.date('d-m-Y',strtotime($row->booking_date)).'</td>
        <td>'.date('d-m-Y',strtotime($row->checkin)).'</td>
        <td>'.date('d-m-Y',strtotime($row->checkout)).'</td>
        <td>'.$booking_status.'</td>';
        //$Datalist.='<td><a href="#" onclick="reservationDetails('.$ids.');"><img height="20px" src="../images/view2.png" style="cursor:pointer;" title="View  "></a>';
		$Datalist.='<td><a href="#" onclick="ReservationSingleForm('.$ids.','.$Type.');"><img height="20px" src="../images/view2.png" style="cursor:pointer;" title="View  "></a></td>
      </tr>	';
								  }
								}
						//$BookingArray= array('data'=>$loop);
	
	//echo json_encode($BookingArray);


?>      <div class="col-md-2 col-sm-3 col-xs-12"><h3 class="box-title"></h3></div>
         <table id="BookingList" class="display table table-bordered table-hover" style="width:100%">
        <thead>
            <tr>
				 <th>S No.</th>
                <th>Guest Name</th>
                <th>Res#</th>
				 <th>Other Reference</th>
                <th>Hotel Name</th>
                <th>Booking Date</th>
                <th>Checkin</th>
                <th>Checkout </th>
                <th>Status </th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php echo $Datalist; ?>
        </tbody>
    </table>