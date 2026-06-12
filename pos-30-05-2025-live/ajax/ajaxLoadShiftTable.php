<?php include_once("../../config/auto_loader.php");?> 
    
      <div class="modal-content">
        <div class="modal-header">
          <label for="name">Shift table <font color="#FF0000">*</font> </label>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <table id="myTableTest" style="width:100%;" class="table table-fixed table-striped table-bordered dataTable no-footer" cellspacing="0"  >
              <tbody>
                <?php $POSCurrentDate	=date('d-m-Y',strtotime("-3 day", strtotime(date('d-m-Y'))));

					  $ResultBlockedtable =array();
					  $Resultdoc_type=array();

	$CheckBlockedTable_Sql_1 = "SELECT id_attribute_table,doc_type FROM pos_purch WHERE pos_bill_type='1' AND id IN (SELECT id_pos_purch as posid FROM pos_purch_details WHERE qty-adj_qty>0 AND DATE(date_created)>'".$POSCurrentDate."') and doc_type='".$_REQUEST['id_document']."'";
			

		   $db->query($CheckBlockedTable_Sql_1); 

		  while($ResultBlockedtable1_1 = $db->fetch_object()){ 	                  	
			$Resultdoc_type[]	=	$ResultBlockedtable1_1->id_attribute_table;
			  }
			  
	$CheckBlockedTable_Sql = "SELECT id_attribute_table,doc_type FROM pos_purch WHERE cancelled=0 AND id IN (SELECT id_pos_purch as posid FROM pos_purch_details WHERE qty-adj_qty>0 AND DATE(date_created)>'".$POSCurrentDate."')";

	                   $db->query($CheckBlockedTable_Sql); 

	                  while($ResultBlockedtable1 = $db->fetch_object()){ 

	                   $ResultBlockedtable[]	=	$ResultBlockedtable1->id_attribute_table;
						

					  }

	                  	
					  

				$resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'table_group'."' ",' ORDER BY `id` asc');

								  if($db->num_rows2($resCat)){

								  	$resultCat = $db->fetch_object2($resCat);  

								  }

	// $resContact = selectSql(TBL_ATTRIBUTES," where id_shop='".$_SESSION['shop']."'   AND id_table_group='".$resultCat->id."'  AND  status = '1' AND table_name ='".'table'."' ",' ORDER BY LPAD(lower(`field_value`),6,0) asc');
$resContact = selectSql(TBL_ATTRIBUTES," where id_shop='".$_SESSION['shop']."'   AND  status = '1' AND table_name ='".'table'."' ",' ORDER BY LPAD(lower(`field_value`),6,0) asc');
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
                  <td  class="btn tableviewBlockedbtn"  onClick="shiftTable(<?php echo $rowContact->id ?>,'<?php echo $rowContact->field_value;?>');"  id="TableGroup_<?php echo $rowContact->id ?>_<?php echo $rowContact->field_value;?>"><?php echo $rowContact->field_value;?></td>
                  
                  <!--<td style="width:14% !important; padding:5px 10px;" class="btn tableviewBlockedbtn" onclick="TabeleSelect(this.id); PreviousOrder(this.id)" id="TableGroup_<?php echo $rowContact->id ?>_<?php echo $rowContact->field_value;?>"><?php echo $rowContact->field_value;?></td>-->
                  
                  <?php }else{ ?>
                  <td style="background-color:#c6574b;" class="btn tableviewBlockedbtn" 
                 onClick="shiftTable(<?php echo $rowContact->id ?>,'<?php echo $rowContact->field_value;?>');"  id="TableGroup_<?php echo $rowContact->id ?>_<?php echo $rowContact->field_value;?>"><?php echo $rowContact->field_value;?></td>
                  <?php }
  
  
  }

else

  { ?>
                  <td class="btn tableviewbtn" onclick="shiftTable(<?php echo $rowContact->id ?>,'<?php echo $rowContact->field_value;?>');" id="TableGroup_<?php echo $rowContact->id ?>_<?php echo $rowContact->field_value;?>"><?php echo $rowContact->field_value;?></td>
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
    
 