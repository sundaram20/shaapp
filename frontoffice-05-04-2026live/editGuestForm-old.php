<div class="box box-primary">
      <div class="box-header with-border">
          <div class="row">
              <div class="col-xs-6 col-md-3 col-sm-6">
              <h3 class="box-title">Add Guest Details</h3>
          </div>
          <div class="col-xs-6 col-md-3 col-sm-6 text-center">
              <h3  class="box-title" style="font-size: 16px;">Guest Registration Number : 44</h3>
          </div>
          <div class="col-xs-12 col-md-6 col-sm-6">
              <div class="input-group"> 
                  <select class="form-control select2" style="width: 100%;" id="user" name="user">
                    <option selected="selected" value="">Select person</option> 
                    <option value="India"></option>
                    <option value="Alaska">Alaska</option>
                    <option value="California">California</option>
                    <option value="Delaware">Delaware</option>
                    <option value="Tennessee">Tennessee</option>
                    <option value="Texas">Texas</option>
                    <option value="Washington">Washington</option>
                  </select>
                  <div class="input-group-addon">
                      <i class="fa fa-plus"></i> 
                  </div>
              </div>
          </div>
          </div>
      </div>
      <div class="box-body">
          <!-- /.box-header -->
          <!-- form start -->
          <form name="form1"  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off">
          
              <div class="card text-dark bg-light">
                  <div class="bg-primary text-center">
                      <h5 style="padding: 5px;">General Details</h5>
                  </div> 
                  <hr>
                  <div class="row">
                      <div class="form-group col-md-4 col-sm-2">
                        <label for="title">Title<font color="#FF0000">*</font></label>
                          <select class="form-control select2" style="width: 100%;" id="title" name="title" data-parsley-errors-container="#titleError" data-parsley-required>
                                <option selected="selected" value="">Select Title</option> 
                                <option value="Mr.">Mr.</option>    
                                <option value="Mrs.">Mrs.</option>\
                                <option value="Mrs.">Ms.</option>
                            </select>
                        <span id="titleError"><?php echo $err_title;?></span>
                      </div>
                      <div class="form-group col-xs-12 col-md-4 col-sm-2">
                          <label for="fname">Guest Firstname<font color="#FF0000">*</font></label>
                          <div class="input-group"> 
                              <div class="input-group-addon">
                                  <i class="fa fa-user-o"></i> 
                              </div>
                            <input type="text" class="form-control" placeholder="Enter Guest Firstname" id="fname" name="fname" data-parsley-errors-container="#fnameError" data-parsley-required/>
                          </div>
                          <span id="fnameError"><?php echo $err_fnameError;?></span>
                      </div>
                       <div class="form-group col-xs-12 col-md-4 col-sm-2">
                          <label for="lname">Lastname<font color="#FF0000">*</font></label>
                          <div class="input-group"> 
                              <div class="input-group-addon">
                                  <i class="fa fa-user-o"></i> 
                              </div>
                            <input type="text" class="form-control" placeholder="Enter Lastname" id="lname" name="lname" data-parsley-errors-container="#lnameError" data-parsley-required/>
                          </div>
                          <span id="lnameError"><?php echo $err_lnameError;?></span>
                      </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-md-2 col-sm-2">
                        <label for="primaryContact">Primary contact<font color="#FF0000">*</font></label>
                        <select name="primaryContact" id="primaryContact" class="form-control select2" style="width: 100%" data-parsley-errors-container="#primaryContactError" data-parsley-required>
                            <option value="Mobile" selected="selected">Mobile</option>
                            <option value="Landline">Landline</option>
                        </select>
                        <span id="primaryContactError"><?php echo $err_primaryContactError;?></span>
                      </div>
                      <div id="primaryContactDiv">
                        <div class="form-group col-md-2 col-sm-2">
                          <label for="primaryContactMobile">Mobile<font color="#FF0000">*</font>
                          </label>
                          <input type="number" class="form-control" placeholder="Enter Mobile" id="primaryContactMobile" name="primaryContactMobile" data-parsley-errors-container="#primaryContactMobileError" data-parsley-length="[10, 10]" data-parsley-required />
                          <span id="primaryContactMobileError"><?php echo $err_primaryContactMobileError;?></span>
                        </div>
                      </div>
                      <div class="form-group col-md-2 col-sm-2">
                        <label for="secondaryContact">Secondary contact</label>
                        <select name="secondaryContact" id="secondaryContact" class="form-control select2" style="width: 100%">
                            <option value="Landline" selected="selected">Landline</option>
                            <option value="Mobile">Mobile</option>
                        </select>
                      </div>
                      <div id="secondaryContactDiv">
                        <div class="form-group col-md-2 col-sm-2">
                          <label for="secondaryContactLandline">Landline</label>
                          <input type="number" class="form-control" placeholder="Enter Landline" id="secondaryContactLandline" name="secondaryContactLandline"/>
                        </div>
                      </div>
                      <div class="form-group  col-md-4 col-sm-4">
                      <label for="email">Email Id<font color="#FF0000">*</font></label>
                      <div class="input-group"> 
                          <div class="input-group-addon">
                              <i class="fa fa-envelope"></i> 
                          </div>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email Id" data-parsley-errors-container="#emailError" data-parsley-type="email" data-parsley-required />
                      </div>
                      <span id="emailError"><?php echo $err_emailError;?></span>
                    </div>
                  </div>
                  
                  <div class="row">
                      <div class="form-group  col-md-4 col-sm-4">
                        <label for="address">Address<font color="#FF0000">*</font></label>
                        <div class="input-group"> 
                            <div class="input-group-addon">
                                <i class="fa fa-building"></i> 
                            </div>
                            <textarea class="form-control" name="address" id="address" name="address" rows="1" placeholder="Enter Address" data-parsley-errors-container="#addressError" data-parsley-required>
                            </textarea>
                         <!-- <input type="text" class="form-control" placeholder="Enter Address" id="address" name="address" data-parsley-errors-container="#addressError" data-parsley-required/> -->
                        </div>
                        <span id="addressError"><?php echo $err_addressError;?></span>
                      </div>
                      <div class="form-group col-md-4 col-sm-4">
                        <label for="city">City<font color="#FF0000">*</font></label>
                        <div class="input-group"> 
                            <div class="input-group-addon">
                                <i class="fa fa-home"></i> 
                            </div>
                          <input type="text" class="form-control" id="city" name="city" placeholder="Enter City" data-parsley-errors-container="#cityError" data-parsley-required/>
                        </div>
                        <span id="cityError"><?php echo $err_cityError;?></span>
                      </div>
                      <div class="form-group col-md-4 col-sm-2">
                        <label for="pincode">Pincode<font color="#FF0000">*</font></label>
                        <div class="input-group"> 
                            <div class="input-group-addon">
                                <i class="fa fa-map-pin"></i> 
                            </div>
                          <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Enter Pincode"  data-parsley-errors-container="#pincodeError" data-parsley-required />
                        </div>
                         <span id="pincodeError"><?php echo $err_pincodeError;?></span>
                      </div>
                       
                  </div>
                  <div class="row">
                      <div class="form-group  col-md-4 col-sm-4">
                        <label for="id_country">Country<font color="#FF0000">*</font></label>
                        <div class="input-group"> 
                            <div class="input-group-addon">
                                <i class="fa fa-flag"></i> 
                            </div>
                            <select class="form-control select2" style="width: 100%;" id="id_country" name="id_country" data-parsley-errors-container="#id_countryError" data-parsley-required>
                              <option selected="selected" value="">Select Country</option> 
                              <option value="India">India</option>
                              <option value="Alaska">Alaska</option>
                              <option value="California">California</option>
                              <option value="Delaware">Delaware</option>
                              <option value="Tennessee">Tennessee</option>
                              <option value="Texas">Texas</option>
                              <option value="Washington">Washington</option>
                              <option value="10000">Other</option>
                            </select>
                        </div>
                        <span id="id_countryError"><?php echo $err_id_countryError;?></span>
                      </div>
                      <div class="form-group col-md-4 col-sm-2">
                        <label for="id_state">State<font color="#FF0000">*</font></label>
                        <div class="input-group"> 
                            <div class="input-group-addon">
                                <i class="fa fa-adjust"></i> 
                            </div>
                              <select class="form-control select2" style="width: 100%;" id="id_state" name="id_state" data-parsley-errors-container="#id_stateError" data-parsley-required>
                                <option selected="selected" value="">Select State</option> 
                                <option  value="Uttar Pradesh">Uttar Pradesh</option>
                                <option value="Madhya Pradesh">Madhya Pradesh</option>
                                <option value="California">California</option>
                                <option value="Delaware">Delaware</option>
                                <option value="Tennessee">Tennessee</option>
                                <option value="Texas">Texas</option>
                                <option value="Washington">Washington</option>
                                <option value="10000">Other</option>
                            </select>  
                        </div>
                        <span id="id_stateError"><?php echo $err_id_stateError;?></span>
                      </div>
                      
                      <div class="form-group col-md-4 col-sm-2">
                        <label for="nationality">Nationality<font color="#FF0000">*</font></label>
                         <div class="input-group"> 
                            <div class="input-group-addon">
                                <i class="fa fa-flag-o"></i> 
                            </div>
                              <select class="form-control select2" style="width: 100%;" id="nationality" name="nationality" data-parsley-errors-container="#nationalityError" data-parsley-required>
                                <option selected="selected" value="">Select Nationality</option> 
                                <option  value="Indian">Indian</option>
                                <option value="American">American</option>
                            </select>  
                        </div>
                          <span id="nationalityError"><?php echo $err_nationalityError;?></span>
                      </div>  
                  </div>

                  <div class="row">
                    <div id="otherCountryDiv" class="form-group col-sm-4">
                      <?php if($row->id_country == 10000){ ?>
                        <label col="other_country">Other Country</label>
                        <input type="text" name="other_country" id="other_country" class="form-control" placeholder="Enter Country Name" value="<?php if($_POST) echo $_POST['other_country']; else echo $row->other_country;?>" data-parsley-errors-container="#other_countryError" data-parsley-required />
                        <span id="other_countryError"></span>
                      <?php } ?>
                    </div>
                    <div id="otherStateDiv" class="form-group col-sm-4">
                      <?php if($row->id_state == 10000){ ?>
                        <label col="other_state">Other State</label>
                        <input type="text" name="other_state" id="other_state" class="form-control" placeholder="Enter State Name" value="<?php if($_POST) echo $_POST['other_state']; else echo $row->other_state;?>" data-parsley-errors-container="#other_stateError" data-parsley-required />
                        <span id="other_stateError"></span>
                      <?php } ?>
                    </div>
                </div>     
                  
                  <div class="row">
                    <div class="form-group col-md-4 col-sm-2">
                        <label for="dobday">Date of Birth</label>
                       <div class="row">
                          <div class="form-group col-xs-12 col-md-6 col-sm-2">
                            <div class="input-group">
                              <div class="input-group-addon">
                                <i class="fa fa-birthday-cake"></i> 
                              </div> 
                                <select class="form-control select2" style="width: 100%;" id="dobday" name="dobday">
                                  <?php
                                     if(!empty($row->dateofBirthday)){
                                      for($Birthday = 1; $Birthday <= 31; $Birthday++){
                                        if($Birthday==$row->dateofBirthday){
                                          $selected = 'selected="selected"';
                                        }else{

                                          $selected = '';
                                        }
                                        echo "<option value=\"$Birthday\" $selected>$Birthday</option>";
                                      } 
                                     }else{
                                      $selected = 'selected="selected"';
                                      echo "<option value='' $selected>Select Day</option>";
                                        for($Birthday = 1; $Birthday <= 31; $Birthday++){

                                        echo "<option value=\"$Birthday\">$Birthday</option>";
                                        } 
                                     }
                                     
                                ?>
                                </select>  
                              </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-2">
                              <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i> 
                                </div> 
                                <select class="form-control select2" style="width: 100%;" id="dobmonth" name="dobmonth">
                                <?php 
                                  if(!empty($row->dateofBirthMonth)){
                                     for($i = 1; $i <= 12; $i++){
                                        if($i==$row->dateofBirthMonth){
                                          $selected = 'selected="selected"';
                                        }else {
                                          $selected = '';
                                        }
                        
                                        $dt = DateTime::createFromFormat('!m', $i);
                                        echo "<option value=\"$i\" $selected >".$dt->format('F')."</option>";
                                      }
                                }else{
                                    $selected = 'selected="selected"';
                                    echo "<option value='' $selected>Select Month</option>";
                                    for($i = 1; $i <= 12; $i++){
                                        $dt = DateTime::createFromFormat('!m', $i);
                                        echo "<option value=\"$i\">".$dt->format('F')."</option>";
                                    }
                                }
                              ?>
                            </select>  
                          </div>
                          </div>
                       </div> 
                      </div>   
                     
                      <div class="form-group col-md-4 col-sm-2">
                          <label for="doaday">Anniversary</label>
                          <div class="row">
                           <div class="form-group col-xs-12 col-md-6 col-sm-2">
                                <div class="input-group">
                                  <div class="input-group-addon">
                                    <i class="fa fa-gift"></i> 
                                  </div> 
                                  <select class="form-control select2" style="width: 100%;" id="doaday" name="doaday" data-parsley-errors-container="#doadayError" data-parsley-required>
                                  <?php 
                                    if(!empty($row->dateofanniversaryday)){
                                        for($Birthday = 1; $Birthday <= 31; $Birthday++){
                                          if($Birthday==$row->dateofanniversaryday){
                                              $selected = 'selected="selected"';
                                          }else{
                                            $selected = '';
                                          }
                                          echo "<option value=\"$Birthday\" $selected>$Birthday</option>";
                                        } 
                                      }else{
                                        $selected = 'selected="selected"';
                                        echo "<option value='' $selected>Select Day</option>";
                                        for($Birthday = 1; $Birthday <= 31; $Birthday++){

                                           echo "<option value=\"$Birthday\">$Birthday</option>";
                                        } 
                                       }
                                    ?>
                                  </select>  
                                </div>
                                <span id="doadayError"><?php echo $err_doadayError;?></span>
                              </div>
                              <div class="form-group col-xs-12 col-md-6 col-sm-2">
                                <div class="input-group">
                                  <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i> 
                                  </div> 
                                  <select class="form-control select2" style="width: 100%;" id="doamonth" name="doamonth" data-parsley-errors-container="#doamonthError" data-parsley-required>
                                    <?php 
                                      if(!empty($row->dateofanniversaryMonth)){
                                        for($i = 1; $i <= 12; $i++){
                                          if($i==$row->dateofanniversaryMonth){
                                            $selected = 'selected="selected"';
                                          }else {
                                            $selected = '';
                                          }
                              
                                          $dt = DateTime::createFromFormat('!m', $i);
                                            echo "<option value=\"$i\" $selected >".$dt->format('F')."</option>";
                                        }
                                      }else{
                                        $selected = 'selected="selected"';
                                        echo "<option value='' $selected>Select Month</option>";
                                        for($i = 1; $i <= 12; $i++){
                                
                                          $dt = DateTime::createFromFormat('!m', $i);
                                                echo "<option value=\"$i\">".$dt->format('F')."</option>";
                                        }
                                      }
                                    ?>
                                </select>  
                              </div>
                              <span id="doamonthError"><?php echo $err_doamonthError;?></span>
                              </div>
                         </div>
                      </div> 
                      <div class="form-group  col-md-4 col-sm-2">
                        <label for="gender">Gender<font color="#FF0000">*</font></label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-intersex"></i> 
                            </div>
                            <select class="form-control select2" style="width: 100%;" id="gender" name="gender" data-parsley-errors-container="#genderError" data-parsley-required>
                              <option selected="selected" value="">Select Gender</option>    
                              <option  value="male">Male</option>
                              <option value="female">Female</option>
                            </select>
                        </div>
                        <span id="genderError"><?php echo $err_genderError;?></span>
                      </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-xs-12 col-md-4 col-sm-2">
                      <label for="guestType">Guest VIP Status<font color="#FF0000">*</font></label>
                      <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-user"></i> 
                        </div>
                        <select class="form-control select2" style="width: 100%;"  id="guestType" name="guestType" data-parsley-errors-container="#guestTypeError" data-parsley-required>
                          <option selected="selected" value="">Select Guest Status</option>    
                          <option value="VIP">VIP</option>
                          <option value="VIP">CIP</option>
                        </select>
                      </div>
                      <span id="guestTypeError"><?php echo $err_guestTypeError;?></span>
                    </div>     
                    <div class="form-group col-xs-12 col-md-4 col-sm-2">
                      <label for="memebership">Membership Status <font color="#FF0000">*</font></label>
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-group"></i> 
                        </div>
                        <select class="form-control select2" style="width: 100%;"  id="memebership" name="memebership" data-parsley-errors-container="#memebershipError" data-parsley-required>
                          <option selected="selected" value="">Select Memebership</option>    
                          <option value="Active">Active</option>
                          <option value="Deactive">Inactive</option>
                        </select>
                      </div>
                      <span id="memebershipError"><?php echo $err_memebershipError;?></span>
                    </div> 
                    <div class="form-group  col-md-4 col-sm-2">
                      <label for="guestnote">Guest Note <font color="#FF0000">*</font></label>   
                      <div class="input-group">
                          <div class="input-group-addon">
                              <i class="fa fa-comment"></i> 
                          </div>
                          <textarea class="form-control" name="guestnote" id="guestnote" rows="1" data-parsley-errors-container="#guestnoteError" data-parsley-required></textarea>
                      </div>
                      <span id="guestnoteError"><?php echo $err_guestnoteError;?></span>
                    </div>    
                </div>
              </div>  
              <hr>
               
              <div class="card text-dark bg-light">
                <div class="bg-primary text-center ">
                  <h5 style="padding: 5px;">ID Proof Details</h5>
                </div> 
                <hr>
                <div class="row">
                  <div class="form-group col-md-3 col-sm-2">
                    <label for="idProof">Id Proof Details<font color="#FF0000">*</font></label>
                    <div class="input-group"> 
                      <div class="input-group-addon">
                        <i class="fa fa-address-card"></i> 
                      </div>
                      <select class="form-control select2" style="width: 100%;" id="idProof" name="idProof" data-parsley-errors-container="#idProofError" data-parsley-required>
                          <option selected="selected" value="">Select Id Proof</option> 
                          <option value="Voter_Id">Voter Id</option>    
                          <option value="Passport">Passport</option>
                          <option value="Aadhar">Aadhar</option>
                      </select>
                    </div>
                    <span id="idProofError"><?php echo $err_idProofError;?></span>
                  </div>
                  <span id="appenddata"></span>  
                </div>
              </div>
              <hr>
              <div class="card text-dark bg-light">
                  <div class="bg-primary text-center ">
                      <h5 style="padding: 5px;">Guest Photo</h5>
                  </div> 
                  <hr>
                  <div class="row">
                      <div class="col-sm-3">
                          <div class="form-group">
                              <label for="image">Guest Photo &nbsp;&nbsp;</label>
                              <div class="btn btn-default btn-file">
                                <i class="fa fa-upload"></i> Upload
                               <input type="file" class="form-control" placeholder="Item Image" id="item_image" name="item_image" value="">   
                               <input type="hidden" name="old_image" value="<?php echo stripslashes($row->item_image);?>"/>                    
                          
                              </div>
                              <p class="help-block">Must be of width:600px and height:300px.<br />Max. Size: 1MB</p>      
                          </div>
                          <?php echo $err_image;?>
                      </div>
                      <div class="col-sm-9">                                                  
                          <ul class="mailbox-attachments clearfix"> 
                              <li id="imageCallback">
                              <?php if(@file_exists($image_path.$row->item_image) && $row->item_image!=''){ ?>
                              <span class="mailbox-attachment-icon has-img">                           
                                  <img src="<?php echo $image_display_path.$row->item_image; ?>" alt="Item Image">                              
                                </span>           
                                <div class="mailbox-attachment-info">
                                  <a href="javascript:void(0);" class="mailbox-attachment-name"><i class="fa fa-camera"></i> <?php echo $row->item_image; ?></a>
                                      <span class="mailbox-attachment-size">
                                        <?php echo round(filesize($image_path.$row->item_image)/ 1024 ,2).' KB'; ?>
                                        <a href="<?php echo $image_display_path.$row->item_image; ?>" download class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                                      </span>
                                </div>
                              <?php }else{ ?>                         
                              <span class="mailbox-attachment-icon has-img">                           
                                  <img src="../images/no-hotel-image.jpg" alt="Item Image">                             
                                </span>           
                                <div class="mailbox-attachment-info">
                                  <a href="javascript:void(0);" class="mailbox-attachment-name"><i class="fa fa-camera"></i> no-hotel-image.jpg</a>
                                      <span class="mailbox-attachment-size">
                                         <?php echo round(filesize('../images/no-hotel-image.jpg')/ 1024 ,2).' KB'; ?>
                                        <a href="../images/no-hotel-image.jpg" download class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                                      </span>
                                </div>                            
                              <?php }?> 
                                
                              </li>                
                          </ul>           
                      </div>
                  </div>
              </div>
              <hr>
              <div class="card text-dark bg-light">
                  <div class="bg-primary text-center ">
                      <h5 style="padding: 5px;">Previous History</h5>
                  </div> 
                  <hr>
                  <div class="row">
                      <div class="col-xs-12 col-md-12 col-sm-12">
                          <div>
                              <div class="box-body">
                                  <table  class="table table-bordered table-hover table-striped table-responsive">
                                      <thead>
                                          <tr class="info">
                                            <th>S.No</th>
                                            <th>Check In</th>
                                            <th>Days Stayed</th>
                                            <th>Room</th> 
                                            <th>Guest Note</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <tr>
                                              <td>1</td>
                                              <td>Example</td>
                                              <td>Example</td>
                                              <td>Example</td>
                                              <td>Example</td>
                                          </tr>
                                      </tbody>
                                  </table>
                              </div>
                          </div>
                      </div>
                  </div>
                  
              </div> 
          </div>
        <!-- /.box-body -->

          <div class="box-footer">
              <button type="submit" class="btn btn-success">Add</button>
              &nbsp;&nbsp; 
              <button type="button" class="btn btn-danger">Cancel </button>
          </div>
      </form>

  </div>