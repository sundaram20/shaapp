<?php include_once("../config/auto_loader.php");

if($_REQUEST['gId']=='')
  checkUserLevelPermission($_SESSION['userLevel'],TBL_GUEST,'add');
else
  checkUserLevelPermission($_SESSION['userLevel'],TBL_GUEST,'edit');

$image_path = $UPLOAD_FILES.'/guestImages/';

$image_display_path = $UPLOAD_FILES_PATH ."/guestImages/";

if(!empty($_REQUEST['gId']) && $_REQUEST['action']=='edit'){

  $sql = "  SELECT * FROM `".TBL_GUEST."`

                WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['gId']))."'";

  $db->query($sql);

  if($db->num_rows() > 0){

    $row = $db->fetch_object();

  }           

} 

?>
<div class="box box-primary">
      <div class="box-header with-border">
          <div class="row">
            <div class="col-xs-6 col-md-3 col-sm-6">
              <h3 class="box-title"><?php echo $_REQUEST['gId']=='' ? 'Add':'Edit' ?> Guest Details <?php if(!empty($_REQUEST['gId'])){ echo " - " . $row->first_name; } ?></h3>
            </div>
            <div class="col-xs-6 col-md-3 col-sm-6 text-center">
                <h3  class="box-title" style="font-size: 16px;"><?php echo $_REQUEST['gId']=='' ? '' : "Guest RegNo : " .$row->guest_reg_no ?></h3>
            </div>
            <div class="col-xs-12 col-md-5 col-sm-6">
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
		  
		  
		  <!-- Audit Trail Modal -->
<div class="modal fade" id="auditModal" tabindex="-1" role="dialog" aria-labelledby="auditModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
               <!-- <h4 class="modal-title" id="roomtypeModalLabel">Rooms Select</h4>  -->
                <label class="modal-title" id="roomtitle1" style="font-size:22px;">Audit Trail</label>
            </div>
            <div class="modal-body" style="overflow-y: scroll; max-height:100%;height:250px ">
                <table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Details</th>   
					</tr>
				</thead>
				
				<tbody id="roombutton">
					
				</tbody>
			</table>
            </div>
			
            <div class="modal-footer"  style="background-color: #e4e4e4;color: #fff;text-align:center">
               <button type="button" class="btn btn-danger" data-dismiss="modal"> <span class="glyphicon glyphicon-off"></span> Close</button> 
            </div>
     </form>
        </div>
    </div>
</div>
<!-- End Audit trail Modal -->
		  
		  
		  
		  
          <!-- form start -->
          <form name="guestForm"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off" id="guestForm">

            <input type="hidden" value="<?php echo $_REQUEST['gId'];?>" name="gId" />

            <input type="hidden" value="<?=($_REQUEST['gId']==''?'Add':'Edit')?>" name="Save" />

             <div class="form-group col-xs-12 col-md-2 col-sm-2" style="display:none;">
                <label for="name">Id Doc Type</label>
                <input type="text" class="form-control" placeholder="Enter Id Doc Type" id="id_mst_doc_type_configuration" name="id_mst_doc_type_configuration" value="<?php if($_POST['id_mst_doc_type_configuration']) echo $_POST['id_mst_doc_type_configuration'];else echo stripslashes($row->id_mst_doc_type_configuration); ?>"> 
              </div>  

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
                  <div class="form-group col-xs-12 col-md-4 col-sm-4" style="display:none">
                    <label for="name">Document Type</label>
                    <div class="input-group"> 
                      <div class="input-group-addon">
                        <i class="fa fa-book"></i> 
                      </div>
                        <select class="form-control select2" id="doc_type" name="doc_type" onchange="hideandshow()" style="width: 100%">  
                          <option  value="501" selected="selected">Guest Registration Number</option>  
                        </select> 
                      <?php 
                        $sql2 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$row->id_mst_doc_type_configuration."' ";
                          $db->query($sql2);   
                          while($row2 = $db->fetch_object()){ 
                            $prefix= $row2->prefix; 
                            $suffix = $row2->suffix; 
                          } 
                      ?>
                    </div>
                  </div> 
                  <?php if($row->doc_type == 501){ ?>
                    <div class="form-group col-xs-12 col-md-6 col-sm-6" >
                      <label for="name">Guest Registration Date</label>
                      <div class="input-group"> 
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i> 
                        </div>
                        <input type="text" class="form-control dates" placeholder="Enter Guest Registration Date" id="guest_reg_date" name="guest_reg_date" onchange="hideandshow()"  value="<?php if($_POST['guest_reg_date']) echo $_POST['guest_reg_date'];elseif($row->guest_reg_date!='') echo date('d-m-Y',strtotime($row->guest_reg_date));?>" readonly="readonly"/> 
                      </div>
                    </div>
                  <?php }else{ ?>
                    <div class="form-group col-xs-12 col-md-6 col-sm-6" >
                      <label for="name">Guest Registration Date<font color="#FF0000">*</font></label>
                      <div class="input-group"> 
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i> 
                        </div>
                        <input data-parsley-errors-container="#guestRegistrationError" data-parsley-require type="text" class="form-control dates"  value="<?php echo date('d-m-Y'); ?>" id="guest_reg_date" name="guest_reg_date" onchange="hideandshow()"> 
                        <span id="guestRegistrationError"></span>
                      </div>
                    </div>
                  <?php } ?>
                  <?php if($row->id ==''){?>
                    <style type="text/css">
                       /*#ind{
                        display: none;
                       }*/
                    </style>
                    <?php } ?> 
                    <div style="display: none">
                       <input type="text" class="form-control" placeholder="Prefix" id="prefix" name="prefix" value="<?php if($_POST['prefix']) echo $_POST['prefix'];else echo stripslashes($prefix);?>" readonly> 
                       <input type="text" class="form-control" placeholder="Guest No" id="doc_no" name="doc_no" value="<?php if($_POST['doc_no']) echo $_POST['doc_no'];else echo stripslashes($row->doc_no);?>" readonly> 
                       <input type="text" class="form-control" placeholder="Suffix" id="suffix" name="suffix" value="<?php if($_POST['suffix']) echo $_POST['suffix'];else echo stripslashes($suffix);?>" readonly>
                    </div>
                    <div id="guestResDiv">
                      <div class="form-group col-md-6 col-sm-6">
                        <label for="guestres">Guest Registration Number</label>
                        <div class="input-group"> 
                          <div class="input-group-addon">
                            <i class="fa fa-caret-square-o-left"></i> 
                          </div>
                          <input type="text" class="form-control" placeholder="Guest Registration Number" id="guestRegNo" value="<?php if($_POST['doc_no']) echo $_POST['prefix'].$_POST['doc_no'].$_POST['suffix'];else echo stripslashes($prefix).stripslashes($row->doc_no).stripslashes($suffix);?>" disabled="disabled"> 
                        </div>
                      </div>
                    </div>
                    
                    <?php if($row->id ==''  || $prefix != ''){ 
                      $mdocRequired='';

                      ?>
                      <style type="text/css">
                        #hideandshow{
                          display: none;
                        }

                      </style>
                      <?php }else{$docRequired='data=data-parsley-required';} ?>
                    <div id="hideandshow" name="hideandshow">
                        <div class="form-group col-md-4 col-sm-4">
                          <label for="name">Manual Guest No</label>
                          <div class="input-group"> 
                            <div class="input-group-addon">
                              <i class="fa fa-list-ol"></i> 
                            </div>
                            <input <?php echo $docRequired;?>  type="text" class="form-control" placeholder="Enter Manual Guest No" id="guest_reg_no" name="guest_reg_no" value="<?php if($_POST['guest_reg_no']) echo $_POST['guest_reg_no'];else echo stripslashes($row->guest_reg_no); ?>">
                          </div> 
                        </div>                       
                    </div> 
                  </div>                                  
                  <div class="row">
                      <div class="form-group col-md-4 col-sm-4">
                        <label for="title">Title<font color="#FF0000">*</font></label>
                          <select class="form-control select2" style="width: 100%;" id="title" name="id_mst_attributes_title" data-parsley-errors-container="#titleError" data-parsley-required>
                              <option value="">Select Title</option>
                              <?php 
                                $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop= ".addslashes($_SESSION['shop'])." and `table_name` = 'title' Order By id ");

                                if($db->num_rows2($resCat)){

                                  while($resultCat = $db->fetch_object2($resCat)){  
                                   
                                    if($row->id_mst_attributes_title == $resultCat->id){

                                       $selected = 'selected="selected"';

                                    }else{

                                      $selected = '';

                                    }

                                    $titleDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
                                  }
                                }
                                 echo $titleDropDown;
                              ?>
                          </select>
                        <span id="titleError"><?php echo $err_title;?></span>
                      </div>
                      <div class="form-group col-xs-12 col-md-4 col-sm-4">
                          <label for="first_name">Firstname<font color="#FF0000">*</font></label>
                          <div class="input-group"> 
                              <div class="input-group-addon">
                                  <i class="fa fa-user-o"></i> 
                              </div>
                            <input type="text" class="form-control" placeholder="Enter Guest Firstname" id="first_name" name="first_name" value="<?php if($_POST['first_name']) echo $_POST['first_name'];else echo stripslashes($row->first_name);?>" data-parsley-errors-container="#first_nameError" data-parsley-required/>
                          </div>
                          <span id="first_nameError"><?php echo $err_first_nameError;?></span>
                      </div>
                       <div class="form-group col-xs-12 col-md-4 col-sm-4">
                          <label for="last_name">Lastname<font color="#FF0000">*</font></label>
                          <div class="input-group"> 
                              <div class="input-group-addon">
                                  <i class="fa fa-user-o"></i> 
                              </div>
                            <input type="text" class="form-control" placeholder="Enter Lastname" id="last_name" name="last_name" value="<?php if($_POST['last_name']) echo $_POST['last_name'];else echo stripslashes($row->last_name);?>" data-parsley-errors-container="#last_nameError" data-parsley-required/>
                          </div>
                          <span id="last_nameError"><?php echo $err_last_nameError;?></span>
                      </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-sm-2">
                      <label for="primary_contact_type">Primary contact<font color="#FF0000">*</font></label>
                      <select name="primary_contact_type" id="primary_contact_type" class="form-control select2" style="width: 100%" data-parsley-errors-container="#primary_contactError" >
                        <?php if($row->primary_contact_type == 1){?>
                              <option value="1" selected="selected">Mobile</option>
                              <option value="2">Landline</option>
                          <?php }else if($row->primary_contact_type == 2){ ?>
                            <option value="2" selected="selected">Landline</option>
                            <option value="1">Mobile</option>
                          <?php }else{ ?>
                          <option value="1" selected="selected">Mobile</option>
                          <option value="2">Landline</option>
                        <?php } ?>  
                      </select>
                      <span id="primary_contactError"></span>
                    </div>
                    <div id="primaryContactDiv">
                      <?php if($row->primary_contact_type == 1){ ?>
                      <div class="form-group col-md-2 col-sm-2">
                        <label for="primary_mobile">Mobile
                        </label>
                        <input type="text" class="form-control" placeholder="Enter Mobile" id="primary_mobile" name="primary_mobile" value="<?php if($_POST['primary_mobile']) echo $_POST['primary_mobile']; else echo $row->primary_mobile;?>" data-parsley-errors-container="#primary_mobileError" data-parsley-type="digits" />
                        <span id="primary_mobileError"><?php echo $err_primary_mobile;?></span>
                      </div>
                      <?php }else if($row->primary_contact_type == 2){?>
                      <div class="form-group col-md-2 col-sm-2">
                        <label for="primary_landline">Landline
                        </label>
                        <input type="text" class="form-control" placeholder="Enter Mobile" id="primary_landline" name="primary_landline" value="<?php if($_POST['primary_landline']) echo $_POST['primary_landline']; else echo $row->primary_landline;?>" data-parsley-errors-container="#primary_landlineError" data-parsley-type="digits"   />
                        <span id="primary_landlineError"><?php echo $err_primary_landlineError;?></span>
                      </div>
                      <?php }else{ ?>
                      <div class="form-group col-md-2 col-sm-2">
                        <label for="primary_mobile">Mobile
                        </label>
                        <input type="text" class="form-control" placeholder="Enter Mobile" id="primary_mobile" name="primary_mobile" value="<?php if($_POST['primary_mobile']) echo $_POST['primary_mobile']; else echo $row->primary_mobile;?>" data-parsley-errors-container="#primary_mobileError" data-parsley-type="digits"  />
                        <span id="primary_mobileError"><?php echo $err_primary_mobileError;?></span>
                      </div>
                    <?php } ?>
                    </div>
                    <div class="form-group col-md-2 col-sm-2">
                      <label for="secondary_contact_type">Secondary contact</label>
                      <select name="secondary_contact_type" id="secondary_contact_type" class="form-control select2" style="width: 100%">
                        <?php if($row->secondary_contact_type == 1){?>
                          <option value="1" selected="selected">Landline</option>
                          <option value="2">Mobile</option>
                        <?php }else if($row->secondary_contact_type == 2){?>
                          <option value="2" selected="selected">Mobile</option>
                          <option value="1">Landline</option>
                        <?php }else{ ?>
                          <option value="1" selected="selected">Landline</option>
                          <option value="2">Mobile</option>
                       <?php } ?>
                      </select>
                    </div>
                    <div id="secondaryContactDiv">
                      <?php if($row->secondary_contact_type == 1){ ?>
                        <div class="form-group col-md-2 col-sm-2">
                          <label for="secondary_landline">Landline</label>
                          <input type="text" class="form-control" placeholder="Enter Landline" id="secondary_landline" name="secondary_landline" value="<?php if($_POST['secondary_landline']) echo $_POST['secondary_landline']; else echo $row->secondary_landline;?>"data-parsley-type="digits" />
                        </div>
                      <?php }else if($row->secondary_contact_type == 2){ ?>
                        <div class="form-group col-md-2 col-sm-2">
                          <label for="secondary_mobile">Mobile</label>
                          <input type="text" class="form-control" placeholder="Enter Landline" id="secondary_mobile" name="secondary_mobile" value="<?php if($_POST['secondary_mobile']) echo $_POST['secondary_mobile']; else echo $row->secondary_mobile;?>"data-parsley-type="digits" />
                        </div>
                      <?php }else{ ?>
                        <div class="form-group col-md-2 col-sm-2">
                          <label for="secondary_landline">Landline</label>
                          <input type="text" class="form-control" placeholder="Enter Landline" id="secondary_landline" name="secondary_landline" value="<?php if($_POST['secondary_landline']) echo $_POST['secondary_landline']; else echo $row->secondary_landline;?>"data-parsley-type="digits" />
                        </div>
                      <?php } ?>
                    </div>
                    <div class="form-group  col-md-4 col-sm-4">
                     <label for="email">Email Id</label>
                        <div class="input-group"> 
                          <div class="input-group-addon">
                              <i class="fa fa-envelope"></i> 
                          </div>
                          <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email Id" value="<?php if($_POST['email']) echo $_POST['email'];else echo stripslashes($row->email);?>"data-parsley-errors-container="#emailError" data-parsley-type="email"  />
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
                            <textarea class="form-control" name="address" id="address" name="address" rows="1" placeholder="Enter Address" data-parsley-errors-container="#addressError" data-parsley-required><?php if($_POST['address']) echo $_POST['address'];else echo stripslashes($row->address);?>
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
                          <input type="text" class="form-control" id="city" name="city" placeholder="Enter City" value="<?php if($_POST['city']) echo $_POST['city'];else echo stripslashes($row->city);?>"data-parsley-errors-container="#cityError" data-parsley-required/>
                        </div>
                        <span id="cityError"><?php echo $err_cityError;?></span>
                      </div>
                      <div class="form-group col-md-4 col-sm-4">
                        <label for="postcode">Pincode<font color="#FF0000">*</font></label>
                        <div class="input-group"> 
                            <div class="input-group-addon">
                                <i class="fa fa-map-pin"></i> 
                            </div>
                          <input type="text" class="form-control" id="postcode" name="postcode" placeholder="Enter Pincode" value="<?php if($_POST['postcode']) echo $_POST['postcode'];else echo stripslashes($row->postcode);?>"data-parsley-errors-container="#postcodeError"  data-parsley-required/>
                        </div>
                         <span id="postcodeError"><?php echo $err_postcodeError;?></span>
                      </div>
                       
                  </div>
                  <div class="row">
                      <div class="form-group  col-md-4 col-sm-4">
                        <label for="id_mst_country_lang">Country<font color="#FF0000">*</font></label>
                        <div class="input-group"> 
                            <div class="input-group-addon">
                                <i class="fa fa-flag"></i> 
                            </div>
                            <!--select class="form-control select2" name="id_country" id="id_country" style="width:100%" data-parsley-errors-container="#countryError" data-parsley-required onchange="getState(this.value,'','');" -->

                            <select class="form-control select2" name="id_mst_country_lang" id="id_mst_country_lang" style="width:100%" data-parsley-errors-container="#id_mst_country_langError" data-parsley-required >
                              <option value="">Select Country</option>
                              <?php 
                                $resCat = selectSql(TBL_COUNTRY_LANG,"where id_lang='1' ",' ORDER BY `name`');

                                if($db->num_rows2($resCat)){

                                  while($resultCat = $db->fetch_object2($resCat)){  
                                    if($_REQUEST['id_mst_country_lang'] == $resultCat->id_country){

                                      $selected = 'selected="selected"';

                                    }elseif($row->id_mst_country_lang == $resultCat->id_country){

                                      $selected = 'selected="selected"';

                                    }else{

                                      $selected = '';

                                    }

                                    $countryDropDown .= '<option '.$selected.' value="'.$resultCat->id_country.'">'.ucfirst($resultCat->name).'</option>';
                                  }
                                }
                                 echo $countryDropDown;
                              ?>
                            <?php if($row->id_mst_country_lang == 10000){?>
                            <option value="10000" selected="selected">Other</option>
                             <?php } else{ ?>
                                <option value="10000">Other</option>
                             <?php } ?>
                         </select>
                        </div>
                        <span id="id_mst_country_langError"><?php echo $err_id_mst_country_langError;?></span>
                        
                      </div>
                      <div class="form-group col-md-4 col-sm-4">
                        <label for="id_mst_state">State<font color="#FF0000">*</font></label>
                        <div class="input-group"> 
                            <div class="input-group-addon">
                                <i class="fa fa-adjust"></i> 
                            </div>
                              <div id="state"> 
                               <select class="form-control select2"  name="id_mst_state" id="id_mst_state"  style="width:100%" data-parsley-errors-container="#id_mst_stateError" data-parsley-required>
                                <?php  if(!empty($row->id_mst_state) && $row->id_mst_state != 10000){
                                   $resCat = selectSql(TBL_STATE," where id_mst_country_lang='".$row->id_mst_country_lang."' ",' ORDER BY `name` ');
                                  if($db->num_rows2($resCat)){
                                    while($resultCat = $db->fetch_object2($resCat)){  
                                        if($row->id_mst_state == $resultCat->id_state){

                                          $selected = 'selected="selected"';

                                        }else{

                                          $selected = '';

                                        }

                                        $stateDropDown .= '<option '.$selected.' value="'.$resultCat->id_state.'">'.ucfirst($resultCat->name).'</option>';
                                    }
                                  }
                                   echo $stateDropDown;
                                   echo '<option value="10000">Other</option>';
                                 }else if($row->id_mst_state == 10000){?>
                                <option value="10000" selected="selected">Other</option>
                                <?php } else{ ?>
                                  <option value="" selected="selected">Select Please</option>
                                  <option value="10000">Other</option>
                                <?php } ?>
                              </select>
                            </div>
                        </div>
                        <span id="id_mst_stateError"><?php echo $err_id_mst_stateError;?></span>
                      </div>
                      
                      <div class="form-group col-md-4 col-sm-4">
                        <label for="nationality">Nationality<font color="#FF0000">*</font></label>
                         <div class="input-group"> 
                            <div class="input-group-addon">
                                <i class="fa fa-flag-o"></i> 
                            </div>
                              <select class="form-control select2" style="width: 100%;" id="nationality" name="id_mst_country_lang_nationality" data-parsley-errors-container="#nationalityError" data-parsley-required>
                                <?php 
                                  if(!empty($row->id_mst_country_lang_nationality) && $row->id_mst_country_lang_nationality != 10000){

                                    $resCat = selectSql(TBL_COUNTRY_LANG," where id_country='".$row->id_mst_country_lang."' ",' ORDER BY `name` ');
                                    if($db->num_rows2($resCat)){
                                      $resultCat = $db->fetch_object2($resCat);
                                      echo  '<option value="'.htmlentities($resultCat->id_country).'" selected="selected">'.ucfirst($resultCat->nationality) .'</option>';
                                    }

                                  }else if($row->id_mst_country_lang_nationality == 10000){?>
                                    
                                    <option value="10000" selected="selected">Other</option>

                                  <?php } ?>

                            </select>  
                        </div>
                          <span id="nationalityError"><?php echo $err_nationalityError;?></span>
                      </div>  
                  </div>

                  <div class="row">
                    <div id="otherCountryDiv" class="form-group col-sm-4">
                      <?php if($row->id_mst_country_lang == 10000){ ?>
                        <label for="other_country">Other Country<font color="#FF0000">*</font></label>
                        <input type="text" name="other_country" id="other_country" class="form-control" placeholder="Enter Country Name" value="<?php if($_POST['other_country']) echo $_POST['other_country']; else echo $row->other_country;?>" data-parsley-errors-container="#other_countryError" data-parsley-required />
                        <span id="other_countryError"></span>
                      <?php } ?>
                    </div>
                    <div id="otherStateDiv" class="form-group col-sm-4">
                      <?php if($row->id_mst_state == 10000){ ?>
                        <label for="other_state">Other State<font color="#FF0000">*</font></label>
                        <input type="text" name="other_state" id="other_state" class="form-control" placeholder="Enter State Name" value="<?php if($_POST['other_state']) echo $_POST['other_state']; else echo $row->other_state;?>" data-parsley-errors-container="#other_stateError" data-parsley-required />
                        <span id="other_stateError"></span>
                      <?php } ?>
                    </div>
                    <div id="otherNationalityDiv" class="form-group col-sm-4">
                      <?php if($row->id_mst_country_lang_nationality == 10000){ ?>
                        <label for="other_nationality">Other Nationality<font color="#FF0000">*</font></label>
                        <input type="text" name="other_nationality" id="other_nationality" class="form-control" placeholder="Enter State Name" value="<?php if($_POST['other_nationality']) echo $_POST['other_nationality']; else echo $row->other_nationality;?>" data-parsley-errors-container="#other_nationalityError" data-parsley-required />
                        <span id="other_nationalityError"></span>
                      <?php } ?>
                    </div>
                </div>     
                  
                  <div class="row">
                    <div class="form-group col-md-4 col-sm-4">
                        <label for="date_birth_day">Date of Birth</label>
                       <div class="row">
                          <div class="form-group col-md-6 col-sm-6">
                            <div class="input-group">
                              <div class="input-group-addon">
                                <i class="fa fa-birthday-cake"></i> 
                              </div> 
                                <select class="form-control select2" style="width: 100%;" id="date_birth_day" name="date_birth_day">
                                  <?php
                                     if(!empty($row->date_birth_day)){
                                      for($Birthday = 1; $Birthday <= 31; $Birthday++){
                                        if($Birthday==$row->date_birth_day){
                                          $selected = 'selected="selected"';
                                        }else{

                                          $selected = '';
                                        }
                                        echo "<option value=\"$Birthday\" $selected>$Birthday</option>";
                                      } 
                                     }else{
                                      $selected = 'selected="selected"';
                                      echo "<option value='' $selected>Day</option>";
                                        for($Birthday = 1; $Birthday <= 31; $Birthday++){

                                        echo "<option value=\"$Birthday\">$Birthday</option>";
                                        } 
                                     }
                                     
                                ?>
                                </select>  
                              </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                              <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i> 
                                </div> 
                                <select class="form-control select2" style="width: 100%;" id="date_birth_month" name="date_birth_month">
                                <?php 
                                  if(!empty($row->date_birth_month)){
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
                                    echo "<option value='' $selected>Month</option>";
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
                     
                      <div class="form-group col-md-4 col-sm-4">
                          <label for="date_anniversary_day">Anniversary</label>
                          <div class="row">
                           <div class="form-group col-md-6 col-sm-6">
                                <div class="input-group">
                                  <div class="input-group-addon">
                                    <i class="fa fa-gift"></i> 
                                  </div> 
                                  <select class="form-control select2" style="width: 100%;" id="date_anniversary_day" name="date_anniversary_day">
                                  <?php 
                                    if(!empty($row->date_anniversary_day)){
                                        for($Birthday = 1; $Birthday <= 31; $Birthday++){
                                          if($Birthday==$row->date_anniversary_day){
                                              $selected = 'selected="selected"';
                                          }else{
                                            $selected = '';
                                          }
                                          echo "<option value=\"$Birthday\" $selected>$Birthday</option>";
                                        } 
                                      }else{
                                        $selected = 'selected="selected"';
                                        echo "<option value='' $selected>Day</option>";
                                        for($Birthday = 1; $Birthday <= 31; $Birthday++){

                                           echo "<option value=\"$Birthday\">$Birthday</option>";
                                        } 
                                       }
                                    ?>
                                  </select>  
                                </div>
                              </div>
                              <div class="form-group col-xs-12 col-md-6 col-sm-6">
                                <div class="input-group">
                                  <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i> 
                                  </div> 
                                  <select class="form-control select2" style="width: 100%;" id="date_anniversary_month" name="date_anniversary_month">
                                    <?php 
                                      if(!empty($row->date_anniversary_month)){
                                        for($i = 1; $i <= 12; $i++){
                                          if($i==$row->date_anniversary_month){
                                            $selected = 'selected="selected"';
                                          }else {
                                            $selected = '';
                                          }
                              
                                          $dt = DateTime::createFromFormat('!m', $i);
                                            echo "<option value=\"$i\" $selected >".$dt->format('F')."</option>";
                                        }
                                      }else{
                                        $selected = 'selected="selected"';
                                        echo "<option value='' $selected>Month</option>";
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
                      <div class="form-group  col-md-4 col-sm-4">
                        <label for="gender">Gender<font color="#FF0000">*</font></label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-intersex"></i> 
                            </div>
                            <select class="form-control select2" style="width: 100%;" id="gender" name="gender" data-parsley-errors-container="#genderError" data-parsley-required>
                              <?php if($row->gender == 1){ ?> 
                                <option  value="1" selected="selected">Male</option>
                                <option value="2">Female</option>
                                <option value="3">Other</option>
                              <?php }else if($row->gender == 2){ ?>
                                <option  value="1">Male</option>
                                <option value="2" selected="selected">Female</option>
                                <option value="3">Other</option>
                               <?php }else if($row->gender == 3){ ?>
                                <option  value="1">Male</option>
                                <option value="2">Female</option>
                                <option value="3" selected="selected">Other</option>
                              <?php }else{ ?>   
                                <option selected="selected" value="">Select Gender</option>
                                <option  value="1">Male</option>
                                <option value="2">Female</option>
                                <option value="3">Other</option>
                              <?php } ?>
                            </select>
                        </div>
                         <span id="genderError"><?php echo $err_genderError;?></span> 
                        
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-xs-12 col-md-4 col-sm-4">
                      <label for="guest_vipstatus">Guest VIP Status</label>
                      <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-user"></i> 
                        </div>
                        <select class="form-control select2" style="width: 100%;"  id="guest_vipstatus" name="guest_vipstatus">
                          <?php if($row->guest_vipstatus == 1){ ?>
                            <option value="1" selected="selected">VIP</option>
                            <option value="2">CIP</option>
                          <?php }else if($row->guest_vipstatus == 2){ ?>
                            <option value="1">VIP</option>
                            <option value="2" selected="selected">CIP</option>
                          <?php }else{ ?>
                            <option selected="selected" value="">Select Guest Status</option>
                            <option value="1">VIP</option>
                            <option value="2">CIP</option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>     
                    <div class="form-group col-xs-12 col-md-4 col-sm-4">
                      <label for="membership_status">Membership Status</label>
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-group"></i> 
                        </div>
                        <select class="form-control select2" style="width: 100%;"  id="membership_status" name="membership_status" data-parsley-errors-container="#statusError" data-parsley-required>
                          <?php if($row->membership_status == 1){ ?>
                            <option value="1" selected="selected">Member</option>
                            <option value="0">Non Member</option>
                          <?php }else if($row->membership_status == 0){ ?>
                            <option value="0" selected="selected">Non Member</option>
                            <option value="1">Member</option>
                          <?php }else{ ?>
                            <option selected="selected" value="">Select Memebership</option>
                            <option value="1">Member</option>
                            <option value="0" selected="selected">Non Member</option>
                          <?php } ?>   
                          
                        </select>
                      </div>
                      <span id="statusError"><?php echo $err_statusError;?></span>
                    </div> 
                    <div class="form-group  col-md-4 col-sm-4">
                      <label for="guest_note">Guest Note </label>   
                      <div class="input-group">
                          <div class="input-group-addon">
                              <i class="fa fa-comment"></i> 
                          </div>
                          <textarea class="form-control" name="guest_note" id="guest_note" rows="1"><?php if($_POST['guest_note']) echo $_POST['guest_note'];else echo stripslashes($row->guest_note);?></textarea>
                      </div>
                    </div>    
                </div> 
              </div>
              <hr/>
              <div class="card text-dark bg-light">
                <div class="bg-primary text-center ">
                  <h5 style="padding: 5px;">ID Proof Details</h5>
                </div> 
                <hr/>
                <div class="row">
                  <div class="form-group col-md-3 col-sm-3">
                    <label for="proof_type">Id Proof Details</label>
                    <div class="input-group"> 
                      <div class="input-group-addon">
                        <i class="fa fa-address-card"></i> 
                      </div>
                      <select class="form-control select2" style="width: 100%;" id="proof_type" name="proof_type">
                          <?php if($row->proof_type == 1){ ?>
                            <option value="1" selected="selected">Voter Id</option>    
                            <option value="2">Adhar</option>
                            <option value="3">Passport</option>
                          <?php }else if($row->proof_type == 2){?>
                            <option value="2" selected="selected">Adhar</option>
                            <option value="1">Voter Id</option>
                            <option value="3">Passport</option>
                          <?php }else if($row->proof_type == 3){?>
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
                  </div>
                  <div id="appenddata">
                    <?php if($row->proof_type == 1){ ?>
                      <div class="form-group col-xs-12 col-md-3 col-sm-3">
                        <label for="voter_no">Voter Id Number <font color="#FF0000">*</font></label>
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa fa-address-book"></i>
                          </div>
                          <input type="text" class="form-control" id="voter_no" name="voter_no" placeholder="Enter Voter Id Number" value="<?php if($_POST['voter_no']) echo $_POST['voter_no']; else echo $row->voter_no;?>"data-parsley-errors-container="#voter_noError" data-parsley-required />
                        </div>
                        <span id="voter_noError"><?php echo $err_voter_noError;?></span>
                      </div>
                      <?php }else if($row->proof_type == 2){ ?>
                        <div class="form-group col-xs-12 col-md-3 col-sm-3">
                          <label for="adhar_no">Adhar Number <font color="#FF0000">*</font></label>
                          <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa fa-address-book"></i>
                            </div>
                            <input type="text" class="form-control" id="adhar_no" name="adhar_no" placeholder="Enter Aadhar Number" value="<?php if($_POST['adhar_no']) echo $_POST['adhar_no']; else echo $row->adhar_no;?>"data-parsley-errors-container="#adhar_noError" data-parsley-required />
                          </div>
                          <span id="adhar_noError"><?php echo $err_adhar_noError;?></span>
                        </div>
                      <?php }else if($row->proof_type == 3){ ?>
                      <div class="form-group col-xs-12 col-md-3 col-sm-3">
                        <label for="passport_no">Passport Number <font color="#FF0000">*</font></label>
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa fa-address-book"></i>
                          </div>
                          <input type="text" class="form-control" id="passport_no" name="passport_no" placeholder="Enter Passport Number" value="<?php if($_POST['passport_no']) echo $_POST['passport_no']; else echo $row->passport_no;?>" data-parsley-errors-container="#passport_noError" data-parsley-required />
                        </div> 
                        <span id="passport_noError"><?php echo $err_passport_noError;?></span>
                      </div>
                      <div class="form-group col-xs-12 col-md-3 col-sm-3">
                        <label for="authority">Authority<font color="#FF0000">*</font></label>
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa-arrows"></i>
                          </div>
                          <input type="text" class="form-control" id="authority" name="authority" placeholder="Enter Authority" value="<?php if($_POST['authority']) echo $_POST['authority']; else echo $row->authority;?>" data-parsley-errors-container="#authorityError" data-parsley-required /></div>
                          <span id="authorityError"><?php echo $err_authorityError;?></span></div>
                          <div class="form-group col-xs-12 col-md-3 col-sm-3">
                            <label for="passport_expiry_date">Expiry Date<font color="#FF0000">*</font></label>
                            <div class="input-group">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar-minus-o"></i>
                              </div>
                              <input type="text" class="form-control datepicker" id="passport_expiry_date" name="passport_expiry_date" placeholder="dd-mm-yyyy" value="<?php if($_POST['passport_expiry_date']) echo $_POST['passport_expiry_date']; else echo date('d-m-Y',strtotime($row->passport_expiry_date));?>" data-parsley-errors-container="#passport_expiry_dateError" data-parsley-required />
                            </div>
                            <span id="passport_expiry_dateError"><?php echo $err_passport_expiry_dateError;?></span>
                          </div>
                      <?php } ?>
                  </div>  
                </div>
              </div>
              <hr/>
              <div class="card text-dark bg-light">
                  <div class="bg-primary text-center ">
                      <h5 style="padding: 5px;">Guest Photo</h5>
                  </div> 
                  <hr>
                  <div class="row">
                      <div class="col-sm-3">
                          <div class="form-group">
                              <label for="guestimage">Guest Photo &nbsp;&nbsp;</label>
                              <div class="btn btn-default btn-file">
                                <i class="fa fa-upload"></i> Upload
                               <input type="file" class="form-control" placeholder="Guest Image" id="image" name="image" value="" onchange="readURL(this);">   
                               <input type="hidden" name="old_image" value="<?php echo stripslashes($row->image);?>"/>                    
                          
                              </div>
                              <p class="help-block">Must be of width:600px and height:300px.<br />Max. Size: 1MB</p>      
                          </div>
                          <?php echo $err_image;?>
                      </div>
                      <div class="col-sm-9">                                                  
                          <ul class="mailbox-attachments clearfix"> 
                              <li id="imageCallback">
                              <?php if(@file_exists($image_path.$row->image) && $row->image!=''){ ?>
                              <span class="mailbox-attachment-icon has-img">                           
                                  <img src="<?php echo $image_display_path.$row->image; ?>" alt="Guest Image">                              
                                </span>           
                                <div class="mailbox-attachment-info">
                                  <a href="javascript:void(0);" class="mailbox-attachment-name"><i class="fa fa-camera"></i> <?php echo $row->image; ?></a>
                                      <span class="mailbox-attachment-size">
                                        <?php echo round(filesize($image_path.$row->image)/ 1024 ,2).' KB'; ?>
                                        <a href="<?php echo $image_display_path.$row->image; ?>" download class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                                      </span>
                                </div>
                              <?php }else{ ?>                         
                              <span class="mailbox-attachment-icon has-img">                           
                                  <img src="../images/no-hotel-image.jpg" alt="Item Image" id="blah">                            
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
             
              <div class="row">
                  <div class="form-group col-sm-4" style="margin-top:10px;">
                    <label for="status">Status </label>
                    <input type="radio" class="flat-red"  <?php if($_POST['status'] == '1'){echo "checked";}else{if($row->status == 1)echo "checked";}?> value="1" name="status" checked/>
                    Active
                    <input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($row->status == "0")echo "checked";}?> value="0" name="status"/>
                    Inactive <?php echo $err_status;?> </div>
                </div>
               <?php if($row->date_created){?>
                <div class="row">
                  <div class="form-group col-sm-4">
                    <label for="date_created">Date Created</label>
                    <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($row->date_created));?>">
                  </div>
                  <div class="form-group col-sm-4">
                    <label for="last_modified">Last Updated</label>
                    <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($row->last_modified));?>">
                  </div>
                  <div class="form-group col-sm-4">
                    <label for="last_modified_by">Last Updated By</label>
                    <?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$row->id_mst_user_modified_by."'",''));?>
                    <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->user_name);?>">
                  </div>
                </div>
              <?php } ?>
              <hr/>
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
          <div class="box-footer">

             <input type="submit" value="<?=($_REQUEST['gId']==''?'Add':'Edit')?>" class="btn btn-primary"  onclick="guestDetailForm()">
              &nbsp;&nbsp; 
              <a class="btn btn-danger" href='manageGuests.php'>Close</a>
			  <input type='button' value='Audit Trail' class="btn btn-success"  onclick="audittrial(this.value);" style="float:right">
          </div>
      </form>
  </div>
</div>

<!--script src="../actions/js/guestForm.js"></script -->

<script type="text/javascript">
    $(document).ready(function(){
      $(document).on('change', '#primary_contact_type', function(){
            var primaryContact = $(this).val();
            if(primaryContact == 1){
              var mobile = '<div class="form-group col-md-2 col-sm-2"><label for="primary_mobile">Mobile<font color="#FF0000">*</font></label><input type="text" class="form-control" placeholder="Enter Mobile" id="primary_mobile" name="primary_mobile" value="<?php if($_POST['primary_mobile']) echo $_POST['primary_mobile']; else echo $row->primary_mobile;?>" data-parsley-errors-container="#primary_mobileError" data-parsley-type="digits" data-parsley-required /><span id="primary_mobileError"><?php echo $err_primary_mobileError;?></span></div>';

                $("#primaryContactDiv").html(mobile);

            }else if(primaryContact == 2){
              var landline = '<div class="form-group col-md-2 col-sm-2"><label for="primary_landline">Landline<font color="#FF0000">*</font></label><input type="text" class="form-control" placeholder="Enter Landline" id="primary_landline" name="primary_landline" value="<?php if($_POST['primary_landline']) echo $_POST['primary_landline']; else echo $row->primary_landline;?>" data-parsley-errors-container="#primary_landlineError" "data-parsley-type="digits" data-parsley-required /><span id="primary_landlineError"><?php echo $err_primary_landlineError;?></span></div>';

                $("#primaryContactDiv").html(landline);
            }
          });

          $(document).on('change', '#secondary_contact_type', function(){
            var secondaryContact = $(this).val();
            if(secondaryContact == 1){
              var landline = '<div class="form-group col-md-2 col-sm-2"><label for="secondary_landline">Landline</label><input type="text" class="form-control" placeholder="Enter Landline" id="secondary_landline" name="secondary_landline" value="<?php if($_POST['secondary_landline']) echo $_POST['secondary_landline']; else echo $row->secondary_landline;?>"data-parsley-type="digits"/></div>';

                $("#secondaryContactDiv").html(landline);

            }else if(secondaryContact == 2){
              var mobile = '<div class="form-group col-md-2 col-sm-2"><label for="secondary_mobile">Mobile</label><input type="text" class="form-control" placeholder="Enter Mobile" id="secondary_mobile" name="secondary_mobile" value="<?php if($_POST['secondary_mobile']) echo $_POST['secondary_mobile']; else echo $row->secondary_mobile;?>"data-parsley-type="digits"/></div>';

                $("#secondaryContactDiv").html(mobile);
            }
          });

          $(document).on('change', '#id_mst_country_lang', function(){
            var otherCountry  = $(this).val();
            if(otherCountry == 10000){
              var countryDiv = `<label col="other_country">Other Country <font color="#FF0000">*</font></label><input type="text" name="other_country" id="other_country" class="form-control" placeholder="Enter Country Name" value="<?php if($_POST['other_country']) echo $_POST['other_country']; else echo $row->other_country;?>" data-parsley-errors-container="#other_countryError" data-parsley-required /><span id="other_countryError"></span>`;

              $("#otherCountryDiv").html(countryDiv);

            }else{
              $("#otherCountryDiv").html('<div></div>');
            }
            $.ajax({
              type : 'POST',
              url : '../actions/ajax/ajaxGetState.php',
              data : {countryId : otherCountry},
              success : function(data){
                $("#id_mst_state").html(data); 
                if($("#id_mst_state").val() != 10000)
                {
                  $("#otherStateDiv").html('<div></div>');
                }
              }
            });

            $.ajax({
              type : 'POST',
              url : '../actions/ajax/ajaxGetNationality.php',
              data : {countryId : otherCountry},
              success : function(data){
                $("#nationality").html(data); 
                if($("#nationality").val() != 10000)
                {
                  $("#otherNationalityDiv").html('<div></div>');
                }
              }
            });

          });

          $(document).on('change', '#id_mst_state', function(){
            var otherState  = $(this).val();
            if(otherState == 10000){
              var stateDiv = `<label col="other_state">Other State<font color="#FF0000">*</font></label><input type="text" name="other_state" id="other_state" class="form-control" placeholder="Enter State Name" value="<?php if($_POST['other_state']) echo $_POST['other_state']; else echo $row->other_state;?>" data-parsley-errors-container="#other_stateError" data-parsley-required /><span id="other_stateError"></span>`;

              $("#otherStateDiv").html(stateDiv);

            }else{
              $("#otherStateDiv").html('<div></div>');
            }
          });

           $(document).on('change', '#nationality', function(){
            var otherState  = $(this).val();
            if(otherState == 10000){
              var stateDiv = `<label col="other_nationality">Other Nationality<font color="#FF0000">*</font></label><input type="text" name="other_nationality" id="other_nationality" class="form-control" placeholder="Enter nationality" value="<?php if($_POST['other_nationality']) echo $_POST['other_nationality']; else echo $row->other_nationality;?>" data-parsley-errors-container="#other_nationalityError" data-parsley-required /><span id="other_nationalityError"></span>`;

              $("#otherNationalityDiv").html(stateDiv);

            }else{
              $("#otherNationalityDiv").html('<div></div>');
            }
          });

          $(document).on('change','#proof_type',function(){
            
            var idProof = $(this).val();
             
            if(idProof == 1){
                var Vote_Id = '<div class="form-group col-xs-12 col-md-3 col-sm-3"><label for="voter_no">Voter Id Number <font color="#FF0000">*</font></label><div class="input-group"><div class="input-group-addon"><i class="fa fa fa-address-book"></i></div><input type="text" class="form-control" id="voter_no" name="voter_no" placeholder="Enter Voter Id Number" data-parsley-errors-container="#voter_noError" data-parsley-required /></div><span id="voter_noError"><?php echo $err_voter_noError;?></span></div>'; 
                $("#appenddata").html(Vote_Id);
             }
             else if(idProof == 3)
            {
                var pass ='<div class="form-group col-xs-12 col-md-3 col-sm-3"><label for="passport_no">Passport Number <font color="#FF0000">*</font></label><div class="input-group"> <div class="input-group-addon"><i class="fa fa fa-address-book"></i></div><input type="text" class="form-control" id="passport_no" name="passport_no" placeholder="Enter Passport Number" data-parsley-errors-container="#passport_noError" data-parsley-required /> </div> <span id="passport_noError"><?php echo $err_passport_noError;?></span></div><div class="form-group col-xs-12 col-md-3 col-sm-3"><label for="authority">Authority<font color="#FF0000">*</font></label><div class="input-group"><div class="input-group-addon"><i class="fa fa-arrows"></i></div><input type="text" class="form-control" id="authority" name="authority" placeholder="Enter Authority" data-parsley-errors-container="#authorityError" data-parsley-required /></div><span id="authorityError"><?php echo $err_authorityError;?></span></div><div class="form-group col-xs-12 col-md-3 col-sm-3"><label for="passport_expiry_date">Expiry Date<font color="#FF0000">*</font></label><div class="input-group"><div class="input-group-addon"><i class="fa fa-calendar-minus-o"></i></div><input type="date" class="form-control datepicker" id="passport_expiry_date" name="passport_expiry_date" placeholder="dd-mm-yyyy" data-parsley-errors-container="#passport_expiry_dateError" data-parsley-required /></div><span id="passport_expiry_dateError"><?php echo $err_passport_expiry_dateError;?></span></div>';
                
                $("#appenddata").html(pass);

            }
            else if(idProof == 2)
            {
                var Aadhar = '<div class="form-group col-xs-12 col-md-3 col-sm-3"><label for="adhar_no">Aadhar Number <font color="#FF0000">*</font></label><div class="input-group"><div class="input-group-addon"><i class="fa fa fa-address-book"></i></div><input type="text" class="form-control" id="adhar_no" name="adhar_no" placeholder="Enter Adhar Number" data-parsley-errors-container="#adhar_noError" data-parsley-required /></div><span id="adhar_noError"><?php echo $err_adhar_noError;?></span></div>'; 
                $("#appenddata").html(Aadhar);
            }else{
              $("#appenddata").html('<div></div>');
            }
        });

    });

</script>
<script type="text/javascript">

/*function guestDetailForm(){
  var form = $("#guestForm");
  var formData = $("#guestForm").serialize();

  if(form.parsley().validate()){
   $('.loading').show(); 
   $.ajax({
        url : '../actions/ajax/ajaxEditGuestForm.php',
        type : 'POST',
        data : formData,
        success : function(response){
          //alert(formData);
          alert(response);
        }
    });
  }else{
    
  }
} */



function guestDetailForm(){
  var form = $("#guestForm");
  //var formData = $("#guestForm").serialize();

  if(form.parsley().validate()){
   $('.loading').show(); 
   $("#guestForm").on('submit', function(event){
    event.preventDefault();    
    var formData = new FormData(this);
    $.ajax({
      url : '../actions/ajax/ajaxEditGuestForm.php',
      type : 'POST',
      data : formData,
      success: function (data) {
        bootbox.alert({
          message: data,
          callback: function () {

            if(window.location.href == "http://localhost/application/master/editGuest.php"){
              window.scrollTo(0,0); 
              location.reload();
            }else{
              window.scrollTo(0,0); 
              $("#tab_3,#tab_1,#tab_4,#tab_5,#tab_6,.nav-tabs li").removeClass("active");
              $("#tab_2,#reservation_tab").addClass("active");
            }
            
          }
        });
      },
      cache: false,
      contentType: false,
      processData: false,
    });

   });
  }else{
    
  }
  //return true;
} 

$( document ).ready(function() {
    var dates = '<?php echo ($guest_reg_date!=""?date("d-m-Y",strtotime($guest_reg_date)):date("d-m-Y")); ?>'; 
    $('.dates').datepicker({ dateFormat: "dd-mm-yy" , minDate: dates });
    //Button hide 
     
  });


function hideandshow() {

    //alert("hello");
    
    var doc_type = document.getElementById("doc_type");
      var doc_type = doc_type.options[doc_type.selectedIndex].value;

      var guest_reg_date = document.getElementById("guest_reg_date").value; 
     
    if(doc_type != '' && guest_reg_date !='') {
      $('#guestResDiv').show(); 
      
      $.ajax({
        type: "POST",
        url: "../ajax/GuestManage.php",
        data:{doc_type:doc_type, guest_reg_date:guest_reg_date},
        success: function(data){
          var mydata = JSON.parse(data);  
          if(mydata['method'] == 1){
            $('#hideandshow').hide();   
            $('#guestResDiv').show();   
            <?php if($row->id == ''){?>
              document.getElementById("doc_no").value = mydata['doc_no'];
              document.getElementById("guestRegNo").value = mydata['doc_no'];
              document.getElementById("id_mst_doc_type_configuration").value = mydata['id_mst_doc_type_configuration'];
            <?php } ?>
            document.getElementById("guestRegNo").value = mydata['prefix']+mydata['doc_no']+mydata['suffix'];
            document.getElementById("prefix").value = mydata['prefix'];
            document.getElementById("suffix").value = mydata['suffix'];

          }else{
            $('#hideandshow').show();
            $('#guestResDiv').hide(); 
            <?php if($row->id == ''){?>
              document.getElementById("doc_no").value = mydata['doc_no'];
              document.getElementById("guestRegNo").value = mydata['doc_no'];
              document.getElementById("id_mst_doc_type_configuration").value = mydata['id_mst_doc_type_configuration'];
            <?php } ?>
            document.getElementById("prefix").value = '';
            document.getElementById("suffix").value = '';
          }
        }
      });
    }
  } 

  <?php if(empty($_REQUEST['gId']) && $_REQUEST['gId']==''){ ?>
     window.onload = hideandshow;
  <?php } ?>

  if(window.location.href == "http://localhost/application/frontoffice/onewindow.php?submenu=218#"){
    <?php if(empty($_REQUEST['gId']) && $_REQUEST['gId']==''){ ?>
      hideandshow();
    <?php } ?>
  }

  /*$(document).ready(function(){
    $("#guestForm").on('submit', function(e){
      e.preventDefault();
      var formData = $("#guestForm").serialize();
      $.ajax({
        url : '../actions/ajax/ajaxEditGuestForm.php',
        type : 'POST',
        data : formData,
        success : function(response){
          alert(response);
        }
      });
    })
  }); */


function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#blah').attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}
  
</script>


<script type="text/javascript">

	function audittrial(clicked_value){
		//alert(clicked_value);
		//var id = document.getElementById('id_mst_hotels').value;
		$('#auditModal').modal('show');
		var table ='mst_guest';
		$.ajax({
			url: "../functions/ajaxAuditTrail.php",
			  type: 'POST',
				data: { tablename : table },
				dataType: "JSON",
				success: function(data) {
				// alert(data);
			  $('#roombutton').html(data);
			}
	   });
	}
	
	
</script>