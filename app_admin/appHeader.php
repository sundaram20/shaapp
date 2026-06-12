<?php
include_once('../config/fron_autoload.php');

if(isset($_SESSION['app_user_name']) && isset($_SESSION['app_id_user'])){
	//Access Granted
}else{
	header('Location:../appAdmin.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>APP CONTROL SYSTEM</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
	<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
	<!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
	<script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>
	<style type="text/css">
		.container{
			padding-top:30px;
		}
		.appNav{
			border:1px solid #252525;
			text-align: center;
			padding: 20px 0px 20px 0px;
			font-size:1.2em;
			background: #36a2eb;
		}
		.appNav:hover{
			background: #252525;
			font-weight: bold;
			color:yellow;
		}
		a{
			text-decoration: none;
			color:#252525;
		}
		.loader {
		  border: 16px solid #f3f3f3; /* Light grey */
		  border-top: 16px solid #3498db; /* Blue */
		  border-radius: 50%;
		  width: 50px;
		  height: 50px;
		  animation: spin 2s linear infinite;
		}

		@keyframes spin {
		  0% { transform: rotate(0deg); }
		  100% { transform: rotate(360deg); }
		}
	</style>
</head>
<body>
	<div class="container">
			<a href="appIndex.php"><div class="col-md-1 appNav">Home</div></a>
			<a href="appMigrations.php"><div class="col-md-2 appNav">Migrations</div></a>
			<a href="appModule.php"><div class="col-md-2 appNav">Modules</div></a>
			<a href="appMenu.php"><div class="col-md-1 appNav">Menus</div></a>
			<a href="appSubMenu.php"><div class="col-md-2 appNav">Sub Menus</div></a>
			<a href="appUserProfile.php"><div class="col-md-2 appNav">Profile</div></a>
			<a href="appLogout.php"><div class="col-md-2 appNav">Logout</div></a>
	</div>

		
