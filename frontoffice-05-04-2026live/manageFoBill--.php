<?php
include_once "../config/auto_loader.php";
checkUserLevelPermission($_SESSION["userLevel"], TBL_PURCH, "view");
?>
<?php include_once "../includes/header.php"; ?>
<?php
include_once "../includes/left.php";

$statuscase = "";
$searchDocumentType = ""; 
$periodDateCondition = ""; 
$checkinDateCondition = ""; 
$searchGuest = "";       

$baseWhere = "fb.id!=0 AND fb.doc_no>0"; 


$isFoNoSearch = (isset($_REQUEST["search_name"]) && $_REQUEST["search_name"] != "");
$isGuestIdSearch = (isset($_REQUEST["id_mst_guest_form"]) && $_REQUEST["id_mst_guest_form"] != "");
$isPeriodDateFilterApplied = (isset($_REQUEST["datefilter"]) && $_REQUEST["datefilter"] != ""); 
$isCheckinDateFilterApplied = (isset($_REQUEST["datefilterCheckin"]) && $_REQUEST["datefilterCheckin"] != ""); 

$isCombinedPeriodSearchEnabled = (isset($_REQUEST["enable_combined_date_search"]) && $_REQUEST["enable_combined_date_search"] == "on");
$isCombinedCheckinSearchEnabled = (isset($_REQUEST["enable_combined_date_search2"]) && $_REQUEST["enable_combined_date_search2"] == "on");

$isAnySearchCriterionProvided = $isFoNoSearch || $isGuestIdSearch || $isPeriodDateFilterApplied || $isCheckinDateFilterApplied || (isset($_REQUEST["status"]) && !empty($_REQUEST["status"]));

if (!$isAnySearchCriterionProvided) {
  
  $_REQUEST["enable_combined_date_search"] = "on";
  $_REQUEST["enable_combined_date_search2"] = ""; 

  $defaultStartDate = date("Y-m-d", strtotime("-1 day"));
  $defaultEndDate = date("Y-m-d");
  $periodDateCondition = " AND DATE(fb.`doc_date`) BETWEEN '$defaultStartDate' AND '$defaultEndDate'";
  $_REQUEST['datefilter'] = date('d-m-Y',strtotime('-1 days')).' to '.date('d-m-Y'); 

}


// Handle status filter
if (isset($_REQUEST["status"]) && !empty($_REQUEST["status"])) {
    $status = mysqli_real_escape_string($connNew, $_REQUEST["status"]);
    if ($status == "Pending") {
        $statuscase = " AND fb.kot_status='Pending'";
    } elseif ($status == "Billed") {
        $statuscase = " AND fb.kot_status='Billed'";
    } elseif ($status == "Cancelled") {
        $statuscase = " AND fb.kot_status='Cancelled'";
    }
}

// Handle Period date filter (doc_date)
if ($isPeriodDateFilterApplied && $isCombinedPeriodSearchEnabled) {
  $DateExplode = explode(" to ", $_REQUEST["datefilter"]);
  $startDate = date("Y-m-d", strtotime($DateExplode[0]));
  $endDate = date("Y-m-d", strtotime($DateExplode[1]));
  $periodDateCondition = " AND DATE(fb.`doc_date`) BETWEEN '$startDate' AND '$endDate'";
}

// Handle Checkin date filter (joining with fo_reservations to get checkin date)
if ($isCheckinDateFilterApplied && $isCombinedCheckinSearchEnabled) {
  $DateExplode = explode(" to ", $_REQUEST["datefilterCheckin"]);
  $startDate = date("Y-m-d", strtotime($DateExplode[0]));
  $endDate = date("Y-m-d", strtotime($DateExplode[1]));
  $checkinDateCondition = " AND EXISTS (SELECT 1 FROM fo_reservations fr WHERE fr.id = fb.id_reservations AND DATE(fr.checkin) BETWEEN '$startDate' AND '$endDate')";
}

// Handle search name (FO No)
if ($isFoNoSearch) {
    $searchDocumentType = " AND fb.`mdoc_no` LIKE '%" . addslashes(trim($_REQUEST["search_name"])) . "%'";
}

// Handle guest search by ID (from dropdown)
if ($isGuestIdSearch) {
    $guestId = mysqli_real_escape_string($connNew, $_REQUEST["id_mst_guest_form"]);
    $searchGuest = " AND fb.id_fo_folio_to IN (SELECT id FROM fo_folio WHERE id_mst_guest = '" . $guestId . "')";
}

// --- Construct SQL Query based on Logic ---

// Start with the base query
$SQL = "SELECT fb.* FROM fo_bill fb WHERE " . $baseWhere;

if ($isAnySearchCriterionProvided) {
    // If ANY search criterion is provided, proceed with conditional logic
    if ($isFoNoSearch || $isGuestIdSearch) {
        if ($isCombinedPeriodSearchEnabled || $isCombinedCheckinSearchEnabled) { // Check if EITHER combined checkbox is enabled
            // Combined search: FO No/Guest Name + Date(s) + Status
            if ($isFoNoSearch) {
                $SQL .= $searchDocumentType;
            }
            if ($isGuestIdSearch) {
                $SQL .= $searchGuest;
            }
            // Add Period date if its combined checkbox is checked AND filter applied
            if ($isPeriodDateFilterApplied && $isCombinedPeriodSearchEnabled) {
                $SQL .= $periodDateCondition;
            }
            // Add Checkin date if its combined checkbox is checked AND filter applied
            if ($isCheckinDateFilterApplied && $isCombinedCheckinSearchEnabled) {
                $SQL .= $checkinDateCondition;
            }
            $SQL .= $statuscase; // Status can always be combined if combined search is enabled
        } else {
            // Exclusive search: FO No OR Guest Name (ignoring dates and status)
            if ($isFoNoSearch) {
                $SQL .= $searchDocumentType;
            } else { // Must be $isGuestIdSearch because of the outer if condition
                $SQL .= $searchGuest;
            }
            
        }
    } elseif ($isPeriodDateFilterApplied || $isCheckinDateFilterApplied) {
        if ($isPeriodDateFilterApplied) {
            $SQL .= $periodDateCondition;
        }
        if ($isCheckinDateFilterApplied) {
            $SQL .= $checkinDateCondition;
        }
        $SQL .= $statuscase; // Status can be combined with date-only search
    } elseif (isset($_REQUEST["status"]) && !empty($_REQUEST["status"])) {
        // Only status filter applied (no FO No, Guest Name, or Date)
        $SQL .= $statuscase;
    }
}else{
  // $defaultStartDate = date("Y-m-d", strtotime("-1 day"));
  // $defaultEndDate = date("Y-m-d");
  // $SQL .= " AND DATE(fb.`doc_date`) BETWEEN '$defaultStartDate' AND '$defaultEndDate'"; 
  // $SQL .= $statuscase; // Apply status filter even with default date

  // $SQL .= $periodDateCondition;
  $SQL .= $statuscase;

}

$SQL .= " ORDER BY fb.doc_date DESC";
// echo "<pre>Final SQL Query: " . htmlspecialchars($SQL) . "</pre>";
// exit;
$SqlKotList = mysqli_query($connNew, $SQL);
$numRows = mysqli_num_rows($SqlKotList);
$i = 1;
?>


<div class="content-wrapper"> 
	<?php $session=$_GET['submenu']; ?>
    <section class="content-header">
        <div class="row">
            <div class="col-md-4 col-xs-12"> 
                <h6 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
		            <?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>
                </h6>
            </div>
            <div class="col-md-4 col-xs-12 dd-f"></div>
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
                <h6 class="box-title">Search <small> Records:( <?=$numRows;?> ) &nbsp;</small></h6>
                <?php /*?><div class="btn-group  pull-right"> <a type="button" class="btn n-btn pull-right" href="managePosKot.php?submenu=<?php echo $_GET['submenu']; ?>" >Add <?php echo currentNavigation()['submenu']; ?> </a> </div><?php */?>
            </div>
        
        <!-- /.box-header -->
        <?php //debugData($_REQUEST);
			  //echo $SQL;?>
        <form name="searchForm" action="" method="get" value="1">
          <input type="hidden" value="1" name="searchFormSubmit" />
           <input type="hidden" value="<?php echo $_GET['session'] ?>" name="session" />
            <input type="hidden" value="<?php echo $_GET['submenu'] ?>" name="submenu" />
          <div class="box-body">
            <div class="row">
              <div class="col-md-2 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>FO No</label>
                  <input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />
                </div>
                
                <!-- /.form-group --> 
                
              </div>



              <!-- <div class="form-group col-sm-2" style="">
                    <label for="checkin" style="float:left;" readonly="readonly">Guest Name</label>
                    <?php/*
                        $categoryDropDown = '<select class="form-control select2" name="id_mst_guest_form" id="id_mst_guest_form">
                            <option value="">Select Guest</option>';
                            $SQL = "select *  from ".TBL_GUEST." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."'";
                            $query = mysqli_query($connNew, $SQL);
                            while ($resultCat=mysqli_fetch_assoc($query)) {
                                if ($row->id_mst_guest == $resultCat['id']) {
                                    $selected = 'selected="selected"';
                                } else {
                                    $selected = '';
                                }
                                $categoryDropDown .= '<option value="'.$resultCat['id'].'"  '.$selected.' >'.$resultCat['guest_reg_no'] . ' - ' . $resultCat['first_name'].' '. $resultCat['last_name'].' - '.$resultCat['email'].'-' . $resultCat['city'].'</option>';
                            }
                            echo $categoryDropDown .= '</select>';
                    */?>
                    <p class="error id_mst_guest_form-error"></p>
                </div> -->

                <div class="form-group col-sm-2">
            <label for="id_guest" >Guest Name</label>
            <div class="input-group " id="showGuest">
            <select class="form-control select2 guestNameSearch" name="id_mst_guest_form" id="id_guest" >
            </select>
           
              
            <!-- <div class="input-group-addon guest_open"> <i class="fa fa-plus"></i> </div>-->
              <?php //if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){?>
                <div id="EditcusterName" class="input-group-addon guest_open"></div>
                <?php //} ?>
        		
            </div>
            
            <span id="guestError"></span> </div>

            <!-- ///////////////////////////////////////////////////////////////////////////////////////////////// -->
              
              <!-- /.col -->
              <!--col start-->
              <div class="col-md-2 col-sm-6 col-xs-6">
    <div class="form-group">
        <label for="dateRangeReport">Period
            <input type="checkbox" name="enable_combined_date_search" id="enable_combined_date_search"
                   <?php echo isset($_REQUEST['enable_combined_date_search']) && $_REQUEST['enable_combined_date_search'] == 'on' ? 'checked' : ''; ?>
                   style="margin-left: 10px; position: relative; top: 2px;">
        </label>
        <div class="input-group">
            <input type="text" class="form-control pull-right "
                   placeholder="Select period: From - To"
                   name="datefilter" id="dateRangeReport" data-parsley-required
                   value="<?php if(isset($_REQUEST['datefilter']) && $_REQUEST['datefilter']!=''){echo $_REQUEST['datefilter'];}
                   else{ echo date('d-m-Y',strtotime('-1 days')).' to '.date('d-m-Y'); }?>"
                   autocomplete="off">
        </div>
    </div>
</div>

<div class="col-md-2 col-sm-6 col-xs-6">
    <div class="form-group">
        <label for="dateRangeReportCheckin">Checkin
            <input type="checkbox" name="enable_combined_date_search2" id="enable_combined_date_search2" <?php echo isset($_REQUEST['enable_combined_date_search2']) && $_REQUEST['enable_combined_date_search2'] == 'on' ? 'checked' : ''; ?>
                   style="margin-left: 10px; position: relative; top: 2px;">
        </label>
        <div class="input-group">
            <input type="text" class="form-control pull-right appdaterange"
                   placeholder="Select checkin: From - To"
                   name="datefilterCheckin" id="dateRangeReportCheckin" data-parsley-required value="<?php if(isset($_REQUEST['datefilterCheckin']) && $_REQUEST['datefilterCheckin']!=''){echo $_REQUEST['datefilterCheckin'];}
                   else{ echo date('d-m-Y',strtotime('-1 days')).' to '.date('d-m-Y'); }?>"
                   autocomplete="off">
        </div>
    </div>
</div>

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
                      
                      <th>FO No</th>
                      <th>Date</th>
                      <th>Guest Name</th>
                      <!-- <th>Source</th> -->
                      <th>Check-in</th>
                      <th>Check-out</th>
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
					  
					$table_name=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'table' AND id= '".$row->id_attribute_table."'"); 
					$shift_name=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'shift' AND id= '".$row->id_attribute_shift."'"); 
					$steward_name=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'steward' AND id= '".$row->id_attribute_steward."'"); 
					
					$Sqlsettled = mysqli_query($connNew,"SELECT * FROM pos_purch_details WHERE qty-adj_qty>0 AND id_pos_purch  IN  (SELECT id FROM pos_purch WHERE pos_bill_type= '1' AND id_pos_purch= '".$row->id."')");
					$countSettled = mysqli_num_rows($Sqlsettled);
					$rowSettled = mysqli_fetch_object($Sqlsettled);
					/*if($countSettled>0){
					$status='<a class="showSingle" ><span class="label label-danger">PENDING</span></a>';
					$stausicon='view_edit.gif';
					$Imgtitle='View / Edit ';
					$statusValue='editKotid';
					}else{
					$status=' <a class="showSingle"><span class="label label-success">BILLED</span></a>';
					$stausicon='view.gif';
					$Imgtitle='View ';
					$statusValue='editKotviewid';
					}*/
					if($row->kot_status=='Billed')
					{
					$status=' <a class="showSingle"><span class="label label-success">BILLED</span></a>';
					$stausicon='view2.png';
					$Imgtitle='View ';
					$statusValue='editKotviewid';
					$status1='view';
					 }
					if($row->kot_status=='Pending')
					{
					$status='<a class="showSingle" ><span class="label label-info">PENDING</span></a>';
					$stausicon='edit.png';
					$Imgtitle='View / Edit ';
					$statusValue='editKotid';
					$status1='edit';
					 }
					 if($row->kot_status=='cancelled')
					{
					$status='<a class="showSingle" ><span class="label label-danger">CANCELLED</span></a>';
					$stausicon='view2.png';
					$Imgtitle='View ';
					$statusValue='CancelledKOT';
					$status1='cancel';
					 } 
					
					
						  ?>
						  
                    <tr>
                      <td><?php echo $i++;?></td>
                       
                      <td><?php echo $row->mdoc_no;//.'=='.$row->id;?></td>
					  
					 <?php 
						$timestamp = strtotime($row->doc_date);
						$date = date('d-m-Y', $timestamp);
						
					  ?>
					  
                      <td><?php echo $date;  //<td><?=date('d-m-Y',strtotime($row->doc_date));?></td>
                      <?php
                    // $id_mst_guest	=	selectColumn(FO_RESERVATIONS_DETAILS,'id_mst_guest','WHERE id_fo_folio_to="'.$row->id_fo_folio_to.'" '); 
					$id_mst_guest	=  selectColumn('fo_folio','id_mst_guest'," WHERE `id` = '".$row->id_fo_folio_to."'");	 
						 
                   

                      	$SQL2 = "select *  from ".TBL_GUEST." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  `id` = '".addslashes($id_mst_guest)."' ";
                      	//echo $SQL;


		
		$query2=mysqli_query($connNew, $SQL2);		
	    $row2=mysqli_fetch_assoc($query2);
		$GuestTitle	=	selectColumn(TBL_ATTRIBUTES,'field_value','WHERE `id_shop`="'.addslashes($_SESSION['shop']).'" and id="'.$row2['id_mst_attributes_title'].'"'); 
		
 $GuestName = $GuestTitle.' '.$row2['first_name'].' '. $row2['last_name'];
 $checkin_date=	date('d-m-Y',strtotime(selectColumn(FO_RESERVATIONS,'checkin','WHERE id="'.$row->id_reservations.'"')));
		?>
                       <td><?php echo  $GuestName; ?></td>
                       <!-- <td><?php// echo  $GuestName; ?></td> -->
                       <td><?php echo date('d-m-Y',strtotime($checkin_date)); ?></td>
                       <td><?php echo $checkout_date= date('d-m-Y',strtotime($row->checkout_date)); ?></td>

                      <td> 

                      <?php
                      $frontbillprint = selectColumn('mst_shops','fobill_url'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");
                      if($frontbillprint==''){
                        $frontbillprint='fobillformat1.php';
                      } else {
                        $frontbillprint = selectColumn('mst_shops','fobill_url'," WHERE `id` = '".addslashes($_SESSION['shop'])."'"); 
                      }
                      ?>
                     
                     <a target="_blank" href="<?=$frontbillprint;?>?idfobill=<?=encryptor(encrypt, $row->id);?>&id_folio=<?=encryptor(encrypt, $row->id_fo_folio_to);?>&id_mst_room_no_allocation=<?=encryptor(encrypt, $id_room);?>&submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>" ><img src="../images/preview.png" style="cursor:pointer;height:20px;" title="Page Preview "  /></a>&nbsp;&nbsp;
                      
                      
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
 
      </div>
  <?php include_once("../includes/footer.php")?>
  <script>
  function ajaxCancelKot(id){

var form=$("#Formkotremarks");	
	var id_pos_pos_purch_idpurch=$("#pos_purch_id").val();
	//var id_pos_purch=$("#id_pos_purch").val();
	
	var submenu1=$("#submenu1").val();
	
	if(pos_purch_id!='' && pos_purch_id==undefined){
		var purch = form.serialize()+'&pos_purch_id='+pos_purch_id;
		var saveType='edit';
		
	}else{
		var purch = form.serialize();
		var saveType='add';
		
	}
	$('.loading').show();
    if(form.parsley().validate()){

		$.ajax({
			type: "GET",
			url: 'ajax/ajaxCancelKot.php',
			data: purch, 
			success: function (result) {
			        console.log(result);
			        data = JSON.parse(result);
					//$( "#GetItemListView" ).html('');
					//getPreviousOrder(data.purch_id);	
					alert(data.msg);
					
					if(submenu1=='179'){
						window.location.href="manageKotNc.php?submenu="+submenu1;
					}else{
						window.location.href="manageKot.php?submenu=178&session=22";
					}
					
      	}

		});

	}


}


function ajaxKOTcancel(posid,mdoc_no){
	
	//$("#cancelled").addClass("bookedby_open");
	$('#cancelpop').popup({
        			transition: 'all 0.3s',
           			 autoopen: true,            
        			});
	$("#pos_purch_id").val(posid);
	$("#kot_mdoc_no").html(' KOT No: '+mdoc_no);				
	}




  </script>

<script>
    $(document).ready(function() {
        // Function to handle enabling/disabling for a specific pair
        function toggleDateInput(checkboxId, inputId) {
            const $checkbox = $('#' + checkboxId);
            const $input = $('#' + inputId);

            // Set initial state on page load
            $input.prop('disabled', !$checkbox.is(':checked'));

            // Attach change event listener to the checkbox
            $checkbox.on('change', function() {
                $input.prop('disabled', !$(this).is(':checked'));
                // Clear the input value when disabled, if you want
                if ($input.prop('disabled')) {
                    $input.val('');
                }
            });
        }

        // Apply the logic to both date pickers
        toggleDateInput('enable_combined_date_search', 'dateRangeReport');
        toggleDateInput('enable_combined_date_search2', 'dateRangeReportCheckin');
    });
</script>