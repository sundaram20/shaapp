<?php

include_once('appHeader.php');

if(isset($_REQUEST['update'])){
	if($_REQUEST['column']=='color')
		$_REQUEST['value']='#'.$_REQUEST['value'];

	$updateData['id_app_user_modified_by']=$_SESSION['app_id_user'];
	$updateData['last_modified']=date('Y-m-d H:i:s');

	$updateData[$_REQUEST['column']]=$_REQUEST['value'];
	if(updateData(APP_SHOP,$updateData,'id='.$_REQUEST['id'].' ',$appConnect)){
		echo "<script>alert('Record Updated')</script>";
	}
}

if(isset($_POST['shop_code'])){
	$_POST['id_app_user_created_by']=$_SESSION['app_id_user'];
	$_POST['id_app_user_modified_by']=$_SESSION['app_id_user'];
	$_POST['module_access']=implode(',',$_POST['module_access']);

	insertData(APP_SHOP,$_POST,$appConnect);
}
?>

<div class="container">
	<div class="col-md-12" style="padding: 10px 10px 10px 10px;border: 1px solid black; ">
		<form id="moduleForm" enctype="multipart/form-data" method="post" action="" class="text-center">
			<div class="row">
				<div class="col-md-3">
					<div class="input-group ">
						<label for="">Module(s)</label>
						<?php
						 $sql="SELECT * FROM ".APP_MODULE." order by display_order";
						 $res = mysqli_query($appConnect,$sql);
						?>
						<select multiple="multiple" name="module_access[]" id="ids_module" class="form-control select2" placeholder=""  required="required">
							<?php
								while($rowTop = mysqli_fetch_object($res)){
									echo '<option value="'.$rowTop->id.'">'.$rowTop->name.'-'.$rowTop->id.'</option>';
								}
							?>
						</select>	
					</div>
				</div>
				<div class="col-md-3">
					<div class="input-group ">
						<label for="">Shop Code</label>
						<input name="shop_code" id="shop_code" class="form-control" placeholder="FERN" type="text" required="required">
					</div>
				</div>
				<div class="col-md-3">
					<div class="input-group ">
						<label for="">Database</label>
						<input name="`database`" id="database" class="form-control" type="text" placeholder="fa-plane" required="required">
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
		<h3>List Of Shops</h3>
		<table id="moduleTable">
			<thead>
				<th>S.No</th>
				<th>Shop Code</th>
				<th>Database</th>
				<th>Modules Access</th>
				<th>Status</th>
				<th>last update at</th>
				<th>last Update by</th>
			</thead>
			<tbody>
				<?php
					$sql="SELECT * FROM ".APP_SHOP." order by id desc";
					$res = mysqli_query($appConnect,$sql);
					$sno =1;
					while($row = mysqli_fetch_object($res)){
						echo'<tr><td>'.$sno++.'</td>
							<td>'.$row->shop_code.'<input onchange="updateMe(\'shop_code\','.$row->id.',this.value);" class="form-control" name="name" value="'.$row->shop_code.'" ></td>
							<td>'.$row->database.'<input onchange="updateMe(\'database\','.$row->id.',this.value);" class="form-control" value="'.$row->database.'"</td>
							<td><input onchange="updateMe(\'module_access\','.$row->id.',this.value);" class="form-control" value="'.str_replace('#','',$row->module_access).'"</td>
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
		window.location.href='appIndex.php?update=1&column=`'+column+'`&id='+id+'&value='+value;
	}
</script>
<?php
include_once('appFooter.php');
?>
