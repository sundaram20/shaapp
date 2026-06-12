<?php include_once("../../config/auto_loader.php");

?>
<script>var headerTwo = document.getElementById("TableListview");

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

</script>
<?php

		$table_group=$_REQUEST['table_group'];


 					$Resultdoc_type=array();

	$type = "table";
	$sql = "  SELECT * FROM `".TBL_ATTRIBUTES."` WHERE `id` = '".$table_group."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
	$db->query($sql);
	if($db->num_rows() > 0){
		$firstRecord = $db->fetch_object();
		if ($firstRecord->id_table_group == 1) {
			$type = "room";
		} else {
			$type = "table";
		}
	}
					
					
	$POSCurrentStartDate = date('d-m-Y',strtotime("-3 day", strtotime(date('d-m-Y'))));
	$POSCurrentEndDate 	= 	date('Y-m-d');
    $CheckBlockedTable_Sql_1 ="SELECT id_attribute_table,sum(total_qty) as total_qty, sum(total_adj_qty) as total_adj_qty 
	FROM `pos_purch`
	 WHERE `pos_bill_type` = 1 
	 and cancelled!=1 
	 and (DATE(date_created) BETWEEN '".$POSCurrentStartDate."' and '".$POSCurrentEndDate."' ) 
	 and doc_type!='24' and total_qty-total_adj_qty>0 and doc_type='".$_SESSION['id_document']."' GROUP BY id_attribute_table";
		
					

					  //$CheckBlockedTable_Sql_1 = "SELECT id_attribute_table,doc_type FROM pos_purch WHERE pos_bill_type='1' AND doc_type!='24' AND id IN (SELECT id_pos_purch as posid FROM pos_purch_details WHERE qty-adj_qty>0) and doc_type='".$_SESSION['id_document']."'";

	                   $db->query($CheckBlockedTable_Sql_1); 
					$ResultBlockedtable=array();
	                  while($ResultBlockedtable1_1 = $db->fetch_object()){ 	                  	
						$Resultdoc_type[]	=	$ResultBlockedtable1_1->id_attribute_table;
						 array_push($ResultBlockedtable,$ResultBlockedtable1_1->id_attribute_table);
						  }

/* $CheckBlockedTable_Sql ="SELECT id_attribute_table,sum(total_qty) as total_qty, sum(total_adj_qty) as total_adj_qty 
	FROM `pos_purch`
	 WHERE `pos_bill_type` = 1 
	 and cancelled!=1 
	 and (DATE(date_created) BETWEEN '".$POSCurrentStartDate."' and '".$POSCurrentEndDate."' ) 
	 and doc_type!='24' and total_qty-total_adj_qty>0 and doc_type='".$_SESSION['id_document']."' GROUP BY id_attribute_table";
 //$CheckBlockedTable_Sql = "SELECT id_attribute_table FROM pos_purch WHERE pos_bill_type='1' and cancelled=0 AND doc_type!='24' AND id IN (SELECT id_pos_purch as posid FROM pos_purch_details WHERE qty-adj_qty>0)";

	                   $db->query($CheckBlockedTable_Sql); 
                     $ResultBlockedtable=array();
	                  while($ResultBlockedtable1 = $db->fetch_object()){ 

                      array_push($ResultBlockedtable,$ResultBlockedtable1->id_attribute_table);
	                  }
*/
$sqlRoomNumber = mysqli_query($connNew,"SELECT DISTINCT 
room.id,room.room_no,room.id_mst_room_types,room.room_status,resdetails.id_fo_reservations,
resdetails.id_mst_guest,resdetails.id_fo_folio_to ,resdetails.id_fo_bill,
resdetails.order_by_room,
fo_bill.status as occupanyStatus
FROM `mst_room_no_allocation` as room 
INNER JOIN fo_reservations_details as resdetails ON room.id=resdetails.id_mst_room_no_allocation 
INNER JOIN fo_bill as fo_bill ON fo_bill.id=resdetails.id_fo_bill 
WHERE fo_bill.status='1'  and resdetails.`checkout_status`='0' and  resdetails.`no_showoff`='0'  and resdetails.`room_availability`='Checkin'");
$ResultRoomId=array();
$ResultGuestId=array();
if(mysqli_num_rows($sqlRoomNumber) >0 ) {
  while($rowRoomNumbers= mysqli_fetch_object($sqlRoomNumber)) {
    $ResultRoomId[]	=	$rowRoomNumbers->id;
    $ResultGuestId[] = $rowRoomNumbers->id_mst_guest;
  }
}
$resContact = selectSql(TBL_ATTRIBUTES," where id_shop='".$_SESSION['shop']."'  AND id_table_group='".$table_group."' AND  status = '1' AND table_name ='".'table'."' ",' ORDER BY LPAD(lower(`field_value`),6,0)');
if($db->num_rows2($resContact) > 0){
	

	?>

<div class="col-md-12 mt-10">
  <div class="form-group">
    <label class="tablelabel" for="name">Table <font color="#FF0000">*</font> </label>
    <form name="listingForm" action="" method="post">
      
      <!-- /.box-header -->
      
      <div class="box-body table-responsive" style="padding-left: 0px;padding-top: 0px;padding-right: 3px;">
        <div id="TableListview">
          <table id="myTableTest" class="table table-fixed table-striped table-bordered dataTable no-footer" cellspacing="0"  >
            <tbody>
              <?php	

	$i=1;

		while($rowContact = $db->fetch_object2($resContact)){

			

						

    if($i==1){?>
              <tr>
                <?php } 

       if (in_array($rowContact->id, $ResultBlockedtable))
 		{
      if ($type == "room") {
        if (in_array($rowContact->id_mst_room_no, $ResultRoomId)) {
          $index = array_search($rowContact->id_mst_room_no, $ResultRoomId);
          $guest_id = $ResultGuestId[$index];
          $GuestName	=	selectColumn("mst_guest",'first_name'," WHERE `id` = '".$guest_id."'");
          $lastName	=	selectColumn("mst_guest",'last_name'," WHERE `id` = '".$guest_id."'");
          $id_mst_attributes_title	=	selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$guest_id."'");
          $Title=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title."'");
        if (in_array($rowContact->id, $Resultdoc_type)){
          // PreviousOrder(this.id); ?>
                        <td style="flex-direction : column;" class="btn tableviewBlockedbtn" onclick="TabeleSelect(this.id); PreviousOrderPax(this.id);" id="TableGroup_<?php echo $rowContact->id ?>_<?php echo $rowContact->field_value;?>"><?php echo $rowContact->field_value;?><br /><div style="font-size : 12px!important; white-space : break-spaces; height : 50px;"><?php echo $GuestName!=''?$Title.' '.$GuestName.' '.$lastName:'' ?></div></td>
                        <?php }else{ ?>
                        <td style=" background-color:#c6574b;flex-direction : column;" class="btn tableviewBlockedbtn"   id="TableGroup_<?php echo $rowContact->id ?>_<?php echo $rowContact->field_value;?>"><?php echo $rowContact->field_value;?><br /><div style="font-size : 12px!important; white-space : break-spaces; height : 50px;"><?php echo $GuestName!=''?$Title.' '.$GuestName.' '.$lastName:'' ?></div></td>
                        <?php }
        }
      } else {
			if (in_array($rowContact->id, $Resultdoc_type)){
	// PreviousOrder(this.id); ?>
                <td style="" class="btn tableviewBlockedbtn" onclick="TabeleSelect(this.id); PreviousOrderPax(this.id);" id="TableGroup_<?php echo $rowContact->id ?>_<?php echo $rowContact->field_value;?>"><?php echo $rowContact->field_value;?></td>
                <?php }else{ ?>
                <td style=" background-color:#c6574b;" class="btn tableviewBlockedbtn"   id="TableGroup_<?php echo $rowContact->id ?>_<?php echo $rowContact->field_value;?>"><?php echo $rowContact->field_value;?></td>
                <?php }
      }
  

   }

else

  {

	if ($type == "room") {
	if (in_array($rowContact->id_mst_room_no, $ResultRoomId)) {
		$index = array_search($rowContact->id_mst_room_no, $ResultRoomId);
		$guest_id = $ResultGuestId[$index];
		$GuestName	=	selectColumn("mst_guest",'first_name'," WHERE `id` = '".$guest_id."'");
		$lastName	=	selectColumn("mst_guest",'last_name'," WHERE `id` = '".$guest_id."'");
		$id_mst_attributes_title	=	selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$guest_id."'");
		$Title=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title."'");
    ?>

                <td style="flex-direction : column;" class="btn tableviewbtn" onclick="TabeleSelect(this.id); PreviousOrderPax(this.id);" id="TableGroup_<?php echo $rowContact->id ?>_<?php echo $rowContact->field_value;?>"><?php echo $rowContact->field_value;?><br /><div style="font-size : 12px!important; white-space : break-spaces; height : 50px;"><?php echo $GuestName!=''?$Title.' '.$GuestName.' '.$lastName:'' ?></div></td>
                <?php
				}
  			} else {
				?>
				<td style="flex-direction : column;" class="btn tableviewbtn" onclick="TabeleSelect(this.id); PreviousOrderPax(this.id);" id="TableGroup_<?php echo $rowContact->id ?>_<?php echo $rowContact->field_value;?>"><?php echo $rowContact->field_value;?><br /><div style="font-size : 12px!important; white-space : break-spaces; height : 50px;"></div></td>
				<?php
			}
		}

                      ?>
                <?php if($i==5){ $i=1;?>
              </tr>
              <?php }else{ $i++; } ?>
              <?php

														

																										

		}?>
            </tbody>
          </table>
        </div>
      </div>
    </form>
  </div>
</div>
<?php

			}else{?>
<div class="col-md-12 mt-10">
  <div class="form-group">
    <label class="tablelabel" for="name">Table <font color="#FF0000">*</font> </label>
    <form name="listingForm" action="" method="post">
      
      <!-- /.box-header -->
      
      <div class="box-body table-responsive" style="padding:0px;">
        <table id="myTableTest" class="table table-fixed table-striped table-bordered dataTabletest no-footer" cellspacing="0"  >
          <tbody>
            <tr>
              <td style="width:40%!important;margin:15px">    
                <?php
                if ($type == "room") {
                    echo "No Room Available";
                } else {
                    echo "No ".$firstRecord->field_value." Assigned";
                }
                ?> </td>
            </tr>
          </tbody>
        </table>
      </div>
    </form>
  </div>
</div>
<?php }

?>
