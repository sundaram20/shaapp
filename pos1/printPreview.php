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

	$pos_purch_sql="SELECT * FROM ".TBL_PURCH." AS A  WHERE A.id_shop='".$_SESSION['shop']."'  AND id='".encryptor(decrypt, $_REQUEST['printPreviewid'])."'";
	$resultPosPurch = mysqli_query($connNew, $pos_purch_sql); 
	$numRows = mysqli_num_rows($resultPosPurch);
	$posPurchResult = mysqli_fetch_object($resultPosPurch);
	
	$pos_purch_sqlDoc="SELECT max(id) as ids FROM ".TBL_DOC_TYPE_CONFIG." AS A  WHERE doc_type='".$posPurchResult->id_doc_type_configuration."'";
	$resultPosPurchDoc = mysqli_query($connNew, $pos_purch_sqlDoc); 
	$numRowsDoc = mysqli_num_rows($resultPosPurchDoc);
	$posDocResult = mysqli_fetch_object($resultPosPurchDoc);
	
	
	$pos_AccountDoc="SELECT enable_split_bill_by_sales_account_group FROM ".TBL_DOC_TYPE_CONFIG." AS A  WHERE id='".$posPurchResult->id_doc_type_configuration."'";
	$resultpos_AccountDoc = mysqli_query($connNew, $pos_AccountDoc); 
	$numRowspos_AccountDoc = mysqli_num_rows($resultpos_AccountDoc);
	$posDocResultpos_AccountDoc = mysqli_fetch_object($resultpos_AccountDoc);
	$enable_split_bill_by_sales_account_group	= $posDocResultpos_AccountDoc->enable_split_bill_by_sales_account_group;
	
	
	if($enable_split_bill_by_sales_account_group=='1'){
		$printgroup			  = 	fetchdataSalesGroupPrint($pos_purch_id_array,$grouparray=0);
		}else{

  $printgroup			  = 	fetchdataprint($pos_purch_id_array,$grouparray=0);
		}
		
		
		
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
        <div class="form-group has-error mb-0" align="center">
          <?php if($_SESSION['errorMsg']){?>
          <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
          <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
          <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
          <?php unset($_SESSION['successMsg']);}?>
        </div>
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
 /*$sql1 = "SELECT *  from
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

			  
			  
			  */
			  
	$CurrentDate	=date('Y-m-d',strtotime("-5 day", strtotime(date('d-m-Y'))));		  
			  
 $sql = "

select pp.*,

(pp.grant_total_amount) as amount_need_to_pay, 
(pp.payment_amount_received) as amount_paid,
(pp.grant_total_amount-pp.payment_amount_received) as balance_amount

from pos_purch  pp 
 where pp.pos_bill_type=2 and DATE(pp.doc_date)>'".$CurrentDate."'
and  pp.id!=0 AND pp.doc_type=21 AND pp.id='".$pos_purch_id."' 
ORDER BY pp.last_modified desc
";
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
<input type="hidden" name="id_pos_purch_bill" id="id_pos_purch_bill" value="<?php echo $purch_id; ?>">					
                  




<? //  Settle Bill End----------------------------------------------------- ?>
          <!--printtable start-->
          <div id="printTable">
            <div id="invoice-POS" > 
              <!--End InvoiceTop--> 
              
              <!--End Invoice Mid-->
              
              <div  id="bot" > 
			  <?php echo '<pre style="font-size:12px!important;">';
			  
					echo $printer	=	printPreview($printgroup);
					
					echo '</pre>';
						?> </div>
              
              <!--<div id="legalcopy">
						<p class="legal"><strong>Thank you ...</strong> 
						</p>
					</div>--> 
              
           
       
          
          <!--End InvoiceBot--> 
        </div>
		
		</div>
			



 

<?php
$CurrentDate	=date('Y-m-d',strtotime("-5 day", strtotime(date('d-m-Y'))));	
			  
	/*		  
 $TableBillExistSql	=	"SELECT * from ( select pp.*, max(pp.grant_total_amount) as amount_need_to_pay, sum(ppp.amount) as amount_paid, (case when max(pp.cancelled)=1 then 'cancelled' when max(pp.grant_total_amount)=sum(ppp.amount) then 'Settled' when max(pp.grant_total_amount)<>sum(ppp.amount) and sum(ppp.amount)<>0 then 'Partial' when max(pp.grant_total_amount)<>sum(ppp.amount) and sum(ppp.amount)=0 then 'Pending' when max(pp.grant_total_amount)=0 and sum(ppp.amount) is NULL then 'Pending' when max(pp.grant_total_amount)>0 and sum(ppp.amount) is NULL then 'Pending' end) as payment_status from pos_purch pp left join pos_purch_pay ppp on ppp.id_purch=pp.id 
 where pos_bill_type=2 and DATE(pp.doc_date)>'".$CurrentDate."'
 
 group by pp.id ORDER BY last_modified desc )as managekotlist WHERE id!=0 AND doc_type=21 AND payment_status='Pending'  AND id_attribute_table='".$row->id_attribute_table."' ";
		 
			 */
	$TableBillExistSql_new = "

select pp.*,

(pp.grant_total_amount) as amount_need_to_pay, 
(pp.payment_amount_received) as amount_paid,
(pp.grant_total_amount-pp.payment_amount_received) as balance_amount

from pos_purch  pp 
 where pp.pos_bill_type=2 and DATE(pp.doc_date)>'".$CurrentDate."'
and  pp.id!=0 AND pp.doc_type=21 AND  pp.id_attribute_table='".$row->id_attribute_table."' and cancelled!=1 and pp.grant_total_amount-pp.payment_amount_received>0
ORDER BY pp.last_modified desc
";	 
		 
	$resultTableBillExist = mysqli_query($connNew, $TableBillExistSql_new); 
	$numRows	=	mysqli_num_rows($resultTableBillExist);
	while($rowTableBillExist = mysqli_fetch_object($resultTableBillExist)){
		
		$statusOutlet[]	=	$rowTableBillExist->id_mst_outlet;
		
	}
	$countArray	=	array_unique($statusOutlet);

if(count($countArray)>1 && $numRows>1 ){ 
?>		
   <div id="printSummaryTable">                    
 <div id="invoice-POS" >              
              
              <div  id="bot" > 
			  <?php echo '<pre style="font-size:12px!important;page-break-before: always;float:left">';
			  
					//echo $printer	=	printPreview($printgroup);
					echo $printer	=	printBillSummary($printgroup);
					echo '</pre>';
						?> </div>
              
            
        </div> 
        </div>
        <?php  }?> 
        
             
      
      
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
          
          <?php /*?><a  class="btn c-btn btn-block showSingle" target="<?php echo $pos_purch_id;?>" style="cursor:pointer;">Settle</a><?php */?>
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
      function printSummaryData()
        { 
           var divToPrint=document.getElementById("printSummaryTable");
           newWin= window.open("");
           newWin.document.write(divToPrint.outerHTML);		   
		   
           newWin.print();
           newWin.close();
        } 
      function printData()
        {  posPrintStatusUpdate();
           var divToPrint=document.getElementById("printTable");
           newWin= window.open("");
           newWin.document.write(divToPrint.outerHTML);		   
		   
           newWin.print();
           newWin.close();
        } 

        $('button').on('click',function(){
			var i=0;
			for(i=0;i<1;i++){
				//alert(i);
        printData();
			}
		<?php if(count($countArray)>1 && $numRows>1){ ?>
				printSummaryData();
		<?php }?>	
			
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
function posPrintStatusUpdate(){ 
	var id_pos_purch_bill=$("#id_pos_purch_bill").val();
	$.ajax({
		   type: "GET",
			   url: 'ajax/ajaxPosPrintStatusUpdate.php',
			   data: 'id_pos_purch_bill='+id_pos_purch_bill,  
			   success: function (result) {
				   
				   //$("#showKotstatus").modal('show');
				  // $('#resultkotstatus').html(result);
				   				  
				  
				  
			   }
	})
	}
 </script>
<?php include_once("../includes/footer.php")?>
