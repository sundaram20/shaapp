<?php 
include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_PACKAGE_LINKING,'add');
?>

<?php
    if($_REQUEST['saveForm']=='Save'){
       
       for($i=0;$i<count($_REQUEST['id_hotel']);$i++){

          $chkExsiting = selectColumn(TBL_PACKAGE_LINKING,'id','WHERE id_shop="'.$_SESSION['shop'].'" AND id_hotel='.$_REQUEST['id_hotel'][$i].' AND id_room='.$_REQUEST['id_room'][$i].' AND id_plan='.$_REQUEST['id_rate_plan'][$i].' '); 

          if($chkExsiting > 0){
            //skip insertion
          }  
          else{
            $insertSql = "INSERT INTO ".TBL_PACKAGE_LINKING." 
                        SET id_shop='".$_SESSION['shop']."',
                        id_hotel='".$_REQUEST['id_hotel'][$i]."',
                        id_room='".$_REQUEST['id_room'][$i]."',
                        id_plan='".$_REQUEST['id_rate_plan'][$i]."',
                        status='1' "; 

            $insertSql .= ",last_modified='".date('Y-m-d H:i:s')."',
                        date_created='".date('Y-m-d H:i:s')."',
                        id_mst_user_created_by='".$_SESSION['userId']."',
                        id_mst_user_modified_by='".$_SESSION['userId']."' ";
            mysqli_query($connNew,$insertSql);            
          }
          
                      
                   
        } 

        $_SESSION['successMsg']='Data Saved Successfully';
        header('LOCATION:manageLinkPackages.php');                        
    }
    else if($_REQUEST['saveForm']=='Edit'){

            $updateSql = "UPDATE  ".TBL_PACKAGE_LINKING." SET
                        id_hotel='".$_REQUEST['id_hotel'][0]."',
                        id_room='".$_REQUEST['id_room'][0]."',
                        id_plan='".$_REQUEST['id_rate_plan'][0]."',
                        status='".$_REQUEST['status']."' "; 

            $updateSql .= ",last_modified='".date('Y-m-d H:i:s')."',
                           id_mst_user_modified_by='".$_SESSION['userId']."' 
                           WHERE id='".encryptor(decrypt,$_REQUEST['eId'])."' ";
            

            mysqli_query($connNew,$updateSql);               
        $_SESSION['successMsg']='Data Updated Successfully';
        header('LOCATION:manageLinkPackages.php');
    }
    
?>

<?php include_once("../includes/header.php")?>

<?php include_once("../includes/left.php")?>

<?php
    if($_REQUEST['action']=='Edit'){
        
        $editSql = "SELECT * FROM ".TBL_PACKAGE_LINKING." WHERE id=".encryptor(decrypt,$_REQUEST['eId'])." ";
        $resEdit = mysqli_query($connNew,$editSql);
        $row = mysqli_fetch_object($resEdit);
    }
?>




<div class="content-wrapper">
    <!-- Content Header (Page header) -->
	
	<section class="content-header">
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
        <?php echo '<span style="color:'.currentNavigation()['color'].'">&nbsp;<i class="fa '.currentNavigation()['icon'].'"></i> '.currentNavigation()['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>
      <?php echo breadCrumbs(); ?>
    </section>
	
  <!--  <section class="content-header">
      <h1>
        Booking Engine
        <small>Manage Room Package Links</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i>Booking Engine</a></li>
        <li class="active">Manage Room Package Links</li>
      </ol>
    </section>  -->
    <!-- Main content -->
    <section class="content">		
	<div class="box box-success">
	 <div class="form-group has-error" align="center">
		<?php if($_SESSION['errorMsg']){?>
		 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
		<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
		<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
		<?php unset($_SESSION['successMsg']);}?>
		</div>
		
		<div class="box-header with-border">
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation()['submenu']; ?> : <span style="color:#3c8dbc"> <?php 
			  
			  $resHotel1 = selectSql(TBL_HOTELS," WHERE  id='".$row->id_hotel."' ") ;
                if($db->num_rows2($resHotel1)){
                    $rowHotel1 = $db->fetch_object2($resHotel1);
					echo $rowHotel1->name ;
				}
			  ?> </span>
            </div>
        
        <!-- /.box-header -->
		<form name="addForm" id="addForm" action="" method="post">
          <input type="hidden" >  
        <div class="box-body" id="rowGrid">
          <div class="row" >
            <div class="col-md-3">
              <div class="form-group">
        <label for="hotel">Hotel<font color="#FF0000">*</font></label>
        	<?php $categoryDropDown = '<select class="form-control select2" onchange="fetchRoomsForHotel(this.value,\'id_room\');"  required name="id_hotel[]" id="id_hotel">
				<option value="">Select Hotel</option>';
				$resHotel = selectSql(TBL_HOTELS," WHERE `status` = '1' AND id_shop='".$_SESSION['shop']."' ",' ORDER BY `name`');
				if($db->num_rows2($resHotel)){
				  	while($rowHotel = $db->fetch_object2($resHotel)){
						if($row->id_hotel == $rowHotel->id){
							$selected = 'selected="selected"';
						}else{
							$selected = '';
						}
						$categoryDropDown .= '<option '.$selected.' value="'.$rowHotel->id.'">'.ucfirst($rowHotel->name).'</option>';
					}
				}
				echo $categoryDropDown .= '</select>';
			?>
        
         </div>
            </div>

            <!---- Rooms-->
            <div class="col-md-3">
              <div class="form-group">
        		<label for="room">Room<font color="#FF0000">*</font></label>
        		<select  required class="form-control select2" onchange="fecthRatePlan('id_rate_plan');"  name="id_room[]" id="id_room">
        			<option value="">Select Room</option>
        		</select>
			       
         	</div>
            </div>
            
            <!--plans-->
            <div class="col-md-3">
              <div class="form-group">
        		<label for="room">Rate Plan<font color="#FF0000">*</font></label>
        		<select required  class="form-control select2" name="id_rate_plan[]" id="id_rate_plan">
        		</select>
			       
         	</div>
            </div>
            <!-- from to date -->
            <?php if($_REQUEST['action']!='Edit'){ ?>
            <div class="col-md-3">
              <div class="form-group">
                <label for="room">Add More</label>
                <input onclick="addNewGrid();" type="button" value="Add" class="form-control btn btn-success">
                </select>
                   
            </div>
            </div>
            <?php } ?>

          </div>
          <!-- /.row -->
          <?php if($_REQUEST['action']=='Edit'){ 
                $createdBy=selectColumn(TBL_USERS,'name','WHERE id='.$row->id_mst_user_created_by.' ');
                $modifiedBy=selectColumn(TBL_USERS,'name','WHERE id='.$row->id_mst_user_modified_by.' ');

            ?>
            <hr width="100%">
          <div class="row">
              <div class="col-md-3">
              <div class="form-group">
                <label for="created">Created By</label>
                <input type="text" disabled class="form-control" value="<?php echo $createdBy ;?>">
                   
            </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="room">Modified By</label>
                <input type="text" disabled class="form-control" value="<?php echo $modifiedBy ;?>">
                   
            </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="room">Created On</label>
                <input type="text" disabled class="form-control" value="<?php echo $row->date_created ;?>">
                   
            </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label for="room">Modified On</label>
                <input type="text" disabled class="form-control" value="<?php echo $row->last_modified ;?>">
                   
            </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <?php
                  if($row->status==1)
                    $active="checked='checked'";
                  elseif($row->status==0)
                    $inactive="checked='checked'";
                  else
                    $active="checked='checked'";

                ?>
                <label for="room">Status:</label>&nbsp;&nbsp; 
                 <input <?php echo $active; ?> type="radio"  class="form-control flat-red" value="1" name="status">&nbsp; <label for="room">Active</label>  &nbsp;           
                 <input <?php echo $inactive; ?> type="radio"  class="form-control flat-red" value="0" name="status">&nbsp; 
                <label for="room">Inactive</label>
                   
            </div>
            </div>
          </div>
          <?php } ?>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
        <input name="saveForm" id="saveForm" type="submit" class="btn btn-primary" value="<?php echo ($_REQUEST['action']!='Edit'?'Save':'Edit') ;?>" />
        <a href="manageLinkPackages.php" class="btn btn-warning">Cancel</a>
        <span id="loadMe" style="display: none;color: red;" >Wait Uploading</span>
        </div>
		</form>		
      
    </section>
    
    <!-- /.content -->
  </div>
<script type="text/javascript">var dayExtend=0; </script>                                   
<?php include_once("../includes/footer.php")?>  

<script type="text/javascript">
    var gridNo=1;
	function addNewGrid(){
        
        var grid ='<div class="row" id="grid'+gridNo+'" ><div class="col-md-3"><div class="form-group"><label for="hotel">Hotel<font color="#FF0000">*</font></label><select class="form-control select2" onchange="fetchRoomsForHotel(this.value,\'id_room'+gridNo+'\');"  required name="id_hotel[]" id="id_hotel">'+$("#id_hotel").html()+'</select></div></div>           <div class="col-md-3">              <div class="form-group">                <label for="room">Room<font color="#FF0000">*</font></label>                <select  required class="form-control select2" onchange="fecthRatePlan(\'id_rate_plan'+gridNo+'\');"  name="id_room[]" id="id_room'+gridNo+'">                    <option value="">Select Room</option></select></div></div>       <div class="col-md-3">           <div class="form-group">                <label for="room">Rate Plan<font color="#FF0000">*</font></label>                <select required  class="form-control select2" name="id_rate_plan[]" id="id_rate_plan'+gridNo+'">                </select></div>           </div>      <div class="col-md-3">              <div class="form-group">                <label for="room">Remove Row</label>                <input onclick="removeGrid('+gridNo+');" type="button" value="Remove" class="form-control btn btn-danger">                </select>         </div>            </div>  </div>';

        $('#rowGrid').append(grid); 
        gridNo++;
    }

    function removeGrid(id){
        $('#grid'+id).remove();
    }

    var chkEdit = '<?php echo $_REQUEST["action"] ;?>';
    if(chkEdit=='Edit'){
        fetchRoomsForHotel('<?php echo $row->id_hotel; ?>','id_room','<?php echo $row->id_room ;?>');
        fecthRatePlan('id_rate_plan','<?php echo $row->id_plan; ?>')
    }
</script>