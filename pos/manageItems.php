<?php 
	include_once("../config/auto_loader.php");
	include_once("functions/selectQuery.php");
	checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_ITEMS,'view');

	if($_REQUEST['action'] == 'change'){
		if($_REQUEST['activeId'] != ''){
			checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_ITEMS,'activate');
			//$statusId = addslashes($_REQUEST['activeId']);
			$statusId = addslashes(encryptor(decrypt,$_REQUEST['activeId']));
			$statusSql = "	UPDATE `".TBL_INV_ITEMS."` 
						SET `status` = '1' 
						,`last_modified` = '".currenDateTime()."'
						,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".$statusId."'";
		}elseif($_REQUEST['inactiveId'] != ''){
			checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_ITEMS,'deactivate');
			//$statusId = addslashes($_REQUEST['inactiveId']);
			$statusId = addslashes(encryptor(decrypt,$_REQUEST['inactiveId']));
			$statusSql = "	UPDATE `".TBL_INV_ITEMS."` 
							SET `status` = '0' 
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."' 
							WHERE `id` = '".$statusId."'";

		}
		if(executeSql($statusSql)){
			$err = 0;
			$_SESSION['successMsg'] = 'Items '.selectColumn(TBL_INV_ITEMS,'name'," WHERE `id` = '".$statusId."'").' status has been changed sucessfully.';
		}else{
			$err = 1;
			$_SESSION['errorMsg'] = 'Items '.selectColumn(TBL_INV_ITEMS,'name'," WHERE `id` = '".$statusId."'").' status has not been changed sucessfully.';
		}

	}else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){

		checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_ITEMS,'delete');
		$deleteIds = encryptor(decrypt,$_REQUEST['delId']);
		$delSql = "DELETE FROM `".TBL_INV_ITEMS."` WHERE `id` = '".addslashes($deleteIds)."'";
		$sqlDelUsers = selectRow(TBL_INV_ITEMS," WHERE `id` = '".addslashes($deleteIds)."'");
		if(executeSql($delSql)){
			$err = 0;
			
			$_SESSION['successMsg'] = 'One Items '.$sqlDelUsers["name"].' has been deleted sucessfully.';
			}else{
				$err = 1;
				$_SESSION['errorMsg'] = 'Unable to delete Items '.$sqlDelUsers["name"];
		}
	}

	

	if($_REQUEST['searchFormSubmit']==''){
			$sqlResult1 = mysqli_query($appConnect, "SELECT * FROM ".TBL_REPORT." WHERE table_name = '".TBL_INV_ITEMS."' AND id_shop = ".$_SESSION['shop'] ." ORDER BY display_order");

			if(mysqli_num_rows($sqlResult1)){
				while ($sqlRow = mysqli_fetch_object($sqlResult1)){
					if($sqlRow->default_select == 1){

						$field_name[]=$sqlRow->field_name;	
						$field_label[]=$sqlRow->field_label;			

					} 
				}
			}

			$sqlResultOrder = mysqli_query($appConnect, "SELECT field_name FROM ".TBL_REPORT." WHERE table_name = '".TBL_INV_ITEMS."'AND enable_order_by = 1 AND id_shop = ".$_SESSION['shop']." order BY display_order asc LIMIT 0,1" );
				if(mysqli_num_rows($sqlResultOrder)){
					
					$sqlRowOrder = mysqli_fetch_object($sqlResultOrder);
						
					$EnableOrderBy	=	$sqlRowOrder->field_name;
				}							  
												  
	}else{
		$field_name =$_REQUEST['field_name'];
		$fieldname = @implode("','",$field_name);
		$sqlResult1 = mysqli_query($appConnect, "SELECT * FROM ".TBL_REPORT." WHERE table_name = '".TBL_INV_ITEMS."' AND id_shop = ".$_SESSION['shop'] ." AND field_name IN ('".$fieldname."')  ORDER BY display_order");

			if(mysqli_num_rows($sqlResult1)){
				while ($sqlRow = mysqli_fetch_object($sqlResult1)){
	
					$field_label[]=$sqlRow->field_label;		
				}
			}
		$EnableOrderBy=$_REQUEST['EnableOrderBy'];
		
	}



$submenu = $_GET['submenu'];
if($submenu=='307'){
	$sqlResult1 = "SELECT * FROM ".TBL_ATTRIBUTES." WHERE table_name = 'items_type' AND field_value IN ('Menu') AND id_shop = ".$_SESSION['shop'] ." ";
	$QuerySQL1	=	mysqli_query($connNew,$sqlResult1);
	
		while($sqlRow = mysqli_fetch_object($QuerySQL1)){
	        $list = $sqlRow->id;
			$string .= $list.',';
		} 
		
}else if($submenu=='235'){ //Ingredients
	$sqlResult1 = "SELECT * FROM ".TBL_ATTRIBUTES." WHERE table_name = 'items_type' AND field_value IN ('Ingredients') AND id_shop = ".$_SESSION['shop'] ." ";
	$QuerySQL1	=	mysqli_query($connNew,$sqlResult1);
	
		while($sqlRow = mysqli_fetch_object($QuerySQL1)){
	        $list = $sqlRow->id;
			$string .= $list.',';
		}
}else if($submenu=='248'){ //Landuary
	$sqlResult1 = "SELECT * FROM ".TBL_ATTRIBUTES." WHERE table_name = 'items_type' AND field_value IN ('Laundry') AND id_shop = ".$_SESSION['shop'] ." ";
	$QuerySQL1	=	mysqli_query($connNew,$sqlResult1);
	
		while($sqlRow = mysqli_fetch_object($QuerySQL1)){
	        $list = $sqlRow->id;
			$string .= $list.',';
		}
}else if($submenu=='247'){ //Span Item
	$sqlResult1 = "SELECT * FROM ".TBL_ATTRIBUTES." WHERE table_name = 'items_type' AND field_value IN ('Spa and Health Club') AND id_shop = ".$_SESSION['shop'] ." ";
	$QuerySQL1	=	mysqli_query($connNew,$sqlResult1);
	
		while($sqlRow = mysqli_fetch_object($QuerySQL1)){
	        $list = $sqlRow->id;
			$string .= $list.',';
		}
}else if($submenu=='249'){ //Othere Item
	$sqlResult1 = "SELECT * FROM ".TBL_ATTRIBUTES." WHERE table_name = 'items_type' AND field_value IN ('Others') AND id_shop = ".$_SESSION['shop'] ." ";
	$QuerySQL1	=	mysqli_query($connNew,$sqlResult1);
	
		while($sqlRow = mysqli_fetch_object($QuerySQL1)){
	        $list = $sqlRow->id;
			$string .= $list.',';
		}
}	
else if($submenu=='289'){ //Othere Item
	$sqlResult1 = "SELECT * FROM ".TBL_ATTRIBUTES." WHERE table_name = 'items_type' AND field_value IN ('Store Items') AND id_shop = ".$_SESSION['shop'] ." ";
	$QuerySQL1	=	mysqli_query($connNew,$sqlResult1);
	
		while($sqlRow = mysqli_fetch_object($QuerySQL1)){
	        $list = $sqlRow->id;
			$string .= $list.',';
		}
}	
 $item_list = rtrim($string,',');//echo '===================='.$list = $sqlRow->id.$item_list;

//debugData($field_name);debugData($field_label);
	 $sql = local_SelectQuery_Mst_Items($_REQUEST['tableName'],$field_name,$field_label,$EnableOrderBy,$item_list);
	//echo "hello"; exit;
	$QuerySQL	=	mysqli_query($connNew,$sql);
	$numRows = @mysqli_num_rows($QuerySQL);

	$TotalCountRecord = selectColumn(TBL_INV_ITEMS,'count(id)',' WHERE id_shop= "'.addslashes($_SESSION['shop']).'" ');

	$total = @mysqli_num_rows($QuerySQL);
?>

<?php include_once("../includes/header.php"); ?>
<?php include_once("../includes/left.php")?>
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
<?php 
	if($_REQUEST['toggleCheckOpenStatus']=='' || $_REQUEST['toggleCheckOpenStatus']=='0'){
		$DispalyClass="display:none;";
		$viewClass="";
		$viewIcons='fa fa-plus-square-o fa-1x';
	}else{
		$DispalyClass="";
		$viewClass='fieldset';
		$viewIcons='fa fa-minus-square-o fa-1x';
	}
?>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
   <?php   $session = $_GET['submenu'] ; ?>
     <section class="content-header">
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
		<?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>
      </h3>
      <?php echo breadCrumbs(); ?>
    </section>

    <!-- <section class="content-header">
      <h1>
        User Manager
        <small>Manage Users</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="manageUsers.php">User  Manager</a></li>
        <li class="active">Manage Users</li>
      </ol>
    </section> -->
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
			               	 <input type="hidden" name="table_name" value="<?php echo TBL_INV_ITEMS;?>" />
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
        		<h3 class="box-title">Search <small>Total Records: (<?=$total;?>) &nbsp;</small> </h3>
		  		<!-- <div class="btn-group  pull-right">
		  			<a type="button" class="btn btn-success" href="editItems.php">Add Items</a>
		  							<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
		  								<span class="caret"></span>
		  								<span class="sr-only">Toggle Dropdown</span>
		  			</button>
		  			<ul class="dropdown-menu" role="menu">
		  								<?php ?><li><a title="Export to excel file" href="#"><img src="../images/excel-icon.jpg" width="20" height="20" />&nbsp;Import</a></li>
		  								<?php ?>
		  				  
		  			</ul>
		  						</div> -->
				<div class="btn-group  pull-right">
		          	<a type="button" class="btn btn-success" href="editItems.php?submenu=<?php echo $_GET['submenu']; ?>">Add Items</a>
			         <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
			            <span class="caret"></span>
			            <span class="sr-only">Toggle Dropdown</span>
			        </button>
		         
		          	<ul class="dropdown-menu" role="menu">
		          		<li><a title="Import to excel file" href="#" data-toggle="modal" data-target="#importComapnyModal" ><img src="../images/excel-icon.jpg" width="20" height="20" />&nbsp;Import</a></li>
		            	<li><a title="Export to excel file" href="masterExportTableItem.php?fileType=xls&tableName=<?php echo TBL_INV_ITEMS;?>&itemType=<?php echo $item_list;?>"><img src="../images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
		            	
		          	</ul>
	        	</div>

        	<form name="searchForm" id="phpForm" action="" method="get">
        		<input type="hidden" value="1" name="searchFormSubmit" />
        		<div class="box-body">
					<div class="row">
						
              			<div class="col-md-6 col-sm-6">
			            	<div class="form-group">
			            		<label>Item Name </label>
                     
			                      <?php $categoryDropDown = '<select class="form-control select2" name="search_name" style="width:100%">
			                        <option value="">Select Items</option>';
			                        $resUserLevel = selectSql(TBL_INV_ITEMS," WHERE `status` = '1' AND  `id_shop` = '".addslashes($_SESSION['shop'])."' AND id_mst_attributes_item_type != 177 and  `id_mst_attributes_item_type` = '".addslashes($list)."'",' ORDER BY `name`');
			                        if($db->num_rows2($resUserLevel)){
			                          while($resultUserLevel = $db->fetch_object2($resUserLevel)){
			                            if($_REQUEST['search_name'] == $resultUserLevel->id){
			                              $selected = 'selected="selected"';
			                            }else{
			                              $selected = '';
			                            }
			                            $categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->item_code).' | '.ucfirst($resultUserLevel->name).'</option>';
			                              }
			                          }
			                          echo $categoryDropDown .= '</select>';
			                          ?>
			            		
			            	</div>
			            </div>
                       	
                       		 
            			<div class="form-group col-md-6">
		                	<label>Status</label>
		                	<?php 
								if($_REQUEST['status'] == '1'){
										$selected1 = 'selected="selected"';
								}elseif($_REQUEST['status'] == '0'){
										$selected0 = 'selected="selected"';
								}
								 echo $statusDropDown	 = '<select class="form-control select2" name="status" style="width:100%;"> <option value="">Both</option>
									<option '.$selected1.' value="1">Active</option>
									<option '.$selected0.' value="0">Inactive</option>
								</select>';
							?>
		              	</div>                  
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
		        								<label >Order By</label>
		        							</div>
		        							<div class="col-md-3 col-sm-3">
		        								<select class="form-control select2" style="width:100%;" name="EnableOrderBy" id="EnableOrderBy" >
		        									<?php /*?><option selected="selected" value="">----Order By----</option><?php */?>
		        									<?php
		    											$sqlResult = mysqli_query($appConnect, "SELECT field_name, field_label FROM ".TBL_REPORT." WHERE table_name = '".TBL_INV_ITEMS."'AND enable_order_by = 1 AND id_shop = ".$_SESSION['shop']." order BY display_order asc" );

		    											if(mysqli_num_rows($sqlResult)){
														
		    												while ($sqlRow = mysqli_fetch_object($sqlResult)){
															
		    											?>
				    									<option value="<?php  echo $sqlRow->field_name?>"><?php  echo $sqlRow->field_label?></option>

		    										<?php } }?>		
		        								
		        								</select>
		        							
		        							</div>
		        						</div>
		        						<div class="row">
			    							<?php
											is_array($_REQUEST['field_name']) ? '' : $_REQUEST['field_name']=array();
			    									$sqlResult = mysqli_query($appConnect, "SELECT * FROM ".TBL_REPORT." WHERE table_name = '".TBL_INV_ITEMS."' AND id_shop = ".$_SESSION['shop'] ." ORDER BY display_order");

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
			    							
	    								<?php } }?>		 <input type="hidden" name="tableName" value="<?php
echo TBL_INV_ITEMS;?>" />	 </div>
		    						
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
			        		<input type="hidden" name="submenu" value="<?php echo $_GET['submenu']; ?>" />
                            <input name="Search" type="submit" class="btn btn-primary" value="Search" />
	                    	<button class="btn btn-primary" type="button" onclick="generateFile()">Generate</button>
						        
			        	</div>
					</div>
	        	</div>
			</form>
        </div>
		</div>
        <div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
          	<div class="box">
            <div class="box-header">
              <h3 class="box-title">List of <?php echo currentNavigation_id($session)['submenu']; ?> (<?= $numRows; ?>)</h3>
            </div>
			<form name="listingForm" action="" method="post">
               <input type="hidden" value="" name="act" />
			     <div id="listingDiv"></div>
            	<!-- /.box-header -->
            	<div class="box-body table-responsive">
              		<table id="myTable" class="table table-striped table-bordered dataTable no-footer" cellspacing="0">
	               		<?php /* <thead>
	                	<tr>
	                  		<th width="10%"><!--<input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' />--> S.No.&nbsp;</th>
	                  <th>Hotel Name</th>
					  <th>Hotel Category</th>
	                  <th>Status</th>
					  <th>Action</th>
	                </tr>
	                </thead> */ ?>	
	              
	                <thead>
	                	<?php 
							$fields_num = @mysqli_num_fields($QuerySQL);
						?>
						<tr>
							<th width="3%"> S.No.&nbsp;</th>
							<?php 
					  			for($i=0; $i<$fields_num; $i++){
									$field = mysqli_fetch_field($QuerySQL);
									if($field->name != 'id'){
										$dataWriteheader .= "<th>{$field->name}</th>";
									}

								}
								echo $dataWriteheader;
							?>
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
								//print_r($row) ;

									if($cell=='Active'){						
									
										$cell='<span onclick="location.href=\'manageItems.php?inactiveId='.encryptor(encrypt,$Id).'&submenu='.$_GET['submenu'].'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>';
									}else if($cell=='Inactive'){
										$cell='<span onclick="location.href=\'manageItems.php?activeId='.encryptor(encrypt,$Id).'&submenu='.$_GET['submenu'].'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';
									}
									if($value == 1){
										$Id = $cell;
									}else{

										$dataWrite .= "<td>$cell</td>";
									}

									//$namearra= array_search("status",$field_name,true);

									$value ++;
								}

							$strLocation = 'editItems.php?eId='.encryptor(encrypt,$Id).'&submenu='.$_GET['submenu'].'&action=edit&page='.$_REQUEST['page'];
						
	
							// $table_name = array(TBL_PURCH_DETAILS);
                			// $ajaxCheckTransactions = CheckTransactionsItems($Id, $table_name);

							//if($ajaxCheckTransactions != '1'){
								
							//echo "SELECT * FROM pos_purch_details WHERE id_mst_items='".$Id."' ";
						//	$rows = mysqli_num_rows(mysqli_query($connNew,"SELECT * FROM pos_purch_details WHERE id_mst_items='".$Id."' "));
						//	if($rows <= 0){
								
								
$rows = mysqli_num_rows(mysqli_query($connNew,"SELECT * FROM ".TBL_INV_INDENT_DETAILS." WHERE id_inv_items IN ($Id) "));
$rows1 = mysqli_num_rows(mysqli_query($connNew,"SELECT * FROM ".TBL_PURCH_DETAILS." WHERE id_mst_items IN ($Id) "));
$rows2 = mysqli_num_rows(mysqli_query($connNew,"SELECT * FROM ".TBL_INV_PO_DETAILS." WHERE id_inv_items IN ($Id) "));
								
if($rows <= 0 && $rows1 <= 0 && $rows2 <= 0){							
								
								$dataWrite .='<td><img src="../images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onclick="window.location.href=\''.$strLocation.'\'" />&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/delete.gif" style="cursor:pointer;" title="Delete" name="'.$row->name.'" id="'.encryptor(encrypt,$Id).'" onClick="deletes(this.id);"/></td>';
							}else{
								$dataWrite .='<td><img src="../images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onclick="window.location.href=\''.$strLocation.'\'" />&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/chat.png" style="cursor:pointer; " title="In Use" /></td>';
							}
							
								$dataWrite .= "</tr>";
							}
							echo $dataWrite;
								if($total>0){

									  /* $counter = 1;
									  while($row = mysqli_fetch_object($result)){?>
					                <tr>
					                  <td><?php echo $counter++;?></td>
					                  <td><?= $row->name; ?></td>
									  <td><?php echo selectColumn(TBL_HOTEL_CATEGORY,'name'," WHERE `id` = '".$row->id_mst_hotel_category."'");   ?></td>
					                  <td><?=$row->status=='1'?'<span onclick="location.href=\'manageHotels.php?inactiveId='.encryptor(encrypt,$row->id).'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageHotels.php?activeId='.encryptor(encrypt,$row->id).'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>			 
									  <td><img src="../images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editHotels.php?eId=<?=encryptor(encrypt,$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $row->first_name; ?>" id="<?php echo encryptor(encrypt,$row->id);?>" onClick="deletes(this.id);" /></td>
					                </tr>
					               <?php }?> */
								}
							?>
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
</div>

<?php include_once("../includes/footer.php")?> 

  <script type="text/javascript">
	$(document).ready(function(){

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
	          self.location='manageItems.php?delId='+sid+'&action=delete&submenu=<?=$_GET["submenu"]?>&page=<?=$_REQUEST["page"]?>';
	     } 
	     else
	     {
	        self.location='manageItems.php?submenu=<?php echo $_GET["submenu"] ?>';
	      }
	     });
    }

    

    function generateFile(){

    	var form = $("#phpForm");
    	if(form.parsley().validate()){
			var formData = $("#phpForm").serialize();
			window.location.href = "functions/exportTable.php?"+formData;
    	}else{

    	}
    	
    }
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




