<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_PURCH,'view');
?>
<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<?php 

//print_r($_REQUEST);
if($_REQUEST['searchFormSubmit'] == '1'){   
$cc = array();

			
$mailto = 'roomstatushublogs@gmail.com';
//die;
//EMAIL START==================================================================================
	
		
 if($mailto !=''){
	 // && file_exists($attach)){
	 
	 
	
	
	
//$msg 	 = "Please find the attachment for the Date Wise Reservations Report<br/><br/> RoomStatusHUB Team.";
$sub	 = $_POST['subject'];//$shopCodeSub.'- Date Wise Reservations Report';
$cc='';		
$cc=array();

$msg 	 = $_POST['sendcontent'];//wordwrap($msg,70);
$From	= "support@roomstatushub.com";



//$recipients =explode(",",$_POST['ccId']);

$mail = new PHPMailer(); // create a new object	
$mail->IsSMTP(); // enable SMTP
//$mail->SMTPDebug = 2; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
$mail->Host = "smtp.gmail.com";
$mail->Port = 465; // or 587
$mail->IsHTML(true);
$mail->Username = "support@roomstatushub.com";
$mail->Password = "kxfm xrpv znoi xmhx";
$mail->SetFrom($From);
$mail->AddReplyTo($From);
$mail->Subject = $sub;
$mail->Body = $msg;
//$mail->AddAddress('roomstatushublogs@gmail.com');	

		 $to_email = $_REQUEST['mailId'];
		 $toArray = explode(';',$to_email);
      for($i=0;$i<count($toArray);$i++){
		  $mail->addAddress($toArray[$i]);
      }
		 $cc_email = $_REQUEST['ccId'];
		 $ccArray = explode(';',$cc_email);
      for($i=0;$i<count($ccArray);$i++){
		  $mail->AddCC($ccArray[$i]);
      }
	
	 
		
		//$mail->AddBCC("sundaram@roomstatushub.com", "support");
		//$mail->AddBCC("roomstatushublogs@gmail.com", "support");
	 //echo $fileType;
	
				  
	//die;
	$sendMail = $mail->Send(); 
		 	
 }
 mysqli_query($connNew,"UPDATE `fo_reservations` set reminder=reminder+1 where id=".addslashes(encryptor('decrypt',$_POST['eId']))."");
		 unset($sendMail);
     $message = "Mail Sent Successfully";
echo "<script type='text/javascript'>alert('$message');window.location.href='manageAdvanceReminder.php';</script>";

}	 
		 

//EMAIL END ===================================================================================


  ?>
    <style type="text/css">
      /*--more btn css*/
          @import url("https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css");
   .panel-title{
    font-size:14px;
   }
   .panel-heading{
    border-color: #f56616!important;
    padding:8px 14px;
   }
    .panel-title>a:before {
      float: left !important;
      font-family: FontAwesome;
      content: "\f068";
      padding-right: 5px;
    }

    .panel-title>a.collapsed:before {
      float: left !important;
      content: "\f067";
    }

    .panel-title>a:hover,
    .panel-title>a:active,
    .panel-title>a:focus {
      text-decoration: none;
    }
      .breadcrumb{
        margin-bottom:0;
      }
    /*more btn css ends*/
.fieldset {
	border: 2px groove #f56616;
	border-top: none;
	padding: 0.5em;
	margin: 1em 2px;
}
.fieldset>p {
	font: 1.4em normal;
	margin: -0.8em -0.4em 0;
}
.fieldset>p>span {
	float: left;
}
.fieldset>p:before {
	border-top: 3px solid #f56616;
	content: ' ';
	float: left;
	margin: 0.5em 2px 0 -1px;
	width: 0.75em;
}
.fieldset>p:after {
	border-top: 3px solid #f56616;
	content: ' ';
	display: block;
	height: 0.5em;
	left: 2px;
	margin: 0 1px 0 0;
	overflow: hidden;
	position: relative;
	top: 0.5em;
}
.text {
	font-size: 20px;
}
</style>
    <?php 
		
			$DispalyClass="display:none;";
			$viewClass="";
			$viewIcons='fa fa-plus-square-o fa-1x';
		
			$DispalyClass="";
		//	$viewClass='fieldset';
			$viewIcons='fa fa-minus-square-o fa-1x';
		//}
		
		
	$subMenuCond = " AND `id_module` = '8'";

  

$resShop  =  mysqli_query($connNew,"SELECT * FROM `mst_shops` WHERE id= '".addslashes($_SESSION['shop'])."'");
 $rowShop = mysqli_fetch_object($resShop);

		$sqlOrderDetail = mysqli_query($connNew,"SELECT * from `fo_reservations` where `fo_reservations`.booking_status='2' and `fo_reservations`.id= '".addslashes(encryptor('decrypt',$_REQUEST['mailId']))."'"); 
		 $rowOrderDetail = mysqli_fetch_object($sqlOrderDetail); 
		 
    $sqlGuestDetail = mysqli_query($connNew,"SELECT * FROM `mst_guest` WHERE  id= '".addslashes($rowOrderDetail->id_mst_guest)."'"); 
		 $rowGuestDetail = mysqli_fetch_object($sqlGuestDetail); 
		
 	
      $resHotelDetail  =  mysqli_query($connNew,"SELECT * FROM `mst_hotels` WHERE id= '".addslashes($rowOrderDetail->id_mst_hotels)."'");
 $resultHotelDetail = mysqli_fetch_object($resHotelDetail);
if($rowOrderDetail->id_company_person>0){  
	//$resContact = selectSql(TBL_CUSTOMER,"where type='2' and id_customer='".addslashes($rowOrderDetail->id_company_person)."'",''); 
		  //$resultContact = fetch_object($resContact); 
      }
		 $resCompany  =  mysqli_query($connNew,"SELECT * FROM `mst_company` WHERE id= '".addslashes($rowOrderDetail->id_mst_company)."'");
 $resultCompany = mysqli_fetch_object($resCompany);
	
		  
	if($resultCompany->id_default_group != '0'){	  
	$content ='<p><strong>'.$resultContact->first_name.' '.$resultContact->last_name.'</strong><br /><strong>'.$resultCompany->name.'</strong><br />
   				 <br />';
				 $emailId= $resultContact->email;
	}else{
			$emailId= $rowGuestDetail->email;
	}
  $emailId= $rowGuestDetail->email;
 /*$content .= '
    
  Dear '.$rowGuestDetail->first_name.' '.$rowGuestDetail->last_name.', &nbsp;<br />
  Greetings!!!&nbsp;<br />
  <br />
  This is in reference to your booking from '.date('d-m-Y',strtotime($rowOrderDetail->checkin)).' to '.date('d-m-Y',strtotime($rowOrderDetail->checkout)).' at '.$resultHotelDetail->name.', '.$resultHotelDetail->city.' wherein the  is due by '.date('d-m-Y',strtotime($rowOrderDetail->payment_date)).' .&nbsp;<br />
  You are requested to send us the payment(cheque/cash/voucher) by the specified date to reconfirm the booking and avoid automatic system cancellation of the same. &nbsp;<br />
  In case of any further assistance please feel free to get in touch with us. <br />
  With Kind Regards.<br />
  ADMIN <br />
 <address><strong> '.$ShopShortCode.' '.$resultHotelDetail->name.'</strong><br>'.
								$resultHotelDetail->address.'<br>'.
								$resultHotelDetail->city.' - '.$resultHotelDetail->pincode.'<br>'.
								selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".addslashes($resultHotelDetail->id_mst_state)."'").', India <br>
								<strong>Phone :</strong> '.$resultHotelDetail->phone1.'<br>
								<strong>Email :</strong> '.$resultHotelDetail->email.'<br>
								<strong>Website :</strong> '.$rowShop->website_url.'
							</address></p>';*/

	?>


<?php
$guestName   = ($rowGuestDetail->first_name . ' ' . $rowGuestDetail->last_name);
$checkin     = date('d-m-Y', strtotime($rowOrderDetail->checkin));
$checkout    = date('d-m-Y', strtotime($rowOrderDetail->checkout));
$paymentDate = date('d-m-Y', strtotime($rowOrderDetail->payment_date));
$bookingNo   = $rowOrderDetail->booking_no;

// 🔥 Fetch payment status text
/*$paymentStatus = selectColumn(
    TBL_ORDER_STATE,
    'template',
    " WHERE `id_order_state` = '".addslashes($rowOrderDetail->payment_status)."'"
);*/

// 🔥 Fetch state name once
$stateName = selectColumn(
    TBL_STATE,
    'name',
    " WHERE `id_state` = '".addslashes($resultHotelDetail->id_mst_state)."'"
);

$content .= '
<p>
Dear '.ucwords(strtolower($guestName)).',<br />
Greetings!!!<br /><br />

This is in reference to your booking <b>(Booking No: '.$bookingNo.')</b> 
from '.$checkin.' to '.$checkout.' at '.$resultHotelDetail->name.', '.$resultHotelDetail->city.' 
where in the <b>'.$paymentStatus.'</b> is due by '.$paymentDate.'.<br /><br />

You are requested to send us the payment (cheque/cash/voucher) by the specified date 
to reconfirm the booking and avoid automatic system cancellation of the same.<br /><br />

In case of any further assistance please feel free to get in touch with us.<br /><br />

With Kind Regards,<br />

<address>
<strong>'.$ShopShortCode.' '.$resultHotelDetail->name.'</strong><br />
'.$resultHotelDetail->address.'<br />
'.$resultHotelDetail->city.' - '.$resultHotelDetail->pincode.'<br />
'.$stateName.', India<br />
<strong>Phone :</strong> '.$resultHotelDetail->phone1.'<br />
<strong>Email :</strong> '.$resultHotelDetail->email.'<br />
<strong>Website :</strong> '.$rowShop->website_url.'
</address>
</p>';
?>
    <style>
.ranges {
	padding: 9px !important;
}
.daterangepicker .ranges li:hover {
	background-color: #08c !important;
}
</style>
<div class="content-wrapper">
<!-- Content Header (Page header) -->

<?php  $session=$_GET['submenu']; ?>
<section class="content-header">
     <div class="row">
          <div class="col-md-6">
  <!--<h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;"> <?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>
    <?php //echo currentNavigation()['submenu']; ?>
  </h3>-->
     <div class="row">
       
    <div class="col-md-4 col-xs-12"> 
      <h6 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
		<span style="color:#f28613">&nbsp;<i class="fa fa-home"></i> Advance Reminder Mail</span>
              </h6>
     </div>  
    </div>
  </div>
  
  
       <div class="col-md-6 col-xs-12">
      <?php echo breadCrumbs(); ?>
     </div>
   </div>
 
   </section>
<section class="content ">
  <div class="row">
 
<div class="col-xs-12">
<!-- /.box -->
<div class="box">
<div class="box-header"> <small class="text-center has-error">
  <?php if($_SESSION['errorMsg']){?>
  <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
  <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
  <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
  <?php unset($_SESSION['successMsg']);}?>
  </small> </div>
<form action="" method="post" enctype="multipart/form-data" autocomplete="off" data-parsley-validate>
<input type="hidden" value="1" name="searchFormSubmit" />
<input type="hidden" value="1" name="report_show" id="report_show" />
<div class="box-body">
<div class="row">



<!---Filter Star---------------------------------------------------------------------------->

			<input type="hidden" name="eId" value="<?php echo $_REQUEST['mailId'];?>" />
            <div class="box-body">
              <div class="form-group">
                <input class="form-control" placeholder="To:" name="mailId" value="<?php echo $emailId; ?>" data-parsley-required data-parsley-type="email">
              </div>
              <div class="form-group">

                <input class="form-control" placeholder="CC:" name="ccId" value="<?php echo $resultHotelDetail->email.','.$rowShop->email; ?>" data-parsley-required data-parsley-type="cc email">

              </div>
              <div class="form-group">
                <input class="form-control" placeholder="Subject:" name="subject" value="Reminder" data-parsley-required >
              </div>
              <div class="form-group">
                    <textarea id="description" class="ckeditor" name="sendcontent"><?php echo $content; ?>
                   
                    </textarea>
              </div>
              <!--<div class="form-group">
                <div class="btn btn-default btn-file">
                  <i class="fa fa-paperclip"></i> Attachment
                  <input type="file" name="sendAttachment">
                </div>
                <p class="help-block">Max. 1MB</p>
              </div>-->
            </div>
			<div class="box-footer">
              <div class="pull-right">   
			  	             
                
             <input name="submit" type="submit" class="btn o-btn" value="Send"> </div>
              
            </div>
			</form>	
<div class="col-md-12 col-sm-12">
<div  id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" id="fieldset" >
<!--<p id="showheadelion" class="text-o"><span id="textReportShow" style="font-size:15px;">Filter </span></p>-->
<div  id="moreReportDiv" style="padding-left:0px;<?php echo $DispalyClass; ?>">

<div class="box-body pt-0 pl-0">
<div class="row">

<!----Row----->

				
      		        				</div>
      		        			</div>








		        		</div>
		        	



	
          </div>
          <!-- /.row -->

        
     </div>
        <!-- /.box-body -->
    
        
        
        
        

  
  
		
            <style>.overlay {
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    position: fixed;
    background: #222;
}

.overlay__inner {
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    position: absolute;
}

.overlay__content {
    left: 50%;
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%);
}

.spinner {
    width: 55px;
    height: 55px;
    display: inline-block;
    border-width: 2px;
    border-color: rgba(247,23,82);
    border-top-color: #fff;
    animation: spin 1s infinite linear;
    border-radius: 100%;
    border-style: solid;
}

@keyframes spin {
  100% {
    transform: rotate(360deg);
  }
}

</style> 
            
            <div class="col-sm-12">
              <label for="">&nbsp;</label>
              <br>
              <span style="color:red;display:none;" id="loading">
                  <div class="overlay">
    <div class="overlay__inner">
        <div class="overlay__content"><span class="spinner"></span></div>
    </div>
</div>
                  
                  <!--<img src="../images/ajax-loader1.gif">Loading Please Wait...--></span> </div>
      
      
  
           <div class="box-body table-responsive"> <div class="row">
              <div id="ShowResultContent" style="padding:0px 10px 0px 10px;"> </div>
            </div> 
           
           
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
  <script type="text/javascript">
	
function getReportOrderBy(id_order_by){	

		$.ajax({

			   type: "GET",

			   url: 'ajax/ajaxPosReportOrderBy.php',

			   data: 'id_order_by='+id_order_by, 

			   success: function (result) {				   

			     $('#id_order_by').empty();

				 $('#id_order_by').html(result);

				 

				}

		});

}


//Form Data Clear
	downloadExcelPdf = (ReportShowType) => {
    
        var period = $("#per_report_date").val();
		var id_report_type = $("#id_report_type").val();
		var id_main_group = $("#id_main_group").val();
		var id_sub_group = $("#id_sub_group").val();
		var id_item = $("#id_item").val();
    var id_order_by = $("#id_order_by").val();
	var showItemReport = $("#showItemReport").val();
	
    if(id_report_type =='189'){
        let url2 = 'ajax/ajaxSettlementSummaryReports.php?period='+period+'&id_report_type='+id_report_type+'&id_main_group='+id_main_group+'&id_sub_group='+id_sub_group+'&id_item='+id_item+'&ReportShowType='+ReportShowType+'&id_order_by='+id_order_by+'&showItemReport='+showItemReport;
         window.open(url2);
	
	}else if(id_report_type =='285'){
	 let url2 = 'ajax/ajaxCollectionWiseDetailReport.php?period='+period+'&id_report_type='+id_report_type+'&id_main_group='+id_main_group+'&id_sub_group='+id_sub_group+'&id_item='+id_item+'&ReportShowType='+ReportShowType+'&id_order_by='+id_order_by+'&showItemReport='+showItemReport;
         window.open(url2);	
	}else{
		let url2 = 'ajax/ajaxFoBillDetailReport.php?period='+period+'&id_report_type='+id_report_type+'&id_main_group='+id_main_group+'&id_sub_group='+id_sub_group+'&id_item='+id_item+'&ReportShowType='+ReportShowType+'&id_order_by='+id_order_by+'&showItemReport='+showItemReport;
         window.open(url2);
		
		}
   }
   
   function loadSalesReport(ReportShowType){
   
	 //id_report_type,id_main_group,id_data_main_group,id_item
	 
	 var id_report_type = $("#id_report_type").val();
	  var id_order_by = $("#id_order_by").val();
	 if(id_report_type==''){
		 document.getElementById('id_report_type_error').innerHTML = 'Please Select Report Type';
		 return false;
		 }
		 document.getElementById('id_report_type_error').style.display = "none";
	if(id_order_by==''){
		 document.getElementById('id_order_by_error').innerHTML = 'Please Select Order By';
		 return false;
		 }	 
		  
		   document.getElementById('id_order_by_error').style.display = "none";
	// alert(id_report_type);
	 $("#showPrintExplode").hide();
     $("#loading").show();
	  var id_order_by = $("#id_order_by").val();
	 	var period = $("#per_report_date").val();
		//var id_report_type = $("#id_report_type").val();
		var id_main_group = $("#id_main_group").val();
		var id_sub_group = $("#id_sub_group").val();
		var id_data_main_group = $("#id_data_main_group").val();
		var showItemReport = $("#showItemReport").val();
		var id_item = $("#id_item").val();
		  if(id_report_type =='189'){
		 reportTypeFile  ='ajaxSettlementSummaryReports.php';
		  }else if(id_report_type =='285'){
			  
		 reportTypeFile  ='ajaxCollectionWiseDetailReport.php';
		 
		  }else{
			  
			 reportTypeFile  ='ajaxFoBillDetailReport.php';  
			  
			  }
		 $.ajax({
				url:'ajax/'+reportTypeFile,
				type:'POST',
				data:'period='+period+'&id_report_type='+id_report_type+'&id_main_group='+id_main_group+'&id_data_main_group='+id_data_main_group+'&id_item='+id_item+'&ReportShowType='+ReportShowType+'&id_order_by='+id_order_by+'&id_sub_group='+id_sub_group+'&showItemReport='+showItemReport,
				success:function(data){
					$("#ShowResultContent").html(data);
                    
                    $("#loading").hide();
                   $("#showPrintExplode").show(); 
					
				}
			})
			
			
			//let reportType = $("#reportType").val();
       /* //$("#SummaryDataloading").show(); 
		
		//let reportType =  $('input[name="reportType"]:checked').val();
		var reportType = $("#SelectedreportType").val();
		let id_hotel = $("#id_hotel").val();
		
		var id_group_sun_master = $("#id_group_master").val();
		var groupsMenu = id_group_sun_master.split("_");
        var id_group_master = groupsMenu[0];
        var id_group_sub_master = groupsMenu[1];
		
	    let ComparePeriodDate = $("#SelectedComparePeriodDate").val();
	    
        if(summaryReportType==4){
            reportTypeFile  ='DashboardCompareAgentTop.php';
            
        }else if(summaryReportType==61){
            reportTypeFile  ='DashboardCompareAgentDropOut.php';
        }else if(summaryReportType==62){
            reportTypeFile  ='DashboardMtdReport.php';
        }else if(summaryReportType==5){
            reportTypeFile  ='DashboardMtdYtdReport.php';
        }else{
            reportTypeFile  ='DashboardCompareView.php';
        }
        
			$.ajax({
				url:'ajax/'+reportTypeFile,
				type:'POST',
				data:'period='+period+'&id_hotel='+id_hotel+'&id_group_master='+id_group_master+'&reportType='+reportType+'&viewMonthwise='+viewMonthwise+'&summaryReportType='+summaryReportType+'&ComparePeriodDate='+ComparePeriodDate+'&CompareFinancialYear='+CompareFinancialYear+'&CurrentFinancialYear='+CurrentFinancialYear+'&id_group_sub_master='+id_group_sub_master,
				success:function(data){
					$("#ShowCompareReportData").html(data);
                    
                    $("#loading").hide();
                    
					
				}
			})*/
	
        

	}
	
	function fomrdata_clear(){
		location.reload();	
	}
    function mainGroup(sel){
		
		
	
	var opts = [],opt;	
	var len = sel.options.length;
	
	for (var i = 0; i < len; i++) {
		opt = sel.options[i];	
		if (opt.selected) {
			opts.push(opt.value);
		}
	}
  	
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxconsolidateItemreport.php',
		data: 'id_main_group='+opts+'&group=1', 
		success: function (result) {
				$( "#id_sub_group" ).html(result);
				$( "#id_data_main_group" ).val(opts);
				
	 	}
	});
		
		}
function subGroup(sel){
		
	var id_data_main_group=$("#id_data_main_group").val();	
	
	var opts = [],opt;	
	var len = sel.options.length;
	
	for (var i = 0; i < len; i++) {
		opt = sel.options[i];	
		if (opt.selected) {
			opts.push(opt.value);
		}
	}
  	
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxconsolidateItemreport.php',
		data: 'id_sub_group='+opts+'&group=2&id_data_main_group='+id_data_main_group, 
		success: function (result) {
				$( "#id_item" ).html(result);
				
	 	}
	});
		
		}		
	function consolidateItemreport(){
		
		var datefilter=$("#datefilter").val();
		alert(datefilter);
		$.ajax({
		type: "POST",
		url: 'ajax/ajaxconsolidateItemreport.php',
		data: 'datefilter='+datefilter+'&group=2', 
		success: function (result) {
				alert(result);
	 	}
	});
		
		}	
	</script>
  <?php include_once("../includes/footer.php")?>
>>>>>>>>>>>>>>>>