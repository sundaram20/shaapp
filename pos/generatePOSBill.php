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
          
          
          <!--end of shift--> 
          
          <!-- Modal -->
       
            <div class="" role="">
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

	
		$POSCurrentStartDate = date('d-m-Y',strtotime("-3 day", strtotime(date('d-m-Y'))));
	$POSCurrentEndDate 	= 	date('Y-m-d');
    $CheckBlockedTable_Sql_1 ="SELECT id_attribute_table,sum(total_qty) as total_qty, sum(total_adj_qty) as total_adj_qty FROM `pos_purch` WHERE `pos_bill_type` = 1 and cancelled!=1 and (DATE(date_created) BETWEEN '".$POSCurrentStartDate."' and '".$POSCurrentEndDate."' ) and doc_type!='24' and total_qty-total_adj_qty>0 and doc_type='".$_SESSION['id_document']."' GROUP BY id_attribute_table";
			

		   $db->query($CheckBlockedTable_Sql_1); 

		  while($ResultBlockedtable1_1 = $db->fetch_object()){ 	                  	
			$Resultdoc_type[]	=	$ResultBlockedtable1_1->id_attribute_table;
			$ResultBlockedtable[]	=	$ResultBlockedtable1_1->id_attribute_table;
			  }
			  
	

	                  	
					  
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
                                                                                         onclick="autoGenerateBill('<?php echo $rowContact->id ?>','<?php echo $rowContact->field_value;?>','<?php echo $UniqueCodeGen; ?>');"
                                                                                        id="TableGroup_<?php echo $rowContact->id ?>_<?php echo $rowContact->field_value;?>"><?php echo $rowContact->field_value;?>aaa</td>
                                      
                                      <!--<td style="width:14% !important; padding:5px 10px;" class="btn tableviewBlockedbtn" onclick="TabeleSelect(this.id); PreviousOrder(this.id)" id="TableGroup_<?php echo $rowContact->id ?>_<?php echo $rowContact->field_value;?>"><?php echo $rowContact->field_value;?></td>-->
                                      
                                      <?php }else{ ?>
                                      <td style=" background-color:#c6574b;"
                                                                                        class="btn tableviewBlockedbtn"
                                                                                        id="TableGroup_<?php echo $rowContact->id ?>_<?php echo $rowContact->field_value;?>"><?php echo $rowContact->field_value;?>sss</td>
                                      <?php }
  
  
  }

else

  { ?>
                                  
										
										
										<td class="btn tableviewbtn"
    onclick="autoGenerateBill('<?php echo $rowContact->id ?>','<?php echo $rowContact->field_value;?>','<?php echo $UniqueCodeGen; ?>');"
    id="TableGroup_<?php echo $rowContact->id ?>_<?php echo $rowContact->field_value;?>">
    <?php echo $rowContact->field_value;?>
</td>
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
                      
                      
                     
                    </div>
                    
                  </div>
                  
                  <!-----------------Table Part END--------------------> 
                </div>
              </div>
            </div>
         
          <!--modal popup ends--> 
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          <!--Add Items Remarks Modal Popups Starts-->
          

<!--Add Items Remarks Modal Popups Ends-->





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


<!-- Modal ends --> 

<!--Guest Modal Starts--> 

<!--Guest Modal Ends-->

<style>
#tableModal i{font-size:18px}#tableModal td,#tableModal th{padding:0}.paxbox label{margin:0 auto}.paxbox2{width:228px},.activetablegroup,.activetablenaitonality,.tablegroupbtn:hover,.tablenationalitybtn:hover{background-color:#d73925;color:#fff}.tablegroupbtn,.tablenationalitybtn{border:1px solid;outline:0;padding:11px;background-color:#00a65a;color:#fff;cursor:pointer;font-size:12px;border-radius:3px;-webkit-box-shadow:none;box-shadow:none}.tableviewBlockedbtn,.tableviewbtn{border:1px solid;color:#fff;-webkit-box-shadow:none;margin:1px}.tablegroupbtn{padding:13px}.noofpaxbtn,.tableviewBlockedbtn,.tableviewbtn{outline:0;padding:3px 8px;cursor:pointer;font-size:12px}#MyNumOfPax .noofpaxbtn:hover,.activenoofpaxbtn,.activetablegroup,.activetablenationality,.activetableviewbtn,.tablegroupbtn:hover,.tablenationalitybtn:hover,.tableviewBlockedbtn:hover,.tableviewbtn:hover{background-color:#d73925;color:#fff}.tableviewbtn{background-color:#00a65a;border-radius:3px;box-shadow:none;box-shadow:0 0 3px 0 #000}.tableviewBlockedbtn{background-color:#f54242;border-radius:3px;box-shadow:none;box-shadow:0 0 3px 0 #000}.noofpaxbtn{border:1px solid;background-color:#fff;color:#000;border-radius:3px;-webkit-box-shadow:none;box-shadow:none}.activenoofpaxbtn{background-color:#d73925!important;color:#fff}.activestewardbtn{background:#17b3be;box-shadow:0 0 12px 1px #000;color:#fff}.table-fixeditem tbody td a{width:83px}.activeset,.mainmenu_btn:hover{background-color:#00a65a;color:#fff}#myTableSecond .mainmenu_btn{background:#1874d3!important}.table-fixeditem tbody td{height:70px;width:83px;white-space:pre-wrap;margin-bottom:0;padding:0!important}.btns:focus{background:red}.table-fixed thead,.table-fixedTableGroup thead,.table-fixedsteward thead,.table-fixedsubmenu thead{width:97%}.table-fixed tbody,.table-fixed td,.table-fixed th,.table-fixed thead,.table-fixed tr{display:inline}.table-fixed tbody td,.table-fixed thead>tr>th,.table-fixeditem tbody td,.table-fixeditem thead>tr>th,.table-fixedsubmenu tbody td,.table-fixedsubmenu thead>tr>th{float:left;border-bottom-width:0}.table-fixedsteward tbody{width:100%}.table-fixedsteward tbody,.table-fixedsteward td,.table-fixedsteward th,.table-fixedsteward thead,.table-fixedsteward tr{display:flex}.table-fixedTableGroup tbody td,.table-fixedTableGroup thead>tr>th,.table-fixedsteward tbody td,.table-fixedsteward thead>tr>th{border-bottom-width:0}.table-fixedTableGroup tbody{height:194px;overflow-y:auto;width:100%}.table-fixedTableGroup tbody,.table-fixedTableGroup td,.table-fixedTableGroup th,.table-fixedTableGroup thead,.table-fixedTableGroup tr{display:block}.table-fixedsubmenu tbody{max-height:136px;width:100%}.table-fixeditem tbody{max-height:359px;overflow-y:auto;width:100%}.table-fixeditem tbody,.table-fixeditem td,.table-fixeditem th,.table-fixeditem thead,.table-fixeditem tr,.table-fixedsubmenu tbody,.table-fixedsubmenu td,.table-fixedsubmenu th,.table-fixedsubmenu thead,.table-fixedsubmenu tr{display:flex;flex-wrap:wrap}.table-fixedsubmenu td input{padding:3px!important;font-size:12px;margin-right:1px}#hideGroup{display:none}.tablefixeditem tbody td{height:70px;width:70px;overflow:hidden}.table-fixeditem tbody td a,.table-fixeditem tbody td input{padding:7px!important;white-space:pre-wrap!important;font-size:12px!important;margin-bottom:0!important;display:flex;justify-content:center;align-items:center}@media only screen and (min-width:776px) and (max-width:991px){.n-btn{padding:10px}#id_table{margin-top:80px}}@media only screen and (max-width:776px){.tablegroupbtn,.tablenationalitybtn{font-size:18px}.table-responsive{border:none!important}#listsubgroup{margin-top:10px}.paxbox2{width:323px}.table-fixedsubmenu tbody{max-height:48px}.table-fixeditem tbody{max-height:362px}.table-fixedTableGroup tbody{height:75px}#MyNumOfPax .table-fixed tbody,.table-fixedsteward tbody{height:132px}#MyNumOfPax .table-fixed tbody{height:90px}#myTableOrder tbody tr td:nth-child(3),.table-fixedsteward tbody{display:flex}#id_table .table-responsive{padding:0}#myTableTest{margin:0}#SearchResult input,.mainmenu_btn,.table>tbody>tr>td,table.dataTable tbody td,table.dataTable tbody th{font-size:12px}.noofpaxbtn,.tablegroupbtn{font-size:14px!important}#listsubgroup td{padding:1px!important}#myTableSecond tbody{max-height:264px}#SearchResult td a,#SearchResult td input,.table-fixeditem tbody td a,.table-fixedsubmenu td input{padding:3px!important;font-size:12px;display:flex;justify-content:center;align-items:center}.table-fixedTableGroup tbody tr td{padding:12px 12px 0 0!important}}
</style>


<!-- keypad modal starts -->

<script>
function autoGenerateBill(tableId, tableName, uniqueCode) {
    if (!confirm("Generate bill for table " + tableName + "?")) return;

    $.ajax({
        url: "ajax/ajax_generate_bill.php",
        type: "POST",
        data: {
            id_attribute_table: tableId,
            table_name: tableName,
            unique_code: uniqueCode
        },
        beforeSend: function() {
            $("#loader").show(); // optional
        },
        success: function(response) {
            $("#loader").hide();

            try {
                const res = JSON.parse(response);
                if (res.status === "success") {
                    alert("Bill generated successfully! Bill No: " + res.bill_no);
                    // redirect to billing page
                   // window.location.href = "kotbilling.php?submenu=177&bill_id=" + res.bill_id;
                } else {
                    alert("Error: " + res.message);
                }
            } catch (e) {
                alert("Invalid response: " + response);
            }
        },
        error: function(xhr, status, error) {
            $("#loader").hide();
            alert("AJAX error: " + error);
        }
    });
}
</script>



<!-- keypad modal ends --> 
<?php include_once("../includes/footer.php")?>
