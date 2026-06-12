<?php
include_once('appHeader.php');

if(isset($_REQUEST['update'])){
	if($_REQUEST['column']=='color')
		$_REQUEST['value']='#'.$_REQUEST['value'];

	$updateData['id_app_user_modified_by']=$_SESSION['app_id_user'];
	$updateData['last_modified']=date('Y-m-d H:i:s');

	$updateData[$_REQUEST['column']]=$_REQUEST['value'];
	
	if(updateData(APP_SUB_MENU,$updateData,'id='.$_REQUEST['id'].' ',$appConnect)){
		echo "<script>alert('Record Updated')</script>";
	}
}

if(isset($_POST['name'])){
	$_POST['id_app_user_created_by']=$_SESSION['app_id_user'];
	$_POST['id_app_user_modified_by']=$_SESSION['app_id_user'];

	insertData(APP_SUB_MENU,$_POST,$appConnect);
}
?>

<div class="container">
	<div class="col-md-12" style="padding: 10px 10px 10px 10px;border: 1px solid black; ">
		<form id="moduleForm" enctype="multipart/form-data" method="post" action="" class="text-center">
			<div class="row">
				<div class="col-md-2">
					<div class="input-group ">
						<label for="">Module</label>
						<?php
						 $sql="SELECT * FROM ".APP_MODULE." order by display_order";
						 $res = mysqli_query($appConnect,$sql);
						?>
						<select  name="id_module" id="id_module" class="form-control select2" placeholder=""  required="required">
							<?php
								while($rowTop = mysqli_fetch_object($res)){
									echo '<option value="'.$rowTop->id.'">'.$rowTop->name.'-'.$rowTop->id.'</option>';
								}
							?>
						</select>	
					</div>
				</div>
				<div class="col-md-2">
					<div class="input-group ">
						<label for="">Menu</label>
						<?php
						 $sql="SELECT * FROM ".APP_MENU." order by display_order";
						 $res = mysqli_query($appConnect,$sql);
						?>
						<select  name="id_menu" id="id_menu" class="form-control select2" placeholder=""  required="required">
							<?php
								while($rowTop = mysqli_fetch_object($res)){
									echo '<option value="'.$rowTop->id.'">'.$rowTop->name.'-'.$rowTop->id.'</option>';
								}
							?>
						</select>	
					</div>
				</div>
				<div class="col-md-2">
					<div class="input-group ">
						<label for="">Name</label>
						<input name="name" id="name" class="form-control" placeholder="Reports" type="text" required="required">
					</div>
				</div>

				<div class="col-md-2">
					<div class="input-group ">
						<label for="file_name">File Name</label>
						<input name="file_name" id="file_name" class="form-control" placeholder="manageOrders.php" type="text" required="required">
					</div>
				</div>
				
				
				<div class="col-md-2">
					<div class="input-group ">
						<label>Display Order</label>
						<input  name="display_order"  required="required" type="number" id="display_order" min="0" value="0" class="form-control">
					</div>
				</div>
				<div class="col-md-1">
					<div class="input-group ">
						<label>&nbsp;</label>
						<input type="submit"  value="Add Sub Menu" class="form-control btn btn-info">
					</div>
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-12" style="margin-top: 20px;padding: 10px 10px 10px 10px;border:1px solid black;">
		<h3>List Of Sub Menus</h3>
		<table id="moduleTable">
			<thead>
				<th>S.No</th>
				<th>Name</th>
				<th>Module</th>
				<th>Menu</th>
				<th>File Name</th>
				<th>Display Order</th>
                <th>Type(2 for report)</th>
				<th>Status</th>
				<th>last update at</th>
				<th>last Update by</th>
			</thead>
			<tbody>
				<?php
					$sql="SELECT * FROM ".APP_SUB_MENU." order by id_module,id_menu,display_order";
					$res = mysqli_query($appConnect,$sql);
					$sno =1;
					while($row = mysqli_fetch_object($res)){
						echo'<tr><td>'.$sno++.'</td>
							<td>'.$row->name.' <input onchange="updateMe(\'name\','.$row->id.',this.value);" class="form-control" name="name" value="'.$row->name.'" ></td>

							<td>'.selectField(APP_MODULE,'name','WHERE id='.$row->id_module.' ').'<input onchange="updateMe(\'id_module\','.$row->id.',this.value);" class="form-control" name="name" value="'.$row->id_module.'" ></td>

							<td>'.selectField(APP_MENU,'name','WHERE id='.$row->id_menu.' ').'<input onchange="updateMe(\'id_menu\','.$row->id.',this.value);" class="form-control" name="name" value="'.$row->id_menu.'" ></td>
							<td>'.$row->file_name.' <input onchange="updateMe(\'file_name\','.$row->id.',this.value);" class="form-control" name="name" value="'.$row->file_name.'" ></td>

							<td><input onchange="updateMe(\'display_order\','.$row->id.',this.value);" class="form-control" value="'.$row->display_order.'"</td>
							<td><input onchange="updateMe(\'type\','.$row->id.',this.value);" class="form-control" value="'.$row->type.'"</td>
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
		window.location.href='appSubMenu.php?update=1&column='+column+'&id='+id+'&value='+value;
	}
</script>
<?php
include_once('appFooter.php');
?>
