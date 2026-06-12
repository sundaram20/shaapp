<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_PURCH,'add');

?>
<script src="<?php echo $SITE_URL; ?>/pos/js/custom.js<?php echo '?'.mt_rand(); ?>"></script>
<?php include_once("../includes/header.php")?>
  <?php include_once("../includes/left.php");

//unset($_SESSION['POSKOT']);
$POSCurrentDate	=date('d-m-Y',strtotime("-3 day", strtotime(date('d-m-Y'))));
unset($_SESSION['POSKOT']['outlet']);
$UniqueCodeGen = 'UNIC'.rand(0000,9999);
    $image_path="images/steward/";

$date =date('Y-m-d');	
$doc_type_kot='22';
$id_subsection = '0' ;	 
$retunDocConfig	=	docConfigNoValidator($doc_type_kot,$date,$id_subsection);	
$id_doc_type_configuration	=	$retunDocConfig['id_doc_type_configuration'];
	 $sqlNat = "SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE   `doc_type`='22' and id='".$id_doc_type_configuration."'";
	 $resToNat = mysqli_query($connNew,$sqlNat);
 	 $numRowsNat =  mysqli_num_rows($resToNat);
	   $rowNat =  mysqli_fetch_object($resToNat);
		$enable_nationality= $rowNat->enable_nationality;
		$enable_steward_passcode= $rowNat->enable_steward_passcode;

  ?>
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/pos/css/style.css<?php echo '?'.mt_rand(); ?>" />
  <style>
.discountvalue {
    border-radius: 1px;
    width: 10px !important;
    float: left;
    padding: 0px !important;
    text-align: center;
    height: 17px !important;
    display: flex;
    align-items: center;
    background-color: #31c6d5 !important;
    border: none;
}

.quant {
    width: 27px !important;
}

.btn-danger {
    background: #f2f2f2;
    color: #ff7777;
    border: none;
    width: 34px !important;
    height: 17px !important;
}

#MyTableTableList {
    position: relative;

    z-index: 1;
}

.modal-body {
    text-align: center;
}

#passwordInput {
    text-align: center;
    font-size: 18px;
    margin-bottom: 10px;
}

.keypad button {
    width: 50px;
    height: 50px;
    font-size: 16px;
    margin: 5px;
}

.mainPasswordModal{
  display : flex!mportant;
  justify-content : center;
  align-items : center!important;
}

.modalSelectBtn{

}
</style>
  <div class="content-wrapper"> 
    
    <!-- ItemModal Modal -->
    <?php $id_item = $_POST['id']; ?>
    <div class="modal fade" id="ItemModal" tabindex="-1" role="dialog" aria-labelledby="ItemModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color:#172635; color: #fff;text-align: center;">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
            <h6 class="modal-title" id="ItemModalLabel">Sub Items</h6>
          </div>
          <div class="modal-body">
            <div id="ajaxPlanData">
              <div class="box table-responsive no-padding">
                <input type="text" name="bd_count" id="bd_count" hidden="">
                <br />
                <table class="table table-bordered table-striped">
                  <!--	<thead style="text-align:center">
							<tr>
								<th>Sub Item Name</th>
							</tr>
						</thead>  -->
                  
                  <tbody id="subitemlist" style="display:inline-flex">
                  </tbody>
                </table>
              </div>
            </div>
            <!--  <div style="text-align:center">
					<button type="button" class="btn btn-danger" data-dismiss="modal"> <span class="glyphicon glyphicon-off"></span> Close </button>
			   </div> --> 
          </div>
          
          <!-- <div class="modal-footer"  style="background-color: #e4e4e4;color: #fff;"> 
				<button type="button" class="btn btn-success" data-dismiss="modal" id="submit" onclick="AddgetItemlist1(this.id);"> <span class="glyphicon glyphicon-ok"></span> Submit</button>
			   <button type="button" class="btn btn-danger" data-dismiss="modal"> <span class="glyphicon glyphicon-off"></span> Cancel</button>
			</div> --> 
          
        </div>
      </div>
    </div>
    <!-- End ItemModal Modal -->
    
    <?php $session = $_GET['submenu']; ?>
    <section class="content-header"> 
      <!--<h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
		<?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>
      </h3>-->
      <div class="row">
        <div class="col-md-3 col-xs-12">
          <h6 class="box-title" style="margin:0px !important;padding: 0px!important;"> <?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation_id($session)['submenu']; ?> : <a><?php echo selectColumn(TBL_INV_PO,'doc_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND 'id' = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'"); ?></a> </h6>
        </div>
        <div class="col-md-5 col-xs-12 dd-f">
          <div class="icn-box">
            <div class="btn-group"> <a type="button" title="List KOT" class="btn n-btn pull-right"
                            href="manageKot.php?submenu=178&session=22"> <i class="fas fa-list"></i> KOT</a> </div>
            <div class="btn-group  "> <a type="button" title="Add Bill" class="btn n-btn pull-right"
                            href="kotbilling.php?submenu=177&session=21"><i class="fas fa-plus "></i> Bill </a> </div>
            <div class="btn-group"> <a type="button" title="List Bill" class="btn n-btn pull-right"
                            href="manageOutletBilling.php?submenu=177&session=21"> <i class="fas fa-list "></i> Bill</a> </div>
            <div class="btn-group"> <a type="button" title="KOT Table View" class="btn n-btn pull-right"
                            href="pendingkots.php?submenu=178"> <i class="fas fa-table"></i> KOT</a> </div>
            <div class="btn-group"> <a type="button" title="Kitchen Display System" class="btn n-btn pull-right"
                            href="kds.php?submenu=178"> <i class="fas fa-tv"></i> KDS </a> </div>
            <div class="btn-group"> <a type="button" onClick="AddPosGuest();" title="Add POS Guest"
                            class="btn n-btn pull-right"> <i class="fas fa-plus"></i> POS Guest </a></div>
          </div>
        </div>
        <div class="col-md-4 col-xs-12 tb-br"> <?php echo breadCrumbs(); ?> </div>
      </div>
    </section>
    
    <!-- Main content -->
    
    <section class="content kot-content">
    <div class="box box-default"> 
      <!--<div class="box-header with-border">
             <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation_id($session)['submenu']; ?> : <a><?php echo selectColumn(TBL_INV_PO,'doc_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND 'id' = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'"); ?></a></h3>
            </div>-->
      <div class="form-group has-error" align="center">
        <?php if($_SESSION['errorMsg']){?>
        <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
        <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
        <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
        <?php unset($_SESSION['successMsg']);}?>
      </div>
      <div>
        <div> 
          
          <!-- /.box-header -->
          
          <form name="FormPosKot" id="FormPosKot" action="kotbilling.php?submenu=177" method="post">
          <input type="hidden" value="1" name="FormSubmitPosKot" />
          <input type="hidden" value="<?php echo $_REQUEST['submenu'];?>" name="submenu1" id="submenu1">
          <input type="hidden" id="enable_nationality_show" name="enable_nationality_show"
                            value="<?php echo $enable_nationality;?>">
          <input type="hidden" value="<?php echo $enable_steward_passcode;?>"
                            name="enable_steward_passcode" id="enable_steward_passcode">
          <input type="hidden" value="0" name="passcode_valid_status" id="passcode_valid_status">
          <input type="hidden" value="<?php echo $_REQUEST['doc_type'];?>" name="kotType" id="kotType">
          <input type="hidden" value="" name="NonChargeableRemarks" id="NonChargeableRemarks">
          <div class="box-body kotbox">
          
          <!-----------------Table Part END--------------------> 
          <!-- Button trigger modal --> 
          
          <!--<button type="button" class="btn col-md-1" data-toggle="modal" data-target="#tableModal">
 <div class="tooltiptable"> <img style="height:32px;" title="Table" src="images/TABLE.jpg">

  <span class="tooltiptext" id="tooltiptext">Click here</span>
</div>
</button>--> 
          <!--
<div class="tooltiptable">Hover over me
  <span class="tooltiptext" id="tooltiptext">Tooltip text</span>
</div>-->
          
          <div class="col-xs-3  col-md-1   p-0 shiftbox">
            <div class="form-group"> 
              <!-- <label for="name">Shift <font color="#FF0000">*</font> </label>-->
              <div class="input-group1">
                <?php 
     
       $_REQUEST['id_attribute_shift'] = selectColumn(TBL_ATTRIBUTES,'id'," WHERE table_name ='shift' and`field_category` = 'default'  ");
      
      $categoryDropDown = '<select  onChange="updateShift(this.value)" class="form-control select2" name="id_attribute_shift" id="id_attribute_shift" data-parsley-required data-parsley-errors-container="#id_shiftError">

                  <option value="">Select Shift</option>';

                  $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'shift'."' ",' ORDER BY `field_value`');

                  if($db->num_rows2($resCat)){

                    while($resultCat = $db->fetch_object2($resCat)){

                    if($_REQUEST['id_attribute_shift'] == $resultCat->id){

                      $selected = 'selected="selected"';

                    }elseif($row->id_mst_attributes_store == $resultCat->id){

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
              <span id="id_shiftError"></span> </div>
          </div>
          <!--end of shift--> 
          
          <!-- Modal -->
          <div class="modal" id="tableModal" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-body">
                  <div id="myDIV">
                    <div class="row">
                      <div class="col-sm-6 col-md-3 box-header with-border paxbox mobpax"
                                                        style="display:hnone;"> 
                        <!--<h5 class="box-title">Main Group </h5>-->
                        
                        <label for="name" title="Table"><i class="fas fa-chair"></i> : <span id="ViewSelectedTable2"
                                                                style="color:#FF0000"></span></label>
                        <label for="name" title="No of Pax"> <i class="fa fa-users"></i> : <span id="ViewSelectedPax2"
                                                                style="color:#FF0000"></span></label>
                        <label for="name" title="Steward Name"><i
                                                                class="fa-solid fa-person"></i> : <span
                                                                id="ViewSteward2" style="color:#FF0000"></span></label>
                        
                        <!--natinality strats--> 
                        
                      </div>
                      <?php       
		 if($enable_nationality=='1'){ ?>
                      <div class="col-sm-6 col-md-3 box-header with-border paxbox paxbox2"
                                                        style="display:hnone;">
                        <div class="" id="MyNationalitySelect" tyle="">
                          <div class="form-group mb-0">
                            <div class="box-body table-responsive border-none"
                                                                    style="padding-left: 0px;padding-bottom: 0px;padding-top: 0px;padding-right: 4px;">
                              <div style="display:flex;">
                                <label for="name" style="display:flex;"
                                                                            title="Nationality"><i
                                                                                class="fa-solid fa-flag"></i> : </label>
                                <table id=""
                                                                            class="table table-fixedTableGroup table-striped table-bordered dataTable no-footer"
                                                                            cellspacing="0">
                                  <tbody style="display:flex;height:auto;">
                                    <?php 



          $resCatNat = selectSql(TBL_COUNTRY_LANG,"where  status = '1' AND `id_lang` = '1' AND nationality!='' ",' ORDER BY `name` asc');

                  if($db->num_rows2($resCatNat)){

                    $i=1;

                    while($resultCatNat = $db->fetch_object2($resCatNat)){

                    if($i==1){

                      $ClassNameNat='btn tablenationalitybtn';
					  $DefaultCountryName='Indian';
					  $DefaultCountryID='110';

                    }else{

                      //$ClassName='';

                      $ClassNameNat='btn tablenationalitybtn';

                      }

                      if($i==1){

                  echo $groupNat = '<input name="id_mst_country_lang" id="id_mst_country_lang" type="hidden"  value=""  >';

                      }

echo $categoryDropDownNat ='<tr><td><a href="#" class="mstcountrylang '.$ClassNameNat.'" name="id_mst_country_lang" id="'.$resultCatNat->id_country.'_'.$resultCatNat->name.'" type="button" value="'.ucfirst($resultCatNat->nationality).'"  onclick="SelectNationality(this.id);reset();" >'.ucfirst($resultCatNat->nationality).'</a></td></tr>';

                    //$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';

                    $i++;

                  }

                  }

                  //echo $categoryDropDown;

                  ?>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                        
                        <!--natinality ends--> 
                        
                      </div>
                      <?php } ?>
                      <div class="col-xs-12 col-sm-12 col-md-12 mt-10">
                        <div class="form-group mb-0">
                          <label class="tablegrouplabel" for="name">Table<br>
                            Group <font color="#FF0000">*</font> </label>
                          <div class="box-body table-responsive "
                                                                style="padding-left: 0px;padding-bottom: 0px;padding-top: 0px;padding-right: 4px;">
                            <div id="MyTableGroupID">
                              <table id="myTableTableList"
                                                                        class="table table-fixedTableGroup table-striped table-bordered dataTable no-footer"
                                                                        cellspacing="0">
                                <tbody style="display:flex;height:auto;">
                                  <?php 



				  $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'table_group'."' ",' ORDER BY `display_order` asc');

								  if($db->num_rows2($resCat)){

									  $i=1;

								  	while($resultCat = $db->fetch_object2($resCat)){

										if($i==1){

											$ClassName='btn tablegroupbtn activetablegroup';

										}else{

											//$ClassName='';

											$ClassName='btn tablegroupbtn';

											}

											if($i==1){

									echo $group =	'<input name="id_attribute_table_group" id="id_attribute_table_group" type="hidden"  value="'.ucfirst($resultCat->id).'"  >';

											}

echo $categoryDropDown ='<tr><td><a href="#" name="TableGroup" id="TableGroup_'.$resultCat->id.'" type="button" class="'.$ClassName.'" value="'.ucfirst($resultCat->field_value).'"  onclick="getTable(this.id);" >'.ucfirst($resultCat->field_value).'</a></td></tr>';

										//$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';

										$i++;

									}

								  }

								 	//echo $categoryDropDown;

								  ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div id="id_table">
                        <div class="col-md-12 mt-5">
                          <div class="form-group"
                                                                style="margin-bottom: 0px !important;">
                            <label class="tablelabel" for="name">Table <font
                                                                        color="#FF0000">*</font> </label>
                            <div class="box-body table-responsive"
                                                                    style="padding-left: 0px;padding-top: 0px;padding-right: 4px;">
                              <div id="TableListview">
                                <table id="myTableTest"
                                                                            class="table table-fixed table-striped table-bordered dataTable no-footer"
                                                                            cellspacing="0">
                                  <tbody>
                                    <?php 

					  $ResultBlockedtable =array();
					  $Resultdoc_type=array();

	//$CheckBlockedTable_Sql_1 = "SELECT id_attribute_table,doc_type FROM pos_purch WHERE pos_bill_type='1'  AND doc_type!='24' AND id IN (SELECT id_pos_purch as posid FROM pos_purch_details WHERE qty-adj_qty>0  AND DATE(date_created)>'".$POSCurrentDate."') and doc_type='".$_SESSION['id_document']."'";
		$POSCurrentStartDate = date('d-m-Y',strtotime("-3 day", strtotime(date('d-m-Y'))));
	$POSCurrentEndDate 	= 	date('Y-m-d');
    $CheckBlockedTable_Sql_1 ="SELECT id_attribute_table,sum(total_qty) as total_qty, sum(total_adj_qty) as total_adj_qty FROM `pos_purch` WHERE `pos_bill_type` = 1 and cancelled!=1 and (DATE(date_created) BETWEEN '".$POSCurrentStartDate."' and '".$POSCurrentEndDate."' ) and doc_type!='24' and total_qty-total_adj_qty>0 and doc_type='".$_SESSION['id_document']."' GROUP BY id_attribute_table";
			

		   $db->query($CheckBlockedTable_Sql_1); 

		  while($ResultBlockedtable1_1 = $db->fetch_object()){ 	                  	
			$Resultdoc_type[]	=	$ResultBlockedtable1_1->id_attribute_table;
			$ResultBlockedtable[]	=	$ResultBlockedtable1_1->id_attribute_table;
			  }
			  
	//$CheckBlockedTable_Sql = "SELECT id_attribute_table,doc_type FROM pos_purch WHERE cancelled=0  AND doc_type!='24' AND id IN (SELECT id_pos_purch as posid FROM pos_purch_details WHERE qty-adj_qty>0  AND DATE(date_created)>'".$POSCurrentDate."')";
/*$CheckBlockedTable_Sql = "SELECT id_attribute_table,sum(total_qty) as total_qty, sum(total_adj_qty) as total_adj_qty FROM `pos_purch` WHERE `pos_bill_type` = 1 and cancelled!=1 and (DATE(date_created) BETWEEN '".$POSCurrentStartDate."' and '".$POSCurrentEndDate."' ) and doc_type!='24' and doc_type='".$_SESSION['id_document']."' and total_qty-total_adj_qty>0  GROUP BY id_attribute_table";

	
	                   $db->query($CheckBlockedTable_Sql); 

	                  while($ResultBlockedtable1 = $db->fetch_object()){ 

	                   
						

					  }*/

	                  	
					  
$resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'table_group'."' ",' ORDER BY `display_order` asc limit 0,1');
				//$resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'table_group'."' ",' ORDER BY `id` asc');

								  if($db->num_rows2($resCat)){

								  	$resultCat = $db->fetch_object2($resCat);  

								  }

	 $resContact = selectSql(TBL_ATTRIBUTES," where id_shop='".$_SESSION['shop']."'   AND id_table_group='".$resultCat->id."'  AND  status = '1' AND table_name ='".'table'."' ",' ORDER BY LPAD(lower(`field_value`),6,0) asc');

          if($db->num_rows2($resContact) > 0){?>
                                    <?php	

                $i=1;

                while($rowContact = $db->fetch_object2($resContact)){

                if($i==1){?>
                                    <tr>
                                      <?php } 

                    if (in_array($rowContact->id, $ResultBlockedtable))

  {
			
			if (in_array($rowContact->id, $Resultdoc_type)){
	 ?>
                                      <td class="btn tableviewBlockedbtn"
                                                                                        onclick="TabeleSelect(this.id); PreviousOrderPax(this.id,<?php echo '\''.$UniqueCodeGen.'\''?>);ajaxRemoveAllItemList('removeAll',<?php echo '\''.$UniqueCodeGen.'\''?>);"
                                                                                        id="TableGroup_<?php echo $rowContact->id ?>_<?php echo $rowContact->field_value;?>"><?php echo $rowContact->field_value;?></td>
                                      
                                      <!--<td style="width:14% !important; padding:5px 10px;" class="btn tableviewBlockedbtn" onclick="TabeleSelect(this.id); PreviousOrder(this.id)" id="TableGroup_<?php echo $rowContact->id ?>_<?php echo $rowContact->field_value;?>"><?php echo $rowContact->field_value;?></td>-->
                                      
                                      <?php }else{ ?>
                                      <td style=" background-color:#c6574b;"
                                                                                        class="btn tableviewBlockedbtn"
                                                                                        id="TableGroup_<?php echo $rowContact->id ?>_<?php echo $rowContact->field_value;?>"><?php echo $rowContact->field_value;?></td>
                                      <?php }
  
  
  }

else

  { ?>
                                      <td style="hi2"
                                                                                        class="btn tableviewbtn"
                                                                                        onclick="TabeleSelect(this.id); PreviousOrderPax(this.id,<?php echo '\''.$UniqueCodeGen.'\''?>);ajaxRemoveAllItemList('removeAll',<?php echo '\''.$UniqueCodeGen.'\''?>);"
                                                                                        id="TableGroup_<?php echo $rowContact->id ?>_<?php echo $rowContact->field_value;?>"><?php echo $rowContact->field_value;?></td>
                                      <?php }

                      ?>
                                      <?php if($i==5){ $i=1;?>
                                    </tr>
                                    <?php }else{ $i++; } ?>
                                    <?php

														

																										

		}}?>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div id="PreviousPaxsID">
                        <div class="col-md-12" style="display:nne;">
                          <div class="form-group"
                                                                style="margin-bottom: 0px !important;">
                            <label class="paxlabel" for="name">Paxs<font
                                                                        color="#FF0000">*</font> </label>
                            <div class="box-body table-responsive"
                                                                    style="padding: 0px;">
                              <div id="MyNumOfPax">
                                <table id="myTableTest"
                                                                            class="table table-fixed table-striped table-bordered dataTabletest no-footer"
                                                                            cellspacing="0">
                                  <tbody>
                                    <?php  for ($i=1; $i<=50; $i++)

    				{
						echo $categoryDropDown = '<tr class="paxloadmore"><td style="" class="noofpaxbtn" id="'.$i.'" onclick="SelectNoPaxs(this.id);">'.$i.'</td></tr>';
					}
				?>
                                    <tr class="paxloadbtn">
                                      <td class="btn" onclick="Paxload()"><i class="fa fa-plus"></i></td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- <div class="col-md-3">
                <div class="form-group">
                   <-- <label for="name">Shift <font color="#FF0000">*</font> </label>--
                    <div class="input-group1">
                    <?php 
			
			$_REQUEST['id_attribute_shift'] = selectColumn(TBL_ATTRIBUTES,'id'," WHERE table_name ='shift' and`field_category` = 'default'  ");
			
			$categoryDropDown = '<select class="form-control select2" name="id_attribute_shift" data-parsley-required data-parsley-errors-container="#id_shiftError">

									<option value="">Select Shift</option>';

								  $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'shift'."' ",' ORDER BY `field_value`');

								  if($db->num_rows2($resCat)){

								  	while($resultCat = $db->fetch_object2($resCat)){

										if($_REQUEST['id_attribute_shift'] == $resultCat->id){

											$selected = 'selected="selected"';

										}elseif($row->id_mst_attributes_store == $resultCat->id){

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
                    <span id="id_shiftError"></span> </div>
              </div>
              --end of shift-->
                        <div class="col-md-11">
                          <div class="form-group"
                                                                style="margin-bottom: 0px !important;"> 
                            <!--       <label for="name">Steward <font color="#FF0000">*</font> </label>-->
                            <div class="box-body table-responsive"
                                                                    style="padding: 0px;">
                              <div id="MyStewardSelect">
                                <table id="myTableTest"
                                                                            class="table table-fixedsteward table-striped table-bordered dataTabletest no-footer"
                                                                            cellspacing="0">
                                  <tbody>
                                    <?php $categoryDropDown = '<select class="form-control select2" name="id_attribute_steward" id="id_attribute_steward" data-parsley-required data-parsley-errors-container="#id_stewardError">

									<option value="">Select Steward</option>';

								  $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'steward'."' ",' ORDER BY `field_value`');

								  if($db->num_rows2($resCat)){

								  	while($resultCat = $db->fetch_object2($resCat)){

										if($_REQUEST['id_attribute_steward'] == $resultCat->id){

											$selected = 'selected="selected"';

										}elseif($row->id_attribute_steward == $resultCat->id){

											$selected = 'selected="selected"';

										}else{

											$selected = '';

										}

										if($resultCat->image!=''){
										$image =  $image_path.$resultCat->image;
										}else{
											$image = "images/steward.png";
										}


										 $categoryDropDown .= '<tr><td class="noofpaxbtn bt" id="'.$resultCat->id.'_'.$resultCat->field_value.'" onclick="SelectSteward(this.id);">'.ucfirst($resultCat->field_value).'<img src="'.$image.'" style="height:53px;border-radius:50%;"></td></tr>';

									}

								  }

								 	echo $categoryDropDown .= '</select>';

								  ?>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div> </div>
                      </div>
                      
                      <!------------------------->
                      <?php if($enable_steward_passcode=='1'){ /*?>
                      <div class="col-md-11">
                        <div class="form-group" style="margin-bottom: 0px !important;">
                          <label for="name">Passcode <font color="#FF0000">*</font> </label>
                          <div class="box-body table-responsive"
                                                                style="padding: 0px;">
                            <div>
                              <input type="password"
                                                                        class="form-group col-md-2 col-sm-2" style="
    padding: 10px 20px;
    border: 1px solid #b3b1b1!important;
    color: #575353;
    background-color: rgba(0,0,0,0);
    border-radius: 4px;
    transition: box-shadow .5s,border-color .25s ease-in-out;
    font-size: 1.6rem!important;
}" value="<?php echo $enable_steward_passcode_value;?>" name="enable_steward_passcode_value"
                                                                        id="enable_steward_passcode_value"
                                                                        onkeyup="ValidatePasscode(this.value);">
                              <span id="Msgpasscode_value"
                                                                        style="margin:25px;"></span> </div>
                          </div>
                        </div>
                      </div>
                      <?php */ } ?>
                      <!------------------------> 
                    </div>
                    <div class="modal-footer">
                      <button type="button" style="padding:10px;"
                                                        class="btn c-btn btn-default"
                                                        data-dismiss="modal">Close</button>
                    </div>
                  </div>
                  
                  <!-----------------Table Part END--------------------> 
                </div>
              </div>
            </div>
          </div>
          <!--modal popup ends--> 
          
          <!--Add Items Remarks Modal Popups Starts-->
          <div class="modal" id="itemModalRemarks" class="itemModalRemarks" tabindex="-1"
                                role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
          <div class="modal-content">
          <div class="modal-header"
                                            style="background-color:#172635; color: #fff;text-align: center;">
            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="roomtypeModalLabel">Special Request</h4>
          </div>
          <div class="modal-body">
          <div id="myDIV">
          <div class="">
          <div class="form-group">
          <form method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="specialRequestUniqueCode"
                                                                id="specialRequestUniqueCode" value="" />
            <input type="hidden" name="UniqueCodeGen" id="UniqueCodeGen"
                                                                value="" />
            <input type="text" class="form-control"
                                                                name="special_request_name" id="special_request_name"
                                                                value="">
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer"
                                            style="background-color: #e4e4e4;color: #fff;text-align:center">
    <button type="button" class="btn c-btn" data-dismiss="modal"
                                                onclick="ajaxAddSpecialRequest();"> <i class="far fa-save"></i> Add</button>
    <button type="button" class="btn c-btn" data-dismiss="modal"> <i
                                                    class="far fa-window-close"></i> Close</button>
  </div>
</div>
</div>
</div>

<!--Add Items Remarks Modal Popups Ends-->

<div class="col-md-12 p-0 ">
  <div class="">
    <button type="button" class="btn tooltiptable" data-toggle="modal"
                                        data-target="#tableModal">
    <div class="box-header with-border paxbox" style="display:hnone;">
    <!--<h5 class="box-title">Main Group </h5>-->
    
    <?php


if($_REQUEST['doc_type']==''){
		$doc_type_bill=21;
		$doc_type_kot=22;
	}else{ //KOT NC
		$doc_type_bill=23;
		$doc_type_kot=24;
	}
	
	 $sql = "SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE   `doc_type`='22' ";
	 $resToPrint = mysqli_query($connNew,$sql);
 	 $numRows =  mysqli_num_rows($sql);
	   $rowdoc =  mysqli_fetch_object($resToPrint);
		 $idDocConfigDetail= $rowdoc->id;
	
	$doc_detail = "SELECT * FROM `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE `id_mst_doc_type_config` = '".$idDocConfigDetail."' ";
	$resToPrint1= mysqli_query($connNew,$doc_detail);
	 $rowdoc1 =  mysqli_fetch_object($resToPrint1);
		 $idsubsection = $rowdoc1->id_subsection;

?>
    <label for="name" title="Table"><i class="fas fa-chair"></i> : <span
                                                    id="ViewSelectedTable1" style="color:#FF0000"></span></label>
    <input type="hidden" name="doc_type_bill" id="doc_type_bill"
                                                value="<?php echo $doc_type_bill; ?>" />
    <input type="hidden" name="doc_type_kot" id="doc_type_kot"
                                                value="<?php echo $doc_type_kot; ?>" />
    <input type="hidden" name="id_subsection" id="id_subsection"
                                                value="<?php echo $idsubsection; ?>" />
    <input type="hidden" name="id_attribute_table" id="id_attribute_table"
                                                value="" />
    <label for="name" title="No of Pax"> <i class="fa fa-users"></i> : <span
                                                    id="ViewSelectedPax1" style="color:#FF0000"></span></label>
    <input type="hidden" name="pax" id="pax" value="" />
    <label for="name" title="Steward Name"><i class="fa-solid fa-person"></i>: <span id="ViewSteward1" style="color:#FF0000"></span></label>
    <input type="hidden" name="id_attribute_steward" id="id_attribute_steward"
                                                value="" />
    <input type="hidden" name="attribute_steward_name"
                                                id="attribute_steward_name" value="" />
    <?php if($enable_nationality=='1'){?>
    <label for="name" title="Nationality"><i class="fa-solid fa-flag"></i>: <span id="ViewNationality1" style="color:#FF0000"></span></label>
    <input type="hidden" name="nationality" id="nationality" value="" />
    <?php } ?>
  </div>
  <!--box-heaer ends-->
  
  </button>
  
  <!--modal button ends--> 
</div>
<?php 
$id_item_type=selectColumn(TBL_ATTRIBUTES,'id','WHERE table_name="items_type" AND field_value="Menu" ');
 $SqlItemList = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' AND id_mst_attributes_item_type='".$id_item_type."' and  status='1' "); 

					 $ArrayMainGroup=array();
					 $ArraySubGroup=array();
					 $ArrayItemList=array();
					  while($ItemRow = $db->fetch_object2($SqlItemList)){ 
					  
					 if($ItemRow->id_mst_attributes_item_type=='16'){  //display_order
					  $groupName = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$ItemRow->id_mst_attributes_group_main."'");
					 
					  $ArrayMainGroup[$ItemRow->id_mst_attributes_group_main]['id_mst_attributes_group_main']=$ItemRow->id_mst_attributes_group_main;
					  $ArrayMainGroup[$ItemRow->id_mst_attributes_group_main]['groupName']=$groupName;
					  //$ArrayMainGroup['MainGroup'][$ItemRow->id_mst_attributes_group_main]['display_order']=$display_order;
					  
					  $subgroupName = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$ItemRow->id_mst_attributes_group_sub."'");
					  $display_order = selectColumn(TBL_ATTRIBUTES,'display_order'," WHERE `id` = '".$ItemRow->id_mst_attributes_group_sub."'");
					  $ArraySubGroup[$ItemRow->id_mst_attributes_group_sub]['id_mst_attributes_group_sub']=$ItemRow->id_mst_attributes_group_sub;
					  $ArraySubGroup[$ItemRow->id_mst_attributes_group_sub]['subgroupName']=strtolower($subgroupName);
					  //$ArraySubGroup['SubGroup'][$ItemRow->id_mst_attributes_group_sub]['display_order']=$display_order;
					  
					   }
					  
					
					  $ArrayItemList['ItemList'][$ItemRow->id]['id_item']=$ItemRow->id;
					  $ArrayItemList['ItemList'][$ItemRow->id]['itemName']=$ItemRow->name;
							$itemNameSelectSQL = "SELECT * FROM `".TBL_INV_ITEMS_DETAILS."` WHERE id_item='".$ItemRow->id."' and enabled='1' ";
							$resitemName=mysqli_query($connNew,$itemNameSelectSQL); 
							$itemNameNumRows = mysqli_num_rows($resitemName);
							if($itemNameNumRows>0){
					 $ArrayItemList['ItemList'][$ItemRow->id]['subitem']=1;								
							}else{
					 $ArrayItemList['ItemList'][$ItemRow->id]['subitem']=0;
								}
					  }
					  
	//debugData($ArrayMainGroup);				  
//debugData($ArraySubGroup);	  

//debugData($ArraySubGroup);
	  

			  
$key_values = array_column($ArrayMainGroup, 'groupName'); 
array_multisort($key_values, SORT_ASC, $ArrayMainGroup);
//debugData($ArraySubGroup);

					  
$key_values = array_column($ArraySubGroup, 'subgroupName'); 
array_multisort($key_values, SORT_ASC, $ArraySubGroup);
//debugData($ArraySubGroup);
		  
	?>
<div class="hr-box m-0">
  <div class="grouptitle maintext">Main </div>
</div>
<div class="form-group mb-0 maingroupbox">
  <div class="input-group" id="MainMenuID"> 
    
    <!--strat of owl slider-->
    <div class="main col-md-12">
      <div class="owl-carousel" id="myMainGroupCarousel">
        <input type="hidden" name="id_maingroup" id="id_maingroup" value="">
        <input type="hidden" name="id_subgroup" id="id_subgroup" value="">
        <?php 
 
 
		         echo '  <input name="selectmaingroup" id="selectmaingroup_'.$resultCat->id.'" type="button" class="mainmenu_btn activeset" value="All"  onclick="getsubgrouplist(this.id,\''.$UniqueCodeGen.'\');" style="margin-bottom:5px;">';

		  

								  
								  
	      foreach($ArrayMainGroup  as $MainGroupList){
			 echo '<input name="selectmaingroup" id="selectmaingroup_'.$MainGroupList['id_mst_attributes_group_main'].'" type="button" class="mainmenu_btn"  title="'.ucfirst($MainGroupList['groupName']).'" value="'.ucfirst($MainGroupList['groupName']).'"  onclick="getsubgrouplist(this.id,\''.$UniqueCodeGen.'\');" style="margin-bottom:5px;">';


}
		?>
        
        <!--owl end div start --> 
        
      </div>
    </div>
    
    <!--owl ends div--> 
  </div>
</div>
</div>
<div id="listsubgroup">
  <div class="col-md-12  p-0">
    <div class="hr-box">
      <hr class="m-0">
      <div class="grouptitle subtext">Sub </div>
    </div>
    <div class="form-group mb-0">
      <div class="box-body main2-boxbody table-responsive"
                                            style="padding-top: 10px;padding-left: 1px;padding-right: 5px;">
        <table id="myTableFirst"
                                                class="table table-fixedsubmenu table-striped table-bordered dataTable no-footer"
                                                cellspacing="0">
          <thead style="display:none;">
            <tr style="height:10px;">
              <th colspan="6" style="padding: 4px 10px;font-weight:100;"> Sub
                Group </th>
            </tr>
          </thead>
          <tbody class="main2 col-md-12 owl-carousel" id="mySubGroupCarousel">
            <?php

               

        foreach($ArraySubGroup  as $SubGroupList){
				  
		 echo $subGroup ='<tr><td style="padding:0px;" ><input name="selectitemlist" id="selectitemlist_'.$SubGroupList['id_mst_attributes_group_sub'].'" type="button" class="btn btn-success mainmenu_btn" value="'.strtoupper($SubGroupList['subgroupName']).'" title="'.ucfirst($SubGroupList['subgroupName']).'" onclick="getItemlist(this.id,\''.$UniqueCodeGen.'\');" style="margin-bottom:5px;padding: 5px 10px;" ></td></tr>';


}
		
		
		
		?>
            
            <!--end of slider-->
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-12 p-0" id="listitemName" style="">
    <div class="form-group">
      <div class="box-body table-responsive" style="">
        <table id="myTableSecond"
                                                class="table table-fixeditem table-striped table-bordered dataTable no-footer"
                                                cellspacing="0">
          <thead class="searchmenu">
            <tr>
              <th class="input-box" title="Search Menu"> <input type="text" name="keywordsearch" id="keywordsearch"
                                                                placeholder="Search Menu"
                                                                onKeyUp="keysearch(this.value,'<?php echo $UniqueCodeGen; ?>')">
                <span class="icon"> <i class="fas fa-search"></i> </span> <i class="fas fa-close close-icon"></i> </th>
            </tr>
          </thead>
          <tbody id="SearchResult">
            <?php 
                foreach($ArrayItemList['ItemList']  as $itemList){
                
                
                
                if($itemList['subitem']=='1'){
                ?>
          <td style=""><a name="addItemList"
                                                            id="<?php echo $itemList['id_item'];?>"
                                                            class="btn mainmenu_btn btn-success"
                                                            value="<?php echo ucfirst($itemList['itemName']);?>"
                                                            title="<?php echo ucfirst($itemList['itemName']);?>"
                                                            onclick="selectsubitem(this.id,'<?php echo $UniqueCodeGen; ?>');"><?php echo ucfirst($itemList['itemName']);?></a></td>
            <?php }else{ ?>
            <td style=""><a name="addItemList"
                                                            id="addItemList_<?php echo $itemList['id_item'];?>"
                                                            class="btn mainmenu_btn btn-success"
                                                            value="<?php echo ucfirst($itemList['itemName']);?>"
                                                            title="<?php echo ucfirst($itemList['itemName']);?>"
                                                            onclick="AddgetItemlist(this.id,'<?php echo $UniqueCodeGen; ?>');"><?php echo ucfirst($itemList['itemName']);?></a></td>
            <?php } ?>
            <?php 
                }
                ?>
              </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="ordersdiv">
  <div class="prebtn" style="    margin-left: 16px;">
    <div onclick="myFunction()" class="btn btn-block  btn-sm" id="HideOpen" style=""> Current Order</div>
    <div onclick="LoadPreviousOrder();myFunction2()" class="btn btn-block  btn-sm" id="HideOpen2"
                                        style="display:none;"> Previous Order</div>
  </div>
  <div class="col-md-12" id="ViewPreviousOrder"> 
    <!--  <div class="box-header with-border" style="padding-bottom:10px; padding-top:0px;">
            <--<h3 class="box-title">Previous Order </h3>--
            
          </div>-->
    <div class="form-group" style="margin-bottom: 1px;">
      <div class="box-body table-responsive"
                                            style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">
        <table id="myTableOrder1"
                                                class="table table-striped table-bordered dataTable no-footer"
                                                cellspacing="0">
          <thead>
            <tr>
              <th width="1%">#</th>
              <th>Items Name</th>
              <th>Qty</th>
              <th>Price</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-12" id="GetItemListView"> 
    
    
    <!--<div class="box-header with-border" style="padding: 2px;
    /* padding-top: 3px; */
    background-color: #e5e2e2;
    text-align: center;
    margin-top: 4px;">

        <--  <h3 class="box-title">Current Order </h3>

        </div>--end of box-header-->
    <div class="form-group" style="margin-bottom: 1px;">
      <div class="box-body table-responsive"
                                            style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">
        <table id="myTableOrder"
                                                class="table table-striped table-bordered dataTable no-footer"
                                                cellspacing="0">
          <thead>
            <tr>
              <th width="1%">#</th>
              <th>Items Name</th>
              <th width="1%" class="qnty">Qty</th>
              <th>Price</th>
              <th>Amount</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
      <!--end of box-body--> 
      
    </div>
    <!--end of form-group--> 
    
  </div>
  <!--end of col --> 
  
</div>

<!-- /.end of ordersdiv -->

</div>
</div>

<!-- /.col --> 

<!-- /.row -->

</div>
</div>

<!-- /.box-body -->

</form>
</div>
<div class="row"> 
  
  <!-- /.col --> 
  
</div>

<!-- /.row -->

</section>

<!-- /.content -->

</div>

<!--cancel pop start-->

<div class="modal fade" id="adminsOfferform" tabindex="-1" role="dialog" data-backdrop="static"
    aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="shiftTablepopupform" name="shiftTablepopupform" enctype="multipart/form-data">
      <input type="hidden" name="ids_purch" id="ids_purch" class="form-control">
      <input type="hidden" name="id_table_selected" id='id_table_selected' value="">
      <input type="hidden" name="id_table_shift" id='id_table_shift' value="">
      <div id="LoadShiftTables"></div>
    </form>
  </div>
</div>
<!-- Modal ends --> 

<!--Guest Modal Starts--> 

<!--Guest Modal Ends-->

<style>
/* Style the buttons */
.mt-10 {
    margin-top: 10px;
}

#tableModal i {
    font-size: 18px;
}

#tableModal th,
#tableModal td {
    padding: 0;
}

.paxbox label {
    margin: 0px auto;
}

.paxbox2 {
    width: 228px;
}

.tablegroupbtn,
.tablenationalitybtn {

    border: none;

    outline: none;

    padding: 11px;

    background-color: #00a65a;

    border-color: #008d4c;

    color: #fff;

    cursor: pointer;

    font-size: 12px;

    border-radius: 3px;

    -webkit-box-shadow: none;

    box-shadow: none;

    border: 1px solid transparent;

    border-top-color: transparent;

    border-right-color: transparent;

    border-bottom-color: transparent;

    border-left-color: transparent;

}



/* Style the active class, and buttons on mouse-over */

.activetablegroup,
.tablegroupbtn:hover,
.activetablenaitonality,
.tablenationalitybtn:hover,
    {

    background-color: #d73925;
    ;

    color: #fff;

}





.tablegroupbtn,
.tablenationalitybtn {

    border: none;

    outline: none;

    padding: 11px;

    background-color: #00a65a;

    border-color: #008d4c;

    color: #fff;

    cursor: pointer;

    font-size: 12px;

    border-radius: 3px;

    -webkit-box-shadow: none;

    box-shadow: none;

    border: 1px solid transparent;

    border-top-color: transparent;

    border-right-color: transparent;

    border-bottom-color: transparent;

    border-left-color: transparent;

}

.tablegroupbtn {
    padding: 13px;
}

/* Style the active class, and buttons on mouse-over */

.activetablegroup,
.tablegroupbtn:hover,
.activetablenationality,
.tablenationalitybtn:hover {

    background-color: #d73925;
    ;

    color: #fff;

}





.tableviewbtn {

    border: none;

    outline: none;

    padding: 3px 8px;

    background-color: #00a65a;

    border-color: #008d4c;

    color: #fff;

    cursor: pointer;

    font-size: 12px;

    border-radius: 3px;

    -webkit-box-shadow: none;

    box-shadow: none;

    border: 1px solid transparent;

    border-top-color: transparent;

    border-right-color: transparent;

    border-bottom-color: transparent;

    border-left-color: transparent;
    box-shadow: 0px 0px 3px 0px black;
    margin: 1px;

}



.tableviewBlockedbtn {

    border: none;

    outline: none;

    padding: 3px 8px;

    background-color: #f54242;

    border-color: #d73925;

    color: #fff;

    cursor: pointer;

    font-size: 12px;

    border-radius: 3px;

    -webkit-box-shadow: none;

    box-shadow: none;

    border: 1px solid transparent;

    border-top-color: transparent;

    border-right-color: transparent;

    border-bottom-color: transparent;

    border-left-color: transparent;
    box-shadow: 0px 0px 3px 0px black;
    margin: 1px;

}



/* Style the active class, and buttons on mouse-over */

.activetableviewbtn,
.tableviewbtn:hover {

    background-color: #d73925;
    ;

    color: #fff;

}



/* Style the active class, and buttons on mouse-over */

.activetableviewbtn,
.tableviewBlockedbtn:hover {

    background-color: #d73925;
    ;

    color: #fff;

}



.noofpaxbtn {

    border: none;

    outline: none;

    padding: 3px 8px;

    background-color: #fff;

    border-color: #008d4c;

    color: #000;

    cursor: pointer;

    font-size: 12px;

    border-radius: 3px;

    -webkit-box-shadow: none;

    box-shadow: none;

    border: 1px solid transparent;

    border-top-color: transparent;

    border-right-color: transparent;

    border-bottom-color: transparent;

    border-left-color: transparent;

}



/* Style the active class, and buttons on mouse-over */

.activenoofpaxbtn,
#MyNumOfPax .noofpaxbtn:hover {

    background-color: #d73925;
    ;

    color: #fff;

}

.activenoofpaxbtn {

    background-color: #d73925 !important;

    color: #fff;

}

.activestewardbtn {


    background: #17b3be;
    box-shadow: 0px 0px 12px 1px black;
    color: #fff;

}
</style>
<style>
.mainmenu_btn {
    border: none;
    outline: none;
    background-color: #2471a3;
    /*#00db74;*/
    color: #fff;
    border-color: #008d4c;
    cursor: pointer;
    border-radius: 3px;
    -webkit-box-shadow: none;
    box-shadow: none;
    border: 1px solid transparent;
    border-color: transparent;
    font-weight: 500 !important;
    height: 70px;
    width: 70;
    display: flex;
    justify-content: center;
    align-items: center;

    padding: 3px !important;
    white-space: pre-wrap !important;
    font-size: 12px !important;
    margin-bottom: 0 !important;
    display: flex;
    justify-content: center;
    align-items: center;

}

/*
.table-fixeditem tbody td:hover,.table-fixeditem tbody a:hover{
 width:100px;
 height: 80px;

box-shadow: 2px 2px 2px #dedede;
transition: .6s;
transform :scale(1);


}
*/


.table-fixeditem tbody td a {
    width: 83px;
}

/* Style the active class, and buttons on mouse-over */
.activeset,
.mainmenu_btn:hover {

    background-color: #00a65a;

    color: white;

}

#myTableSecond .mainmenu_btn {
    background: #1874d3 !important;
}

.table-fixeditem tbody td {
    height: 70px;
    width: 83px;
    white-space: pre-wrap;
    margin-bottom: 0;
    padding: 0 !important;
}
</style>
<style>
input.activeMenuItem {

    background-color: #F00;

    font-weight: bold;

}

.btns:focus {

    background: red;

}

.table-fixed thead {

    width: 97%;

}



.table-fixed thead,
.table-fixed tbody,
.table-fixed tr,
.table-fixed td,
.table-fixed th {

    display: inline;

}

.table-fixed tbody td,
.table-fixed thead>tr>th {

    float: left;

    border-bottom-width: 0;

}



.table-fixedsteward thead {

    width: 97%;

}

.table-fixedsteward tbody {


    width: 100%;

}

.table-fixedsteward thead,
.table-fixedsteward tbody,
.table-fixedsteward tr,
.table-fixedsteward td,
.table-fixedsteward th {

    display: flex;

}

.table-fixedsteward tbody td,
.table-fixedsteward thead>tr>th {

    /*float: left;*/

    border-bottom-width: 0;

}



.table-fixedTableGroup thead {

    width: 97%;

}

.table-fixedTableGroup tbody {

    height: 194px;

    overflow-y: auto;

    width: 100%;

}

.table-fixedTableGroup thead,
.table-fixedTableGroup tbody,
.table-fixedTableGroup tr,
.table-fixedTableGroup td,
.table-fixedTableGroup th {

    display: block;

}

.table-fixedTableGroup tbody td,
.table-fixedTableGroup thead>tr>th {

    /*float: left;*/

    border-bottom-width: 0;

}







.table-fixedsubmenu thead {

    width: 97%;

}

.table-fixedsubmenu tbody {

    max-height: 136px;


    width: 100%;

}

.table-fixeditem tbody {

    max-height: 359px;

    overflow-y: auto;

    width: 100%;

}



.table-fixedsubmenu thead,
.table-fixedsubmenu tbody,
.table-fixedsubmenu tr,
.table-fixedsubmenu td,
.table-fixedsubmenu th,
.table-fixeditem thead,
.table-fixeditem tbody,
.table-fixeditem tr,
.table-fixeditem td,
.table-fixeditem th {

    display: flex;
    flex-wrap: wrap;


}

.table-fixedsubmenu tbody td,
.table-fixedsubmenu thead>tr>th,
.table-fixeditem tbody td,
.table-fixeditem thead>tr>th {

    float: left;

    border-bottom-width: 0;

}

.table-fixedsubmenu td input {
    padding: 3px !important;
    font-size: 12px;
    margin-right: 1px;
}

#hideGroup {
    display: none;
}

.tablefixeditem tbody td {
        {
        margin-bottom: 0 !important;
        height: 70px;
        width: 70px;
        overflow: hidden;
    }

    .table-fixeditem tbody td a,
    .table-fixeditem tbody td input {
        padding: 7px !important;
        white-space: pre-wrap !important;
        font-size: 12px !important;
        margin-bottom: 0 !important;
        display: flex;
        justify-content: center;
        align-items: center;
    }


    @media only screen and (min-width:776px) and (max-width:991px) {

        .n-btn {
            padding: 10px;
        }

        #id_table {
            margin-top: 80px;
        }


    }

    @media only screen and (max-width:776px) {

        .tablegroupbtn,
        .tablenationalitybtn {
            font-size: 18px;
        }

        .table-responsive {
            border: none !important;
        }

        #listsubgroup {
            margin-top: 10px;
        }

        .paxbox2 {
            width: 323px;
        }

        .table-fixedsubmenu tbody {
            max-height: 48px;


        }

        .table-fixeditem tbody {
            max-height: 362px;

        }

        .table-fixedTableGroup tbody {

            height: 75px;
        }

        #MyNumOfPax .table-fixed tbody,
        .table-fixedsteward tbody {
            height: 132px;
        }

        #MyNumOfPax .table-fixed tbody {
            height: 90px;
        }

        .table-fixedsteward tbody {
            display: flex;
        }

        #id_table .table-responsive {
            padding: 0px;
        }

        #myTableTest {
            margin: 0;
        }


        table.dataTable tbody th,
        table.dataTable tbody td,
        .table>tbody>tr>td,
        .mainmenu_btn,
        #SearchResult input {

            font-size: 12px;
        }

        .noofpaxbtn,
        .tablegroupbtn {
            font-size: 14px !important;

        }

        #myTableOrder tbody tr td:nth-child(3) {
            display: flex;
        }

        #listsubgroup td {
            padding: 1px !important;
        }

        #myTableSecond tbody {
            max-height: 264px;
        }

        .table-fixeditem tbody td a,
        #SearchResult td a,
        #SearchResult td input,
        .table-fixedsubmenu td input {
            padding: 3px !important;
            font-size: 12px;
            display: flex;
            justify-content: center;
            align-items: center;

        }

        .table-fixedTableGroup tbody tr td {
            padding: 12px 12px 0px 0px !important;

        }

    }
</style>
<div class="row" id="nchide">
  <div class="col-md-12"> 
    <!--cancel pop start-->
    <div id="ncremarkspop" class="well p-4" style="margin:0 15px;display: none;">
      <form id="Formkotremarks" autocomplete="off">
        <input type="hidden" id="pos_purch_idpop" name="pos_purch_idpop" value="">
        <input type="hidden" id="UniqueCodeGenpos" name="UniqueCodeGenpos" value="">
        <div id="kot_mdoc_no"> </div>
        <div class="form-group">
          <label for="title">Non Chargeable Remarks</label>
          <textarea rows="4" cols="50" type="text" class="form-control input-sm" placeholder="Enter Remark"
                        id="get_remark" name="get_remark" value="" data-parsley-required></textarea>
        </div>
        <div class="form-group">
          <label for="btn">&nbsp;<br>
            <br>
          </label>
          <button class="btn c-btn" onclick="ajaxupdateNonChargeableRemarks();" type="button"><i
                            class="far fa-save"></i> Update</button>
          <button class="ncremarkspop_close btn c-btn"><i class="far fa-window-close"></i> Close</button>
        </div>
      </form>
    </div>
    <!--cancel pop ends--> 
    
    <!--add guest starts--> 
    
    <!--cancel pop ends--> 
  </div>
</div>

<!-- keypad modal starts -->



<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

  <div class="modal-dialog modal-dialog-centered custom-modal-dialog" style="max-width : 30em!important;">
    <div class="modal-content" style="border-radius : 1%!important;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="margin-right : 10px;">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Enter Password</h4>
      </div>
      <div class="modal-body">
        <div style="margin-bottom : 2rem;">
          <div class="box-body table-responsive"
                    style="padding-top: 1px; padding-left: 1px; padding-right: 5px;">
            <table id="myTableOrder1 modalSelectBtn" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" style="font-size: 1.5rem;">
              <thead>
                <tr>
                  <th class="text-center" style="font-size: 1.5rem; background: #f56616!important; color: #fff!important; font-weight : 400;">Table</th>
                  <th class="text-center" style="font-size: 1.5rem; background: #f56616!important; color: #fff!important; font-weight : 400;">Pax</th>
                  <th class="text-center" style="font-size: 1.5rem; background: #f56616!important; color: #fff!important; font-weight : 400;">Steward</th>
                  <th class="text-center" style="font-size: 1.5rem; background: #f56616!important; color: #fff!important; font-weight : 400;">Total Items</th>
                </tr>
              </thead>
              <input type="hidden" name="enable_pass_id" id="enable_pass_id" value="">
              <input type="hidden" name="enable_pass_UniqueCode" id="enable_pass_UniqueCode" value="">
              <tbody>
                <tr class="text-center">
                  <td class="bg-secondary" style="font-size: 1.5rem; font-weight: 700; background: #faf9f9; color :  #f56616!important;" id="passcode_table11"></td>
                  <td class="bg-secondary" style="font-size: 1.5rem; font-weight: 700; background: #faf9f9; color :  #f56616!important;" id="passcode_pax"></td>
                  <td class="bg-secondary" style="font-size: 1.5rem; font-weight: 700; background: #faf9f9; color :  #f56616!important;" id="passcode_steward"></td>
                  <td class="bg-secondary" style="font-size: 1.5rem; font-weight: 700; background: #faf9f9; color :  #f56616!important;" id="passcode_Total_item"></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div><?php // id="passwordInput" ?>
        <input type="password" name="enable_steward_passcode_value_new" id="passwordInput" onkeyup="ValidatePasscodeNew(this.value);"  readonly autocomplete="off"><br>
        <span id="Msgpasscode_value"
                                                                        style="margin:25px;"></span>
        <div class="keypad">
          <button type="button" onclick="appendNumber('1')" style="box-shadow: rgba(17, 17, 26, 0.1) 0px 1px 0px;">1</button>
          <button type="button" onclick="appendNumber('2')" style="box-shadow: rgba(17, 17, 26, 0.1) 0px 1px 0px;">2</button>
          <button type="button" onclick="appendNumber('3')" style="box-shadow: rgba(17, 17, 26, 0.1) 0px 1px 0px;">3</button>
          <br>
          <button type="button" onclick="appendNumber('4')"  style="box-shadow: rgba(17, 17, 26, 0.1) 0px 1px 0px;">4</button>
          <button type="button" onclick="appendNumber('5')" style="box-shadow: rgba(17, 17, 26, 0.1) 0px 1px 0px;">5</button>
          <button type="button" onclick="appendNumber('6')" style="box-shadow: rgba(17, 17, 26, 0.1) 0px 1px 0px;">6</button>
          <br>
          <button type="button" onclick="appendNumber('7')" style="box-shadow: rgba(17, 17, 26, 0.1) 0px 1px 0px;">7</button>
          <button type="button" onclick="appendNumber('8')" style="box-shadow: rgba(17, 17, 26, 0.1) 0px 1px 0px;">8</button>
          <button type="button" onclick="appendNumber('9')" style="box-shadow: rgba(17, 17, 26, 0.1) 0px 1px 0px;">9</button>
          <br>
          <button type="button" onclick="clearInput()">C</button>
          <button type="button" onclick="appendNumber('0')">0</button>
          <button type="button" onclick="removeLast()">x</button>
        </div>
        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#changePinModal" style="padding: 7px 15px; margin-top  : 15px;"> <i class="fa-regular fa-pen-to-square"></i>&nbsp;&nbsp;Change Pin </button>
      </div>
      <!-- <div class="modal-footer">
            
            <button type="button" class="btn btn-primary" data-dismiss="modal" style="padding: 7px 15px;">Save</button>
            <button type="button" class="btn btn-default" data-dismiss="modal" style="padding: 7px 15px;">Close</button>
        </div> --> 
    </div>
  </div>
</div>
<div class="modal fade" id="changePinModal" tabindex="-1" role="dialog" aria-labelledby="changePinModalLabel"
aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width : 22em; margin-top : 10%!important;">
    <div class="modal-content" style="border-radius : 2%!important;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="margin-right : 8px!important;">&times;</button>
        <h5 class="modal-title" id="changePinModalLabel"><strong>Change Pin</strong></h5>
      </div>
      <div class="modal-body">
       <form>
          <div class="form-group">
            <label for="oldPin">Old Pin:</label>
            <input type="password" class="form-control" id="oldPin" name="oldPin" placeholder="Enter old pin" required onkeyup="CheckOldPasscode(this.value);">
            <span id="MsgoldPinpasscode_value"
                                                                        style="margin:25px;"></span></div>
          <div class="form-group">
            <label for="newPin">New Pin:</label>
            <input type="password" class="form-control" id="newPin" name="newPin" minlength="3" placeholder="Enter new pin" required>
          </div>
          <button type="button" class="btn btn-primary" onclick="saveNewPin()">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- keypad modal ends --> 
<?php include_once("../includes/footer.php")?>
<script>
let passwordInput = document.getElementById('passwordInput');

function appendNumber(number) {
    passwordInput.value += number;
    ValidatePasscodeNew(passwordInput.value);
}

function clearInput() {
    passwordInput.value = '';
}

function removeLast() {
    passwordInput.value = passwordInput.value.slice(0, -1);
}

function saveNewPin() {
    // You can implement the logic to save the new pin here
    
    var oldPin=$("#oldPin").val();
    var newPin=$("#newPin").val();
    var id_attribute_steward=$("#id_attribute_steward").val();

    
    if(oldPin==''){
		alert('Please Enter Old Pin');
		exit;
	  }
    var regex = /^\s*$/;
    if (regex.test(newPin)) {
            // The string contains only spaces or is empty
            alert("Please enter a non-empty string");
            exit;
        } else if(newPin==''){
		alert('Please Enter New Pin');
    exit;
	  }

    if(id_attribute_steward==''){
		alert('Please select Steward');
		exit;
	  }
    $.ajax({
        type: "POST",
        url: 'ajax/ajaxUpdateNewPasscode.php',
        data: 'newPin=' + newPin + '&id_attribute_steward=' + id_attribute_steward,
        success: function(result) {

            if (result == 1) {
              alert('New Pin Saved!');
              $('#changePinModal').modal('hide');
               


            } else {

                
               
            }
        },

    });
    //alert('New Pin Saved!');
    //$('#changePinModal').modal('hide');
}
</script>
<script>
function addFormtest(specialRequestUniqueCode, UniqueCodeGen) {

    var special_request_name = $("#itemRemarksLabel" + specialRequestUniqueCode).html();
    $('#UniqueCodeGen').val(UniqueCodeGen);
    $('#specialRequestUniqueCode').val(specialRequestUniqueCode);
    $('#special_request_name').val(special_request_name);



}

function ajaxAddSpecialRequest() {

    var specialRequestUniqueCode = $("#specialRequestUniqueCode").val();
    var special_request_name = $("#special_request_name").val();
    var UniqueCodeGen = $("#UniqueCodeGen").val();

    $("#itemRemarksLabel" + specialRequestUniqueCode).html(special_request_name);
    $("#itemRemarksLabelInput" + specialRequestUniqueCode).html("<input type='hidden' class='form-control' value='" +
        special_request_name + "'  name='item_special_request|'" + specialRequestUniqueCode +
        "  id='item_special_request|'" + specialRequestUniqueCode + ">");
    $.ajax({
        type: "POST",
        url: 'ajax/ajaxUpdateSessionSpecialRequest.php',
        data: 'specialRequestUniqueCode=' + specialRequestUniqueCode + '&special_request_name=' +
            special_request_name + '&UniqueCodeGen=' + UniqueCodeGen,
        success: function(result) {
            $("#itemModalRemarks").modal('hide');


        }
    });

}

function shiftTable(id_table_shift, shift_table_value) {

    $("#id_table_shift").val(id_table_shift);
    $("#shift_table_value").html('Shift Table To ' + shift_table_value);
}

function updateShiftTable() {

    var form = $("#shiftTablepopupform");
    if (form.parsley().validate()) {
        $('.loading').show();
        $.ajax({
            type: "POST",
            url: 'ajax/ajaxUpdateShiftTable.php',
            data: form.serialize(),
            success: function(result) {

                if (result == 1) {
                    alert('Please select Table.');
                    return false;
                } else {
                    alert(' Table changed Successfully');
                    window.location.reload();
                }
            },
            complete: function() {
                $('.loading').hide();
            }
        });
        return false;
    }


    //	id_table_shift id_table_selected ids_purch






}

function AddgetItemlist2(addItemList, subid, UniqueCodeGen) {

    //	alert(UniqueCodeGen);
    resultArrayItemlistId = addItemList.split('_');
    $('#' + addItemList).not(this).removeClass();
    $('#' + addItemList).toggleClass('btn btn-success mainmenu_btn');
    $.ajax({
        type: "POST",
        url: 'ajax/ajaxGetSubGroup.php',
        data: 'selectSubgroup=' + resultArrayItemlistId[1] + '&listsubgroup=3' + '&subid=' + subid +
            '&UniqueCodeGen=' + UniqueCodeGen,
        success: function(result) {
            $("#GetItemListView").html(result);
            $('#ItemModal').modal('hide');
        }
    });

    /*	resultArrayItemlistId = addItemList.split('_');
    		$('#'+addItemList).not(this).removeClass();
    		$('#'+addItemList).toggleClass('btn btn-success');
    		$.ajax({
    		   type: "POST",
    		   url: 'ajax/ajaxGetSubGroup.php',
    		   data: 'selectSubgroup='+resultArrayItemlistId[1]+'&listsubgroup=3'+'&subid='+subid, 
    		   success: function (result) {	
    				$( "#GetItemListView" ).html(result);
    				$('#ItemModal').modal('hide');
    			}
    		});  */



}



//header five  starts

/*
var headerFive = document.getElementById("MyNationalitySelect");

var btnsFive = headerFive.getElementsByClassName("tablenationalitybtn");

for (var j = 0; j < btnsFive.length; j++) {

  btnsFive[j].addEventListener("click", function() {

  var currentFive = document.getElementsByClassName("activetablenationality");

  if (currentFive.length > 0) { 

    currentFive[0].csslassName = currentFive[0].className.replace(" activetablenationality", "");

  }


  this.className += " activetablenationality";

  });
 }

 */

//header five ends


var headerFour = document.getElementById("MyStewardSelect");

var btnsFour = headerFour.getElementsByClassName("noofpaxbtn");

for (var j = 0; j < btnsFour.length; j++) {

    btnsFour[j].addEventListener("click", function() {

        var currentFour = document.getElementsByClassName("activestewardbtn");

        if (currentFour.length > 0) {

            currentFour[0].className = currentFour[0].className.replace(" activestewardbtn", "");

        }

        this.className += " activestewardbtn";

    });

}





var headerThree = document.getElementById("MyNumOfPax");



var btnsThree = headerThree.getElementsByClassName("noofpaxbtn");

for (var j = 0; j < btnsThree.length; j++) {

    btnsThree[j].addEventListener("click", function() {



        var currentThree = document.getElementsByClassName("activenoofpaxbtn");



        if (currentThree.length > 0) {

            currentThree[0].className = currentThree[0].className.replace(" activenoofpaxbtn", "");

        }

        this.className += " activenoofpaxbtn";

    });

}





var headerTwo = document.getElementById("TableListview");

var btnsTwo = headerTwo.getElementsByClassName("tableviewbtn");

for (var j = 0; j < btnsTwo.length; j++) {

    btnsTwo[j].addEventListener("click", function() {

        var currentTwo = document.getElementsByClassName("activetableviewbtn");

        if (currentTwo.length > 0) {

            currentTwo[0].className = currentTwo[0].className.replace(" activetableviewbtn", "");

        }

        this.className += " activetableviewbtn";

    });

}





var headerOne = document.getElementById("MyTableGroupID");

var btnsOne = headerOne.getElementsByClassName("tablegroupbtn");

for (var j = 0; j < btnsOne.length; j++) {

    btnsOne[j].addEventListener("click", function() {

        var current1 = document.getElementsByClassName("activetablegroup");

        if (current1.length > 0) {

            current1[0].className = current1[0].className.replace(" activetablegroup", "");

        }

        this.className += " activetablegroup";

    });

}





var header = document.getElementById("MainMenuID");

var btns = header.getElementsByClassName("mainmenu_btn");

for (var i = 0; i < btns.length; i++) {

    btns[i].addEventListener("click", function() {

        var current = document.getElementsByClassName("activeset");

        if (current.length > 0) {

            current[0].className = current[0].className.replace(" activeset", "");

        }

        this.className += " activeset";

    });

}


document.getElementById("ViewPreviousOrder").style.display = "none";

function myFunction2() {

    var x = document.getElementById("ViewPreviousOrder");

    var y = document.getElementById("GetItemListView");

    //var clickButton = document.getElementById('HideOpen');








    x.style.display = "block";
    y.style.display = "none";
    //clickButton.innerHTML = 'Curent Order ';


}




//document.getElementById("ViewPreviousOrder").style.display="none";
function myFunction() {

    var x = document.getElementById("ViewPreviousOrder");

    var y = document.getElementById("GetItemListView");

    //var clickButton = document.getElementById('HideOpen');


    x.style.display = "none";
    y.style.display = "block";
    //clickButton.innerHTML = 'Curent Order ';



}



function TabeleSelect(Selectname)

{
    //alert('Table');
    resultArrayTableID = Selectname.split('_');

    $("#id_attribute_table").val(resultArrayTableID[1]);
    $("#ViewSelectedTable1").html(resultArrayTableID[2]);


    //$( "#id_attribute_table" ).val(resultArrayTableID[1]);
    $("#ViewSelectedTable2").html(resultArrayTableID[2]);
    var id_attribute_shift = $("#id_attribute_shift").val();
    var pax = $("#pax").val();
    // var nationality=$("#id_m").val();
    //alert(nationality);
    var nationality = $("#nationality").val();

    var id_attribute_table = $("#id_attribute_table").val();
    var id_attribute_steward = $("#id_attribute_steward").val();

    var enable_nationality_show = $("#enable_nationality_show").val();
    if (enable_nationality_show == '1') {
        var natcontions = " && nationality!=''";
    } else {
        var natcontions = '';
    }




    if (id_attribute_steward != '' && pax != '' && id_attribute_table != '' + natcontions) {
        //$('#tableModal').modal('hide');
    }

}

function SelectSteward(Steward)

{

    resultArrayStewardID = Steward.split('_');

    $("#id_attribute_steward").val(resultArrayStewardID[0]);
    $("#attribute_steward_name").val(resultArrayStewardID[1]);
    $("#ViewSteward1").html(resultArrayStewardID[1]);
    $("#ViewSteward2").html(resultArrayStewardID[1]);

    var id_attribute_shift = $("#id_attribute_shift").val();
    var pax = $("#pax").val();
    var nationality = $("#nationality").val();
    //alert(nationality);
    $("#ViewNationality1").html(nationality);
    var id_attribute_table = $("#id_attribute_table").val();
    var id_attribute_steward = $("#id_attribute_steward").val();

    var enable_nationality_show = $("#enable_nationality_show").val();
    if (enable_nationality_show == '1') {
        var natcontions = " && nationality!=''";
    } else {
        var natcontions = '';
    }
    //var enable_steward_passcode = $("#enable_steward_passcode").val();
    //var enable_steward_passcode_value = $("#enable_steward_passcode_value").val();
    //var passcode_valid_status = $("#passcode_valid_status").val();


    //alert(nationality);
   
    if (resultArrayStewardID[0] != '' && pax != '' && id_attribute_table != '' + natcontions) {
       /* if (enable_steward_passcode == '1' && passcode_valid_status == '1') {

            $('#tableModal').modal('hide');
        } else if (enable_steward_passcode == '1' && passcode_valid_status == '0') {

        } else {*/

            $('#tableModal').modal('hide');
        //}
        //$('#tableModal').modal('hide');
    }

}



function SelectNoPaxs(Selectpax)

{

    $("#pax").val(Selectpax);

    $("#ViewSelectedPax1").html(Selectpax);
    $("#ViewSelectedPax2").html(Selectpax);

    var id_attribute_shift = $("#id_attribute_shift").val();
    var pax = $("#pax").val();
    var nationality = $("#ViewNationality1").val();
    //alert(nationality);
    var id_attribute_table = $("#id_attribute_table").val();
    var id_attribute_steward = $("#id_attribute_steward").val();

    var enable_nationality_show = $("#enable_nationality_show").val();
    if (enable_nationality_show == '1') {
        var natcontions = " && nationality!=''";
    } else {
        var natcontions = '';
    }

    if (id_attribute_steward != '' && Selectpax != '' && id_attribute_table != '' + natcontions) {
        $('#tableModal').modal('hide');
    }

}



function SelectNationality(Selectnationality)

{

    resultArrayNationalityID = Selectnationality.split('_');


    $("#id_mst_country_lang").val(resultArrayNationalityID[0]);
    $("#nationality").val(resultArrayNationalityID[1]);
    // $( "#nationality" ).html(resultArrayNationalityID[1]);
    //var natID = resultArrayNationalityID[0];
    // for(natID=1;natID<4000;natID++){
    //document.getElementById(natID).style.backgroundColor="green";
    //  }



    //var count =1;
    //if(count==0){}
    //var natBgColor = document.getElementById(Selectnationality);
    //if(natBgColor.style.backgroundColor="red"){
    //natBgColor.style.backgroundColor="red";

    //}

    var pax = $("#pax").val();
    var id_attribute_shift = $("#id_attribute_shift").val();
    var nationality = $("#nationality").val();

    // alert(nationality);
    $("#ViewNationality1").html(nationality);
    var enable_nationality_show = $("#enable_nationality_show").val();

    if (enable_nationality_show == '1') {
        var natcontions = " && nationality[0]!=''";
    } else {
        var natcontions = '';
    }
    var id_attribute_table = $("#id_attribute_table").val();
    var id_attribute_steward = $("#id_attribute_steward").val();
    if (id_attribute_steward != '' && pax != '' && id_attribute_table != '' + natcontions) {
        $('#tableModal').modal('hide');
    }

}

function reset() {
    document.getElementsByClassName('tablenationalitybtn').style.color = "pink";

}


function SelectshiftType(SelectshiftType) {

    resultArrayStewardID = Steward.split('_');

    alert(SelectshiftType);

    //$( "#pax" ).val(SelectshiftType);



}
</script>
<script>
$('#myTableFirst').dataTable({

    "paging": false,

    "info": false,

    "searching": false,



});

$(document).ready(function() {

    $('#myTableOrder').DataTable({

        "paging": false,

        "info": false,

        "searching": false,

        scrollY: 50,

        deferRender: true,

        scroller: true


    });

});



$('#myTableSecond').dataTable({

    "paging": false,

    "info": false,

    "searching": false,

    scrollY: 50,

    deferRender: true,

    scroller: true

});


$('#myTableTableList').dataTable({

    "paging": false,

    "info": false,

    "searching": false,

    scrollY: 200,

    deferRender: true,

    scroller: true

});



function selectsubitem(id, UniqueCodeGen) {
    $('#ItemModal').modal('show');
    $.ajax({
        url: "ajax/ajaxSubItem.php",
        type: 'POST',
        // data: { id : id },
        data: 'id=' + id + '&UniqueCodeGen=' + UniqueCodeGen,
        dataType: "JSON",
        success: function(data) {
            //alert(UniqueCodeGen);
            $('#subitemlist').html(data);
        }
    });
}
</script>
<script>
$(".discountvalue").keyup(function() {
    var $this = $(this);
    $this.val($this.val().replace(/[^\d.]/g, ''));
});
</script>
<script>
function ajaxShiftTable() {

    //$("#cancelled").addClass("bookedby_open");
    $('#cancelpop2').popup({
        transition: 'all 0.3s',
        autoopen: true,
    });
    //$("#pos_purch_id").val(posid);
    //$("#kot_mdoc_no").html(' KOT No: '+mdoc_no);        
}

function openshiftTable(id_table_selected, ids_purch) {

    // $('.modal-titlecheck', 
    //$('#'+fid)).html(txt);

    $('#id_table_shift').val('');
    $('#id_table_selected').val(id_table_selected);
    $('#ids_purch').val(ids_purch);


    var id_document = <?php echo $_SESSION['id_document']; ?>

    $.ajax({
        type: "POST",
        url: 'ajax/ajaxLoadShiftTable.php',
        data: 'id_document=' + id_document,
        success: function(result) {
            $("#LoadShiftTables").html(result);

        },

    });

}


//noofpaxbtn  more button

jQuery(document).ready(function($) {
    $(".paxloadbtn").click(function(e) {
        $(".paxloadmore:hidden").slice(0, 15).fadeIn();
        if ($(".paxloadmore:hidden").length < 1) $(this).fadeOut();
    })
})

function checkShiftTime() {
    var id_attribute_shift = $("#id_attribute_shift").val();
    $.ajax({
        url: "ajax/ajaxCheckShiftTimeItem.php",
        type: 'POST',
        data: 'id_attribute_shift=' + id_attribute_shift,

        success: function(result) {
            //alert(ajaxdata);
            console.log(result);
            data = JSON.parse(result);
            if (data.status == 1) {
                bootbox.confirm({
                    title: "SHIFT ",
                    message: "Shift Timing are mismatching still do you want to Continue ?",
                    buttons: {
                        cancel: {
                            label: '<i class="fa fa-times"></i> Cancel'
                        },
                        confirm: {
                            label: '<i class="fa fa-check"></i> Continue'
                        }
                    },
                    callback: function(result) {
                        if (result == false) {
                            $('#tableModal').modal('hide');
                        }
                    }
                });

                //alert(data.msg);
            } else {
                //alert(data.msg);
            }
            //$('#subitemlist').html(data);
            //alert(data.msg);

        }
    });
}
</script>
<script>
//script for modal
$(window).on('load', function() {
    checkShiftTime();
    $('#tableModal').modal('show');
})



//
let current = document.getElementById('HideOpen');
let previous = document.getElementById('HideOpen2');
current.style.color = "#c75616d1";

current.addEventListener('click', function onClick() {
    current.style.color = "#c75616d1";
    previous.style.color = "#000";

});

previous.addEventListener('click', function onClick() {
    current.style.color = "#000";
    previous.style.color = "#c75616d1";

});
</script>
<script>
//script for adding pos guest



$(".mstcountrylang").click(function() { //alert('1');
    var x = $(this).attr('class').split(' ').pop();
    $("[name=id_mst_country_lang]").removeClass('activetablenationality');
    if (x == 'activetablenationality') {


    } else {

        $(this).addClass('activetablenationality');
        //alert(x);
    }

});
</script>
<script>
//owl starts
var $owl = $('#myMainGroupCarousel');
var owl = $owl.owlCarousel({
    autoplay: false,
    dots: false,
    loop: false,
    autoWidth: true,
    nav: true,
    navText: ["<i class=\"fa fa-chevron-left\"></i>",
        "<i class=\"fa fa-chevron-right\"></i>"
    ],
    // responsiveBaseElement: '.main',
    responsive: {
        0: {
            items: 3,
            slideBy: 3
        },
        400: {
            items: 4,
            slideBy: 4
        },

        505: {
            items: 5,
            slideBy: 5
        },

        575: {
            items: 7,
            slideBy: 7
        },

        769: {
            items: 8,
            slideBy: 8
        },
        992: {
            items: 8,
            slideBy: 8
        },
        1200: {
            items: 10,
            slideBy: 10
        },
        1500: {
            items: 12,
            slideBy: 12
        },
    },
});


//subgroup carousel
var $owl = $('#mySubGroupCarousel');
var owl = $owl.owlCarousel({
    autoplay: false,
    dots: false,
    loop: false,
    autoWidth: true,
    nav: true,

    navText: ["<i class=\"fa fa-chevron-left\"></i>",
        "<i class=\"fa fa-chevron-right\"></i>"
    ],
    //responsiveBaseElement: '.main2',
    responsive: {
        0: {
            items: 3,
            slideBy: 3
        },
        400: {
            items: 4,
            slideBy: 4
        },

        505: {
            items: 5,
            slideBy: 5
        },

        575: {
            items: 7,
            slideBy: 7
        },

        769: {
            items: 8,
            slideBy: 8
        },
        992: {
            items: 8,
            slideBy: 8
        },
        1200: {
            items: 10,
            slideBy: 10
        },
        1500: {
            items: 12,
            slideBy: 12
        },

    },
});


var $owl = $('#mySubGroupCarousel3');
var owl = $owl.owlCarousel({
    autoplay: false,
    dots: false,
    loop: false,
    autoWidth: true,
    nav: true,

    navText: ["<i class=\"fa fa-chevron-left\"></i>",
        "<i class=\"fa fa-chevron-right\"></i>"
    ],
    //responsiveBaseElement: '.main3',
    responsive: {
        0: {
            items: 3,
            slideBy: 3
        },
        400: {
            items: 4,
            slideBy: 4
        },

        505: {
            items: 5,
            slideBy: 5
        },

        575: {
            items: 7,
            slideBy: 7
        },

        769: {
            items: 8,
            slideBy: 8
        },
        992: {
            items: 8,
            slideBy: 8
        },
        1200: {
            items: 10,
            slideBy: 10
        },
        1500: {
            items: 12,
            slideBy: 12
        },
    },
});


/*
$('.sidebar-switcher').click(function(){
  $('body').toggleClass( 'body-open' );
  $('.main').one("webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend", function(event) {
    owl.trigger('refresh.owl.carousel');
  });
});
*/

//menu searchbar 
let inputBox = document.querySelector(".input-box"),
    searchIcon = document.querySelector(".icon"),
    closeIcon = document.querySelector(".close-icon");

searchIcon.addEventListener("click", () => inputBox.classList.add("open"));
closeIcon.addEventListener("click", () => inputBox.classList.remove("open"));


//   inputBox.classList.add("open");

function searchMenu(x) {
    if (x.matches) { // If media query matches
        inputBox.classList.add("open");
    }
}

var x = window.matchMedia("(max-width: 991px)");
searchMenu(x); // Call listener function at run time
x.addListener(searchMenu); // Attach listener function on state changes


function updateShift(id_attribute_shift) {


    $.ajax({
        type: "POST",
        url: 'ajax/ajaxUpdateShift.php',
        data: 'id_attribute_shift=' + id_attribute_shift,
        success: function(result) {},

    });



}

function ValidatePasscode(passcode) {
    $("#Msgpasscode_value").html('');
    var id_attribute_steward = $("#id_attribute_steward").val();
    $.ajax({
        type: "POST",
        url: 'ajax/ajaxValidatePasscode.php',
        data: 'passcode=' + passcode + '&id_attribute_steward=' + id_attribute_steward,
        success: function(result) {

            if (result == 1) {

                $("#passcode_valid_status").val('1');
                $("#Msgpasscode_value").html('Passcode Valid');
                var enable_pass_id = $("#enable_pass_id").val();
                var enable_pass_UniqueCode = $("#enable_pass_UniqueCode").val();
                //$('#passwordModal').modal('hide');
                alert(enable_pass_id+'==='+enable_pass_UniqueCode);
                ajaxUpdateKot(enable_pass_id,enable_pass_UniqueCode);
            } else {

                $("#passcode_valid_status").val('0');
                $("#Msgpasscode_value").html('Invalid Passcode');
            }
        },

    });



}

function ValidatePasscodeNew(passcode) {
    $("#Msgpasscode_value").html('');
    var id_attribute_steward = $("#id_attribute_steward").val();
    //alert(passcode);
    $.ajax({
        type: "POST",
        url: 'ajax/ajaxValidatePasscode.php',
        data: 'passcode=' + passcode + '&id_attribute_steward=' + id_attribute_steward,
        success: function(result) {

            if (result == 1) {

                $("#passcode_valid_status").val('1');
                $("#Msgpasscode_value").html('Passcode Valid');
                //$('#passwordModal').modal('hide');

                var enable_pass_id = $("#enable_pass_id").val();
                var enable_pass_UniqueCode = $("#enable_pass_UniqueCode").val();
                $('#passwordModal').modal('hide');
               // alert(enable_pass_id+'==='+enable_pass_UniqueCode);
                ajaxUpdateKot(enable_pass_id,enable_pass_UniqueCode);


            } else {

                $("#passcode_valid_status").val('0');
                $("#Msgpasscode_value").html('Invalid Passcode');
            }
        },

    });



}

function ajaxupdateNonChargeableRemarks() {


    var get_remark = $('#get_remark').val();
    if (get_remark == '') {
        alert('Please add Remarks');
    } else {
        $('#NonChargeableRemarks').val(get_remark);
        //$('.targetDivShow').not('#div' + $(this).attr('target')).hide();
        $('#ncremarkspop').popup('hide');
        var id = $('#pos_purch_idpop').val();
        var UniqueCodeGen = $('#UniqueCodeGenpos').val();
        ajaxUpdateKot(id, UniqueCodeGen);
    }
}



function LoadPreviousOrder(){
var id_attribute_table=$("#id_attribute_table").val();
$( "#ViewPreviousOrder" ).html('');
	
		$.ajax({

		type: "POST",

		url: 'ajax/ajaxGetSubGroup.php',

		data: 'id_attribute_table='+id_attribute_table+'&listsubgroup=7'+'&UniqueCodeGen='+UniqueCodeGen,  

		success: function (result) {

			//alert(result);

			//resulthtml = result.split('EXPLODE');

			//alert(resulthtml[0]);			

				$( "#ViewPreviousOrder" ).html(result);

				//$( "#ViewPreviousOrder" ).html(result);

	 	}

	});

	}

function CheckOldPasscode(passcode) {
    $("#MsgoldPinpasscode_value").html('');
    var id_attribute_steward = $("#id_attribute_steward").val();
    //alert(passcode);
    $.ajax({
        type: "POST",
        url: 'ajax/ajaxValidatePasscode.php',
        data: 'passcode=' + passcode + '&id_attribute_steward=' + id_attribute_steward,
        success: function(result) {

            if (result == 1) {
              $("#MsgoldPinpasscode_value").html('Passcode Valid');
               


            } else {

                
                $("#MsgoldPinpasscode_value").html('Invalid Passcode');
            }
        },

    });



}
</script>