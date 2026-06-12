<?php include_once("../config/auto_loader.php");
 include_once("../includes/header.php");include_once("../includes/left.php");
include_once("include/function1.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_PURCH,'view');


if($_REQUEST['status'] != ''){

	$sql .= " AND `status` = '".addslashes($_REQUEST['status'])."%' ";
}

$sql .= " AND `id_shop` = '".addslashes($_SESSION['shop'])."'";


/*		
if($_REQUEST['search_name'] != ''){
	$sname	=explode('-',$_REQUEST['search_name']);
	$searchDocumentType = " AND pp.`mdoc_no` ='".addslashes($sname[1])."'";

}	 */


$session=$_REQUEST['session'];
$session1=$_SESSION['id_document'];


if($session=='25'){
	$doctypename	=	'Laundry';
}
if($session=='26'){
	$doctypename	=	'Spa and Health Club';
}

if($session=='29'){
	$doctypename	=	'Others';
} 

 $doc_type = $session;	

if($_REQUEST['search_name'] != ''){

	$statuscase .= " AND `mdoc_no` ='".addslashes($_REQUEST['search_name'])."'";

}
if($_REQUEST['id_steward'] != ''){
	$statuscase .= " AND id_attribute_steward ='".$_REQUEST['id_steward']."'" ;
}
if($_REQUEST['id_shift'] != ''){
	$statuscase .= " AND id_attribute_shift='".$_REQUEST['id_shift']."'" ;
}
	if($_REQUEST['status'] == '4'){
		$statuscase .= " AND payment_status='cancelled'"; 
	}elseif($_REQUEST['status'] == '3'){
		$statuscase .= " AND payment_status='Settled'" ;
	}elseif($_REQUEST['status'] == '2'){
		$statuscase .= " AND payment_status='Partial'"; 
	}elseif($_REQUEST['status'] == '1'){
		$statuscase .= " AND payment_status='Pending'"; 
	}elseif($_REQUEST['status'] == '0'){
		$statuscase .= ""; 
	}else{
		  if($_REQUEST['status'] == ''  && $_REQUEST['searchFormSubmit']==1){
                  $statuscase = " " ;
          // $selected3 = 'selected="selected"';
                }else{
           $statuscase = " AND payment_status='Pending'" ;
                  //$selected2 = 'selected="selected"';
                }
			}

			if($_REQUEST['datefilter'] != ''){
		$DateExplode = explode(' to ',$_REQUEST['datefilter']);
		$startDate = date('Y-m-d',strtotime($DateExplode['0']));
		$endDate	=	date('Y-m-d',strtotime($DateExplode['1']));
		//$endDate = date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
			
		$statuscase .= " AND DATE(`doc_date`) BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
	}
	 else{
      $statuscase .= " AND DATE(`doc_date`) BETWEEN '".date('Y-m-d',strtotime('-1 days'))."' And '".date('Y-m-d')."'";
  }
			
if($_REQUEST['type'] == '0'){
	$doc_type1 = '25,27' ;
	//if($doc_type1=='27'){$doctypename	=	'Laundry (nc)';}
	//$doctypename	=	'Laundry (nc)';
}else if(($_REQUEST['type'] == '1' || $_REQUEST['type'] == '') && $doc_type!='26' && $doc_type!='29'){
	$doc_type1 = '25' ;
}else if($_REQUEST['type'] == '2'){
	$doc_type1 = '27' ;
	//$doctypename	=	'Laundry (nc)';
}else if($doc_type == '26')	{
	$doc_type1 = '26' ;
}else if($doc_type == '29')	{
	$doc_type1 = '29' ;
}	
			
	
/*old with shop
 $SQL="SELECT *  from
( select pp.*, 
	   (case  when COALESCE(ppp.qty-ppp.bal_qty)>0 then 'Pending'
	        when COALESCE(ppp.qty-ppp.bal_qty)=0 then 'Billed' end) as kot_status
 
 from inv_purch pp left join inv_purch_details ppp on ppp.`id_inv_items`=pp.id where  pp.doc_type='".$doc_type."' $searchDocumentType group by pp.id AND id_shop= '".$_SESSION['shop']."'  ORDER BY pp.`date_created` desc
 
 )as managekotlist WHERE id!=0 ".$statuscase." 
";
*/

 /* $SQL="SELECT *  from
( select pp.*, 
	   (case  when COALESCE(ppp.qty-ppp.bal_qty)>0 then 'Pending'
	        when COALESCE(ppp.qty-ppp.bal_qty)=0 then 'Billed' end) as kot_status
 
 from inv_purch pp left join inv_purch_details ppp on ppp.`id_inv_items`=pp.id where  pp.doc_type='".$doc_type."' $searchDocumentType group by pp.id ORDER BY pp.`date_created` desc
 
 )as managekotlist WHERE id!=0 ".$statuscase." 
"; */









/* pos table sql



$SQL = "SELECT *  from
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
ppp.id_purch=pp.id 
group by
pp.id ORDER BY doc_date desc
)as managekotlist WHERE id!=0 AND doc_type='".$doc_type."' ".$statuscase." 
";




*/




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
)as managekotlist WHERE id!=0 AND doc_type IN ($doc_type1) ".$statuscase." 
";


$db->query($sql);

$numRows= $db->num_rows();

$pagging = new pagingClass($sql,$setpage);

$db->query($pagging->getQuery());

$total = $db->num_rows();


        	 
$i=1;
  ?>
  

  
<style>
 .paymentmode{height:50px !important;min-height: 50px !important;margin-bottom: 0px !important;}
 .paymode-span{ height:50px !important;line-height: 40px !important;}
 #trbgcolor{background-color:#fff;}
 .paymentlable{margin-bottom: 0px !important;}
 
 .ranges{padding: 9px !important;}
 .daterangepicker .ranges li:hover {background-color:#08c !important;}
</style>
  
  
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
	
<?php
	$session = $_GET['session']; 
    $submenu = $_GET['submenu']; 
    

?>

	
    <section class="content-header">
    	    <div class="row">
         <div class="col-md-5 col-xs-8">
	      <h5 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
			<?php echo '<span style="color:'.currentNavigation_s($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_s($session)['icon'].'"></i> '.currentNavigation_s($session)['submenu'].'</span>'; ?>

	        <?php //echo currentNavigation()['submenu']; ?>
	      </h5>
	          </div>
       <div class="col-md-2 col-xs-4"> 
           <!-- <span style="font-weight:100;padding:3px 8px;text-decoration: underline;"><a class="text-o" href="manageKot.php?submenu=178&session=22">  List KOT </a>
            </span>-->
       </div> 

       <div class="col-md-5 col-xs-12 tb-br"> 
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
          <h3 class="box-title">Search <small>Records: (
            <?=$numRows;?>
            ) &nbsp;</small> </h3>
          <div class="btn-group  pull-right"> <a type="button" class="btn n-btn pull-right" href="editOutletBill.php?session=<?php echo $_GET['session']?>&submenu=<?php echo $_GET['submenu']?>" >Add <?php echo currentNavigation_s($session)['submenu']; ?> </a> </div>
        </div>
        
        <!-- /.box-header -->
        
         <form name="searchForm" action="" method="get">
          <input type="hidden" value="1" name="searchFormSubmit" />
          <input type="hidden" value="<?php echo $_GET['submenu'];?>" name="submenu">
          <div class="box-body">
            <div class="row">
             <div class="col-md-2 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>Document No</label>
                  <input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />
				  
                </div>
                <!-- /.form-group --> 
                
              </div>
              
              <!-- /.col -->
            <div class="col-md-2 col-sm-6 col-xs-6">
              <div class="form-group">
               <label>Period</label>	
					<div class="input-group"> 
					
						<!-- <input type="text" name="datefilter" id="datefilter" placeholder="Date" class="form-control"  value="" /> -->
						<input type="text" class="form-control pull-right" placeholder="Select From -  To" name="datefilter" id="dateRangeReport" data-parsley-required value="<?php if($_REQUEST['datefilter']!=''){echo $_REQUEST['datefilter'];}else{  echo Date('d-m-Y',strtotime('-1 days')).' to '.date('d-m-Y'); }?>"   autocomplete="off">
					</div>
              </div>
              <!-- /.form-group -->
 
              
            </div>
             
             
        <div class="col-md-2 col-sm-6 col-xs-6">
        <div class="form-group">
      <label for="id_shop">Steward </label>
      <select class="form-control select2" name="id_steward" id="id_steward" style="width: 100%">
        <?php $shopDropDown = '<option value="">All </option>';
					  $resUserShop = selectSql(TBL_ATTRIBUTES," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and `table_name` = 'steward'  and  `status` = '1'",' ORDER BY `field_value`');
											  if($db->num_rows2($resUserShop)){
											  	while($resultUserShop = $db->fetch_object2($resUserShop)){
													if($_REQUEST['id_steward'] == $resultUserShop->id){
														$selected = 'selected="selected"';
													
													}else{
														$selected = '';
													}
													$shopDropDown .= '<option '.$selected.' value="'.$resultUserShop->id.'">'.ucfirst($resultUserShop->field_value).'</option>';
												}
											  }
											 	echo $shopDropDown .= '</select>';
											  ?>
											  
				  
       
        </div>
        </div>
        
              <div class="col-md-2 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>Status</label>
                  <?php 

					if($_REQUEST['status'] == '1'){

							$selected1 = 'selected="selected"';

					}elseif($_REQUEST['status'] == '2'){

							$selected2 = 'selected="selected"';

					}
					elseif($_REQUEST['status'] == '3'){

							$selected3 = 'selected="selected"';

					}elseif($_REQUEST['status'] == '4'){

							$selected4 = 'selected="selected"';

					}elseif($_REQUEST['status'] == '0'){

							$selected0 = 'selected="selected"';

					}else{
//$selected12 = '';
						  if($_REQUEST['status'] == ''  && $_REQUEST['searchFormSubmit']==1){
		                  $selected0 = 'selected="selected"';
		                }else{
		                  $selected1 = 'selected="selected"';
		                }

					}

				  echo $statusDropDown = '<select class="form-control select2" name="status" style="width: 100%"> 
				
				   
				  <option '.$selected0.' value="0">All</option>

				  <option '.$selected1.' value="1">Pending</option>

				  <option '.$selected2.' value="2">Partially</option>

				  <option '.$selected3.' value="3">Settled</option>
				  
				  <option '.$selected4.' value="4">Cancelled</option>

				  </select>';?>
				  
				  <input name="session" type="hidden" class="btn btn-primary" value="<?php echo $_GET['session'] ?>" />
				 
                </div>
                
                <!-- /.form-group --> 
                
              </div>
			  
			<?php if($doc_type!='26' && $doc_type!='29'){  ?>
			   <div class="col-md-4">
                <div class="form-group">
                  <label>Document Type</label>
                  <?php 
					if($_REQUEST['type'] == '0'){
						$selected1 = 'selected="selected"';
					}else if(($_REQUEST['type'] == '1' || $_REQUEST['type'] == '') && $doc_type!='26' && $doc_type!='29'){
						$selected2 = 'selected="selected"';
					}
					else if($_REQUEST['type'] == '2'){
						$selected3 = 'selected="selected"';
					}else{
						$selected4 = '';
					}

				  echo $statusDropDown = '<select class="form-control select2" name="type" style="width: 100%"> 
					  <option value="">Select Document type</option>
					  <option '.$selected1.' value="0">Both</option>
					  <option '.$selected2.' value="1">Laundry</option>
					  <option '.$selected3.' value="2">Laundry (nc)</option>
					  </select>';?>
				</div>
			</div>
			<?php } ?>      
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
            <div class="box-header table-h text-center">
             <!-- <h3 class="box-title">List Of <?php echo currentNavigation()['submenu']; ?> </h3>-->
              <small class="text-center has-error">
              <?php if($_SESSION['errorMsg']){?>
              <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
              <?php unset($_SESSION['errorMsg']);} elseif($_SESSION['successMsg']){?>
              <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
              <?php unset($_SESSION['successMsg']);}?>
              </small>  </div>
            <div name="listingForm">
              <input type="hidden" value="" name="act" />
              <div id="listingDiv"></div>
              <!-- /.box-header -->
              <div class="box-body table-responsive">
                <table id="myTable" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" >
                  <thead>
                    <tr>
                      <th width="1%"> S.No.&nbsp;</th>
                      <th>Document Type</th>
                      <th>Document No</th>  
                      <th>Date</th>
                      <th>Steward</th>
                      <th>Amount</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php  
                     		 					

					$i=1;
					 

						if($numRows > 0){$counter = 1;
						  $pos_purch_id_array=array();
						 while($row = $row = $db->fetch_object()){ 

							$pos_purch_id_array[]= $row->id;
							$mdoc_no			 = $row->mdoc_no;
							$grand_total_amount	 = $row->grant_total_amount;
						 $purch_id			 = $row->id;	
						 $we			 = $row->payment_status;	
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
						$id_fo_bill[$purch_id][$id_type]	=	$ResultBlockedtable1->id_fo_bill;	
									$edit_doc_date  					 =	$ResultBlockedtable1->doc_date!=''?$ResultBlockedtable1->doc_date:'';	
						$TotalPaidAmount 	   +=	$ResultBlockedtable1->amount;	
						
						$id_onlinetransfertype[$purch_id][$id_type]	  =   $ResultBlockedtable1->id_onlinetransfertype;
						if($ResultBlockedtable1->payment_mode=='CARD' || $ResultBlockedtable1->payment_mode=='ONLINETRANSFER'   ){
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
					?> 
		        	 
		        	 
				<?php	  
					$table_name=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'table' AND id= '".$row->id_attribute_table."'"); 
					$shift_name=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'shift' AND id= '".$row->id_attribute_shift."'"); 
					$steward_name=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'steward' AND id= '".$row->id_attribute_steward."'"); 
					
				/*	$Sqlsettled = mysqli_query($connNew,"SELECT * FROM pos_purch_details WHERE qty-adj_qty>0 AND id_pos_purch  IN  (SELECT id FROM pos_purch WHERE pos_bill_type= '2' AND id_pos_purch= '".$row->id."')");
					$countSettled = mysqli_num_rows($Sqlsettled);
					$rowSettled = mysqli_fetch_object($Sqlsettled);
					if($countSettled>0){
					$status='<a class="showSingle" ><span class="label label-danger">PENDING</span></a>';
					$stausicon='view_edit.gif';
					$Imgtitle='View / Edit ';
					$statusValue='editKotid';
					}else{
					$status=' <a class="showSingle"><span class="label label-success">BILLED</span></a>';
					$stausicon='view.gif';
					$Imgtitle='View ';
					$statusValue='editKotviewid';
					} $row->doc_type */
					
					
					
if($row->doc_type=='25'){
	$doctypename	=	'Laundry';
}
if($row->doc_type=='26'){
	$doctypename	=	'Spa and Health Club';
}
if($row->doc_type=='27'){
	$doctypename	=	'Laundry (nc)';
}
if($row->doc_type=='29'){
	$doctypename	=	'Others';
} 
					
						  ?>
                    <tr>
                       <td><?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>
                      <td><?php echo $doctypename; ?></td>
                   <td><?php echo $row->mdoc_no;//.'=='.$row->id;?></td> 
                      <td><?=date('d-m-Y',strtotime($row->doc_date));?></td>
                   <!--   <td><?php echo ucfirst($shift_name);?></td>
                       <td><?php echo $table_name;?></td>  -->
                      <td><?php echo ucfirst($steward_name);?></td>
                      <td><?php echo ucfirst($row->grant_total_amount);?></td>
                    <!--  <td><?php echo $row->pax;?></td>  -->                    
                      <th><?php
					
							
							if($row->payment_status=='Settled'){
								?>
                        <a class="showSingle" target="<?php echo $purch_id;?>" style="cursor:pointer;"><span class="label label-success">SETTLED</span></a>
                        <?php }
						if($row->payment_status=='Partial'){?>
                        <a class="showSingle" target="<?php echo $purch_id;?>" style="cursor:pointer;"><span class="label label-warning">PARTIALLY</span></a>
                        <?php	}
					   if($row->payment_status=='Pending'){
						   if($amount[$purch_id][1]==0){
							  $amount[$purch_id][1] =round($balance_amount,2);
							  $TotalPaidAmount	= $grand_total_amount;
							  $balance_amount=0;
						   }
						   ?>
                        <a class="showSingle" target="<?php echo $purch_id;?>" style="cursor:pointer;"><span class="label label-info">PENDING</span></a>
                        <?php }
					  
					  if($row->payment_status=='cancelled'){?>
                        <a  target="<?php echo $purch_id;?>" style="cursor:pointer;"><span class="label label-info">CANCELLED</span></a>
                        <?php }
					  
					  ?></th> 
                    <!--  <td>
					  <a href="editKot.php?<?php echo $statusValue;?>=<?=encryptor(encrypt, $row->id);?>" >
                      <img src="../images/<?php echo $stausicon;?>" style="cursor:pointer;" title="<?php echo $Imgtitle; ?> "/></a>&nbsp;&nbsp;&nbsp;&nbsp;
                      &nbsp;&nbsp; <a href="printKotPreview.php?printPreviewid=<?=encryptor(encrypt, $row->id);?>" target="_blank" ><img src="../images/print.png" style="cursor:pointer;" title=" Print "  /></a> </td> -->
					  
					  <td style="width:130px;" >
                       <?php if($row->payment_status!='cancelled'){?>
                       <a href="editOutletBill.php?updateid=<?=encryptor(encrypt, $row->id);?>&session=<?php echo $_GET['session']?>&submenu=<?php echo $_GET['submenu']?>"  ><img src="../images/edit.png" style="cursor:pointer;height:20px;" title=" View / Edit "  /></a> 
                      <?php } ?>
                      &nbsp;&nbsp; <a href="printPreviewlaundryspaothers.php?printPreviewid=<?=encryptor(encrypt, $row->id);?>&session=<?php echo $_GET['session'] ?>&submenu=<?php echo $_GET['submenu'] ?>" ><img src="../images/preview.png" style="cursor:pointer;height:20px;" title="Page Preview "  /></a>
					  
                       <?php if($row->payment_status!='cancelled'){?>
                       &nbsp;&nbsp;<!--<a href="billPayment.php?id=<?=encryptor(encrypt, $row->id);?>"><img src="../images/bill3.png" style="cursor:pointer;" title=" Bill Payment "  /></a>--> 
                      <a class="showSingle" target="<?php echo $purch_id;?>" style="cursor:pointer;"><img src="../images/bill-payment.png" style="cursor:pointer;height:20px;" title=" Bill Payment " /></a>
                      &nbsp;&nbsp;<a class="showSingle" onClick="ajaxcancel(<?php echo $purch_id;?>);" style="cursor:pointer;"><img src="../images/cash-register.png" style="cursor:pointer;height:20px;" title="Cancel Bill"   /></a>
                 
                  <?php } ?>
                  </td>  
					  
                    </tr>
					
					
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
					$id_charges_master  =   $Resultcardpayment->id_charges_master;					
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
                                      <div class="info-box-content" style="width: 83%;height: 28px;"> <span class="info-box-text" style="width:81%;float:left;">TRANSFER </span>
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
                            <img class="flagimgs first" src="<?php echo $SITE_URL; ?>/images/upi.png" /> </div>
                        </div>
                        <div class="box-body" style="width: 16%;float: left;padding: 0px !important; height: 60px;">
                          <div class="form-group" style="margin-bottom: 0px;">
                            <div style="margin-left: 15px;">
                              <label for="name" class="paymentlable">
                                <input type="radio" class="flat-red" <?php if($id_cardtype == '3'){echo "checked";} ?> value="3" name="cardtype[<?php echo $purch_id;?>][CARDTYPE][][<?php echo $gridNo;?>]" id="cardtype"/>
                              </label>
                            </div>
                            <img src="<?php echo $SITE_URL; ?>/images/neft.png" style="cursor:pointer;" title="upi"  /> </div>
                        </div>
                        
                         
                        
                       </span> </div> </div></td>
                                                 
                                               
                                                    
                                                      <td style="width: 12.5%;"><input type="text" <?php  if($CardAmount>0){ $CardAmount;}else {echo 'disabled="disabled"';} ?> class="form-control first-input billingamount_<?php echo $purch_id;?>" name="payamount[<?php echo $purch_id;?>][CARD][]" id="payamount_2_<?php echo $purch_id;?>" onKeyUp="getpayamount(this.value,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>,2);"  value="<?php echo $CardAmount?$CardAmount:0; ?>" style="float: left;" data-parsley-required data-parsley-errors-container="#payamountError"/></td>
                                                      <td style="width: 35.5%;"><div class="form-group" style="width:100% !important;margin-bottom: 5px!important;">
                                                        <select class="form-control first-input select2" style="width:100% !important;" name="id_bank[<?php echo $purch_id;?>][BANK][]" id="id_bank_2_<?php echo $purch_id;?>">
                                                          <option value="0">--- Select Bank --- </option>
                                                          <!--select bank-->
                                                          <?php  $resCat = selectSql(TBL_CHARGES," where status='1' and charges_account='8' and id_shop='".addslashes($_SESSION['shop'])."' and name !=' ' ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($id_charges_master == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													echo $categoryDropDown = '<option '.$selected.'  value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }?>
                                                        </select>  
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
                            <img src="../images/upi.png" style="cursor:pointer;" title="upi"  /> </div>
                        </div>
                        <div class="box-body" style="width: 16%;float: left;padding: 0px !important; height: 60px;">
                          <div class="form-group" style="margin-bottom: 0px;">
                            <div style="margin-left: 15px;">
                              <label for="name" class="paymentlable">
                                <input type="radio" class="flat-red" <?php if($id_cardtype == '3'){echo "checked";} ?> value="3" name="cardtype[<?php echo $purch_id;?>][CARDTYPE][][<?php echo $gridNo;?>]" id="cardtype"/>
                              </label>
                            </div>
                            <img src="../images/neft.png" style="cursor:pointer;" title="upi"  /> </div>
                        </div>
                        
                        
                        
                        
                        
                       </span> </div> </div></td>
                                                 
                                               
                                                    
                                                      <td style="width: 12.5%;"><input type="text" <?php  if($amount[$purch_id][2]>0){ $amount[$purch_id][2];}else {echo 'disabled="disabled"';} ?> class="form-control first-input billingamount_<?php echo $purch_id;?>" name="payamount[<?php echo $purch_id;?>][CARD][]" id="payamount_2_<?php echo $purch_id;?>" onKeyUp="getpayamount(this.value,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>,2);"  value="<?php echo $CardAmount?$CardAmount:0; ?>" style="float: left;" data-parsley-required data-parsley-errors-container="#payamountError"/></td>
                                                      <td style="width: 35.5%;"><div class="form-group" style="width:100% !important;margin-bottom: 5px!important;">
                                                          
                                                          
                                                          <div class="form-group" style="width:100% !important;margin-bottom: 5px!important;">
                                                        <select class="form-control first-input select2" style="width:100% !important;" name="id_bank[<?php echo $purch_id;?>][BANK][]" id="id_bank_2_<?php echo $purch_id;?>"   <?php  if($amount[$purch_id][2]>0){ $amount[$purch_id][2];}else {echo 'disabled="disabled"';} ?>>
                                                          <option value="0">--- Select Bank --- </option>
                                                          <!--select bank-->
                                                          <?php  $resCat = selectSql(TBL_CHARGES," where status='1' and charges_account='8' and id_shop='".addslashes($_SESSION['shop'])."' and name !=' ' ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($id_charges_master == $resultCat->id){
														$selected = '';
													}else{
														$selected = '';
													}
													echo $categoryDropDown = '<option '.$selected.'  value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }?>
                                                        </select>  
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
                                                      <div class="info-box-content"> <span class="info-box-text">GIFT VOUCHER</span> </div>
                                                      <!-- /.info-box-content --> 
                                                    </div></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][6]>0){ $amount[$purch_id][6];}else {echo 'disabled="disabled"';} ?> class="form-control first-input billingamount_<?php echo $purch_id;?>" name="payamount[<?php echo $purch_id;?>][GIFTVOUCHER][]" id="payamount_6_<?php echo $purch_id;?>" onKeyUp="getpayamount(this.value,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>,6);"  value="<?php echo $amount[$purch_id][6]?$amount[$purch_id][6]:0; ?>" style="float: left;" data-parsley-required data-parsley-errors-container="#payamountError"/></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][6]>0){ $amount[$purch_id][6];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" placeholder="Remarks" name="remarks[<?php echo $purch_id;?>][GIFTVOUCHER][]" id="remarks_6_<?php echo $purch_id;?>" value="<?php echo $remarks[$purch_id][6]; ?>" style="float: left;"/></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][6]>0){ $amount[$purch_id][6];}else {echo 'disabled="disabled"';} ?>  class="form-control first-input" name="tips[<?php echo $purch_id;?>][GIFTVOUCHER][]" id="tips_6_<?php echo $purch_id;?>" value="<?php echo $tips[$purch_id][6]; ?>" style="float: left;"/></td>
                                                </tr>
                                                
                        
                        
                        <!--Room TO Settle Start------------------>
                        <tr id="trbgcolor">
                                                  <td style="width: 2.5%;"> 
                                                   <input type="checkbox" <?php  if($amount[$purch_id][7]>0){ echo 'checked';} ?> class="flat-red i-checks checkboxpayamount_7_<?php echo $purch_id;?>"   name="checkboxpayamount" id="checkboxpayamount" value="<?php echo $amount[$purch_id][7].'_7_'.$grand_total_amount.'_'.$purch_id; ?>"  />
                                                   </td>
                                                   <td> <div class="info-box" style="height:80px !important;min-height: 80px !important;margin-bottom: 0px !important;"> <span class="info-box-icon bg-aqua" style="height:80px !important;line-height: 70px !important;"> <img src="../images/company.png" style="cursor:pointer;" title=" Bill Payment "  /> </span>
                                                      <div class="info-box-content"> <span class="info-box-text">ROOM TO </span> </div>
                                                      <!-- /.info-box-content --> 
                                                    </div></td>
                                                  <td><input type="text"   <?php  if($amount[$purch_id][7]>0){ $amount[$purch_id][7];}else {echo 'disabled="disabled"';} ?> class="form-control first-input billingamount_<?php echo $purch_id;?>" name="payamount[<?php echo $purch_id;?>][ROOMTO][]" id="payamount_7_<?php echo $purch_id;?>" onKeyUp="getpayamount(this.value,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>,7);"  value="<?php echo  $amount[$purch_id][7]?$amount[$purch_id][7]:0;  ?>" style="float: left;" data-parsley-required data-parsley-errors-container="#payamountError"/></td>
                                                  <td><div class="form-group" style="width:100% !important; margin-bottom:5px !important;">
                                                      <div class="input-group"  style="width:100% !important;">
                                                        <select class="form-control first-input select2" style="width:100% !important;" name="id_fo_bill[<?php echo $purch_id;?>][ROOMTO][]" id="id_fo_bill_7_<?php echo $purch_id;?>" <?php  if($amount[$purch_id][7]>0){ $amount[$purch_id][7];}else {echo 'disabled="disabled"';} ?>>
                                                          <option value="0">Select Room </option>
     <?php /*?>  <?php  $resCat = mysqli_query($connNew,"SELECT * FROM `fo_folio` WHERE  folio_status='0' and status='1' ");
															   
		
	if(mysqli_num_rows($resCat)){
	while($resultCat = mysqli_fetch_object($resCat)){
	$guestName	=	selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$resultCat->id_mst_guest."'");
		
	//$id_fo_bill	=  selectColumn(FO_BILL,'id'," WHERE `id_fo_folio_to` = '".$resultCat->id."'");
	
	$id_mst_room_no_allocation	=	selectColumn(FO_RESERVATIONS_DETAILS,'id_mst_room_no_allocation'," WHERE `id_fo_bill` = '".$resultCat->id_fo_bill."'");
	$roomNumber= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$id_mst_room_no_allocation."'");
							
if($id_fo_bill[$purch_id][7] == $resultCat->id_fo_bill){
	$selected = 'selected="selected"';
}else{
	$selected = '';
}
echo $categoryDropDown = '<option '.$selected.'  value="'.$resultCat->id_fo_bill.'">
'.$resultCat->mdoc_no.'---    Room No:'.$roomNumber.' ---  Guest: '.$guestName.'</option>';
												//}
											  }
											  }?><?php */?> 
                                              
                                               <?php  if($RowStatus=='Settled' || $RowStatus=='Partial'){ 
												  
												  
// Fetch all active folios
							  
							  
					//echo "SELECT * FROM `fo_folio` WHERE id = ".$row->id_fo_folio_to." AND status = '1'";		  
							  
$resCat = mysqli_query($connNew, "SELECT * FROM `fo_folio` WHERE id = ".$row->id_fo_folio_to." AND status = '1'");

if (mysqli_num_rows($resCat)) {
    while ($resultCat = mysqli_fetch_object($resCat)) {
        // Get guest name
        $guestName = selectColumn(TBL_GUEST, 'first_name', " WHERE `id` = '" . $resultCat->id_mst_guest . "'");

       
		if($amount[$purch_id][7]>0){ 
		    //$guestName = selectColumn(TBL_GUEST, 'first_name', " WHERE `id` = '" . $resultCat->id_mst_guest . "'");
			$id_mst_room_no_allocationRoom=selectColumn(TBL_ATTRIBUTES,'id_mst_room_no'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' AND id= '".$row->id_attribute_table."'");	
		}else{
			$id_mst_room_no_allocationRoom='';
			}
		$resCatResRoom = mysqli_query($connNew, "SELECT id_mst_room_no_allocation FROM ".FO_RESERVATIONS_DETAILS." where `id_fo_bill` = '" . $resultCat->id_fo_bill . "' group by id_mst_room_no_allocation");

if (mysqli_num_rows($resCatResRoom)) {
    while ($resultResRoom = mysqli_fetch_object($resCatResRoom)) {
		$id_mst_room_no_allocation	= $resultResRoom->id_mst_room_no_allocation;

        // Get actual room number
        $roomNumber = selectColumn(
            TBL_ROOMNO,
            'room_no',
            " WHERE `id` = '" . $id_mst_room_no_allocation . "'"
        );

        // Check if this option should be selected
		if($id_fo_bill[$purch_id][7] == $resultCat->id_fo_bill && $amount[$purch_id][7]=='0'){
			
			$selected	='selected="selected"';
		}elseif($id_fo_bill[$purch_id][7] == $resultCat->id_fo_bill && $amount[$purch_id][7]>'0' && $id_mst_room_no_allocation	==$id_mst_room_no_allocationRoom){
			$selected	='selected="selected"';
			}else{
				$selected	='';
				}
		
        //$selected = ($id_fo_bill[$purch_id][7] == $resultCat->id_fo_bill) ? 'selected="selected"' : '';

        // Output the dropdown option
        echo '<option ' . $selected . ' value="' . $resultCat->id_fo_bill . '">'
            . $resultCat->mdoc_no . ' --- Room No: ' . $roomNumber . ' --- Guest: ' . $guestName
            . '</option>';
    }
}
	}
}
 
											  }else{
								?>
                                     
                        <?php
// Fetch all active folios
							  
							  
							  
							  
$resCat = mysqli_query($connNew, "SELECT * FROM `fo_folio` WHERE folio_status = '0' AND status = '1'");

if (mysqli_num_rows($resCat)) {
    while ($resultCat = mysqli_fetch_object($resCat)) {
        // Get guest name
        $guestName = selectColumn(TBL_GUEST, 'first_name', " WHERE `id` = '" . $resultCat->id_mst_guest . "'");

       
		if($amount[$purch_id][7]>0){ 
		    //$guestName = selectColumn(TBL_GUEST, 'first_name', " WHERE `id` = '" . $resultCat->id_mst_guest . "'");
			$id_mst_room_no_allocationRoom=selectColumn(TBL_ATTRIBUTES,'id_mst_room_no'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' AND id= '".$row->id_attribute_table."'");	
		}else{
			$id_mst_room_no_allocationRoom='';
			}
		$resCatResRoom = mysqli_query($connNew, "SELECT id_mst_room_no_allocation FROM ".FO_RESERVATIONS_DETAILS." where `id_fo_bill` = '" . $resultCat->id_fo_bill . "' group by id_mst_room_no_allocation");

if (mysqli_num_rows($resCatResRoom)) {
    while ($resultResRoom = mysqli_fetch_object($resCatResRoom)) {
		$id_mst_room_no_allocation	= $resultResRoom->id_mst_room_no_allocation;

        // Get actual room number
        $roomNumber = selectColumn(
            TBL_ROOMNO,
            'room_no',
            " WHERE `id` = '" . $id_mst_room_no_allocation . "'"
        );

        // Check if this option should be selected
		if($id_fo_bill[$purch_id][7] == $resultCat->id_fo_bill && $amount[$purch_id][7]=='0'){
			
			$selected	='selected="selected"';
		}elseif($id_fo_bill[$purch_id][7] == $resultCat->id_fo_bill && $amount[$purch_id][7]>'0' && $id_mst_room_no_allocation	==$id_mst_room_no_allocationRoom){
			$selected	='selected="selected"';
			}else{
				$selected	='';
				}
		
        //$selected = ($id_fo_bill[$purch_id][7] == $resultCat->id_fo_bill) ? 'selected="selected"' : '';

        // Output the dropdown option
        echo '<option ' . $selected . ' value="' . $resultCat->id_fo_bill . '">'
            . $resultCat->mdoc_no . ' --- Room No: ' . $roomNumber . ' --- Guest: ' . $guestName
            . '</option>';
    }
}
	}
}
?>
															
			 <?php }	?>		 
                                                        </select>
                                                      </div>
                                                    </div>
                                                    <input type="text" <?php  if($amount[$purch_id][7]>0){ $amount[$purch_id][7];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" placeholder="Remarks" name="remarks[<?php echo $purch_id;?>][ROOMTO][]" id="remarks_7_<?php echo $purch_id;?>" value="<?php echo $remarks[$purch_id][7]; ?>" style="float: left;"/></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][7]>0){ $amount[$purch_id][7];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" name="tips[<?php echo $purch_id;?>][ROOMTO][]" id="tips_7_<?php echo $purch_id;?>" value="<?php echo $tips[$purch_id][7]; ?>" style="float: left;"/></td>
                                                </tr>
                          
                                                
                                                
                        
                         <!--Room TO Settle END------------------> 
                                                
                          <!-----------------------BILL ON HOLD START----------------- ------>
                          
                          
                          
                       <tr id="trbgcolor">
                                                  <td style="width: 2.5%;"> 
                                                   <input type="checkbox" <?php  if($amount[$purch_id][8]>0){ echo 'checked';} ?> class="flat-red i-checks checkboxpayamount_8_<?php echo $purch_id;?>"   name="checkboxpayamount" id="checkboxpayamount" value="<?php echo $amount[$purch_id][8].'_8_'.$grand_total_amount.'_'.$purch_id; ?>"  />
                                                   </td>
                                                   <td> <div class="info-box" style="height:80px !important;min-height: 80px !important;margin-bottom: 0px !important;"> <span class="info-box-icon bg-aqua" style="height:80px !important;line-height: 70px !important;"> <img src="../images/hold.png" style="cursor:pointer;" title=" Bill Payment "  /> </span>
                                                      <div class="info-box-content"> <span class="info-box-text">BIll ON HOLD </span> </div>
                                                      <!-- /.info-box-content --> 
                                                    </div></td>
                                                  <td><input type="text"   <?php  if($amount[$purch_id][8]>0){ $amount[$purch_id][8];}else {echo 'disabled="disabled"';} ?> class="form-control first-input billingamount_<?php echo $purch_id;?>" name="payamount[<?php echo $purch_id;?>][BIllONHOLD][]" id="payamount_8_<?php echo $purch_id;?>" onKeyUp="getpayamount(this.value,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>,8);"  value="<?php echo  $amount[$purch_id][8]?$amount[$purch_id][8]:0;  ?>" style="float: left;" data-parsley-required data-parsley-errors-container="#payamountError"/></td>
                                                  <td><div class="form-group" style="width:100% !important; margin-bottom:5px !important;">
                                                      <div class="input-group"  style="width:100% !important;">
                                                       
                                                      </div>
                                                    </div>
                                                    <input type="text" <?php  if($amount[$purch_id][8]>0){ $amount[$purch_id][8];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" placeholder="Remarks" name="remarks[<?php echo $purch_id;?>][BIllONHOLD][]" id="remarks_8_<?php echo $purch_id;?>" value="<?php echo $remarks[$purch_id][8]; ?>" style="float: left;"/></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][8]>0){ $amount[$purch_id][8];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" name="tips[<?php echo $purch_id;?>][BIllONHOLD][]" id="tips_8_<?php echo $purch_id;?>" value="<?php echo $tips[$purch_id][8]; ?>" style="float: left;"/></td>
                                                </tr>     
                          
                          
                          
                           
                           <!-----------------------BILL ON HOLD END----------------- ------>                        
                                                
                                                
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
                                            <input   type="text" class="form-control pickerdateretwodays"  placeholder="sreEnter PO Date" id="po_date1" name="po_date1" value="<?php echo $edit_doc_date!=''?date('d-m-Y',strtotime($edit_doc_date)):date('d-m-Y');?>" >
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
                    
					  <?php } ?><tr>	 
					  <td align="right" colspan="9"><?php  echo $pagging->getLinks();?> </td>
                 </tr>               
				<?php }else {?>
				
				 <tr>
                      <td height="200" align="center" colspan="8">---- No Record Found ---- </td>
                 </tr>                 
				<?php }?>
						
                  </tbody>
                </table>
				 </div>
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
  
  
  
   <div id="bookedby" class="well" style="max-width:44em; display:none;"> 
  <form id="Formkotremarks" autocomplete="off">
  <input type="hidden" id="pos_purch_id" name="pos_purch_id" value="">
  	<div class="form-group">
      <label for="title">Bill Cancel Remarks</label>
      
      <textarea rows="4" cols="50" type="text" class="form-control input-sm" placeholder="Enter Remark" id="remark" name="remark" value="" data-parsley-required></textarea>
    </div>
	
	
	<div class="form-group">
		 <label for="btn">&nbsp;<br><br></label>
		<button class="btn btn-primary" onclick="ajaxCancelPOS();" type="button">Save</button>
		<button class="bookedby_close btn btn-default">Close</button>
	</div>
  </form>
</div>    
    
<div id="ratePoint" class="well" style="display:none;">
	<form id="ratePointForm" autocomplete="off">
    <div ></div>
    <p class="help-block" id="updatestatussuccess"></p>
	</form>
	<button class="ratePoint_close btn btn-default pull-right" >Close</button>
</div>


<div id="ratePointfaild" class="well" style="display:none;">
	<form id="ratePointfaildForm" autocomplete="off">
    <div ></div>
    <p class="help-block" id="updatestatus"></p>
	</form>
	<button class="ratePointfaild_close btn btn-default pull-right" >Close</button>
</div>
 
   <script>
	<?php if($_GET['submenu'] == '' || $_GET['session'] =='' ){ ?>
	$(document).ready(function (){  
		location.href = "manageOutletBill.php?submenu=214&session=25&reload=1";
	});
	<?php } ?>
 </script>
  
  
  <script type="text/javascript">

  	function deleteMe(id,name){

  		var xhttp = new XMLHttpRequest();

  		  xhttp.onreadystatechange = function() {

  		    if (this.readyState == 4 && this.status == 200) {

  		    	console.log(this.responseText);

  		      if(this.responseText == 1){

  		      	alert("Transaction Found In the Table");

  		      }

  		      else{

  		      	if(confirm('Are you sure that you want to delete this record '+name+'?')){

  		      		window.location.href='manageHotels.php?delId='+id+'&action=delete&page=<?=$_REQUEST['page']?>';

  		      	}

  		      }

  		    }

  		  };

  		  xhttp.open("GET", "ajax/ajaxCheckCompanyDomain.php?id_hotel="+id, true);

  		  xhttp.send();

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
			url: 'ajax/ajaxCancelLaundryspaothers.php',
			data: purch, 
			success: function (result) {
				
			        console.log(result);
			        data = JSON.parse(result);
			
					alert('POS Bill Cancel successfully. ');
				window.location.href = "manageOutletBill.php?session=<?php echo $_GET['session'] ?>&submenu=<?php echo $_GET['submenu'] ?> ";
					
      	}

		});

	}
}	
	
	
	
	function printFuntion(id){
		//printFuntion
		
//alert(id);
	$.ajax({

		type: "POST",

		url: 'include/function1.php',

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
		$("#id_fo_bill_"+type+"_"+get_purch_id).removeAttr('disabled');
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
	   $("#id_fo_bill_"+type+"_"+get_purch_id).attr('disabled','disabled');
	   
	   $('#tips_'+type+'_'+get_purch_id).val('');
	   $('#remarks_'+type+'_'+get_purch_id).val('');
	   $('#id_company_'+type+'_'+get_purch_id).val('0');
	   $('#id_fo_bill_'+type+'_'+get_purch_id).val('0');
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
	var selectedRoomFolioId = $("#id_fo_bill_7_" + get_purch_id).val(); // Gets selected value (folio bill ID)

var selectedRoomText = $("#id_fo_bill_7_" + get_purch_id + " option:selected").text(); // Gets the full text
// Optional: extract just the Room No from the text
var roomNumber = selectedRoomText.split('---')[1]?.trim().replace('Room No:', '').split('---')[0]?.trim();			
   var form1=$("#listingForm_"+get_purch_id);	
  // alert(form1);
  
   var dataString = $("#listingForm_"+get_purch_id).serialize()+'&savetype='+savetype+ '&room_no=' + encodeURIComponent(roomNumber);
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
			   
			   
			  
					
					console.log(result);
			        data = JSON.parse(result);
					
					
					
					
			   if(data.status ==1  || data.status ==2 ){
				  $("#updatestatussuccess").html(data.msg);
				//$( "#my_popup_open" ).click(); 
				$('#ratePoint').popup({
        			transition: 'all 0.3s',
           			 autoopen: true,            
        			});				 
				  $('.targetDiv').not('#div' + $(this).attr('target')).hide();
				  window.location.href = "manageOutletBill.php?session=<?php echo $_GET['session']?>&submenu=<?php echo $_GET['submenu']?>";	
				  }else{
					  
					  $("#updatestatus").html(data.msg);
					  //$( ".my_popupfaild_open" ).click(); 
					  $('#ratePointfaild').popup({
        						transition: 'all 0.3s',
           						 autoopen: true,            
        				});
						//window.location.href = "manageOutletBilling.php";
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


//var gridNo=2;
function addNewGrid(get_purch_id,grand_total_amount){
var gridNo =  Math.floor((Math.random() * 1000)); 

        var grid ='<div id="grid'+gridNo+'_'+get_purch_id+'" > <table id="myTableOrder1" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" style="font-size:14px;padding: 0px 0px;border:none;" ><tbody><tr style="background-color:#fff !important;"><td style="border-right: none;border-top: none;width: 3.4% !important;"></td><td style="border-right: none;border-top: none;width: 97% !important;float: left;"> <div class="info-box" style="height:90px !important;min-height: 90x !important;margin-bottom: 0px !important;" > <span class="info-box-icon bg-aqua" style="height:90px !important;line-height: 90px !important;"> <img src="../images/credit_cards_card-512.png" style="cursor:pointer;" title=" Bill Payment "/> </span> <div class="info-box-content" style="width: 74%;height: 28px;"> <span class="info-box-text" style="width:20%;float:left;">CARD </span>  </div><div class="info-box" style="height:60px !important;min-height: 60px !important;margin-bottom: 0px !important;" > <span class="info-box-number"> <div class="box-body" style="width: 16%;float: left;padding: 0px !important;height: 60px;margin-left: 16px;"> <div class="form-group"> <div style="margin-left: 15px;"> <label for="name" class="paymentlable"> <input type="radio" class="flat-red"  value="1" name="cardtype[<?php echo $purch_id;?>][CARDTYPE][][<?php echo $gridNo;?>]" id="cardtype"/> </label> </div><img class="flagimgs first" src="<?php echo $SITE_URL; ?>/plugins/dist/img/credit/visa.png" alt="Visa"> </div></div><div class="box-body" style="width: 16%;float: left;padding: 0px !important; height: 60px;"> <div class="form-group"> <div style="margin-left: 15px;"> <label for="name" class="paymentlable"> <input type="radio"  class="flat-red" value="2" name="cardtype[<?php echo $purch_id;?>][CARDTYPE][][<?php echo $gridNo;?>]" id="cardtype"/> </label> </div><img class="flagimgs first" src="<?php echo $SITE_URL; ?>/plugins/dist/img/credit/mastercard.png"/> </div></div><div class="box-body" style="width: 16%;float: left;padding: 0px !important; height: 60px;"> <div class="form-group"> <div style="margin-left: 15px;"> <label for="name" class="paymentlable"> <input type="radio" class="flat-red" <?php if($id_cardtype=='3'){echo "checked";}?> value="3" name="cardtype[<?php echo $purch_id;?>][CARDTYPE][][<?php echo $gridNo;?>]" id="cardtype"/> </label> </div><img class="flagimgs first" src="<?php echo $SITE_URL; ?>/plugins/dist/img/credit/american-express.png"/> </div></div><div class="box-body" style="width: 16%;float: left;padding: 0px !important; height: 60px;"> <div class="form-group"> <div style="margin-left: 15px;"> <label for="name" class="paymentlable"> <input type="radio" class="flat-red" value="4" name="cardtype[<?php echo $purch_id;?>][CARDTYPE][][<?php echo $gridNo;?>]" id="cardtype"/> </label> </div><img class="flagimgs first" src="<?php echo $SITE_URL; ?>/plugins/dist/img/credit/paypal2.png"/> </div></div></span> </div></div></td><td style="width:11.8%;border-left: none !important;border-right: none !important;"><input type="text" class="form-control first-input billingamount_'+get_purch_id+'" name="payamount['+get_purch_id+'][CARD][]" id="payamount" onKeyUp="getpayamount(this.value,'+get_purch_id+','+grand_total_amount+');"  value="0" style="float: left;" data-parsley-required data-parsley-errors-container="#payamountError"/></td><td style="width: 35.5%;border-left: none !important;border-right: none !important;"><div class="form-group" style="width:100% !important;"><div class="input-group"  style="width:100% !important;"><input type="text" class="form-control first-input" placeholder="Card Number" name="cardnumber['+get_purch_id+'][CARDNUMBER][]" id="cardnumber" value="" style="width:100% !important;border: none !important;"/></div></div><input type="text" class="form-control first-input" placeholder="Remarks" name="remarks['+get_purch_id+'][CARD][]" id="remarks" value="" style="float: left;"/></td><td style="border-left: none !important;border-right: none !important;width:11.5%"> <div class="form-group" style="width:100% !important;"><div class="input-group"  style="width:100% !important;"><input type="text" class="form-control first-input" name="tips['+get_purch_id+'][CARD][]" id="tips" value="0" style="float: left;"/></div></div><a class="btn btn-danger btn-sm" href="javascript:void(0);"  onclick="removeGrid('+gridNo+','+get_purch_id+','+grand_total_amount+');"><i class="fa fa-trash-o fa-lg"></i> </a></td></tr> </tbody></table></div>';

        $('#rowGrid_'+get_purch_id).append(grid); 
        gridNo++;
    }

</script>

  <?php include_once("../includes/footer.php")?>
  
