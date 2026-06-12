			
<?php

include_once("../../config/auto_loader.php");

$select_qty = $_GET["qty"];
$pos_purch_details_id = $_GET["id"];
$status = $_GET["status"];
$old_qty = $_GET["old_qty"];


$select = "SELECT * From `".TBL_PURCH_DETAILS."` WHERE id = '".$pos_purch_details_id."'  ";
	$selectt = mysqli_query($connNew, $select);	
		while($selecttt = mysqli_fetch_object($selectt)){
		   $qty = $selecttt -> qty;
		   $row_old_qty = $selecttt -> old_qty;
		   $check_orderis_ready = $selecttt -> check_orderis_ready;
		   $total =  $qty - $select_qty;
				$final =  number_format($total,2);
		}

if($status=='0'){ 
	$updatePurch = executeSql("UPDATE `".TBL_PURCH_DETAILS."`  SET  `cook_status` = '0'	where  `id`='".$pos_purch_details_id."' ");
	//$updatePurch = "UPDATE `".TBL_PURCH_DETAILS."`  SET  `check_orderis_ready` = '0', qty='".$row_old_qty."', old_qty='".$select_qty."'	where  `id`='".$pos_purch_details_id."' ";
}else{
	$updatePurch = executeSql("UPDATE `".TBL_PURCH_DETAILS."`  SET  `cook_status` = '1' where  `id`='".$pos_purch_details_id."' ");
	//$updatePurch = "UPDATE `".TBL_PURCH_DETAILS."`  SET  `check_orderis_ready` = '".$select_qty."', `qty` = '".$final."', old_qty='".$select_qty."' where  `id`='".$pos_purch_details_id."' ";
}
//echo "Reserved Successfully";

//$menudate = "DATE(`doc_date`) BETWEEN '".date('Y-m-d',strtotime('-3 days'))."' And '".date('Y-m-d')."'";
$menudate = "DATE(`doc_date`) BETWEEN '2022-08-05' And '".date('Y-m-d')."'";


//$MSQL = "select pos_purch_details.*, sum(pos_purch_details.qty) as max_qty, pos_purch.doc_date, inv_items.id_mst_attributes_group_main from pos_purch_details join pos_purch on pos_purch.id = pos_purch_details.id_pos_purch join inv_items on inv_items.id = pos_purch_details.id_mst_items where $menudate group by inv_items.id_mst_attributes_group_main order by max_qty desc";

$MSQL = "select pos_purch_details.*, sum(pos_purch_details.qty) as max_qty, pos_purch.doc_date,mst_attributes.field_value, inv_items.id_mst_attributes_group_main from pos_purch_details join pos_purch on pos_purch.id = pos_purch_details.id_pos_purch join inv_items on inv_items.id = pos_purch_details.id_mst_items join mst_attributes on inv_items.id_mst_attributes_group_main = mst_attributes.id where pos_purch.cancelled!=1 and pos_purch.id_shop= '2' AND pos_purch.pos_bill_type=1 AND pos_purch.doc_type=22 and (pos_purch_details.qty-pos_purch_details.adj_qty)>0 and pos_purch.serve_status=1 and pos_purch_details.cook_status='0' group by inv_items.id_mst_attributes_group_main order by mst_attributes.field_value ASC";

	
	$MSqlKotList = mysqli_query($connNew, $MSQL); 
	$numRows=	mysqli_num_rows($MSqlKotList);
	
?>				
	        		<div id="kwbox2" class="col-md-2 kw-sidebar floating">
						<div class="kw-box">
						
							<table class="table table-responsive sidebar-h">
								<thead>
									<th width="90%">Menu Itemwise</th>
									<th>Qty</th>
									
								</thead>
							</table>
							
							<?php
								$listPrintArra1y=array();
								
								while($Mrow = mysqli_fetch_object($MSqlKotList)){ 
								//id_shop='".$_SESSION['shop']."'
								$id_main_menu = selectColumn('inv_items','id_mst_attributes_group_main'," WHERE  status = '1' AND id= '".$Mrow->id_mst_items."'"); 
								$main_menu_name = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE  status = '1' and `table_name` = 'item_group_main' AND id= '".$id_main_menu."'"); 
									$listPrintArra1y[$Mrow->inv_items][$Mrow->id_mst_items]['item_description']=$Mrow->item_description;
									$listPrintArra1y[$Mrow->inv_items][$Mrow->id_mst_items]['main_menu_name']=$Mrow->field_value;
									$listPrintArra1y[$Mrow->inv_items][$Mrow->id_mst_items]['id_main_menu']=$id_main_menu;
									$listPrintArra1y[$Mrow->inv_items][$Mrow->id_mst_items]['max_qty']=round($Mrow->max_qty);
									$listPrintArra1y[$Mrow->inv_items][$Mrow->id_mst_items]['id_pos_purch']=$Mrow->id_pos_purch;
								}
								//debugData($listPrintArra1y);
							?>
							
							<table class="table table-responsive">
								<?php  
									foreach($listPrintArra1y as $Datasett=>$TableData1){
									if($Table1== $Datasett){
									foreach($TableData1 as $value1){
								?>
								<thead>
									<th colspan="2" class="text-center"><?php echo $value1['main_menu_name']; ?></th>
								</thead>
								
								<tbody>
								
								<?php
									$MSQL1 = "select pos_purch_details.*, sum(pos_purch_details.qty) as max_qty, pos_purch.doc_date, inv_items.id_mst_attributes_group_main from pos_purch_details   join pos_purch on pos_purch.id = pos_purch_details.id_pos_purch join inv_items on pos_purch_details.id_mst_items = inv_items.id where pos_purch.cancelled!=1 and pos_purch.id_shop= '2' AND pos_purch.pos_bill_type=1 AND pos_purch.doc_type=22 and (pos_purch_details.qty-pos_purch_details.adj_qty)>0  and inv_items.id_mst_attributes_group_main=".$value1['id_main_menu']." and pos_purch.serve_status='0' and pos_purch_details.cook_status='0' group by pos_purch_details.item_description order by max_qty desc";
									
									$MSqlKotList1 = mysqli_query($connNew, $MSQL1); 
									while($Mrow1 = mysqli_fetch_object($MSqlKotList1)){
								?>
									<tr>
										<td><?php echo $Mrow1->item_description; ?></td>
										<td><span><?php echo round($Mrow1->max_qty); ?></span></td>
									</tr>
								<?php } ?>	
									
									
								</tbody>
								
								<?php } } } ?>
							</table>
							
						</div>
					</div>	
	
	        	