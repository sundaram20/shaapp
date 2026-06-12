<?php include_once("../config/auto_loader.php"); ?>

<?php

// getting data to generate graph
  
 // $conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
 /* $sql = "SELECT hotel_access FROM `fs_users` WHERE id = '".$_SESSION['userId']."' AND id_shop = '".$_SESSION['shop']."' ";
  
  $res = mysqli_query($connNew,$sql);
  //Fecthing Data for 1st graph
  $list = "";
  $hotel_cond = "";
  $dates = array();
  
  $list = mysqli_fetch_object($res);
  $select_hotel =  $list->hotel_access;*/


  if($select_hotel != ""){
    $hotel_cond3 = "AND id IN (".$select_hotel.")";
  }
  
  if($_REQUEST["id_hotel"] != 0){
    $list->hotel_access  = $_REQUEST["id_hotel"];
  }
  
  if($_REQUEST["id_outlet"] != 0){
    $outlet_cond  = "AND ppp.id_mst_outlet IN (".$_REQUEST["id_outlet"].")";
  }
  else{
    //$hotel_cond_md;
  }
  
  
  if($list->hotel_access != "" ){
    $hotel_cond = "AND hotel_id IN (".$list->hotel_access.")";
    $hotel_cond1 = "AND id_hotel IN (".$list->hotel_access.")";
    $hotel_cond2 = "AND HID IN (".$list->hotel_access.")";
	 $hotel_access = "AND hotel_access IN (".$list->hotel_access.")";
	
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
	$financial_year_to = (date('m') > 3) ? date('Y') +1 : date('Y');
	$financial_year_from = $financial_year_to - 1;
	
    $start = date("".$financial_year_from."-04-01");
    $end =  date("".$financial_year_to."-03-31");
    $startMo = date('m',strtotime($start));
    $endMo = date('m',strtotime($end));
    $startYr = date('Y',strtotime($start));
    $endYr = date('Y',strtotime($end));
    $diff = abs(($endMo-$startMo));
    $diffYr = ($endYr-$startYr);
    $endYr = date('Y',strtotime($end));
    $diff = abs(($endMo-$startMo));
    $diffYr = ($endYr-$startYr);
    $reserve = array(date("d-m-Y",strtotime($start)),date("d-m-Y",strtotime($end)));
    $_REQUEST['reservation_date']=implode(" to ", $reserve);
  }  
    
  $dataRN = array();
  $finalData = array();
  if($diffYr > 0){
    $diff = abs(($endMo+12-$startMo));
  }
 
  for($i = 0 ; $i <= $diff ; $i++){
    /*$sqlGrp = "SELECT SUM(A.`qty`) AS `RN` 
            FROM `pos_purch` ORD
            INNER JOIN `pos_purch_details` A ON ORD.id = A.id_pos_purch
            WHERE MONTH(ORD.doc_date) = '".$startMo."' AND YEAR(ORD.doc_date) = '".$startYr."'  AND ORD.id_shop = ".$_SESSION['shop']." ".$hotel_cond."   ";
      */
	  
	    $sqlGrp = "select 
			
			sum(qty) as RN,
			SUM(ROUND(total/100,2)) AS `RNVALUE`
 
											from 
										(select pp.doc_date,pp.kot_doc_no,ppp.id_mst_items,ppp.item_description,  ppp.id as id_purch_detail,inv.item_code,
										ppp.qty, ppp.item_amount, ppp.item_discount_amount,((ppp.qty*ppp.item_amount)-ppp.item_discount_amount)  as  total,
										
										inv.id as id_item,
										inv.id_mst_attributes_group_main,inv.id_mst_attributes_group_sub
										
										from pos_purch  pp
										left join
										pos_purch_details ppp
										
										on
										FIND_IN_SET(ppp.id_pos_purch,pp.kot_doc_no)
										
										Inner join
										inv_items inv
										ON inv.id=ppp.id_mst_items
										  where pp.pos_bill_type='2' and pp.cancelled=0 and  pp.doc_type=21 and pp.id_shop= ".$_SESSION['shop']."
											AND MONTH(pp.doc_date) = '".$startMo."' AND YEAR(pp.doc_date) = '".$startYr."'
										  $outlet_cond
										  order by inv.id_mst_attributes_group_main,inv.id_mst_attributes_group_sub,inv.id
											) as purch_rpt
										
										WHERE id_mst_items!=0 
										
										order by id_mst_attributes_group_main,id_mst_attributes_group_sub";
    $sqlBgt = "SELECT SUM(A.`qty`) AS `BGRN`,SUM(A.`month_value`) AS `BGRNVALUE` 
            FROM `fs_budget_master` A
            WHERE MONTH(A.month) = '".$startMo."' AND YEAR(A.month) = '".$startYr."'  AND id_shop = ".$_SESSION['shop']." ".$hotel_cond1."  ";

   /* $sqlGrpVal = "SELECT SUM(ROUND(VALUE/100000,2)) AS `RNVALUE` 
            FROM `budget_achieved_revenue` 
            WHERE MONTH(Date) = '".$startMo."' AND YEAR(Date) = '".$startYr."' AND `DOC_TYPE`='ACHIEVED' AND shop_id = ".$_SESSION['shop']." ".$hotel_cond2." ";
     */     
    //echo $sqlGrp;
   /* exit;*/
    if($startMo >= 12 ){
      $startYr++;
      $startMo = 0;
    }
    
    $res = mysqli_query($connNew,$sqlGrp);
    if($res){

      $rn = mysqli_fetch_object($res);  
      //array_push($dataRN,$rn->RN);
    }

    $res1 = mysqli_query($connNew,$sqlBgt);
    if($res1){
      $rn1 = mysqli_fetch_object($res1);  
      //array_push($dataRN,$rn->RN);
    }

    /*$res2 = mysqli_query($connNew,$sqlGrpVal);
    if($res2){
      $val = mysqli_fetch_object($res2);  
      //array_push($dataRN,$rn->RN);
    }*/
    
    $monthName =  DateTime::createFromFormat('!m', $startMo);
    $monthName = $monthName->format('F');
    $finalData[$i] = array($monthName,$rn->RN,$rn1->BGRN,$rn->RNVALUE,$rn1->BGRNVALUE); 
    $startMo++;  
  }/*echo '<pre>';
  print_r($finalData);
  echo '</pre>';*/
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
  if(date('m',strtotime($current_date)) == '01' || date('m',strtotime($current_date)) == '02' || date('m',strtotime($current_date)) == '03' ){
    $from_year_to_date = date_create($from->format('Y-04-01'));
    $from_year_to_date = $from_year_to_date->format('Y-m-d');
    $from_year_to_date = date('Y-m-d',strtotime('-1 year',strtotime($from_year_to_date)));
  }
  else{
    $from_year_to_date = date_create($from->format('Y-04-01'));
    $from_year_to_date = $from_year_to_date->format('Y-m-d');
  }

  $last_year_to_year_to_date = date('Y-m-d',strtotime('-1 year',strtotime($current_date)));
  $from = date_create($last_year_to_year_to_date);

  $last_year_from_year_to_date = date_create($from->format('Y-04-01'));
  if(date('m',strtotime($current_date)) == '01' || date('m',strtotime($current_date)) == '02' || date('m',strtotime($current_date)) == '03' ){
    $last_year_from_year_to_date = $last_year_from_year_to_date->format('Y-m-d');
    $last_year_from_year_to_date = date('Y-m-d',strtotime('-1 year',strtotime($last_year_from_year_to_date)));
  }
  else{
    $last_year_from_year_to_date = $last_year_from_year_to_date->format('Y-m-d');
  }
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
  echo "<br>10)Last_year_from_year_to_date :".$last_year_from_year_to_date;*/
  
// Fecthing Data for 2nd graph

  	
 if($_REQUEST["id_outlet_md"] != 0){
    $outlet_cond_md  = "AND ppp.id_mst_outlet IN (".$_REQUEST["id_outlet_md"].")";
  }
  else{
    //$hotel_cond_md;
  }
  
 
											
		   $sqlmtd = "SELECT  

sum(case when  (   pp.doc_date = '".$current_date."') then ppp.qty else 0 end) as `ThisYearRn`,

sum(case when( pp.doc_date = '".$last_year_current_date."') then ppp.qty else 0 end) as `LastYearRn`,


sum(case when  ( ( pp.doc_date between '".$from_month_to_date."' and '".$to_month_to_date."')) then ppp.qty else 0 end) as `MTDthisyear`,

sum(case when  ( ( pp.doc_date between '".$last_year_from_month_to_date."' and '".$last_year_to_month_to_date."')) then ppp.qty else 0 end) as `LastYearMTD`,
sum(case when ( ( pp.doc_date between '".$from_year_to_date."' and '".$to_year_to_date."')) then ppp.qty else 0 end) as `YTDthisyear`,

sum(case when  ( ( pp.doc_date between '".$last_year_from_year_to_date."' and '".$last_year_to_year_to_date."')) then ppp.qty else 0 end) as `LastYearYTD`
										from pos_purch  pp
										left join
										pos_purch_details ppp
										
										on
										FIND_IN_SET(ppp.id_pos_purch,pp.kot_doc_no)
										
										Inner join
										inv_items inv
										ON inv.id=ppp.id_mst_items
										  where pp.pos_bill_type='2' and pp.cancelled=0 and 
										   pp.doc_type=21 and pp.id_shop = ".$_SESSION['shop']." 
										   and (pp.doc_date <= '".$current_date."') $outlet_cond_md
											
										  
										  order by inv.id_mst_attributes_group_main,inv.id_mst_attributes_group_sub,inv.id 
										  
										  
";










 $sqlmtdValue=" select 

sum(case when  pp.doc_date = '".$current_date."' then ROUND(((ppp.qty*ppp.item_amount)-ppp.item_discount_amount)/100,2) else 0 end) as `ValueThisYear`,
sum(case when   pp.doc_date = '".$last_year_current_date."' then ROUND(((ppp.qty*ppp.item_amount)-ppp.item_discount_amount)/100,2) else 0 end) as `ValueLastYear`,
sum(case when  pp.doc_date between '".$from_month_to_date."' and '".$to_month_to_date."' then ROUND(((ppp.qty*ppp.item_amount)-ppp.item_discount_amount)/100,2) else 0 end) as `ValueMTDthisyear`,
sum(case when  (pp.doc_date between '".$last_year_from_month_to_date."' and '".$last_year_to_month_to_date."') then ROUND(((ppp.qty*ppp.item_amount)-ppp.item_discount_amount)/100,2) else 0 end) as `ValueLastYearMTD`,
sum(case when  ( pp.doc_date between '".$from_year_to_date."' and '".$to_year_to_date."') then ROUND(((ppp.qty*ppp.item_amount)-ppp.item_discount_amount)/100,2) else 0 end) as `ValueYTDthisyear`,
sum(case when  (  pp.doc_date between '".$last_year_from_year_to_date."' and '".$last_year_to_year_to_date."') then ROUND(((ppp.qty*ppp.item_amount)-ppp.item_discount_amount)/100,2) else 0 end) as `ValueLastYearYTD`

										from pos_purch  pp
										left join
										pos_purch_details ppp
										
										on
										FIND_IN_SET(ppp.id_pos_purch,pp.kot_doc_no)
										
										Inner join
										inv_items inv
										ON inv.id=ppp.id_mst_items
										  where pp.pos_bill_type='2' and pp.cancelled=0 and  pp.doc_type=21 
										  and pp.id_shop = ".$_SESSION['shop']." $outlet_cond_md
											
										  order by inv.id_mst_attributes_group_main,inv.id_mst_attributes_group_sub,inv.id
											
											
											
											
											";
///////////////////////////////
$resMd2=array();
$resMd = mysqli_query($connNew,$sqlmtd);
if($resMd){

  $mtdData =  mysqli_fetch_object($resMd);

}


$resMdVal = mysqli_query($connNew,$sqlmtdValue);
if($resMdVal){
	
  $mtdDataVal = mysqli_fetch_object($resMdVal);
}
//print_r($mtdDataVal);
//echo $sqlmtd;
//exit;

// Fetching Data for 2nd report end

  mysqli_close($connNew);
 //getting data to generate graph end 
 
 
?>

<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>

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
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-home"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Items	</span>
              <span class="info-box-number"><?php echo @mysql_num_rows(@mysql_query("SELECT `id` FROM `".TBL_INV_ITEMS."` where status='1' AND id_shop='".addslashes($_SESSION['shop'])."'  "));?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-book"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Steward</span>
              <span class="info-box-number"><?php echo @mysql_num_rows(@mysql_query("SELECT a.`id` FROM `".TBL_ATTRIBUTES."` as a   where a.id_shop='".addslashes($_SESSION['shop'])."'  and a.status = '1' AND a.table_name ='".'steward'."' "));?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <!--<div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-bed"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Room Type</span>
              <span class="info-box-number"><?php echo @mysql_num_rows(@mysql_query("SELECT `id` FROM `".TBL_ROOM_TYPE."` where status='1' AND id_shop='".addslashes($_SESSION['shop'])."'"));?></span>
            </div>
            
          </div>
        
        </div>-->
        <!-- /.col -->
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Users</span>
              <span class="info-box-number"><?php echo @mysql_num_rows(@mysql_query("SELECT `id` FROM `".TBL_USERS."` where id_shop='".addslashes($_SESSION['shop'])."' AND status='1' ".$hotel_access));?></span>
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
            <form method="post" accept="" style="text-align: center;">
              <!--date input-->
              <div class="form-group col-md-2 col-md-offset-1 col-sm-12 col-xs-12 " style="margin-top:15px;">
                <div class="input-group">
                  <select id="preDate" class="form-control">
                    <option value="01-04-2017 to 31-03-2018" > FY 2017-2018</option>
                    <option value="01-04-2018 to 31-03-2019"  > FY 2018-2019</option>
                    <option value="01-04-2019 to 31-03-2020" selected="selected">FY 2019-2020</option>
                    <option value="01-04-2018 to 30-09-2018" >Apr-2018 to Sep-2018</option>
                    <option value="01-10-2018 to 31-03-2019" >Oct-2018 to Mar-2019</option>
                    <option value="01-04-2019 to 30-09-2020" >Apr-2019 to Sep-2020</option>                    
                    <option value="01-10-2019 to 31-03-2020" >Oct-2019 to Mar-2020</option>
                  </select>  
                </div>
               </div>


              <div class="form-group col-md-3 col-sm-12" style="margin-top:15px;">
                <div class="input-group">
                  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                    <input type="text" class="form-control pull-right dateRangeEdit"  placeholder="Enter Checkin date" name="reservation_date" id="reservation_date" data-parsley-required value="<?php if(isset($_REQUEST['reservation_date'])) echo $_REQUEST['reservation_date'];?>" data-parsley-errors-container="#reservation_dateError"  automcomplete="off">
                  </div>
               </div>
               <!--date input end-->
               <!--Hotel select list-->
               <div class="col-md-3  col-sm-12" style="margin-top:15px;">
                  <div class="form-group ">
                    <?php 
                        $listOutlet = '<select class="form-control select2 " name="id_outlet">
                                             <option value="0">All Outlet</option>';
                                            $resCat = selectSql(mst_outlets," where id_shop='".$_SESSION['shop']."' AND  status = '1' and outlettype='1' ",'');
									  if($db->num_rows2($resCat)){
										while($resultCat = $db->fetch_object2($resCat)){
											if(isset($_REQUEST['id_outlet']) && $_REQUEST['id_outlet']!="" && trim($_REQUEST['id_outlet']) == $resultCat->id){
												$selected = 'selected="selected"';
											}else{
												$selected = '';
											}
											$listOutlet .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
										}
									  }
										echo $listOutlet .= '</select>';
										
				

                       ?>
                      </div>
                      
                  </div>
                  <!--hotel select list end-->
                 <!--search button-->
                  <div class="form-group col-md-3 col-sm-12" style="margin-top:15px;" >
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
                <div class="col-md-6" >
                  <p class="text-center">
                    <strong class="col-lg-offset-2">Item Sales (Month Wise) : <?php echo date("d-M-Y",strtotime($start))?> - <?php echo date("d-M-Y",strtotime($end))?></strong>
                    <div class="row"> 
                      <div class="col-md-3 col-lg-offset-4 col-sm-offset-3 col-sm-3 col-xs-2 col-xs-offset-4 col-xs-2 col-md-offset-4 text-center" style="background-color: #87CEFA;">
                          Budget
                      </div>
                      <div class="col-md-3 col-sm-3 col-xs-3 text-center" style="background-color: #3b8bba ;">
                          Achieved 
                      </div>
                    </div>
                  </p>

                  <a  href="monthWiseReport.php?flash_date=<?php echo $_REQUEST['reservation_date']; ?>&hotel_access=<?php echo $list->hotel_access; ?>"><div class="chart ">
                      <canvas id="saleChart" style="height: 250px;"></canvas>
                  </div></a>
                  </div>
                <!-- /.col -->
                <div class="col-md-6 ">
                  <p class="text-center">
                    <strong class="col-lg-offset-2">Revenue (Month Wise in lacs) : <?php echo date("d-M-Y",strtotime($start))?> - <?php echo date("d-M-Y",strtotime($end))?></strong>
                    <div class="row"> 
                      <div class="col-md-3 col-sm-3 col-xs-2 col-sm-offset-3 col-md-offset-4 col-xs-offset-4 text-center" style="background-color: #DCD0FF;">
                          Budget
                      </div>
                      <div class="col-md-3 col-sm-3 col-xs-3 text-center" style="background-color: #8e44ad ;">
                          Achieved 
                      </div>
                  </p>
                  <br>
                  <a  href="monthWiseRevenue.php?flash_date=<?php echo $_REQUEST['reservation_date']; ?>&hotel_access=<?php echo $list->hotel_access; ?>"><div class="chart col-sm-7 col-md-7">
                    <!--sales Chart Canvas -->
                    <canvas id="saleChartValue" style="height: 250px;"></canvas>
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
            <form method="post" accept="" style="text-align: center;">
              <!--date input-->
              <div class="form-group col-md-3 col-sm-12 col-md-offset-2" style="margin-top:15px;">
                <input autocomplete="off"  type="text" class="form-control pickerdate" placeholder="Enter start date" id="pace_date" name="flash_date" value="<?php if($_POST['flash_date']) echo $_POST['flash_date'];else echo date('d-m-Y'); ?>"   data-parsley-required>
        <?php echo $err_start_date;?>
               </div>
               <!--date input end-->
               <!--Hotel select list-->
               <div class="col-md-3" style="margin-top:15px;">
                  <div class="form-group">      
                     <?php 
                        $listOutlet = '<select class="form-control select2 " name="id_outlet_md">
                                             <option value="0">All Outlet</option>';
                                            $resCat = selectSql(mst_outlets," where id_shop='".$_SESSION['shop']."' AND  status = '1' and outlettype='1' ",'');
									  if($db->num_rows2($resCat)){
										while($resultCat = $db->fetch_object2($resCat)){
											if(isset($_REQUEST['id_outlet_md']) && $_REQUEST['id_outlet_md']!="" && trim($_REQUEST['id_outlet_md']) == $resultCat->id){
												$selected = 'selected="selected"';
											}else{
												$selected = '';
											}
											$listOutlet .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
										}
									  }
										echo $listOutlet .= '</select>';
										
				

                       ?>
                      </div>
                      
                  </div>
                  <!--hotel select list end-->
                 <!--search button-->
                  <div class="form-group col-md-3 col-sm-12" style="margin-top:15px;">
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

                 <div class="col-md-12">
                   <p class="text-center">
                      <strong>Item Sales (Today | MTD | YTD) On <?php echo date("d-M-Y",strtotime($current_date))?></strong>
                      <div class="row"> 
                        <div class="col-md-3 col-lg-offset-4 col-sm-3 col-xs-3 col-xs-offset-4  col-sm-offset-3 " style="background-color: #3ee096;">
                            Last Year
                        </div>
                        <div class="col-md-3 col-xs-3 col-xs-3" style="background-color: #00a65a ;color:white;">
                            This Year 
                        </div>
                      </div>
                    </p>
                 </div>
                 

                <div class="col-md-4">                   
                    <a  href="mtdytdReport.php?mtd=1&flash_date=<?php echo $current_date; ?>&Download=Generate&hotel_access=<?php echo $list->hotel_access1; ?>">
                        <div class="chart col-lg-2 col-md-2 col-sm-2 col-xs-2" style="float:left;">
                          <canvas id="todayChart" style="height: 150px;"></canvas>
                        </div>
                      </a>
                </div>

                <div class="col-md-4">
                  <a  href="mtdytdReport.php?mtd=1&flash_date=<?php echo $current_date; ?>&Download=Generate&hotel_access=<?php echo $list->hotel_access1; ?>">
                        <div class="chart  col-lg-2 col-md-2 col-sm-2 col-xs-2" style="float:left;">
                          <canvas id="mtdChart" style="height: 150px;"></canvas>
                        </div>
                      </a>
                </div>  
                <div class="col-md-4">
                  <a  href="mtdytdReport.php?mtd=1&flash_date=<?php echo $current_date; ?>&Download=Generate&hotel_access=<?php echo $list->hotel_access1; ?>">
                        <div class="chart  col-lg-2 col-md-2 col-sm-2 col-xs-2" style="float:left;">
                          <canvas id="ytdChart" style="height: 150px;"></canvas>
                        </div>
                      </a>  
                </div>
                </div>    
                  <!-- /.chart-responsive -->
                <!-- /.col -->
                <div class="col-md-6">

                  <div class="col-md-12">
                    <p class="text-center">
                      <strong>Revenue (Today | MTD | YTD in Lacs) On <?php echo date("d-M-Y",strtotime($current_date))?></strong>
                      <div class="row"> 
                        <div class="col-md-3 col-sm-3 col-xs-3 col-lg-offset-4 col-sm-offset-3 col-xs-offset-4 " style="background-color: #48d0f2; color: white;">
                            Last Year
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 " style="background-color: #02aad3 ;color:white;">
                            This Year 
                        </div>
                    </p>
                  </div>                 
                  <div class="col-md-4">
                    <a  href="mtdytdReport.php?mtd=2&flash_date=<?php echo $current_date ?>&Download=Generate&hotel_access=<?php echo $list->hotel_access1; ?>">
                      <div class="chart  col-lg-2 col-md-2 col-sm-2 col-xs-2">
                        <canvas id="todayChartValue" style="height: 150px;"></canvas>
                      </div>
                    </a>
                  </div>
                  <div class="col-md-4">
                    <a  href="mtdytdReport.php?mtd=2&flash_date=<?php echo $current_date ?>&Download=Generate&hotel_access=<?php echo $list->hotel_access1; ?>">
                      <div class="chart  col-lg-2 col-md-2 col-sm-2 col-xs-2">
                        <canvas id="mtdChartValue" style="height: 150px;"></canvas>
                      </div>
                    </a>
                  </div> 
                  <div class="col-md-4">
                    <a  href="mtdytdReport.php?mtd=2&flash_date=<?php echo $current_date ?>&Download=Generate&hotel_access=<?php echo $list->hotel_access1; ?>">
                      <div class="chart  col-lg-2 col-md-2 col-sm-2 col-xs-2">
                        <canvas id="ytdChartValue" style="height: 150px;"></canvas>
                      </div>
                    </a>
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
          
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
   
<?php include_once("../includes/footer.php")?>
<script type="text/javascript">
  $("#preDate").change(function(){
    var date = $(this).val();
    $("#reservation_date").val(date);
  });
</script>