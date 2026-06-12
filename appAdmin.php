<?php
session_start();
session_destroy();
unset($_SESSION['app_user_name']);
unset($_SESSION['app_password']);
?>

<html>
<head>
	<title>App Admin Login</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<style>

		.formDiv{
			padding: 10px 10px 10px 10px;
			background: #fff;
			border-radius: 15px;
			top:200;
			-webkit-box-shadow: 5px 5px 15px 5px #000000; 
			box-shadow: 5px 5px 15px 5px #000000;
		}
		body{
			background: #4e54c8;
			background: -webkit-linear-gradient(to right, #8f94fb, #4e54c8);
			background: linear-gradient(to right, #8f94fb, #4e54c8);
		}

		#loginError{
			text-align: center;
			margin-top: 5px;
		}

	</style>
</head>
<body>
	<div class="container ">
		<div class="row ">
			<div class="col-md-12 "> 
				<div class="col-md-4 col-md-offset-4 formDiv" >
					<form  action="" id="form" method="post" >
						<div class="col-md-12 text-center"><h3>APP LOGIN</h3></div>
						<div class="col-md-12 form-group">
							<label>User Name</label>
							<input required="required" name="app_user_name" id="app_user_name" type="text" class="form-control"  placeholder="Enter User Name...">
						</div>
						<div class="col-md-12 form-group">
							<label>Password</label>
							<input required="required" name="app_password" id="app_password"  type="password" class="form-control" autocomplete="off" placeholder="Enter Password...">
						</div>
						
						<div class="col-md-12">
							<input  class="btn btn-success form-control" value="LOGIN" type="submit">
						</div>
						<div class="col-md-12"  id="loginError">
							
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
<script type="text/javascript">
	$('#form').submit(function(e){
		e.preventDefault();
		$.ajax({
			type:'POST',
			url:'app_admin/loginCheck.ajax.php',
			data:'user_name='+$('#app_user_name').val()+'&password='+$('#app_password').val(),
			success:function(data){
				$('#loginError').html(data);	

				setTimeout(
					function(){
				    	window.location.href = 'app_admin/appIndex.php';
				    },
				    1000
				);	
			}
		})
	});
</script>
</html>