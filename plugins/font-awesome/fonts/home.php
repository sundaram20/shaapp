<?php include_once("../config/auto_loader.php"); ?>

<?php
// getting data to generate graph
  $conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
  
 
  $sql = "SELECT hotel_access FROM `fs_users` WHERE id = '".$_SESSION['userId']."' AND id_shop = '".$_SESSION['shop']."' ";
  
  $res = mysqli_query($conn,$sql);
  //Fecthing Data for 1st graph
  $list = "";
  $hotel_cond = "";
  $dates = array();
  
  $list = mysqli_fetch_object($res);
  $select_hotel =  $list->hotel_access;


  if($select_hotel != ""){
    $hotel_cond3 = "AND id IN (".$select_hotel.")";
  }
  
  if($_REQUEST["id_hotel"] != 0){
    $list->hotel_access  = $_REQUEST["id_hotel"];
  }
  
  if($list->hotel_access != "" ){
    $hotel_cond = "AND hotel_id IN (".$list->hotel_access.")";
    $hotel_cond1 = "AND id_hotel IN (".$list->hotel_access.")";
  }
  
  
  if(isset($_REQUEST['reservation_date']) || $_REQUEST['reservation_date'] != ""){
    $dates = explode(' to ',$_REQUEST['reservation_date']);
    $start = $dates[0];
    $end = $dates[1];
    $startMo = date('m',strtotime($start));
    $endMo = date('m',strtotime($end));
    $startYr = date('Y',strtotime($start));
    $endYr = date('Y',strtotime($end));
    $diff = abs(($endMo-$startMo));
    $diffYr = ($endYr-$startYr);
  }
  else{
    $start = date("Y-m-d");
    $end =  date("Y-m-d",strtotime("+5 months", strtotime($start)));
    $startMo = date('m',strtotime($start));
    $endMo = date('m',strtotime($end));
    $startYr = date('Y',strtotime($start));
    $endYr = date('Y',strtotime($end));
    $diff = abs(($endMo-$startMo));
    $diffYr = ($endYr-$startYr);
  }  
    
  $dataRN = array();
  $finalData = array();
  if($diffYr > 0){
    $diff = abs(($endMo+12-$startMo));
  }
  for($i = 0 ; $i <= $diff ; $i++){
     $sqlGrp = "SELECT SUM(`room_quantity`) AS `RN`,SUM(`tarrif_price`) AS `RNVALUE` FROM `fs_order_detail` WHERE MONTH(dated) = '".$startMo."' AND YEAR(dated) = '".$startYr."' AND id_shop = ".$_SESSION['shop']." ".$hotel_cond."   ";

   $sqlBgt = "SELECT SUM(`qty`) AS `BGRN`,SUM(`month_value`) AS `BGRNVALUE` FROM `fs_budget_master` WHERE MONTH(month) = '".$startMo."' AND YEAR(month) = '".$startYr."' AND id_shop = ".$_SESSION['shop']." ".$hotel_cond1."";
   
   //echo"<br>".$sqlGrp;
    
    if($startMo >= 12 ){
      $startYr++;
      $startMo = 0;
    }
    
    $res = mysqli_query($conn,$sqlGrp);
    if($res){
      $rn = mysqli_fetch_object($res);  
      array_push($dataRN,$rn->RN);
    }
    $res1 = mysqli_query($conn,$sqlBgt);
    if($res1){ 
      $rn1 = mysqli_fetch_object($res1);  
      array_push($dataRN,$rn1->BGRN);
    }

    $monthName =  DateTime::createFromFormat('!m', $startMo);
    $monthName = $monthName->format('F');
    $finalData[$i] = array($monthName,$rn->RN,$rn1->BGRN,$rn->RNVALUE,$rn1->BGRNVALUE); 
    $startMo++;  
  }
//Fecthing Data for 1st graph end
  if(isset($_REQUEST["flash_date"])){
    $setFormat = date_create($_REQUEST["flash_date"]);
    $current_date = $setFormat->format('Y-m-d');
  }
  else{
    $current_date = date('Y-m-d');
  }
  $last_year_current_date = date('Y-m-d',strtotime('-1 year',strtotime($current_date)));

  //MTD
  $from = date_create($current_date);
  $from_month_to_date = date_create($from->format('Y-m-01'));
  $from_month_to_date = $from_month_to_date->format('Y-m-d');
  $to_month_to_date = $current_date;
  $last_year_to_month_to_date = date('Y-m-d',strtotime('-1 year',strtotime($current_date)));
  $from = date_create($last_year_to_month_to_date);
  $last_year_from_month_to_date = date_create($from->format('Y-m-01'));
  $last_year_from_month_to_date = $last_year_from_month_to_date->format('Y-m-d');

  //YTD
  $to_year_to_date = $current_date;
  $from = date_create($current_date);
  $from_year_to_date = date_create($from->format('Y-04-01'));
  $from_year_to_date = $from_year_to_date->format('Y-m-d');
  $last_year_to_year_to_date = date('Y-m-d',strtotime('-1 year',strtotime($current_date)));
  $from = date_create($last_year_to_year_to_date);
  $last_year_from_year_to_date = date_create($from->format('Y-04-01'));
  $last_year_from_year_to_date = $last_year_from_year_to_date->format('Y-m-d');

  //printing dates
  /*echo "Today";
  echo "<br>1)Current_date :".$current_date;
  echo "<br>2)Last_year_current_date :".$last_year_current_date;
  echo "<br><br>MTD";
  echo "<br>3) From_month_to_date :".$from_month_to_date;
  echo "<br>4)To_month_to_date :".$to_month_to_date;
  echo "<br>5)Last_year_to_month_to_date :".$last_year_to_month_to_date;
  echo "<br>6)Last_year_from_month_to_date : ".$last_year_from_month_to_date;
  echo "<br><br>YTD";
  echo "<br>7)from_year_to_date :".$from_year_to_date;
  echo "<br>8)to_year_to_date :".$to_year_to_date;
  echo "<br>9)Last_year_to_year_to_date :".$last_year_to_year_to_date;
  echo "<br>10)Last_year_from_year_to_date :".$last_year_from_year_to_date;
  exit;*/
// Fecthing Data for 2nd graph

  
  if($_REQUEST["id_hotel_md"] != 0){
    $list->hotel_access1  = $_REQUEST["id_hotel_md"];
  }
  else{
    $list->hotel_access1  = $select_hotel;
  }
  if($list->hotel_access1 != "" ){
    $hotel_cond_md = "AND id_hotel IN (".$list->hotel_access1.")";
  }
  
   $sqlmtd = "SELECT  

sum(case when (`booking_status` = '1' || `booking_status` = '2') and (  `fs_order_detail` .dated = '".$current_date."') then `fs_order_detail`.room_quantity else 0 end) as `ThisYearRn`,

sum(case when (`booking_status` = '1' || `booking_status` = '2') and (  `fs_order_detail` .dated = '".$current_date."') then `fs_order_detail`.tarrif_price else 0 end) as `ValueThisYear`,

sum(case when (`booking_status` = '1' || `booking_status` = '2') and ( `fs_order_detail` .dated = '".$last_year_current_date."') then `fs_order_detail`.room_quantity else 0 end) as `LastYearRn`,

sum(case when (`booking_status` = '1' || `booking_status` = '2') and (  `fs_order_detail` .dated = '".$last_year_current_date."') then `fs_order_detail`.tarrif_price else 0 end) as `ValueLastYear`,


sum(case when (`booking_status` = '1' || `booking_status` = '2') and ( ( `fs_order_detail` .dated between '".$from_month_to_date."' and '".$to_month_to_date."')) then `fs_order_detail`.room_quantity else 0 end) as `MTDthisyear`,

sum(case when (`booking_status` = '1' || `booking_status` = '2') and ( ( `fs_order_detail` .dated between '".$from_month_to_date."' and '".$to_month_to_date."')) then `fs_order_detail`.tarrif_price else 0 end) as `ValueMTDthisyear`,

sum(case when (`booking_status` = '1' || `booking_status` = '2') and ( ( `fs_order_detail` .dated between '".$last_year_from_month_to_date."' and '".$last_year_to_month_to_date."')) then `fs_order_detail`.room_quantity else 0 end) as `LastYearMTD`,
sum(case when (`booking_status` = '1' || `booking_status` = '2') and ( ( `fs_order_detail` .dated between '".$last_year_from_month_to_date."' and '".$last_year_to_month_to_date."')) then `fs_order_detail`.tarrif_price else 0 end) as `ValueLastYearMTD`,

sum(case when (`booking_status` = '1' || `booking_status` = '2') and ( ( `fs_order_detail` .dated between '".$from_year_to_date."' and '".$to_year_to_date."')) then `fs_order_detail`.room_quantity else 0 end) as `YTDthisyear`,

sum(case when (`booking_status` = '1' || `booking_status` = '2') and ( ( `fs_order_detail` .dated between '".$from_year_to_date."' and '".$to_year_to_date."')) then `fs_order_detail`.tarrif_price else 0 end) as `ValueYTDthisyear`,

sum(case when (`booking_status` = '1' || `booking_status` = '2') and ( ( `fs_order_detail` .dated between '".$last_year_from_year_to_date."' and '".$last_year_to_year_to_date."')) then `fs_order_detail`.room_quantity else 0 end) as `LastYearYTD`,
sum(case when (`booking_status` = '1' || `booking_status` = '2') and ( ( `fs_order_detail` .dated between '".$last_year_from_year_to_date."' and '".$last_year_to_year_to_date."')) then `fs_order_detail`.tarrif_price else 0 end) as `ValueLastYearYTD`

FROM `fs_orders` right join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order  and (`fs_order_detail` .dated <= '".$current_date."') where `fs_orders`.`id_shop` = '".addslashes($_SESSION['shop'])."' $hotel_cond_md  ORDER BY `fs_order_detail`.`dated` ASC";

$resMd = mysqli_query($conn,$sqlmtd);
if($resMd){
  $mtdData = mysqli_fetch_object($resMd);
}

// Fecthincg Data for 2nd report end

  mysqli_close($conn);
 //getting data to generate graph end 
?>

<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Version 1.0.0</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	 <div class="form-group has-error" align="center">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div>
      <!-- Info boxes -->
      <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-home"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Hotels</span>
              <span class="info-box-number"><?php echo @mysql_num_rows(@mysql_query("SELECT `id` FROM `".TBL_HOTELS."` where id_shop='".addslashes($_SESSION['shop'])."'"));?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-book"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Bookings</span>
              <span class="info-box-number"><?php echo @mysql_num_rows(@mysql_query("SELECT `id_order` FROM `".TBL_ORDERS."` where id_shop='".addslashes($_SESSION['shop'])."'"));?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-bed"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Room Type</span>
              <span class="info-box-number"><?php echo @mysql_num_rows(@mysql_query("SELECT `id` FROM `".TBL_ROOM_TYPE."` where id_shop='".addslashes($_SESSION['shop'])."'"));?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Users</span>
              <span class="info-box-number"><?php echo @mysql_num_rows(@mysql_query("SELECT `id` FROM `".TBL_USERS."` where id_shop='".addslashes($_SESSION['shop'])."'"));?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!--first Graph start-->
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Monthly Report</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>               
                
              </div>
            </div>
            <!--date form-->
            <form method="post" accept="">
              <!--date input-->
              <div class="form-group col-sm-4">
                <label for="reservation_date">&nbsp;Booking Date : From  - To </label>
                <div class="input-group">
                  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                    <input type="text" class="form-control pull-right dateRangeEdit" id="checkin_date" placeholder="Enter Checkin date" name="reservation_date" id="reservation_date" data-parsley-required value="<?php if(isset($_REQUEST['reservation_date'])) echo $_REQUEST['reservation_date'];?>" data-parsley-errors-container="#reservation_dateError"  automcomplete="off">
                  </div>
               </div>
               <!--date input end-->
               <!--Hotel select list-->
               <div class="col-md-4">
                  <div class="form-group">
                    <label>Hotel</label>       
                      <?php 
                        $hotelDropDown = '<select class="form-control select2" name="id_hotel">
                                             <option value="0">Select All</option>';
                                            $resCat = selectSql(TBL_HOTELS," where id_shop='".addslashes($_SESSION['shop'])."' ".$hotel_cond3." ",' ORDER BY `name`');
                                      if($db->num_rows2($resCat)){
                                        while($resultCat = $db->fetch_object2($resCat)){
                                        if(isset($_REQUEST['id_hotel']) && $_REQUEST['id_hotel']!="" && trim($_REQUEST['id_hotel']) == $resultCat->id_hotel){
                                          $selected = 'selected="selected"';
                                       }else{
                                          $selected = '';
                                        }
                                        $hotelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).' '.ucfirst($resultCat->last_name).'</option>';
                                     }
                                      }
                                      echo $hotelDropDown .= '</select>';
                       ?>
                      </div>
                      
                  </div>
                  <!--hotel select list end-->
                 <!--search button-->
                  <div class="form-group col-sm-4" style="margin-top:25px;">
                    <div class="input-group">
                        <input name="Search" type="submit" class="btn btn-primary" value="Submit" />
                      </div>
                   </div>
                  
                 <!--search button end-->
               </form>
              <!--date form end-->
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-6">
                  <p class="text-center">
                    <strong>Room Nights (Month Wise) : <?php echo date("d-M-Y",strtotime($start))?> - <?php echo date("d-M-Y",strtotime($end))?></strong>
                    <div class="row"> 
                      <div class="col-md-2 col-lg-offset-4 col-xs-2 col-xs-2 col-xs-offset-4 col-xs-2 col-md-offset-4" style="background-color: #87CEFA;">
                          Budget
                      </div>
                      <div class="col-md-2 col-xs-3 col-xs-3" style="background-color: #3b8bba ;">
                          Achieved 
                      </div>
                    </div>
                  </p>

                  <div class="chart">
                    <!-- sales Chart Canvas -->
                    <canvas id="saleChart" style="height: 250px;"></canvas>
                  </div>
                  <!-- /.chart-responsive -->
                </div>
                <!-- /.col -->
                <div class="col-md-6 ">
                  <p class="text-center">
                    <strong>Revenue (Month Wise in lacs) : <?php echo date("d-M-Y",strtotime($start))?> - <?php echo date("d-M-Y",strtotime($end))?></strong>
                    <div class="row"> 
                      <div class="col-md-2 col-sm-2 col-xs-2 col-lg-offset-4 col-md-offset-4 col-xs-offset-4" style="background-color: #DCD0FF;">
                          Budget
                      </div>
                      <div class="col-md-2 col-sm-3 col-xs-3" style="background-color: #8e44ad ;">
                          Achieved 
                      </div>
                  </p>
                  <br>
                  <div class="chart">
                    <!--sales Chart Canvas -->
                    <canvas id="saleChartValue" style="height: 250px;"></canvas>
                  </div>
                  <!-- /.chart-responsive -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <!--first graph end-->

        <!--second graph start-->

        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Today | MTD | YTD Report</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>               
                
              </div>
            </div>
            <!--date form-->
            <form method="post" accept="">
              <!--date input-->
              <div class="form-group col-sm-4">
                <label for="start_date">AS On</label>
                  <input type="text" class="form-control pickerdate" placeholder="Enter start date" id="pace_date" name="flash_date" value="<?php if($_POST) echo $_POST['flash_date'];elseif($row->pace_date) echo stripslashes(date('d-m-Y',strtotime($row->pace_date))); else echo date('d-m-Y'); ?>"  data-parsley-required>
        <?php echo $err_start_date;?>
               </div>
               <!--date input end-->
               <!--Hotel select list-->
               <div class="col-md-4">
                  <div class="form-group">
                    <label>Hotel</label>       
                      <?php 
                        $hotelDropDown = '<select class="form-control select2" name="id_hotel_md">
                                             <option value="0">Select All</option>';
                                            $resCat = selectSql(TBL_HOTELS," where id_shop='".addslashes($_SESSION['shop'])."' ".$hotel_cond3." ",' ORDER BY `name`');
                                      if($db->num_rows2($resCat)){
                                        while($resultCat = $db->fetch_object2($resCat)){
                                        if(isset($_REQUEST['id_hotel']) && $_REQUEST['id_hotel']!="" && trim($_REQUEST['id_hotel']) == $resultCat->id_hotel){
                                          $selected = 'selected="selected"';
                                       }else{
                                          $selected = '';
                                        }
                                        $hotelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).' '.ucfirst($resultCat->last_name).'</option>';
                                     }
                                      }
                                      echo $hotelDropDown .= '</select>';
                       ?>
                      </div>
                      
                  </div>
                  <!--hotel select list end-->
                 <!--search button-->
                  <div class="form-group col-sm-4" style="margin-top:25px;">
                    <div class="input-group">
                        <input name="Search" type="submit" class="btn btn-primary" value="Submit" />
                      </div>
                   </div>
                  
                 <!--search button end-->
               </form>
              <!--date form end-->
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-6">
                  <p class="text-center">
                    <strong>Room Nights (Today | MTD | YTD) On <?php echo date("d-M-Y",strtotime($current_date))?></strong>
                    <div class="row"> 
                      <div class="col-md-2 col-lg-offset-4 col-xs-2 col-xs-2 col-xs-offset-4 col-xs-2 col-md-offset-4" style="background-color: #3ee096;">
                          Last Year
                      </div>
                      <div class="col-md-2 col-xs-3 col-xs-3" style="background-color: #00a65a ;color:white;">
                          This Year 
                      </div>
                    </div>
                  </p>

                  <a href="mtdytdReport.php?flash_date=<?php echo $current_date; ?>&Search=Search&hotel_access=<?php echo $list->hotel_access1; ?>"><div class="chart">
                    <!-- sales Chart Canvas -->
                    <canvas id="mtdChart" style="height: 250px;"></canvas>
                  </div></a>
                  <!-- /.chart-responsive -->
                </div>
                <!-- /.col -->
                <div class="col-md-6">
                  <p class="text-center">
                    <strong>Revenue (Today | MTD | YTD in Lacs) On <?php echo date("d-M-Y",strtotime($current_date))?></strong>
                    <div class="row"> 
                      <div class="col-md-2 col-sm-2 col-xs-2 col-lg-offset-4 col-md-offset-4 col-xs-offset-4" style="background-color: #48d0f2; color: white;">
                          Last Year
                      </div>
                      <div class="col-md-2 col-sm-3 col-xs-3" style="background-color: #02aad3 ;color:white;">
                          This Year 
                      </div>
                  </p>
                  <br>
                  <a href="mtdytdReport.php?flash_date=<?php echo $current_date ?>&Search=Search"><div class="chart">
                    <!--sales Chart Canvas -->
                    <canvas id="mtdChartValue" style="height: 250px;"></canvas>
                  </div></a>
                  <!-- /.chart-responsive -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            
          </div>
          <!-- /.box -->
        </div>

        <!--second graph end-->
      </div>
      <!-- /.row -->
      <!--first Graph start end-->


      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <div class="col-md-8">
          <!-- MAP & BOX PANE -->
          
          <!-- /.box -->
          <div class="row">            

            <!-- /.col -->
          </div>
          <!-- /.row -->

          <!-- TABLE: LATEST ORDERS -->
        
        
        </div>
     
        <div class="col-md-4">
         

          <!-- PRODUCT LIST -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Recently Added Hotels</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <ul class="products-list product-list-in-box">
			  
			  <?php $sql = " SELECT * FROM `".TBL_HOTELS."` where id_shop='".addslashes($_SESSION['shop'])."' ";
					
						$sql .= " ORDER BY `id` DESC limit 0,3";
					
					$resSql = executeSql($sql);
					if(num_rows($resSql) > 0){$counter = 1;
						$image_path = $UPLOAD_FILES.'/hotel_gallery/';
						$image_display_path = $UPLOAD_FILES_PATH ."/hotel_gallery/";
				  		while($rowSql = $db->fetch_object2($resSql)){
				  			echo '<li class="item">
								  <div class="product-img">';
							if(@file_exists($image_path.$rowSql->image) && $rowSql->image!=''){
								echo '<img src="'.$image_display_path.$rowSql->image.'" alt="'.$rowSql->name.'" title="'.$rowSql->name.'">';
							}else{
								echo '<img src="images/no-hotel-image.jpg" alt="'.$rowSql->name.'" title="'.$rowSql->name.'">';
							}		
							echo '</div>
								  <div class="product-info">
									<a href="editHotels.php?eId='.encryptor(encrypt,$rowSql->id).'&action=edit&page=" class="product-title">'.$rowSql->name.'</a>
									<span class="product-description">
										  '.$rowSql->address.'
										</span>
								  </div>
								</li>';
				  			}
				  	}
				   ?>
				 </ul>
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
  <!-- /.content-wrapper -->
   
<?php include_once("includes/footer.php")?>