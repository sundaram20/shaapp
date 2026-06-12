<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_PURCH,'view');

?>


<?php include_once("../includes/header.php")?>
  <?php include_once("../includes/left.php");

 
		
 if(!empty($_REQUEST['datefilter'])){	
    $DateExplode = explode(' to ',$_REQUEST['datefilter']);
    $startDate = date('Y-m-d',strtotime($DateExplode[0]));
    $endDate   = date('Y-m-d',strtotime($DateExplode[1]));

    $searchDocumentType .= " 
        AND DATE(`date_created`) 
        BETWEEN '".$startDate."' AND '".$endDate."'
    ";

}else{
    // ✅ Last 24 hours condition
    $searchDocumentType .= " 
        AND `date_created` >= NOW() - INTERVAL 1 DAY
    ";
}

		
$SQL="SELECT *  from api_request where 1=1 $searchDocumentType ORDER BY id desc
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
              
              
              
             
              
              <!-- /.col -->
              <!--col start-->
                <div class="form-group col-sm-3">
                       <?php //debugData($_REQUEST); ?>
                           <label for="booking_date">Date : From - To</label>
                                <div class="input-group">
                      <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                          <input type="text" class="form-control pull-right"  placeholder="Select From -  To" name="datefilter" id="dateRangeReport" data-parsley-required value="<?php if($_REQUEST['datefilter']!=''){echo $_REQUEST['datefilter'];}else{ echo date('d-m-Y').' to '.date('d-m-Y'); }?>"   autocomplete="off">
                        </div>
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
                      
                      <th>Date </th>
						<th>reference </th><th>Request</th>
                      <th>Response</th>
                      
                       <th>Hotel Name</th>
                      <th>StartDate</th>
                      <th>EndDate</th>
                      <th>IP Address</th>
                      
                    </tr>
                  </thead>
                  <tbody>
                    <?php  
  
		        	 
		        	 $i=1;
					 while($row = mysqli_fetch_object($SqlKotList)){ 
					  
		
				
					
					
						  ?>
						  
                    <tr>
                      <td><?php echo $i++;?></td>
                       
                      <td><?php echo $row->date_created;//.'=='.$row->id;?></td>
						<td><?php echo $row->booking_referance_id;//.'=='.$row->id;?></td>
					  <td><textarea class="form-control" name="address" id="myInput" cols = "150" rows="10" placeholder="Enter Address" data-parsley-required><?php  echo $row->request;?> </textarea></td>
					  <td><textarea class="form-control" name="address" id="myInput" cols = "150" rows="10" placeholder="Enter Address" data-parsley-required><?php  echo $row->response;?> </textarea></td>
						<td></td>
					  <td><?=date('d-m-Y',strtotime($row->start_date));?></td>	 
					 <td><?=date('d-m-Y',strtotime($row->end_date));?></td>	 
                  <td><?=$row->ip_address;?></td>	
                   
                      
                  
                      
                      
                      
                       
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
