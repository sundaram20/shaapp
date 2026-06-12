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
		
    if($_REQUEST['datefilter'] != ''){
    $DateExplode = explode(' to ',$_REQUEST['datefilter']);
    $startDate = date('Y-m-d',strtotime($DateExplode['0']));
    $endDate  = date('Y-m-d',strtotime($DateExplode['1']));
    //$endDate = date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
      
    $statuscase .= " AND DATE(`doc_date`) BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
  } else{
      $statuscase .= " AND DATE(`doc_date`) BETWEEN '".date('Y-m-d',strtotime('-1 days'))."' And '".date('Y-m-d')."'";
  }
	
if($_REQUEST['search_name'] != ''){
	 $sname	=explode('/',$_REQUEST['search_name']);
	
	$statuscase = " AND `id` ='".addslashes($sname[1])."'";

}		
$SQL="SELECT *  from
fo_receipt WHERE id!=0 ".$statuscase." 
";

//echo $SQL;


$SqlKotList = mysqli_query($connNew, $SQL); 
$numRows=	mysqli_num_rows($SqlKotList);	        	 
$i=1;
  ?>
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
                  <label>FO No</label>
                  <input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />
                </div>
                
                <!-- /.form-group --> 
                
              </div>
              
              <!-- /.col -->
              <!--col start-->
                <div class="col-md-2 col-sm-6 col-xs-6">
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
                      
                      <th>Receipt No</th>
                      <th>Date</th>
                      <th>FOLIO No</th>
                      <th>Receipt Amount</th>
                     
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
                       
                      <td><?php echo 'REC/'.$row->id;//.'=='.$row->id;?></td>
					  
					 <?php 
						$timestamp = strtotime($row->doc_date);
						$date = date('d-m-Y', $timestamp);
						
					  ?>
					  
                      <td><?php echo $date  //<td><?=date('d-m-Y',strtotime($row->doc_date));?></td>
                      
                      
                      <td><?php echo   selectColumn('fo_folio','mdoc_no'," WHERE `id` = '".$row->id_fo_folio."'"); ?></td> 
                      
                       <td><?php echo  $row->amount; ?></td> 
                       
                      <td> 
						  <?php if($row->id_reservation>0){?>
                     <a target="_blank" href="print_advance_receipt.php?res_id=<?=encryptor(encrypt, $row->id_reservation);?>&id_folio=<?=encryptor(encrypt, $row->id);?>&id_mst_room_no_allocation=<?=encryptor(encrypt, $id_room);?>&submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>" ><img src="../images/preview.png" style="cursor:pointer;height:20px;" title="Page Preview "  /></a>&nbsp;&nbsp;
						  <?php }else{ ?>
                      
                     <a target="_blank" href="print_receipt.php?idrec=<?=encryptor(encrypt, $row->id);?>&id_folio=<?=encryptor(encrypt, $row->id);?>&id_mst_room_no_allocation=<?=encryptor(encrypt, $id_room);?>&submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>" ><img src="../images/preview.png" style="cursor:pointer;height:20px;" title="Page Preview "  /></a>&nbsp;&nbsp;
                      
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