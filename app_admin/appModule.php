<?php
include_once('appHeader.php');

if(isset($_REQUEST['update'])){
	if($_REQUEST['column']=='color')
		$_REQUEST['value']='#'.$_REQUEST['value'];

	$updateData['id_app_user_modified_by']=$_SESSION['app_id_user'];
	$updateData['last_modified']=date('Y-m-d H:i:s');

	$updateData[$_REQUEST['column']]=$_REQUEST['value'];
	if(updateData(APP_MODULE,$updateData,'id='.$_REQUEST['id'].' ',$appConnect)){
		echo "<script>alert('Record Updated')</script>";
	}
}

if(isset($_POST['name'])){
	$_POST['id_app_user_created_by']=$_SESSION['app_id_user'];
	$_POST['id_app_user_modified_by']=$_SESSION['app_id_user'];
	
	insertData(APP_MODULE,$_POST,$appConnect);
}
?>

<div class="container">
	<div class="col-md-12" style="padding: 10px 10px 10px 10px;border: 1px solid black; ">
		<form id="moduleForm" enctype="multipart/form-data" method="post" action="" class="text-center">
			<div class="row">
				<div class="col-md-3">
					<div class="input-group ">
						<label for="">Name</label>
						<input name="name" id="name" class="form-control" placeholder="POS" type="text" required="required">
					</div>
				</div>
				<div class="col-md-2">
					<div class="input-group ">
						<label for="">Icon Class</label>
						<input name="icon" id="icon" class="form-control" type="text" placeholder="fa-plane" required="required">
					</div>
				</div>
				<div class="col-md-2">
					<div class="input-group ">
						<label>Colour</label>
						<input  name="color" placeholder="#252525" required="required" type="text" id="color" class="form-control">
					</div>
				</div>
				<div class="col-md-2">
					<div class="input-group ">
						<label>Display Order</label>
						<input  name="display_order"  required="required" type="number" id="display_order" min="0" value="0" class="form-control">
					</div>
				</div>
				<div class="col-md-3">
					<div class="input-group ">
						<label>&nbsp;</label>
						<input type="submit"  value="Add Module" class="form-control btn btn-info">
					</div>
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-12" style="margin-top: 20px;padding: 10px 10px 10px 10px;border:1px solid black;">
		<h3>List Of Modules</h3>
		<table id="moduleTable">
			<thead>
				<th>S.No</th>
				<th>Name</th>
				<th>Icon class</th>
				<th>Colour Code</th>
				<th>Display Order</th>
				<th>Status</th>
				<th>last update at</th>
				<th>last Update by</th>
			</thead>
			<tbody>
				<?php
					$sql="SELECT * FROM ".APP_MODULE." order by display_order";
					$res = mysqli_query($appConnect,$sql);
					$sno =1;
					while($row = mysqli_fetch_object($res)){
						echo'<tr><td>'.$sno++.'</td>
							<td><input onchange="updateMe(\'name\','.$row->id.',this.value);" class="form-control" name="name" value="'.$row->name.'" ></td>
							<td><input onchange="updateMe(\'icon\','.$row->id.',this.value);" class="form-control" value="'.$row->icon.'"</td>
							<td><input onchange="updateMe(\'color\','.$row->id.',this.value);" class="form-control" value="'.str_replace('#','',$row->color).'"</td>
							<td><input onchange="updateMe(\'display_order\','.$row->id.',this.value);" class="form-control" value="'.$row->display_order.'"</td>
							<td><input onchange="updateMe(\'status\','.$row->id.',this.value);" class="form-control" value="'.$row->status.'"</td>
							<td>'.$row->last_modified.'</td>
							<td>'.selectField(APP_USERS,'name','WHERE id="'.$row->id_app_user_modified_by.'" ',$appConnect).'</td>
							</tr>';
				 } ?>
			</tbody>
		</table>
	</div>		
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$("#moduleTable").DataTable();
	});

	function updateMe(column,id,value){
		window.location.href='appModule.php?update=1&column='+column+'&id='+id+'&value='+value;
	}
</script>
<?php
include_once('appFooter.php');
?>
