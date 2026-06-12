<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_PURCH,'view');
?>

  <?php 

  if($_REQUEST['status'] == 'Pending'){

	$statuscase = " AND kot_status='Pending'" ;

}elseif($_REQUEST['status'] == 'Billed'){
	$statuscase = " AND kot_status='Billed'"; 
	}else{
		$statuscase= "";
		}
		
if($_REQUEST['search_name'] != ''){
	$sname	=explode('-',$_REQUEST['search_name']);
	$searchDocumentType = " AND pp.`mdoc_no` ='".addslashes($sname[1])."'";

}	

if($_SESSION['id_document']==25){
	$outleType	=	2;
	$MenuType	=	172;
	$id_item_type=selectColumn(TBL_ATTRIBUTES,'id','WHERE table_name="items_type" AND field_value="Laundry" ');
}
if($_SESSION['id_document']==26){
	$outleType	=	3;
	$id_item_type=selectColumn(TBL_ATTRIBUTES,'id','WHERE table_name="items_type" AND field_value="Spa and Health Club" ');
}

if($_SESSION['id_document']==29){
	$outleType	=	4;
	$id_item_type=selectColumn(TBL_ATTRIBUTES,'id','WHERE table_name="items_type" AND field_value="Others" ');
}

/*if($_SESSION['id_document']==25){
	$outleType	=	0;
}*/

  $doc_type = $_SESSION['id_document'];	

	
$SQL="SELECT *  from
( select pp.*, 
	   (case  when COALESCE(ppp.qty-ppp.adj_qty)>0 then 'Pending'
	         when COALESCE(ppp.qty-ppp.adj_qty)=0 then 'Billed' end) as kot_status
 
 from pos_purch pp left join pos_purch_details ppp on ppp.`id_pos_purch`=pp.id where id_shop= '".addslashes($_SESSION['shop'])."' AND pp.pos_bill_type=2 AND pp.doc_type='".$doc_type."' $searchDocumentType group by pp.id ORDER BY pp.`date_created` desc
 
 )as managekotlist WHERE id!=0 ".$statuscase." 
";



$SqlKotList = mysqli_query($connNew, $SQL); 
$numRows=	mysqli_num_rows($SqlKotList);	        	 
$i=1;
  ?>
  
 <?php include_once("../includes/header.php");include_once("../includes/left.php");?> 
  
  
  
  
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;"> <?php echo '<span style="color:'.currentNavigation()['color'].'">&nbsp;<i class="fa '.currentNavigation()['icon'].'"></i> '.currentNavigation()['submenu'].'</span>'; ?>
        <?php //echo currentNavigation()['submenu']; ?>
      </h3>
      <?php echo breadCrumbs(); ?> </section>
    <section class="content">
    <div class="box box-default">
        <div class="form-group has-error" align="center">
          <?php if($_SESSION['errorMsg']){?>
          <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
          <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
          <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
          <?php unset($_SESSION['successMsg']);}?>
        </div>
        <div class="box-header with-border">
          <h3 class="box-title">Search <small>Total Records: (
            <?=$numRows;?>
            ) &nbsp;</small> </h3>
          <div class="btn-group  pull-right"> <a type="button" class="btn btn-info pull-right" href="editOutletBill.php" >Add <?php echo currentNavigation()['submenu']; ?> </a> </div>
        </div>
        
        <!-- /.box-header -->
        
        <form name="searchForm" action="" method="get">
          <input type="hidden" value="1" name="searchFormSubmit" />
          <div class="box-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Document Type</label>
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
              <div class="col-md-6">
                <div class="form-group">
                  <label>Status</label>
                  <?php 

					if($_REQUEST['status'] == '1'){

							$selected1 = 'selected="selected"';

					}elseif($_REQUEST['status'] == '0'){

							$selected0 = 'selected="selected"';

					}

				  echo $statusDropDown = '<select class="form-control select2" name="status" style="width: 100%"> <option value="">All</option>

				  <option '.$selected1.' value="Pending">Pending</option>

				

				  <option '.$selected0.' value="Billed">Billed</option>

				  </select>';?>
                </div>
                
                <!-- /.form-group --> 
                
              </div>
              
              <!-- /.row --> 
              
            </div>
          </div>
          
          <!-- /.box-body -->
          
          <div class="box-footer">
            <input name="Search" type="submit" class="btn btn-primary" value="Search" />
          </div>
        </form>
      </div>
      
      <div class="row">
        <div class="col-xs-12"> 
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List Of <?php echo currentNavigation()['submenu']; ?> </h3>
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
                     <!-- <th>KOT No</th>  -->
                      <th>Date</th>
                     <!-- <th>Shift</th>
                      <th>Table</th> -->
                      <th>Steward</th>
                      <th>Pax</th>
                      
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
					}
					
						  ?>
                    <tr>
                      <td><?php echo $i++;?></td>
                       <td>Kitchen Order Ticket</td>
                    <!--  <td><?php echo $row->mdoc_no;//.'=='.$row->id;?></td> -->
                      <td><?=date('d-m-Y',strtotime($row->doc_date));?></td>
                   <!--   <td><?php echo ucfirst($shift_name);?></td>
                       <td><?php echo $table_name;?></td>  -->
                      <td><?php echo ucfirst($steward_name);?></td>
                      <td><?php echo $row->pax;?></td>                      
                      <td><?php echo $status; ?></td>
                      <td><a href="editKot.php?<?php echo $statusValue;?>=<?=encryptor(encrypt, $row->id);?>" >
                      <img src="../images/<?php echo $stausicon;?>" style="cursor:pointer;" title="<?php echo $Imgtitle; ?> "/></a>&nbsp;&nbsp;&nbsp;&nbsp;
                      &nbsp;&nbsp; <a href="printKotPreview.php?printPreviewid=<?=encryptor(encrypt, $row->id);?>" target="_blank" ><img src="../images/print.png" style="cursor:pointer;" title=" Print "  /></a> </td>
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
  <?php include_once("../includes/footer.php")?>
