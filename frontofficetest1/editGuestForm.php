<div class="box box-primary">
      <div class="box-header with-border">
          <div class="row">
              <div class="col-xs-6 col-md-3 col-sm-6">
              <h3 class="box-title"><?php echo $_REQUEST['eId']=='' ? 'Add':'Edit' ?> Guest Details</h3>
          </div>
          <div class="col-xs-6 col-md-3 col-sm-6 text-center">
              <h3  class="box-title" style="font-size: 16px;"><?php echo $_REQUEST['eId']=='' ? '':'Guest Registration Number : 44' ?></h3>
          </div>
          <div class="col-xs-12 col-md-6 col-sm-6">
            <!--
              <div class="input-group"> 
                  <div class="input-group-addon">
                    <i class="fa fa-user"></i>
                  </div>
                  <select class="form-control select2" style="width: 100%;" id="user" name="user">
                    <option selected="selected" value="">Select person</option> 
                  </select>
                  <div class="input-group-addon">
                      <i class="fa fa-plus"></i> 
                  </div>
              </div>
            -->
          </div>
        </div>
      </div>
      <div class="box-body">
          <!-- /.box-header -->
          <!-- form start -->
          <form name="guestForm"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off" id="guestForm">

            <input type="hidden" value="<?php echo $_REQUEST['id'];?>" name="id" />

              <div class="form-group has-error" align="center">
                <?php if($_SESSION['errorMsg']){?>
                <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
                <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
                <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
                <?php unset($_SESSION['successMsg']);}?>
              </div>
          
              <div class="card text-dark bg-light">
                  <div class="bg-primary text-center">
                      <h5 style="padding: 5px;">General Details</h5>
                  </div> 
                  <hr>
                  <div class="row">
                      <div class="form-group col-md-4 col-sm-2">
                        <label for="title">Title<font color="#FF0000">*</font></label>
                          <select class="form-control select2" style="width: 100%;" id="title" name="title" data-parsley-errors-container="#titleError" data-parsley-required>
                                <?php if($row->title == "Mr."){?>
                                  <option value="Mr." selected="selected">Mr.</option>    
                                  <option value="Mrs.">Mrs.</option>
                                  <option value="Mrs.">Ms.</option>
                                <?php }else if($row->title == "Mrs."){ ?>
                                  <option value="Mr.">Mr.</option>    
                                  <option value="Mrs."  selected="selected">Mrs.</option>
                                  <option value="Mrs.">Ms.</option>
                                <?php }else{ ?>
                                  <option selected="selected" value="">Select Title</option> 
                                  <option value="Mr.">Mr.</option>    
                                  <option value="Mrs.">Mrs.</option>
                                  <option value="Mrs.">Ms.</option>
                                <?php } ?>
                            </select>
                        <span id="titleError"><?php echo $err_title;?></span>
                      </div>
                      <div class="form-group col-xs-12 col-md-4 col-sm-2">
                          <label for="first_name">Guest Firstname<font color="#FF0000">*</font></label>
                          <div class="input-group"> 
                              <div class="input-group-addon">
                                  <i class="fa fa-user-o"></i> 
                              </div>
                            <input type="text" class="form-control" placeholder="Enter Guest Firstname" id="first_name" name="first_name" value="<?php if($_POST) echo $_POST['first_name'];else echo stripslashes($row->first_name);?>" data-parsley-errors-container="#first_nameError" data-parsley-required/>
                          </div>
                          <span id="first_nameError"><?php echo $err_first_nameError;?></span>
                      </div>
                       <div class="form-group col-xs-12 col-md-4 col-sm-2">
                          <label for="last_name">Lastname<font color="#FF0000">*</font></label>
                          <div class="input-group"> 
                              <div class="input-group-addon">
                                  <i class="fa fa-user-o"></i> 
                              </div>
                            <input type="text" class="form-control" placeholder="Enter Lastname" id="last_name" name="last_name" value="<?php if($_POST) echo $_POST['last_name'];else echo stripslashes($row->last_name);?>" data-parsley-errors-container="#last_nameError" data-parsley-required/>
                          </div>
                          <span id="last_nameError"><?php echo $err_last_nameError;?></span>
                      </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-sm-2">
                      <label for="primary_contact">Primary contact<font color="#FF0000">*</font></label>
                      <select name="primary_contact" id="primary_contact" class="form-control select2" style="width: 100%" data-parsley-errors-container="#primary_contactError" data-parsley-required>
                        <?php if($row->primary_contact == 1){?>
                              <option value="1" selected="selected">Mobile</option>
                              <option value="2">Landline</option>
                          <?php }else if($row->primary_contact == 2){ ?>
                            <option value="2" selected="selected">Landline</option>
                            <option value="1">Mobile</option>
                          <?php }else{ ?>
                          <option value="1" selected="selected">Mobile</option>
                          <option value="2">Landline</option>
                        <?php } ?>  
                      </select>
                      <span id="primary_contactError"><?php echo $err_priContactMobile;?></span>
                    </div>
                    <div id="primaryContactDiv">
                      <?php if($row->primary_contact == 1){ ?>
                      <div class="form-group col-md-2 col-sm-2">
                        <label for="primary_mobile">Mobile<font color="#FF0000">*</font>
                        </label>
                        <input type="text" class="form-control" placeholder="Enter Mobile" id="primary_mobile" name="primary_mobile" value="<?php if($_POST) echo $_POST['primary_mobile']; else echo $row->primary_mobile;?>" data-parsley-errors-container="#primary_mobileError" data-parsley-type="digits" data-parsley-length="[10, 10]" data-parsley-required />
                        <span id="primary_mobileError"><?php echo $err_primary_mobile;?></span>
                      </div>
                      <?php }else if($row->primary_contact == 2){?>
                      <div class="form-group col-md-2 col-sm-2">
                        <label for="primary_landline">Landline<font color="#FF0000">*</font>
                        </label>
                        <input type="text" class="form-control" placeholder="Enter Mobile" id="primary_landline" name="primary_landline" value="<?php if($_POST) echo $_POST['primary_landline']; else echo $row->primary_landline;?>" data-parsley-errors-container="#primary_landlineError" data-parsley-type="digits"  data-parsley-required />
                        <span id="primary_landlineError"><?php echo $err_primary_landlineError;?></span>
                      </div>
                      <?php }else{ ?>
                      <div class="form-group col-md-2 col-sm-2">
                        <label for="primary_mobile">Mobile<font color="#FF0000">*</font>
                        </label>
                        <input type="text" class="form-control" placeholder="Enter Mobile" id="primary_mobile" name="primary_mobile" value="<?php if($_POST) echo $_POST['primary_mobile']; else echo $row->primary_mobile;?>" data-parsley-errors-container="#primary_mobileError" data-parsley-type="digits" data-parsley-length="[10, 10]" data-parsley-required />
                        <span id="primary_mobileError"><?php echo $err_primary_mobileError;?></span>
                      </div>
                    <?php } ?>
                    </div>
                    <div class="form-group col-md-2 col-sm-2">
                      <label for="secondary_contact">Secondary contact</label>
                      <select name="secondary_contact" id="secondary_contact" class="form-control select2" style="width: 100%">
                        <?php if($row->secondary_contact == 1){?>
                          <option value="1" selected="selected">Landline</option>
                          <option value="2">Mobile</option>
                        <?php }else if($row->secondary_contact == 2){?>
                          <option value="2" selected="selected">Mobile</option>
                          <option value="1">Landline</option>
                        <?php }else{ ?>
                          <option value="1" selected="selected">Landline</option>
                          <option value="2">Mobile</option>
                       <?php } ?>
                      </select>
                    </div>
                    <div id="secondaryContactDiv">
                      <?php if($row->secondary_contact == 1){ ?>
                        <div class="form-group col-md-2 col-sm-2">
                          <label for="secondary_landline">Landline</label>
                          <input type="text" class="form-control" placeholder="Enter Landline" id="secondary_landline" name="secondary_landline" value="<?php if($_POST) echo $_POST['secondary_landline']; else echo $row->secondary_landline;?>"data-parsley-type="digits" />
                        </div>
                      <?php }else if($row->secondary_contact == 2){ ?>
                        <div class="form-group col-md-2 col-sm-2">
                          <label for="secondary_mobile">Mobile</label>
                          <input type="text" class="form-control" placeholder="Enter Landline" id="secondary_mobile" name="secondary_mobile" value="<?php if($_POST) echo $_POST['secondary_mobile']; else echo $row->secondary_mobile;?>"data-parsley-type="digits" />
                        </div>
                      <?php }else{ ?>
                        <div class="form-group col-md-2 col-sm-2">
                          <label for="secondary_landline">Landline</label>
                          <input type="text" class="form-control" placeholder="Enter Landline" id="secondary_landline" name="secondary_landline" value="<?php if($_POST) echo $_POST['secondary_landline']; else echo $row->secondary_landline;?>"data-parsley-type="digits" />
                        </div>
                      <?php } ?>
                    </div>
                    <div class="form-group  col-md-4 col-sm-4">
                     <label for="email">Email Id<font color="#FF0000">*</font></label>
                        <div class="input-group"> 
                          <div class="input-group-addon">
                              <i class="fa fa-envelope"></i> 
                          </div>
                          <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email Id" value="<?php if($_POST) echo $_POST['email'];else echo stripslashes($row->email);?>"data-parsley-errors-container="#emailError" data-parsley-type="email" data-parsley-required />
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
                            <textarea class="form-control" name="address" id="address" name="address" rows="1" placeholder="Enter Address" data-parsley-errors-container="#addressError" data-parsley-required><?php if($_POST) echo $_POST['address'];else echo stripslashes($row->address);?>
                            </textarea>
                        </div>
                        <span id="addressError"><?php echo $err_addressError;?></span>
                      </div>
                      <div class="form-group col-md-4 col-sm-4">
                        <label for="city">City<font color="#FF0000">*</font></label>
                        <div class="input-group"> 
                            <div class="input-group-addon">
                                <i class="fa fa-home"></i> 
                            </div>
                          <input type="text" class="form-control" id="city" name="city" placeholder="Enter City" value="<?php if($_POST) echo $_POST['city'];else echo stripslashes($row->city);?>"data-parsley-errors-container="#cityError" data-parsley-required/>
                        </div>
                        <span id="cityError"><?php echo $err_cityError;?></span>
                      </div>
                      <div class="form-group col-md-4 col-sm-2">
                        <label for="postcode">Pincode<font color="#FF0000">*</font></label>
                        <div class="input-group"> 
                            <div class="input-group-addon">
                                <i class="fa fa-map-pin"></i> 
                            </div>
                          <input type="text" class="form-control" id="postcode" name="postcode" placeholder="Enter Pincode" value="<?php if($_POST) echo $_POST['postcode'];else echo stripslashes($row->postcode);?>"data-parsley-errors-container="#postcodeError" data-parsley-required />
                        </div>
                         <span id="postcodeError"><?php echo $err_postcodeError;?></span>
                      </div>
                       
                  </div>
                  <div class="row">
                      <div class="form-group  col-md-4 col-sm-4">
                        <label for="id_country">Country<font color="#FF0000">*</font></label>
                        <div class="input-group"> 
                            <div class="input-group-addon">
                                <i class="fa fa-flag"></i> 
                            </div>
                            <select class="form-control select2" name="id_country" id="id_country" style="width:100%" data-parsley-errors-container="#countryError" data-parsley-required onchange="getState(this.value,'','');" >
                              <option value="">Select Country</option>
                              <?php 
                                $resCat = selectSql(TBL_COUNTRY_LANG,"where id_lang='1' ",' ORDER BY `name`');

                                if(num_rows($resCat)){

                                  while($resultCat = $db->fetch_object2($resCat)){  
                                    if($_REQUEST['id_country'] == $resultCat->id_country){

                                      $selected = 'selected="selected"';

                                    }elseif($row->id_country == $resultCat->id_country){

                                      $selected = 'selected="selected"';

                                    }else{

                                      $selected = '';

                                    }

                                    $countryDropDown .= '<option '.$selected.' value="'.$resultCat->id_country.'">'.ucfirst($resultCat->name).'</option>';
                                  }
                                }
                                 echo $countryDropDown;
                              ?>
                            <?php if($row->id_country == 10000){?>
                            <option value="10000" selected="selected">Other</option>
                             <?php } else{ ?>
                                <option value="10000">Other</option>
                             <?php } ?>
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
                              <div id="state"> 
                               <select class="form-control select2"  name="id_state" id="id_state"  style="width:100%" data-parsley-errors-container="#id_stateError" data-parsley-required>
                                <option value="" selected="">Please Select State</option>
                                <?php if($row->id_state == 10000){?>
                                  <option value="10000" selected="selected">Other</option>
                                  <?php } else{ ?>
                                  <option value="10000">Other</option>
                                <?php } ?>   
                              </select>
                            </div>
                        </div>
                        <span id="id_stateError"><?php echo $err_id_stateError;?></span>
                      </div>
                      
                      <div class="form-group col-md-4 col-sm-2">
                        <label for="id_nationality">Nationality<font color="#FF0000">*</font></label>
                         <div class="input-group"> 
                            <div class="input-group-addon">
                                <i class="fa fa-flag-o"></i> 
                            </div>
                              <select class="form-control select2" style="width: 100%;" id="id_nationality" name="id_nationality" data-parsley-errors-container="#nationalityError" data-parsley-required>
                                <option selected="selected" value="">Select Nationality</option> 
                                <?php if($row->id_nationality == 10000){?>
                                  <option value="10000" selected="selected">Other</option>
                                  <?php } else{ ?>
                                  <option value="10000">Other</option>
                                <?php } ?>   
                            </select>  
                        </div>
                          <span id="nationalityError"><?php echo $err_id_nationalityError;?></span>
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
                    <div id="otherNationalityDiv" class="form-group col-sm-4">
                      <?php if($row->id_nationality == 10000){ ?>
                        <label col="other_state">Other Nationality</label>
                        <input type="text" name="other_nationality" id="other_nationality" class="form-control" placeholder="Enter State Name" value="<?php if($_POST) echo $_POST['other_nationality']; else echo $row->other_nationality;?>" data-parsley-errors-container="#other_nationalityError" data-parsley-required />
                        <span id="other_nationalityError"></span>
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
                                  <select class="form-control select2" style="width: 100%;" id="doaday" name="doaday">
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
                              </div>
                              <div class="form-group col-xs-12 col-md-6 col-sm-2">
                                <div class="input-group">
                                  <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i> 
                                  </div> 
                                  <select class="form-control select2" style="width: 100%;" id="doamonth" name="doamonth">
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
                              <?php if($row->gender == 1){ ?> 
                                <option  value="1" selected="selected">Male</option>
                                <option value="2">Female</option>
                              <?php }else if($row->gender == 2){ ?>
                                <option  value="1">Male</option>
                                <option value="2" selected="selected">Female</option>
                              <?php }else{ ?>   
                                <option selected="selected" value="">Select Gender</option>
                                <option  value="1">Male</option>
                                <option value="2">Female</option>
                              <?php } ?>
                            </select>
                        </div>
                        <span id="genderError"><?php echo $err_genderError;?></span>
                      </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-xs-12 col-md-4 col-sm-2">
                      <label for="id_guestvip_status">Guest VIP Status<font color="#FF0000">*</font></label>
                      <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-user"></i> 
                        </div>
                        <select class="form-control select2" style="width: 100%;"  id="id_guestvip_status" name="id_guestvip_status" data-parsley-errors-container="#id_guestvip_statusError" data-parsley-required>
                          <?php if($row->id_guestvip_status == 1){ ?>
                            <option value="1" selected="selected">VIP</option>
                            <option value="2">CIP</option>
                          <?php }else if($row->id_guestvip_status == 2){ ?>
                            <option value="1">VIP</option>
                            <option value="2" selected="selected">CIP</option>
                          <?php }else{ ?>
                            <option selected="selected" value="">Select Guest Status</option>
                            <option value="1">VIP</option>
                            <option value="2">CIP</option>
                          <?php } ?>
                        </select>
                      </div>
                      <span id="id_guestvip_statusError"><?php echo $err_id_guestvip_statusError;?></span>
                    </div>     
                    <div class="form-group col-xs-12 col-md-4 col-sm-2">
                      <label for="status">Membership Status <font color="#FF0000">*</font></label>
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-group"></i> 
                        </div>
                        <select class="form-control select2" style="width: 100%;"  id="status" name="status" data-parsley-errors-container="#statusError" data-parsley-required>
                          <?php if($row->status == 1){ ?>
                            <option value="1" selected="selected">Active</option>
                            <option value="0">Inactive</option>
                          <?php }else if($row->status == 0){ ?>
                            <option value="0" selected="selected">Inactive</option>
                            <option value="1">Active</option>
                          <?php }else{ ?>
                            <option selected="selected" value="">Select Memebership</option>
                            <option value="1">Active</option>
                            <option value="0" selected="selected">Inactive</option>
                          <?php } ?>   
                          
                        </select>
                      </div>
                      <span id="statusError"><?php echo $err_statusError;?></span>
                    </div> 
                    <div class="form-group  col-md-4 col-sm-2">
                      <label for="guest_note">Guest Note <font color="#FF0000">*</font></label>   
                      <div class="input-group">
                          <div class="input-group-addon">
                              <i class="fa fa-comment"></i> 
                          </div>
                          <textarea class="form-control" name="guest_note" id="guest_note" rows="1"><?php if($_POST) echo $_POST['guest_note'];else echo stripslashes($row->guest_note);?></textarea>
                      </div>
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
                    <label for="id_proof">Id Proof Details<font color="#FF0000">*</font></label>
                    <div class="input-group"> 
                      <div class="input-group-addon">
                        <i class="fa fa-address-card"></i> 
                      </div>
                      <select class="form-control select2" style="width: 100%;" id="id_proof" name="id_proof" data-parsley-errors-container="#id_proofError" data-parsley-required>
                          <?php if($row->id_proof == 1){ ?>
                            <option value="1" selected="selected">Voter Id</option>    
                            <option value="2">Adhar</option>
                            <option value="3">Passport</option>
                          <?php }else if($row->id_proof == 2){?>
                            <option value="2" selected="selected">Adhar</option>
                            <option value="1">Voter Id</option>
                            <option value="3">Passport</option>
                          <?php }else if($row->id_proof == 3){?>
                            <option value="1">Voter Id</option>
                            <option value="2">Adhar</option>
                            <option value="3" selected="selected">Passport</option>
                          <?php }else{ ?>
                            <option selected="selected" value="">Select Id Proof</option> 
                            <option value="1">Voter Id</option>    
                            <option value="2">Adhar</option>
                            <option value="3">Passport</option>
                          <?php } ?>
                          
                      </select>
                    </div>
                    <span id="id_proofError"><?php echo $err_id_proofError;?></span>
                  </div>
                  <div id="appenddata">
                    <?php if($row->id_proof == 1){ ?>
                      <div class="form-group col-xs-12 col-md-3 col-sm-2">
                        <label for="voter_no">Voter Id Number <font color="#FF0000">*</font></label>
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa fa-address-book"></i>
                          </div>
                          <input type="text" class="form-control" id="voter_no" name="voter_no" placeholder="Enter Voter Id Number" value="<?php if($_POST) echo $_POST['voter_no']; else echo $row->voter_no;?>"data-parsley-errors-container="#voter_noError" data-parsley-required />
                        </div>
                        <span id="voter_noError"><?php echo $err_voter_noError;?></span>
                      </div>
                      <?php }else if($row->id_proof == 2){ ?>
                        <div class="form-group col-xs-12 col-md-3 col-sm-2">
                          <label for="adhar_no">Adhar Number <font color="#FF0000">*</font></label>
                          <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa fa-address-book"></i>
                            </div>
                            <input type="text" class="form-control" id="adhar_no" name="adhar_no" placeholder="Enter Aadhar Number" value="<?php if($_POST) echo $_POST['adhar_no']; else echo $row->adhar_no;?>"data-parsley-errors-container="#adhar_noError" data-parsley-required />
                          </div>
                          <span id="adhar_noError"><?php echo $err_adhar_noError;?></span>
                        </div>
                      <?php }else if($row->id_proof == 3){ ?>
                      <div class="form-group col-xs-12 col-md-3 col-sm-2">
                        <label for="passport_no">Passport Number <font color="#FF0000">*</font></label>
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa fa-address-book"></i>
                          </div>
                          <input type="text" class="form-control" id="passport_no" name="passport_no" placeholder="Enter Passport Number" value="<?php if($_POST) echo $_POST['passport_no']; else echo $row->passport_no;?>" data-parsley-errors-container="#passport_noError" data-parsley-required />
                        </div> 
                        <span id="passport_noError"><?php echo $err_passport_noError;?></span>
                      </div>
                      <div class="form-group col-xs-12 col-md-3 col-sm-2">
                        <label for="authority">Authority<font color="#FF0000">*</font></label>
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa-arrows"></i>
                          </div>
                          <input type="text" class="form-control" id="authority" name="authority" placeholder="Enter Authority" value="<?php if($_POST) echo $_POST['authority']; else echo $row->authority;?>" data-parsley-errors-container="#authorityError" data-parsley-required /></div>
                          <span id="authorityError"><?php echo $err_authorityError;?></span></div>
                          <div class="form-group col-xs-12 col-md-3 col-sm-2">
                            <label for="expiry_date">Expiry Date<font color="#FF0000">*</font></label>
                            <div class="input-group">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar-minus-o"></i>
                              </div>
                              <input type="text" class="form-control datepicker" id="expiry_date" name="expiry_date" placeholder="dd-mm-yyyy" value="<?php if($_POST) echo $_POST['expiry_date']; else echo $row->expiry_date;?>" data-parsley-errors-container="#expiry_dateError" data-parsley-required />
                            </div>
                            <span id="expiry_dateError"><?php echo $err_expiryDateError;?></span>
                          </div>
                      <?php } ?>
                  </div>  
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
              <button type="submit" class="btn btn-success" id="guestFormBtn">Add</button>
              &nbsp;&nbsp; 
              <button type="button" class="btn btn-danger">Cancel </button>
          </div>
      </form>

  </div>


<script type="text/javascript">
  $(document).ready(function(){
    $("#guestFormBtn").on('click', function(){
      var formData = $("#guestForm").serialize();
      $.ajax({
        url : 'application/frontoffice/ajax/ajaxEditGuestForm.php',
        type : 'POST',
        data : formData,
        success : function(response){
          alert(response);
        }
      });
    })
  });
</script>
