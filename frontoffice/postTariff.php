<?php include_once("../config/auto_loader.php");?>

<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<div class="content-wrapper">

	<?php 
	

 $sqlRes="SELECT count(fo_reservations_details.room_quantity) as qty ,fo_reservations_details.dated,fo_reservations_details.id_mst_room_types,fo_reservations_details.id_mst_hotels 
FROM `fo_reservations_details` left join fo_reservations on fo_reservations_details.id_fo_reservations =fo_reservations.id
where fo_reservations.booking_status!='4' 
GROUP by fo_reservations_details.dated ,fo_reservations_details.id_mst_room_types ORDER BY `fo_reservations_details`.`dated` DESC";



//"SELECT count(room_quantity) as qty ,dated,id_mst_room_types,id_mst_hotels FROM `fo_reservations_details` WHERE booking_status!='761' GROUP by dated ,id_mst_room_types ORDER BY `fo_reservations_details`.`dated` DESC";

$resRes = mysqli_query($connNew,$sqlRes);
						
						
						while($rowRes = mysqli_fetch_object($resRes)){ 
						
						$sqla = "SELECT * FROM ".FO_INVENTORY." WHERE id_mst_room_types='".$rowRes->id_mst_room_types."' and allocation_date='".$rowRes->dated."' and id_mst_hotels = '".$rowRes->id_mst_hotels."' ";
						$resnew = mysqli_query($connNew,$sqla);
						//$rownew = mysqli_fetch_object($resnew);
						
						$rownew = mysqli_fetch_object($resnew);
						
						
						$sqlRoom=  "SELECT rt.name, ahr.id_mst_hotels,ahr.inventory, ahr.id_mst_room_types from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.id_mst_room_types = rt.id where ahr.status='1' and rt.status='1' and ahr.id_mst_hotels = '".$rowRes->id_mst_hotels."' and ahr.id_mst_room_types='".addslashes($rowRes->id_mst_room_types)."'" ;
						
						
						$resRoom = mysqli_query($connNew,$sqlRoom);
						$rowRoom = mysqli_fetch_object($resRoom);
						
						
							$crs_available = $rowRoom->inventory - $rowRes->qty ; 
							$confirmed =  $rowRes->qty ; 
							
							$insertGrid = "UPDATE ".FO_INVENTORY." SET `crs_available`='".$crs_available."',`confirmed`='".$confirmed."' ";
							$insertGrid .=" WHERE id_mst_room_types='".$rowRes->id_mst_room_types."' and allocation_date='".$rowRes->dated."' and id_mst_hotels = '".$rowRes->id_mst_hotels."'";
						
						mysqli_query($connNew,$insertGrid);
						
						
						
						
						
						
						}?>
<!-- Audit Trail Modal -->

<!-- End Audit trail Modal -->

    <!-- Content Header (Page header) -->
	
	 <?php $session=$_GET['submenu'];
//echo $_REQUEST['id_posbilling'];
	 ?>
    <section class="content-header">
    	<div class="row">
     <div class="col-md-4 col-xs-12"> 
      <!--<h5 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
		<?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h5>-->
       <h5 class="box-title" style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;"><?php echo $_REQUEST['updateid']==''?'Add':'Edit'?> 
					<?php echo currentNavigation_id($session)['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo selectColumn(TBL_PURCH,'mdoc_no'," WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['updateid']))."' ") ?> </span> 
			  </h5>
			   </div>
     <div class="col-md-4 col-xs-12 dd-f">	
        <div class="icn-box">
                    
                     
                      
                    
                     
          
                
                 </div>
       
     </div> 
     <div class="col-md-4 col-xs-12 tb-br">	
      <?php echo breadCrumbs(); ?>
  </div>
</div>
    </section> 
	
	
  <!--  <section class="content-header">
      <h1>
        Billing Manager
      </h1>
      <ol class="breadcrumb">
        <li><a href="managePO.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage  Billing</li>
      </ol>
    </section>  -->
    <!-- Main content -->
    <section class="content">
			
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
         <?php //print_r($_REQUEST);?>
           
			 <div class="nav-tabs-custom mb-0">
			<!--<div class="box-header with-border">
               <h3 class="box-title"><?php echo $_REQUEST['updateid']==''?'Add':'Edit'?> 
					<?php echo currentNavigation_id($session)['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo selectColumn(TBL_PURCH,'mdoc_no'," WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['updateid']))."' ") ?> </span> 
			  </h3>
            </div>-->
			
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="postTariff" action=""  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off" id="postTariff">
   
				
					<div class="form-group has-error" align="center">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div> 
              <div class="box-body">
              	<div class="card text-dark bg-light">
              		<!--<div class="bg-primary text-center ">
              			<h5 style="padding: 5px;">Billing</h5>
              		</div> -->
              		
	              	<div class="row">	
        
              
              
              
                     
				 <div class="form-group col-xs-6 col-md-2 col-sm-6" >
	              			<label for="name">Date <font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-calendar"></i> 
						   	</div>
                         
                          <input data-parsley-required type="text" class="form-control pickerdateretwodays" placeholder="Enter post tariff Date" id="post_tariff_date" name="post_tariff_date" 
                          value="<?php echo date('d-m-Y');?>" >
												  
		                    </div> 
	              		</div>
                        
                        <div class="form-group col-xs-6 col-md-2 col-sm-6" >
		
 <label for="outlet">Post Tariff <font color="#FF0000">*</font> </label>
     
               				
 <select class="form-control select2" name="id_post_tariff" id="id_post_tariff" data-parsley-required data-parsley-errors-container="#id_post_tariffError" onChange="GetPostTariff(this.value);" >
				<?php 
			 		$categoryDropDown .= '<option  value="">--Select Post Tariff--</option>';
					$categoryDropDown .= '<option '.$selected.' value="1">All Occupied Rooms</option>';
					$categoryDropDown .= '<option '.$selected.' value="2">Selected Room</option>';
				
				echo $categoryDropDown .= '</select>';
 ?>
              <span id="id_post_tariffError"></span>
              </div>
                        
                        <div class="targetDivShow"  >
                        <div class="col-md-4 col-sm-4 col-xs-5" >
                        
 <label for="outlet">Rooms & Guest <font color="#FF0000">*</font> </label>
     
                                        <!-- input type="text"  class="form-control" id="datepicker1" value="<?php// echo Date('d-m-Y'); ?>" -->
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                              <select class="form-control first-input select2" select2" " multiple="multiple"  style="width:100% !important;" name="id_fo_bill[]" id="id_fo_bill"  >
                                                          <option value="0">Select Room </option>
       <?php  $resCat = mysqli_query($connNew,"SELECT * FROM `fo_folio` WHERE  folio_status='0' and status='1' ");
															   
			/*"SELECT *,fo.mdoc_no as folio_mdoc_no 
															   FROM `fo_folio` as fo 
															   INNER JOIN fo_bill as bi 
															   ON fo.id=bi.id_fo_folio_to where bi.folio_status='0'"*/												   
															   
	   //selectSql('fo_folio'," where  id_mst_shops='".addslashes($_SESSION['shop'])."' and folio_status='0'  ",' ');
														  
	if(mysqli_num_rows($resCat)){
	while($resultCat = mysqli_fetch_object($resCat)){
	$guestName	=	selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$resultCat->id_mst_guest."'");
	
	//$id_fo_bill	=  selectColumn(FO_BILL,'id'," WHERE `id_fo_folio_to` = '".$resultCat->id."'");
	
	$id_mst_room_no_allocation	=	selectColumn(FO_RESERVATIONS_DETAILS,'id_mst_room_no_allocation'," WHERE `id_fo_bill` = '".$resultCat->id_fo_bill."'");
	$roomNumber= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$id_mst_room_no_allocation."'");
									

echo $categoryDropDown = '<option '.$selected.'  value="'.$resultCat->id_fo_bill.'">
'.$resultCat->mdoc_no.'---    Room No:'.$roomNumber.' ---  Guest: '.$guestName.'</option>';
												//}
											  }
											  }
											  
											  /*$resCat = selectSql(FO_BILL," where  id_mst_shops='".addslashes($_SESSION['shop'])."' and folio_status='0'  ",' ');
														  
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
			
			$sqlOrderDetail = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($resultCat->id_reservations)."'   group by id_mst_room_no_allocation ");
			
			
			while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){										
			//$id_mst_guest=	selectColumn(FO_RESERVATIONS_DETAILS,'id_mst_guest'," WHERE `id_fo_reservations` = '".addslashes($resultCat->id_reservations)."' and DATE(dated) = '".date('Y-m-d')."'");
			$guestName	=	selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$rowOrderDetail->id_mst_guest."'");
			$roomNumber = selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
			
			$booking_no=	selectColumn(FO_RESERVATIONS,'booking_no'," WHERE `id` = '".addslashes($resultCat->id_reservations)."' ");					
													
													

echo $categoryDropDown = '<option '.$selected.'  value="'.$resultCat->id.'">Room No: '.$roomNumber.' Guest Name: '.$guestName.'</option>';
												}
											  }
											  }*/?>
                                                        </select>
                                        </div>   
                                    </div>
                        
                        
                        </div>
                        
                        
                        
                        
                        
                        
                       			                	                
						
                        
           
              
	                   
                 
 									                	                
						
		            </div>
		    </div>        
 
		       
              	     
		        </div>
		      
 
				           
              </div>
         	</div>
              <!-- /.box-body -->	
              <div class="box-footer"> 

			
				<input type='button' value='<?=($_REQUEST['eId']==''?'Save':'Edit')?>' class="btn c-btn ml-10" name="Save"  onClick="inuseUpdate();"  >
			
			  
			   <br><br>
			
			
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
<?php include_once("../includes/footer.php");?> 


<script>
$(".targetDivShow").hide();
function GetPostTariff(id_post_tariff){
			if(id_post_tariff=='1'){
				$(".targetDivShow").hide();
			}else if(id_post_tariff=='2'){
				$(".targetDivShow").toggle();
			}else{
				$(".targetDivShow").hide();
			}
	
	
	}	
	
	function inuseUpdate(){
		var id_post_tariff = $('#id_post_tariff').val();
		var post_tariff_date = $('#post_tariff_date').val();
		var id_fo_bill = $('#id_fo_bill').val();
		
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxUpdateInUseDate.php',
		   data: 'post_tariff_date='+post_tariff_date+'&id_post_tariff='+id_post_tariff+'&id_fo_bill='+id_fo_bill,
		   success:function(result){
       alert(result);
      		}, 
		   
		});
		
		}
</script>


