<?php 
include_once("../../config/auto_loader.php");

?>


  <!-- form start -->
         <?php     $companySql = "  SELECT * FROM `".TBL_COMPANY."`
                WHERE `id` = '".$_REQUEST['eId']."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
  $db->query($companySql);
  if($db->num_rows() > 0){
    $row = $db->fetch_object();
//print_r($row);
  }
  ?>
          <form   id="companybypopupform" method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off" >
        <input type="hidden" id="EditCompanyID" name="EditCompanyID" value="<?php echo $row->id; ?>" > 
        
           <div class="form-group has-error" align="center">
              <?php if($_SESSION['errorMsg']){?>
              <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
              <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
              <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
              <?php unset($_SESSION['successMsg']);}?>
            </div>
           
            <div class="box-body">
              <div class="row">
                <div class="form-group col-sm-4">
                  <label for="id_mst_attributes_company_group">Company Group<font color="#FF0000">*</font></label>
                  <select class="form-control select2"  style="width:100%" name="id_mst_attributes_company_group" id="id_mst_attributes_company_group"  data-parsley-errors-container="#id_mst_attributes_company_groupError" required="required" data-parsley-required >
                  <?php $categoryDropDown = '
									<option value="">Select Company  Group</option>';
												  $resCat = selectSql(TBL_ATTRIBUTES," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND table_name='company_group' ",' ORDER BY `field_value`');
												  if($db->num_rows2($resCat)){
												  	while($resultCat = $db->fetch_object2($resCat)){
														if($_REQUEST['id_mst_attributes_company_group'] == $resultCat->id){
															$selected = 'selected="selected"';
														}elseif($row->id_mst_attributes_company_group == $resultCat->id){
															$selected = 'selected="selected"';
														}else{
															$selected = '';
														}
														$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
													}
												  }
												 	echo $categoryDropDown .= '</select>';?>
                                                    <span id="id_mst_attributes_company_groupError"></span> 
                </div>
                <div class="form-group col-sm-4">
                  <label for="name">Company Name<font color="#FF0000">*</font></label>
                 <input autocomplete="off" type="text" class="form-control awesomplete" data-list="#mylist" placeholder="Enter Company name" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($row->name);?>" data-parsley-required >
	                  <ul id="mylist" style="display:none;">
	                    <?php  $resCat = selectSql(TBL_COMPANY," where status=1  and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `id`');
												  if($db->num_rows2($resCat)){
												  	while($resultCat = $db->fetch_object2($resCat)){
														$companyDropDown .= '<li>'.ucfirst($resultCat->name).'-'.ucfirst($resultCat->city).'</li>';
													}
												  }
												 	echo $companyDropDown;
						?> </div>


               <!-- <div class="form-group col-sm-4">
                  <label for="email">Email Id<font color="#FF0000">*</font></label>
                  <input type="email" class="form-control"  placeholder="Enter email id" id="email" name="email"  >
                  </div>-->
<div class="form-group col-sm-4">
                  <label for="secondary_email">Seconday Email</label>
                  <input type="text" class="form-control" value="<?php echo $row->secondary_email;?>" placeholder="Enter seconday email id" id="secondary_email" name="secondary_email"  data-parsley-type="email"  >
                  <?php echo $err_email;?> </div>
                  
                  <div class="form-group col-sm-4">
                  <label for="fax">GST Number</label>
                  <input type="text" class="form-control" placeholder="Enter fax number" id="fax" name="fax" value="<?php echo $row->fax;?>">
                  <?php echo $err_fax;?> </div>
                <div class="form-group col-sm-4">
                  <label for="address">Address</label>
                  <textarea class="form-control" name="address" id="address"  rows="1" placeholder="Enter Address" > 
					  <?php echo addslashes($row->address);?>
</textarea>
                  <?php echo $err_address;?> </div>
                  
                   <div class="form-group col-sm-4">
                  <label for="name">City<font color="#FF0000">*</font></label>
                  <input autocomplete="off" type="text" class="form-control awesomplete" data-list="#citylist" placeholder="Enter City" id="city" name="city" data-parsley-required  value="<?php echo $row->city;?>">
                  <ul id="citylist" style="display:none;">
                    <?php  //$resCat = selectSql(TBL_COMPANY,'distinct',"  where status=1  and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `id_company`');

                    $citySql="SELECT DISTINCT city from `".TBL_COMPANY."` WHERE  status=1  and id_shop='".addslashes($_SESSION['shop'])."' ORDER BY `id_company`";

    $resCat = mysqli_query($connNew,$citySql);

                      


                        if($db->num_rows2($resCat)){
                          while($resultCat = $db->fetch_object2($resCat)){
                          $cityDropDown .= '<li>'.ucfirst($resultCat->city).'</li>';
                        }
                        }
                       echo $cityDropDown;
                        
          ?>
                  </ul>
                   </div>
                   <div class="form-group col-sm-4">
                  <label for="id_country" >Country<font color="#FF0000">*</font></label>               
                               <select class="form-control select2" style="width:100%" name="id_mst_country_lang" id="id_mst_country_lang" data-parsley-errors-container="#countryError" required="required" data-parsley-required>
	                <option value="">Select Country</option>
	                <?php 
									$resCat = selectSql(TBL_COUNTRY_LANG,"where id_lang='1' ",' ORDER BY `name`');
												  if($db->num_rows2($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($_REQUEST['id_mst_country_lang'] == $resultCat->id_country){
															$selected = 'selected="selected"';
														}elseif($row->id_mst_country_lang == $resultCat->id_country){
														$selected = 'selected="selected"';
														}elseif(110 == $resultCat->id_country){
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
	              <span id="countryError"></span>  </div>
                  
                  
                 <div class="form-group col-sm-4">
                  <label for="id_state">State <font color="#FF0000">*</font></label>
                <div class="input-group"> 
	                    <div class="input-group-addon">
	                        <i class="fa fa-adjust"></i> 
	                    </div>
	                      <div id="state"> 
	                       <select class="form-control select2"  name="id_mst_state" id="id_mst_state"  style="width:100%" data-parsley-errors-container="#id_mst_stateError" data-parsley-required><option value="">Select state</option>
	                        <?php  
	                           $resCat = selectSql(TBL_STATE," where id_mst_country_lang='110' ",' ORDER BY `name` ');
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
	                          ?>
	                      </select>
                           
	                    </div>
	                </div><span id="id_mst_stateError"></span> </div> 
                    
                    
                    <div class="form-group col-sm-4">
                  <label for="postcode">Pincode</label>
                  <input type="text" class="form-control" placeholder="Enter pincode" id="postcode" name="postcode" value="<?php echo $row->postcode;?>">
                  <?php echo $err_postcode;?> </div>
                  
              

<div class="form-group  col-sm-4">
                  <label for="phone">Phone Number</label>
                  <input type="text" class="form-control" placeholder="Enter phone number" id="phone" name="phone" value="<?php echo $row->primary_mobile;?>" >
                  <?php echo $err_phone;?> </div>
                  </div>
             <div class="row">
              

                   
                </div><!--end of row-->  
 
                  
        

              <div class="row">
                
                
              
            <!--   </div>
              <div class="row">-->
                

                   
               <!-- <div class="form-group col-sm-4">
                  <label for="city">City<font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter city" id="city" name="city" value="<?php if($_POST) echo $_POST['city'];else echo $row->city;?>" data-parsley-required>
                  <?php echo $err_city;?> </div>
              -->


                <!--<div class="form-group col-sm-4">

                <label for="city">City </label>

                <select class="form-control select2 itemName" name="city" id="city"   >

                </select>
             </div> --> 
  
            </div>
            
              
              </div>
        <br />
    
     
            <div class="form-group col-sm-12" align="left">
              <input  type="button" class="btn btn-default" onClick="savenewCompanyPopupform();" value="Save">
              
              <button type="button" class="viewincPopUp_close btn btn-default" data-dismiss="modal">Close</button>
            </div>
 <br /> <br /> <br />
          </form>
				
				<script>


function savenewCompanyPopupform() {  
    var EditCompanyID = $("#EditCompanyID").val() || "";
    var form = $("#companybypopupform");

    if (form.parsley().validate()) {
        $('.loading').show();

        $.ajax({
            type: "POST",
            url: 'ajax/ajaxSavenewCompany.php',
            data: form.serialize() + "&EditCompanyID=" + EditCompanyID,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    // Update the dropdown with returned HTML
                    $('#id_bill_to_company').html(response.dropdown_html);

                    // Reinitialize select2 if needed
                    $('#id_bill_to_company').select2({
                        dropdownParent: $('.bootbox')
                    });

                    // Reset the form
                    form[0].reset();

                    // Close the Bootbox modal
                   // $('.bootbox.modal').modal('hide');
$('.bootbox.modal').last().modal('hide');
                    // Show success message after modal is hidden
                    setTimeout(() => {
                        bootbox.alert(response.message || "Company added successfully!");
                    }, 500);
                } else {
                    bootbox.alert(response.message || "Failed to save company.");
                }
            },
            error: function () {
                bootbox.alert("Something went wrong while saving the company.");
            },
            complete: function () {
                $('.loading').hide();
            }
        });

        return false;
    }
}

				</script>