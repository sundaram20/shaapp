<?php include_once("../config/auto_loader.php");?>
<script src="<?php echo $SITE_URL; ?>/pos/js/custom.js" ></script>
<?php include_once("../includes/header.php")?>
    <?php include_once("../includes/left.php");
//unset($_SESSION['POSKOT']);
unset($_SESSION['POSKOT']['outlet']);
$UniqueCodeGen = 'UNIC'.rand(0000,9999);
  ?>
    <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/pos/css/style.css"/>
<style>
	.discountvalue{border-radius:1px;width:22px!important;float: left;padding: 1px 4px!important;text-align:center;height: 24px;display:flex;align-items:center;
		background-color: #31c6d5!important;
	border:none;
}
.btn-danger{
	background: #f2f2f2;
    color: #ff7777;
    border: none;
    width:34px!important;
}


</style>

    <div class="content-wrapper"> 
    
    <!-- ItemModal Modal -->
    <?php $id_item = $_POST['id']; ?>
    <div class="modal fade" id="ItemModal" tabindex="-1" role="dialog" aria-labelledby="ItemModalLabel">
        <div class="modal-dialog" role="document" >
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="ItemModalLabel">Sub Items</h4>
          </div>
            <div class="modal-body">
            <div id="ajaxPlanData">
                <div class="box box-success  table-responsive no-padding">
                <input type="text" name="bd_count" id="bd_count" hidden="">
                <br/>
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
        <div class="col-md-4 col-xs-12">
            <h6 class="box-title" style="margin:0px !important;padding: 0px!important;"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation_id($session)['submenu']; ?> : <a><?php echo selectColumn(TBL_INV_PO,'doc_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND 'id' = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'"); ?></a></h6>
          </div>
        <div class="col-md-4 col-xs-12 dd-f">
            <div class="icn-box">
            <div class="btn-group"> <a type="button"  title="List KOT" class="btn n-btn pull-right" href="manageKot.php?submenu=178&session=22" > <i class="fas fa-list"></i> KOT</a> </div>
            <div class="btn-group  "> <a type="button"  title="Add Bill" class="btn n-btn pull-right" href="kotbilling.php?submenu=177&session=21" ><i class="fas fa-plus "></i> Bill </a> </div>
            <div class="btn-group"> <a type="button"  title="List Bill" class="btn n-btn pull-right" href="manageOutletBilling.php?submenu=177&session=21" > <i class="fas fa-list "></i> Bill</a> </div>
          </div>
          </div>
        <div class="col-md-4 col-xs-12 tb-br"> <?php echo breadCrumbs(); ?> </div>
      </div>
      </section>
    
    <!-- Main content -->
    
    <section class="content">
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
    <div >
    <div >
    
    <!-- /.box-header -->
    
    <form name="FormPosKot" id="FormPosKot" action="kotbilling.php?submenu=177" method="post">
        <input type="hidden" value="1" name="FormSubmitPosKot" />
        <input type="hidden" value="<?php echo $_REQUEST['submenu'];?>" name="submenu1" id="submenu1">
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

               <div class="col-md-2">
                <div class="form-group">
                   <!-- <label for="name">Shift <font color="#FF0000">*</font> </label>-->
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
              <!--end of shift-->

<!-- Modal -->
<div class="modal fade" id="tableModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     
      <div class="modal-body">
    
        <div id="myDIV">
            <div class="row">
            <div class="col-md-2" id="hideGroup">
                <div class="form-group mb-0">
                <label for="name">Table Group<font color="#FF0000">*</font> </label>
                <div class="box-body table-responsive " style="padding-left: 0px;padding-bottom: 0px;padding-top: 0px;padding-right: 4px;">
                    <div id="MyTableGroupID">
                    <table id="myTableTableList" class="table table-fixedTableGroup table-striped table-bordered dataTable no-footer" cellspacing="0" >
                        <tbody >
                        <?php 



				  $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'table_group'."' ",' ORDER BY `id` asc');

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
                <div class="col-md-12">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <!-- <label for="name">Table <font color="#FF0000">*</font> </label> -->
                    <div class="box-body table-responsive" style="padding-left: 0px;padding-top: 0px;padding-right: 4px;">
                    <div id="TableListview">
                        <table id="myTableTest" class="table table-fixed table-striped table-bordered dataTable no-footer" cellspacing="0"  >
                        <tbody>
                            <?php 

					  $ResultBlockedtable =array();
					  $Resultdoc_type=array();

	$CheckBlockedTable_Sql_1 = "SELECT id_attribute_table,doc_type FROM pos_purch WHERE pos_bill_type='1'  AND doc_type!='24' AND id IN (SELECT id_pos_purch as posid FROM pos_purch_details WHERE qty-adj_qty>0) and doc_type='".$_SESSION['id_document']."'";
			

		   $db->query($CheckBlockedTable_Sql_1); 

		  while($ResultBlockedtable1_1 = $db->fetch_object()){ 	                  	
			$Resultdoc_type[]	=	$ResultBlockedtable1_1->id_attribute_table;
			  }
			  
	$CheckBlockedTable_Sql = "SELECT id_attribute_table,doc_type FROM pos_purch WHERE cancelled=0  AND doc_type!='24' AND id IN (SELECT id_pos_purch as posid FROM pos_purch_details WHERE qty-adj_qty>0)";

	                   $db->query($CheckBlockedTable_Sql); 

	                  while($ResultBlockedtable1 = $db->fetch_object()){ 

	                   $ResultBlockedtable[]	=	$ResultBlockedtable1->id_attribute_table;
						

					  }

	                  	
					  

				$resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'table_group'."' ",' ORDER BY `id` asc');

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
                            <td style="" class="btn tableviewBlockedbtn" onclick="TabeleSelect(this.id); PreviousOrderPax(this.id,<?php echo '\''.$UniqueCodeGen.'\''?>);PreviousOrder(this.id,<?php echo '\''.$UniqueCodeGen.'\''?>);ajaxRemoveAllItemList('removeAll',<?php echo '\''.$UniqueCodeGen.'\''?>);" id="TableGroup_<?php echo $rowContact->id ?>_<?php echo $rowContact->field_value;?>"><?php echo $rowContact->field_value;?></td>
                            
                            <!--<td style="width:14% !important; padding:5px 10px;" class="btn tableviewBlockedbtn" onclick="TabeleSelect(this.id); PreviousOrder(this.id)" id="TableGroup_<?php echo $rowContact->id ?>_<?php echo $rowContact->field_value;?>"><?php echo $rowContact->field_value;?></td>-->
                            
                            <?php }else{ ?>
                            <td style=" background-color:#c6574b;" class="btn tableviewBlockedbtn"   id="TableGroup_<?php echo $rowContact->id ?>_<?php echo $rowContact->field_value;?>"><?php echo $rowContact->field_value;?></td>
                            <?php }
  
  
  }

else

  { ?>
                            <td style="" class="btn tableviewbtn" onclick="TabeleSelect(this.id); PreviousOrderPax(this.id,<?php echo '\''.$UniqueCodeGen.'\''?>);PreviousOrder(this.id,<?php echo '\''.$UniqueCodeGen.'\''?>);ajaxRemoveAllItemList('removeAll',<?php echo '\''.$UniqueCodeGen.'\''?>);" id="TableGroup_<?php echo $rowContact->id ?>_<?php echo $rowContact->field_value;?>"><?php echo $rowContact->field_value;?></td>
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


                <div class="col-md-12"  style="display:nne;">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <label for="name">No Of Paxs <font color="#FF0000">*</font> </label>
                    <div class="box-body table-responsive" style="padding: 0px;">
                    <div id="MyNumOfPax">
                        <table id="myTableTest" class="table table-fixed table-striped table-bordered dataTabletest no-footer" cellspacing="0" >
                        <tbody>
                            <?php  for ($i=1; $i<=50; $i++)

    				{
						echo $categoryDropDown = '<tr class="paxloadmore"><td style="" class="noofpaxbtn" id="'.$i.'" onclick="SelectNoPaxs(this.id);">'.$i.'</td></tr>';
					}
				?>
        <tr class="paxloadbtn" ><td  class="btn" onclick="Paxload()"><i class="fa fa-plus"></i></td></tr>
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
                <div class="form-group" style="margin-bottom: 0px !important;">
                 <!--       <label for="name">Steward <font color="#FF0000">*</font> </label>-->
                    <div class="box-body table-responsive" style="padding: 0px;">
                    <div id="MyStewardSelect">
                        <table id="myTableTest" class="table table-fixedsteward table-striped table-bordered dataTabletest no-footer" cellspacing="0" >
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
										$image =  $resultCat->image;
										}else{
											$image = "images/steward.png";
										}


										echo $categoryDropDown = '<tr><td class="noofpaxbtn bt" id="'.$resultCat->id.'_'.$resultCat->field_value.'" onclick="SelectSteward(this.id);">'.ucfirst($resultCat->field_value).'<img src="'.$image.'" style="height:53px;border-radius:50%;"></td></tr>';

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
          </div>
              <div class="modal-footer">
        <button type="button" class="btn c-btn btn-default" data-dismiss="modal">Select</button>
      
      </div>
          </div>
        
        <!-----------------Table Part END-------------------->
         </div>
    
    </div>
  </div>
</div> <!--modal popup ends-->
        
        <div class="col-md-12 p-0">
            <div class="">
           <button type="button" class="btn tooltiptable" data-toggle="modal" data-target="#tableModal">
            

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
                <label for="name"  title="Table"><i class="fas fa-chair"></i> : <span id="ViewSelectedTable1" style="color:#FF0000"></span></label>
                <input type="hidden" name="doc_type_bill" id="doc_type_bill" value="<?php echo $doc_type_bill; ?>"/>
                <input type="hidden" name="doc_type_kot" id="doc_type_kot" value="<?php echo $doc_type_kot; ?>"/>
                <input type="hidden" name="id_subsection" id="id_subsection" value="<?php echo $idsubsection; ?>"/>
                <input type="hidden" name="id_attribute_table" id="id_attribute_table" value=""/>
                <label for="name" title="No of Pax">  <i class="fa fa-users"></i> : <span id="ViewSelectedPax1" style="color:#FF0000" ></span></label>
                <input type="hidden" name="pax" id="pax" value=""/>
                <label for="name"  title="Steward Name"><i class="fa-solid fa-person"></i>: <span id="ViewSteward1" style="color:#FF0000"></span></label>
                <input type="hidden" name="id_attribute_steward" id="id_attribute_steward" value=""/>
                <input type="hidden" name="attribute_steward_name" id="attribute_steward_name" value=""/>
           
              </div>
              <!--box-heaer ends-->
                <span class="tooltiptext" id="tooltiptext">Click here</span>
             
            </button>

              <!--modal button ends-->
          </div>

           <div class="hr-box">
              <hr >
              <div class="grouptitle">
                 Main Group
              </div>
            </div>  
            <div class="form-group mb-0 maingroupbox" >
            <div class="input-group" id="MainMenuID">
                <?php 

		  echo '<input name="selectmaingroup" id="selectmaingroup_'.$resultCat->id.'" type="button" class="mainmenu_btn activeset" value="All"  onclick="getsubgrouplist(this.id,\''.$UniqueCodeGen.'\');" style="margin-bottom:5px;">&nbsp;';

		  

						  $resCatMain = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and `id_mst_attributes_item_type`=16  AND status='1' GROUP BY id_mst_attributes_group_main"); 

	  while($row2 = $db->fetch_object2($resCatMain)){ 

						  $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'item_group_main' and field_value!='Laundry' and field_value!='Spa And Health Club' and id= '".addslashes($row2->id_mst_attributes_group_main)."' ",' ORDER BY `field_value`');

								  if($db->num_rows2($resCat)){

									  

								  	while($resultCat = $db->fetch_object2($resCat)){

										

		echo '<input name="selectmaingroup" id="selectmaingroup_'.$resultCat->id.'" type="button" class="mainmenu_btn" value="'.ucfirst($resultCat->field_value).'"  onclick="getsubgrouplist(this.id,\''.$UniqueCodeGen.'\');" style="margin-bottom:5px;">&nbsp;';

									}

								  }
	  }
		?>
              </div>
          </div>
          </div>
        <div id="listsubgroup">
            <div class="col-md-12 p-0">
               <div class="hr-box">
                  <hr>
                  <div class="grouptitle">
                    Sub Group
                  </div>
               </div>  
            <div class="form-group mb-0">
                <div class="box-body table-responsive" style="padding-top: 10px;padding-left: 1px;padding-right: 5px;">
                <table id="myTableFirst" class="table table-fixedsubmenu table-striped table-bordered dataTable no-footer" cellspacing="0" >
                    <thead style="display:none;">
                    <tr style="height:10px;">
                        <th colspan="6" style="padding: 4px 10px;font-weight:100;" > Sub Group </th>
                      </tr>
                  </thead>
                    <tbody>
                    <?php

      $id_item_type=selectColumn(TBL_ATTRIBUTES,'id','WHERE table_name="items_type" AND field_value="Menu" ');          

   	  $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' AND status='1' and `id_mst_attributes_item_type`=16 GROUP BY id_mst_attributes_group_sub"); 

	  while($row = $db->fetch_object2($resCat)){ 

	  	

	  $SqlAttrbute = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'item_group_sub' AND id= '".addslashes($row->id_mst_attributes_group_sub)."'");

	  if($db->num_rows2($SqlAttrbute)){

	  $resultAttrbute = $db->fetch_object2($SqlAttrbute);

	  echo $subGroup ='<tr><td style="padding:0px;" ><input name="selectitemlist" id="selectitemlist_'.$resultAttrbute->id.'" type="button" class="btn btn-success mainmenu_btn" value="'.ucfirst($resultAttrbute->field_value).'"  onclick="getItemlist(this.id,\''.$UniqueCodeGen.'\');" style="margin-bottom:5px;padding: 5px 10px;" >&nbsp;</td></tr>';

					}

					  }

                ?>
                  </tbody>
                  </table>
              </div>
              </div>
          </div>
            <div class="col-md-12 p-0" id="listitemName" style="">
          
            <div class="form-group">
                <div class="box-body table-responsive" style="">
                <table id="myTableSecond" class="table table-fixedsubmenu table-striped table-bordered dataTable no-footer" cellspacing="0" >
                    <thead>
                    <tr>
                        <th style="padding: 4px 10px;font-weight:100;"> Menu &nbsp;
                        <input type="text" name="keywordsearch" id="keywordsearch"  placeholder="Search Menu"  onKeyUp="keysearch(this.value,'<?php echo $UniqueCodeGen; ?>')" ></th>
                      </tr>
                  </thead>
                    <tbody id="SearchResult">
                    <?php 

   				    $SqlItemList = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' AND id_mst_attributes_item_type='".$id_item_type."' and  status='1' "); 

					 $i=1;
					  while($row = $db->fetch_object2($SqlItemList)){ 
				?>
                    <?php if($i==1){?>
                    <tr>
                        <?php } ?>
                        <?php  
				$resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' AND status='1' AND  id='".$row->id."' ");
			    $row2 = $db->fetch_object2($resCat);
				
				$itemNameSelectSQL = "SELECT * FROM `".TBL_INV_ITEMS_DETAILS."` WHERE id_item='".$row->id."' and enabled='1' ";
				$resitemName=mysqli_query($connNew,$itemNameSelectSQL); 
				$itemNameNumRows = mysqli_num_rows($resitemName);
				if($itemNameNumRows>0){
					while($rowitemName = mysqli_fetch_object($resitemName)){
					}	
				?>
                        <td style="padding:0px;margin-bottom: 5px;"><a name="addItemList" id="<?php echo $row->id;?>"  class="btn mainmenu_btn btn-success" value="<?php echo ucfirst($row->name);?>"  onclick="selectsubitem(this.id,'<?php echo $UniqueCodeGen; ?>');" style="padding: 4px 11px;"><?php echo ucfirst($row->name);?></a></td>
                        <?php	
				
				}else{
				?>
                        <td style="padding:0px;margin-bottom:5px;"><a name="addItemList" id="addItemList_<?php echo $row->id;?>"  class="btn mainmenu_btn btn-success" value="<?php echo ucfirst($row->name);?>"  onclick="AddgetItemlist(this.id,'<?php echo $UniqueCodeGen; ?>');" style="padding: 4px 11px;"><?php echo ucfirst($row->name);?></a></td>
                        <?php	
				}
				?>
                        <?php if($i==1){ $i=1;?>
                      </tr>
                    <?php }else{ $i++; } ?>
                    <?php }

					  ?>
                  </tbody>
                  </table>
              </div>
              </div>
          </div>
          </div>



      <div class="ordersdiv">   

            <div onclick="myFunction()" class="btn btn-block btn-primary btn-sm" id="HideOpen" style="float:right;width:120px;margin-right:10%;">Previous Order</div>  
        <div class="col-md-12" id="ViewPreviousOrder" >
            <div class="box-header with-border" style="padding-bottom:10px; padding-top:0px;">
            <h3 class="box-title">Previous Order </h3>
          </div>
            <div class="form-group" style="margin-bottom: 1px;" >
            <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">
                <table id="myTableOrder1" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" >
                <thead>
                    <tr>
                    <th> S.No.&nbsp;</th>
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
            <div class="box-header with-border" style="padding-bottom:10px; padding-top:0px;">
                <h3 class="box-title">Current Order </h3>  
            </div><!--end of box-header-->
            <div class="form-group" style="margin-bottom: 1px;">
              <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">
                    <table id="myTableOrder" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" >
                    <thead>
                        <tr>
                        <th width="10%"> S.No.&nbsp;</th>
                        <th>Items Name</th>
                        <th class="qnty">Quantity</th>
                        <th>Price</th>
                        <th>Amount</th>
                        <th>Action</th>
                      </tr>
                      </thead>
                    <tbody>
                      </tbody>
                  </table>
              </div><!--end of box-body-->
           </div><!--end of form-group-->

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


<div class="modal fade" id="adminsOfferform" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form id="shiftTablepopupform" name="shiftTablepopupform"   enctype="multipart/form-data">
          <input name="ids_purch"  id="ids_purch" type="hidden" class="form-control">
          <input type="hidden" name="id_table_selected" id='id_table_selected' value="">
          <input type="hidden" name="id_table_shift" id='id_table_shift' value="">
          <div class="modal-content">
        <div class="modal-header">
        <label for="name">Shift table <font color="#FF0000">*</font> </label>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
        <div class="modal-body">
        <div class="form-group">
            <table id="myTableTest" style="width:400px;" class="table table-fixed table-striped table-bordered dataTable no-footer" cellspacing="0"  >
            <tbody>
                <?php 

					  $ResultBlockedtable =array();
					  $Resultdoc_type=array();

	$CheckBlockedTable_Sql_1 = "SELECT id_attribute_table,doc_type FROM pos_purch WHERE pos_bill_type='1' AND id IN (SELECT id_pos_purch as posid FROM pos_purch_details WHERE qty-adj_qty>0) and doc_type='".$_SESSION['id_document']."'";
			

		   $db->query($CheckBlockedTable_Sql_1); 

		  while($ResultBlockedtable1_1 = $db->fetch_object()){ 	                  	
			$Resultdoc_type[]	=	$ResultBlockedtable1_1->id_attribute_table;
			  }
			  
	$CheckBlockedTable_Sql = "SELECT id_attribute_table,doc_type FROM pos_purch WHERE cancelled=0 AND id IN (SELECT id_pos_purch as posid FROM pos_purch_details WHERE qty-adj_qty>0)";

	                   $db->query($CheckBlockedTable_Sql); 

	                  while($ResultBlockedtable1 = $db->fetch_object()){ 

	                   $ResultBlockedtable[]	=	$ResultBlockedtable1->id_attribute_table;
						

					  }

	                  	
					  

				$resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'table_group'."' ",' ORDER BY `id` asc');

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
                <td style="width:14% !important; padding:5px 10px;" class="btn tableviewBlockedbtn"  onClick="shiftTable(<?php echo $rowContact->id ?>,'<?php echo $rowContact->field_value;?>');"  id="TableGroup_<?php echo $rowContact->id ?>_<?php echo $rowContact->field_value;?>"><?php echo $rowContact->field_value;?></td>
                
                <!--<td style="width:14% !important; padding:5px 10px;" class="btn tableviewBlockedbtn" onclick="TabeleSelect(this.id); PreviousOrder(this.id)" id="TableGroup_<?php echo $rowContact->id ?>_<?php echo $rowContact->field_value;?>"><?php echo $rowContact->field_value;?></td>-->
                
                <?php }else{ ?>
                <td style="width:14% !important; padding:5px 10px; background-color:#c6574b;" class="btn tableviewBlockedbtn" 
                 onClick="shiftTable(<?php echo $rowContact->id ?>,'<?php echo $rowContact->field_value;?>');"  id="TableGroup_<?php echo $rowContact->id ?>_<?php echo $rowContact->field_value;?>"><?php echo $rowContact->field_value;?></td>
                <?php }
  
  
  }

else

  { ?>
                <td style="width:14% !important; padding:5px 10px;" class="btn tableviewbtn" onclick="shiftTable(<?php echo $rowContact->id ?>,'<?php echo $rowContact->field_value;?>');" id="TableGroup_<?php echo $rowContact->id ?>_<?php echo $rowContact->field_value;?>"><?php echo $rowContact->field_value;?></td>
                <?php }

                      ?>
                <?php if($i==5){ $i=1;?>
              </tr>
                <?php }else{ $i++; } ?>
                <?php

														

																										

		}}?>
              </tbody>
          </table>
          <div id="shift_table_value" style="float:right;margin-right:50px;">Shift Table To: -</div>
          </div>
      </div>
        <div class="modal-footer">
        <button class="btn c-btn" type="button" onClick="updateShiftTable();"><i class="fas fa-exchange-alt"></i> Shift</button>
        <button class="cancelpop_close btn c-btn" data-dismiss="modal"><i class="far fa-window-close"></i> Close</button>
      </div>
      </div>
        </form>
      </div>
    </div>











<!-- Modal -->

<style>

/* Style the buttons */

.tablegroupbtn {

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

}



/* Style the active class, and buttons on mouse-over */

.activetablegroup, .tablegroupbtn:hover {

  background-color: #d73925;;

  color: #fff;

}





.tablegroupbtn {

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

}



/* Style the active class, and buttons on mouse-over */

.activetablegroup, .tablegroupbtn:hover {

  background-color: #d73925;;

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

  background-color:#f54242;

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

.activetableviewbtn, .tableviewbtn:hover {

  background-color: #d73925;;

  color: #fff;

}



/* Style the active class, and buttons on mouse-over */

.activetableviewbtn, .tableviewBlockedbtn:hover {

  background-color: #d73925;;

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

.activenoofpaxbtn,   #MyNumOfPax .noofpaxbtn:hover {

  background-color: #d73925;;

  color: #fff;

}
.activenoofpaxbtn{

  background-color: #d73925!important;

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

  padding: 5px 10px;

 background-color:#2471a3;/*#00db74;*/



color: #fff;

border-color: #008d4c;



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

.activeset, .mainmenu_btn:hover {

  background-color: #00a65a;

  color: white;

}

#myTableSecond .mainmenu_btn{
 background:#1874d3!important;
}


</style>
<style>

   input.activeMenuItem {

    background-color: #F00;

    font-weight: bold;

}

   .btns:focus{

        background:red;

    }

.table-fixed thead {

  width: 97%;

}



.table-fixed thead, .table-fixed tbody, .table-fixed tr, .table-fixed td, .table-fixed th {

  display: block;

}

.table-fixed tbody td, .table-fixed thead > tr> th {

	float: left;

  border-bottom-width: 0;

}



.table-fixedsteward thead {

  width: 97%;

}

.table-fixedsteward tbody {


  width: 100%;

}

.table-fixedsteward thead, .table-fixedsteward tbody, .table-fixedsteward tr, .table-fixedsteward td, .table-fixedsteward th {

  display: block;

}

.table-fixedsteward tbody td, .table-fixedsteward thead > tr> th {

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

.table-fixedTableGroup thead, .table-fixedTableGroup tbody, .table-fixedTableGroup tr, .table-fixedTableGroup td, .table-fixedTableGroup th {

  display: block;

}

.table-fixedTableGroup tbody td, .table-fixedTableGroup thead > tr> th {

  /*float: left;*/

  border-bottom-width: 0;

}







.table-fixedsubmenu thead {

  width: 97%;

}

.table-fixedsubmenu tbody {

  max-height: 145px;

  overflow-y: auto;

  width: 100%;

}

.table-fixedsubmenu thead, .table-fixedsubmenu tbody, .table-fixedsubmenu tr, .table-fixedsubmenu td, .table-fixedsubmenu th {

  display: block;

}

.table-fixedsubmenu tbody td, .table-fixedsubmenu thead > tr> th {

  float: left;

  border-bottom-width: 0;

}
#hideGroup {
   display:none;
}

 @media only screen and (max-width:776px){

.table-fixedTableGroup tbody {

   height: 75px;
 }
 #MyNumOfPax .table-fixed tbody{
  height:132px;
 }
 .table-fixedsteward tbody{
  height:94px;
 }
 
 #id_table .table-responsive{
   padding:0px;
 }
 #myTableTest{
  margin:0;
 }
 

table.dataTable tbody th, table.dataTable tbody td ,.table>tbody>tr>td,.mainmenu_btn {
    padding: 5px 14px!important;
    font-size: 14px;
}
.noofpaxbtn, .tablegroupbtn{
   font-size:14px!important;

}
#myTableOrder tbody tr td:nth-child(3){
   display: flex;
}
#listsubgroup td {
  padding:1px!important;
}

#myTableSecond tbody{
  height:192px;
}
}
 </style>
<?php include_once("../includes/footer.php")?>
<script>


function shiftTable(id_table_shift,shift_table_value){
	
	$( "#id_table_shift" ).val(id_table_shift);
	$( "#shift_table_value" ).html('Shift Table To '+shift_table_value);
	}
function updateShiftTable(){
	
	var form=$("#shiftTablepopupform");
	if(form.parsley().validate()){
	$('.loading').show(); 
	$.ajax({
	   type: "POST",
	   url: 'ajax/ajaxUpdateShiftTable.php',
	   data: form.serialize(), 
	   success: function (result) {
		   
		   if(result==1){
		   alert('Please select Table.');
		   return false;
		   }else{
		  alert(' Table changed Successfully');
		  window.location.reload();
		   }
		},
	  complete: function(){
		$('.loading').hide();
	  }
	});
	return false;
	}
	

//	id_table_shift id_table_selected ids_purch

	

	
	
	
}
function AddgetItemlist2(addItemList,subid,UniqueCodeGen){
	
//	alert(UniqueCodeGen);
	resultArrayItemlistId = addItemList.split('_');
	$('#'+addItemList).not(this).removeClass();
	$('#'+addItemList).toggleClass('btn btn-success');
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxGetSubGroup.php',
		   data: 'selectSubgroup='+resultArrayItemlistId[1]+'&listsubgroup=3'+'&subid='+subid+'&UniqueCodeGen='+UniqueCodeGen,
		   success: function (result) {	
				$( "#GetItemListView" ).html(result);
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


document.getElementById("ViewPreviousOrder").style.display="none";
function myFunction() {

	var x = document.getElementById("ViewPreviousOrder");

	var y = document.getElementById("GetItemListView");

	var clickButton = document.getElementById('HideOpen');





  if (x.style.display === "none") {

	
    x.style.display = "block";
     y.style.display = "none";
       clickButton.innerHTML = 'Curent Order ';

  } else {

	
    x.style.display = "none";
 y.style.display = "block";
   clickButton.innerHTML = 'Previous Order';
	

  }

}

 function TabeleSelect(Selectname)

	  {  

	resultArrayTableID = Selectname.split('_');

	$( "#id_attribute_table" ).val(resultArrayTableID[1]);
	$( "#ViewSelectedTable1" ).html(resultArrayTableID[2]);

  }

function SelectSteward(Steward)

	  {  

	resultArrayStewardID = Steward.split('_');

	$( "#id_attribute_steward" ).val(resultArrayStewardID[0]);
	$( "#attribute_steward_name" ).val(resultArrayStewardID[1]);
	$( "#ViewSteward1" ).html(resultArrayStewardID[1]);

  }  

  

function SelectNoPaxs(Selectpax)

  {  

	$( "#pax" ).val(Selectpax);

	$( "#ViewSelectedPax1" ).html(Selectpax);

  	}

	

function SelectshiftType(SelectshiftType)
  {  

  resultArrayStewardID = Steward.split('_');

  alert(SelectshiftType);

	//$( "#pax" ).val(SelectshiftType);

	

  	}	 

</script>
<script>

$('#myTableFirst').dataTable( {

    "paging":false,

	"info":false,

	"searching":false,

    

} );

$(document).ready(function(){

	$('#myTableOrder').DataTable({

	"paging":false,

	"info":false,

	"searching":false,

	scrollY:        50,

    deferRender:    true,

    scroller:       true


	});

	});



$('#myTableSecond').dataTable( {

    "paging":false,

	"info":false,

	"searching":false,

    scrollY:        50,

    deferRender:    true,

    scroller:       true

} );


$('#myTableTableList').dataTable( {

    "paging":false,

	"info":false,

	"searching":false,

    scrollY:        200,

    deferRender:    true,

    scroller:       true

} );

	

function selectsubitem(id,UniqueCodeGen)
  {  
$('#ItemModal').modal('show');
	$.ajax({
		url: "ajax/ajaxSubItem.php",
		  type: 'POST',
		 // data: { id : id },
		 data: 'id='+id+'&UniqueCodeGen='+UniqueCodeGen, 
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
  function ajaxShiftTable(){
  
  //$("#cancelled").addClass("bookedby_open");
  $('#cancelpop2').popup({
              transition: 'all 0.3s',
                 autoopen: true,            
              });
  //$("#pos_purch_id").val(posid);
  //$("#kot_mdoc_no").html(' KOT No: '+mdoc_no);        
  }
  
  function openshiftTable(id_table_selected,ids_purch)
{
	
	 // $('.modal-titlecheck', 
	  //$('#'+fid)).html(txt);
	  
	  $('#id_table_shift').val('');
$('#id_table_selected').val(id_table_selected);
$('#ids_purch').val(ids_purch);
  

  

}


//noofpaxbtn  more button
  
jQuery(document).ready(function($){
  $(".paxloadbtn").click(function(e){
    $(".paxloadmore:hidden").slice(0,15).fadeIn();
    if ($(".paxloadmore:hidden").length < 1) $(this).fadeOut();
  })
})

  </script>

  <script>
    //script for tooltip
   //document.getElementById('tooltiptext').style.display="nonhe";
    document.getElementById('tooltiptext').style.visibility="visible";
    setTimeout(function (){
        $('.tooltiptext').hide();
    },1000);
    setTimeout(function (){
        $('.tooltiptext').show();
    },2000);
     setTimeout(function (){
        $('.tooltiptext').hide();
    },3000);
      setTimeout(function (){
        $('.tooltiptext').show();
    },4000);
setTimeout(function (){
        $('.tooltiptext').hide();
    },5000);
      setTimeout(function (){
        $('.tooltiptext').show();
    },6000);
       setTimeout(function (){
        $('.tooltiptext').hide();
    },7000); 
</script>

