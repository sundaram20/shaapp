<?php error_reporting(0);
				   include_once("../../config/auto_loader.php");
				   //print_r($_REQUEST);
				   $purch_id=$_REQUEST['id_fo_bill'];
$status=$_REQUEST['status'];
$id_reservation=$_REQUEST['id_reservation'];
$grand_total_amount=round($_REQUEST['amount']);
?>
                    <tr>
                    
                      <td colspan="10">
                    <div id="div<?php echo $purch_id;?>" class="targetDiv">
                      <form name="listingForm_<?php echo $purch_id;?>" id="listingForm_<?php echo $purch_id;?>" action="" method='POST' data-parsley-validate>
                        <input type="hidden" value="" name="act" />
                        <input type="hidden" name="get_purch_id" id="get_purch_id"  value="<?php echo $purch_id;?>"/>
                        <div class="box-body">
                          <div class="card text-dark bg-light">
                            <div class="row">
                              <input type="hidden" class="form-control" readonly placeholder="mdock_no" id="mdock_no" name="mdock_no" value="<?php echo $purch_row->mdoc_no;?>"  >
                            </div>
                            <div class="row">
                              <div class="form-group col-xs-12 col-md-12 col-sm-12" >
                                <div class="box-body" style=" padding-bottom:0px !important;">
                                  <div class="card text-dark bg-light">
                                    <div class="row">
                                      <div class="col-md-12">
                                        <div class="form-group" style="margin-bottom: 1px;" >
                                          <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;" >
                                            <table id="myTableOrder1" class="table dataTable no-footer table-responsive" cellspacing="0" style="font-size:14px;padding: 0px 0px;border: 1px solid #3c8dbc;" >
                                              <thead style="font-size:10px;padding: 0px 0px;">
                                                <tr style="background-color: #3c8dbc;color: #fff;font-variant-caps: all-petite-caps;font-size: 14px;">
                                                  <th></th>
                                                  <th style="width:350px;padding: 5px 9px;"> Payment Mode.&nbsp;</th>
                                                  <th style="width:100px;padding: 5px 9px;">Amount</th>
                                                  <th style=" padding: 5px 9px;">Remarks</th>
                                                  <th style="width:100px;padding: 5px 9px;">Tips</th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                                <tr id="trbgcolor">
                                                <td style="width: 2.5%;"> 
                                                <input type="checkbox"  <?php  if($amount[$purch_id][1]>0){ echo 'checked="checked"';} ?> class="flat-red i-checks checkboxpayamount_1_<?php echo $purch_id;?>"   name="checkboxpayamount" id="checkboxpayamount" value="<?php echo $amount[$purch_id][1].'_1_'.$grand_total_amount.'_'.$purch_id; ?>"  />
                                                 </td>
                                                 <td> <div class="info-box paymentmode" > <span class="info-box-icon bg-aqua paymode-span"> 
                                                 <img src="../images/cashpay.png" style="cursor:pointer;" title=" Bill Payment "  /> </span>
                                                      <div class="info-box-content"> <span class="info-box-text">CASH</span> </div>
                                                      <!-- /.info-box-content --> 
                                                    </div>
                                                    
                                                    <!-- /.info-box --></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][1]>0){ $amount[$purch_id][1];}else {echo 'disabled="disabled"';} ?> class="form-control first-input billingamount_<?php echo $purch_id;?>" name="payamount[<?php echo $purch_id;?>][CASH][]" id="payamount_1_<?php echo $purch_id;?>" onKeyUp="getpayamount(this.value,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>,1);"  value="<?php echo $amount[$purch_id][1]?$amount[$purch_id][1]:0; ?>" style="float: left;" data-parsley-required data-parsley-errors-container="#payamountError"/></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][1]>0){ $amount[$purch_id][1];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" placeholder="Remarks" name="remarks[<?php echo $purch_id;?>][CASH][]" id="remarks_1_<?php echo $purch_id;?>" value="<?php echo $remarks[$purch_id][1]; ?>" style="float: left;"/></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][1]>0){ $amount[$purch_id][1];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" name="tips[<?php echo $purch_id;?>][CASH][]" id="tips_1_<?php echo $purch_id;?>" value="<?php echo $tips[$purch_id][1]; ?>" style="float: left;"/></td>
                                                </tr>
                                                <!----------------------CARD PAYMENT------------------------------------>
                                                 <?php $cardStarCount	=	1;?>
                                                  <?php 
								
								 ?>       
                                                   <tr style="border:1px solid red;background-color:#fff;" id="grid<?php echo $gridNo;?>_<?php echo $purch_id;?>" >
                                                <td style="width: 2.5%;"><input type="checkbox" <?php  if($CardAmount>0){ echo 'checked="checked"';} ?> class="flat-red i-checks checkboxpayamount_2_<?php echo $purch_id;?>"   name="checkboxpayamount" id="checkboxpayamount" value="<?php echo $amount[$purch_id][2].'_2_'.$grand_total_amount.'_'.$purch_id; ?>"  /></td>
                                                <td>
                                                <div class="info-box" style="height:90px !important;min-height: 90px !important;margin-bottom: 0px !important;" > 
                                                <span class="info-box-icon bg-aqua" style="height:90px !important;line-height: 90px !important;"> 
                                                
                                                
                                                <img src="../images/credit_cards_card-512.png" style="cursor:pointer;" title=" Bill Payment "  /> 
                                                </span>
                                                    <div class="info-box-content" style="width: 83%;height: 28px;"> <span class="info-box-text" style="width:87%;float:left;">CARD </span>
                                                       <button class="pull-left btn btn-success btn-xs" type="button"  onclick="addNewGrid(<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>);"  style="margin: 0px;float:right;" ><i class="fa fa-plus-circle"></i></button>
                                                    </div>
                                                    <!-- /.info-box-content --> 
                                                    
                                                   
                                                  
                                                   
                                                   <div class="info-box" style="height:60px !important;min-height: 60px !important;margin-bottom: 0px !important;" > 
                        <span class="info-box-number">
                       
                        <div class="box-body" style="width: 16%;float: left;padding: 0px !important;height: 60px;margin-left: 16px;">
                          <div class="form-group" style="margin-bottom: 0px;">
                            <div style="margin-left: 15px;">
                              <label for="name" class="paymentlable">
                                <input type="radio" class="flat-red" <?php if($id_cardtype == '1'){echo "checked";} ?> value="1" name="cardtype[<?php echo $purch_id;?>][CARDTYPE][][<?php echo $gridNo;?>]" id="cardtype"/>
                              </label>
                            </div>
                            <img class="flagimgs first" src="<?php echo $SITE_URL; ?>/plugins/dist/img/credit/visa.png" alt="Visa"> </div>
                        </div>
                        <div class="box-body" style="width: 16%;float: left;padding: 0px !important; height: 60px;">
                          <div class="form-group" style="margin-bottom: 0px;">
                            <div style="margin-left: 15px;">
                              <label for="name" class="paymentlable">
                                <input type="radio" <?php if($id_cardtype == '2'){echo "checked";} ?> class="flat-red" value="2" name="cardtype[<?php echo $purch_id;?>][CARDTYPE][][<?php echo $gridNo;?>]" id="cardtype"/>
                              </label>
                            </div>
                            <img src="../images/upi.png" style="cursor:pointer;" title="upi"  /> </div>
                        </div>
                        <div class="box-body" style="width: 16%;float: left;padding: 0px !important; height: 60px;">
                          <div class="form-group" style="margin-bottom: 0px;">
                            <div style="margin-left: 15px;">
                              <label for="name" class="paymentlable">
                                <input type="radio" class="flat-red" <?php if($id_cardtype == '3'){echo "checked";} ?> value="3" name="cardtype[<?php echo $purch_id;?>][CARDTYPE][][<?php echo $gridNo;?>]" id="cardtype"/>
                              </label>
                            </div>
                            <img src="../images/neft.png" style="cursor:pointer;" title="upi"  /> </div>
                        </div>
                        
                        
                        
                        
                        
                       </span> </div> </div></td>
                                                 
                                               
                                                    
                                                      <td style="width: 12.5%;"><input type="text" <?php  if($amount[$purch_id][2]>0){ $amount[$purch_id][2];}else {echo 'disabled="disabled"';} ?> class="form-control first-input billingamount_<?php echo $purch_id;?>" name="payamount[<?php echo $purch_id;?>][CARD][]" id="payamount_2_<?php echo $purch_id;?>" onKeyUp="getpayamount(this.value,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>,2);"  value="<?php echo $CardAmount?$CardAmount:0; ?>" style="float: left;" data-parsley-required data-parsley-errors-container="#payamountError"/></td>
                                                      <td style="width: 35.5%;"><div class="form-group" style="width:100% !important;margin-bottom: 5px!important;">
                                                          
                                                          
                                                          <div class="form-group" style="width:100% !important;margin-bottom: 5px!important;">
                                                        <select class="form-control first-input select2" style="width:100% !important;" name="id_bank[<?php echo $purch_id;?>][BANK][]" id="id_bank_2_<?php echo $purch_id;?>"   <?php  if($amount[$purch_id][2]>0){ $amount[$purch_id][2];}else {echo 'disabled="disabled"';} ?>>
                                                          <option value="0">--- Select Bank --- </option>
                                                          <!--select bank-->
                                                          <?php  $resCat = selectSql(TBL_CHARGES," where status='1' and charges_account='8' and id_shop='".addslashes($_SESSION['shop'])."' and name !=' ' ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($id_charges_master == $resultCat->id){
														$selected = '';
													}else{
														$selected = '';
													}
													echo $categoryDropDown = '<option '.$selected.'  value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }?>
                                                        </select>  
                                                        </div>
                                                          
                                                          
                                                          
                                                        </div>
                                                        <input type="text" <?php  if($amount[$purch_id][2]>0){ $amount[$purch_id][2];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" placeholder="Remarks" name="remarks[<?php echo $purch_id;?>][CARD][]" id="remarks_2_<?php echo $purch_id;?>" value="<?php echo $cardRemark; ?>" style="float: left;"/></td>
                                                        
                       
                                                        
                                                      <td ><div class="form-group" style="width:100% !important;margin-bottom: 5px!important;">
                                                          <div class="input-group"  style="width:100% !important;">
                                                            <input type="text" <?php  if($amount[$purch_id][2]>0){ $amount[$purch_id][2];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" name="tips[<?php echo $purch_id;?>][CARD][]" id="tips_2_<?php echo $purch_id;?>" value="<?php echo $CardTips; ?>" style="float: left;"/>
                                                          </div>
                                                        </div>
                                                       <?php if($gridNo>1){?> <a class="btn btn-danger btn-sm" href="javascript:void(0);"  onclick="removeGrid(<?php echo $gridNo;?>,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>);"> <i class="fa fa-trash-o fa-lg"></i> </a><?php } ?></td>
                                                      </td>
                                                    
                                                      </tr>
                                                  
                                               
                                                 
                                                  
                                                  
                                                
                                                <!----------------------ONLINE TRANSFER ------------------------------------>
                                                
                                                
                                                
                                                <!------------------COMPANY--------START------------------------------>
                                                <tr id="trbgcolor">
                                                  <td style="width: 2.5%;"> 
                                                   <input type="checkbox" <?php  if($amount[$purch_id][4]>0){ echo 'checked';} ?> class="flat-red i-checks checkboxpayamount_4_<?php echo $purch_id;?>"   name="checkboxpayamount" id="checkboxpayamount" value="<?php echo $amount[$purch_id][4].'_4_'.$grand_total_amount.'_'.$purch_id; ?>"  />
                                                   </td>
                                                   <td> <div class="info-box" style="height:80px !important;min-height: 80px !important;margin-bottom: 0px !important;"> <span class="info-box-icon bg-aqua" style="height:80px !important;line-height: 70px !important;"> <img src="../images/company.png" style="cursor:pointer;" title=" Bill Payment "  /> </span>
                                                      <div class="info-box-content"> <span class="info-box-text">COMPANY</span> </div>
                                                      <!-- /.info-box-content --> 
                                                    </div></td>
                                                  <td><input type="text"   <?php  if($amount[$purch_id][4]>0){ $amount[$purch_id][4];}else {echo 'disabled="disabled"';} ?> class="form-control first-input billingamount_<?php echo $purch_id;?>" name="payamount[<?php echo $purch_id;?>][COMPANY][]" id="payamount_4_<?php echo $purch_id;?>" onKeyUp="getpayamount(this.value,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>,4);"  value="<?php echo  $amount[$purch_id][4]?$amount[$purch_id][4]:0;  ?>" style="float: left;" data-parsley-required data-parsley-errors-container="#payamountError"/></td>
                                                  <td><div class="form-group" style="width:100% !important; margin-bottom:5px !important;">
                                                      <div class="input-group"  style="width:100% !important;">
                                                        <select class="form-control first-input select2" style="width:100% !important;" name="id_company[<?php echo $purch_id;?>][COMPANY][]" id="id_company_4_<?php echo $purch_id;?>" <?php  if($amount[$purch_id][4]>0){ $amount[$purch_id][4];}else {echo 'disabled="disabled"';} ?>>
                                                          <option value="0">Select Company </option>
                                                          <?php  $resCat = selectSql(MST_COMPANY," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' and name !=' ' ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($id_company[$purch_id][4] == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													echo $categoryDropDown = '<option '.$selected.'  value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }?>
                                                        </select>
                                                      </div>
                                                    </div>
                                                    <input type="text" <?php  if($amount[$purch_id][4]>0){ $amount[$purch_id][4];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" placeholder="Remarks" name="remarks[<?php echo $purch_id;?>][COMPANY][]" id="remarks_4_<?php echo $purch_id;?>" value="<?php echo $remarks[$purch_id][4]; ?>" style="float: left;"/></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][4]>0){ $amount[$purch_id][4];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" name="tips[<?php echo $purch_id;?>][COMPANY][]" id="tips_4_<?php echo $purch_id;?>" value="<?php echo $tips[$purch_id][4]; ?>" style="float: left;"/></td>
                                                </tr>
                                                 <!------------------COMPANY---END----------------------------------->
                                                
                                                <tr id="trbgcolor">
                                                  <td style="width: 2.5%;"> 
                                                  <input type="checkbox" <?php  if($amount[$purch_id][5]>0){ echo 'checked';} ?> class="flat-red i-checks checkboxpayamount_5_<?php echo $purch_id;?>"   name="checkboxpayamount" id="checkboxpayamount" value="<?php echo $amount[$purch_id][5].'_5_'.$grand_total_amount.'_'.$purch_id; ?>"  />
                                               </td>
                                               <td> 
                                                   <div class="info-box paymentmode" > <span class="info-box-icon bg-aqua paymode-span"> <img src="../images/cheq.jpg" style="cursor:pointer;" title=" Bill Payment "  /> </span>
                                                      <div class="info-box-content"> <span class="info-box-text">CHEQUE</span> </div>
                                                      <!-- /.info-box-content --> 
                                                    </div></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][5]>0){ $amount[$purch_id][5];}else {echo 'disabled="disabled"';} ?>  class="form-control first-input billingamount_<?php echo $purch_id;?>" name="payamount[<?php echo $purch_id;?>][CHEQUE][]" id="payamount_5_<?php echo $purch_id;?>" onKeyUp="getpayamount(this.value,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>,5);"  value="<?php echo $amount[$purch_id][5]?$amount[$purch_id][5]:0; ?>" style="float: left;" data-parsley-required data-parsley-errors-container="#payamountError"/></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][5]>0){ $amount[$purch_id][5];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" placeholder="Remarks" name="remarks[<?php echo $purch_id;?>][CHEQUE][]" id="remarks_5_<?php echo $purch_id;?>" value="<?php echo $remarks[$purch_id][5]; ?>" style="float: left;"/></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][5]>0){ $amount[$purch_id][5];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" name="tips[<?php echo $purch_id;?>][CHEQUE][]" id="tips_5_<?php echo $purch_id;?>" value="<?php echo $tips[$purch_id][5]; ?>" style="float: left;"/></td>
                                                </tr>
                                                <tr id="trbgcolor">
                                                  <td style="width: 2.5%;"> 
                                                   <input type="checkbox"  <?php  if($amount[$purch_id][6]>0){ echo 'checked';} ?> class="flat-red i-checks checkboxpayamount_6_<?php echo $purch_id;?>"   name="checkboxpayamount" id="checkboxpayamount" value="<?php echo $amount[$purch_id][6].'_6_'.$grand_total_amount.'_'.$purch_id; ?>"  /></div> 
                                                   </td>
                                                   <td> <div class="info-box paymentmode" > <span class="info-box-icon bg-aqua paymode-span"> <img src="../images/gift.jpg" style="cursor:pointer;" title=" Bill Payment "  /> </span>
                                                      <div class="info-box-content"> <span class="info-box-text">UPI</span> </div>
                                                      <!-- /.info-box-content --> 
                                                    </div></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][6]>0){ $amount[$purch_id][6];}else {echo 'disabled="disabled"';} ?> class="form-control first-input billingamount_<?php echo $purch_id;?>" name="payamount[<?php echo $purch_id;?>][UPI][]" id="payamount_6_<?php echo $purch_id;?>" onKeyUp="getpayamount(this.value,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>,6);"  value="<?php echo $amount[$purch_id][6]?$amount[$purch_id][6]:0; ?>" style="float: left;" data-parsley-required data-parsley-errors-container="#payamountError"/></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][6]>0){ $amount[$purch_id][6];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" placeholder="Remarks" name="remarks[<?php echo $purch_id;?>][UPI][]" id="remarks_6_<?php echo $purch_id;?>" value="<?php echo $remarks[$purch_id][6]; ?>" style="float: left;"/></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][6]>0){ $amount[$purch_id][6];}else {echo 'disabled="disabled"';} ?>  class="form-control first-input" name="tips[<?php echo $purch_id;?>][UPI][]" id="tips_6_<?php echo $purch_id;?>" value="<?php echo $tips[$purch_id][6]; ?>" style="float: left;"/></td>
                                                </tr>
                                                
                        
                        
                        <!--Room TO Settle Start------------------>
                        <tr id="trbgcolor">
                                                  <td style="width: 2.5%;"> 
                                                   <input type="checkbox" <?php  if($amount[$purch_id][7]>0){ echo 'checked';} ?> class="flat-red i-checks checkboxpayamount_7_<?php echo $purch_id;?>"   name="checkboxpayamount" id="checkboxpayamount" value="<?php echo $amount[$purch_id][7].'_7_'.$grand_total_amount.'_'.$purch_id; ?>"  />
                                                   </td>
                                                   <td> <div class="info-box" style="height:80px !important;min-height: 80px !important;margin-bottom: 0px !important;"> <span class="info-box-icon bg-aqua" style="height:80px !important;line-height: 70px !important;"> <img src="../images/company.png" style="cursor:pointer;" title=" Bill Payment "  /> </span>
                                                      <div class="info-box-content"> <span class="info-box-text">ROOM TO </span> </div>
                                                      <!-- /.info-box-content --> 
                                                    </div></td>
                                                  <td><input type="text"   <?php  if($amount[$purch_id][7]>0){ $amount[$purch_id][7];}else {echo 'disabled="disabled"';} ?> class="form-control first-input billingamount_<?php echo $purch_id;?>" name="payamount[<?php echo $purch_id;?>][ROOMTO][]" id="payamount_7_<?php echo $purch_id;?>" onKeyUp="getpayamount(this.value,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>,7);"  value="<?php echo  $amount[$purch_id][7]?$amount[$purch_id][7]:0;  ?>" style="float: left;" data-parsley-required data-parsley-errors-container="#payamountError"/></td>
                                                  <td><div class="form-group" style="width:100% !important; margin-bottom:5px !important;">
                                                      <div class="input-group"  style="width:100% !important;">
                                                        <select class="form-control first-input select2" style="width:100% !important;" name="id_fo_bill[<?php echo $purch_id;?>][ROOMTO][]" id="id_fo_bill_7_<?php echo $purch_id;?>" <?php  if($amount[$purch_id][7]>0){ $amount[$purch_id][7];}else {echo 'disabled="disabled"';} ?>>
                                                          <option value="0">Select Room </option>
       <?php  $resCat = selectSql(FO_BILL," where status='1' and id_mst_shops='".addslashes($_SESSION['shop'])."'  ",' ');
														  
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
			
			$sqlOrderDetail = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($resultCat->id_reservations)."'   group by id_mst_room_no_allocation ");
			
			
			while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){										
			//$id_mst_guest=	selectColumn(FO_RESERVATIONS_DETAILS,'id_mst_guest'," WHERE `id_fo_reservations` = '".addslashes($resultCat->id_reservations)."' and DATE(dated) = '".date('Y-m-d')."'");
			$guestName	=	selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$rowOrderDetail->id_mst_guest."'");
			$roomNumber = selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
			
			$booking_no=	selectColumn(FO_RESERVATIONS,'booking_no'," WHERE `id` = '".addslashes($resultCat->id_reservations)."' ");					
													
													
if($id_fo_bill[$purch_id][7] == $resultCat->id){
	$selected = 'selected="selected"';
}else{
	$selected = '';
}
echo $categoryDropDown = '<option '.$selected.'  value="'.$resultCat->id.'">Room No: '.$roomNumber.' Guest Name: '.$guestName.'</option>';
												}
											  }
											  }?>
                                                        </select>
                                                      </div>
                                                    </div>
                                                    <input type="text" <?php  if($amount[$purch_id][7]>0){ $amount[$purch_id][7];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" placeholder="Remarks" name="remarks[<?php echo $purch_id;?>][ROOMTO][]" id="remarks_7_<?php echo $purch_id;?>" value="<?php echo $remarks[$purch_id][7]; ?>" style="float: left;"/></td>
                                                  <td><input type="text" <?php  if($amount[$purch_id][7]>0){ $amount[$purch_id][7];}else {echo 'disabled="disabled"';} ?> class="form-control first-input" name="tips[<?php echo $purch_id;?>][ROOMTO][]" id="tips_7_<?php echo $purch_id;?>" value="<?php echo $tips[$purch_id][7]; ?>" style="float: left;"/></td>
                                                </tr>
                          
                                                
                                                
                        
                         <!--Room TO Settle END------------------> 
                                                
                                                
                                                
                                                
                                              </tbody>
                                            </table>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="card text-dark bg-light" style="background-color:#3c8dbc;">
                                      <div class="row">
                                        <div class="form-group col-xs-12 col-md-2 col-sm-2" >
                                          <label for="name" style="margin-left:5px;color:#fff;">Date</label>
                                          <div class="input-group" style="margin-left:5px;">
                                            <div class="input-group-addon"> <i class="fa fa-diamond"></i> </div>
                                            <input   type="text" class="form-control pickerdateretwodays"  placeholder="sreEnter PO Date" id="po_date1" name="po_date1" value="<?php echo $edit_doc_date!=''?date('d-m-Y',strtotime($edit_doc_date)):date('d-m-Y');?>" >
                                          </div>
                                        </div>
                                        <div class="form-group col-xs-12 col-md-2 col-sm-2">
                                          <label for="name" style="color:#fff;">Bill Amount</label>
                                          <div class="input-group">
                                            <div class="input-group-addon"> <i class="fa fa-diamond"></i> </div>
                                            <input type="text" class="form-control" placeholder="Total Amount" id="grand_total_amount" name="grand_total_amount" value="<?php echo $grand_total_amount; ?>" readonly>
                                          </div>
                                        </div>
                                        <div class="form-group col-xs-12 col-md-2 col-sm-2">
                                          <label for="name" style="color:#fff;">Paid Amount</label>
                                          <div class="input-group">
                                            <div class="input-group-addon"> <i class="fa fa-asterisk"></i> </div>
                                            <input type="text" class="form-control" disabled placeholder="Total Pay Amount" id="pay_total_amount_<?php echo $purch_id;?>" name="pay_total_amount_<?php echo $purch_id;?>" value="<?php echo $TotalPaidAmount;?>"  style="text-align:right;" data-parsley-required data-parsley-errors-container="#pay_total_amountError" >
                                          </div>
                                        </div>
                                        <div class="form-group col-xs-12 col-md-2 col-sm-2">
                                          <label for="name" style="color:#fff;">Balance</label>
                                          <div class="input-group">
                                            <div class="input-group-addon"> <i class="fa fa-asterisk"></i> </div>
                                            <input type="text" class="form-control" disabled placeholder="Balance Amount" id="balance_amount_<?php echo $purch_id;?>" name="balance_amount_<?php echo $purch_id;?>" value="<?php echo round($balance_amount,2); ?>"  style="text-align:right;"  >
                                          </div>
                                        </div>
                                        <div class="form-group col-xs-12 col-md-4 col-sm-4">
                                          <div class="input-group" style="margin-top:24px;">
                                              <input type='button' name="saveForm" id="saveForm" value='Save' class="btn btn-success" onClick="ajaxAddBillPayment(<?php echo $purch_id;?>,1);"  >
                                              &nbsp;
                <!--  <input type='button' name="cancelled" id="cancelled" value='Cancel Bill' class="btn btn-danger"  onClick="ajaxcancel(<?php echo $purch_id;?>);" >-->
                                        &nbsp;                                     
                                           <?php  if($row->payment_status=='Settled' || $row->payment_status=='Partial'){
								?>
                       <input type='button' name="saveunsettled" id="saveunsettled" value='Unsettle' class="btn btn-success" onClick="ajaxAddBillPayment(<?php echo $purch_id;?>,0);"   >
                                            
                        <?php }	?>
                                             
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <!-- Total Amount Section --> 
                                    
                                  </div>
                                </div>
                              </div>
                              <div > </div>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                      </td>
                    
                      </tr>
                      