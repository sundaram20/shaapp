<?php
include_once('appHeader.php');
?>

<div class="container">
	<div class="col-md-12" style="padding: 10px 10px 10px 10px;border: 1px solid black; ">
		<form id="migrationForm" enctype="multipart/form-data" action="" class="text-center">
			<div class="row">
				<div class="col-md-3">
					<div class="input-group ">
						<label for="">Migration Title</label>
						<input name="title" id="title" class="form-control" placeholder="column added" type="text" required="required">
					</div>
				</div>
				<div class="col-md-4">
					<div class="input-group ">
						<label for="">Migration Description</label>
						<input name="description" id="description" class="form-control" type="text" placeholder="user_name in users table default value is none" required="required">
					</div>
				</div>
				<div class="col-md-2">
					<div class="input-group ">
						<label>Upload Latest File</label>
						<input accept=".php" name="migrationFile" required="required" type="file" value="Choose" class="form-control">
					</div>
				</div>
				<div class="col-md-2">
					<div class="input-group ">
						<label>&nbsp;</label>
						<input type="submit" value="Migrate" class="form-control btn btn-info">
					</div>
				</div>
				<div  class="col-md-1">
					<div style="display: none;" class="loader"></div>
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-12" style="margin-top: 20px;padding: 10px 10px 10px 10px;border:1px solid black;">
		<input type="button" onClick="downloadMigration();" value="Dowload Latest File" class="btn btn-success pull-right">
		<h3>List Of Migrations</h3>
		<table id="migrationTable">
			<thead>
				<th>S.No</th>
				<th>Title</th>
				<th>Description</th>
				<th>Migrated At</th>
				<th>Migrated By</th>
			</thead>
			<tbody>
				<?php
					$sql="SELECT * FROM ".APP_MIGRATIONS." order by id desc";
					$res = mysqli_query($appConnect,$sql);
					$sno =1;
					while($row = mysqli_fetch_object($res)){
						echo'<tr><td>'.$sno++.'</td>
							<td>'.$row->title.'</td>
							<td>'.$row->description.'</td>
							<td>'.$row->migrated_at.'</td>
							<td>'.selectField(APP_USERS,'name','WHERE id="'.$row->id_app_user.'" ',$appConnect).'</td></tr>';
				 } ?>
			</tbody>
		</table>
	</div>		
</div>

<script type="text/javascript">
	$(document).ready( function () {
	    $('#migrationTable').DataTable();
	} );

	$("#migrationForm").submit(function(e){
		e.preventDefault();
		
		let text='<strong><ul style="text-align:left"><li>Made changes in the latest file.</li><li>Query is added to the correct location</li><li>Query is thoroughly checked before migration</li><li>In case of new table,Entry is made into the <code>data.constant.php</code> and same is updated on the server</li><li>It is not containing <code>DROP</code> or <code>DELETE</code> command</li><li>Selected File is <code>.php</code> extension</li></ul></strong>';

		Swal.fire({
			  title: 'Are you sure?',
			  html:'<h4>You wan\'t be able to revert this change !</h4><h5>Following points have been taken in considaration:</h5>'+text+'',
			  type: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Yes, Migrate it!'
			}).then((result) => {
			  if (result.value) {
			    $('.loader').show();
			  	$.ajax({
			  		type:'POST',
			  		url:'makeMigration.ajax.php',
			  		data:new FormData(this),
			  		contentType : false,
			  		processData : false, 
			  		success:function(data){
			  		
			  		$('.loader').hide();
			  			Swal.fire(
			  			  'Migration',
			  			  ''+data+'',
			  			  'warning'
			  			).then(function(){
			  				location.reload();
			  			});
			  		}
			  	})
			  }
			});
	});

	function downloadMigration(){
		window.open('downloadMigration.php');
	}
</script>
<?php
include_once('appFooter.php');
?>
