<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_PURCH,'view');

?>


<?php include_once("../includes/header.php")?>
  <?php include_once("../includes/left.php");

  if($_REQUEST['status'] == 'Pending'){

	$statuscase = " AND kot_status='Pending'" ;
  
  }elseif($_REQUEST['status'] == 'Billed'){
	$statuscase = " AND kot_status='Billed'"; 
	
  }elseif($_REQUEST['status'] == 'Cancelled'){
  $statuscase = " AND kot_status='Cancelled'"; 
   
  }else{
  if($_REQUEST['status'] == ''  && $_REQUEST['searchFormSubmit']==1){
                  $statuscase = " " ;
				  // $selected3 = 'selected="selected"';
                }else{
					 $statuscase = " AND kot_status='Pending'" ;
                  //$selected2 = 'selected="selected"';
                }
 //  $statuscase = " AND kot_status='Pending'" ;
   

	}
		
   if(isset($_REQUEST['checkin_radio']) && $_REQUEST['checkin_radio']==2 && $_REQUEST['datefilter']!=''){	
    $DateExplode = explode(' to ',$_REQUEST['datefilter']);
    $startDate = date('Y-m-d',strtotime($DateExplode['0']));
    $endDate  = date('Y-m-d',strtotime($DateExplode['1']));
    //$endDate = date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
      
    $searchDocumentType .= " AND DATE(`doc_date`) BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
  }// else{
    //  $searchDocumentType .= " AND DATE(`doc_date`) BETWEEN '".date('Y-m-d',strtotime('-1 days'))."' And '".date('Y-m-d')."'";
 // }
	
elseif(isset($_REQUEST['checkin_radio']) && $_REQUEST['checkin_radio']==1){	
	if($_REQUEST['reservation_checkindate'] != ''){
		//list($checkin,$checkout) = split(" to ",$_REQUEST['reservation_date']);	
		$splitArray= explode(" to ",$_REQUEST['reservation_checkindate']);
		$checkin = $splitArray['0'];
		$checkout = $splitArray['1'];
		
		//$cond .= " AND `".TBL_ORDERS."`.`checkin` BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime($checkout))."'";
		
		$searchDocumentType .= " AND DATE(`checkin`) BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime($checkout))."'";
		
		
		$fromPrint = $checkin;
		$toPrint = $checkout;
	}
}elseif(isset($_REQUEST['checkin_radio']) && $_REQUEST['checkin_radio']==3){
	
	if($_REQUEST['modified_datefilter'] != ''){
        $DateExplode = explode(' to ', $_REQUEST['modified_datefilter']);
        $startDate = date('Y-m-d', strtotime($DateExplode['0']));
        $endDate   = date('Y-m-d', strtotime($DateExplode['1']));
        $searchDocumentType .= " AND DATE(`last_modified`) BETWEEN '".date('Y-m-d', strtotime($startDate))."' AND '".date('Y-m-d', strtotime($endDate))."'";
    }

}else{
		
		
		$searchDocumentType .= " AND DATE(`doc_date`) BETWEEN '".date('Y-m-d',strtotime('-1 days'))."' And '".date('Y-m-d')."'";
		}
	
	
if($_REQUEST['search_name'] != ''){
	$sname	=explode('-',$_REQUEST['search_name']);
	$searchDocumentType = " AND pp.`booking_no` ='".addslashes($_REQUEST['search_name'])."'";

}
if($_REQUEST['other_reference_no'] != ''){
	$sname	=$_REQUEST['other_reference_no'];
	$searchDocumentType = " AND pp.`reference` ='".addslashes($_REQUEST['other_reference_no'])."'";

}

		
$SQL="SELECT *  from
".FO_RESERVATIONS." as pp WHERE pp.id!=0 and pp.doc_no>0".$statuscase11." ".$searchDocumentType." ORDER BY  pp.id desc
";

//echo $SQL;


$SqlKotList = mysqli_query($connNew, $SQL); 
$numRows=	mysqli_num_rows($SqlKotList);	        	 
$i=1;
  ?> <style>
    /* Style for the checkbox */
    .checkbox-button {
      display: none; /* Hide the actual checkbox */
    }

    /* Style for the label to create the button appearance */
    .checkbox-label {
      display: inline-block;
      padding: 10px 20px;
      font-size: 16px;
      font-weight: bold;
      color: #fff;
      background-color: #3498db;
      border: 1px solid #2980b9;
      cursor: pointer;
      user-select: none;
    }

    /* Style for the label when checkbox is checked */
    .checkbox-label input:checked + .custom-checkbox {
      background-color: #2980b9;
    }
  </style>
  <style>
  #EditCheckinModal{
  padding : 0!important;
}

#EditCheckinModal .modal-dialog{
width : 100%!important;
margin-top : 0!important;
margin-bottom : 0!important;
}

#EditCheckinModal .modal-dialog .modal-content{min-height: 100vh;}
  </style>
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
	
	 <?php $session=$_GET['submenu']; ?>
    <section class="content-header">
    <div class="row">
     <div class="col-md-4 col-xs-12"> 
      <h6 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
		<?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h6>
     </div>
     <div class="col-md-4 col-xs-12 dd-f">	
     
                 
                  
       
     </div> 
     <div class="col-md-4 col-xs-12 tb-br">	            
      <?php echo breadCrumbs(); ?>

     </div> 
    </div>
    </section> 
	  
	  
	  
	  
    <section class="content">
    <div class="box box-default">
        <div class="form-group has-error mb-0" align="center">
          <?php if($_SESSION['errorMsg']){?>
          <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
          <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
          <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
          <?php unset($_SESSION['successMsg']);}?>
			
			
        </div>
        <div class="box-header with-border">
			<div style="width:100%;float:left;">
					 <button class="btn btn-info" onclick="ReservationSingleForm('','New Reservation Form');" style="overflow-x: auto !important;float:right;">New Reservation</button>
					</div>
          <h6 class="box-title">Search <small> Records:(
            <?=$numRows;?>
            ) &nbsp;</small> </h6>
          <?php /*?><div class="btn-group  pull-right"> <a type="button" class="btn n-btn pull-right" href="managePosKot.php?submenu=<?php echo $_GET['submenu']; ?>" >Add <?php echo currentNavigation()['submenu']; ?> </a> </div><?php */?>
        </div>
        
        <!-- /.box-header -->
        <?php //debugData($_REQUEST);
			  //echo $SQL;?>
        <form name="searchForm" action="" method="get">
          <input type="hidden" value="1" name="searchFormSubmit" />
           <input type="hidden" value="<?php echo $_GET['session'] ?>" name="session" />
            <input type="hidden" value="<?php echo $_GET['submenu'] ?>" name="submenu" />
          <div class="box-body">
            <div class="row">
              <div class="col-md-2 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>Reservation No</label>
                  <input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />
                </div>
                
                <!-- /.form-group --> 
                
              </div>
              
              
              <div class="col-md-2 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>Other Reference No</label>
                  <input type="text" name="other_reference_no" id="other_reference_no" value="<?php echo trim($_REQUEST['other_reference_no']);?>" class="form-control" />
                </div>
                
                <!-- /.form-group --> 
                
              </div>
              
              <!-- /.col -->
              <!--col start-->
                <div class="form-group col-sm-2">
                       <?php //debugData($_REQUEST); ?>
                           <label for="booking_date"><input type="radio" name="checkin_radio" value="2" <?php if($_REQUEST['checkin_radio']=='2'){echo 'checked="checked"'; }else if(isset($_REQUEST['checkin_radio']) && $_REQUEST['checkin_radio']=='1'  ){}else{echo 'checked="checked"';}?>/>&nbsp;Booking Date : From - To</label>
                                <div class="input-group">
                      <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                          <input type="text" class="form-control pull-right"  placeholder="Select From -  To" name="datefilter" id="dateRangeReport" data-parsley-required value="<?php if($_REQUEST['datefilter']!=''){echo $_REQUEST['datefilter'];}else{ echo date('d-m-Y').' to '.date('d-m-Y'); }?>"   autocomplete="off">
                        </div>
                    </div>
				
				<div class="form-group col-sm-2">
    <label for="modified_date">
        <input type="radio" name="checkin_radio" value="3" 
            <?php if($_REQUEST['checkin_radio']=='3'){ echo 'checked="checked"'; } ?>
        />&nbsp;Modified Date : From - To
    </label>
    <div class="input-group">
        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
        <input type="text" class="form-control pull-right appdaterange" 
            id="modified_datefilter" 
            name="modified_datefilter" 
            placeholder="Select From - To"
            value="<?php echo isset($_REQUEST['modified_datefilter']) ? $_REQUEST['modified_datefilter'] : date('d-m-Y').' to '.date('d-m-Y'); ?>"
            autocomplete="off">
    </div>
</div>
                  
                  
                  <div class="form-group col-sm-2">
                    <label for="reservation_date"><input type="radio" name="checkin_radio" value="1" <?php if($_REQUEST['checkin_radio']=='1'){echo 'checked="checked"'; }else if(isset($_REQUEST['checkin_radio']) && $_REQUEST['checkin_radio']=='2'  ){}?>/>&nbsp;Checkin Date : From - To </label>
                    <div class="input-group">
                      <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                      <input type="text" class="form-control pull-right appdaterange" id="reservation_checkindate" placeholder="Enter Checkin date" name="reservation_checkindate"  value="<?php if(isset($_REQUEST['reservation_checkindate'])){ echo $_REQUEST['reservation_checkindate'];}else{ echo date('d-m-Y').' to '.date('d-m-Y');}?>"   automcomplete="off">
					   
                    </div>
                    <!-- /.input group -->
                    <span id="reservation_dateError"></span> </div>
                  
                    

              <!-- /.form-group -->
              <!--End col-->
              
              <?php /*?><div class="col-md-6">
                <div class="form-group">
                  <label>Outlet</label>
                  <?php $categoryDropDown = '<select class="form-control select2" name="outlet">

											    <option value="">Select Outlet</option>';

											  $resCat = selectSql(mst_outlets," where id_shop='".$_SESSION['shop']."' AND  status = '1' ",'');

											  if($db->num_rows2($resCat)){

											  	while($resultCat = $db->fetch_object2($resCat)){

													if($_REQUEST['outlet'] == $resultCat->id){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}

													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';

												}

											  }

											 	echo $categoryDropDown .= '</select>';

											  ?>
                </div>
              </div><?php */?>
              
                 
                  
              
              
              
              <!-- /.row --> 
              
            </div>

              <div class="box-footer pt-0 pl-0">
                 <input name="Search" type="submit" class="btn o-btn" value="Apply" />
             </div>
          </div>
          
          <!-- /.box-body -->
      
        </form>
      </div>
      
      <div class="row">
        <div class="col-xs-12"> 
          <!-- /.box -->
          <div class="box">
            <div class="box-header  table-h text-center">
             <h4 class="box-title">List Of <?php echo currentNavigation_id($session)['submenu']; ?> </h4>
              <small class="text-center has-error">
              <?php if($_SESSION['errorMsg']){?>
              <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
              <?php unset($_SESSION['errorMsg']);} elseif($_SESSION['successMsg']){?>
              <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
              <?php unset($_SESSION['successMsg']);}?>
              </small>  </div>
            <form name="listingForm" action="" method="post">
              <input type="hidden" value="" name="act" />
              <div id="listingDiv"></div>
              <!-- /.box-header -->
              <div class="box-body table-responsive">
                <table id="myTable" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" >
                  <thead>
                    <tr>
                      <th width="1%"> S.No.&nbsp;</th>
                      
                      <th>Reservation No</th>
						<th>Other Reference</th>
                      <th>Guest Name</th>
                      
                       <th>Hotel Name</th>
                      <th>Booking Date</th>
						<th>Modified Date</th>
                      <th>Check-in</th>
                      <th>Check-out</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php  
 //$resCat = selectSql("pos_purch_details WHERE qty-adj_qty>0 AND id_pos_purch  IN  (SELECT id FROM pos_purch WHERE pos_bill_type= '1')");

	                   //$db->query($CheckBlockedTable_Sql); 
					   //$i=1;

	                  //while($ResultBlockedtable1 = $db->fetch_object()){
						  
						  
		        	 //$resCat = selectSql(TBL_PURCH," where id_shop= '".addslashes($_SESSION['shop'])."' AND pos_bill_type=1 ORDER BY `date_created` desc"); 
		        	 
		        	 $i=1;
					 while($row = mysqli_fetch_object($SqlKotList)){ 
					  
					
					$Sqlsettled = mysqli_query($connNew,"SELECT * FROM pos_purch_details WHERE qty-adj_qty>0 AND id_pos_purch  IN  (SELECT id FROM pos_purch WHERE pos_bill_type= '1' AND id_pos_purch= '".$row->id."')");
					$countSettled = mysqli_num_rows($Sqlsettled);
					$rowSettled = mysqli_fetch_object($Sqlsettled);
				
					
					
						  ?>
						  
                    <tr>
                      <td><?php echo $i++;?></td>
                       
                      <td><?php echo $row->mdoc_no;//.'=='.$row->id;?></td>
					  <td><?php echo $row->reference;//.'=='.$row->id;?></td>
					 <?php 
						//$timestamp = strtotime($row->doc_date);
						 $timestamp = strtotime($row->date_created);
						$date = date('d-m-Y', $timestamp);
						$modified_data = strtotime($row->last_modified);
						 $modified_date = date('d-m-Y', $modified_data);
					  ?>
					  
                      <?php
					  
				  
				$guest = selectColumn("mst_guest",'first_name'," WHERE `id` = '".$row->id_mst_guest."'").' '.selectColumn("mst_guest",'last_name'," WHERE `id` = '".$row->id_mst_guest."'");
			 $hotel= selectColumn("mst_hotels",'name'," WHERE `id` = '".$row->id_mst_hotels."'"); 
			   //  echo $id_mst_guest;
			   //  echo 'id_mst_guest','WHERE id_fo_folio_to="'.$row->id_fo_folio_to.'"';
				

					$SQL2 = "select *  from ".TBL_GUEST." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  `id` = '".addslashes($id_mst_guest)."' ";
					//echo $SQL;


	
	$query2=mysqli_query($connNew, $SQL2);		
	$row2=mysqli_fetch_assoc($query2);
	$GuestTitle	=	selectColumn(TBL_ATTRIBUTES,'field_value','WHERE `id_shop`="'.addslashes($_SESSION['shop']).'" and id="'.$row2['id_mst_attributes_title'].'"'); 
	
$GuestName = $GuestTitle.' '.$guest;
  $booking_status		=	selectColumn('fo_booking_status','name'," WHERE  id='".addslashes($row->booking_status)."'");
						 
						// $booking_status		=	selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `table_name` = 'bookingstatus' and id='".addslashes($row->booking_status)."'");
		?>
                       <td><?php echo  $GuestName; ?></td> 
                         <td><?php echo  $hotel; ?></td> 
                       <td><?php echo $date  //<td><?=date('d-m-Y',strtotime($row->doc_date));?></td>
                     <td><?php echo  $modified_date; ?></td>
                       <td><?php echo $checkin_date= date('d-m-Y',strtotime($row->checkin));  ?></td> 
                       <td><?php echo $checkout_date= date('d-m-Y',strtotime($row->checkout));  ?></td> 
 <td><?php echo $booking_status  ?></td>
                      <td> 
                        <?php
                            $id_folio = '';
                            $reservation_detail_query = mysqli_query($connNew, "select * from fo_reservations_details where id_fo_reservations = '".$row->id."'");
                            $reservation_detail_result = mysqli_fetch_object($reservation_detail_query);
                            $id_folio = $reservation_detail_result->id_fo_folio_to>0?$reservation_detail_result->id_fo_folio_to:'0';
                            if ($id_folio == 0) {
                        ?>
                     
                     <a href="#" onclick="ReservationSingleForm('<?=encryptor(encrypt, $row->id);?>','Edit', '<?=$id_folio;?>');" ><img src="../images/edit.png" style="cursor:pointer;height:20px;" title="Edit Reservation"  /></a>&nbsp;&nbsp;
                    <?php 
                            }
					if($_SESSION['database']=='swanand' || $_SESSION['database']=='demo_swanand'){
						
						$pdf_file_name='swanand_generateOrderPdf.php';
						
					}elseif($_SESSION['database']=='tig' || $_SESSION['database']=='tig'){
						$pdf_file_name='generateOrderPdfTig.php';
					}else{
						
						$pdf_file_name='generateOrderPdf.php';
						
						}?> <a href="<?=$pdf_file_name;?>?id=<?=encryptor(encrypt, $row->id);?>"  target="_blank"><img src="../images/pdf-icon.png" style="cursor:pointer;height:20px;" title="Generate Voucher"  /></a>&nbsp;&nbsp;
                      <?php 
                       
                            $id_folio = '';
                            $reservation_detail_query = mysqli_query($connNew, "select * from fo_reservations_details where id_fo_reservations = '".$row->id."'");
                            $reservation_detail_result = mysqli_fetch_object($reservation_detail_query);
                            $id_folio = $reservation_detail_result->id_fo_folio_to>0?$reservation_detail_result->id_fo_folio_to:'0';
                            if ($id_folio == 0) {
                        
                       $res = $row->booking_no;
                        if($res != ""){
                          $res_no = "For '".$res."'";
                        }else{
                          $res_no = "";
                        }

                        $title = "Advance Payment " . $res_no;
                      ?>
                     <a onclick="openPaymentPopup('ajax/ajaxPaymentForm.php?id=<?=encryptor(encrypt, $row->id); ?>', '<?=addslashes($title);?>')"><img src="../images/bill-payment.png" style="cursor:pointer;height:20px;" title="Advance Payment"  /></a>
                      <?php } ?>

                      <?php 
                      $sql_paid = "SELECT SUM(amount) AS total_amount FROM fo_receipt WHERE id_reservation = '".$row->id."'";
                          $res_paid = executeSql($sql_paid);
                          $row_paid = mysqli_fetch_assoc($res_paid);
                          $total_paid = $row_paid['total_amount'] ?? 0;

                          if($total_paid!='0'){
                      ?>
                      <a id="printReceiptBtn"  onclick="printReceiptBtn('<?php echo encryptor(encrypt, $row->id);?>');" ><i class="fa fa-print"></i></a>
                            <?php } ?>
                      </td>
                      
                      
                       
                 <?php //} ?>    
                      
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </form>
            
            <!-- /.box-body --> 
          </div>
          <!-- /.box --> 
        </div>
        <!-- /.col --> 
      </div>
      <!-- /.row --> 
    </section>
    <!-- /.content --> 
  </div>
  <div class="row">
      	<div class="col-md-12">
      		      	<!--cancel pop start-->
		  <div id="cancelpop" class="well p-4" style="margin:0 15px;display: none;"> 
		  <form id="Formkotremarks" autocomplete="off">
          
		  <input type="hidden" id="pos_purch_id" name="pos_purch_id" value="<?php echo encryptor(decrypt, $_REQUEST['editKotid']); ?>">
            <div id="kot_mdoc_no"> </div>
		 	<div class="form-group">
		      <label for="title">Remarks</label>
		      
		      <textarea rows="4" cols="50" type="text" class="form-control input-sm" placeholder="Enter Remark" id="remark" name="remark" value="" data-parsley-required></textarea>
		    </div>
			
			
			<div class="form-group">
				 <label for="btn">&nbsp;<br><br></label>
                 <?php echo $StatusOfPaymentis;?>
				<button class="btn c-btn" onclick="ajaxCancelKot();" type="button"><i class="far fa-save"></i> Update</button>
				<button class="cancelpop_close btn c-btn"><i class="far fa-window-close"></i> Close</button>
			</div>
		  </form>
		</div>
		<!--cancel pop ends-->
      	</div>
 
        <!-- Reusable popup modal -->
<div class="modal fade" id="paymentPopup" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="popupTitle">Form</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="popupBody">
        <!-- AJAX content will load here -->
      </div>
    </div>
  </div>
</div>



      </div>
<!-- Checkin Modal -->
<div class="modal" id="EditReservationModal" tabindex="-1" role="dialog" aria-labelledby="EditReservationModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
           <div id="EditReservationForm">
           </div>
            
            
            
               
            
        </div>
    </div>
</div><!-- Checkin Modal -->
<div class="modal" id="EditCheckinModal" tabindex="-1" role="dialog" aria-labelledby="EditCheckinLabel">
    <div class="modal-dialog" role="document">
      
        <div class="modal-content">
           <div id="expectedarrivals_datalistInForm">
           </div>
           
        </div>
    </div>
</div>
<!--- End Checkin modal -->

<!--- End Checkin modal -->
<!-- Begain Booker By Model -->
<div class="modal fade" id="bookereditModal" tabindex="-1" role="dialog" aria-labelledby="bookereditModalLabel" style="width: 100%; height: 100%;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="roomratesModalLabel">Booker By Details</h4>
      </div>

      <div class="modal-body">
        <form method="post" id="newbookerpopupform" name="newbookerpopupform" data-parsley-validate="" autocomplete="off" novalidate>
          <input type="hidden" id="booker_EditCustomerID" name="booker_EditCustomerID" value="">

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control input-sm" placeholder="Enter first name" id="booker_first_name" name="booker_first_name" value="" data-parsley-required="">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control input-sm" placeholder="Enter last name" id="booker_last_name" name="booker_last_name" value="" data-parsley-required="">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="email">Email Id</label>
                <input type="text" name="booker_email" id="booker_email" class="form-control"  placeholder="Enter Email Id" automcomplete="off">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="mobile">Mobile No.</label>
                <input type="text" name="booker_mobile" id="booker_mobile" class="form-control" placeholder="Enter mobile number" automcomplete="off">
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="mobile">City</label>
                <input type="text" name="booker_city" id="booker_city" class="form-control" placeholder="Enter City"  automcomplete="off">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="mobile">Postcode</label>
                <input type="text" name="booker_postcode" id="booker_postcode" class="form-control" placeholder="Enter Postcode" automcomplete="off">
              </div>
            </div>
          </div>
          <div style="text-align:center">
            <!-- <input name="save" id="save" type="submit" class="btn btn-primary" value="Save" /> -->
            <input type="submit" name="submit" class="btn btn-primary" value="Save">           
             <button type="button" class="btn btn-danger" onClick="closeModalFooterButton();" >Close</button>
      
          </div>
        </form>
      </div>
      <div class="popup_align" style="display: inline-block; vertical-align: middle; height: 100%;"></div>
    </div>
  </div>

</div>

<?php include_once('sharedguestModal.php');?>
<div class="modal fade" id="guestNewaddeditModal" tabindex="-1" role="dialog"
  aria-labelledby="guestNewaddeditModalLabel" style="width: 100%; height: 100%;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="roomratesModalLabel">Guest Name Details New</h4>
      </div>

      <div class="modal-body">

       
        <form id="guestNewpopupform" data-parsley-validate="" autocomplete="off" method="post" action="" novalidate>
          <input type="hidden" id="EditCustomerID" name="EditCustomerID" value="">
          <input type="hidden" id="edit_order_by_room" name="edit_order_by_room" value="">
          <input type="hidden" id="id_edit_reservation" name="id_edit_reservation" value="">
          <?php // 1 for Owner and 2 for Sharer Guest?>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Title</label>
                <?php
		  $categoryDropDown = '<select name="Nametitle" id="Nametitle" class="form-control input-sm" data-parsley-required="">
           <option value="">-Select-</option>';
		   $resCat1 = selectSql(TBL_ATTRIBUTES," where  table_name ='title' AND status='1'  ");
									if($db->num_rows2($resCat1)){
										while($resultCat1 = $db->fetch_object2($resCat1)){
											if($row->id_mst_attributes_title == $resultCat1->id){
												$selected = 'selected="selected"';
											}else{
												$selected = '';
											}
											$categoryDropDown .= '<option '.$selected.' value="'.$resultCat1->id.'">'.$resultCat1->field_value.'</option>';
										}
									}	echo $categoryDropDown .= '</select>'; ?>
                <?php /*?> <select name="Nametitle" id="Nametitle" class="form-control input-sm"
                  data-parsley-required="">
                  <option value="">-Select-</option>
                  <option value="Dr.">Dr.</option>
                  <option value="Miss.">Miss.</option>
                  <option value="Mr.">Mr.</option>
                  <option value="Mrs.">Mrs.</option>
                  <option value="Ms.">Ms.</option>
                  <option value="Pr.">Pr.</option>
                  <option value="Prof.">Prof.</option>
                  <option value="Rev.">Rev.</option>
                  <option value="Group.">Group.</option>
                </select> <?php */ ?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control input-sm" placeholder="Enter first name" id="first_name"
                  name="first_name" value="" data-parsley-required="">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control input-sm" placeholder="Enter last name" id="last_name"
                  name="last_name" value="" data-parsley-required="">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="email">Email Id</label>
                <input type="text" name="email" id="email" class="form-control" placeholder="Enter Email Id"
                  automcomplete="off">
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="mobile">Mobile No.</label>
                <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Enter mobile number"
                  automcomplete="off">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="mobile">City</label>
                <input type="text" name="city" id="city" class="form-control" placeholder="Enter City"
                  automcomplete="off">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Country</label>
                <select class="form-control" name="id_country" id="id_country" data-parsley-required="">
                  <option value="">Select Country</option>
                  <option value="231">Afghanistan</option>
                  <option value="244">Aland Islands</option>
                  <option value="230">Albania</option>
                  <option value="38">Algeria</option>
                  <option value="39">American Samoa</option>
                  <option value="40">Andorra</option>
                  <option value="41">Angola</option>
                  <option value="42">Anguilla</option>
                  <option value="232">Antarctica</option>
                  <option value="43">Antigua and Barbuda</option>
                  <option value="44">Argentina</option>
                  <option value="45">Armenia</option>
                  <option value="46">Aruba</option>
                  <option value="24">Australia</option>
                  <option value="2">Austria</option>
                  <option value="47">Azerbaijan</option>
                  <option value="48">Bahamas</option>
                  <option value="49">Bahrain</option>
                  <option value="50">Bangladesh</option>
                  <option value="51">Barbados</option>
                  <option value="52">Belarus</option>
                  <option value="3">Belgium</option>
                  <option value="53">Belize</option>
                  <option value="54">Benin</option>
                  <option value="55">Bermuda</option>
                  <option value="56">Bhutan</option>
                  <option value="34">Bolivia</option>
                  <option value="233">Bosnia and Herzegovina</option>
                  <option value="57">Botswana</option>
                  <option value="234">Bouvet Island</option>
                  <option value="58">Brazil</option>
                  <option value="235">British Indian Ocean Territory</option>
                  <option value="59">Brunei</option>
                  <option value="236">Bulgaria</option>
                  <option value="60">Burkina Faso</option>
                  <option value="61">Burma (Myanmar)</option>
                  <option value="62">Burundi</option>
                  <option value="63">Cambodia</option>
                  <option value="64">Cameroon</option>
                  <option value="4">Canada</option>
                  <option value="65">Cape Verde</option>
                  <option value="237">Cayman Islands</option>
                  <option value="66">Central African Republic</option>
                  <option value="67">Chad</option>
                  <option value="68">Chile</option>
                  <option value="5">China</option>
                  <option value="238">Christmas Island</option>
                  <option value="239">Cocos (Keeling) Islands</option>
                  <option value="69">Colombia</option>
                  <option value="70">Comoros</option>
                  <option value="71">Congo, Dem. Republic</option>
                  <option value="72">Congo, Republic</option>
                  <option value="240">Cook Islands</option>
                  <option value="73">Costa Rica</option>
                  <option value="74">Croatia</option>
                  <option value="75">Cuba</option>
                  <option value="76">Cyprus</option>
                  <option value="16">Czech Republic</option>
                  <option value="20">Denmark</option>
                  <option value="245">Details Awaited</option>
                  <option value="77">Djibouti</option>
                  <option value="78">Dominica</option>
                  <option value="79">Dominican Republic</option>
                  <option value="80">East Timor</option>
                  <option value="81">Ecuador</option>
                  <option value="82">Egypt</option>
                  <option value="83">El Salvador</option>
                  <option value="84">Equatorial Guinea</option>
                  <option value="85">Eritrea</option>
                  <option value="86">Estonia</option>
                  <option value="87">Ethiopia</option>
                  <option value="88">Falkland Islands</option>
                  <option value="89">Faroe Islands</option>
                  <option value="90">Fiji</option>
                  <option value="7">Finland</option>
                  <option value="246">Foreigner</option>
                  <option value="8">France</option>
                  <option value="241">French Guiana</option>
                  <option value="242">French Polynesia</option>
                  <option value="243">French Southern Territories</option>
                  <option value="91">Gabon</option>
                  <option value="92">Gambia</option>
                  <option value="93">Georgia</option>
                  <option value="1">Germany</option>
                  <option value="94">Ghana</option>
                  <option value="97">Gibraltar</option>
                  <option value="9">Greece</option>
                  <option value="96">Greenland</option>
                  <option value="95">Grenada</option>
                  <option value="98">Guadeloupe</option>
                  <option value="99">Guam</option>
                  <option value="100">Guatemala</option>
                  <option value="101">Guernsey</option>
                  <option value="102">Guinea</option>
                  <option value="103">Guinea-Bissau</option>
                  <option value="104">Guyana</option>
                  <option value="105">Haiti</option>
                  <option value="106">Heard Island and McDonald Islands</option>
                  <option value="108">Honduras</option>
                  <option value="22">HongKong</option>
                  <option value="143">Hungary</option>
                  <option value="109">Iceland</option>
                  <option value="110">India</option>
                  <option value="111">Indonesia</option>
                  <option value="112">Iran</option>
                  <option value="113">Iraq</option>
                  <option value="26">Ireland</option>
                  <option value="29">Israel</option>
                  <option value="10">Italy</option>
                  <option value="32">Ivory Coast</option>
                  <option value="115">Jamaica</option>
                  <option value="11">Japan</option>
                  <option value="116">Jersey</option>
                  <option value="117">Jordan</option>
                  <option value="118">Kazakhstan</option>
                  <option value="119">Kenya</option>
                  <option value="120">Kiribati</option>
                  <option value="121">Korea, Dem. Republic of</option>
                  <option value="122">Kuwait</option>
                  <option value="123">Kyrgyzstan</option>
                  <option value="124">Laos</option>
                  <option value="125">Latvia</option>
                  <option value="126">Lebanon</option>
                  <option value="127">Lesotho</option>
                  <option value="128">Liberia</option>
                  <option value="129">Libya</option>
                  <option value="130">Liechtenstein</option>
                  <option value="131">Lithuania</option>
                  <option value="12">Luxemburg</option>
                  <option value="132">Macau</option>
                  <option value="133">Macedonia</option>
                  <option value="134">Madagascar</option>
                  <option value="135">Malawi</option>
                  <option value="136">Malaysia</option>
                  <option value="137">Maldives</option>
                  <option value="138">Mali</option>
                  <option value="139">Malta</option>
                  <option value="114">Man Island</option>
                  <option value="140">Marshall Islands</option>
                  <option value="141">Martinique</option>
                  <option value="142">Mauritania</option>
                  <option value="35">Mauritius</option>
                  <option value="144">Mayotte</option>
                  <option value="145">Mexico</option>
                  <option value="146">Micronesia</option>
                  <option value="147">Moldova</option>
                  <option value="148">Monaco</option>
                  <option value="149">Mongolia</option>
                  <option value="150">Montenegro</option>
                  <option value="151">Montserrat</option>
                  <option value="152">Morocco</option>
                  <option value="153">Mozambique</option>
                  <option value="154">Namibia</option>
                  <option value="155">Nauru</option>
                  <option value="156">Nepal</option>
                  <option value="13">Netherlands</option>
                  <option value="157">Netherlands Antilles</option>
                  <option value="158">New Caledonia</option>
                  <option value="27">New Zealand</option>
                  <option value="159">Nicaragua</option>
                  <option value="160">Niger</option>
                  <option value="31">Nigeria</option>
                  <option value="161">Niue</option>
                  <option value="162">Norfolk Island</option>
                  <option value="163">Northern Mariana Islands</option>
                  <option value="23">Norway</option>
                  <option value="164">Oman</option>
                  <option value="165">Pakistan</option>
                  <option value="166">Palau</option>
                  <option value="167">Palestinian Territories</option>
                  <option value="168">Panama</option>
                  <option value="169">Papua New Guinea</option>
                  <option value="170">Paraguay</option>
                  <option value="171">Peru</option>
                  <option value="172">Philippines</option>
                  <option value="173">Pitcairn</option>
                  <option value="14">Poland</option>
                  <option value="15">Portugal</option>
                  <option value="174">Puerto Rico</option>
                  <option value="175">Qatar</option>
                  <option value="176">Reunion Island</option>
                  <option value="36">Romania</option>
                  <option value="177">Russian Federation</option>
                  <option value="178">Rwanda</option>
                  <option value="179">Saint Barthelemy</option>
                  <option value="180">Saint Kitts and Nevis</option>
                  <option value="181">Saint Lucia</option>
                  <option value="182">Saint Martin</option>
                  <option value="183">Saint Pierre and Miquelon</option>
                  <option value="184">Saint Vincent and the Grenadines</option>
                  <option value="185">Samoa</option>
                  <option value="186">San Marino</option>
                  <option value="187">Sao Tome and Principe</option>
                  <option value="188">Saudi Arabia</option>
                  <option value="189">Senegal</option>
                  <option value="190">Serbia</option>
                  <option value="191">Seychelles</option>
                  <option value="192">Sierra Leone</option>
                  <option value="25">Singapore</option>
                  <option value="37">Slovakia</option>
                  <option value="193">Slovenia</option>
                  <option value="194">Solomon Islands</option>
                  <option value="195">Somalia</option>
                  <option value="30">South Africa</option>
                  <option value="196">South Georgia and the South Sandwich Islands</option>
                  <option value="28">South Korea</option>
                  <option value="6">Spain</option>
                  <option value="197">Sri Lanka</option>
                  <option value="198">Sudan</option>
                  <option value="199">Suriname</option>
                  <option value="200">Svalbard and Jan Mayen</option>
                  <option value="201">Swaziland</option>
                  <option value="18">Sweden</option>
                  <option value="19">Switzerland</option>
                  <option value="202">Syria</option>
                  <option value="203">Taiwan</option>
                  <option value="204">Tajikistan</option>
                  <option value="205">Tanzania</option>
                  <option value="206">Thailand</option>
                  <option value="33">Togo</option>
                  <option value="207">Tokelau</option>
                  <option value="208">Tonga</option>
                  <option value="209">Trinidad and Tobago</option>
                  <option value="210">Tunisia</option>
                  <option value="211">Turkey</option>
                  <option value="212">Turkmenistan</option>
                  <option value="213">Turks and Caicos Islands</option>
                  <option value="214">Tuvalu</option>
                  <option value="215">Uganda</option>
                  <option value="216">Ukraine</option>
                  <option value="217">United Arab Emirates</option>
                  <option value="17">United Kingdom</option>
                  <option value="21">United States</option>
                  <option value="218">Uruguay</option>
                  <option value="219">Uzbekistan</option>
                  <option value="220">Vanuatu</option>
                  <option value="107">Vatican City State</option>
                  <option value="221">Venezuela</option>
                  <option value="222">Vietnam</option>
                  <option value="223">Virgin Islands (British)</option>
                  <option value="224">Virgin Islands (U.S.)</option>
                  <option value="225">Wallis and Futuna</option>
                  <option value="226">Western Sahara</option>
                  <option value="227">Yemen</option>
                  <option value="228">Zambia</option>
                  <option value="229">Zimbabwe</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Guest type</label>
                <select name="user_type" id="user_type" class="form-control input-sm">
                  <option value="">-Select-</option>
                  <option value="VIP">VIP</option>
                  <option value="CIP">CIP</option>
                </select>
              </div>
            </div>
          </div>
          <div class="card text-dark bg-light">
            <div class="bg-primary text-center ">
              <h5 style="padding: 5px;">ID Proof Details</h5>
            </div>
            <hr />
            <div class="row">
              <div class="form-group col-md-6">
                <label for="proof_type">Id Proof Details</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-address-card"></i>
                  </div>
                  <select class="form-control" style="width: 100%;" id="proof_type" name="proof_type">
                    <?php if($row->proof_type == 1){ ?>
                    <option value="1" selected="selected">Voter Id</option>
                    <option value="2">Adhar</option>
                    <option value="3">Passport</option>
                    <option value="4">Form C</option>
                    <?php }else if($row->proof_type == 2){?>
                    <option value="2" selected="selected">Adhar</option>
                    <option value="1">Voter Id</option>
                    <option value="3">Passport</option>
                    <?php }else if($row->proof_type == 3){?>
                    <option value="1">Voter Id</option>
                    <option value="2">Adhar</option>
                    <option value="3" selected="selected">Passport</option>
                    <?php }else{ ?>
                    <option selected="selected" value="">Select Id Proof</option>
                    <option value="1">Voter Id</option>
                    <option value="2">Adhar</option>
                    <option value="3">Passport</option>
                    <?php } ?>

                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div id="appenddata">
                <?php if($row->proof_type == 1){ ?>
                <div class="form-group col-md-6">
                  <label for="voter_no">Voter Id Number <font color="#FF0000">*</font></label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa fa-address-book"></i>
                    </div>
                    <input type="text" class="form-control" id="voter_no" name="voter_no"
                      placeholder="Enter Voter Id Number"
                      value="<?php if($_POST['voter_no']) echo $_POST['voter_no']; else echo $row->voter_no;?>"
                      data-parsley-errors-container="#voter_noError" data-parsley-required />
                  </div>
                  <span id="voter_noError"><?php echo $err_voter_noError;?></span>
                </div>
                <?php }else if($row->proof_type == 2){ ?>
                <div class="form-group col-md-6">
                  <label for="adhar_no">Adhar Number <font color="#FF0000">*</font></label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa fa-address-book"></i>
                    </div>
                    <input type="text" class="form-control" id="adhar_no" name="adhar_no"
                      placeholder="Enter Aadhar Number"
                      value="<?php if($_POST['adhar_no']) echo $_POST['adhar_no']; else echo $row->adhar_no;?>"
                      data-parsley-errors-container="#adhar_noError" data-parsley-required />
                  </div>
                  <span id="adhar_noError"><?php echo $err_adhar_noError;?></span>
                </div>
                <?php }else if($row->proof_type == 3){ ?>
                <div class="form-group col-md-6">
                  <label for="passport_no">Passport Number <font color="#FF0000">*</font></label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa fa-address-book"></i>
                    </div>
                    <input type="text" class="form-control" id="passport_no" name="passport_no"
                      placeholder="Enter Passport Number"
                      value="<?php if($_POST['passport_no']) echo $_POST['passport_no']; else echo $row->passport_no;?>"
                      data-parsley-errors-container="#passport_noError" data-parsley-required />
                  </div>
                  <span id="passport_noError"><?php echo $err_passport_noError;?></span>
                </div>
                <div class="form-group col-md-6">
                  <label for="authority">Authority<font color="#FF0000">*</font></label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-arrows"></i>
                    </div>
                    <input type="text" class="form-control" id="authority" name="authority"
                      placeholder="Enter Authority"
                      value="<?php if($_POST['authority']) echo $_POST['authority']; else echo $row->authority;?>"
                      data-parsley-errors-container="#authorityError" data-parsley-required />
                  </div>
                  <span id="authorityError"><?php echo $err_authorityError;?></span>
                </div>
                <div class="form-group col-md-6">
                  <label for="passport_expiry_date">Passport Expiry Date<font color="#FF0000">*</font></label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar-minus-o"></i>
                    </div>
                    <input type="text" class="form-control datepicker" id="passport_expiry_date"
                      name="passport_expiry_date" placeholder="dd-mm-yyyy"
                      value="<?php if($_POST['passport_expiry_date']) echo $_POST['passport_expiry_date']; else echo date('d-m-Y',strtotime($row->passport_expiry_date));?>"
                      data-parsley-errors-container="#passport_expiry_dateError" data-parsley-required />
                  </div>
                  <span id="passport_expiry_dateError"><?php echo $err_passport_expiry_dateError;?></span>
                </div>

                <div class="form-group col-md-6">
                  <label for="passport_expiry_date">Visa Expiry Date<font color="#FF0000">*</font></label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar-minus-o"></i>
                    </div>
                    <input type="text" class="form-control datepicker" id="visa_expiry_date" name="visa_expiry_date"
                      placeholder="dd-mm-yyyy"
                      value="<?php if($_POST['visa_expiry_date']) echo $_POST['visa_expiry_date']; else echo date('d-m-Y',strtotime($row->visa_expiry_date));?>"
                      data-parsley-errors-container="#visa_expiry_dateError" data-parsley-required />
                  </div>
                  <span id="visa_expiry_dateError"><?php echo $err_visa_expiry_dateError;?></span>
                </div>

                <div class="form-group col-md-6">
                  <label for="cform_expiry_date">C Form Expiry Date<font color="#FF0000">*</font></label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar-minus-o"></i>
                    </div>
                    <input type="text" class="form-control datepicker" id="cform_expiry_date" name="cform_expiry_date"
                      placeholder="dd-mm-yyyy"
                      value="<?php if($_POST['cform_expiry_date']) echo $_POST['cform_expiry_date']; else echo date('d-m-Y',strtotime($row->cform_expiry_date));?>"
                      data-parsley-errors-container="#cform_expiry_dateError" data-parsley-required />
                  </div>
                  <span id="cform_expiry_dateError"><?php echo $err_cform_expiry_dateError;?></span>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
          <div style="text-align:center">
            <input type="button" class="btn btn-primary" onClick="saveGuestNewPopupform();" value="Save">
            <button type="button" class="guest_close btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
          </div>
      </div>
      </form>
    </div>
    <div class="popup_align" style="display: inline-block; vertical-align: middle; height: 100%;"></div>
  </div>
</div
  ><?php include_once("../includes/footer.php")?>

 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


<script>
	 function ReservationSingleForm(id, BookingType, id_folio){ 
    var id_hotel = '1';

    // 1. OPEN MODAL FIRST
    $("#EditReservationModal").modal('show');

 $('#EditReservationForm').html(`
    <div style="
        width:100%;
        height:90vh;
        display:flex;
        justify-content:center;
        align-items:center;
        flex-direction:column;
        background:#fff;
    ">
        <i class="fa fa-spinner fa-spin" style="font-size:35px;color:#3498db;"></i>
        <p style="margin-top:10px;">Loading reservation...</p>
    </div>
`);

    // 3. CALL AJAX
    $.ajax({
        type: "POST",
        url: 'ajax/ReservationSingleForm.php',
        data: {
            id: id,
            BookingType: BookingType,
            id_folio: id_folio,
             id_hotel: id_hotel
        },
        success: function (result) {
            // 4. REPLACE LOADER WITH ACTUAL CONTENT
            $('#EditReservationForm').html(result);

            $(".select3").select2({});
            LoadRoomType(id_hotel,'');
            checkTableBody();
        },
        error: function () {
            $('#EditReservationForm').html(`
                <div style="text-align:center; padding:50px; color:red;">
                    Failed to load data
                </div>
            `);
        }
    });
}
/* function ReservationSingleForm(id,BookingType,id_folio){ 
			var id_hotel = $("#id_hotel").val();
				$.ajax({
				type: "POST",
				url: 'ajax/ReservationSingleForm.php',
				data: 'id='+id+'&BookingType='+BookingType+'&id_folio='+id_folio,
				success: function (result) {
					$("#EditReservationModal").modal('show');
					$('#EditReservationForm').html(result);	
					 $(".select3").select2({});checkTableBody();
         
         
				}
		});
        
		}*/
	  function saveReservationSingleForm(){

	var id_mst_hotels = $("#id_mst_hotels_new").val();
	var id_mst_company_new = $("#id_mst_company_new").val();
	var id_mst_company_contacts_new = $("#id_mst_company_contacts_new").val();
	var res_bookingStatus_new = $("#res_bookingStatus_new").val();
	var id_mst_guest_form = $("#id_mst_guest_form").val();
	 var res_payment_status = $("#res_payment_status").val();
		if ($('#tableBody').children().length == 0) {
		alert('Please Add Room');
		return false;
	}


	if(id_mst_hotels.trim() === ""){
      document.querySelector(".id_mst_hotels_new-error").innerHTML = 
      "This value is required.";
 
      document.querySelector(".id_mst_hotels_new-error").style.display = 
      "block";
 
     // return false;
   }else{ document.querySelector(".id_mst_hotels_new-error").innerHTML = 
      "";
	   document.querySelector(".id_mst_hotels_new-error").style.display = 
      "none";
	   }
	  if (res_payment_status.trim() === "") {
      document.querySelector(".res_payment_status-error").innerHTML =
        "This value is required.";

      document.querySelector(".res_payment_status-error").style.display =
        "block";


    } else {
      document.querySelector(".res_payment_status-error").innerHTML =
        "";
      document.querySelector(".res_payment_status-error").style.display =
        "none";
    } 
	   
   if(id_mst_company_new.trim() === ""){
      document.querySelector(".id_mst_company_new-error").innerHTML = 
      "This value is required.";
 
      document.querySelector(".id_mst_company_new-error").style.display = 
      "block";
 
     
   }else{
	    document.querySelector(".id_mst_company_new-error").innerHTML = 
      "";
	   document.querySelector(".id_mst_company_new-error").style.display = 
      "none";
	   } 
	   
	   
  if(id_mst_company_contacts_new.trim() === ""){
      document.querySelector(".id_mst_company_contacts_new-error").innerHTML = 
      "This value is required.";
 
      document.querySelector(".id_mst_company_contacts_new-error").style.display = 
      "block";
 
     
   }else{
	    document.querySelector(".id_mst_company_contacts_new-error").innerHTML = 
      "";
	   document.querySelector(".id_mst_company_contacts_new-error").style.display = 
      "none";
	   } 
	   
	 if(res_bookingStatus_new.trim() === ""){
      document.querySelector(".res_bookingStatus_new-error").innerHTML = 
      "This value is required.";
 
      document.querySelector(".res_bookingStatus_new-error").style.display = 
      "block";
 
     
   }else{
	    document.querySelector(".res_bookingStatus_new-error").innerHTML = 
      "";
	   document.querySelector(".res_bookingStatus_new-error").style.display = 
      "none";
	   } 
	   
	  	   
 if(id_mst_guest_form.trim() === ""){
      document.querySelector(".id_mst_guest_form-error").innerHTML = 
      "This value is required.";
 
      document.querySelector(".id_mst_guest_form-error").style.display = 
      "block";
 
     // return false;
   }else{ document.querySelector(".id_mst_guest_form-error").innerHTML = 
      "";
	   document.querySelector(".id_mst_guest_form-error").style.display = 
      "none";
	   }
   
   
   if(id_mst_hotels.trim() === "" || id_mst_company_new.trim() === "" || id_mst_company_contacts_new.trim() === "" || res_bookingStatus_new.trim() === "" || id_mst_guest_form.trim() === "" || res_payment_status.trim() === "" ){
	    return false;
   }
   
	var form=$("#saveReservationDateform");


$.ajax({

        type: "POST",

        url: 'ajax/checkReservationInventory.php',

        data: form.serialize(),

        dataType: 'json',

        success: function (inventoryResponse) {

            if (inventoryResponse.status == '0') {

                // Inventory not available
                $('.loading').hide();

                //alert(inventoryResponse.message);
                bootbox.alert(inventoryResponse.message);
                return false;
            }



	if(form.parsley().validate()){

	$('.loading').css('display','flex');

	$.ajax({

	   type: "POST",

	   url: 'ajax/ajaxsaveReservationSingleForm.php',

	   data: form.serialize(), 

	   success: function (result) {
		var response = JSON.parse(result);
		alert(response.message);
		$("#EditReservationModal").modal("hide");
		
		if(response.id_follio!='0'){
		InvoiceDetails(response.id_follio);   
		}

		},

	  complete: function(){

		$('.loading').hide();

	  }

	});

	return false;

	}
 }

      });
	}
	
	function getCompanyContact(companyId,contactId){	

		$.ajax({

			   type: "GET",

			   url: 'ajax/ajaxCompanyContacts.php',

			   data: 'companyId='+companyId+'&contactId='+contactId, 

			   success: function (result) {				   

			     $('#id_mst_company_contacts_new').empty();

				 $('#id_mst_company_contacts_new').html(result);

				 

				}

		});

} 
function tariffCalculationNew(uncode){
	
	
	
	if(uncode==''){
	var res_tariff_per_room_per_night = $("#res_tariff_per_room_per_night").val();
	}else{
		var res_tariff_per_room_per_night = $("#tariff_per_room_per_night_"+uncode).val();
		}
	
			//alert('LOad');
	
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxNewTariffExceCalculation.php',
		   data: {"res_tariff_per_room_per_night" : res_tariff_per_room_per_night,"uncode":uncode},
		   success: function (result) {				
		  
					
					result = JSON.parse(result);
					
					if(result.uncode==''){

					$("#res_tax").val(result.total_taxes);
					$("#res_tariff_per_room_inclusive_tax").val(result.total);
					}else{
						$("#perday_tax_"+result.uncode).val(result.total_taxes);
					$("#tariff_per_room_inclusive_tax_"+result.uncode).val(result.total);
					TotoalTarffiData();
						}
					
				
				
						
			}
			
		})
		 
 
	
}
	 $(document).ready(function () {
    $(document).on('change', '#proof_type', function () {

      var idProof = $(this).val();

      if (idProof == 1) {
        var Vote_Id =
          '<div class="form-group col-md-6"><label for="voter_no">Voter Id Number <font color="#FF0000">*</font></label><div class="input-group"><div class="input-group-addon"><i class="fa fa fa-address-book"></i></div><input type="text" class="form-control" id="voter_no" name="voter_no" placeholder="Enter Voter Id Number" data-parsley-errors-container="#voter_noError" data-parsley-required /></div><span id="voter_noError"><?php echo $err_voter_noError;?></span></div>';
        $("#appenddata").html(Vote_Id);
      } else if (idProof == 3) {
        var pass =
          '<div class="form-group col-md-6"> <label for="passport_no">Passport Number <font color="#FF0000">*</font></label><div class="input-group"> <div class="input-group-addon"><i class="fa fa fa-address-book"></i></div><input type="text" class="form-control" id="passport_no" name="passport_no" placeholder="Enter Passport Number" data-parsley-errors-container="#passport_noError" data-parsley-required /> </div> <span id="passport_noError"><?php echo $err_passport_noError;?></span></div><div class="form-group col-md-6">  <label for="authority">Authority<font color="#FF0000">*</font></label><div class="input-group"><div class="input-group-addon"><i class="fa fa-arrows"></i></div><input type="text" class="form-control" id="authority" name="authority" placeholder="Enter Authority" data-parsley-errors-container="#authorityError" data-parsley-required /></div><span id="authorityError"><?php echo $err_authorityError;?></span></div><div class="form-group col-md-6"> <label for="passport_expiry_date">Passport Expiry Date<font color="#FF0000">*</font></label><div class="input-group"><div class="input-group-addon"><i class="fa fa-calendar-minus-o"></i></div><input type="date" class="form-control datepicker" id="passport_expiry_date" name="passport_expiry_date" placeholder="dd-mm-yyyy" data-parsley-errors-container="#passport_expiry_dateError" data-parsley-required /></div><span id="passport_expiry_dateError"><?php echo $err_passport_expiry_dateError;?></span></div>';

        pass +=
          '<div class="form-group col-md-6"> <label for="visa_expiry_date">Visa Expiry Date<font color="#FF0000">*</font></label><div class="input-group"><div class="input-group-addon"><i class="fa fa-calendar-minus-o"></i></div><input type="date" class="form-control datepicker" id="visa_expiry_date" name="visa_expiry_date" placeholder="dd-mm-yyyy" data-parsley-errors-container="#visa_expiry_dateError" data-parsley-required /></div><span id="visa_expiry_dateError"><?php echo $err_visa_expiry_dateError;?></span></div> ';

        pass +=
          '<div class="form-group col-md-6"> <label for="cform_expiry_date">C Form Expiry Date<font color="#FF0000">*</font></label><div class="input-group"><div class="input-group-addon"><i class="fa fa-calendar-minus-o"></i></div><input type="date" class="form-control datepicker" id="cform_expiry_date" name="cform_expiry_date" placeholder="dd-mm-yyyy" data-parsley-errors-container="#cform_expiry_dateError" data-parsley-required /></div><span id="cform_expiry_dateError"><?php echo $err_cform_expiry_dateError;?></span></div> ';


        $("#appenddata").html(pass);

      } else if (idProof == 2) {
        var Aadhar =
          '<div class="form-group col-md-6"><label for="adhar_no">Aadhar Number <font color="#FF0000">*</font></label><div class="input-group"><div class="input-group-addon"><i class="fa fa fa-address-book"></i></div><input type="text" class="form-control" id="adhar_no" name="adhar_no" placeholder="Enter Adhar Number" data-parsley-errors-container="#adhar_noError" data-parsley-required /></div><span id="adhar_noError"><?php echo $err_adhar_noError;?></span></div>';
        $("#appenddata").html(Aadhar);
      } else {
        $("#appenddata").html('<div></div>');
      }
    });
  });
 function saveRoomStatusForm(button){
      var form = $(button).closest('form');
      var rm_id = form.find('.rm_id').val();
      // var cur_room_status = form.find('.cur_room_status').val();
      event.preventDefault();
    var formData = form.serialize();
    $.ajax({
              url: "ajax/ajaxRoomStatusForm.php",
              data: formData,
               type: "POST",
              success: function(data) {                
                $("#EditRoomStatusModal"+rm_id).modal("hide");
                 alert(data);
				 setRoomTypeUrl('');
                 //window.location.reload();
          //  roomStats();
              }
          });
      }	
  

      function setRoomTypeUrl(roomTypeName) {   
        var frmData = {
          flt1:roomTypeName,
        }
        $.ajax({
              url: "ajax/ajaxAllRoom.php",
              data: frmData,
               type: "GET",
              success: function(data) {
				  $("#ViewSelectedRoom").html(data);                
                // alert(data);
          //  roomStats();
              }
          });
    /* var newUrl = window.location.href + "&room_type=" + roomTypeName;
    window.location.href = newUrl; */
} 
	
	function PostCharges(id,BookingType){
		//function ReservationSingleForm(id,BookingType){ 
			var id_hotel = $("#id_hotel").val();
				$.ajax({
				type: "POST",
				url: 'ajax/PostTariffSingleForm.php',
				data: 'id='+id+'&BookingType='+BookingType,  
				success: function (result) {
					$("#EditReservationModal").modal('show');
					$('#EditReservationForm').html(result);	
					 $(".select3").select2({});
         
         
				}
		});
        
		
		}
	
function saveGuestNewPopupform(){	

	var form=$("#guestNewpopupform");

	if(form.parsley().validate()){

	$('.loading').show(); 

	$.ajax({

	   type: "POST",

	   url: 'ajax/ajaxSaveGuestNew.php',

	   data: form.serialize(), 

	   success: function (result) {

		  if(result!=''){

		    $('#id_mst_guest_form').empty();

			$('#id_mst_guest_form').html(result);

			$('#showGuest').click();

			$("#guestNewpopupform")[0].reset();
 			$("#guestNewaddeditModal").modal("hide");
			alert("Guest added sucessfully ");
		  }

		},

	  complete: function(){

		$('.loading').hide();

	  }

	});

	return false;

	}

}

function ValidateRoomSelected(roomNumber, roomId, checkboxValue) {
 // Create an array to store the values of checked checkboxes
    var selectedValues = [];

    // Iterate through all checkboxes with the specified name attribute
    $('input[name="expected_arrivals_rooms[]"]:checked').each(function() {
        // Push the value of each checked checkbox to the array
		 var selectedCount = selectedValues.length;
		
		 if(selectedValues.length!=roomNumber){ //alert('Count= '+selectedValues.length)
        		selectedValues.push($(this).val());
				$('input[name="expected_arrivals_rooms[]"][id="myCheckboxData_' + roomNumber + '_' + roomId + '"]').not(this).prop('disabled', false);
				if(selectedValues.length==roomNumber){ 
					
					$('input[name="expected_arrivals_rooms[]"][id="myCheckboxData_' + roomNumber + '_' + roomId + '"]').not(this).prop('disabled', true);
					var myArray = selectedValues.length;
					for (var i = 0; i < myArray; i++) { 
					
					$('input[name="expected_arrivals_rooms[]"][class="roomdata_' + roomNumber + '_' + roomId + '_' + selectedValues[i] + '"]').not(this).prop('disabled', false);
					
					}
	
	
	}
		 }else{
		 var checkbox = $('input[name="expected_arrivals_rooms[]"].roomdata_' + roomNumber + '_' + roomId + '_' + checkboxValue);
			checkbox.prop('checked', false);
			
			
			 }
			 
			 
			 
			 
    });
$("#RoomCountSelected_" + roomNumber + "_" + roomId).html('Selected Room('+selectedValues.length+')');
    console.log("Selected checkbox values: " + selectedValues.join(', '));
    console.log("Number of selected checkboxes: " + selectedCount);
			
}

function viewAlloaction(id_fo_reservations) {
$("#EditCheckinModal").modal('show');
			searchArrivalsInForm(id_fo_reservations);
}

function searchArrivalsInForm(id_fo_reservations){

var expectedarrivals= $("#expectedarrivals").val();
                $.ajax({
                    'url':'checkInAndAllocation.php',
                    'data':'date='+expectedarrivals+'&id_fo_reservations='+id_fo_reservations,
                   
                    success:function(data){ 
					
							$("#expectedarrivals_datalistInForm").html(data);
					var LoadAllocationText= $("#LoadAllocationText").val(); 
					$('#alloctiontitle').html(LoadAllocationText);
					
					
					 }
                });
}



function getRoomDetailsSingleForm(resId,pending,userid,id_mst_room_types){
    //alert(userid);
     //$("#tr").html(tableData).toggle();
	$.ajax({
			   type: "GET",
			   url: 'ajax/nightAduitValidate.php',
			   data: 'folio_split=', 
			   success: function (result) {
				  var response = JSON.parse(result);
				 
				  if(response.status=='1'){
				  	 nightAduitValidateSingleForm(resId,pending,userid,id_mst_room_types);
					// $("#EditCheckinModal").modal("hide");
				  }else{
					   alert(response.msg);
					  }
				  
				  
				//return '2';
				 }
			})
	
	 
     
    }
	
//var  selectedValues = [];
function ResetValidateRoomSelected() {
    selectedValues = []; // Reset selectedValues array
    // Reset UI elements or checkboxes based on your application's logic
    // Example: Reset checkboxes to unchecked state
    $('input[name="expected_arrivals_rooms[]"]').prop('checked', false);
    // Example: Reset UI element showing count of selected rooms
    $(".roomCountDisplay").html('Selected Room (0)');
    console.log("Selections reset.");
}	
	
	
	
	function getRoomDetails(resId,pending,userid,id_mst_room_types){
    //alert(userid);
     //$("#tr").html(tableData).toggle();
	$.ajax({
			   type: "GET",
			   url: 'ajax/nightAduitValidate.php',
			   data: 'folio_split=', 
			   success: function (result) {
				  var response = JSON.parse(result);
				 
				  if(response.status=='1'){ 
				  	 nightAduitValidate(resId,pending,userid,id_mst_room_types);
				  }else{
					   alert(response.msg);
					  }
				  
				  
				//return '2';
				 }
			})
	
	 
     
    }
	
	 function nightAduitValidateSingleForm(resId,pending,userid,id_mst_room_types){
    
     //$("#tr").html(tableData).toggle();
	
	  ResetValidateRoomSelected();
	 
	   $('.Exparrivals').hide();
      var tr = "#tr_"+resId+"_"+id_mst_room_types;
      $.ajax({
        url : 'ajax/ajaxGetRooms.php',
        type : 'POST',
        data : {resId:resId,Id:userid,id_mst_room_types:id_mst_room_types},
       
        success : function(resp){
			var result = JSON.parse(resp);
			//var tableData = 'werwe';
			$(tr).html(result.rr).toggle();
			
		}
      });
    }
	
	function RoomAllocationsingleForm(resvId,id_mst_room_types){
  
		
		var expected_arrivals_rooms = $("input[name='expected_arrivals_rooms[]']:checked").map(function(){return $(this).val();}).get();
		if(expected_arrivals_rooms!=''){
        $.ajax({
          url: 'ajax/ajaxRoomAllocationsingleForm.php',
          type: 'POST',
          data: 'resvId='+resvId+'&id_mst_room_types='+id_mst_room_types+'&dataselected='+expected_arrivals_rooms,//{resvId : resvId,id_mst_room_types:id_mst_room_types},
          //dataType: 'JSON',
          success : function(data){
			  //searchArrivals();
			  // roomStats();
            bootbox.alert(data);
			//reloadCalander();
			//$("#EditCheckinModal").modal("hide");
			
          }
        });
    }
    else{
       bootbox.alert("Please Select a room");
    }
    
  }
	function nightAduitValidate(resId,pending,userid,id_mst_room_types){
  //  alert(userid);
     //$("#tr").html(tableData).toggle();
	
	  ResetValidateRoomSelected();
	 
	   $('.Exparrivals').hide();
      var tr = "#tr_"+resId+"_"+id_mst_room_types;
      $.ajax({
        url : 'ajax/ajaxGetRooms.php',
        type : 'POST',
        data : {resId:resId,Id:userid,id_mst_room_types:id_mst_room_types},
       
        success : function(resp){
			var result = JSON.parse(resp);
			//var tableData = 'werwe';
			$(tr).html(result.rr).toggle();
		//ValidateRoomSelected.reset();
		 
		  // selectedValues = [];
		   	/*
         
		 //resp= JSON.parse(resp);
          var tableData = '';
		  var roomid=[];
           tableData += `<td colspan="9"><div class="row">
                       <div class="col-md-12 col-sm-12">
                         <div class="box box-primary box-outline">
                           <div class="box-body">`;
                            resp.forEach((value,key,arr) => { //alert(value.TotalPendingCheckin);
							//arra[key]=(value.room_id);
                            tableData += `
                              <div class="row" >
                                <div class="col-md-12 col-sm-12">
                                  <h4>${value.RoomName} <span id="roomTypeName_${resId}_${value.room_id}" class="text-primary"></span></h4> 
                                </div>

                                <div class="col-md-12">
                                  <div class="row text-center">`;
								  
								   value.roomCheckinArray.forEach((datavalue1,keys,arrays) => {
								  tableData += `<div class="col-md-1 col-sm-1 col-xs-1"  style="padding-right:0px; margin-top: 2px; margin-left:0px; margin-right: 0px;"><button disabled="true" class="btn btn-block btn-danger"  onclick="selectRoom(${value.room_id},${datavalue1},this.id,'`+ resId +`',`+ pending +`,`+ value.TotalPendingCheckin +`);" id="btn-`+resId+`-${datavalue1}">${datavalue1}</button></div> `;
 									});
								  
								  value.roomReservedArray.forEach((Reserveddatavalue,keys,arrays) => {
								 if(Reserveddatavalue!='0'){
									 tableData += `<div class="col-md-1 col-sm-1 col-xs-1"  style="padding-right:0px; margin-top: 2px; margin-left:0px; margin-right: 0px;"><button class="btn btn-block btn-warning"  onclick="selectRoom(${value.room_id},${Reserveddatavalue},this.id,'`+ resId +`',`+ pending +`,`+ value.TotalPendingCheckin +`);" id="btn-`+resId+`-${Reserveddatavalue}">${Reserveddatavalue}</button></div> `;
	}
								 });
								  
                                    value.RoomDetails.forEach((datavalue,keys,arrays) => {
									//roomArr =	value.bookedRoom;
									//roomCheckinArray
									roomArr =	(value.bookedRoom);
									//alert(value.roomCheckinArray);
									
						
          if ($.inArray(datavalue, value.roomReservedArray) !== -1) {
							//alert(JSON.stringify(value.bookedRoom));
							
							//alert(roomArr);
							//
							
				if(datavalue!=null){							
//tableData += `<div class="col-md-1 col-sm-1 col-xs-1"  style="padding-right:0px; margin-top: 2px; margin-left:0px; margin-right: 0px;"><button class="btn btn-block btn-primary"  onclick="selectRoom(${value.room_id},${datavalue},this.id,'`+ resId +`',`+ pending +`);" id="btn-`+resId+`-${datavalue}">${datavalue}</button></div> `;
				}
		} else if ($.inArray(datavalue, value.roomCheckinArray) != -1) {
							//alert(JSON.stringify(value.bookedRoom));
							
		}else {
		
		if(datavalue!=null){	
        tableData += `<div class="col-md-1 col-sm-1 col-xs-1" style="padding-right:0px; margin-top: 2px; margin-left:0px; margin-right: 0px;"><button class="btn btn-success btn-block" onclick="selectRoom(${value.room_id},${datavalue},this.id,'`+ resId +`',`+ pending +`,`+ value.TotalPendingCheckin +`);" id="btn-`+resId+`-${datavalue}">${datavalue}</button></div> `;
		}}  
                                    });
                                  tableData += `</div></div></div>`;
                            });
                            tableData += `
                            <div id="showBookedRoom_${resId}"></div>
                            </div>
                            <div class="box-footer">
                             <button class="btn btn-primary pull-right" onclick="userCheckIn('`+ resId +`');">Check-in</button>
                           </div>
                         </div>
                       </div> 
                    </div></td>`;

                    $(tr).html(tableData).toggle();
        */}
      });
    }
	
  $("#newbookerpopupform").submit(function () {

      var form1 = $("#newbookerpopupform");
      var id_mst_company_new = $("#id_mst_company_new").val();
      if (form1.parsley().validate()) {
        $.ajax({
          url: "ajax/ajaxSaveBookerEdit.php",
          data: $("#newbookerpopupform").serialize()+'&id_mst_company_new='+id_mst_company_new,
          type: "GET",          
          success: function (result) {
              $('#id_mst_company_contacts_new').empty();
              $('#id_mst_company_contacts_new').html(result);
              $("#newbookerpopupform")[0].reset();
              closeModalFooterButton();
              alert("Booker added sucessfully ");
          },
          error: function (e) {
            //console.log(JSON.stringify(e));
          }
        });
        return false;
      }
  });

  function openPaymentPopup(url, title = 'Form') {
  $('#popupTitle').text(title);
  $('#popupBody').html('<div class="text-center p-4">Loading...</div>');

  $.ajax({
    url: url,
    type: 'GET',
    success: function(response) {
      $('#popupBody').html(response);
      $('#paymentPopup').modal('show');
    },
    error: function() {
      $('#popupBody').html('<div class="text-danger text-center">Failed to load content.</div>');
    }
  });
}



function printReceiptBtn(resId) {

    // Redirect to print receipt page
    window.open("print_advance_receipt.php?res_id=" + resId, "_blank");
};

  </script>