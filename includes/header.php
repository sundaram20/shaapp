<!DOCTYPE html>
<html >
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  
  
  <?php
	
	header("Expires: Tue, 15 Feb 2023 16:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
	
	
	$session1=$_GET['submenu'];
	if($session1==''){
		$session=$_GET['session'];
		$fun=currentNavigation_s($session);
	}else{
		$session=$_GET['submenu'];
		$fun=currentNavigation_id($session);
	}

  

  ?>
  
  <title>RoomStatusHUB | <?php echo $fun['submenu']; ?></title>
  <link rel="icon" href="<?php echo $SITE_URL; ?>/images/fevicon.png" type="image/x-icon">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/plugins/bootstrap/dist/css/bootstrap.min.css"/>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/plugins/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/plugins/fontawesome2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/plugins/fontawesome2/css/fontawesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/plugins/Ionicons/css/ionicons.min.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/plugins/iCheck/all.css">
   <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/plugins/select2/dist/css/select2.min.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/plugins/jvectormap/jquery-jvectormap.css">
   <!-- Pace style -->
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/plugins/pace/pace.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/plugins/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/plugins/dist/css/skins/_all-skins.min.css"> 
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/css/jquery-ui.min.css" />  
  <!-- daterange picker -->
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/plugins/bootstrap-daterangepicker/daterangepicker.css">
	
	 <!--owl css-->
    <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/pos/css/owl.css" />  
	
	
  <!-- bootstrap datetimepicker -->
  <link href="<?php echo $SITE_URL; ?>/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/css/style.css<?php echo '?'.mt_rand(); ?>"> 
  <!-- fullCalendar -->
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/plugins/fullcalendar/dist/fullcalendar.min.css">
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/plugins/fullcalendar/dist/fullcalendar.print.min.css" media="print">
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/css/jquery.dataTables.min.css" media="print"> 
  
    <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/css/sweetalert.css">  
  <script type="text/javascript" src="<?php echo $SITE_URL; ?>/js/jquery.min.js"></script>
   <script type="text/javascript" src="<?php echo $SITE_URL; ?>/js/moment.min.js"></script>
   <script type="text/javascript" src="<?php echo $SITE_URL; ?>/js/daterangepicker.min.js"></script>
   <link rel="stylesheet" type="text/css" href="<?php echo $SITE_URL; ?>/css/daterangepicker.css" /> 
   
   <!-- Calender Ui Style Sheets -->
 <link rel="stylesheet" href="https://unpkg.com/@fullcalendar/core@4.4.0/main.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/@fullcalendar/timeline@4.4.0/main.min.css">   
    <link rel="stylesheet" href="https://unpkg.com/@fullcalendar/resource-timeline@4.4.0/main.min.css">
     <script src="https://unpkg.com/@fullcalendar/core@4.4.0/main.min.js"></script>
    <script src="https://unpkg.com/@fullcalendar/interaction@4.4.0/main.min.js"></script>
    <script src="https://unpkg.com/@fullcalendar/timeline@4.4.0/main.min.js"></script>
    <script src="https://unpkg.com/@fullcalendar/resource-common@4.4.0/main.min.js"></script>
    <script src="https://unpkg.com/@fullcalendar/resource-timeline@4.4.0/main.min.js"></script>
  <!-- End -->


  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  

</head>
<?php  $resLogo  =  selectColumn(TBL_SHOP,'image'," WHERE `id` = '".addslashes($_SESSION['shop'])."'"); ?>
<body class="hold-transition skin-blue sidebar-mini  pace-done sidebar-collapse" >
<div class="wrapper" >
  <header class="main-header">
    <!-- Logo -->
	  <?php
	 // print_r($_SESSION);
	  if($_SESSION['database']=='hip' || $_SESSION['database']=='krk-ban' || $_SESSION['database']=='krk-gf' || $_SESSION['database']=='krk-chn'  || $_SESSION['database']=='krk-mum'  || $_SESSION['database']=='TT001'  || $_SESSION['database']=='TT002'  || $_SESSION['database']=='krk-chn') {
					 $DfileName	=	"/dashboard.php";
					
					
					}else{
					$DfileName	=	"/favourites.php";
					
				}
	  ?>
    <a href="<?php echo $SITE_URL.$DfileName; ?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>R</b>sh</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Room</b>StatusHUB</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
		
		
		
		
      <!-- Sidebar toggle button-->
		
		
		
		
		
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
       <div class="navbar-custom-menu" style="
    display: flex;
    align-items: center;
">
		  
		  
		   <div id="auditDate" style="font-size: 1.4rem; border: 1px solid #fff; border-radius: 5px; padding: 0.35rem 0.5rem;color : #fff;">
       <?php
            $sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
            $numRowsNightAudit =  mysqli_num_rows($sqlNightAudit);
            $rowNightAudit =  mysqli_fetch_object($sqlNightAudit);
            $Dated = date('d-m-Y',strtotime('+1 day',strtotime($rowNightAudit->dated)));
        ?>
       Date Open : <b> <?php echo $Dated ?> </b>
      </div>
		  
		  
        <ul class="nav navbar-nav">

        		<?php 
		if((file_exists($_SERVER['DOCUMENT_ROOT'].'/uploaded_files/shop/'.$resLogo )) && $resLogo !=''){  
		  
			
			$imageLLogo=$SITE_URL.'/uploaded_files/shop/'.$resLogo;
		  
		  ?>  
		  
	<?php 	}else{
		$imageLLogo='';
		}

?>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo $imageLLogo; ?>" class="user-image" alt="User Image">
              <span class="hidden-xs">Hello!  <?php echo $_SESSION['userName'];?>&nbsp;&nbsp;&nbsp;</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo $imageLLogo; ?>" class="img-circle" alt="User Image">
                <p>
                  Welcome  <?php echo $_SESSION['userName'];?> 
                  <small> <?=dateFormat_date(currenDateTime());?></small>
				   <small> <?=selectColumn(TBL_SHOP,'name'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");?></small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-6 text-center">
                    <a href="javascript:void(0);">  You IP address:</a>
                  </div>
                  <div class="col-xs-6 text-center">
                    <a href="javascript:void(0);"> <?=$_SERVER['REMOTE_ADDR'];?></a>
                  </div>                 
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?php echo $SITE_URL; ?>/master/userProfile.php" class="btn btn-default btn-flat"><i class="fa fa-user-circle"></i> Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo $SITE_URL; ?>/process.php?process=secureLogout" class="btn btn-default btn-flat">Sign out <i class="fa fa-sign-out"></i></a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
         <!-- <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>-->
        </ul>
      </div>

    </nav>
  </header>

