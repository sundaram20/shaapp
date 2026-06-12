<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_OFFER_MASTER,'add');
$image_display_path=$image_path = $UPLOAD_FILES.'/offers/';



if($_REQUEST['Save']=='Save'){
	if(isset($_REQUEST['effective_date'])){
		$dateArr=explode(' to ',$_REQUEST['effective_date']);
		
	}

	/*$chk=selectColumn(TBL_OFFER_MASTER,'id','WHERE id_shop="'.$_SESSION['shop'].'" AND offer_type="'.$_REQUEST['offer_type'].'" AND  "'.date('Y-m-d',strtotime($dateArr[0])).'" BETWEEN valid_from AND valid_till ');
	


	if($chk>0){
		$_SESSION['successMsg']='Offer Already Exists.Kindly Use edit option';
		header('LOCATION:manageOfferMaster.php');
		exit;
	}*/
		
	

	$inclusions = array();
	if($_REQUEST['additionalInc']){
		$inclusions=$_REQUEST['additionalInc'];
	}

	$sql="INSERT INTO ".TBL_OFFER_MASTER." 
		  SET id_shop='".$_SESSION['shop']."',
		  offer_type='".$_REQUEST['offer_type']."',
		  offer_name='".$_REQUEST['offer_name']."',
		  image='".$_REQUEST['offer_image']."',
		  remarks='".$_REQUEST['remarks']."',
		  valid_from='".date('Y-m-d',strtotime($dateArr[0]))."',
		  valid_till='".date('Y-m-d',strtotime($dateArr[1]))."',
		  min_stay='".$_REQUEST['min_stay']."',
		  advance_days='".$_REQUEST['valid_before']."',
		  ids_additional_inclusion='".implode(',',$inclusions)."',
		  display_at_home='".$_REQUEST['display_home']."',
		  display_order='".$_REQUEST['display_order']."',
		  id_mst_user_created_by='".$_SESSION['userId']."',	
		  id_mst_user_modified_by='".$_SESSION['userId']."',
		  date_created='".date('Y-m-d H:i:s')."',
		  last_modified	='".date('Y-m-d H:i:s')."',
		  status='".$_REQUEST['status']."'
		   ";
	mysqli_query($connNew,$sql);
	$_SESSION['successMsg']='Offer Added Successfully';
	header('LOCATION:manageOfferMaster.php');	   
	exit;
}
else if($_REQUEST['Save']=='Edit'){



	$inclusions = array();
	if($_REQUEST['additionalInc']){
		$inclusions=$_REQUEST['additionalInc'];
	}

	if(isset($_REQUEST['effective_date'])){
		$dateArr=explode(' to ',$_REQUEST['effective_date']);
		$updateValidity = 'valid_from="'.date('Y-m-d',strtotime($dateArr[0])).'",valid_till="'.date('Y-m-d',strtotime($dateArr[1])).'",';
	}

	$sql="UPDATE ".TBL_OFFER_MASTER." 
		  SET offer_name='".$_REQUEST['offer_name']."',
		  image='".$_REQUEST['offer_image']."',
		  remarks='".$_REQUEST['remarks']."',
		  min_stay='".$_REQUEST['min_stay']."',
		  advance_days='".$_REQUEST['valid_before']."',
		  ".$updateValidity."
		  ids_additional_inclusion='".implode(',',$inclusions)."',
		  display_at_home='".$_REQUEST['display_home']."',
		  display_order='".$_REQUEST['display_order']."',
		  id_mst_user_modified_by='".$_SESSION['userId']."',
		  last_modified	='".date('Y-m-d H:i:s')."',
		  status='".$_REQUEST['status']."'
		  WHERE id='".encryptor(decrypt,$_REQUEST['eidHide'])."'
		   ";
	   
	mysqli_query($connNew,$sql);

	$_SESSION['successMsg']='Offer Updated Successfully';
	
	header('LOCATION:manageOfferMaster.php');	   
	exit;	   
}

if(isset($_REQUEST['eId'])){
	$sql="SELECT * FROM ".TBL_OFFER_MASTER." WHERE id='".encryptor(decrypt,$_REQUEST['eId'])."' ";
	$res=mysqli_query($connNew,$sql);
	$row=mysqli_fetch_object($res);

	

	$dayExtendOf = date('d-m-Y',strtotime($row->valid_from)).' to '.date('d-m-Y',strtotime($row->valid_till));

	$chkId=selectColumn(TBL_OFFER,'id','WHERE id_offer_master="'.$row->id.'" ');

	if($chkId>0){
		$disabled='disabled="disabled"';
	}
}

?>

<?php include_once("../includes/header.php");?>

<?php include_once("../includes/left.php");?>

<div class="content-wrapper">  
	<!-- offer Image modal--> 
	<div class="modal fade" id="adminsform" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <form id="offerImageForm" name="offerImageForm"   enctype="multipart/form-data">
	      <input name="id" type="hidden" class="form-control">
	      <input type="hidden" name="formType" value="forOfferImage">
		  <input name="image" type="hidden" class="form-control">	  
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" id="modal-close" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title">Update Image</h4><span id="uploadTxt" style="color:red;display:none;">Uploading Please Wait...</span>
	        </div>
	        <div class="modal-body">
	          <div class="row"> 
			       		             
	            <!--<div class="col-xs-12 col-md-12">
	              <div class="form-group">
	                <label>Display Title<font color="#FF0000">*</font> </label>
	                <input  name="caption" class="form-control" >
	              </div>
	            </div>
	            <div class="col-xs-12 col-md-12">
	              <div class="form-group">
	                <label>Short Description<font color="#FF0000">*</font></label>
	                <input  name="short_description" class="form-control">
	              </div>
	            </div>
	            <div class="col-xs-12 col-md-12">
	              <div class="form-group">
	                <label>Display Order<font color="#FF0000">*</font></label>
	                <input required="required" type="number" name="order_display" class="form-control">
	              </div>
	            </div> -->
	            <div class="col-xs-12 col-md-12">
	              <div class="form-group">
	                <label>Recommended Photo Size<small>(400 x 300)</small></label>
	                <input type="file" id="photomain" name="photo" />
	              </div>
	            </div>      
	                          				  
				<!--<div class="col-xs-12 col-md-4">
				  <label for="date_created">Date Created</label>
				  <input type="text" disabled="disabled" class="form-control" id="date_created" name="date_created">				
				</div> 
					
				<div class="col-xs-12 col-md-4">
				  <label for="last_modified">Last Updated</label>
				  <input type="text" disabled="disabled" class="form-control" id="last_modified" name="last_modified">				
				</div> 
					
				<div class="col-xs-12 col-md-4">
				  <label for="last_modified_by">Last Updated By</label>
				   <?php $sqlUserDetail = selectColumn(TBL_USERS,'user_name','where id="'.$row->id_mst_user_modified_by.'" ');?>
				  <input type="text" name="id_mst_user_modified_by" id="id_mst_user_modified_by" disabled="disabled" class="form-control"   >				
				</div>-->
				
											
	          </div>
	        </div>
	        <div class="modal-footer">
	          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	          <button type="submit" class="btn btn-primary" name="SaveGal" value="1">Save</button>
	        </div>
	      </div>
	    </form>
	  </div>
	</div>
	<!-- offer image End--> 

<section class="content-header">
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
        <?php echo '<span style="color:'.currentNavigation()['color'].'">&nbsp;<i class="fa '.currentNavigation()['icon'].'"></i> '.currentNavigation()['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>
      <?php echo breadCrumbs(); ?>
    </section>
    <!-- <section class="content-header">
      <h1>
        Booking Engine
        <small>Offer Master</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i>Booking Engine</a></li>
        <li class="active">Offer Master</li>
      </ol>
    </section> -->
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
					echo $row->offer_name; 
			  ?> </span>
            </div>
			
			
			
			<div class="box-body">	
				<form enctype='multipart/form-data' name="searchForm" id="searchForm" action="" method="get" action="" >
					<input type="hidden" name="eidHide" value="<?php echo $_REQUEST['eId']; ?>">
					<div class="row">
						<div class="col-md-4">
						    <div class="form-group">
						  		<label for="offer_type">Offer Type</label>
							    <?php
							        $visibility='style="visibility: hidden;"';
							        $visibilityPromo='style="visibility: hidden;"';
							        
							        if($row->offer_type==1)
							            $selected='selected="selected"';
							        else if($row->offer_type==2)
							            $selected1='selected="selected"';
							        else if($row->offer_type==3){
							            $selected2='selected="selected"'; 
							            $visibility='style="visibility: visible;"';     
							        }
							        else if($row->offer_type==4){
							            $selected3='selected="selected"'; 
							            $visibilityPromo='style="visibility: visible;"';
							        }
							    ?>
							  <select <?=$disabled; ?>  class="form-control select2" required name="offer_type" on id="offer_type" onchange="inputDisplay(this.value);">
							     <option value="">---Select Offer Type---</option>
							     <option <?=$selected; ?> value="1">Special Offer </option>
							     <option <?=$selected1;?> value="2">Package Deal</option>
							     <option <?=$selected2;?> value="3">Advance Purchase</option>
							     <!--<option <?=$selected3;?> value="4">Promo Code Offer</option>-->               
							  </select>     
							</div>
						</div>
						<div class="col-md-4">
						    <div class="form-group">
						  		<label for="offer_name">Offer Name</label>
							    <input required="" class="form-control" type="text" name="offer_name" value="<?php echo $row->offer_name; ?>" placeholder="Enter offer Name..." />
							</div>
						</div>
						<!-- from to date -->
						<div class="form-group col-sm-4">
						    <label for="effective_date">Valid From - Valid Till </label>
						    <div class="input-group">
						        <div class="input-group-addon">
						         <i class="fa fa-calendar"></i> 
						        </div>

						        <input <?php echo $disabled; ?>  type="text" class="form-control pull-right dateRangeOffer" id="effective_date" name="effective_date" data-parsley-required value="<?php echo $dayExtendOf ;?>" data-parsley-errors-container="#effective_dateError"  autocomplete="off" >
						    </div>
						        <!-- /.input group -->
						    <span id="effective_dateError"></span>
						</div>
						<div class="col-md-4">
						    <div class="form-group">
						  		<label  for="remarks">Remarks</label>
							    <textarea required="" style="resize: vertical;" rows="1" class="form-control" cols="6" name="remarks" placeholder="Enter remarks..."><?php echo $row->remarks?></textarea> 
							</div>
						</div>
						<div class="col-md-2">
						<div class="form-group">
						    <label for="offer_type">Additional Inclusion</label>
						    <select name="additionalInc[]" class="form-control select2" multiple="multiple">
						        <option value="">Select Inclusion</option>
						    <?php
						        $sql="SELECT id,name FROM mst_rate_inclusion WHERE status=1 AND id_shop=2 and type=2";

						        $resInc=mysqli_query($connNew,$sql); 
						        while($rowInc = mysqli_fetch_object($resInc)){
						            if(in_array($rowInc->id, explode(',',$row->ids_additional_inclusion))){
						                $selected='selected';
						            }
						            else{
						                $selected='';
						            }

						            echo '<option '.$selected.' value="'.$rowInc->id.'">'.$rowInc->name.'</option>';
						            
						        }
						    ?>
						    </select>        
						</div>
						</div>
						<div class="col-md-2">
						    <div class="form-group">
						        <label for="offer_type">Min Stay</label>
						        <input required placeholder="Enter Days" class="form-control" type="number" value="<?php echo $row->min_stay!=''?$row->min_stay:1;?>"  name="min_stay">               
						    </div>
						</div>
						
						<div class="col-md-2" id="display_home">
						<div class="form-group">
						    <label for="display_home">Display at home</label>
						    <?php
						        if($row->display_at_home==1)
						           $select1='selected="selected"';
						        else
						            $select0='selected="selected"';        
						    ?>
						    <select name="display_home" class="form-control select2">
						        <option <?php echo $select1;?> value="1">Yes</option>
						        <option <?php echo $select0;?> value="0">No</option>
						    </select>
						</div>
						</div>
						<div <?php echo $visibility ; ?> class="col-md-2" id="validBeforeBox">
						<div class="form-group">
						    <label for="offer_type">Advance Days</label>
						    <input  required placeholder="Enter Days" class="form-control" type="number" id="valid_before" value="<?php echo $row->advance_days; ?>"  name="valid_before">               
						</div>
						</div> 
						<!--image portion -->
						<input type="hidden" id="offer_image_name" name="offer_image" value="<?php echo $row->image;?>" />
						
						<div class="col-md-12">&nbsp;</div>
						<?php if($row->image =='' ){ ?>
						<div class="col-md-2">
							<label>&nbsp;</label>
							<a href="#" onClick="return addForm('adminsform', 'Add New Photo');" data-toggle="modal" data-target="#adminsform"  class="btn btn-success form-control"><i class="fa fa-plus fa-x1 "></i> Add Image</a>
							
						</div>
						<?php } ?>
						<div class="col-md-2">
							<label>Display order</label>
							<input required class="form-control" value="<?php echo $row->display_order?>" type="number" name="display_order" />
						</div>
						<div class="col-md-4">
						  <div class="form-group">
						    <?php
						      if($row->status==1)
						        $active="checked='checked'";
						      elseif($row->status==0)
						        $inactive="checked='checked'";
						      else
						        $active="checked='checked'";

						    ?>
						    <br/><br/>
						    <label for="room">Status:</label>&nbsp;&nbsp; 
						     <input <?php echo $active; ?> type="radio"  class="form-control flat-red" value="1" name="status">&nbsp; <label for="room">Active</label>  &nbsp;           
						     <input <?php echo $inactive; ?> type="radio"  class="form-control flat-red" value="0" name="status">&nbsp; 
						    <label for="room">Inactive</label>
						       
						</div>
						</div>
						<?php
							if(file_exists($image_path.$row->image) && $row->image !=''){ 
						?>
						      					
						    <div class="row"> 
						    <div class="col-md-12">      				
							<div style="border:1px solid #e3e3e3; margin-left:20px;" class="col-xs-12 col-md-4 col-lg-4 pull-right" id="<?php echo $row->id?>" >
								<div class="sortable-heading panel-body" >	
									<img src="<?php echo $SITE_URL.'/uploaded_files/offers/medium-'.$row->image; ?>" class="img-responsive" style="height:150px; width:300px;"/>
								</div>
								<div class="panel-footer">    
								    				
									<a href="#" onClick="return cropImg('cropImg','<?php echo '../uploaded_files/offers/'.$row->image; ?>','<?php echo '../uploaded_files/offers/medium-'.$row->image; ?>', '0|0|100|100','400x350');" data-toggle="modal" data-target="#cropImg" class="btn btn-sm btn-success"><i data-toggle="tooltip" title="Crop Image"  class="fa fa-crop fa-fw"></i></a> 

									<a href="#"   data-toggle="modal" data-target="#adminsform" class="btn btn-sm btn-warning"><i data-toggle="tooltip" title="Edit" class="fa fa-pencil fa-fw"></i></a>
								</div>
							</div>
							
							</div>
							</div>
						<?php }?>
						
							
						

					</div>
					<div class="row">
						<div class="col-md-1">
							<input type="submit" value="<?php echo ($_REQUEST['eId']!=''?"Edit":"Save"); ?>" name="Save" class="btn btn-success" />
						</div>
						<div class="col-md-1">
							<a href="manageOfferMaster.php"  class="btn btn-warning" />Cancel</a>
						</div>	
					</div>
				</form>
			</div>
		</div>	
	</section>
</div>
<script type="text/javascript">
	//var dayExtend='<?php echo $dayExtend; ?>';
</script>
<?php include_once("_inc_crop_img.php");?>
<?php include_once("../includes/footer.php");?>
<script type="text/javascript">
	$('document').ready(function(){
		$('#offerImageForm').submit(function(e){
			e.preventDefault();
			var form_data = new FormData();
			var file_name = $('#photomain').prop('files')[0];
			form_data.append('photo',file_name);
			form_data.append('SaveGal','1');
			//form_data.append('form',$(this).serialize());
			//console.log(form_data);	
			$("#uploadTxt").show();	
			$.ajax({
				type: "POST",
				url: 'ajax/ajaxUploadImage.php',
				data: form_data,
				dataType:'JSON',
				cache: false,
				contentType: false,
				processData: false,
				success:function(data){
					if(data==1 || data==2 || data==3){
						alert('Error While Uploading Image');
					}
					else{

						alert('Image Uploaded Succefully');
						$("#offer_image_name").val(data);
						$("#modal-close").click();
						//window.location.href="editOfferMaster.php ?"+formData+'&'+data;
					}
					
					
				},
				complete:function(){
					$("#uploadTxt").hide();
				}				
			});

		});
	});

	function inputDisplay(val){
	    if(val==3){
	        $('#validBeforeBox').css('visibility','visible');
	        $('#promoCodeBox').css('visibility','hidden');
	    }
	    else if(val==4){
	        $('#promoCodeBox').css('visibility','visible');
	        $('#validBeforeBox').css('visibility','hidden');
	        $('#valid_before').val(0);
	    }
	    else{
	        $('#validBeforeBox').css('visibility','hidden');
	        $('#promoCodeBox').css('visibility','hidden');
	        $('#valid_before').val(0);
	    }       
	} 

	
</script>