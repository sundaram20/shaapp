<?php include_once("../config/auto_loader.php");
include_once("include/pos_function.php");
include_once("include/function.php");  ?>
<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>

<style>
 body{font: 12px 'Segoe UI', Tahoma, Arial, Helvetica, sans-serif;}   
#invoice-POS{
	 
  box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
  padding:2mm;
  margin: 0 auto;
  width: 80mm;
  background: #FFF;
  
  
::selection {background: #f31544; color: #FFF;}
::moz-selection {background: #f31544; color: #FFF;}
h1{
  font-size: 1.5em;
  color: #222;
}
h2{font-size: .9em;}
h3{
  font-size: 1.2em;
  font-weight: 300;
  line-height: 2em;
}
h4{
  font-size: 3px;
  font-weight: bold;
  line-height: 2em;
}
p{
  font-size: .7em;
  color: #666;
  line-height: 1.2em;
}
 
#top, #mid,#bot{ /* Targets all id with 'col-' */
  border-bottom: 1px solid #EEE;
}

#top{min-height: 100px;}
#mid{min-height: 80px;} 
#bot{ min-height: 50px;}

#top .logo{
  //float: left;
	height: 60px;
	width: 60px;
	background: url(http://michaeltruong.ca/images/logo1.png) no-repeat;
	background-size: 60px 60px;
}
.clientlogo{
  float: left;
	height: 60px;
	width: 60px;
	background: url(http://michaeltruong.ca/images/client.jpg) no-repeat;
	background-size: 60px 60px;
  border-radius: 50px;
}
.info{
  display: block;
  //float:left;
  margin-left: 0;
}
.title{
  float: right;
}
.title p{text-align: right;} 
table{
  width: 100%;
  border-collapse: collapse;
}
td{
  padding: 5px 0 5px 15px;
  border: 1px solid #EEE
}
.tabletitle{
  padding: 5px;
  font-size: .5em;
  background: #EEE;
}
.service{border-bottom: 1px solid #EEE;}
.item{width: 24mm;}
.itemtext{font-size: .5em;}

#legalcopy{
  margin-top: 5mm;
}

  
  
}

</style>



<?php 

  $session = $_REQUEST['session'];
session_start();
 $mydata = $_SESSION["myidpos"]+1;
			

if($_REQUEST['printPreviewid']!=''){
$pos_purch_id_array[]	=	encryptor(decrypt, $_REQUEST['printPreviewid']);
$printgroup			  = 	fetchdataprint($pos_purch_id_array,$grouparray=0);
 $pos_purch_id_array[]	=	encryptor(decrypt, $_REQUEST['printPreviewid']);
}else{
	$pos_purch_id=	$_REQUEST['editid_posbilling'];
	/*$pos_purch_id=	$_REQUEST['editid_posbilling'];
	$pos_purch_id_array[]	=	$_REQUEST['editid_posbilling'];
$printgroup			  = 	fetchdataprint($pos_purch_id_array,$grouparray=0);
 $pos_purch_id_array[]	=	$_REQUEST['editid_posbilling'];
	*/
	
	}

?>
  <div class="content-wrapper"> 
    
    <!-- Content Header (Page header) -->
    <?php 
	
 //$printgroup			  = 	fetchdataprint($pos_purch_id_array,$grouparray=0); ?>
	
    <section class="content-header">
      <!--<h1> POS Print </h1>-->
	  
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"> POS Print </li>
      </ol>
    </section>
    
    <!-- Main content -->
    
    <section class="content print-con pt-0">
    
    <div class="row">


<?php
if($_REQUEST['printPreviewid']==''){
  $id=$_REQUEST['updateid'];
}else{
  $id=$_REQUEST['printPreviewid'];
}
//echo $mydata;
$NewKot ='kotbilling.php';
?>

<div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
  <a href="<?php echo $NewKot; ?>?submenu=<?php echo $_REQUEST['submenu'] ?>&session=<?php echo $_REQUEST['session'] ?> ">
    <div class="btn c-btn btn-block" style="margin-right:15px" ><i class="fa fa-pencil fa-1x"></i> Add</div >
  </a>
</div>



<?php if($_REQUEST['printPreviewid']!='' || $_REQUEST['updateid']!=''){  ?>

<div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
  <a href="kotbilling.php?updateid=<?php echo $id ?>&session=<?php echo $session ?>&submenu=<?php echo $_REQUEST['submenu'] ?> ">
    <div class="btn c-btn btn-block" style="margin-right:15px" ><i class="fa fa-pencil-square-o fa-1x"></i> Edit</div >
  </a>
</div>

<?php } else {?>

<div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
  <a href="kotbilling.php?updateid=<?php echo encryptor(encrypt, $pos_purch_id) ?>&session=<?php echo $session ?>&submenu=<?php echo $_REQUEST['submenu'] ?> ">
    <div class="btn c-btn btn-block" style="margin-right:15px" ><i class="fa fa-pencil-square-o fa-1x"></i> Edit</div >
  </a>
</div>  
    
<?php } ?>

<div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
  <a href="manageOutletBilling.php?session=<?php echo $_REQUEST['session'] ?>&submenu=<?php echo $_REQUEST['submenu'] ?> ">
    <div class="btn c-btn btn-block" style="margin-right:15px" ><i class="fa fa-list fa-1x"></i> List</div >
   </a>
</div>    
<div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
<button class="btn c-btn btn-block" style="margin-right:15px" onClick="printdiv('div_print');"><i class="fa fa-print fa-1x"></i> Print</button >
</div>    

<div class="form-group col-xs-12 col-sm-3 col-md-3  ">
<div class="btn-group " style="margin-left:6px;" >&nbsp; <a type="button" class="btn c-btn2" href="javascript:void(0)"><i class="fa fa-fw fa-cloud-download"></i> Export</a>
    <a type="button" class="btn o-btn dropdown-toggle" data-toggle="dropdown" > 
    <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </a>
    <ul class="dropdown-menu " role="menu">
      <li><a title="Export to excel file" onClick="downloadExcelPdf(2);" href="javascript:void(0)"><img src="../images/excel-icon.jpg" width="20" height="20">&nbsp;Excel</a></li>
      <li><a title="Export to pdf file" onClick="downloadExcelPdf(3);" href="javascript:void(0)"><img src="../images/pdf.jpg" width="20" height="20">&nbsp;Pdf</a></li>
       <li><a title="Export to JPG file" onClick="downloadExcelPdf(3);" href="javascript:void(0)"><i class="fas fa-file-image"></i>&nbsp;JPG</a></li>
    </ul>
  </div>

<div class="btn-group s-btt" > <a type="button" class="btn c-btn2" href="javascript:void(0)"><i class="fa fa-fw fa-cloud-download"></i> Share</a>
    <a type="button" class="btn o-btn dropdown-toggle" data-toggle="dropdown" > 
    <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </a>
    <ul class="dropdown-menu " role="menu">
      <li><a title="Share on Email" onClick="downloadExcelPdf(2);" href="javascript:void(0)"><i class="fas fa-envelope-open-text"></i>&nbsp;Email</a></li>
      <li><a title="Share on Whatsapp" onClick="downloadExcelPdf(3);" href="javascript:void(0)"><i class="fab fa-whatsapp"></i>&nbsp;Whatsapp</a></li>
    </ul>
  </div>
   </div>
      <div class="col-xs-12"> 
        
        <!-- /.box -->
                 <div class="box">
      
 <div class="row">
 
 
 
          <div class="col-md-9 col-lg-10">
<? //  Settle Bill Start----------------------------------------------------- ?>
<?php 
//debugData($_REQUEST);
if($_REQUEST['printPreviewid']!='' && $_REQUEST['updateid']==''){
 $pos_purch_id=	encryptor(decrypt, $_REQUEST['printPreviewid']);
}elseif($_REQUEST['updateid']!='' && $_REQUEST['printPreviewid']==''){
	$pos_purch_id=encryptor(decrypt, $_REQUEST['updateid']);
	}else{
	$pos_purch_id=	$_REQUEST['editid_posbilling'];
	}
 $sql = "SELECT *  from
(
select pp.*,

max(pp.grant_total_amount) as amount_need_to_pay, 
sum(ppp.amount) as amount_paid,
(case 
when max(pp.cancelled)=1 then 'cancelled'
when max(pp.grant_total_amount)=sum(ppp.amount) then 'Settled'
when max(pp.grant_total_amount)<>sum(ppp.amount) and sum(ppp.amount)<>0 then 'Partial'
when max(pp.grant_total_amount)<>sum(ppp.amount) and sum(ppp.amount)=0 then 'Pending'
when max(pp.grant_total_amount)=0 and sum(ppp.amount) is NULL then 'Pending'
when max(pp.grant_total_amount)>0 and sum(ppp.amount) is NULL then 'Pending'
end) as payment_status
from pos_purch  pp 
left join
pos_purch_pay ppp
on
ppp.id_purch=pp.id  where pos_bill_type=2 
group by
pp.id ORDER BY last_modified desc
)as managekotlist WHERE id!=0 AND doc_type=21  AND id='".$pos_purch_id."'";


$counter = 1;
						 $posQuery	=	mysqli_query($connNew,$sql); 
						$TotalPaidAmount=0;
						$cardId=array();
	                  $row = mysqli_fetch_object($posQuery);
					// print_r($row);
					 
					   $pos_purch_id_array=array();
						  	

							$pos_purch_id_array[]= $row->id;
							$mdoc_no			 = $row->mdoc_no;
							$grand_total_amount	 = $row->grant_total_amount;
							 $purch_id			 = $row->id;	
						
					 ?>
					
                    <?php 
												
					  $Sql = "SELECT * FROM `".TBL_PURCH_PAY."` WHERE id_purch='".$purch_id."' ";

	                  $Query	=	mysqli_query($connNew,$Sql); 
						$TotalPaidAmount=0;
						$cardId=array();
	                  while($ResultBlockedtable1 = mysqli_fetch_object($Query)){ 
					  
						$id_type  					 =	$ResultBlockedtable1->id_type;
	                  	$remarks[$purch_id][$id_type]   =	$ResultBlockedtable1->remark;
						$tips[$purch_id][$id_type]	  =	$ResultBlockedtable1->tips;
						$amount[$purch_id][$id_type]	=	$ResultBlockedtable1->amount;	
						$id_company[$purch_id][$id_type]	=	$ResultBlockedtable1->id_company;				
						$TotalPaidAmount 	   +=	$ResultBlockedtable1->amount;	
						
						$id_onlinetransfertype[$purch_id][$id_type]	  =   $ResultBlockedtable1->id_onlinetransfertype;
						if($ResultBlockedtable1->payment_mode=='CARD'){
						 $cardId[]	=	$ResultBlockedtable1->id;	
						$cardnumber[$purch_id][$id_type][]	=	$ResultBlockedtable1->cardnumber;					
						}
						
					  }
						$balance_amount	=	($grand_total_amount-$TotalPaidAmount);
						$CardAmount	=	'';
						$cardRemark	=	'';
						$CardNumber	=	'';
						$CardTips	  =   '';
						$gridNo=0;
						$cardIdlength	= count($cardId);	
						if($cardIdlength>0){
						$height	=(81*$cardIdlength);
						}else{ $height	=81;}
						//debugData($amount);
?>
  <tr>
                    
                      <td colspan="10">
                    <div id="div<?php echo $purch_id;?>" class="targetDiv" style="display:none;">
                      <form name="listingForm_<?php echo $purch_id;?>" id="listingForm_<?php echo $purch_id;?>" action="" method='POST' data-parsley-validate>
                        <input type="hidden" value="" name="act" />
                        <input type="hidden" name="get_purch_id" id="get_purch_id"  value="<?php echo $purch_id;?>"/>
                        <div class="box-body">
                          <div class="card text-dark bg-light">
                            <div class="row">
                              <input type="hidden" class="form-control" readonly placeholder="mdock_no" id="mdock_no" name="mdock_no" value="<?php echo $purch_row->mdoc_no;?>"  >
                            </div>
                            <div class="row">
                              <div class="form-group col-xs-12 col-md-12 col-sm-12" >
                                <div class="box-body" style=" padding-bottom:0px !important;">
                                  <div class="card text-dark bg-light">
                                    <div class="row">
                                      <div class="col-md-12">
                                        <div class="form-group" style="margin-bottom: 1px;" >
                                          <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;" >
                                            <table id="myTableOrder1" class="table dataTable no-footer table-responsive" cellspacing="0" style="font-size:14px;padding: 0px 0px;border: 1px solid #3c8dbc;" >
                                              <thead style="font-size:10px;padding: 0px 0px;">
                                                <tr style="background-color: #3c8dbc;color: #fff;font-variant-caps: all-petite-caps;font-size: 14px;">
                                                  <th></th>
                                                  <th style="width:350px;padding: 5px 9px;"> Payment Mode.&nbsp;</th>
                                                  <th style="width:100px;padding: 5px 9px;">Amount</th>
                                                  <th style=" padding: 5px 9px;">Remarks</th>
                                                  <th style="width:100px;padding: 5px 9px;">Tips</th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                                <tr id="trbgcolor">
                                                <td style="width: 2.5%;"> 
                                                <input type="checkbox"  <?php  if($amount[$purch_id][1]>0){ echo 'checked="checked"';} ?> class="flat-red i-checks checkboxpayamount_1_<?php echo $purch_id;?>"   name="checkboxpayamount" id="checkboxpayamount" value="<?php echo $amount[$purch_id][1].'_1_'.$grand_total_amount.'_'.$purch_id; ?>"  />
                                                 </td>
                                                 <td> <div class="info-box paymentmode" > <span class="info-box-icon bg-aqua paymode-span"> 
                                                 <img src="../images/cashpay.png" style="cursor:pointer;" title=" Bill Payment "  /> </span>
                                                      <div class="info-box-content"> <span class="info-box-text">CASH</span> </div>
                                                      <!-- /.info-box-content --> 
                                                    </div>
                                                    
                                                    <!-- /.info-box --></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][1]>0){ $amount[$purch_id][1];}else {echo 'disabled="disabled"';} ?> class="form-control first-input billingamount_<?php echo $purch_id;?>" name="payamount[<?php echo $purch_id;?>][CASH][]" id="payamount_1_<?php echo $purch_id;?>" onKeyUp="getpayamount(this.value,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>,1);"  value="<?php echo $amount[$purch_id][1]?$amount[$purch_id][1]:0; ?>" style="float: left;" data-parsley-required data-parsley-errors-container="#payamountError"/></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][1]>0){ $amount[$purch_id][1];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" placeholder="Remarks" name="remarks[<?php echo $purch_id;?>][CASH][]" id="remarks_1_<?php echo $purch_id;?>" value="<?php echo $remarks[$purch_id][1]; ?>" style="float: left;"/></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][1]>0){ $amount[$purch_id][1];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" name="tips[<?php echo $purch_id;?>][CASH][]" id="tips_1_<?php echo $purch_id;?>" value="<?php echo $tips[$purch_id][1]; ?>" style="float: left;"/></td>
                                                </tr>
                                                <!----------------------CARD PAYMENT------------------------------------>
                                                 <?php $cardStarCount	=	1;?>
                                                  <?php 
								
								if($cardIdlength>0){
								$len=1;
								$gridNo=0;
								
					foreach($cardId as $id_card){
								$gridNo++;
					$cardSql = "SELECT * FROM `".TBL_PURCH_PAY."` WHERE id='".$id_card."' ";					
					$cardQuery	=	mysqli_query($connNew,$cardSql); 					
					$Resultcardpayment = mysqli_fetch_object($cardQuery);
					$CardAmount	=	$Resultcardpayment->amount;
					$cardRemark	=	$Resultcardpayment->remark;			   
					$CardNumber	=	$Resultcardpayment->cardnumber;
					$CardTips	  =   $Resultcardpayment->tips;					
					$id_cardtype	  =   $Resultcardpayment->id_cardtype;	
										
								  // if($len==1){
									   
									   ?>
                                                  <tr style="border:1px solid red;background-color:#fff;" id="grid<?php echo $gridNo;?>_<?php echo $purch_id;?>" >
                                                <td style="width: 2.5%;"><?php if($gridNo==1){?><input type="checkbox" <?php  if($CardAmount>0){ echo 'checked="checked"';} ?>  class="flat-red i-checks checkboxpayamount_2_<?php echo $purch_id;?>"   name="checkboxpayamount" id="checkboxpayamount" value="<?php echo $CardAmount.'_2_'.$grand_total_amount.'_'.$purch_id; ?>"  />
                                                <?php } ?>
                                                </td>
                                                <td>
                                                <div class="info-box" style="height:90px !important;min-height: 90px !important;margin-bottom: 0px !important;" > 
                                                <span class="info-box-icon bg-aqua" style="height:90px !important;line-height: 90px !important;"> 
                                                
                                                
                                                <img src="../images/credit_cards_card-512.png" style="cursor:pointer;" title=" Bill Payment "  /> 
                                                </span>
                                                    <div class="info-box-content" style="width: 83%;height: 28px;"> <span class="info-box-text" style="width:81%;float:left;">CARD </span>
                                                       <?php if($gridNo==1){?><button class="pull-left btn btn-success btn-xs" type="button"  onclick="addNewGrid(<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>);"  style="margin: 0px;float:right;" ><i class="fa fa-plus-circle"></i></button><?php } ?>
                                                    </div>
                                                    <!-- /.info-box-content --> 
                                                    
                                                   
                                                  
                                                   
                                                   <div class="info-box" style="height:60px !important;min-height: 60px !important;margin-bottom: 0px !important;" > 
                        <span class="info-box-number">
                       
                        <div class="box-body" style="width: 16%;float: left;padding: 0px !important;height: 60px;margin-left: 16px;">
                          <div class="form-group" style="margin-bottom: 0px;">
                            <div style="margin-left: 15px;">
                              <label for="name" class="paymentlable">
                                <input type="radio" class="flat-red" <?php if($id_cardtype == '1'){echo "checked";} ?> value="1" name="cardtype[<?php echo $purch_id;?>][CARDTYPE][][<?php echo $gridNo;?>]" id="cardtype"/>
                              </label>
                            </div>
                            <img class="flagimgs first" src="<?php echo $SITE_URL; ?>/plugins/dist/img/credit/visa.png" alt="Visa"> </div>
                        </div>
                        <div class="box-body" style="width: 16%;float: left;padding: 0px !important; height: 60px;">
                          <div class="form-group" style="margin-bottom: 0px;">
                            <div style="margin-left: 15px;">
                              <label for="name" class="paymentlable">
                                <input type="radio" <?php if($id_cardtype == '2'){echo "checked";} ?> class="flat-red" value="2" name="cardtype[<?php echo $purch_id;?>][CARDTYPE][][<?php echo $gridNo;?>]" id="cardtype"/>
                              </label>
                            </div>
                            <img class="flagimgs first" src="<?php echo $SITE_URL; ?>/plugins/dist/img/credit/mastercard.png" /> </div>
                        </div>
                        <div class="box-body" style="width: 16%;float: left;padding: 0px !important; height: 60px;">
                          <div class="form-group" style="margin-bottom: 0px;">
                            <div style="margin-left: 15px;">
                              <label for="name" class="paymentlable">
                                <input type="radio" class="flat-red" <?php if($id_cardtype == '3'){echo "checked";} ?> value="3" name="cardtype[<?php echo $purch_id;?>][CARDTYPE][][<?php echo $gridNo;?>]" id="cardtype"/>
                              </label>
                            </div>
                            <img class="flagimgs first" src="<?php echo $SITE_URL; ?>/plugins/dist/img/credit/american-express.png" /> </div>
                        </div>
                        <div class="box-body" style="width: 16%;float: left;padding: 0px !important; height: 60px;">
                          <div class="form-group" style="margin-bottom: 0px;">
                            <div style="margin-left: 15px;">
                              <label for="name" class="paymentlable">
                                <input type="radio" class="flat-red" <?php if($id_cardtype == '4'){echo "checked";} ?> value="4" name="cardtype[<?php echo $purch_id;?>][CARDTYPE][][<?php echo $gridNo;?>]" id="cardtype"/>
                              </label>
                            </div>
                            <img class="flagimgs first" src="<?php echo $SITE_URL; ?>/plugins/dist/img/credit/paypal2.png" /> </div>
                        </div>
                        
                        
                        
                        
                       </span> </div> </div></td>
                                                 
                                               
                                                    
                                                      <td style="width: 12.5%;"><input type="text" <?php  if($CardAmount>0){ $CardAmount;}else {echo 'disabled="disabled"';} ?> class="form-control first-input billingamount_<?php echo $purch_id;?>" name="payamount[<?php echo $purch_id;?>][CARD][]" id="payamount_2_<?php echo $purch_id;?>" onKeyUp="getpayamount(this.value,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>,2);"  value="<?php echo $CardAmount?$CardAmount:0; ?>" style="float: left;" data-parsley-required data-parsley-errors-container="#payamountError"/></td>
                                                      <td style="width: 35.5%;"><div class="form-group" style="width:100% !important;margin-bottom: 5px!important;">
                                                          <div class="input-group"  style="width:100% !important;">
                                                            <input type="text" <?php  if($CardAmount>0){ $CardAmount;}else {echo 'disabled="disabled"';} ?> class="form-control first-input" placeholder="Card Number" name="cardnumber[<?php echo $purch_id;?>][CARDNUMBER][]" id="cardnumber_2_<?php echo $purch_id;?>" value="<?php echo $CardNumber; ?>" style="width:100% !important;"/>
                                                          </div>
                                                        </div>
                                                        <input type="text" <?php  if($CardAmount>0){ $CardAmount;}else {echo 'disabled="disabled"';} ?> class="form-control first-input" placeholder="Remarks" name="remarks[<?php echo $purch_id;?>][CARD][]" id="remarks_2_<?php echo $purch_id;?>" value="<?php echo $cardRemark; ?>" style="float: left;"/></td>
                                                        
                       
                                                        
                                                      <td ><div class="form-group" style="width:100% !important;margin-bottom: 5px!important;">
                                                          <div class="input-group"  style="width:100% !important;">
                                                            <input type="text" <?php  if($CardAmount>0){ $CardAmount;}else {echo 'disabled="disabled"';} ?> class="form-control first-input" name="tips[<?php echo $purch_id;?>][CARD][]" id="tips_2_<?php echo $purch_id;?>" value="<?php echo $CardTips; ?>" style="float: left;"/>
                                                          </div>
                                                        </div>
                                                       <?php if($gridNo>1){?> <a class="btn btn-danger btn-sm" href="javascript:void(0);"  onclick="removeGrid(<?php echo $gridNo;?>,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>);"> <i class="fa fa-trash-o fa-lg"></i> </a><?php } ?></td>
                                                      </td>
                                                    
                                                      </tr>
                                             <?php 
													$len++;
												   } ?>
                                                  <?php }else{ ?>       
                                                   <tr style="border:1px solid red;background-color:#fff;" id="grid<?php echo $gridNo;?>_<?php echo $purch_id;?>" >
                                                <td style="width: 2.5%;"><input type="checkbox" <?php  if($CardAmount>0){ echo 'checked="checked"';} ?> class="flat-red i-checks checkboxpayamount_2_<?php echo $purch_id;?>"   name="checkboxpayamount" id="checkboxpayamount" value="<?php echo $amount[$purch_id][2].'_2_'.$grand_total_amount.'_'.$purch_id; ?>"  /></td>
                                                <td>
                                                <div class="info-box" style="height:90px !important;min-height: 90px !important;margin-bottom: 0px !important;" > 
                                                <span class="info-box-icon bg-aqua" style="height:90px !important;line-height: 90px !important;"> 
                                                
                                                
                                                <img src="../images/credit_cards_card-512.png" style="cursor:pointer;" title=" Bill Payment "  /> 
                                                </span>
                                                    <div class="info-box-content" style="width: 83%;height: 28px;"> <span class="info-box-text" style="width:87%;float:left;">CARD </span>
                                                       <button class="pull-left btn btn-success btn-xs" type="button"  onclick="addNewGrid(<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>);"  style="margin: 0px;float:right;" ><i class="fa fa-plus-circle"></i></button>
                                                    </div>
                                                    <!-- /.info-box-content --> 
                                                    
                                                   
                                                  
                                                   
                                                   <div class="info-box" style="height:60px !important;min-height: 60px !important;margin-bottom: 0px !important;" > 
                        <span class="info-box-number">
                       
                        <div class="box-body" style="width: 16%;float: left;padding: 0px !important;height: 60px;margin-left: 16px;">
                          <div class="form-group" style="margin-bottom: 0px;">
                            <div style="margin-left: 15px;">
                              <label for="name" class="paymentlable">
                                <input type="radio" class="flat-red" <?php if($id_cardtype == '1'){echo "checked";} ?> value="1" name="cardtype[<?php echo $purch_id;?>][CARDTYPE][][<?php echo $gridNo;?>]" id="cardtype"/>
                              </label>
                            </div>
                            <img class="flagimgs first" src="<?php echo $SITE_URL; ?>/plugins/dist/img/credit/visa.png" alt="Visa"> </div>
                        </div>
                        <div class="box-body" style="width: 16%;float: left;padding: 0px !important; height: 60px;">
                          <div class="form-group" style="margin-bottom: 0px;">
                            <div style="margin-left: 15px;">
                              <label for="name" class="paymentlable">
                                <input type="radio" <?php if($id_cardtype == '2'){echo "checked";} ?> class="flat-red" value="2" name="cardtype[<?php echo $purch_id;?>][CARDTYPE][][<?php echo $gridNo;?>]" id="cardtype"/>
                              </label>
                            </div>
                            <img class="flagimgs first" src="<?php echo $SITE_URL; ?>/plugins/dist/img/credit/mastercard.png" /> </div>
                        </div>
                        <div class="box-body" style="width: 16%;float: left;padding: 0px !important; height: 60px;">
                          <div class="form-group" style="margin-bottom: 0px;">
                            <div style="margin-left: 15px;">
                              <label for="name" class="paymentlable">
                                <input type="radio" class="flat-red" <?php if($id_cardtype == '3'){echo "checked";} ?> value="3" name="cardtype[<?php echo $purch_id;?>][CARDTYPE][][<?php echo $gridNo;?>]" id="cardtype"/>
                              </label>
                            </div>
                            <img class="flagimgs first" src="<?php echo $SITE_URL; ?>/plugins/dist/img/credit/american-express.png" /> </div>
                        </div>
                        <div class="box-body" style="width: 16%;float: left;padding: 0px !important; height: 60px;">
                          <div class="form-group" style="margin-bottom: 0px;">
                            <div style="margin-left: 15px;">
                              <label for="name" class="paymentlable">
                                <input type="radio" class="flat-red" <?php if($id_cardtype == '4'){echo "checked";} ?> value="4" name="cardtype[<?php echo $purch_id;?>][CARDTYPE][][<?php echo $gridNo;?>]" id="cardtype"/>
                              </label>
                            </div>
                            <img class="flagimgs first" src="<?php echo $SITE_URL; ?>/plugins/dist/img/credit/paypal2.png" /> </div>
                        </div>
                        
                        
                        
                        
                       </span> </div> </div></td>
                                                 
                                               
                                                    
                                                      <td style="width: 12.5%;"><input type="text" <?php  if($amount[$purch_id][2]>0){ $amount[$purch_id][2];}else {echo 'disabled="disabled"';} ?> class="form-control first-input billingamount_<?php echo $purch_id;?>" name="payamount[<?php echo $purch_id;?>][CARD][]" id="payamount_2_<?php echo $purch_id;?>" onKeyUp="getpayamount(this.value,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>,2);"  value="<?php echo $CardAmount?$CardAmount:0; ?>" style="float: left;" data-parsley-required data-parsley-errors-container="#payamountError"/></td>
                                                      <td style="width: 35.5%;"><div class="form-group" style="width:100% !important;margin-bottom: 5px!important;">
                                                          <div class="input-group"  style="width:100% !important;">
                                                            <input type="text" <?php  if($amount[$purch_id][2]>0){ $amount[$purch_id][2];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" placeholder="Card Number" name="cardnumber[<?php echo $purch_id;?>][CARDNUMBER][]" id="cardnumber_2_<?php echo $purch_id;?>" value="<?php echo $CardNumber; ?>" style="width:100% !important;"/>
                                                          </div>
                                                        </div>
                                                        <input type="text" <?php  if($amount[$purch_id][2]>0){ $amount[$purch_id][2];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" placeholder="Remarks" name="remarks[<?php echo $purch_id;?>][CARD][]" id="remarks_2_<?php echo $purch_id;?>" value="<?php echo $cardRemark; ?>" style="float: left;"/></td>
                                                        
                       
                                                        
                                                      <td ><div class="form-group" style="width:100% !important;margin-bottom: 5px!important;">
                                                          <div class="input-group"  style="width:100% !important;">
                                                            <input type="text" <?php  if($amount[$purch_id][2]>0){ $amount[$purch_id][2];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" name="tips[<?php echo $purch_id;?>][CARD][]" id="tips_2_<?php echo $purch_id;?>" value="<?php echo $CardTips; ?>" style="float: left;"/>
                                                          </div>
                                                        </div>
                                                       <?php if($gridNo>1){?> <a class="btn btn-danger btn-sm" href="javascript:void(0);"  onclick="removeGrid(<?php echo $gridNo;?>,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>);"> <i class="fa fa-trash-o fa-lg"></i> </a><?php } ?></td>
                                                      </td>
                                                    
                                                      </tr>
                                                  <?php }?>
                                                 <tr id="trbgcolor">
                                                    <td colspan="5" style="padding:0px !important;"><div id="rowGrid_<?php echo $purch_id;?>"></div></td>
                                                  </tr>
                                               
                                                 
                                                  
                                                  
                                                
                                                <!----------------------ONLINE TRANSFER ------------------------------------>
                                                
                                                <tr id="trbgcolor">
                                                  <td style="width: 2.5%;"> 
                                                  <input type="checkbox" <?php  if($amount[$purch_id][3]>0){ echo 'checked';} ?> class="flat-red i-checks checkboxpayamount_3_<?php echo $purch_id;?>"   name="checkboxpayamount" id="checkboxpayamount" value="<?php echo $amount[$purch_id][3].'_3_'.$grand_total_amount.'_'.$purch_id; ?>"  />
                                               </td>
                                               <td> 
                                                  <div class="info-box" style="height:80px !important;min-height: 80px !important;margin-bottom: 0px !important;" > 
                                                  <span class="info-box-icon bg-aqua" style="height:80px !important;line-height: 80px !important;"> 
                                                  <img src="../images/online.png" style="cursor:pointer;" title=" Bill Payment "  /> </span>
                                                      <div class="info-box-content"> <span class="info-box-text">ONLINE TRANSFER</span> </div>
                                                      <!-- /.info-box-content --> 
                                                      <span class="info-box-number"><small>
    <div class="box-body" style="width: 16%;float: left;padding: 0px !important; height: 57px;margin-left: 16px;">
    <div class="form-group">
    <div style="margin-left: 15px;">
    <label for="name" class="paymentlable">
    <input type="radio" class="flat-red" <?php if($id_onlinetransfertype[$purch_id][3] == '1'){echo "checked";} ?> value="1" name="onlinetransfertype[<?php echo $purch_id;?>][ONLINETYPE][]" id="onlinetransfertype"/>
    </label>
    </div>
    <img src="../images/upi.png" style="cursor:pointer;" title="upi"  /></div>
    </div>
                                                      
    <div class="box-body" style="width: 16%;float: left;padding: 0px !important; height: 57px;">
    <div class="form-group">
    <div style="margin-left: 15px;">
    <label for="name" class="paymentlable">
    <input type="radio" class="flat-red" <?php if($id_onlinetransfertype[$purch_id][3] == '2'){echo "checked";} ?> value="2" name="onlinetransfertype[<?php echo $purch_id;?>][ONLINETYPE][]" id="onlinetransfertype"/>
    </label>
    </div>
    <img src="../images/paytm.png" style="cursor:pointer;" title="upi"  /></div>
    </div>                                                  
                                                      
    <div class="box-body" style="width: 16%;float: left;padding: 0px !important; height: 57px;">
    <div class="form-group">
    <div style="margin-left: 15px;">
    <label for="name" class="paymentlable">
    <input type="radio" class="flat-red" <?php if($id_onlinetransfertype[$purch_id][3] == '3'){echo "checked";} ?> value="3" name="onlinetransfertype[<?php echo $purch_id;?>][ONLINETYPE][]" id="onlinetransfertype"/>
    </label>
    </div>
    <img src="../images/payu.png" style="cursor:pointer;" title="upi"  /></div>
    </div>
    
    <div class="box-body" style="width: 16%;float: left;padding: 0px !important; height: 57px;">
    <div class="form-group">
    <div style="margin-left: 15px;">
    <label for="name" class="paymentlable">
    <input type="radio" class="flat-red" <?php if($id_onlinetransfertype[$purch_id][3] == '4'){echo "checked";} ?> value="4" name="onlinetransfertype[<?php echo $purch_id;?>][ONLINETYPE][]" id="onlinetransfertype"/>
    </label>
    </div>
    <img src="../images/google-pay.png" style="cursor:pointer;" title="upi"  /></div>
    </div>                                              
     <div class="box-body" style="width: 10%;float: left;padding: 0px !important; height: 57px;">
    <div class="form-group">
    <div style="margin-left: 15px;">
    <label for="name" class="paymentlable">
    <input type="radio" class="flat-red" <?php if($id_onlinetransfertype[$purch_id][3] == '5'){echo "checked";} ?> value="5" name="onlinetransfertype[<?php echo $purch_id;?>][ONLINETYPE][]" id="onlinetransfertype"/>
    </label>
    </div>
    <img src="../images/neft.png" style="cursor:pointer;" title="upi"  /></div>
    </div>
     
     
         
          </small></span> </div></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][3]>0){ $amount[$purch_id][3];}else {echo 'disabled="disabled"';} ?> class="form-control first-input billingamount_<?php echo $purch_id;?>" name="payamount[<?php echo $purch_id;?>][ONLINETRANSFER][]" id="payamount_3_<?php echo $purch_id;?>" onKeyUp="getpayamount(this.value,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>,3);"  value="<?php echo $amount[$purch_id][3]?$amount[$purch_id][3]:0; ?>" style="float: left;" data-parsley-required data-parsley-errors-container="#payamountError"/></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][3]>0){ $amount[$purch_id][3];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" placeholder="Remarks" name="remarks[<?php echo $purch_id;?>][ONLINETRANSFER][]" id="remarks_3_<?php echo $purch_id;?>" value="<?php echo $remarks[$purch_id][3]; ?>" style="float: left;"/></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][3]>0){ $amount[$purch_id][3];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" name="tips[<?php echo $purch_id;?>][ONLINETRANSFER][]" id="tips_3_<?php echo $purch_id;?>" value="<?php echo $tips[$purch_id][3]; ?>" style="float: left;"/></td>
                                                </tr>
                                                
                                                <!------------------COMPANY--------START------------------------------>
                                                <tr id="trbgcolor">
                                                  <td style="width: 2.5%;"> 
                                                   <input type="checkbox" <?php  if($amount[$purch_id][4]>0){ echo 'checked';} ?> class="flat-red i-checks checkboxpayamount_4_<?php echo $purch_id;?>"   name="checkboxpayamount" id="checkboxpayamount" value="<?php echo $amount[$purch_id][4].'_4_'.$grand_total_amount.'_'.$purch_id; ?>"  />
                                                   </td>
                                                   <td> <div class="info-box" style="height:80px !important;min-height: 80px !important;margin-bottom: 0px !important;"> <span class="info-box-icon bg-aqua" style="height:80px !important;line-height: 70px !important;"> <img src="../images/company.png" style="cursor:pointer;" title=" Bill Payment "  /> </span>
                                                      <div class="info-box-content"> <span class="info-box-text">COMPANY</span> </div>
                                                      <!-- /.info-box-content --> 
                                                    </div></td>
                                                  <td><input type="text"   <?php  if($amount[$purch_id][4]>0){ $amount[$purch_id][4];}else {echo 'disabled="disabled"';} ?> class="form-control first-input billingamount_<?php echo $purch_id;?>" name="payamount[<?php echo $purch_id;?>][COMPANY][]" id="payamount_4_<?php echo $purch_id;?>" onKeyUp="getpayamount(this.value,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>,4);"  value="<?php echo  $amount[$purch_id][4]?$amount[$purch_id][4]:0;  ?>" style="float: left;" data-parsley-required data-parsley-errors-container="#payamountError"/></td>
                                                  <td><div class="form-group" style="width:100% !important; margin-bottom:5px !important;">
                                                      <div class="input-group"  style="width:100% !important;">
                                                        <select class="form-control first-input select2" style="width:100% !important;" name="id_company[<?php echo $purch_id;?>][COMPANY][]" id="id_company_4_<?php echo $purch_id;?>" <?php  if($amount[$purch_id][4]>0){ $amount[$purch_id][4];}else {echo 'disabled="disabled"';} ?>>
                                                          <option value="0">Select Company </option>
                                                          <?php  $resCat = selectSql(MST_COMPANY," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' and name !=' ' ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($id_company[$purch_id][4] == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													echo $categoryDropDown = '<option '.$selected.'  value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }?>
                                                        </select>
                                                      </div>
                                                    </div>
                                                    <input type="text" <?php  if($amount[$purch_id][4]>0){ $amount[$purch_id][4];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" placeholder="Remarks" name="remarks[<?php echo $purch_id;?>][COMPANY][]" id="remarks_4_<?php echo $purch_id;?>" value="<?php echo $remarks[$purch_id][4]; ?>" style="float: left;"/></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][4]>0){ $amount[$purch_id][4];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" name="tips[<?php echo $purch_id;?>][COMPANY][]" id="tips_4_<?php echo $purch_id;?>" value="<?php echo $tips[$purch_id][4]; ?>" style="float: left;"/></td>
                                                </tr>
                                                 <!------------------COMPANY---END----------------------------------->
                                                
                                                <tr id="trbgcolor">
                                                  <td style="width: 2.5%;"> 
                                                  <input type="checkbox" <?php  if($amount[$purch_id][5]>0){ echo 'checked';} ?> class="flat-red i-checks checkboxpayamount_5_<?php echo $purch_id;?>"   name="checkboxpayamount" id="checkboxpayamount" value="<?php echo $amount[$purch_id][5].'_5_'.$grand_total_amount.'_'.$purch_id; ?>"  />
                                               </td>
                                               <td> 
                                                   <div class="info-box paymentmode" > <span class="info-box-icon bg-aqua paymode-span"> <img src="../images/cheq.jpg" style="cursor:pointer;" title=" Bill Payment "  /> </span>
                                                      <div class="info-box-content"> <span class="info-box-text">CHEQUE</span> </div>
                                                      <!-- /.info-box-content --> 
                                                    </div></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][5]>0){ $amount[$purch_id][5];}else {echo 'disabled="disabled"';} ?>  class="form-control first-input billingamount_<?php echo $purch_id;?>" name="payamount[<?php echo $purch_id;?>][CHEQUE][]" id="payamount_5_<?php echo $purch_id;?>" onKeyUp="getpayamount(this.value,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>,5);"  value="<?php echo $amount[$purch_id][5]?$amount[$purch_id][5]:0; ?>" style="float: left;" data-parsley-required data-parsley-errors-container="#payamountError"/></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][5]>0){ $amount[$purch_id][5];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" placeholder="Remarks" name="remarks[<?php echo $purch_id;?>][CHEQUE][]" id="remarks_5_<?php echo $purch_id;?>" value="<?php echo $remarks[$purch_id][5]; ?>" style="float: left;"/></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][5]>0){ $amount[$purch_id][5];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" name="tips[<?php echo $purch_id;?>][CHEQUE][]" id="tips_5_<?php echo $purch_id;?>" value="<?php echo $tips[$purch_id][5]; ?>" style="float: left;"/></td>
                                                </tr>
                                                <tr id="trbgcolor">
                                                  <td style="width: 2.5%;"> 
                                                   <input type="checkbox"  <?php  if($amount[$purch_id][6]>0){ echo 'checked';} ?> class="flat-red i-checks checkboxpayamount_6_<?php echo $purch_id;?>"   name="checkboxpayamount" id="checkboxpayamount" value="<?php echo $amount[$purch_id][6].'_6_'.$grand_total_amount.'_'.$purch_id; ?>"  /></div> 
                                                   </td>
                                                   <td> <div class="info-box paymentmode" > <span class="info-box-icon bg-aqua paymode-span"> <img src="../images/gift.jpg" style="cursor:pointer;" title=" Bill Payment "  /> </span>
                                                      <div class="info-box-content"> <span class="info-box-text">UPI</span> </div>
                                                      <!-- /.info-box-content --> 
                                                    </div></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][6]>0){ $amount[$purch_id][6];}else {echo 'disabled="disabled"';} ?> class="form-control first-input billingamount_<?php echo $purch_id;?>" name="payamount[<?php echo $purch_id;?>][UPI][]" id="payamount_6_<?php echo $purch_id;?>" onKeyUp="getpayamount(this.value,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>,6);"  value="<?php echo $amount[$purch_id][6]?$amount[$purch_id][6]:0; ?>" style="float: left;" data-parsley-required data-parsley-errors-container="#payamountError"/></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][6]>0){ $amount[$purch_id][6];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" placeholder="Remarks" name="remarks[<?php echo $purch_id;?>][UPI][]" id="remarks_6_<?php echo $purch_id;?>" value="<?php echo $remarks[$purch_id][6]; ?>" style="float: left;"/></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][6]>0){ $amount[$purch_id][6];}else {echo 'disabled="disabled"';} ?>  class="form-control first-input" name="tips[<?php echo $purch_id;?>][UPI][]" id="tips_6_<?php echo $purch_id;?>" value="<?php echo $tips[$purch_id][6]; ?>" style="float: left;"/></td>
                                                </tr>
                                              </tbody>
                                            </table>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="card text-dark bg-light" style="background-color:#3c8dbc;">
                                      <div class="row">
                                        <div class="form-group col-xs-12 col-md-2 col-sm-2" >
                                          <label for="name" style="margin-left:5px;color:#fff;">Date</label>
                                          <div class="input-group" style="margin-left:5px;">
                                            <div class="input-group-addon"> <i class="fa fa-diamond"></i> </div>
                                            <input   type="text" class="form-control dates" readonly placeholder="sreEnter PO Date" id="po_date1" name="po_date1" value="<?php echo date('d-m-Y');?>" >
                                          </div>
                                        </div>
                                        <div class="form-group col-xs-12 col-md-2 col-sm-2">
                                          <label for="name" style="color:#fff;">Bill Amount</label>
                                          <div class="input-group">
                                            <div class="input-group-addon"> <i class="fa fa-diamond"></i> </div>
                                            <input type="text" class="form-control" placeholder="Total Amount" id="grand_total_amount" name="grand_total_amount" value="<?php echo $grand_total_amount; ?>" readonly>
                                          </div>
                                        </div>
                                        <div class="form-group col-xs-12 col-md-2 col-sm-2">
                                          <label for="name" style="color:#fff;">Paid Amount</label>
                                          <div class="input-group">
                                            <div class="input-group-addon"> <i class="fa fa-asterisk"></i> </div>
                                            <input type="text" class="form-control" disabled placeholder="Total Pay Amount" id="pay_total_amount_<?php echo $purch_id;?>" name="pay_total_amount_<?php echo $purch_id;?>" value="<?php echo $TotalPaidAmount;?>"  style="text-align:right;" data-parsley-required data-parsley-errors-container="#pay_total_amountError" >
                                          </div>
                                        </div>
                                        <div class="form-group col-xs-12 col-md-2 col-sm-2">
                                          <label for="name" style="color:#fff;">Balance</label>
                                          <div class="input-group">
                                            <div class="input-group-addon"> <i class="fa fa-asterisk"></i> </div>
                                            <input type="text" class="form-control" disabled placeholder="Balance Amount" id="balance_amount_<?php echo $purch_id;?>" name="balance_amount_<?php echo $purch_id;?>" value="<?php echo round($balance_amount,2); ?>"  style="text-align:right;"  >
                                          </div>
                                        </div>
                                        <div class="form-group col-xs-12 col-md-4 col-sm-4">
                                          <div class="input-group" style="margin-top:24px;">
                                              <input type='button' name="saveForm" id="saveForm" value='Save' class="btn btn-success" onClick="ajaxAddBillPayment(<?php echo $purch_id;?>,1);"  >
                                              &nbsp;
                <!--  <input type='button' name="cancelled" id="cancelled" value='Cancel Bill' class="btn btn-danger"  onClick="ajaxcancel(<?php echo $purch_id;?>);" >-->
                            &nbsp;                                     
                               <?php  if($row->payment_status=='Settled' || $row->payment_status=='Partial'){
                    ?>
           <input type='button' name="saveunsettled" id="saveunsettled" value='Unsettle' class="btn btn-success" onClick="ajaxAddBillPayment(<?php echo $purch_id;?>,0);"   >
                                
                        <?php }	?>
                                             
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <!-- Total Amount Section --> 
                                    
                                  </div>
                                </div>
                              </div>
                              <div > </div>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                      </td>
                    
                      </tr>



<? //  Settle Bill End----------------------------------------------------- ?>
          <!--printtable start-->
          <div id="printTable">
            <div id="invoice-POS" > 
              <!--End InvoiceTop--> 
              
              <!--End Invoice Mid-->
              
              <div  id="bot" > <?php echo '<pre style="font-size:12px!important;">';
			  
							echo $printer	=	printPreview($printgroup);
							echo '</pre>';
						?> </div>
              
              <!--<div id="legalcopy">
						<p class="legal"><strong>Thank you ...</strong> 
						</p>
					</div>--> 
              
           
       
          
          <!--End InvoiceBot--> 
        </div>
		
		
			



 

		
        <!--End Invoice-->
        
        <?php
/*
$dompdf = new DOMPDF();
//$dompdf->set_option("isPhpEnabled", true);
$dompdf->set_paper('landscape', 'landscape');
$dompdf->load_html($printer);
//debugData($dompdf);
$dompdf->render();
//debugData($dompdf);

$font = Font_Metrics::get_font("helvetica", "bold");
$dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
$Filename=$ReportTypeMainTitle.'pos'.date("Y-m-d H:i:s");
	
	$dompdf->output();
	$dompdf->stream($Filename.'.pdf', array("Attachment" => true));
	
	*/	
		
                      
?>
        <!-- /.box-body --> 
        
      </div>
      
      <!-- /.box --> 
    </div>
    <div class="col-md-3 col-lg-2 order-sm-first">
        <div class="rightbtn">
        <?php /*?>  <div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
          <button class="btn c-btn btn-block" style="margin-right:15px" onClick="printdiv('div_print');"><i class="fa fa-print fa-1x"></i> Select Printer</button >
          </div> 
          <div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
          <button class="btn c-btn btn-block" style="margin-right:15px" onClick="printdiv('div_print');"><i class="fa fa-print fa-1x"></i> Print Copies</button >
          </div> <?php */?>
          <?php if($row->payment_status!='Settled'){?>
          <div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
          
          <a  class="btn c-btn btn-block showSingle" target="<?php echo $pos_purch_id;?>" style="cursor:pointer;">Settle</a>
          </div> 
          <?php } ?>
        </div>
    </div>
    <!--end of col-->
      </div>
      <!--end of row-->
    </div>
    
    <!-- /.col --> 
    
  </div>
  
  <!-- /.row -->
  
  </section>
  
  <!-- /.content --> 
  
</div>
<script language="javascript">
       
      function printData()
        { 
           var divToPrint=document.getElementById("printTable");
           newWin= window.open("");
           newWin.document.write(divToPrint.outerHTML);		   
		   
           newWin.print();
           newWin.close();
        } 

        $('button').on('click',function(){
			var i=0;
			for(i=0;i<=1;i++){
			/*	if(i==1){
				 setTimeout(function () {
					// alert(i);
					 printData();
					 }, 800);
					 
				}else{*/
					 printData();
					//}
				
        
			}
        });
		
 function getPDFDownload(CompareYear,Currentfinancialyear){	
var divToPrint=document.getElementById("printTable");
 	  	$.ajax({
			   type: "GET",
			   url: 'ajax/ajaxDownloadpdf.php',
			   data: 'divToPrint='+divToPrint, 
			   success: function (result) {				   
			     $('#CompareYearselected').empty();
				 $('#CompareYearselected').html(result);
				 
       		 
				}
		});
		
  }		
		
   function ajaxcancel(posid){
	
	//$("#cancelled").addClass("bookedby_open");
	$('#bookedby').popup({
        			transition: 'all 0.3s',
           			 autoopen: true,            
        			});
	$("#pos_purch_id").val(posid);				
	}
	
function ajaxCancelPOS(pos_purch_id){
	
var form=$("#Formkotremarks");	
	
	
		var purch = form.serialize();
		var saveType='edit';
		
	
	$('.loading').show();
    if(form.parsley().validate()){
	$('#bookedby').popup('hide');
		$.ajax({
			type: "GET",
			url: 'ajax/ajaxCancelKot.php',
			data: purch, 
			success: function (result) {
				
			        console.log(result);
			        data = JSON.parse(result);
			
					alert('POS Bill Cancel successfully. ');
				window.location.href = "manageOutletBilling.php";
					
      	}

		});

	}
}	
	
	
	
	function printFuntion(id){
		printFuntion
		
alert(id);
	$.ajax({

		type: "POST",
		url: 'include/function.php',
		data: 'po_purch_id='+id, 
		success: function (result) {
		//$( "#ViewKotSelectedTable" ).html(result);

	 	}

	});
		}
$(document).ready(function (){
	
    var table = $('#example').DataTable({
        'responsive': true
    });

    // Handle click on "Expand All" button
    $('#btn-show-all-children').on('click', function(){
        // Expand row details
        table.rows(':not(.parent)').nodes().to$().find('td:first-child').trigger('click');
    });

    // Handle click on "Collapse All" button
    $('#btn-hide-all-children').on('click', function(){
        // Collapse row details
        table.rows('.parent').nodes().to$().find('td:first-child').trigger('click');
    });
});
  </script> 
  <script>

$('input').on('ifChanged', function(data){
 //alert(data.type + ' callback');
 $('#input-1, #input-3').iCheck('check');
 
 
	var result = $(this).val(); 
	resulthtml = result.split('_');
	var linepay= resulthtml[0];
	var get_purch_id=resulthtml[3];
	var grand_total_amount=resulthtml[2];
	var type=resulthtml[1];
	var isChecked = data.currentTarget.checked;


  var sum = 0;
   $(".billingamount_"+get_purch_id).each(function(){
        sum += +$(this).val();
		//var cash = $(this).val();
    });
	 

   $("#payamount_"+type+"_"+get_purch_id).attr('disabled','disabled');
   
if (isChecked == true) {
		//alert(isChecked);
		
		$("#payamount_"+type+"_"+get_purch_id).removeAttr('disabled');
		$("#tips_"+type+"_"+get_purch_id).removeAttr('disabled');
		$("#remarks_"+type+"_"+get_purch_id).removeAttr('disabled');
		$("#id_company_"+type+"_"+get_purch_id).removeAttr('disabled');
		$("#cardnumber_"+type+"_"+get_purch_id).removeAttr('disabled');
		var pay_total_amount=grand_total_amount-sum;
		//alert(pay_total_amount);
		//alert(sum);
		
		$("#payamount_"+type+"_"+get_purch_id).val(pay_total_amount);
		
		var balance_amount=grand_total_amount+sum;		
		$('input[name="pay_total_amount_'+get_purch_id+'"').val(grand_total_amount);
		$('input[name="balance_amount_'+get_purch_id+'"').val(0);
		
		var checkboxpayamount =pay_total_amount+'_'+type+'_'+grand_total_amount+'_'+get_purch_id;
		$('.checkboxpayamount_'+type+'_'+get_purch_id).val(checkboxpayamount);
		
}else{
	//alert(isChecked);
	//alert(grand_total_amount);
		//alert(sum);
		//alert(linepay);
	   $("#payamount_"+type+"_"+get_purch_id).attr('disabled','disabled');
	   $("#tips_"+type+"_"+get_purch_id).attr('disabled','disabled');
	   $("#remarks_"+type+"_"+get_purch_id).attr('disabled','disabled');
	   $("#id_company_"+type+"_"+get_purch_id).attr('disabled','disabled');
	   $("#cardnumber_"+type+"_"+get_purch_id).attr('disabled','disabled');
	   
	   $('#tips_'+type+'_'+get_purch_id).val('');
	   $('#remarks_'+type+'_'+get_purch_id).val('');
	   $('#id_company_'+type+'_'+get_purch_id).val('0');
	   $('#cardnumber_'+type+'_'+get_purch_id).val('');
	   //$('#id_company_'+type+'_'+get_purch_id).empty();
	  
		var pay_total_amount=sum-linepay;
		$('input[name="pay_total_amount_'+get_purch_id+'"').val(pay_total_amount);
		//$(".billingamount_"+get_purch_id).val(0);
		$("#payamount_"+type+"_"+get_purch_id).val(0);	
		var checkboxpayamount ='0_'+type+'_'+grand_total_amount+'_'+get_purch_id;
		$('.checkboxpayamount_'+type+'_'+get_purch_id).val(checkboxpayamount);
		//+ + +linepay
		var grand_total_amount=(+grand_total_amount)-pay_total_amount;
		$('input[name="balance_amount_'+get_purch_id+'"').val(grand_total_amount);
	}
});

function getpayamount(payamount,get_purch_id,grand_total_amount,type){	
	
    var sum = 0;
    $(".billingamount_"+get_purch_id).each(function(){
        sum += +$(this).val();
		//var cash = $(this).val();
    });
	
	if(grand_total_amount>=sum){
		var balance_amount=grand_total_amount-sum;		
	    $('input[name="pay_total_amount_'+get_purch_id+'"').val(sum);
		$('input[name="balance_amount_'+get_purch_id+'"').val(balance_amount);
		
		var checkboxpayamount =payamount+'_'+type+'_'+grand_total_amount+'_'+get_purch_id;
		$('.checkboxpayamount_'+type+'_'+get_purch_id).val(checkboxpayamount);
		
		//$("#balance_amount_"+get_purch_id).val(balance_amount);
	}else{
		$("#payamount_2_"+get_purch_id).attr('disabled','disabled');
		$("#payamount_3_"+get_purch_id).attr('disabled','disabled');
		$("#payamount_4_"+get_purch_id).attr('disabled','disabled');
		$("#payamount_5_"+get_purch_id).attr('disabled','disabled');
		$("#payamount_6_"+get_purch_id).attr('disabled','disabled');
		
		//var checkboxpayamount =payamount+'_'+type+'_'+grand_total_amount+'_'+get_purch_id;
		$('.checkboxpayamount_1_'+get_purch_id).val('0_1_'+grand_total_amount+'_'+get_purch_id);
		$('.checkboxpayamount_2_'+get_purch_id).val('0_2_'+grand_total_amount+'_'+get_purch_id);
		$('.checkboxpayamount_3_'+get_purch_id).val('0_3_'+grand_total_amount+'_'+get_purch_id);
		$('.checkboxpayamount_4_'+get_purch_id).val('0_4_'+grand_total_amount+'_'+get_purch_id);
		$('.checkboxpayamount_5_'+get_purch_id).val('0_5_'+grand_total_amount+'_'+get_purch_id);
		$('.checkboxpayamount_6_'+get_purch_id).val('0_6_'+grand_total_amount+'_'+get_purch_id);
		
		
		$('input[name="pay_total_amount_'+get_purch_id+'"').val(0);
		$(".billingamount_"+get_purch_id).val(0);
		//payamount_"+type+"_"+get_purch_id
		$('input[name="balance_amount_'+get_purch_id+'"').val(grand_total_amount);
		alert('Greater than Total Amount');
		}
	
}
	
	
		

function removeGrid(id,get_purch_id,grand_total_amount){

	
    $('#grid'+id+'_'+get_purch_id).remove();
	
    var sum = 0;
    $(".billingamount_"+get_purch_id).each(function(){
        sum += +$(this).val();
	    });
		
	var balance_amount=(grand_total_amount-sum);	
        $('input[name="pay_total_amount_'+get_purch_id+'"').val(sum);
		$('input[name="balance_amount_'+get_purch_id+'"').val(balance_amount);
}

function ajaxAddBillPayment(get_purch_id,savetype){
	
	/* if(savetype == 0){

  		      	beforeSend:function(){
         return confirm("Are you sure?");
      }
  		      	}*/
				
   var form1=$("#listingForm_"+get_purch_id);	
  // alert(form1);
  
   var dataString = $("#listingForm_"+get_purch_id).serialize()+'&savetype='+savetype;
	  // alert(dataString);
   
   if(form1.parsley().validate()){
	  // alert();
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxAddBillPayment.php',
		   data: dataString,
		   beforeSend:function(){
         if(savetype == 0){
			 return confirm("Are you sure that you want to Unsettled?");
		 }
      }, 
		   success: function (result) {
			   resulthtml = result.split('###');
			   var resultstatus=	resulthtml[0];
			   // alert(resulthtml[0]);
				
				
			   if(resultstatus ==0){
				  $("#updatestatussuccess").html(resulthtml[1]);
				//$( "#my_popup_open" ).click(); 
				$('#ratePoint').popup({
        			transition: 'all 0.3s',
           			 autoopen: true,            
        			});				 
				  $('.targetDiv').not('#div' + $(this).attr('target')).hide();
				  window.location.href = "manageOutletBilling.php";	
				  }else{
					  
					  $("#updatestatus").html(resulthtml[1]);
					  //$( ".my_popupfaild_open" ).click(); 
					  $('#ratePointfaild').popup({
        						transition: 'all 0.3s',
           						 autoopen: true,            
        				});
						window.location.href = "manageOutletBilling.php";
					  }
					
			}
		})
	}
}


$(function() {
	
  $('.showSingle').click(function() {
    $('.targetDiv').not('#div' + $(this).attr('target')).hide();
    $('#div' + $(this).attr('target')).toggle();
  });
});

 </script>
<?php include_once("../includes/footer.php")?>
