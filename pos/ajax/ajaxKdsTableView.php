<div id="kdstableview"><div class="col-md-10 col-sm-10 kw-leftbar">
<?php  if($numRows==0){ ?><div style="text-align:center;"><?php echo 'No Record Found.';?></div><?php }?>
<input  type="hidden" name="id_mst_items" id="id_mst_items" value="<?php echo $_REQUEST['id_mst_items'];?>"
<!--tab col starts-->					


<style>
    .glow-container {
        position: relative;
        animation: glow 1.5s ease-in-out infinite, fadeIn 0.5s ease-out;
		transition : all 500ms
        /* box-shadow: 0 0 10px rgba(0, 191, 255, 0.5); */
    }

    @keyframes glow {
        0%, 100% {
            /* background-color: rgba(0, 191, 255, 0.3); */
        }

        50% {
            /* background-color: rgba(0, 191, 255, 0.5); */
            box-shadow: 0 0 20px rgba(0, 191, 255, 0.8);
        }
    }

    

    @keyframes shake {
        0%, 100% {
            transform: translateX(0);
        }

        10%, 30%, 50%, 70%, 90% {
            transform: translateX(-5px);
        }

        20%, 40%, 60%, 80% {
            transform: translateX(5px);
        }
    }

    /* Apply shaking effect */
    .glow-container.shake {
        animation: shake 0.5s ease-in-out;
    }


	#animatedBox .tabheading{
		background-color : #378e37!important;
	}

	.glow-container::-webkit-scrollbar {
    width: 5px;
}
</style>

<style>

.kw-con{
	margin : 10px!important;
}

</style>

<script>
    // JavaScript to add the 'shake' class every 3 seconds
    setInterval(function () {
        const animatedBox = document.getElementById('animatedBox');
        animatedBox.classList.add('shake');
        setTimeout(function () {
            animatedBox.classList.remove('shake');
        }, 500); // The duration of the 'shake' animation is 0.5 seconds
    }, 3000); // Trigger the animation every 3 seconds




	function removeAnimatedBox() {
    var checkbox = document.getElementById('removeCheckbox');
    var animatedBox = document.getElementById('animatedBox');

    if (checkbox.checked) {
      // If checkbox is checked, remove the specified ID and class
      animatedBox.removeAttribute('id');
      animatedBox.classList.remove('glow-container');
    } else {
      // If checkbox is unchecked, restore the ID and class
      animatedBox.setAttribute('id', 'animatedBox');
      animatedBox.classList.add('glow-container');
    }
  }
</script>


<!--tab col starts-->
<?php /* ?>
						<div class="col-md-2  col-sm-2 kw-con glow-container" id="animatedBox">
						  <div class="tab-container " >
						    <div class="tabbox" >
						      <div class="tabheading" id="checkcont" >
						        <div class=" d-flex">
								<div class="tbname d-flex" style="margin:4px 14px 4px 4px;align-items:center;">
                                  
							
  <input type="checkbox" id="removeCheckbox" onchange="removeAnimatedBox();" style="margin-top : 0!important; ">
  <label for="removeCheckbox" style="padding-left : 3px!important; font-size : 0.95rem!important">Preparing</label>
								  
													  </div>
							          <div class="tbsteward">
								            <div class="statusbox">
									           <!-- <div class="d-flex ">
									            	<h4><button class="kotwiseModal"  id="kwstatus" ><?php echo $row->kot_status; ?></button></h4>
									            	<input type="hidden" id="pos_id" value="<?php echo $row->id; ?>"> 
												</div>-->
								            </div>
								            <h4 TITLE="Table"><?php echo $TableDetails['table_name']; ?></h4> <h4 title="Time"><?php echo $TableDetails['time']; ?></h4>     
							          </div>
						         </div>        
						      </div>
						      <!--table heading ends-->
						      <div class="tabcontent" id="kwbox" > 
						        <table class="table table-responsive table table-striped table-bordered dataTable no-footer songs-table">
									<thead style="display:none;">
										<tr>
										  <th>  Items Name</th>
										  <th style="width:10%;"> Qty </th>
										</tr>
									</thead>
									<tbody>
										<?php
											$checkBoxServedStatus='';
										foreach($TableDetails['item'] as $value){
											
											 if($value['checkBoxServed']==1){
											 $checkBoxServedStatus=1;
										 }
											
												$qty = (int)$value['qty'];
												$check_orderis_ready = $value['check_orderis_ready'];
											$old_qty = $value['old_qty'];
										 //  debugData($TableData);
?>
<tr>
											<td><span><?php echo (int)$value['tot_qty'].'x'; ?> </span>&nbsp; <?php echo ucwords(strtolower($value['item_description'])); ?> <div class="itemRemarksLabel"><?php echo $value['special_request_name']; ?></div>
												</td>
								            
											<td style="width:50px;">
												<label class="switchCheck">
													<input type="checkbox" <?php echo $value['checked']; ?>  <?php echo $disabled; ?>class="check_class" value="" id="detail_status-<?php echo $value['id_pos_purch_details']; ?>" name="detail_status" onclick="ChangeCookStatus(this.id,<?php echo $value['tot_qty']; ?>,<?php echo $value['old_qty']; ?>)"><span class="slider round"></span>
													<input type="hidden"  value="<?php echo $value['id_pos_purch_details']; ?>" class="check">
												</label>
											</td></tr>
<?php
											  } ?>		
										
									</tbody>
						        </table>
						      </div>

						      <!--tabcontent ends-->
						        <div class="tabheading" id="checkcont2">
						        <div class=" d-flex" id="checkboxs">
						          <div class="tbsteward">
						            <h4 title="Steward"><?php echo $TableDetails['steward_name']; ?></h4> <h4 title="KOT"><?php echo '#'.$TableDetails['mdoc_no']; ?></h4>   
						          </div>
						         
						          <div class="tbname d-flex" style="margin:4px 14px 4px 4px;align-items:center;">
                                  
			  <input type="checkbox" name="serve_status" <?php echo $TableDetails['checkServedStatus']; ?> id="serve_status_<?php echo $TableDetails['mdoc_no']; ?>_<?php echo $Table; ?>" onClick="CheckServerStatus('<?php echo $TableDetails['mdoc_no']; ?>','<?php echo $TableDetails['id_pos_purch']; ?>','<?php echo $Table; ?>');"  > &nbsp;<?php echo $ServedText; ?>
              
						          </div>

						          </div>
						         
						      </div>
						      <!--table heading ends-->
						    </div>
						  </div>
						</div>
			        	<!--tab col ends-->
                        <?php */ ?>

					<?php
					//print_r($_SESSION);
						//	debugData($itemlistArray);
							 //debugData($pendingKotArray);
							 //debugData($listPrintArray);
							foreach($pendingKotArray as $TableDetails2){ 
							foreach($TableDetails2 as $Table=>$TableDetails){ 
							
						
						 $PreparingStatus	= $TableDetails['PreparingStatus'];
						  $doc_enable_status	= $TableDetails['doc_enable_status'];
						  
						  if($doc_enable_status=='1'){
							  
							  if($PreparingStatus=='0'){
							   $ClassAdd	=	'glow-container';
							   $IDAdd	=	'id="animatedBox"';
								  $checkboxStatus	='';
							  }else{
								   $ClassAdd	=	'';
							   		$IDAdd	=	'';
								  $checkboxStatus	='checked="checked"';
								  
								  
								  }
							  }else{
								  
								  $ClassAdd	=	'';
							   $IDAdd	=	'';

								  
								  }	
						?>			  
			  
		        	 	<!---->
						  

						

						<!-- this is actual tab col starts -->

						
                        <div class="col-md-2  col-sm-2 kw-con <?php echo $ClassAdd; ?>" <?php echo $IDAdd; ?> >
						  <div class="tab-container">
						    <div class="tabbox">
						      <div class="tabheading" id="checkcont">
						        <div class=" d-flex">
                                <?php if($doc_enable_status=='1'){ 
								?>
									<div class="tbname d-flex" style="margin:4px 14px 4px 4px;align-items:center;">
                                  
							
  <input type="checkbox" <?php echo $checkboxStatus; ?>  id="removeCheckbox" onchange="removeAnimatedBox();updatekotPreparing('<?php echo $TableDetails['mdoc_no']; ?>','<?php echo $TableDetails['id_pos_purch']; ?>');" style="margin-top : 0!important; ">
  <label for="removeCheckbox" style="padding-left : 3px!important; font-size : 0.95rem!important">Preparing</label>
								  
													  </div>
                                                      
                                               <?php } ?>       
							          <div class="tbsteward">
								            <div class="statusbox">
									           <!-- <div class="d-flex ">
									            	<h4><button class="kotwiseModal"  id="kwstatus" ><?php echo $row->kot_status; ?></button></h4>
									            	<input type="hidden" id="pos_id" value="<?php echo $row->id; ?>"> 
												</div>-->
								            </div>
								            <h4 TITLE="Table"><?php echo $TableDetails['table_name']; ?></h4> <h4 title="Time"><?php echo $TableDetails['time']; ?></h4>     
							          </div>
						         </div>        
						      </div>
						      <!--table heading ends-->
						      <div class="tabcontent" id="kwbox" > 
						        <table class="table table-responsive table table-striped table-bordered dataTable no-footer songs-table">
									<thead style="display:none;">
										<tr>
										  <th>  Items Name</th>
										  <th style="width:10%;"> Qty </th>
										</tr>
									</thead>
									<tbody>
										<?php
											$checkBoxServedStatus='';
										foreach($TableDetails['item'] as $value){
											
											 if($value['checkBoxServed']==1){
											 $checkBoxServedStatus=1;
										 }
											
												$qty = (int)$value['qty'];
												$check_orderis_ready = $value['check_orderis_ready'];
											$old_qty = $value['old_qty'];
										 //  debugData($TableData);
?>
<tr>
											<td><span><?php echo (int)$value['tot_qty'].'x'; ?> </span>&nbsp; <?php echo ucwords(strtolower($value['item_description'])); ?> <div class="itemRemarksLabel"><?php echo $value['special_request_name']; ?></div>
												</td>
								            
											<td style="width:50px;">
												<label class="switchCheck">
													<input type="checkbox" <?php echo $value['checked']; ?>  <?php echo $disabled; ?>class="check_class" value="" id="detail_status-<?php echo $value['id_pos_purch_details']; ?>" name="detail_status" onclick="ChangeCookStatus(this.id,<?php echo $value['tot_qty']; ?>,<?php echo $value['old_qty']; ?>)"><span class="slider round"></span>
													<input type="hidden"  value="<?php echo $value['id_pos_purch_details']; ?>" class="check">
												</label>
											</td></tr>
<?php
											  } ?>		
										
									</tbody>
						        </table>
						      </div>

						      <!--tabcontent ends-->
						        <div class="tabheading" id="checkcont2">
						        <div class=" d-flex" id="checkboxs">
						          <div class="tbsteward">
						            <h4 title="Steward"><?php echo $TableDetails['steward_name']; ?></h4> <h4 title="KOT"><?php echo '#'.$TableDetails['mdoc_no']; ?></h4>   
						          </div>
						         
						          <div class="tbname d-flex" style="margin:4px 14px 4px 4px;align-items:center;">
                                  
			  <input type="checkbox" name="serve_status" <?php echo $TableDetails['checkServedStatus']; ?> id="serve_status_<?php echo $TableDetails['mdoc_no']; ?>_<?php echo $Table; ?>" onClick="CheckServerStatus('<?php echo $TableDetails['mdoc_no']; ?>','<?php echo $TableDetails['id_pos_purch']; ?>','<?php echo $Table; ?>');"  > &nbsp;<?php echo $ServedText; ?>
              
						          </div>

						          </div>
						         
						      </div>
						      <!--table heading ends-->
						    </div>
						  </div>
						</div>



						<!-- this is actual tab col ends -->




							<?php  } } //echo "select "; ?>	
	        	</div>
                </div>

               <!--modal for Served-->
               <!-- Button trigger modal -->
				<!--<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
				  Launch demo modal
				</button>-->

				<!-- Modal -->
				<div class="modal" id="servedModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				  <div class="modal-dialog" role="document">
				    <div class="modal-content">
				      <div class="">
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				       <input type="hidden" name="mdoc_no_new" id="mdoc_no_new" value="" />
                       <input type="hidden" name="id_new" id="id_new" value="" />
                       <input type="hidden" name="id_mst_outlet_new" id="id_mst_outlet_new" value="" />
                       <input type="hidden" name="ServerStatusWithoutCook" id="ServerStatusWithoutCook" value="0" />
<input type="hidden" name="ServerStatus" id="ServerStatus" value="<?php echo $servestatus;?>" />
                       
                       
				      </div>
				      <div class="modal-body">
				       <p> One Or More items are  not Ready !!<br><br>

				        Still do  you want to continue ? </p>
				      </div>
				      <div class="modal-footer">
				      	 <button type="button" class="btn  o-btn" onclick="CheckServerStatusWithoutCook();">Yes</button>
				        <button type="button" class="btn o-btn" data-dismiss="modal">No</button>
				       
				      </div>
				    </div>
				  </div>


				


				</div>

				