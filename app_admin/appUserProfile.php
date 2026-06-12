<?php
include_once('appHeader.php');

if(isset($_POST['submit'])){
	$updateData['name']=trim($_POST['name']);
	$updateData['user_name']=trim($_POST['username']);
	$updateData['password']=trim($_POST['password']);
	$res = updateData(APP_USERS,$updateData,' id="'.$_SESSION['app_id_user'].'" ',$appConnect);
	if($res)
		header('LOCATION:../appAdmin.php');

}
?>
<div class="container">
	<div class="col-md-4 col-md-offset-4">
		<form action="" method="post" class="form">
			<label>Name</label>
			<input required="required" type="text" name="name" class="form-control" value="<?php echo selectField(APP_USERS,'name','WHERE id="'.$_SESSION['app_id_user'].'"',$appConnect)?>">

			<label>User Name</label>
			<input required="required" type="text" name="username" class="form-control" value="<?php echo selectField(APP_USERS,'user_name','WHERE id="'.$_SESSION['app_id_user'].'"',$appConnect)?>">
			<label>Password</label>
			<input required="required" type="password" name="password" class="form-control" value="<?php echo selectField(APP_USERS,'password','WHERE id="'.$_SESSION['app_id_user'].'"',$appConnect)?>"><br>
			<input name="submit" type="submit" class="btn btn-warning form-control" value="UPDATE">
		</form>
	</div>
</div>
<?php
include_once('appFooter.php');
?>