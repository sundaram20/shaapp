	
<?php
	include_once("../../config/auto_loader.php");
?>
    <div class="col-xs-12">
		<!--  <div class="col-md-3 c-box2" style="margin-top:10px;">
			<input type="submit" value="Go To Bill" class="btn btn-block  o-btn" name="Billing" ></input>
		</div>-->

	<script>  
		$(document).ready(function(){
			$('.clickmenu').hide();
			$('.hidemenu').show();
		});	

		function showHint(clicked_id, qty, old_qty){
			//alert();
			var substr = clicked_id.split('-');
			var str = substr[0];
			var pos_purch_details_id = substr[1];
			var check_id = $('#detail_status-'+pos_purch_details_id).is(':checked');
			
			if(check_id){
				var status = '1';
			}else{
				var status = '0';
			}
			
			if(clicked_id.length==0){
				document.getElementById("txtHint").innerHTML='';
				return;
			}else{
				 $('.hidemenu').hide();
				$('.clickmenu').show();
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if(this.readyState == 4 && this.status == 200){
						document.getElementById("txtHint").innerHTML = this.responseText;
					}
				};
				xmlhttp.open("GET", "ajax/ajaxGetkotwise.php?qty=" + qty + "&id=" + pos_purch_details_id + "&status=" + status + "&old_qty=" + old_qty, true);
				xmlhttp.send();
			}
		}		
	</script>
	
        <div class="box pb-70">  
			<div class="row kw-cons">
				<div class="col-md-10 kw-leftbar">
					<?php 
					//$menudate = "DATE(`doc_date`) BETWEEN '".date('Y-m-d',strtotime('-3 days'))."' And '".date('Y-m-d')."'";
					//$menudate = "DATE(`doc_date`) BETWEEN '2022-08-05' And '".date('Y-m-d')."'";
					
					//$menudate_main = " AND DATE(`doc_date`) BETWEEN '".date('Y-m-d',strtotime('-3 days'))."' And '".date('Y-m-d')."'";
					//$menudate_main = " AND DATE(`doc_date`) BETWEEN '2022-08-05' And '".date('Y-m-d')."' ";
					
					if($_GET['search_name'] != ''){
						$searchDocumentType = " AND pos_purch.`mdoc_no` ='".addslashes($_GET['search_name'])."'";
					}
						
					if($_GET['id_attribute_table_search'] != ''){
						$searchDocumentType .= " AND pos_purch.`id_attribute_table` ='".addslashes($_GET['id_attribute_table_search'])."'";
					}
						
					if($_GET['fstatus'] == 'Ready'){
						//$searchDocumentType .= " AND pos_purch_details.`check_orderis_ready`>0";
						$searchDocumentType .= " AND pos_purch.serve_status='2'";
					}else{
						$searchDocumentType .= " AND pos_purch.serve_status='0'";
						$searchDocumentType2 .= " and pos_purch_details.cook_status='0' ";
						}
					
					if($_GET['item'] != ''){
						$searchDocumentType .= " AND inv_items.id='".$_GET['item']."'";
					}
					
					if($_GET['order'] == 'Newest'){
						$order = "order by pos_purch.`last_modified` desc" ;
					}elseif($_GET['order'] == 'Oldest'){
						$order = "order by pos_purch.`last_modified` asc"; 
					}elseif($_GET['order'] == 'Top Priority'){
						$order = "order by pos_purch.`last_modified` desc";
					}else{
						$order = "order by pos_purch.`last_modified` desc";
					}
		//	$SQL="select pp.*,ppp.*,ppp.id_mst_items ,ppp.item_description,ppp.qty,ppp.id as id_pos_purch_details,ppp.old_qty,ppp.check_orderis_ready from pos_purch pp left join pos_purch_details ppp on ppp.id_pos_purch=pp.id where id_shop= '2' AND pp.pos_bill_type=1 AND pp.doc_type=22 and (ppp.qty-ppp.adj_qty)>0 and cancelled!=1
					//";			
					$SQL = "select pos_purch_details.id as id_pos_purch_details, pos_purch.*,pos_purch_details.*, sum(pos_purch_details.qty) as max_qty, pos_purch.doc_date,mst_attributes.field_value, inv_items.id_mst_attributes_group_main from pos_purch_details join pos_purch on pos_purch.id = pos_purch_details.id_pos_purch join inv_items on inv_items.id = pos_purch_details.id_mst_items join mst_attributes on inv_items.id_mst_attributes_group_main = mst_attributes.id where pos_purch.cancelled!=1 and pos_purch.id_shop= '2' AND pos_purch.pos_bill_type=1 AND pos_purch.doc_type=22 and (pos_purch_details.qty-pos_purch_details.adj_qty)>0  $searchDocumentType  group by inv_items.id order by mst_attributes.field_value ASC";/*
					$SQL="SELECT *  from
( select pp.*, ppp.id_mst_items ,ppp.item_description,ppp.qty,ppp.id as id_pos_purch_details,ppp.old_qty,ppp.check_orderis_ready,
	   (case  when COALESCE(pp.cancelled)=1 then 'cancelled'
	   		  when COALESCE(ppp.qty-ppp.adj_qty)>0 then 'Pending'
	         when COALESCE(ppp.qty-ppp.adj_qty)=0 then 'Billed' end) as kot_status
 
 from pos_purch pp right join pos_purch_details ppp on ppp.id_pos_purch=pp.id 
 where id_shop= '".addslashes($_SESSION['shop'])."' AND pp.pos_bill_type=1 AND pp.doc_type=22 
 $searchDocumentType  $order 
 ) as managekotlist WHERE id!=0 ".$menudate_main." 
";*/

				//	echo $SQL;

						$SqlKotList = mysqli_query($connNew, $SQL); 
						$numRows =	mysqli_num_rows($SqlKotList);
 $TableDetails['serve_status']='';
						$i=1;
						$listPrintArray=array();
						$listprintHeaderArray=array();
						$pendingKotArray=array();
						while($row = mysqli_fetch_object($SqlKotList)){ 
											  
							$table_name=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'table' AND id= '".$row->id_attribute_table."'"); 
							$shift_name=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'shift' AND id= '".$row->id_attribute_shift."'"); 
							$steward_name=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'steward' AND id= '".$row->id_attribute_steward."'"); 
							$row->mdoc_no;
							$datetime = new DateTime($row->date_created);
							$time = $datetime->format('h:i A');
							
											
							$pendingKotArray[$row->mdoc_no]['table_name']=$table_name;
							$pendingKotArray[$row->mdoc_no]['steward_name']=$steward_name;	
							$pendingKotArray[$row->mdoc_no]['mdoc_no']=$row->mdoc_no;	
							$pendingKotArray[$row->mdoc_no]['time']=$time;
							$pendingKotArray[$row->mdoc_no]['serve_status']=$row->serve_status;	
							$pendingKotArray[$row->mdoc_no]['id_pos_purch']=$row->id_pos_purch;	
							
							$qty = $row->qty;
							$old_qty = $row->old_qty;
							$check_orderis_ready = (int)$row->check_orderis_ready;
							
							if($check_orderis_ready>0){
								$checked = "checked";
							}else{
								$checked = "";
							}
							if($qty=='0' || $old_qty>'0'){
								$tot_qty = $old_qty;
							}else{
								$tot_qty = $qty;
							}
							if($row->cook_status==0){
								$checked = "";
							}else{
								$checked = "checked";
							}
							$listPrintArray[$row->mdoc_no][$row->id_pos_purch_details]['item_description']=$row->item_description;
							$listPrintArray[$row->mdoc_no][$row->id_pos_purch_details]['id_pos_purch_details']=$row->id_pos_purch_details;
							$listPrintArray[$row->mdoc_no][$row->id_pos_purch_details]['check_orderis_ready']=$row->check_orderis_ready;
							$listPrintArray[$row->mdoc_no][$row->id_pos_purch_details]['qty']=$row->qty;
							$listPrintArray[$row->mdoc_no][$row->id_pos_purch_details]['old_qty']=$old_qty;
							$listPrintArray[$row->mdoc_no][$row->id_pos_purch_details]['checked']=$checked;
							$listPrintArray[$row->mdoc_no][$row->id_pos_purch_details]['tot_qty']=$tot_qty;
							$listPrintArray[$row->mdoc_no][$row->id_pos_purch_details]['KotNo']=$row->mdoc_no;
							$listPrintArray[$row->mdoc_no][$row->id_pos_purch_details]['id_attribute_table']=$row->id_attribute_table;
							} 
							// debugData($pendingKotArray);
							foreach($pendingKotArray as $Table=>$TableDetails){ 
						?>			  
			  
		        	 	<!--tab col starts-->
						<div class="col-md-2 kw-con">
						  <div class="tab-container">
						    <div class="tabbox">
						      <div class="tabheading" id="checkcont">
						        <div class=" d-flex">
							          <div class="tbsteward">
								            <div class="statusbox">
									           <!-- <div class="d-flex ">
									            	<h4><button class="kotwiseModal"  id="kwstatus" ><?php echo $row->kot_status; ?></button></h4>
									            	<input type="hidden" id="pos_id" value="<?php echo $row->id; ?>"> 
												</div>-->
								            </div>
								            <h4 TITLE="Table"><?php echo $TableDetails['table_name']; ?></h4> <h4 title="Time"><?php echo $TableDetails['time']; ?></h4>     
							          </div>
						         </div>        
						      </div>
						      <!--table heading ends-->
						      <div class="tabcontent" id="kwbox" > 
						        <table class="table table-responsive table table-striped table-bordered dataTable no-footer songs-table">
									<thead style="display:none;">
										<tr>
										  <th>  Items Name</th>
										  <th style="width:10%;"> Qty </th>
										</tr>
									</thead>
									<tbody>
										<?php
//debugData($listPrintArray);
										foreach($listPrintArray as $Dataset=>$TableData){
										   if($Table== $Dataset){ ?>
										 
										<?php 
											 foreach($TableData as $value){
										
											/*$item_sql = "select * from pos_purch_details where id_pos_purch=".$row->id;
											$item_SqlKotList = mysqli_query($connNew, $item_sql); 
											$numRows=	mysqli_num_rows($item_SqlKotList);
											$i=1;
											while($item_row = mysqli_fetch_object($item_SqlKotList)){*/
											
												$qty = (int)$value['qty'];
												$check_orderis_ready = $value['check_orderis_ready'];
											$old_qty = $value['old_qty'];
												
												/*if($check_orderis_ready>0){
													$checked = "checked";
												}else{
													$checked = "";
												}
												if($qty=='0' && $old_qty>'0'){
													$tot_qty = $old_qty;
												}else{
													$tot_qty = $qty;
												}*/
										?>
										<tr>
											<td><span><?php echo (int)$value['tot_qty'].'x'; ?> </span>&nbsp; <?php echo $value['item_description']; ?> 
												 </td>
								            
											<td style="width:50px;">
												<label class="switchCheck">
													<input type="checkbox" <?php echo $value['checked']; ?> class="check_class" value="" id="detail_status-<?php echo $value['id_pos_purch_details']; ?>" name="detail_status" onclick="showHint(this.id,<?php echo $value['tot_qty']; ?>,<?php echo $value['old_qty']; ?>)"><span class="slider round"></span>
													<input type="hidden"  value="<?php echo $value['id_pos_purch_details']; ?>" class="check">
												</label>
											</td>
											
											<!--<td style="width:50px;">
												<label class="switchCheck">
													<input type="checkbox"  value="" id="detail_status-<?php echo $value['id_pos_purch_details']; ?>" checked="checked" name="detail_status" onclick="test(this.id);">
													<input type="hidden"  value="<?php echo $value['id_pos_purch_details']; ?>" class="check">
													<span class="slider round"></span>
												</label>
											</td>
										</tr>-->
										   <?php }}} ?>		
										
									</tbody>
						        </table>
						      </div>

						      <!--tabcontent ends-->
						        <div class="tabheading" id="checkcont2">
						        <div class=" d-flex" id="checkboxs">
						          <div class="tbsteward">
						            <h4 title="Steward"><?php echo $TableDetails['steward_name']; ?></h4> <h4 title="KOT"><?php echo '#'.$TableDetails['mdoc_no']; ?></h4>   
						          </div>
						           
						          <div class="tbname">
						           <input type="checkbox" name="serve_status" id="serve_status" onClick="CheckServerStatus('<?php echo $TableDetails['mdoc_no']; ?>','<?php echo $TableDetails['id_pos_purch']; ?>');" <?php echo $TableDetails['serve_status']=='2'?'checked=checked':''; ?> >
						          </div>

						          </div>
						         
						      </div>
						      <!--table heading ends-->
						    </div>
						  </div>
						</div>
			        	<!--tab col ends-->
							<?php  } //echo "select "; ?>	
	        	</div>
				
				<div id="txtHint" class="clickmenu"> </div>

				<div class="hidemenu">		
					<?php
					// $MSQL = "select *, sum(qty) as max_qty,pos_purch.doc_date from pos_purch_details join pos_purch on pos_purch.id = pos_purch_details.id_pos_purch where $menudate group by item_description order by max_qty desc";
						
					$MSQL = "select pos_purch_details.*, sum(pos_purch_details.qty) as max_qty, pos_purch.doc_date,mst_attributes.field_value, inv_items.id_mst_attributes_group_main from pos_purch_details join pos_purch on pos_purch.id = pos_purch_details.id_pos_purch join inv_items on inv_items.id = pos_purch_details.id_mst_items join mst_attributes on inv_items.id_mst_attributes_group_main = mst_attributes.id where pos_purch.cancelled!=1 and pos_purch.id_shop= '2' AND pos_purch.pos_bill_type=1 AND pos_purch.doc_type=22 and (pos_purch_details.qty-pos_purch_details.adj_qty)>0  group by inv_items.id_mst_attributes_group_main order by mst_attributes.field_value ASC";
					
						$MSqlKotList = mysqli_query($connNew, $MSQL); 
						$numRows =	mysqli_num_rows($MSqlKotList);
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
									$listPrintArra1y[$Mrow->inv_items][$Mrow->id_mst_items]['max_qty']= round($Mrow->max_qty);
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
									/*$MSQL1 = "select pos_purch_details.*, sum(pos_purch_details.qty) as max_qty, pos_purch.doc_date, inv_items.id_mst_attributes_group_main from pos_purch_details join pos_purch on pos_purch.id = pos_purch_details.id_pos_purch join inv_items on pos_purch_details.id_mst_items = inv_items.id where $menudate and inv_items.id_mst_attributes_group_main=".$value1['id_main_menu']." and pos_purch_details.qty>0 group by pos_purch_details.item_description order by max_qty desc";*/
									
									//$MSQL1 = "select pos_purch_details.*, sum(pos_purch_details.qty) as max_qty, pos_purch.doc_date, inv_items.id_mst_attributes_group_main,mst_attributes.field_value from pos_purch_details join pos_purch on pos_purch.id = pos_purch_details.id_pos_purch join inv_items on pos_purch_details.id_mst_items = inv_items.id join mst_attributes on inv_items.id_mst_attributes_group_main = mst_attributes.id where $menudate and inv_items.id_mst_attributes_group_main=".$value1['id_main_menu']." and pos_purch_details.qty>0 group by pos_purch_details.item_description order by mst_attributes.field_value ASC";
								$MSQL1 = "select pos_purch_details.*, sum(pos_purch_details.qty) as max_qty, pos_purch.doc_date, inv_items.id_mst_attributes_group_main from pos_purch_details   join pos_purch on pos_purch.id = pos_purch_details.id_pos_purch join inv_items on pos_purch_details.id_mst_items = inv_items.id where pos_purch.cancelled!=1 and pos_purch.id_shop= '2' AND pos_purch.pos_bill_type=1 AND pos_purch.doc_type=22 and (pos_purch_details.qty-pos_purch_details.adj_qty)>0   $searchDocumentType $searchDocumentType2 and inv_items.id_mst_attributes_group_main=".$value1['id_main_menu']." and pos_purch_details.qty>0 group by pos_purch_details.item_description order by max_qty desc";	
									$MSqlKotList1 = mysqli_query($connNew, $MSQL1); 
									while($Mrow1 = mysqli_fetch_object($MSqlKotList1)){
								?>
									<tr>
										<td id="<?php echo $Mrow1->item_description; ?>" onclick="item_test(<?php echo $Mrow1->id_mst_items; ?>);"><?php echo $Mrow1->item_description; ?></td>
										<td><span><?php echo round($Mrow1->max_qty); ?></span></td>
									</tr>
								<?php } ?>	
									
									
								</tbody>
								
								<?php } } } ?>
							</table>
							
						</div>
					</div>	
				</div>
			</div>
		<!--END OF ROW-->  
		</div>
		
      <br><br><br>
      
    </div>