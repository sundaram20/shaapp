<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_PURCH,'view');
$Downloadvalue='0';
?>

	<?php include_once("../includes/header.php")?>
	  <?php include_once("../includes/left.php");

		  if($_REQUEST['status'] == 'Non Chargeable'){

			$statuscase = " AND kot_status='Non Chargeable'" ;

		}elseif($_REQUEST['status'] == 'Cancelled'){
			$statuscase = " AND kot_status='Cancelled'"; 
		}else{
  if($_REQUEST['status'] == ''  && $_REQUEST['searchFormSubmit']==1){
                  $statuscase = " " ;
				  // $selected3 = 'selected="selected"';
                }else{
					 $statuscase = " AND kot_status='Non Chargeable'" ;
                  //$selected2 = 'selected="selected"';
                }
 //  $statuscase = " AND kot_status='Pending'" ;
   

	}
		
    if($_REQUEST['datefilter'] != ''){
    $DateExplode = explode(' to ',$_REQUEST['datefilter']);
    $startDate = date('Y-m-d',strtotime($DateExplode['0']));
    $endDate  = date('Y-m-d',strtotime($DateExplode['1']));
    //$endDate = date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
      
    $statuscase .= " AND DATE(`doc_date`) BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
  } else{
      $statuscase .= " AND DATE(`doc_date`) BETWEEN '".date('Y-m-d',strtotime('-1 days'))."' And '".date('Y-m-d')."'";
  }
		
if($_REQUEST['id_shift'] != ''){

	$statuscase .= " AND id_attribute_shift='".$_REQUEST['id_shift']."'" ;

}if($_REQUEST['id_steward'] != ''){

	$statuscase .= " AND id_attribute_steward='".$_REQUEST['id_steward']."'" ;

}
		
if($_REQUEST['search_name'] != ''){
	$sname	=explode('-',$_REQUEST['search_name']);
	$searchDocumentType = " AND pp.`mdoc_no` ='".addslashes($_REQUEST['search_name'])."'";

}	
	
/* $SQL="SELECT *  from
( select pp.*, 
	   (case  when COALESCE(pp.cancelled)=1 then 'cancelled'
	   		  when COALESCE(ppp.qty-ppp.adj_qty)>0 then 'Non Chargeable'
	         when COALESCE(ppp.qty-ppp.adj_qty)=0 then 'Billed' end) as kot_status
 
 from pos_purch pp left join pos_purch_details ppp on ppp.`id_pos_purch`=pp.id where id_shop= '".addslashes($_SESSION['shop'])."' AND pp.pos_bill_type=1 AND pp.doc_type=24 $searchDocumentType group by pp.id ORDER BY pp.`date_created` desc
 
 )as managekotlist WHERE id!=0 ".$statuscase." 
"; */

$SQL="SELECT *  from
( select pp.*, 
	   (case  when COALESCE(pp.cancelled)=1 then 'cancelled'
	   		  when COALESCE(ppp.qty-ppp.adj_qty)>0 then 'Non Chargeable'  end) as kot_status
 
 from pos_purch pp left join pos_purch_details ppp on ppp.`id_pos_purch`=pp.id where id_shop= '".addslashes($_SESSION['shop'])."' AND pp.pos_bill_type=1 AND pp.doc_type=24 $searchDocumentType group by pp.id ORDER BY pp.`date_created` desc
 
 )as managekotlist WHERE id!=0 ".$statuscase." 
";

//echo $SQL;
$downloadSql	=	$SQL;
$SqlKotList = mysqli_query($connNew, $SQL); 
$numRows=	mysqli_num_rows($SqlKotList);	        	 
$i=1;


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
				->setCellValue($head_cntr++.'1', 'KOT No')
				->setCellValue($head_cntr++.'1', 'Date');
				
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue($head_cntr++.'1', 'Shift')
				->setCellValue($head_cntr++.'1', 'Table')
				->setCellValue($head_cntr++.'1', 'Steward')
				->setCellValue($head_cntr++.'1', 'Pax')
				->setCellValue($head_cntr++.'1', 'Remarks')			
				->setCellValue($head_cntr++.'1', 'Status');

 $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->applyFromArray(

    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

);
$objPHPExcel->getActiveSheet($sheetOneIndex)->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet($sheetOneIndex)->getColumnDimension('I')->setWidth(20);
$objPHPExcel->getActiveSheet($sheetOneIndex)->getColumnDimension('D')->setWidth(20);

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
					  
					  
					 
					if($row->kot_status=='Billed')
					{
					$status='BILLED';
					
					 }
					if($row->kot_status=='Non Chargeable')
					{
					$status='NON CHARGEABLE';
					
					 }
					 if($row->kot_status=='cancelled')
					{
					$status='CANCELLED';
					
					 } 
					  
					  
					  
					  
					  
					  $timestamp = strtotime($row->doc_date);
						$date = date('d-m-Y', $timestamp);
						$time = date('h:i A', $timestamp);
					  
					  $counter++;
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A' . $counter, $SNo++)
					->setCellValue('B' . $counter, 'Kitchen Order Ticket')
					->setCellValue('C' . $counter, $row->mdoc_no)
					->setCellValue('D' . $counter, $date.'/'.$time)
					->setCellValue('E' . $counter, ucwords(selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$row->id_attribute_shift."' AND  status = '1' AND table_name ='".'shift'."'")))
					->setCellValue('F' . $counter, selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$row->id_attribute_table."' AND  status = '1' AND table_name ='".'table'."'"))
					->setCellValue('G' . $counter, $id_item_type=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id`='".$row->id_attribute_steward."'  AND table_name ='".'steward'."' "))
					
					->setCellValue('H' . $counter, $row->pax)
					->setCellValue('I' . $counter, $row->remarks)
					->setCellValue('J' . $counter, $status);
					

                      $objPHPExcel->getActiveSheet()->getStyle('A' .  $counter.':J'. $counter)->applyFromArray($styleThinBlackBorderOutline);


						  }
						  
						













	$objPHPExcel->setActiveSheetIndex(0);
	$datep	=	date('d-M-Y-H-i-s');
	ob_end_clean();
	// Redirect output to a client’s web browser (Excel2007)
	//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="KitchenOrderTicketNC'.$datep.'.xls"');
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
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
	
	<?php $session = '24'; ?>
	
    <section class="content-header">
     <div class="row">
         <div class="col-md-5 col-xs-8"> 
            <h6 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
	    	<?php echo '<span style="color:'.currentNavigation_s($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_s($session)['icon'].'"></i> '.currentNavigation_s($session)['submenu'].'</span>'; ?>
            </h6>
        </div>
     <div class="col-md-2 col-xs-4">	
     
                     <!-- <span style="font-weight:100;padding:3px 8px;text-decoration: underline;"><a class="text-o" href="manageOutletBilling.php?submenu=177&session=21"> List POS Bill</a></span>-->
                  
       
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
          <h6 class="box-title">Search <small>Records: (
            <?=$numRows;?>
            ) &nbsp;</small> </h6>
          <div class="btn-group  pull-right"> <a type="button" class="btn n-btn" href="managePosKot.php?doc_type=nc&submenu=<?php echo $_GET['submenu'] ?>&session=<?php echo $_GET['session'] ?>" >Add <?php echo currentNavigation()['submenu']; ?></a> 
          <button type="button" class="btn o-btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> 
    <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>
     <ul class="dropdown-menu " role="menu">
      <li><a title="Export to excel file" onClick="downloadExcelPdf(3);" href="javascript:void(0)" ><img src="../images/excel-icon.jpg" width="20" height="20">&nbsp;Excel</a></li>
      
    </ul></div>
        </div>
        
        <!-- /.box-header -->
        
        <form name="searchForm" action="" method="get">
			<input type="hidden" value="<?php echo $Downloadvalue;?>" name="Download" id="Download" />
          <input type="hidden" value="1" name="searchFormSubmit" />
          <div class="box-body">
            <div class="row">
              <div class="col-md-2 col-sm-12">
                <div class="form-group">
                  <label>KOT No</label>
                  <input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />
                </div>
                
                <!-- /.form-group --> 
                
              </div>
              
              <!-- /.col -->
              
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

                <!--col start-->
                <div class="col-md-2 col-sm-12 col-xs-6">
                   <div class="form-group">
                     <label>Period</label>  
                         <div class="input-group"> 
                            <!--<div class="input-group-addon">
                              <i class="fa fa-calendar"></i> 
                            </div>  -->
                          <!-- <input type="text" name="datefilter" id="datefilter" placeholder="Date" class="form-control"  value="" /> -->
                          <input type="text" class="form-control pull-right"  placeholder="Select From -  To" name="datefilter" id="dateRangeReport" data-parsley-required value="<?php if($_REQUEST['datefilter']!=''){echo $_REQUEST['datefilter'];}else{ echo date('d-m-Y',strtotime('-1 days')).' to '.date('d-m-Y'); }?>"   autocomplete="off">
                        </div>
                    </div>
                  </div>  

              <!-- /.form-group -->
              <!--End col-->
               <div class="col-md-2 col-sm-6 col-xs-6">
              <div class="form-group">
      <label for="id_shop">Shift </label>
      <select class="form-control select2" name="id_shift" id="id_shift" style="width:100%">

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
      <select class="form-control select2" name="id_steward" id="id_steward" style="width:100%">
        <?php $shopDropDown = '<option value="">All</option>';
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
               <!--End of col-->
        <div class="col-md-2 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>Status</label>
                  <?php 

					if($_REQUEST['status'] == 'Non Chargeable'){

							$selected1 = 'selected="selected"';

					}elseif($_REQUEST['status'] == 'Cancelled'){

							$selected0 = 'selected="selected"';

					}else{
               // $selected2 = 'selected="selected"';
	                   if($_REQUEST['status'] == ''  && $_REQUEST['searchFormSubmit']==1){
	                     $selected2 = 'selected="selected"';
	                     }else{
	                    $selected1 = 'selected="selected"';
	                   }
                   }


				  echo $statusDropDown = '<select class="form-control select2" name="status" style="width:100%"> 

				  <option '.$selected1.' value="Non Chargeable">Non Chargeable</option>
				   <option '.$selected0.' value="Cancelled">Cancelled</option>
				     <option '.$selected2.' value="">All</option>


				 

				  </select>';?>
                </div>
                
                <!-- /.form-group   <option '.$selected0.' value="Billed">Billed</option> --> 
                
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
            <div class="box-header  table-h text-center">
              <h4 class="box-title">List Of <?php echo currentNavigation()['submenu']; ?> </h4>
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
                      <th>Document Type</th>
                      <th>KOT No</th>
                      <th>Date</th>
                      <th>Shift</th>
                      <th>Table</th>
                      <th>Steward</th>
                      <th>Pax</th>
                       <th>Remarks</th>
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
					$status1='edit';
					}else{
					$status=' <a class="showSingle"><span class="label label-success">BILLED</span></a>';
					$stausicon='view.gif';
					$Imgtitle='View ';
					$statusValue='editKotviewid';
					$status1='view';
					} */
					
					if($row->kot_status=='Billed')
					{
					$status=' <a class="showSingle"><span class="label label-success">BILLED</span></a>';
					$stausicon='view.gif';
					$Imgtitle='View ';
					$statusValue='editKotviewid';
					$status1='view';
					 }
					if($row->kot_status=='Non Chargeable')
					{
					$status='<a class="showSingle" ><span class="label label-danger">NON CHARGEABLE</span></a>';
					$stausicon='view_edit.gif';
					$Imgtitle='View / Edit ';
					$statusValue='editKotid';
					$status1='edit';
					 }
					 if($row->kot_status=='cancelled')
					{
					$status='<a class="showSingle" ><span class="label label-info">CANCELLED</span></a>';
					$stausicon='view.gif';
					$Imgtitle='View ';
					$statusValue='CancelledKOT';
					$status1='cancel';
					 } 
					
						  ?>
                    <tr>
                      <td><?php echo $i++;?></td>
                       <td>Kitchen Order Ticket</td>
                      <td><?php echo $row->mdoc_no;//.'=='.$row->id;?></td>
					   <?php 
						$timestamp = strtotime($row->doc_date);
						$date = date('d-m-Y', $timestamp);
						$time = date('h:i A', $timestamp);
					  ?>
					  
                       <td><?php echo $date.' / '.$time  //<td><?=date('d-m-Y',strtotime($row->doc_date));?></td>
					  
					  
                      <td><?php echo ucfirst($shift_name);?></td>
                       <td><?php echo $table_name;?></td>
                      <td><?php echo ucfirst($steward_name);?></td>
                      <td><?php echo $row->pax;?></td> 
						<td><?php echo $row->remarks;?></td> 
                      <td><?php echo $status; ?></td>
					   <?php  if($row->kot_status=='cancelled')
					{?>
                    <td>
                      <a href="editKot.php?<?php echo $statusValue;?>=<?=encryptor(encrypt, $row->id);?>&submenu=<?php echo $_SESSION['submenu']; ?>&staus=<?php echo $status1; ?>" >
                      <img src="../images/<?php echo $stausicon;?>" style="cursor:pointer;" title="<?php echo $Imgtitle; ?> "/></a>&nbsp;&nbsp;&nbsp;&nbsp;
                      &nbsp;&nbsp;  </td>
                     <?php }else{
					?>
                      <td><a href="editKot.php?<?php echo $statusValue;?>=<?=encryptor(encrypt, $row->id);?>&submenu=<?php echo $_SESSION['submenu']; ?>&staus=<?php echo $status1; ?>" >
                      <img src="../images/<?php echo $stausicon;?>" style="cursor:pointer;" title="<?php echo $Imgtitle; ?> "/></a>&nbsp;&nbsp;&nbsp;&nbsp;
                      &nbsp;&nbsp; <a href="printKotPreview.php?printPreviewid=<?=encryptor(encrypt, $row->id);?>&submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>" ><img src="../images/print.png" style="cursor:pointer;" title=" Print "  /></a> </td>
                       <?php } ?> 
					
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
		<script>
  
	
function downloadExcelPdf(){
	$('#Download').val('1');
	document.forms['searchForm'].submit();
	$('#Download').val('0');
	}
  </script>
  <?php include_once("../includes/footer.php")?>
