<?php include_once("../config/auto_loader.php");

if($_REQUEST['eId']==''){
  checkUserLevelPermission($_SESSION['userLevel'],TBL_USER_PERMISSIONS,'add');
}
else{
  checkUserLevelPermission($_SESSION['userLevel'],TBL_USER_PERMISSIONS,'update');
}

?>
<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
	
	<section class="content-header">
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
        <?php echo '<span style="color:'.currentNavigation()['color'].'">&nbsp;<i class="fa '.currentNavigation()['icon'].'"></i> '.currentNavigation()['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>
      <?php echo breadCrumbs(); ?>
    </section>
	
	
    
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
             <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation()['submenu']; ?> : <span style="color:#3c8dbc"> <?php 
			 
			 $sqlUserLevels1="SELECT * FROM ".TBL_USER_LEVELS." WHERE  id = '".encryptor(decrypt,$_REQUEST['eId'])."' ";
				  	$resUserLevels1 = mysqli_query($connNew,$sqlUserLevels1);
					$rowUserLevel1 = mysqli_fetch_object($resUserLevels1);
			 echo $rowUserLevel1->name; 
			 ?> 
			 </span>
            </div>
            <!-- /.box-header -->
            <!-- form start -->     
			    
			 <form name="form1" id="permission_form"  method="post" enctype="multipart/form-data">
        <?php
          $eId=''; 
          if(isset($_REQUEST['eId'])){
            $disabled="disabled='disabled'";
            $eId = encryptor(decrypt,$_REQUEST['eId']);
          ?>
        <input type="text" name="submenu_s" id="submenu_s" hidden="" >

			  <input type="hidden" value="<?php echo $eId;?>" name="eId" />
			  <input type="hidden" value="<?php echo $eId;?>" name="perid" id="perid" />
			  
			  <input type="hidden" value="<?php echo encryptor(decrypt,$_REQUEST['eId']);?>" name="id_user_level" />
        <?php }?>

					<div class="form-group has-error">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div>
              <div class="box-body">
                <div class="col-md-4">
                  <label for="userlevelId">User Level<font color="#FF0000">*</font></label>
				 <?php
				  	$sqlUserLevels="SELECT * FROM ".TBL_USER_LEVELS." WHERE id_shop='".$_SESSION['shop']."' AND status=1 AND id !=1 ";
				  	$resUserLevels = mysqli_query($connNew,$sqlUserLevels);

				 ?>	
					<select <?php echo $disabled; ?> class="select2 form-control" required="" name="id_user_level" id="id_user_level" onchange="fetchModule(this.value);">
						<option value="">---Select User Level---</option>
						<?php
							while($rowUserLevel = mysqli_fetch_object($resUserLevels)){

                if($rowUserLevel->id==$eId)
                    $selectedLvl = "selected='selected'";
                else
                    $selectedLvl = "";

								echo '<option '.$selectedLvl.' value="'.$rowUserLevel->id.'" >'.$rowUserLevel->name.'</option>';
							}
						?>
					</select>

                </div>

				 <div class="col-md-4">
                  <label for="module">Module <font color="#FF0000">*</font></label>
                   <select required="" onchange="fetchModulesMenu(this.value);" class="select2 form-control" name="id_module" id="id_module">
                   		<option value="">---Select Module---</option>
                   </select>
                </div>
				
				 <div class="col-md-4">
                  <label for="module">Menu <font color="#FF0000">*</font></label>
                   <select required="" onchange=" fetchModulesSubMenu(this.value);" class="select2 form-control" name="id_menu" id="id_menu">
                   		<option value="">---Select Menu---</option>
                   </select>
                </div>
               
                	
               
				
				           
              </div>
              <div id="subMenuGrid" class="box-body">
                		
              </div>	
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='Update' class="btn btn-primary" name="Update" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Close' class="btn btn-danger" onclick='location.replace("manageUserPermissions.php"); '>
          <input type='button' value='Audit Trail' class="btn btn-success"  onclick="audittrial(this.value);" style="float:right">  
			 </div>
            </form>			
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>	



<!-- Audit Trail Modal -->
<div class="modal fade" id="auditModal" tabindex="-1" role="dialog" aria-labelledby="auditModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
               <!-- <h4 class="modal-title" id="roomtypeModalLabel">Rooms Select</h4>  -->
                <label class="modal-title" id="roomtitle1" style="font-size:22px;">Audit Trail</label>
            </div>
            <div class="modal-body" style="overflow-y: scroll; max-height:100%;height:250px ">
                <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Details</th>   
          </tr>
        </thead>
        
        <tbody id="roombutton">
          
        </tbody>
      </table>
            </div>
      
            <div class="modal-footer"  style="background-color: #e4e4e4;color: #fff;text-align:center">
               <button type="button" class="btn btn-danger" data-dismiss="modal"> <span class="glyphicon glyphicon-off"></span> Close</button> 
            </div>
     </form>
        </div>
    </div>
</div>
<!-- End Audit trail Modal -->
  
  <script type="text/javascript">

  function audittrial(clicked_value){ 
var id = document.getElementById("perid").value;
    $('#auditModal').modal('show');
    var form_name ='Manage Permissions';
    $.ajax({
      url: "../functions/ajaxAuditTrail.php",
        type: 'POST',
        data: 'form_name='+form_name+'&id='+id,
        dataType: "JSON",
        success: function(data) {
        // alert(data);
        $('#roombutton').html(data);
      }
     });
  }
  
  
</script>

  <script type="text/javascript">

    <?php
      if($eId!=''){
    ?>
    fetchModule($("#id_user_level").val());
  <?php } ?>

  	function fetchModule(id_user_level){
  		$.ajax({
  			url:'ajax/ajaxUserLevelModuleAccess.php',
  			method:'POST',
  			data:'id_user_level='+id_user_level,
  			success:function(data){
  				$("#id_module").html(data);
  				},
  		})
  	}

  	function fetchModulesMenu(id_module){
  		$.ajax({
  			url:'ajax/ajaxUserLevelMenuAccess.php',
  			method:'POST',
  			data:'id_module='+id_module,
  			success:function(data){
  				$("#id_menu").html(data);
          fetchModulesSubMenu('');
			},
		})
  	}

  	function fetchModulesSubMenu(id_menu){
  		$.ajax({
  			url:'ajax/ajaxUserLevelSubMenuAccess.php',
  			method:'POST',
  			data:'id_menu='+id_menu+'&id_module='+$("#id_module").val()+'&id_user_level='+$("#id_user_level").val(),
  			success:function(data){
  				//console.log(data);
  				$("#subMenuGrid").html(data);
			},
		})
  	}

 	function checkAllPer(val,checkThis){
     if($('#'+val).prop("checked")==true){
      
      $('.'+checkThis).prop('checked', true);
     }
     else{
      $('.'+checkThis).prop('checked', false);
      
     }
  }
 	
  $("#permission_form").submit(function(e){
    e.preventDefault();
    $.ajax({
      url:'ajax/ajaxUpdatePermission.php',
      type:'post',
      data:$(this).serialize(),
      success:function(data){
        //console.log(data);
        document.getElementById("submenu_s").value = '';
        alert(data);
      },
      complete:function(data){

      }
    })
  })

  function submenuget(clicked_id){
    var res = clicked_id.split("_");
    var submenu =  document.getElementById("submenu_s").value;   
    var res = submenu.concat(res[2]+',');
   
    document.getElementById("submenu_s").value = res;
    
  }

  </script>						
<?php include_once("../includes/footer.php")?>