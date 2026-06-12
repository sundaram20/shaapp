<?php 
session_start();
unset($_SESSION['database']);
include("config/data.config.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/msgs.inc.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin | Log in</title>
  <!-- Tell the browser to be responsive to screen width ---->
  <!---->

  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
   <link rel="icon" href="<?php echo $SITE_URL; ?>/images/fevicon.png" type="image/x-icon">
    <link rel='stylesheet' href='<?php echo $SITE_URL; ?>/css/netdna.css'>

  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/plugins/bootstrap/dist/css/bootstrap.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/plugins/fontawesome2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/plugins/fontawesome2/css/fontawesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/plugins/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/plugins/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/plugins/iCheck/square/blue.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <script language="javascript" type="text/javascript">
	/* this is just a simple reload; you can safely remove it; remember to remove it from the image too */
	function reloadCaptcha(){

    
    
		document.getElementById('captcha').src = document.getElementById('captcha').src+ '?' +new Date();
 
	}
</script>
<script src='https://www.google.com/recaptcha/api.js'></script>
<style>
	input:-webkit-autofill,input:-webkit-autofill:hover,input:-webkit-autofill:focus,input: -webkit-autofill:active{
      -webkit-box-shadow : none;
	}
@-webkit-keyframes autofill{
  0% , 100% {
  	background-color: transparent;
  }
}

	  .nav-tabs {
    background-color:#0e6aa7;
  }
  .nav-tabs li a{
    color:#fff;
    border-radius:0px;
    font-size:11px;
  }
  .nav-tabs>li.active>a, .nav-tabs>li.active>a:hover, .nav-tabs>li.active>a:focus {
    color: #fff;
    cursor: default;
    background-color:#cc6529;
    border: none;
    border-bottom-color: transparent;
}
.nav>li>a:hover, .nav>li>a:focus {
    text-decoration: none;
    background-color:#0d5381;
    color:#fff;

}
.p-tag{
  margin-top:60px;
  padding:50px 100px;
  padding-left:0;
}
/**/
.tab-content{
  margin-top:100px;
}
.fs-16{
font-size:14px;
text-align:justify;
margin-top:46px;
}
.o-color{
	color:#ed7d31;

}
.login-box-body{
  margin-top:90px;
}
.head{
	margin-left:15px;
	font-weight:bold;
}
body{
	background-color: #fff!important;
}
.d-flex{
	
}
.has-feedback .form-control {
    padding-right: 32.5px;
    box-shadow: none;
    border-radius: 0;
}
.has-feedback span{
  position: absolute;
}
</style>
</head>
<body class="hold-transition login-page"> 
 
<div class="row">
  <div class="col-md-8 col-xs-12 mobile-dn">
  <div class="loginright">
      <a href="index.php"><img style="width: 215px;height: auto;" src="room.png"></a>
      <!--<img src="logo.png">-->
    </div> 
	<h2 class="head">Our Core <span class="o-color"><i>Products</span></i></h2>
	<!---tab carousel start--->
	 <div id="tab-carousel">
      <ul class="nav nav-tabs">
        <li class="active">
          <a href="#C_R_S">Central Reservation Software</a>
        </li>
        <li>
          <a href="#S_T_M_S">Sales Team Management Software</a>
        </li>
        <li>
          <a href="#B_E">Booking Engine</a>
        </li>
        <li>
          <a href="#P_M_S">Property Management Software</a>
        </li>
        <li>
          <a href="#E_F_D_S">Easy Food Delivery Software</a>
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="C_R_S">
          <div col="row">
            <div class="col-md-7">
              <div class="img-box">
                <img src="images/crs-lap.png" class="img-responsive" alt="">
              </div>


            </div>
            <div class="col-md-5">
              <div class="img-box">
                <img src="images/crs-product.png" class="img-responsive" alt="">
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="S_T_M_S">
          <div col="row">

            <div class="col-md-7">
              <div class="img-box">
                <img src="images/sales-lap.png" class="img-responsive" alt="">
              </div>

            </div>
            <div class="col-md-5">
              <div class="img-box">
                <img src="images/sales-team-key.png" class="img-responsive" alt="">
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="B_E">
          <div col="row">
            <div class="col-md-7">
              <div class="img-box">
                <img src="images/be-lap.png" class="img-responsive" alt="">
              </div>
            </div>
            <div class="col-md-5">
              <div class="img-box">
                <img src="images/be-key.png" class="img-responsive" alt="">
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="P_M_S">
          <div col="row">
          
            <div class="col-md-7">
              <div class="img-box">
                <img src="images/property-lap.png" class="img-responsive" alt="">
              </div>

            </div>
            <div class="col-md-5">
              <div class="img-box">
                <img src="images/property-key.png" class="img-responsive" alt="">
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="E_F_D_S">
          <div col="row">
            <div class="col-md-7">
              <div class="img-box">
                <img src="images/efd.png" class="img-responsive" alt="">
              </div>
            </div>

            <div class="col-md-5">
              <div class="img-box">
                <img src="images/fod-key.png" class="img-responsive" alt="">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div><!--tab carousel end-->

   
	
 </div>

 <div class="col-md-4 col-xs-12 login-right">
<div class="login-logo" >
	    <a href="index.php" style="color:#fff;"><img class="img-responsive" src="room.png"></a>
	    <!--<img src="logo.png">-->
	  </div>
	<div class="login-box">
	  
	  <!-- /.login-logo -->
	  <div class="login-box-body">
	  	 <h4 class="login-box-msg text-light"> <!-- <a href="index.php" style="color:#fff;"><img style="height:30px;" src="c3-wh.png"></a>-->Welcome to RoomStatusHUB 
	    </h4>
	    <p class="login-box-msg text-light">Sign in to start your session
	    </p>
		<div class="form-group has-error">
		<?php 
				if($_SESSION['errorMsg']){?>
	                  <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
	                  <?php unset($_SESSION['errorMsg']);
				}elseif($_SESSION['successMsg']){?>
	              	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
	               <?php unset($_SESSION['successMsg']);
				}?>
		</div>
        
        <!---  sdfsd--->
				 
                 
		<form name="form1" action="process.php" method="post">
			<input type="hidden" value="secureLogin" name="process" />
	        <input type="hidden" value="submit" name="submit" />
	      <div class="form-group has-feedback">
	        <input type="text" class="form-control" placeholder="Enter Username" name="username" id="username">
	        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
	      </div>
	      <div class="form-group has-feedback">
	        <input type="password" class="form-control" placeholder="Password" name="password" id="password">
	        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
	      </div>
	      <div class="form-group has-feedback">
	        <input type="text" required class="form-control" placeholder="Enter Corporate Code" name="shopCode" id="shopCode">
	        <span class="glyphicon glyphicon-home form-control-feedback"></span>
	      </div>
	      <br>
		  	   	 
		    <div required="required" class="g-recaptcha" data-sitekey="6LeNrygeAAAAAMrem2HRWCDeDu4Pxm5cttH_qKWb"></div> 
		    <?php echo $test="";

				?>	
		 <br>
	      <div class="row mt-4">        
	        <!-- /.col -->
	        <div class="col-md-12">
	          <button type="submit"  class="btn  o-btn">Sign In</button>
	        </div>
	        <!-- /.col -->
	      </div>
	    </form>
			
	    <!-- /.social-auth-links -->

	  <?php /*?>  <a href="#">I forgot my password</a><br>
	    <a href="register.html" class="text-center">Register a new membership</a><?php */?>

	  </div>
	  <!-- /.login-box-body -->
	  <div class="suprt">
	  	<i class="far fa-envelope"></i><a href="mailus:support@roomstatushub.com"> support@roomstatushub.com</a><br>
          	<i class="fas fa-headset"></i><a href="tel:8929432759"> 8929432759,</a>
          <a href="tel: 8929432758"> 8929432758</a>
	  	</div>
	</div>
	<!-- /.login-box -->
</div>
<!---end of col--->
</div>
	<!--End of row-->

<!-- jQuery 3 -->
<!--<script src="<?php echo $SITE_URL; ?>/plugins/jQuery/dist/jquery.min.js"></script>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo $SITE_URL; ?>/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->

<script src="<?php echo $SITE_URL; ?>/plugins/iCheck/icheck.min.js"></script>
<script >
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
<script>
// default bootstrap click, apenas muda com ação do utilizador
//$('#myTab a').click(function (e) {
//  e.preventDefault()
//  $(this).tab('show')
//})

// Tab-Pane change function
    var tabChange = function(){
        var tabs = $('.nav-tabs > li');
        var active = tabs.filter('.active');
        var next = active.next('li').length? active.next('li').find('a') : tabs.filter(':first-child').find('a');
        // Bootsrap tab show, para ativar a tab
        next.tab('show')
    } //comment
    // Tab Cycle function
    var tabCycle = setInterval(tabChange, 10000)
    // Tab click event handler
    $(function(){
        $('.nav-tabs a').click(function(e) {
            e.preventDefault();
            // Parar o loop
            clearInterval(tabCycle);
            // mosta o tab clicado, default bootstrap
            $(this).tab('show')
            // Inicia o ciclo outra vez
            setTimeout(function(){
                tabCycle = setInterval(tabChange, 10000)//quando recomeça assume este timing
            }, 10000);
        });
    });
</script>
<?php

if(isset($_POST['submit']))
{

function CheckCaptcha($userResponse) {
        $fields_string = '';
        $fields = array(
            'secret' => '6LeNrygeAAAAAO5R9EWfbJws43R4Ays8zLJfCWq5',
            'response' => $userResponse
        );
        foreach($fields as $key=>$value)
        $fields_string .= $key . '=' . $value . '&';
        $fields_string = rtrim($fields_string, '&');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);

        $res = curl_exec($ch);
        curl_close($ch);

        return json_decode($res, true);
    }


    // Call the function CheckCaptcha
    $result = CheckCaptcha($_POST['g-recaptcha-response']);

    if ($result['success']) {
        //If the user has checked the Captcha box
        echo "Captcha verified Successfully";
	
    } else {
        // If the CAPTCHA box wasn't checked
       echo '<script>alert("Error Message");</script>';
    }
}
  
   

   

  
      
    ?>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
  <script src='<?php echo $SITE_URL; ?>/js/netdna.js'></script>
</body>
</html>