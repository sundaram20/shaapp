<?php 

include_once("../config/auto_loader.php");

if($_REQUEST['action'] == 'change'){

	if($_REQUEST['activeId'] != ''){

		if(checkUserLevelPermission($_SESSION['userLevel'],TBL_GUEST,'active')){
			$statusId = addslashes(encryptor(decrypt,$_REQUEST['activeId']));
			$statusSql = "	UPDATE `".TBL_GUEST."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".$statusId."'";

			if(executeSql($statusSql)){
				$err = 0;
				$_SESSION['successMsg'] = 'status has been changed successfully.';
			}else{
				$err = 1;
				$_SESSION['errorMsg'] = 'status has not been changed.';
			}	
		}
	}else if($_REQUEST['inactiveId'] != ''){
		if(checkUserLevelPermission($_SESSION['userLevel'],TBL_GUEST,'inactive')){

			$statusId = addslashes(encryptor(decrypt,$_REQUEST['inactiveId']));

			$statusSql = "	UPDATE `".TBL_GUEST."` 
						SET `status` = '0' 
						,`last_modified` = '".currenDateTime()."'
						,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".$statusId."'";
			
			if(executeSql($statusSql)){
				$err = 0;
				$_SESSION['successMsg'] = 'status has been changed sucessfully.';
			}else{
				$err = 1;
				$_SESSION['errorMsg'] = 'status has not been changed.';
			}	
		}

	}
}else if($_REQUEST["action"] == "delete" && !empty($_REQUEST['delId'])){

	if(checkUserLevelPermission($_SESSION['userLevel'],TBL_GUEST,'delete')){	
		$deleteIds = encryptor(decrypt,$_REQUEST['delId']);	

		$delSql = "DELETE FROM `".TBL_GUEST."` WHERE `id` IN (".addslashes($deleteIds).")";

		//$delSqlImage = selectSql(TBL_GUEST,"where `id` in (".addslashes($deleteIds).") ",'');	
		if(executeSql($delSql)){		
			$err = 0;
			/*while($delResultImage = mysql_fetch_array($delSqlImage)){
				if(file_exists($image_path.$delResultImage['creadit_form'])){
					@unlink($image_path.$delResultImage['creadit_form']);
					@unlink($image_path.'small-'.$delResultImage['creadit_form']);
					@unlink($image_path.'medium-'.$delResultImage['creadit_form']);
				}
			} */
			$_SESSION['successMsg'] = 'Selected records has been deleted sucessfully.';
		}else{
			$err = 1;
			$_SESSION['errorMsg'] = 'Unable to delete selected records';
		}
	}
}

// ----------cate---------
//AND FIND_IN_SET(id_area,'".$_SESSION['teamMemberAreas']."') 
$sql = "SELECT * FROM `".TBL_GUEST."` WHERE id_shop='".addslashes($_SESSION['shop'])."' AND `type` = '1' ";

$countSql = "SELECT * FROM `".TBL_GUEST."` WHERE id_shop='".addslashes($_SESSION['shop'])."' AND `type` = '1' ";

if($_REQUEST['search_name'] != ''){
	$sql .= " AND `id` LIKE '%".$_REQUEST['search_name']."%'";
}

if($_REQUEST['status'] != ''){
	$sql .= " AND `status` = '".addslashes($_REQUEST['status'])."%'";
} 

//$sql .= $_SESSION['Ids_user_access_Company'] ;
if($_REQUEST['order'] != ''){
	$sql .= " ORDER BY `date_created` DESC";
}else{
	$sql .= " ORDER BY `date_created` DESC";
} 

//echo $sql;
$db->query($sql);
$numRows= $db->num_rows();
$db->query($countSql);
$countRecord = $db->num_rows();
$pagging = new pagingClass($sql,$setpage);
$db->query($pagging->getQuery());
$total = $db->num_rows();

?>

<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       	Guest Manager
        <small>Manage Guest</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Guest Company</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">		
		<div class="box box-default">
	 		<div class="form-group has-error" align="center">
				<?php if($_SESSION['errorMsg']){?>
				 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
				<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
				<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
				<?php unset($_SESSION['successMsg']);}?>
			</div>
        	<div class="box-header with-border">
          		<h3 class="box-title"><small>Total Records: (<?=$countRecord;?>) &nbsp;</small> </h3>
		  		<div class="btn-group  pull-right">
                  <a type="button" class="btn btn-success" href="editGuest.php">Add Guest</a>
            	</div>          
        	</div>
	        <!-- /.box-header -->
			<form name="searchForm" action="" method="get">
            	<input type="hidden" value="1" name="searchFormSubmit" />
       			 <div class="box-body">
          			<div class="row">
            			<div class="form-group col-md-6">
                			<label>Guest RegNo - Guest Name - Guest Email - Guest PhoneNo - Guest City</label>				 
							<select class="form-control select2 guestName" name="search_name" id="search_name"  style="width:100%;">

                  			</select>		
          				</div>
		            	<!--Search by Guest Email-->
						<div class="form-group col-md-3">
				          	<label>Status</label>				
							<?php 
								if($_REQUEST['status'] == '1'){
											$selected1 = 'selected="selected"';
								}elseif($_REQUEST['status'] == '0'){
											$selected0 = 'selected="selected"';
								}
								echo $statusDropDown = '<select class="form-control select2" name="status" style="width: 100%"> <option value="">Both</option>
								  <option '.$selected1.' value="1">Active</option>
								  <option '.$selected0.' value="0">Inactive</option>
								  </select>';?>
				        </div>		              
         			<!-- /.row -->
        			</div>
				</div>
        		<!-- /.box-body -->
        		<div class="box-footer">

			        <div class="row">
		        		<div class="col-md-2 col-sm-2" style="padding:0px 0px 0px 20px;">
		        			<input name="Search" type="submit" class="btn btn-primary" value="Search" />
		        		</form>	
					        <button type="button" class="btn btn-primary toggleBtn" id="btnReportShow">Report</button>
		        		</div>
		        		<div class="col-sm-10 col-md-10" id="showReportDiv" style="display:none;padding-left:0px">
			        		<div class="box box-primary" style="border-right:2px #3C8DBC solid;border-bottom:2px #3C8DBC solid;border-left:2px #3C8DBC solid">
			        			<div class="box-header">
			        				<div class="box-tools pull-right">
			        					<button type="button" class="btn btn-box-tool" id="btnRemoveDiv"><i class="fa fa-times"></i>
		                    			</button>
			        				</div>
			        			</div>
			        			<div class="box-body">
			        				<form name="phpForm" id="phpForm" method="post">
			        					<div class="row">
			        						<div class="form-group col-sm-1 col-md-1">
			        							<label style="font-size: 16px;">Format</label>
			        						</div>
			        						<div class="form-group col-md-3 col-sm-3">
			        							<input type="radio" class="flat-red" value="xls" name="fileType" checked/> Excel  &nbsp; &nbsp; &nbsp; &nbsp;
			        							<input type="radio" class="flat-red" value="pdf" name="fileType" />
								                  PDF  <?php 
								                  //echo "SELECT field_name FROM ".TBL_REPORT." WHERE table_name = '".TBL_COMPANY."' AND  enable_order_by = 1 AND id_shop = ".$_SESSION['shop'];   ?>
			        						</div>
			        						<div class="form-group col-sm-1 col-md-1" style="padding-right:0px">
			        							<label style="font-size: 16px;">Order BY</label>
			        						</div>
			        						<div class="col-md-3 col-sm-3">
			        							<select class="form-control select2" style="width:100%;" name="EnableOrderBy" id="EnableOrderBy" data-parsley-errors-container="#OrderBYError"data-parsley-required>
			        								<option selected="selected" value="">----Order By----</option>
			        								<?php
			    										$sqlResult = mysqli_query($appConnect, "SELECT field_name FROM ".TBL_REPORT." WHERE table_name = '".TBL_GUEST."'AND enable_order_by = 1 AND id_shop = ".$_SESSION['shop'] );

			    										if(mysqli_num_rows($sqlResult)){
			    											while ($sqlRow = mysqli_fetch_object($sqlResult)){
			    										?>
					    								<option value="<?php  echo $sqlRow->field_name?> "><?php  echo $sqlRow->field_name?></option>

			    									<?php } }?>		
			        								
			        							</select>
			        							<span id="OrderBYError"></span>
			        						</div>
			        					</div>
			        					<div class="row">
			    							<div class="form-group col-md-12 col-sm-12">
			    								<?php
			    									$sqlResult = mysqli_query($appConnect, "SELECT * FROM ".TBL_REPORT." WHERE table_name = '".TBL_GUEST."' AND id_shop = ".$_SESSION['shop'] ." ORDER BY display_order");

			    									if(mysqli_num_rows($sqlResult)){
			    										while ($sqlRow = mysqli_fetch_object($sqlResult)){
			    											if($sqlRow->default_select == 1){
					    												$checked = "checked";
					    											}else{
					    												$checked = "";
					    											}
			    								?>
					    								<input type="checkbox" class="flat-red" name="field_name[]" value="<?php echo $sqlRow->field_name ?>"  <?= $checked ?> /> <?php echo $sqlRow->field_label ?>
					    								&nbsp; &nbsp; &nbsp; &nbsp;

			    								<?php } }?>		
			    								<input type="hidden" name="tableName" value="<?php echo TBL_GUEST;?>" />	
			    							</div>
			    						</div>
			    						<div class="box-footer">
			    							<button class="btn btn-primary" type="button" onclick="generateFile()">Generate</button>

			    							<!--a class="btn btn-primary" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_COMPANY;?>">Generate</a -->
			            
			    						</div>
			        				</form>
			        			</div>
			        		</div>
			        	</div>
		        	</div>   
        		</div>
      	</div>
      	<div class="row">
        	<div class="col-xs-12">		     
          		<!-- /.box -->
          		<div class="box">
		            <div class="box-header">
		              <h3 class="box-title">Search Result Guest List :  (<?=$numRows;?>)</h3>
		            </div>
					<form name="listingForm" action="" method="post">
               			<input type="hidden" value="" name="act" />
			     		<div id="listingDiv"></div>
            			<div class="box-body table-responsive">
              				<table id="myTable" class="table table-striped table-bordered dataTable no-footer" cellspacing="0">
                				<thead>
                					<tr>
					                  <th width="2%"><!--<input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' />--> S.No.&nbsp;</th>
					                  <th>Guest RegNo - Guest Name - Guest City</th>
					                  <th>Country</th>
									  <th>Primary Contact</th>
									  <th>Email</th>
									  <th>Last Updated By</th>
									  <?php if($_SESSION['userLevel']==1){ ?>
					                  <th>Status</th>
					              	  <?php } ?>
									  <th>Action</th>
                					</tr>
                				</thead>
                				<tbody>
									<?php 				 				
										if($total > 0){$counter = 1;
				  							while($row = $db->fetch_object()){?>
                							<tr>
								                <td><!--<input type="checkbox" name="ids[]" id="ids" value="<?=$row->id_company;?>"/>--> <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>
								                <td><?= $row->doc_no.' - '.$row->first_name." ".$row->last_name.' - '.$row->city;?></td>

								                <?php if($row->id_country == 10000){?>
								                	<td><?= $row->other_country ?></td>
								                <?php }else{ 
								                	$resCat = selectSql(TBL_COUNTRY_LANG,"where id_country=".$row->id_country );
								                	$country = $db->fetch_object2($resCat);
								                	//echo "<pre>"; print_r($country); die();
								                ?>
								                <td><?= $country->name ?></td>
								                <?php } ?>
												<td>
													<?php
													 	if($row->primary_contact == 1){
													 		echo $row->primary_mobile;
														}else if($row->primary_landline == 2){
															echo $row->primary_landline;
														}
													?>
												</td>
												<td><?= $row->email ?></td>
												<td><?= $row->last_modified ?></td>
												 <?php if($_SESSION['userLevel']==1){ ?>
								                <td><?=$row->status=='1'?'<span onclick="location.href=\'manageGuests.php?inactiveId='.encryptor(encrypt,$row->id).'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageGuests.php?activeId='.encryptor(encrypt,$row->id).'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>	<?php } ?>		 
												<td><img src="../images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editGuest.php?gId=<?=encryptor(encrypt,$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/print.png" style="cursor:pointer;" title="Print " 
												onClick="window.location.href='print.php?gId=<?=encryptor(encrypt,$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';">&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $row->first_name; ?>" id="<?php echo encryptor(encrypt,$row->id);?>" onClick="deletes(this.id);"/></td>
								            </tr>
               							<?php }?>               
									<?php }?>
                				</tbody>                
              				</table>			  
            			</div>
            			<!-- /.box-body -->
		  			</form>
          		</div>
          	<!-- /.box -->
        	</div>
        	<!-- /.col -->
      	</div>
      	<!-- /.row -->
    </section>
    <!-- /.content -->
  </div>



<?php include_once("../includes/footer.php")?>  

<script type="text/javascript">
  	function deletes(sid)
    {  
    swal({
      title: "Are you sure?",
      text: "Delete?",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: 'Yes, I am sure!',
      cancelButtonText: "No, cancel it!",
      closeOnConfirm: false,
      closeOnCancel: false
      },
     
     function(isConfirm)
     {

	     if (isConfirm)
	     {  
	          self.location='manageGuests.php?delId='+sid+'&action=delete&page=<?=$_REQUEST['page']?>';
	     } 
	     else
	     {
	        self.location='manageGuests.php';
	      }
	     });
    }


    comCheck = () =>{
		window.location.href='https://localhost/application/dashboard.php';
	}
    $('.guestName').select2({
        placeholder: 'Select Guest',
        ajax: {
          url: "ajax/ajaxSearchGuestName.php",
          dataType: 'json',
          delay: 50,
		  processResults: function (data) {
			  console.log(data[0].id);
			  //data1 = JSON.parse(data);
			  //alert(data1);
			 if(data[0].id){
			 	return { results: data};
			 }
			 else{
				comCheck(); 
				return { results: data};
				
			 }
          },
           cache: true
        }//ajax end
		
      });
    $(document).ready(function(){
    	$(document).on('click','#btnReportShow',function(){
      	 $('#showReportDiv').toggle();
		});
		$(document).on('click','#btnRemoveDiv',function(){
      	 $('#showReportDiv').hide();
		});
    });

    function generateFile(){

    	var form = $("#phpForm");
    	if(form.parsley().validate()){
			var formData = $("#phpForm").serialize();
			window.location.href = "exportTable.php?"+formData;
    	}else{
    		
    	}
    	
    }
  </script> 

