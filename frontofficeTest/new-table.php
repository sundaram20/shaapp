<?php

                  	        // ----------cate---------

							$sql = " SELECT * FROM `".TBL_CUSTOMER."` WHERE type='2' ";

							if($_REQUEST['eId'] != ''){

								$sql .= " AND `id_company` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";

							}

							//echo $sql;
							
							$db->query($sql);

							$numRows= $db->num_rows();

							$pagging = new pagingClass($sql,$setpage);

							$db->query($pagging->getQuery());

							$total = $db->num_rows(); 

		                  ?>
              			<div class="col-md-12 form-group">
              				<div class="box-header with-border">
              					<button type="button" class="btn btn-success btn-xs" id="btnShow"><i class="fa fa-plus"></i></button>
								<h3 class="box-title">List of Contacts : <a><?php echo selectColumn(TBL_COMPANY,'name'," WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'"); ?></a></h3>
								<a href="editCustomer.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" class="btn btn-success pull-right"><i class="fa fa-plus fa-x1"></i> Add New Contacts</a>
							</div>

							<div class="form-group has-error" align="center">
								<?php if($_SESSION['errorMsg']){?>
								 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
								<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
								<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
								<?php unset($_SESSION['successMsg']);}?>

							</div>     
	              			
							<div id="showContactDiv" class="box" style="display: none">
								<form name="listingForm" action="" method="post">
								<input type="hidden" value="" name="act" />
								<div id="listingDiv"></div>
								<div class="box-body table-responsive">
									<table id="example2" class="table table-bordered table-striped">
										<thead>
											<tr>
											<th width="10%"><input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' /> Check All&nbsp;</th>
											<th>Contact Name</th>
											<th>Primary Contact</th>
											<th>Secondary Contact</th>
											<th>Email</th>
											<th>Status</th>
											<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php 				 				
												if($total > 0){$counter = 1;
												  while($rowcontact = $db->fetch_object()){?>
												  	<tr>

									                  <td><?php echo $counter++;?>.&nbsp;</td>

													  <td><?php echo $rowcontact->first_name.' '.$rowcontact->last_name;   ?></td>

													  <td>
													  	<?php if($rowcontact->primary_contact == 1){
													  			echo $rowcontact->primary_mobile;
													  		}else{
													  			echo $rowcontact->primary_landline;
													  		} 
													  	?>
													  </td>
													  <td>
													  	<?php if($rowcontact->secondary_contact == 1){
													  			echo $rowcontact->secondary_landline;
													  		}else{
													  			echo $rowcontact->secondary_mobile;
													  		} 
													  	?>
													  </td>
													  <td><?php echo $rowcontact->email ?></td>
									                  <td><?=$rowcontact->status=='1'?'<span onclick="location.href=\'editCompany.php?inactiveId='.encryptor(encrypt,$rowcontact->id).'&eId='.$_GET['eId'].'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'editCompany.php?activeId='.encryptor(encrypt,$rowcontact->id).'&eId='.$_GET['eId'].'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>			 

													  <td><img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editCustomer.php?eId=<?=$_GET['eId']?>&id=<?=encryptor(encrypt,$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;<!--<img src="images/delete.gif" style="cursor:pointer;" title="Delete" onClick="if(confirm('Are you sure that you want to delete this record <?=$row->name;?>?')){window.location.href='manageCustomer.php?delId=<?=encryptor(encrypt,$row->id_customer)?>&eId=<?=$_GET['eId']?>&action=delete&page=<?=$_REQUEST['page']?>';}"/>--></td>
													</tr>
												<?php }?> 
												<!-- <tr>

												 <td align="left" colspan="5">

												 <input name="delete_sel" type="button" class="btn btn-warning" value="Delete" onClick="javascript:formSubmit('delete');"/>&nbsp;&nbsp;&nbsp;&nbsp; 

												 <input name="active_sel" type="button" class="btn btn-success" value="Active" onClick="javascript:formSubmit('activate');"/>&nbsp;&nbsp;&nbsp;&nbsp;

												  <input name="inactive_sel" type="button" class="btn btn-danger" value="Inactive" onClick="javascript:formSubmit('inactivate');"/> </td>

												</tr>-->
											<tr>	 
												<td align="right" colspan="5"><?php  echo $pagging->getLinks();?> </td>
											</tr>                
										<?php }else {?>
										 <tr>
						                    <td height="200" align="center" colspan="4">---- No Record Found ---- </td>
						                 </tr>                 
										<?php }?>
										</tbody>           
									</table>
								</div>
							</form>
							</div>	
						</div>