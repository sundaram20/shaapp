<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_PURCH,'view');
?>
<?php include_once("../includes/header.php")?>
  <?php include_once("../includes/left.php")?>
  <?php 
  include_once("include/function.php");
  if($_REQUEST['Download'] == 'Generate'){
	$Date =$_REQUEST['datefilter'];
	$id_outlet=implode(',',$_REQUEST['id_outlet']);
	$id_shift=implode(',',$_REQUEST['id_shift']);

	 settlementSummaryReport($Date,$id_outlet,$id_shift);
	 
  }?>
    <style>
  .ranges{padding: 9px !important;}
 .daterangepicker .ranges li:hover {background-color:#08c !important;}
  </style>
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
	
      	
   <?php  $session=$_GET['submenu']; ?>
    <section class="content-header">
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
		<?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>
      <?php echo breadCrumbs(); ?>
    </section>
	
	
    <section class="content">
      <div class="row">
        <div class="col-xs-12"> 
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              
              <small class="text-center has-error">
              <?php if($_SESSION['errorMsg']){?>
              <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
              <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
              <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
              <?php unset($_SESSION['successMsg']);}?>
              </small>
              </div>
            <?php 
			
			$filename = 'userReport'; //your file name

    $objPHPExcel = new PHPExcel();
    /*********************Add column headings START**********************/
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'username')
                ->setCellValue('B1', 'city_name');

    /*********************Add data entries START**********************/
//get_result_array_from_class**You can replace your sql code with this line.

$num_row = 1;

  $user_name = 'kl;kjl';
  $c_code = 'ijop';
  $num_row++;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$num_row, $user_name )
                ->setCellValue('B'.$num_row, $c_code );


    /*********************Autoresize column width depending upon contents START**********************/
    foreach(range('A','B') as $columnID) {
        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
    }
    $objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);



//Make heading font bold

        /*********************Add color to heading START**********************/
        $objPHPExcel->getActiveSheet()
                    ->getStyle('A1:B1')
                    ->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('99ff99');

        $objPHPExcel->getActiveSheet()->setTitle('userReport'); //give title to sheet
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;Filename=$filename.xls");
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');?>
            
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
