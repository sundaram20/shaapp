<?php include_once("../config/auto_loader.php");

include_once("functions/selectQuery.php");
$image_path = $UPLOAD_FILES.'/creadit_form/';
$image_display_path = $UPLOAD_FILES_PATH ."/creadit_form/";

if($_REQUEST['action'] == 'change'){
	
	if($_REQUEST['activeId'] != ''){

		if(checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY,'active')){

		$statusId = addslashes(encryptor(decrypt,$_REQUEST['activeId']));
		$statusSql = "	UPDATE `".TBL_COMPANY."`
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
	}elseif($_REQUEST['inactiveId'] != ''){

		if(checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY,'inactive')){
		
		$statusId = addslashes(encryptor(decrypt,$_REQUEST['inactiveId']));
		$statusSql = "	UPDATE `".TBL_COMPANY."` 
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
	if(checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY,'delete')){	
		$deleteIds = encryptor(decrypt,$_REQUEST['delId']);	
		$delSql = "DELETE FROM `".TBL_COMPANY."` WHERE `id` IN (".addslashes($deleteIds).")";
		$delSqlImage = selectSql(TBL_COMPANY,"where `id` in (".addslashes($deleteIds).") ",'');	
		if(executeSql($delSql)){		
			$err = 0;
			while($delResultImage = mysqli_fetch_array($delSqlImage)){
				if(file_exists($image_path.$delResultImage['creadit_form'])){
					@unlink($image_path.$delResultImage['creadit_form']);
					@unlink($image_path.'small-'.$delResultImage['creadit_form']);
					@unlink($image_path.'medium-'.$delResultImage['creadit_form']);
				}
			}
			$_SESSION['successMsg'] = 'Selected records has been deleted sucessfully.';
		}else{
			$err = 1;
			$_SESSION['errorMsg'] = 'Unable to delete selected records';
		}
	}
}



if($_REQUEST['searchFormSubmit']==''){
			$sqlResult1 = mysqli_query($appConnect, "SELECT * FROM ".TBL_REPORT." WHERE table_name = '".TBL_COMPANY."' AND id_shop = ".$_SESSION['shop'] ." ORDER BY display_order");

			if(mysqli_num_rows($sqlResult1)){
				while ($sqlRow = mysqli_fetch_object($sqlResult1)){
					if($sqlRow->default_select == 1){
							$field_name[]=$sqlRow->field_name;	
							$field_label[]=$sqlRow->field_label;		

					}
				}
			}
			
			$sqlResultOrder = mysqli_query($appConnect, "SELECT field_name FROM ".TBL_REPORT." WHERE table_name = '".TBL_COMPANY."'AND enable_order_by = 1 AND id_shop = ".$_SESSION['shop']." order BY display_order asc LIMIT 0,1" );

				if(mysqli_num_rows($sqlResultOrder)){
					
					$sqlRowOrder = mysqli_fetch_object($sqlResultOrder);
						
				$EnableOrderBy	=	$sqlRowOrder->field_name;
				  }
				  
				  
												  
												  
}else{
		$field_name =$_REQUEST['field_name'];
		$fieldname = implode("','",$field_name);
		$sqlResult1 = mysqli_query($appConnect, "SELECT * FROM ".TBL_REPORT." WHERE table_name = '".TBL_COMPANY."' AND id_shop = ".$_SESSION['shop'] ." AND field_name IN ('".$fieldname."')  ORDER BY display_order");

			if(mysqli_num_rows($sqlResult1)){
				while ($sqlRow = mysqli_fetch_object($sqlResult1)){
						$field_label[]=$sqlRow->field_label;			

				}
			}
		$EnableOrderBy=$_REQUEST['EnableOrderBy'];
	
	}

//echo "<pre>"; print_r($_REQUEST);echo "</pre>";
//print_r($field_name);
$sql = local_SelectQuery_Mst_Company($_REQUEST['tableName'],$field_name,$field_label,$EnableOrderBy);
$QuerySQL	=	mysqli_query($connNew,$sql);
$numRows= @mysqli_num_rows($QuerySQL);
$TotalCountRecord = selectColumn(TBL_COMPANY,'count(id)',' WHERE id_shop="'.$_SESSION['shop'].'" ');
//$countRecord = $db->num_rows();
//$pagging = new pagingClass($sql,$setpage);
//$db->query($pagging->getQuery());
$total = @mysqli_num_rows($QuerySQL);
?>
<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<?php include_once("../ajax/ajaxCheckTransactions.php");?>
<div class="content-wrapper">
	<style type="text/css">
		.fieldset {
		  border: 2px groove #3C8DBC;
		  border-top: none;
		  padding: 0.5em;
		  margin: 1em 2px;
		}

		.fieldset>p {
		  font: 1.4em normal;
		  margin: -0.8em -0.4em 0;
		}

		.fieldset>p>span {
		  float: left;
		}

		.fieldset>p:before {
		  border-top: 3px solid #3C8DBC;
		  content: ' ';
		  float: left;
		  margin: 0.5em 2px 0 -1px;
		  width: 0.75em;
		}

		.fieldset>p:after {
		  border-top: 3px solid #3C8DBC;
		  content: ' ';
		  display: block;
		  height: 0.5em;
		  left: 2px;
		  margin: 0 1px 0 0;
		  overflow: hidden;
		  position: relative;
		  top: 0.5em;
		}

		.text{
			font-size:20px;
		}
	</style>
<?php if($_REQUEST['toggleCheckOpenStatus']=='' || $_REQUEST['toggleCheckOpenStatus']=='0'){
		$DispalyClass="display:none;";
		$viewClass="";
		$viewIcons='fa fa-plus-square-o fa-1x';
	}else{
		$DispalyClass="";
		$viewClass='fieldset';
		$viewIcons='fa fa-minus-square-o fa-1x';
	}
?>
    <!-- Content Header (Page header) -->
    <!--section class="content-header">

     <h1>
        Company Manager
        <small>Manage Company</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Company</li>
      </ol>
    </section-->
    <section class="content-header">
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
        <?php echo '<span style="color:'.currentNavigation()['color'].'">&nbsp;<i class="fa '.currentNavigation()['icon'].'"></i> '.currentNavigation()['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>
      <?php echo breadCrumbs(); ?>
    </section> 

    <!-- Main content -->
    <section class="content">		
	<div class="box box-default">

		 <!--########## Company Import jump#######-->  
		   
		   <!-- Modal -->
		     <div class="modal fade" id="importComapnyModal" role="dialog" >
		       <div class="modal-dialog">
		       
		         <!-- Modal content-->
		         <div class="modal-content" style="width: 300px; margin: 0px auto;">
		           <div class="modal-header">
		             <button type="button" class="close" data-dismiss="modal">&times;</button>
		             <h4 class="modal-title">Import Company</h4><br>
		             <span id="returnTxt" style="color: Green;"></span>
		           </div>
		           <div class="modal-body">
		             <form name="companyimport" method="post" enctype="multipart/form-data" id="companyimport">
		               <div >
		                 <label for="file">Choose File : <span style="color: red;">*</span></label>
		                 <input type="file" name="companyImport" class="form-control" id="companyImport">
		               </div><br>
		               <div >
		               	 <input type="hidden" name="table_name" value="<?php echo TBL_COMPANY;?>" />
		                 <input type="submit" value="uplaod" name="submit" class="btn btn-primary" id="importCompany"><span style="color:red;margin-left:50px; ">*</span> = Required 
		                 Field<br>
		               </div>

		            </form>
		           </div>
		         </div>
		         
		       </div>
		     </div>
		     
		   
		<!--########## Import Company  Modal End#######-->  

	 <div class="form-group has-error" align="center">
		<?php if($_SESSION['errorMsg']){?>
		 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
		<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
		<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
		<?php unset($_SESSION['successMsg']);}?>
		</div>
        <div class="box-header with-border">
          <h3 class="box-title"><small>Total Records: (<?=$TotalCountRecord;?>) &nbsp;</small> </h3>
			<div class="btn-group  pull-right">
	          <a type="button" class="btn btn-success" href="editCompany.php">Add Company</a>
	          <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
	            <span class="caret"></span>
	            <span class="sr-only">Toggle Dropdown</span>
	          </button>
	         
	          <ul class="dropdown-menu" role="menu">
	          	<li><a title="Import to excel file" href="#" data-toggle="modal" data-target="#importComapnyModal" ><img src="../images/excel-icon.jpg" width="20" height="20" />&nbsp;Import</a></li>
	            <li><a title="Export to excel file" href="masterExportTable.php?fileType=xls&tableName=<?php echo TBL_COMPANY;?>"><img src="../images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
	            <li><a title="Export to csv file" href="masterExportTable.php?fileType=csv&tableName=<?php echo TBL_COMPANY;?>"><img  src="../images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li>
	          
	          </ul>
	        </div>          
        </div>
        <!-- /.box-header -->
		<form name="searchForm" id="phpForm" action="" method="get">
            <input type="hidden" value="1" name="searchFormSubmit" />
        <div class="box-body">
          <div class="row">
            <!--<div class="col-md-6">
              <div class="form-group">
                <label>Company Name</label>				
				<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />
              </div>
              
            </div>-->

            <div class="col-md-6">
              <div class="form-group">
                <label>Company Name - City</label>				
				 <?php /* $categoryDropDown = '<select class="form-control select2" name="search_name" id="search_name" style="width: 100%">
											    <option value="">Select Company </option>';
											  $resCat = selectSql(TBL_COMPANY," where  id_shop='".addslashes($_SESSION['shop'])."' and name !=' ' AND FIND_IN_SET(id_mst_portfolio_account,'".$_SESSION['teamMyAreas']."')  ",' ORDER BY `name` ');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['search_name'] == $resultCat->name){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.htmlentities($resultCat->name).'">'.ucfirst($resultCat->name.'-'.$resultCat->city).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											*/  ?>				 
				<select class="form-control select2 companyName" name="search_name" id="search_name"  style="width:100%;">

                </select>
              </div>
			  			
          </div>
            


			<div class="col-md-6">
              <div class="form-group">
                <label>Company Group</label>				
				 <?php $categoryDropDown = '<select class="form-control select2" name="id_mst_attributes_company_group" style="width: 100%">
											    <option value="">Select Company Group</option>';
											  $resCat = selectSql(TBL_ATTRIBUTES," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND table_name='company_group' ",' ORDER BY `field_value`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['	id_mst_attributes_company_group'] == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?>
              </div>
			  			
          </div>
		            <!--Area Executive-->
		            <div class="col-md-3">
		                <div class="form-group">
		                  <label>Area </label>				
		  				 <?php $categoryDropDown = '<select class="form-control select2" name="id_mst_portfolio_account" style="width: 100%">
		  											  <option value="">Select Area</option>  ';
		  											  $resCat = selectSql(mst_portfolio_account," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' and name!='' ".$_SESSION['Ids_user_access_Company']." ",' ORDER BY `name`');
		  											  if($db->num_rows2($resCat)){

		  											  	while($resultCat = $db->fetch_object2($resCat)){
		  													/*if($_REQUEST['id_mst_portfolio_account'] == $resultCat->id_group){
		  														$selected = 'selected="selected"';
		  													}else{
		  														$selected = '';
		  													}*/
		  													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
		  												}
		  											  }
		  											 	echo $categoryDropDown .= '</select>';
		  											  ?>
		                </div>
		  			  			
		            </div>
		            <!--Area Executive End-->
		            <!--Search by Company Email-->
		     		  <div class="col-md-3">
		                   <div class="form-group">
		                     <label>Company Email</label>				
		     				<?php 
		     					$categoryDropDown = '<select class="form-control select2" name="email" style="width: 100%">
		  								<option value="">Select Email</option>  ';
		  								  $resCat = selectSql(TBL_COMPANY," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'  ",' ORDER BY `name`');
		  									  if($db->num_rows2($resCat)){
		  									  	while($resultCat = $db->fetch_object2($resCat)){
		  											/*if($_REQUEST['id_email'] == $resultCat->email){
		  												$selected = 'selected="selected"';
		  												}else{
		  												$selected = '';
		  											}*/
		  											$categoryDropDown .= '<option '.$selected.' value="'.htmlentities($resultCat->email).'">'.ucfirst($resultCat->email).'</option>';
		  											}
		  									   }
		  								echo $categoryDropDown .= '</select>';?>
		                   </div>
		                   <!-- /.form-group -->
		                 </div>       	

		            <!-- End-->

		                   <!--Search by Company Mobile-->
		            		  <div class="col-md-3">
		                          <div class="form-group">
		                            <label>Company Phone No.</label>				
		            				<?php 
		     					$categoryDropDown = '<select class="form-control select2" name="primary_contact_type" style="width: 100%">
		  								<option value="">Select Phone</option>  ';
		  								  $resCat = selectSql(TBL_COMPANY," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');
		  									  if($db->num_rows2($resCat)){
		  									  	while($resultCat = $db->fetch_object2($resCat)){
		  											/*if($_REQUEST['id_mst_portfolio_account'] == $resultCat->id_group){
		  												$selected = 'selected="selected"';
		  												}else{
		  												$selected = '';
		  											}*/
		  											if($resultCat->primary_contact_type == 1){
		  												$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->primary_mobile.'">'.$resultCat->primary_mobile.'</option>';
		  											}else{
		  												$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->primary_landline.'">'.$resultCat->primary_landline.'</option>';
		  											}
		  										}

		  											
		  									   }
		  								echo $categoryDropDown .= '</select>';?>
		                          </div>
		                          <!-- /.form-group -->
		                        </div>       	

		                   <!-- End-->




		  <div class="col-md-3">
              <div class="form-group">
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
              <!-- /.form-group -->
            </div>
          <!-- /.row -->
        </div>

<input type="hidden" name="toggleCheckOpenStatus" id="toggleCheckOpenStatus" value="<?php echo $_REQUEST['toggleCheckOpenStatus']; ?>" />
	        <div class="row">
	        	<div class="col-md-12 col-sm-12">	        		
		        	<div  id="fieldset" class="<?php echo $viewClass; ?>">
		        		<p id="showheadelion" class="text-primary"><span id="textReportShow" style="font-size:15px;">More Options <i class="<?php echo $viewIcons; ?>"></i></span></p>
		        		<div  id="moreReportDiv" style="padding-left:0px;<?php echo $DispalyClass; ?>">
		        			<div class="box-header" style="padding:5px">
		        				<div class="box-tools pull-right">
		        					<!--<button type="button" class="btn btn-box-tool" id="btnMoreReportDiv">X
	                    			</button>-->
		        				</div>
		        			</div>
		        			<div class="box-body">
	        					<div class="row">
	        						<div class="form-group col-sm-1 col-md-1">
	        							<label >Format</label>
	        						</div>
	        						<div class="form-group col-md-3 col-sm-3">
	        							<input type="radio" class="flat-red" value="xls" name="fileType" checked/> Excel  &nbsp; &nbsp; &nbsp; &nbsp;
	        							<input type="radio" class="flat-red" value="pdf" name="fileType" />
						                  PDF  <?php 
						                  //echo "SELECT field_name FROM ".TBL_REPORT." WHERE table_name = '".TBL_COMPANY."' AND  enable_order_by = 1 AND id_shop = ".$_SESSION['shop'];   ?>
	        						</div>
	        						<div class="form-group col-sm-1 col-md-1" style="padding-right:0px">
	        							<label>Order By</label>
	        						</div>
	        						<div class="col-md-3 col-sm-3">
	        							<select class="form-control select2" style="width:100%;" name="EnableOrderBy" id="EnableOrderBy" data-parsley-errors-container="#OrderBYError"data-parsley-required>
	        								<?php /*?><option selected="selected" value="">----Order By----</option><?php */?>
	        								<?php
	    										$sqlResult = mysqli_query($appConnect, "SELECT field_name, field_label FROM ".TBL_REPORT." WHERE table_name = '".TBL_COMPANY."'AND enable_order_by = 1 AND id_shop = ".$_SESSION['shop']." order BY display_order asc" );

	    										if(mysqli_num_rows($sqlResult)){
													
	    											while ($sqlRow = mysqli_fetch_object($sqlResult)){
														
	    										?>
			    								<option value="<?php  echo $sqlRow->field_name?> "><?php  echo $sqlRow->field_label ?></option>

	    									<?php } }?>		
	        								
	        							</select>
	        							<span id="OrderBYError"></span>
	        						</div>
	        					</div>
	        					<div class="row">
	    							
	    								<?php
										is_array($_REQUEST['field_name']) ? '' : $_REQUEST['field_name']=array();
	    									$sqlResult = mysqli_query($appConnect, "SELECT * FROM ".TBL_REPORT." WHERE table_name = '".TBL_COMPANY."' AND id_shop = ".$_SESSION['shop'] ." ORDER BY display_order");

	    									if(mysqli_num_rows($sqlResult)){
	    										while ($sqlRow = mysqli_fetch_object($sqlResult)){
	    											if($sqlRow->default_select == 1){
			    												$checked = "checked";
			    											}elseif(in_array($sqlRow->field_name, $_REQUEST['field_name'])){
																$checked = "checked";
															}else{
			    												$checked = "";
			    											}
	    								?>
	    									<div class="form-group col-md-2 col-sm-2" style="">
			    								<input type="checkbox" class="flat-red" name="field_name[]" value="<?php echo $sqlRow->field_name ?>"  <?= $checked ?> /> <?php echo $sqlRow->field_label ?>
			    							</div>	

	    								<?php } }?>		
	    								<input type="hidden" name="tableName" value="<?php echo TBL_COMPANY;?>" />	
	    							
		    					</div>
	    						
		        			</div>
		        		</div>
		        	</div>
		        </div>
		     </div>
             
             
		</div>
        <!-- /.box-body -->
        <div class="box-footer">
        	<div class="row">
        		<div class="col-md-2 col-sm-2" style="padding:0px 0px 0px 20px;">
        			<input name="Search" type="submit" class="btn btn-primary" value="Search" />
					<button class="btn btn-primary" type="button" onclick="generateFile()">Generate</button>	
			       
        		</div>
        		
        	</div>
        </div>
		</form>	
      </div>
      <div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of <?php echo currentNavigation()['submenu']; ?>  (<?=$numRows;?>)</h3>
            </div>
			<form name="listingForm" action="" method="post">
               <input type="hidden" value="" name="act" />
			     <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="myTable" class="table table-striped table-bordered dataTable no-footer" cellspacing="0">
                <thead>
                <?php 
				$fields_num = @mysqli_num_fields($QuerySQL);
				?>
                <tr>
                  <th width="3%"><!--<input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' />--> S.No.&nbsp;</th>
                  <?php 
				  		for($i=0; $i<$fields_num; $i++){
							$field = mysqli_fetch_field($QuerySQL);
							if($field->name != 'id'){
								 $dataWriteheader .= "<th>{$field->name}</th>";
							}
							
						}
						echo $dataWriteheader;
						?><?php /*?><th>Company Name</th>
				  <th>Company Group</th>
				  <th>Area</th>
				  <th>Area Description</th>
				  <?php if($_SESSION['userLevel']==1){ ?>
                  <th>Status</th>
              	  <?php } ?><?php */?>
				  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				<?php 	
				$counter=1;
				while($row = @mysqli_fetch_row($QuerySQL)){
				$value = 1;
				
				$dataWrite .= "<tr>";
				$dataWrite .='<td>'.$counter++.'</td>';
						foreach($row as $cell){
							if($cell=='Active'){						
								
								$cell='<span onclick="location.href=\'manageCompany.php?inactiveId='.encryptor(encrypt,$Id).'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>';
							}else if($cell=='Inactive'){
									$cell='<span onclick="location.href=\'manageCompany.php?activeId='.encryptor(encrypt,$Id).'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';
							}
							if($value == 1){
								$Id = $cell;
							}else{

								$dataWrite .= "<td>$cell</td>";
							}

							$value ++;
							
						}	

						$strLocation = 'editCompany.php?eId='.encryptor(encrypt,$Id).'&action=edit&page='.$_REQUEST['page'];

						$table_name = array(TBL_COMPANY_CONTACTS);
            			$ajaxCheckTransactions = CheckTransactionsCompany($Id, $table_name); 

						if($ajaxCheckTransactions != '1'){

							$dataWrite .='<td><img src="../images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onclick="window.location.href=\''.$strLocation.'\'" />&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/delete.gif" style="cursor:pointer;" title="Delete" name="'.$row->name.'" id="'.encryptor(encrypt,$Id).'" onClick="deletes(this.id);"/></td>';
						}else{

							$dataWrite .='<td><img src="../images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onclick="window.location.href=\''.$strLocation.'\'" />&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/chat.png" style="cursor:pointer; " title="In Use" /></td>';
						}                                                  
							
					$dataWrite .= "</tr>";
						
				}echo $dataWrite;
				if($total > 0){/*$counter = 1;
				  while($row = mysqli_fetch_object($QuerySQL)){?>
                <tr>
                  <td><!--<input type="checkbox" name="ids[]" id="ids" value="<?=$row->id_company;?>"/>--> <?php echo $counter++;?>.&nbsp;</td>
                  <td><?=$row->name.' - '.$row->city;?></td>
				  <td><?php if($row->id_company_group ==0){ echo 'Default/Guest'; }else {echo selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$row->id_company_group."'"); }  ?></td>
				  <td><?php echo selectColumn(MST_PORTFOLIO_ACCOUNT,'name'," WHERE `id` = '".$row->id_mst_portfolio_account."'");  ?></td>
				  <td><?php echo selectColumn(MST_PORTFOLIO_ACCOUNT,'description'," WHERE `id` = '".$row->id_mst_portfolio_account."'");  ?></td>
				  <?php if($_SESSION['userLevel']==1){ ?>
                  <td><?=$row->status=='1'?'<span onclick="location.href=\'manageCompany.php?inactiveId='.encryptor(encrypt,$row->id).'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageCompany.php?activeId='.encryptor(encrypt,$row->id).'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>	<?php } ?>		 
				  <td><img src="../images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editCompany.php?eId=<?=encryptor(encrypt,$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $row->name; ?>" id="<?php echo encryptor(encrypt,$row->id);?>" onClick="deletes(this.id);"/></td>
                </tr>
               <?php }?> 
			    <!--<tr>
                     <td align="left" colspan="5">
					 <input name="delete_sel" type="button" class="btn btn-warning" value="Delete" onClick="javascript:formSubmit('delete');"/>&nbsp;&nbsp;&nbsp;&nbsp; 
					 <input name="active_sel" type="button" class="btn btn-success" value="Active" onClick="javascript:formSubmit('activate');"/>&nbsp;&nbsp;&nbsp;&nbsp;
					  <input name="inactive_sel" type="button" class="btn btn-danger" value="Inactive" onClick="javascript:formSubmit('inactivate');"/> </td>
				</tr>-->          
				<?php */}?>
                </tbody>                
              </table>			  
            </div>
		  </form>
            <!-- /.box-body -->
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
          self.location='manageCompany.php?delId='+sid+'&action=delete&page=<?=$_REQUEST['page']?>';
     } 
     else
     {
        self.location='manageShop.php';
      }
     });
    }
  </script> 

<!--jump-->
<script type="text/javascript">
	//jump
	$("document").ready(function(){
		

		$(document).on('click','#btnReportShow',function(){
			alert('1');
      	 $('#toggleCheckOpenStatus').val('1');
		  
		 $('#showReportDiv').toggle();
		});
		$(document).on('click','#btnRemoveDiv',function(){
						alert('0');
      	 $('#toggleCheckOpenStatus').val('0');
		 $('#showReportDiv').hide();
		});

		$(document).on('click','#textReportShow',function(){
      	
		 var toggleCheckOpenStatus = $('#toggleCheckOpenStatus').val();
		 if(toggleCheckOpenStatus=='' || toggleCheckOpenStatus=='0'){
	      	$('#toggleCheckOpenStatus').val('1');
			$(this).find('i').toggleClass('fa-plus-square-o fa-1x fa-minus-square-o fa-1x');
		 }else{
			 	$('#toggleCheckOpenStatus').val('0');
				$(this).find('i').toggleClass('fa-plus-square-o fa-1x fa-minus-square-o fa-1x');
				}
				
			 $('#fieldset').toggleClass('fieldset');
	      	 $('#moreReportDiv').toggle();

		});
		$(document).on('click','#btnMoreReportDiv',function(){
			
			
      	 $(this).find('i').toggleClass('fa-plus-square-o fa-1x');
		 $('#toggleCheckOpenStatus').val('0');
		 $('#moreReportDiv').hide();
      	 $('#fieldset').toggleClass('fieldset');
		});
	});


	comCheck = () =>{
		window.location.href='https://localhost/application/dashboard.php';
	}
    $('.companyName').select2({
        placeholder: 'Select Company',
        ajax: {
          url: "ajax/ajaxSearchCompanyName.php",
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

    function generateFile(){

    	var form = $("#phpForm");
    	if(form.parsley().validate()){
			var formData = $("#phpForm").serialize();
			window.location.href = "functions/exportTable.php?"+formData;
    	}else{

    	}
    	
    }
    /*function generateFile(){
    	var formData = $("#phpForm").serialize();
    	$.ajax({
    		url : 'exportTable.php',
    		type : 'POST',
    		data : formData,
    		success : function(response){
    			alert(response);
    		}
    	}); 

    	window.location.href = "exportTable.php?"+formData;
    } */
</script>

<!--jump-->
<script type="text/javascript">
	//jump
	$("document").ready(function(){
		$("#importCompany").click(function(){
        $("#companyimport").submit(function(e){
          e.preventDefault();	
          var fileName = $("#companyImport").val();
          console.log(fileName);
          if(fileName == ""){
          	$("#returnTxt").css("color","red");
          	$("#returnTxt").html(" !! Kindly Select a file !!");
          }  
          else{
            $.ajax({
            type        : 'POST',
            contentType : false,
            processData : false, 
            url         : 'ajax/ajaxMasterImport.php', 
            data        : new FormData(this),
            success     : function(data){
              $("#returnTxt").html(data);
              /*$("#credithidden").val(data[1]);*/
              //alert(data);
            } 
           })
          }
        });
      });
	});
</script>