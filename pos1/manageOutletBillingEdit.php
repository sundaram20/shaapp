<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],'pos_purch_pay','view');

include_once("include/function.php");



$image_path = $UPLOAD_FILES.'/hotel_gallery/';

$image_display_path = $UPLOAD_FILES_PATH ."/hotel_gallery/";

//---------------------------------------------------------------------------------------------------------


if($_REQUEST['status'] != ''){

	$sql .= " AND `status` = '".addslashes($_REQUEST['status'])."%' ";

} 

$sql .= " AND `id_shop` = '".addslashes($_SESSION['shop'])."'";

if($_REQUEST['order'] != ''){

	//$sql .= " AND pos_bill_type=2 ORDER BY `date_created` DESC";

}else{

	//$sql .= " AND pos_bill_type=2 ORDER BY `date_created` DESC";

}
//print_r($_REQUEST['datefilter']);



  
      

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
if($_REQUEST['id_table'] != ''){

  $statuscase .= " AND id_attribute_table='".$_REQUEST['id_table']."'" ;

}

if($_REQUEST['search_name'] != ''){

	$statuscase .= " AND `mdoc_no` ='".addslashes($_REQUEST['search_name'])."'";

}
if($_REQUEST['id_steward'] != ''){

	$statuscase .= " AND id_attribute_steward='".$_REQUEST['id_steward']."'" ;

}
if($_REQUEST['id_shift'] != ''){

	$statuscase .= " AND id_attribute_shift='".$_REQUEST['id_shift']."'" ;

}
if($_REQUEST['status'] == '1'){
    //$statuscase0 .= " AND payment_status='Pending'"; 
	$statuscase.= " AND pp.grant_total_amount-pp.payment_amount_received>0  AND pp.cancelled=0";
  }elseif($_REQUEST['status'] == '3'){
    //$statuscase0.= " AND payment_status='Settled'" ;
	
	$statuscase.= " AND pp.grant_total_amount-pp.payment_amount_received=0";
	
	
  }elseif($_REQUEST['status'] == '2'){
    //$statuscase0 .= " AND payment_status='Partial'"; 
  }elseif($_REQUEST['status'] == '4'){
    //$statuscase0.= " AND payment_status='cancelled'"; 
	
	$statuscase.= " AND pp.cancelled=1 ";
	
	
  }elseif($_REQUEST['status'] == '5'){
    $statuscase0 .= " AND pp.printed>'1'"; 
  }
  elseif($_REQUEST['status'] == '0'){
    $statuscase0 .= ""; 
  }else{
    //$statuscase .= " AND payment_status='Pending'"; 
      if($_REQUEST['status'] == ''  && $_REQUEST['searchFormSubmit']==1){
                  $statuscase0 = " " ;
          // $selected3 = 'selected="selected"';
                }else{
           $statuscase.= " AND pp.grant_total_amount-pp.payment_amount_received>0  AND pp.cancelled=0";
                  //$selected2 = 'selected="selected"';
                }
      }
      



 $sql = "

select pp.*,

(pp.grant_total_amount) as Billedamount, 
(pp.payment_amount_received) as amount_received,
(pp.grant_total_amount-pp.payment_amount_received) as balance_amount

from pos_purch  pp 
 where pos_bill_type=2 
and  id!=0 AND doc_type=21 ".$statuscase." 
ORDER BY last_modified desc
";

 /*$sql = "
select pp.*,

pp.grant_total_amount as amount_need_to_pay, 
pp.payment_amount_received as payment_amount_received
".$connSQL."
from pos_purch  pp 
where pp.pos_bill_type=2 

and pp.id!=0 AND pp.doc_type=21 ".$statuscase." 
";
*/
//echo $sql;//die;
//last_modified
$downloadSql	=	$sql;
$db->query($sql);

$numRows= $db->num_rows();

$pagging = new pagingClass($sql,$setpage);

$db->query($pagging->getQuery());

$total = $db->num_rows();



if($_REQUEST['Download'] == '1'){ 





 function cellColor($cells,$color){
      global $objPHPExcel;
      $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
          'type' => PHPExcel_Style_Fill::FILL_SOLID,
          'startcolor' => array(
          'rgb' => $color
        )
      ));
  }


cellColor('A1:J1','254061');
 $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);
$styleThinBlackBorderOutline = array(

	'borders' => array(

		'allborders' => array(

			'style' => PHPExcel_Style_Border::BORDER_THIN,

			'color' => array('argb' => '000'),

		),

	),

);
$styleArray = array(

    'font'  => array(

        'bold'  => true,

        'color' => array('rgb' => '1e51bf'),

        'size'  => 12,

        'name'  => 'Verdana'

    ));
$styleArray_1 = array(

    'font'  => array(

        'bold'  => true,

        'color' => array('rgb' => 'FF0000'),

        'size'  => 10,

        'name'  => 'Verdana'

    ));
$styleArrayWhite = array(

        'font'  => array(

            'bold'  => true,

            'color' => array('rgb' => 'FFFFFF'),

            'size'  => 11,

        'text-transform'=>'uppercase',

            'name'  => 'Calibri'

        ));
		$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($styleArrayWhite);
$objPHPExcel->getProperties()->setCreator("Gaurav Sharma")
								 ->setLastModifiedBy("Gaurav Sharma")
								 ->setTitle("Booking Report")
								 ->setSubject("Booking Report")
								 ->setDescription("Booking Report")
								 ->setKeywords("Booking Report")
								 ->setCategory("Report");

 									
	$head_cntr = "A";
	$counter = 1;
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue($head_cntr++.'1', 'S.No.')
				->setCellValue($head_cntr++.'1', 'Document Type')
				->setCellValue($head_cntr++.'1', 'Document No')
				->setCellValue($head_cntr++.'1', 'Date');
				
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue($head_cntr++.'1', 'Shift')
				->setCellValue($head_cntr++.'1', 'Table')
				->setCellValue($head_cntr++.'1', 'Steward')
				->setCellValue($head_cntr++.'1', 'KOT')
				->setCellValue($head_cntr++.'1', 'Amount')
				->setCellValue($head_cntr++.'1', 'Status');




						$counter = 1;$SNo=1;
						  $pos_purch_id_array=array();
						  $datalist	=	mysqli_query($connNew,$downloadSql); 
						  while($row = mysqli_fetch_object($datalist)){	

							$pos_purch_id_array[]= $row->id;
							$mdoc_no			 = $row->mdoc_no;
							$grand_total_amount	 = $row->grant_total_amount;
							$purch_id			 = $row->id;	
						
					
												
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
						$id_fo_bill[$purch_id][$id_type]	=	$ResultBlockedtable1->id_fo_bill;				$edit_doc_date  					 =	$ResultBlockedtable1->doc_date!=''?$ResultBlockedtable1->doc_date:'';	
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
						
					$ResultKotdocQuerymdoc_no=   array();
					   $GetKotdocSql = "SELECT id,mdoc_no FROM `".TBL_PURCH."` WHERE FIND_IN_SET(id,'".$row->kot_doc_no."') ";

	                  $KotdocQuery	=	mysqli_query($connNew,$GetKotdocSql); 
						
	                 while($ResultKotdocQuery = mysqli_fetch_object($KotdocQuery)){
					 $ResultKotdocQuerymdoc_no[]= $ResultKotdocQuery->mdoc_no;
					 }
					 
					  $ip=	implode(',',$ResultKotdocQuerymdoc_no);	
					  
					  
					 
							
							if($RowStatus=='Settled'){
								$status ='SETTLED';
                        }
						if($RowStatus=='Partial'){
                        $status ='PARTIALLY';
                        	}
					   if($RowStatus=='Pending'){
						     $status ='PENDING';
							  }
					  
					  if($RowStatus=='cancelled'){
                        $status ='CANCELLED';
                        }
					  
					  
					  
					  
					  
					  
					  
					  $counter++;
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A' . $counter, $SNo++)
					->setCellValue('B' . $counter, 'Point Of Sale')
					->setCellValue('C' . $counter, $row->mdoc_no)
					->setCellValue('D' . $counter, date('d-m-Y',strtotime($row->doc_date)))
					->setCellValue('E' . $counter, ucwords(selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$row->id_attribute_shift."' AND  status = '1' AND table_name ='".'shift'."'")))
					->setCellValue('F' . $counter, selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$row->id_attribute_table."' AND  status = '1' AND table_name ='".'table'."'"))
					->setCellValue('G' . $counter, $id_item_type=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id`='".$row->id_attribute_steward."'  AND table_name ='".'steward'."' "))
					->setCellValue('H' . $counter, $ip)
					->setCellValue('I' . $counter, $row->grant_total_amount)
					->setCellValue('J' . $counter, $status);
					

                      $objPHPExcel->getActiveSheet()->getStyle('A' .  $counter.':J'. $counter)->applyFromArray($styleThinBlackBorderOutline);


						  }
						  
						













	$objPHPExcel->setActiveSheetIndex(0);
	$datep	=	date('d-M-Y-H-i-s');
	ob_end_clean();
	// Redirect output to a client’s web browser (Excel2007)
	//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="PointOfSale'.$datep.'.xls"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');
	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;









die;
	
}

?>
<?php include_once("../includes/header.php")?>
  <?php include_once("../includes/left.php")?>
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
	
	
	<?php //echo $_SESSION['id_document']; ?>
    
     <?php $session=$_GET['submenu']; 
	?>
    <section class="content-header">
       <div class="row">
         <div class="col-md-4 col-xs-12"> 
           <h5 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
        	   	<?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>

               <?php //echo currentNavigation()['submenu']; ?>
           </h5>
         </div>
      <div class="col-md-4 col-xs-12 dd-f"> 
        <div class="icn-box">
                    <div class="btn-group  "> <a type="button"  title="Add KOT" class="btn n-btn pull-right" href="managePosKot.php?submenu=178" ><i class="fas fa-plus"></i> KOT </a> </div>
                     <div class="btn-group"> <a type="button"  title="List KOT" class="btn n-btn pull-right" href="manageKot.php?submenu=178&session=22" > <i class="fas fa-list"></i> KOT</a> </div>
                     
  <div class="btn-group"> <a type="button"  title="KOT Table View" class="btn n-btn pull-right" href="pendingkots.php?submenu=178" > <i class="fas fa-table"></i> KOT</a> </div>
                  <div class="btn-group"> <a type="button"  title="Kitchen Display System" class="btn n-btn pull-right" href="kds.php?submenu=178" > <i class="fas fa-tv"></i> KDS </a> </div> 
        
                   <!--  <span style="font-weight:100;padding:3px 0px;text-decoration: underline;"><a class="text-o" href="kotbilling.php?submenu=177&session=21"> <img title="Add Bill" src="../images/f-add-pos.png" height="28px"></a></span> <span style="font-weight:100;padding:3px 0px;text-decoration: none;"><a class="text-o" href="manageOutletBilling.php?submenu=177&session=21"><img title="List Bill" src="../images/list-billf.png"  height="28px"></a></span>-->
                 </div>
       </div> 

       <div class="col-md-4 col-xs-12 tb-br"> 
           <?php echo breadCrumbs(); ?>
       </div>
     </div>
    </section> 
    
    <!-- Main content -->
    
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
          <h3 class="box-title">Search <small> Records: (
            <?=$numRows;?>
            ) &nbsp;</small> </h3>
         <div class="btn-group  pull-right"> <a type="button" class="btn n-btn" href="kotbilling.php?submenu=<?php echo $_GET['submenu'] ?>&session=<?php echo $_GET['session'] ?>" >Add <?php echo currentNavigation()['submenu']; ?></a> 
          <button type="button" class="btn o-btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> 
    <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>
     <ul class="dropdown-menu " role="menu">
      <li><a title="Export to excel file" onClick="downloadExcelPdf(3);" href="javascript:void(0)" ><img src="../images/excel-icon.jpg" width="20" height="20">&nbsp;Excel</a></li>
      
    </ul></div>
        </div>
        <?php //echo $sql;?>
        <!-- /.box-header -->
        
        <form name="searchForm" id="searchForm" action="" method="get">
        <input type="hidden" value="" name="UnsettleRemarks" id="UnsettleRemarks">
			<input type="hidden" value="<?php echo $Downloadvalue;?>" name="Download" id="Download" />
          <input type="hidden" value="1" name="searchFormSubmit" />
           <input type="hidden" value="<?php echo $_GET['session'] ?>" name="session" />
            <input type="hidden" value="<?php echo $_GET['submenu'] ?>" name="submenu" />
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
						<!--<div class="input-group-addon">
							<i class="fa fa-calendar"></i> 
						</div> --> 
						<!-- <input type="text" name="datefilter" id="datefilter" placeholder="Date" class="form-control"  value="" /> -->
						<input type="text" class="form-control pull-right" style="padding:6px;" placeholder="Select From -  To" name="datefilter" id="dateRangeReport" data-parsley-required value="<?php if($_REQUEST['datefilter']!=''){echo $_REQUEST['datefilter'];}else{ echo Date('d-m-Y',strtotime('-1 days')).' to '.date('d-m-Y'); }?>"   autocomplete="off">
					</div>
              </div>
              <!-- /.form-group -->
 
              
            </div>
             
              
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
              <div class="col-md-2 col-sm-6 col-xs-6">
              <div class="form-group">
      <label for="id_shop">Shift </label>
      <select class="form-control select2" name="id_shift" id="id_shift" style="width: 100%">
        <?php $shopDropDown = '<option value="">All </option>';
					  $resUserShop = selectSql(TBL_ATTRIBUTES," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and `table_name` = 'shift'  and  `status` = '1'",' ORDER BY `field_value`');
											  if($db->num_rows2($resUserShop)){
											  	while($resultUserShop = $db->fetch_object2($resUserShop)){
													if($_REQUEST['id_shift'] == $resultUserShop->id){
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
                <label for="id_shop">Table </label>
                <select class="form-control select2" name="id_table" id="id_table" style="width: 100%">
                  <?php $shopDropDown = '<option value="">All </option>';
                      $resUserShop = selectSql(TBL_ATTRIBUTES," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and `table_name` = 'table'  and  `status` = '1'",' ORDER BY `field_value`');
                                  if($db->num_rows2($resUserShop)){
                                    while($resultUserShop = $db->fetch_object2($resUserShop)){
                                    if($_REQUEST['id_table'] == $resultUserShop->id){
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

					}elseif($_REQUEST['status'] == '5'){

							$selected5 = 'selected="selected"';

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
<option '.$selected5.' value="5">Re-Printed</option>
				  </select>';?>
                </div>
                
                <!-- /.form-group --> 
                
              </div>
              
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
            <!--<div class="box-header table-h text-center">
                 <h3 class="box-title">List Of <?php echo currentNavigation()['submenu']; ?> </h3>
            </div>-->
            <style>
				table.dataTable tbody th, table.dataTable tbody td {
				    padding: 3px 10px;
				}
			</style>
            <div name="listingForm">
              <input type="hidden" value="" name="act" />
              <div id="listingDiv"></div>
              <div class="box-body table-responsive">
                <table id="myTable" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" >
                  <thead>
                    <tr>
                     <th width="1%"> S.No.&nbsp;</th>
                      <th>Document Type</th>
                      <th>Document No</th>
                      <th>Date</th>
                      <th>Shift</th>
                      <th>Table</th>
                      <th>Steward</th>
                      <th width="132px">KOT</th>
                      <th>Billed Amount</th>
                       <th>Received Amount</th>
                        <th>Balance</th>
                      <th>Status</th>
                      <th width="13%">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 				 					

						if($total > 0){$counter = 1;
						  $pos_purch_id_array=array();
						  while($row = $db->fetch_object()){	
//echo '<br><pre>';print_r($row);echo '</pre>';
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
						$id_fo_bill[$purch_id][$id_type]	=	$ResultBlockedtable1->id_fo_bill;				$edit_doc_date  					 =	$ResultBlockedtable1->doc_date!=''?$ResultBlockedtable1->doc_date:'';	
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
                    <tr>
                   <td><?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>
                       <td>Point Of Sale<?php //echo $purch_id;?></td>
                      <td><?=$row->mdoc_no;?></td>
                      <td><?=date('d-m-Y',strtotime($row->doc_date));?></td>
                      <td><?php echo ucwords(selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$row->id_attribute_shift."' AND  status = '1' AND table_name ='".'shift'."'"));   ?></td>
                      <td><?php echo selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$row->id_attribute_table."' AND  status = '1' AND table_name ='".'table'."'");   ?></td>
                      <th ><?php 
						echo $id_item_type=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id`='".$row->id_attribute_steward."'  AND table_name ='".'steward'."' ");
								   ?></th>
                       <td style="word-break: break-all;"><?php $ResultKotdocQuerymdoc_no=   array();
					   $GetKotdocSql = "SELECT id,mdoc_no FROM `".TBL_PURCH."` WHERE FIND_IN_SET(id,'".$row->kot_doc_no."') ";

	                  $KotdocQuery	=	mysqli_query($connNew,$GetKotdocSql); 
						
	                 while($ResultKotdocQuery = mysqli_fetch_object($KotdocQuery)){
					 $ResultKotdocQuerymdoc_no[]= $ResultKotdocQuery->mdoc_no;
					 }
					 
					 echo $ip=	implode(',',$ResultKotdocQuerymdoc_no);
					 
					 ?></td>            
                      <th><?php echo round($row->grant_total_amount,2);?></th>
                      <th><?php echo round($row->amount_received,2);?></th>
                      <th><?php echo round($row->balance_amount,2);?></th>
                      <th><?php
					//Status=========================================
					
					
					if($row->balance_amount==0) {
						$RowStatus	='Settled';
					//Settled	
						?>
                        <a class="showSingle" target="<?php echo $purch_id;?>" style="cursor:pointer;"><span class="label label-success">SETTLED</span></a>
                        <?php
					}elseif($row->balance_amount>0 && $row->cancelled!='1'){
						$RowStatus	='Pending';
					//Pending	
					if($amount[$purch_id][1]==0){
							  $amount[$purch_id][1] =0;//round($balance_amount,2); modifed 15-3-2022 comment balance amount
							  $TotalPaidAmount	= 0;//$grand_total_amount;
							  $balance_amount=0;
							  $edit_doc_date  					 =	'';
						   }
						   ?>
                        <a class="showSingle" target="<?php echo $purch_id;?>" style="cursor:pointer;"><span class="label label-info">PENDING</span></a>
                        <?php	
					}elseif($row->cancelled=='1'){
						$RowStatus	='cancelled';
						?>
						<a  target="<?php echo $purch_id;?>" style="cursor:pointer;"><span class="label label-danger">CANCELLED</span></a>
						<?php }
					
					
					
					
							
							
							
							
							
					  
					  ?></th>
                     
                      <td style="width:130px;" >
                      
                       <a href="kotbilling.php?updateid=<?=encryptor(encrypt, $row->id);?>&submenu=<?php echo $_REQUEST['submenu']; ?>"  >
                       <img src="../images/edit.png" style="cursor:pointer;height:20px;" title=" View / Edit "  /></a> 
                          &nbsp;&nbsp; 
                      
                      <a href="printPreview.php?printPreviewid=<?=encryptor(encrypt, $row->id);?>&session=<?php echo $_REQUEST['session'] ?>&submenu=<?php echo $_REQUEST['submenu'] ?>" ><img src="../images/preview.png" style="cursor:pointer;height:20px;" title=" Page Preview "  /></a>
                        &nbsp;&nbsp; 
                       <?php if($RowStatus=='Pending'){
						   $UserAccessStatus	= checkUserLevelPermissionButton($_SESSION['userLevel'],TBL_PURCH,'status','manageOutletBilling.php');
						   ?>
                     
                        <a class="showSingle" onClick="ajaxcancel('<?php echo $purch_id;?>','<?php echo $UserAccessStatus;?>');" style="cursor:pointer;"><img src="../images/cash-register.png" style="cursor:pointer;width:20px; height:20px;" title=" Cancel Bill "   /></a>
                          &nbsp;&nbsp; 
                      <?php } ?>
                       <?php if($RowStatus!='cancelled'){?>
                      <!--<a href="billPayment.php?id=<?=encryptor(encrypt, $row->id);?>"><img src="../images/bill3.png" style="cursor:pointer;" title=" Bill Payment "  /></a>--> 
                        
                      <a class="showSingle" target="<?php echo $purch_id;?>" style="cursor:pointer;"><img src="../images/bill-payment.png" style="height:20px;cursor:pointer;" title=" Bill Payment "  /></a> &nbsp;&nbsp;
                     
                 
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
                       <!-- <div class="box-body" style="width: 16%;float: left;padding: 0px !important; height: 60px;">
                          <div class="form-group" style="margin-bottom: 0px;">
                            <div style="margin-left: 15px;">
                              <label for="name" class="paymentlable">
                                <input type="radio" <?php if($id_cardtype == '2'){echo "checked";} ?> class="flat-red" value="2" name="cardtype[<?php echo $purch_id;?>][CARDTYPE][][<?php echo $gridNo;?>]" id="cardtype"/>
                              </label>
                            </div>
                            <img src="../images/upi.png" style="cursor:pointer;" title="upi"  /> </div>
                        </div>-->
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
                                                
                                                <tr id="trbgcolor">
                                                  <td style="width: 2.5%;"> 
                                                   <input type="checkbox"  <?php  if($amount[$purch_id][6]>0){ echo 'checked';} ?> class="flat-red i-checks checkboxpayamount_6_<?php echo $purch_id;?>"   name="checkboxpayamount" id="checkboxpayamount" value="<?php echo $amount[$purch_id][6].'_6_'.$grand_total_amount.'_'.$purch_id; ?>"  /></div> 
                                                   </td>
                                                   <td> <div class="info-box" style="margin-bottom: 0px !important;"> <span class="info-box-icon bg-aqua paymode-span"> <img src="../images/gift.jpg" style="cursor:pointer;" title=" Bill Payment "  /> </span>
                                                      <div class="info-box-content"> <span class="info-box-text">UPI</span> 
													    <img src="../images/upi.png" style="cursor:pointer;margin-left:60px;" title="upi"  /></div>
                                                      
                                                        </div>
                                                      <!-- /.info-box-content --> 
                                                    </div></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][6]>0){ $amount[$purch_id][6];}else {echo 'disabled="disabled"';} ?> class="form-control first-input billingamount_<?php echo $purch_id;?>" name="payamount[<?php echo $purch_id;?>][UPI][]" id="payamount_6_<?php echo $purch_id;?>" onKeyUp="getpayamount(this.value,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>,6);"  value="<?php echo $amount[$purch_id][6]?$amount[$purch_id][6]:0; ?>" style="float: left;" data-parsley-required data-parsley-errors-container="#payamountError"/></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][6]>0){ $amount[$purch_id][6];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" placeholder="Remarks" name="remarks[<?php echo $purch_id;?>][UPI][]" id="remarks_6_<?php echo $purch_id;?>" value="<?php echo $remarks[$purch_id][6]; ?>" style="float: left;"/></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][6]>0){ $amount[$purch_id][6];}else {echo 'disabled="disabled"';} ?>  class="form-control first-input" name="tips[<?php echo $purch_id;?>][UPI][]" id="tips_6_<?php echo $purch_id;?>" value="<?php echo $tips[$purch_id][6]; ?>" style="float: left;"/></td>
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
                                                    
<?php
$isDisabled = ($amount[$purch_id][7] > 0) ? '' : 'disabled="disabled"';
?>

<select class="form-control first-input select2" style="width:100% !important;"
    name="id_fo_bill[<?php echo $purch_id; ?>][ROOMTO][]"
    id="id_fo_bill_7_<?php echo $purch_id; ?>"
    <?php echo $isDisabled; ?>>

    <option value="0">Select Room</option>

<?php
// Set condition based on RowStatus
if ($RowStatus == 'Settled' || $RowStatus == 'Partial') {
	 //$condition = "f.folio_status = '0'";
    $condition = "(f.id = '" . intval($row->id_fo_folio_to) . "' OR f.folio_status = '0')";
} else {
    $condition = "f.folio_status = '0'";
}

// Corrected JOIN query with mst_room_no_allocation
$sql = "
    SELECT 
        f.id AS folio_id,
        f.mdoc_no,
		g.id_mst_attributes_title AS id_mst_attributes_title,
        g.first_name AS first_name,
		g.last_name AS last_name,
        r.id_fo_bill,
        r.id_mst_room_no_allocation,
        ralloc.room_no
    FROM fo_folio AS f
    INNER JOIN fo_reservations_details AS r ON f.id = r.id_fo_folio_to
    LEFT JOIN mst_guest AS g ON f.id_mst_guest = g.id
    LEFT JOIN mst_room_no_allocation AS ralloc ON r.id_mst_room_no_allocation = ralloc.id
    WHERE f.status = '1' AND $condition
    GROUP BY f.id, r.id_mst_room_no_allocation
";

$res = mysqli_query($connNew, $sql);

if (mysqli_num_rows($res)) {
    while ($rowFolio = mysqli_fetch_assoc($res)) {
        $folioId           = $rowFolio['folio_id'];
        $mdocNo            = $rowFolio['mdoc_no'];
        $firstName         = $rowFolio['first_name'];
		$lastName         = $rowFolio['last_name'];
        $roomNo            = $rowFolio['room_no'];
        $billId            = $rowFolio['id_fo_bill'];
        $roomAllocId       = $rowFolio['id_mst_room_no_allocation'];
		$id_mst_attributes_title = $rowFolio['id_mst_attributes_title'];
		$Title=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title."'"); 	
		$guestName =$Title.$firstName.' '.$lastName;
        // Determine current room allocation from attributes table
        $id_mst_room_no_allocationRoom = '';
        if ($amount[$purch_id][7] > 0) {
            $id_mst_room_no_allocationRoom = selectColumn(
                TBL_ATTRIBUTES,
                'id_mst_room_no',
                "WHERE id_shop = '{$_SESSION['shop']}' AND status = '1' AND id = '{$row->id_attribute_table}'"
            );
        }

        // Determine if the option should be selected
        $selected = '';
        if ($id_fo_bill[$purch_id][7] == $billId && $amount[$purch_id][7] == '0') {
            $selected = 'selected="selected"';
        } elseif (
            $id_fo_bill[$purch_id][7] == $billId &&
            $amount[$purch_id][7] > 0 &&
            $roomAllocId == $id_mst_room_no_allocationRoom
        ) {
            $selected = 'selected="selected"';
        }

        echo '<option ' . $selected . ' value="' . $billId . '">'
            . $mdocNo . ' --- Room No: ' . $roomNo . ' --- Guest: ' . $guestName
            . '</option>';
    }
}
?>
</select>


  <?php /*?><select class="form-control first-input select2" style="width:100% !important;" name="id_fo_bill[<?php echo $purch_id;?>][ROOMTO][]" id="id_fo_bill_7_<?php echo $purch_id;?>" <?php  if($amount[$purch_id][7]>0){ $amount[$purch_id][7];}else {echo 'disabled="disabled"';} ?>>
                                                          <option value="0">Select Room </option>
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
															
															
                                                        </select><?php */?>
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
<?php
$use_night_audit_date =	selectColumn('mst_shops','use_night_audit_date'," WHERE `id` = '".$_SESSION['shop']."'");
if ($use_night_audit_date=='1') {
	$sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
	$numRowsNightAudit = mysqli_num_rows($sqlNightAudit);
	$rowNightAudit = mysqli_fetch_object($sqlNightAudit);
	$rowNightAuditDated = date('d-m-Y',strtotime($rowNightAudit->dated));
	$DatedNightAudit = date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated)));
	$DatedNightAudit = date('d-m-Y',strtotime($DatedNightAudit));
} else {
	$DatedNightAudit = date('d-m-Y');
}
?>										  
                                        <div class="form-group col-xs-12 col-md-2 col-sm-2" >
                                          <label for="name" style="margin-left:5px;color:#fff;">Date</label>
                                          <div class="input-group" style="margin-left:5px;">
                                            <div class="input-group-addon"> <i class="fa fa-diamond"></i> </div>
                                            <input   type="text" class="form-control pickerdateretwodays"  placeholder="sreEnter PO Date" id="po_date1" name="po_date1" value="<?php echo $edit_doc_date!=''?date('d-m-Y',strtotime($edit_doc_date)):$DatedNightAudit;?>" >
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
                                           <?php  if($RowStatus=='Settled' || $RowStatus=='Partial'){
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
                    
                    
                    <?php }?> 
                    <tr>	 
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
  <form id="FormkotremarksCancel" autocomplete="off">
  <input type="hidden" id="pos_purch_id" name="pos_purch_id" value="">
  	<div class="form-group">
      <label for="title"><b>Remarks</b></label>
      
      <textarea rows="2" cols="50" type="text" class="form-control input-sm" placeholder="Enter Remark" id="remark" name="remark" value="" data-parsley-required></textarea>
    </div>
	
  <div class="form-group">
    <label for="title"><b>Cancel</b> </label><br>
    <input type="radio" name="cancel2" id="cancel2" value="both" > Both ( KOT & BILL ) &nbsp;&nbsp;
   <input type="radio" name="cancel2" id="cancel2" value="bill" checked> Only BILL
  </div>
	
	<div class="form-group">
		 <label for="btn">&nbsp;<br><br></label>
		<button class="btn c-btn" onclick="ajaxCancelPOS();" type="button">Update</button>
		<button class="bookedby_close btn c-btn">Close</button>
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
 
 
 
 
<div class="row" id="nchide" >
      	<div class="col-md-12">
      		      	<!--cancel pop start-->
		  <div id="ncremarkspop" class="well p-4" style="margin:0 15px;display: none;"> 
		  <form id="Formkotremarks" autocomplete="off">
          
		  <input type="hidden" id="pos_purch_idpop" name="pos_purch_idpop" value="">
          <input type="hidden" id="UniqueCodeGenpos" name="UniqueCodeGenpos" value="">
            <div id="kot_mdoc_no"> </div>
		 	<div class="form-group">
		      <label for="title">Unsettle Remarks</label>
		      
		      <textarea rows="4" cols="50" type="text" class="form-control input-sm" placeholder="Enter Remark" id="get_remark" name="get_remark" value="" data-parsley-required></textarea>
		    </div>
			
			
			<div class="form-group">
				 <label for="btn">&nbsp;<br><br></label>
                 
				<button class="btn c-btn" onclick="ajaxupdateNonChargeableRemarks();" type="button"><i class="far fa-save"></i> Update</button>
				<button class="ncremarkspop_close btn c-btn"><i class="far fa-window-close"></i> Close</button>
			</div>
		  </form>
		</div>
		<!--cancel pop ends-->


         <!--add guest starts-->

		  
		<!--cancel pop ends-->
      	</div>
 
      </div>

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
function ajaxcancel(posid,UserAccessStatus){
	if(UserAccessStatus =='1'){
	//$("#cancelled").addClass("bookedby_open");
	$('#bookedby').popup({
        			transition: 'all 0.3s',
           			 autoopen: true,            
        			});
	$("#pos_purch_id").val(posid);	
	}else{
		alert("Cancel Not Allowed for this User");
		}	
	}
	
function ajaxCancelPOS(pos_purch_id){
	
var form=$("#FormkotremarksCancel");	
	
	
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
				window.location.href = "manageOutletBilling.php?submenu=<?php echo $_GET['submenu']?>&session=<?php echo $_GET['session']?>";
					
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
		$("#id_fo_bill_"+type+"_"+get_purch_id).removeAttr('disabled');
		$("#cardnumber_"+type+"_"+get_purch_id).removeAttr('disabled');
		$("#id_bank_"+type+"_"+get_purch_id).removeAttr('disabled')
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
		//alert("id_bank_"+type+"_"+get_purch_id);
	   $("#payamount_"+type+"_"+get_purch_id).attr('disabled','disabled');
	   $("#tips_"+type+"_"+get_purch_id).attr('disabled','disabled');
	   $("#remarks_"+type+"_"+get_purch_id).attr('disabled','disabled');
	   $("#id_company_"+type+"_"+get_purch_id).attr('disabled','disabled');
	   $("#cardnumber_"+type+"_"+get_purch_id).attr('disabled','disabled');
	   $("#id_fo_bill_"+type+"_"+get_purch_id).attr('disabled','disabled');
	  
	 
  
  
	    $("#id_bank_"+type+"_"+get_purch_id).attr('disabled','disabled');
	   $('#tips_'+type+'_'+get_purch_id).val('');
	   $('#remarks_'+type+'_'+get_purch_id).val('');
	   $('#id_company_'+type+'_'+get_purch_id).val('0');
	   $('#id_fo_bill_'+type+'_'+get_purch_id).val('0');
	   $('#cardnumber_'+type+'_'+get_purch_id).val('');
	    $('#id_bank_'+type+'_'+get_purch_id).val('0');
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
function ajaxupdateNonChargeableRemarks(){
	
	
var get_remark = $('#get_remark').val();
if(get_remark==''){
	alert('Please add Remarks');
}else{
	$('#UnsettleRemarks').val(get_remark);	
	//$('.targetDivShow').not('#div' + $(this).attr('target')).hide();
	$('#ncremarkspop').popup('hide');
	var id = $('#pos_purch_idpop').val();
	var UniqueCodeGen = $('#UniqueCodeGenpos').val();
	ajaxAddBillPayment(id,UniqueCodeGen);
		}
	}
	
function ajaxAddBillPayment(get_purch_id,savetype){
	
var UnsettleRemarks=$("#UnsettleRemarks").val();
	if(savetype== 0 && UnsettleRemarks==''){
				 $('#ncremarkspop').popup({
							transition: 'all 0.3s',
							 autoopen: true,            
							});
			$("#pos_purch_idpop").val(get_purch_id);
			$("#UniqueCodeGenpos").val(savetype);
				exit;	
	}
	
	var selectedRoomFolioId = $("#id_fo_bill_7_" + get_purch_id).val(); // Gets selected value (folio bill ID)

var selectedRoomText = $("#id_fo_bill_7_" + get_purch_id + " option:selected").text(); // Gets the full text
// Optional: extract just the Room No from the text
var roomNumber = selectedRoomText.split('---')[1]?.trim().replace('Room No:', '').split('---')[0]?.trim();

//console.log("Selected Folio Bill ID:", selectedRoomFolioId);
//console.log("Full Option Text:", selectedRoomText);
//console.log("Extracted Room Number:", roomNumber);
				
   var form1=$("#listingForm_"+get_purch_id);	
  // alert(form1);
  
   var dataString = $("#listingForm_"+get_purch_id).serialize()+'&savetype='+savetype+ '&room_no=' + encodeURIComponent(roomNumber);
	  // alert(dataString);
   
   if(form1.parsley().validate()){
	  // alert();
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxAddBillPayment.php',
		   data: dataString+'&UnsettleRemarks='+UnsettleRemarks,
		  /* beforeSend:function(){
         if(savetype == 0){
			 return confirm("Are you sure that you want to Unsettled?");
		 }
      },*/ 
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
				  window.location.href = "manageOutletBilling.php?submenu=<?php echo $_GET['submenu']?>&session<?php echo $_GET['session']?>";	
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
<?php $gridNo++;?>
        var grid ='<div id="grid'+gridNo+'_'+get_purch_id+'" > <table id="myTableOrder1" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" style="font-size:14px;padding: 0px 0px;border:none;" ><tbody><tr style="background-color:#fff !important;"><td style="border-right: none;border-top: none;width: 3.4% !important;"></td><td style="border-right: none;border-top: none;width: 97% !important;float: left;"> <div class="info-box" style="height:90px !important;min-height: 90x !important;margin-bottom: 0px !important;" > <span class="info-box-icon bg-aqua" style="height:90px !important;line-height: 90px !important;"> <img src="../images/credit_cards_card-512.png" style="cursor:pointer;" title=" Bill Payment "/> </span> <div class="info-box-content" style="width: 74%;height: 28px;"> <span class="info-box-text" style="width:20%;float:left;">CARD </span>  </div><div class="info-box" style="height:60px !important;min-height: 60px !important;margin-bottom: 0px !important;" > <span class="info-box-number"> <div class="box-body" style="width: 16%;float: left;padding: 0px !important;height: 60px;margin-left: 16px;"> <div class="form-group"> <div style="margin-left: 15px;"> <label for="name" class="paymentlable"> <input type="radio" class="flat-red"  value="1" name="cardtype['+get_purch_id+'][CARDTYPE][][<?php echo $gridNo;?>]" id="cardtype"/> </label> </div><img class="flagimgs first" src="<?php echo $SITE_URL; ?>/plugins/dist/img/credit/visa.png" alt="Visa"> </div></div><div class="box-body" style="width: 16%;float: left;padding: 0px !important; height: 60px;"> <div class="form-group"> <div style="margin-left: 15px;"> <label for="name" class="paymentlable"> <input type="radio"  class="flat-red" value="2" name="cardtype['+get_purch_id+'][CARDTYPE][][<?php echo $gridNo;?>]" id="cardtype"/> </label> </div><img class="flagimgs first" src="<?php echo $SITE_URL; ?>/images/upi.png"/> </div></div><div class="box-body" style="width: 16%;float: left;padding: 0px !important; height: 60px;"> <div class="form-group"> <div style="margin-left: 15px;"> <label for="name" class="paymentlable"> <input type="radio" class="flat-red" <?php if($id_cardtype=='3'){echo "checked";}?> value="3" name="cardtype['+get_purch_id+'][CARDTYPE][][<?php echo $gridNo;?>]" id="cardtype"/> </label> </div><img class="flagimgs first" src="<?php echo $SITE_URL; ?>/images/neft.png"/> </div></div></span> </div></div></td><td style="width:11.8%;border-left: none !important;border-right: none !important;"><input type="text" class="form-control first-input billingamount_'+get_purch_id+'" name="payamount['+get_purch_id+'][CARD][]" id="payamount" onKeyUp="getpayamount(this.value,'+get_purch_id+','+grand_total_amount+');"  value="0" style="float: left;" data-parsley-required data-parsley-errors-container="#payamountError"/></td><td style="width: 35.5%;border-left: none !important;border-right: none !important;"><div class="form-group" style="display:flex;margin-bottom:5px !important;"> <div class="input-group"  style="width:100% !important;"> <select class="form-control first-input select2" style="width:100% !important;" name="id_bank[<?php echo $purch_id;?>][BANK][]" id="id_bank_4_<?php echo $purch_id;?>" <?php  if($amount[$purch_id][4]>0){ $amount[$purch_id][4];}else {'';} ?>> <option value="0">Select BANK </option><!--select bank--> <?php  $resCat = selectSql(TBL_CHARGES," where status='1' and charges_account='8' and id_shop='".addslashes($_SESSION['shop'])."' and name !=' ' ",' ORDER BY `name`');  if($db->num_rows2($resCat)){  	while($resultCat = $db->fetch_object2($resCat)){	if($id_bank[$purch_id][4] == $resultCat->id){		$selected = 'selected="selected"';	}else{	$selected = '';
																}
													echo $categoryDropDown = '<option '.$selected.'  value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }?>
                                                        </select>  </div></div><input type="text" class="form-control first-input" placeholder="Remarks" name="remarks['+get_purch_id+'][CARD][]" id="remarks" value="" style="float: left;"/></td><td style="border-left: none !important;border-right: none !important;width:11.5%"> <div class="form-group" style="width:100% !important;"><div class="input-group"  style="width:100% !important;"><input type="text" class="form-control first-input" name="tips['+get_purch_id+'][CARD][]" id="tips" value="0" style="float: left;"/></div></div><a class="btn btn-danger btn-sm" href="javascript:void(0);"  onclick="removeGrid('+gridNo+','+get_purch_id+','+grand_total_amount+');"><i class="fa fa-trash-o fa-lg"></i> </a></td></tr> </tbody></table></div>';

        $('#rowGrid_'+get_purch_id).append(grid); 
        gridNo++;
    }
function downloadExcelPdf(){
	$('#Download').val('1');
	document.forms['searchForm'].submit();
	$('#Download').val('0');
	}
</script>
  
<?php include_once("../includes/footer.php"); ?>
