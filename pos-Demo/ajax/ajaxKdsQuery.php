<?php 
//debugData($_REQUEST);

$POSCurrentStartDate = date('d-m-Y',strtotime("-3 day", strtotime(date('d-m-Y'))));
$POSCurrentEndDate 	= 	date('Y-m-d');

$DateFilter ="and (DATE(pp.date_created) BETWEEN '".$POSCurrentStartDate."' and '".$POSCurrentEndDate."' )";
if($_GET['search_name'] != ''){
						$searchDocumentType = " AND pp.`mdoc_no` ='".addslashes($_GET['search_name'])."'";
					}
						
					if($_GET['id_attribute_table_search'] != ''){
						$searchDocumentType .= " AND pp.`id_attribute_table` ='".addslashes($_GET['id_attribute_table_search'])."'";
					}
					
					/*if($_GET['outlet'] != ''){
						$searchDocumentType .= " AND ppp.`id_mst_outlet` ='".addslashes($_GET['outlet'])."'";
					}*/
					
					if($_GET['printer'] != ''){
						$searchDocumentType .= " AND inv.id_mst_attributes_printer ='".addslashes($_GET['printer'])."'";
					}	
					if($_GET['fstatus'] == 'Ready'){
						//$searchDocumentType .= " AND pos_purch_details.`check_orderis_ready`>0";
						$searchDocumentType .= " AND ppp.serve_status='2'";
						$disabled ="disabled='disabled'";//$disable='disable="disabled"';
						$ServedText='Served';
						$servestatus='2';
					}else{
						$searchDocumentType .= " AND ppp.serve_status='0'";
						$ServedText='Serve';
						$servestatus='0';
						}
					
					if($_GET['item'] != ''){
						$searchDocumentType .= " AND inv_items.id='".$_GET['item']."'";
					}
					
					if($_GET['order'] == 'Newest'){
						$order = " order by pp.`date_created` desc" ;
					}elseif($_GET['order'] == 'Oldest'){
						$order = " order by pp.`date_created` asc"; 
					}elseif($_GET['order'] == 'Top Priority'){
						$order = " order by pp.`date_created` desc";
					}else{
						$order = " order by pp.`date_created` desc";
					}
					
					if($_GET['id_mst_items'] != ''){
					$searchDocumentType .= " AND ppp.id_mst_items='".$_GET['id_mst_items']."' and ppp.cook_status='0'";
					}
					if($_SESSION['outlet_access']!=''){
					$outletFilterPurch	="  AND ppp.`id_mst_outlet` IN ('".addslashes($_SESSION['outlet_access'])."')";
					}
					if($_SESSION['userPrinter']!=''){
					$outletFilterPurch	="  AND inv.`id_mst_attributes_printer` IN (".addslashes($_SESSION['userPrinter']).")";
					}


					  $SQL="select pp.*,ppp.*,ppp.id_mst_items ,ppp.item_description,ppp.qty,ppp.id as id_pos_purch_details 
					  from pos_purch pp 
					  left join pos_purch_details ppp ON ppp.id_pos_purch=pp.id
					  LEFT JOIN inv_items inv ON inv.id=ppp.id_mst_items
					  
					   where pp.id_shop= '2' AND pp.pos_bill_type=1 AND pp.doc_type=22 and (ppp.qty-ppp.adj_qty)>0 and pp.cancelled!=1 $DateFilter $searchDocumentType $outletFilterPurch $order
					";
					//echo  $SQL;
					
						$SqlKotList = mysqli_query($connNew, $SQL); 
						$numRows =	mysqli_num_rows($SqlKotList);

						$i=1;
						$listPrintArray = array();
						$listprintHeaderArray = array();
						$pendingKotArray = array();
						$itemlistArray=array();
						while($row = mysqli_fetch_object($SqlKotList)){ 
											  
							$table_name=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'table' AND id= '".$row->id_attribute_table."'"); 
							$shift_name=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'shift' AND id= '".$row->id_attribute_shift."'"); 
							$steward_name=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'steward' AND id= '".$row->id_attribute_steward."'"); 
							
							
							//$cook_status=selectColumn('pos_purch','cook_status'," WHERE  id= '".$row->id_pos_purch."' and cook_status='0' "); 
							$row->mdoc_no;
							$datetime = new DateTime($row->date_created);
							$time = $datetime->format('h:i A');
							$qty = $row->qty;
							$old_qty = $row->old_qty;
							$check_orderis_ready = (int)$row->check_orderis_ready;
							if($qty=='0' || $old_qty>'0'){
								$tot_qty = $old_qty;
							}else{
								$tot_qty = $qty;
							}
							
							if($row->cook_status==0){
								$checked = "";
								$checkBoxServed='0';							
							
							}else{
								$checked = "checked";
								$checkBoxServed='1';
								
							}
							if($row->serve_status==0){								
								$checkServedStatus="";							
							}else{								
								$checkServedStatus="checked";
							}	
							
						if($_SESSION['shop_code']=='icon' || $_SESSION['shop_code']=='demo'){
								$SetEnable	='1';
								}else{
									$SetEnable	='0';
									}	
							
							
							
							$pendingKotArray[$row->mdoc_no][$row->id_mst_outlet]['table_name']=$table_name;
							$pendingKotArray[$row->mdoc_no][$row->id_mst_outlet]['steward_name']=$steward_name;	
							$pendingKotArray[$row->mdoc_no][$row->id_mst_outlet]['mdoc_no']=$row->mdoc_no;	
							$pendingKotArray[$row->mdoc_no][$row->id_mst_outlet]['time']=$time;	
							$pendingKotArray[$row->mdoc_no][$row->id_mst_outlet]['id_pos_purch']=$row->id_pos_purch;
							$pendingKotArray[$row->mdoc_no][$row->id_mst_outlet]['served_status']=$row->serve_status;
							$pendingKotArray[$row->mdoc_no][$row->id_mst_outlet]['checkServedStatus']=$checkServedStatus;
							$pendingKotArray[$row->mdoc_no][$row->id_mst_outlet]['PreparingStatus']=$row->kot_preparing;
							$pendingKotArray[$row->mdoc_no][$row->id_mst_outlet]['doc_enable_status']=$SetEnable;

								
							
							$pendingKotArray[$row->mdoc_no][$row->id_mst_outlet]['item'][$row->id_pos_purch_details]['item_description']=$row->item_description;
							$pendingKotArray[$row->mdoc_no][$row->id_mst_outlet]['item'][$row->id_pos_purch_details]['id_pos_purch_details']=$row->id_pos_purch_details;
							$pendingKotArray[$row->mdoc_no][$row->id_mst_outlet]['item'][$row->id_pos_purch_details]['check_orderis_ready']=$row->check_orderis_ready;
							$pendingKotArray[$row->mdoc_no][$row->id_mst_outlet]['item'][$row->id_pos_purch_details]['qty']=$row->qty;
							$pendingKotArray[$row->mdoc_no][$row->id_mst_outlet]['item'][$row->id_pos_purch_details]['old_qty']=$old_qty;
							$pendingKotArray[$row->mdoc_no][$row->id_mst_outlet]['item'][$row->id_pos_purch_details]['checked']=$checked;
							$pendingKotArray[$row->mdoc_no][$row->id_mst_outlet]['item'][$row->id_pos_purch_details]['tot_qty']=$tot_qty;
							$pendingKotArray[$row->mdoc_no][$row->id_mst_outlet]['item'][$row->id_pos_purch_details]['KotNo']=$row->mdoc_no;
							$pendingKotArray[$row->mdoc_no][$row->id_mst_outlet]['item'][$row->id_pos_purch_details]['CookStatus']=$row->cook_status;
							$pendingKotArray[$row->mdoc_no][$row->id_mst_outlet]['item'][$row->id_pos_purch_details]['special_request_name']=$row->item_special_request;
							
							//$pendingKotArray[$row->mdoc_no][$row->id_mst_outlet]['item'][$row->id_pos_purch_details]['checkBoxServed']=$checkBoxServed;
							
							
							 $id_main_menu = selectColumn('inv_items','id_mst_attributes_group_main'," WHERE  status = '1' AND id= '".$row->id_mst_items."'"); 
							 $main_menu_name = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE  status = '1' and `table_name` = 'item_group_main' AND id= '".$id_main_menu."'"); 
							if($row->cook_status==0){
								$itemlistArray[$main_menu_name][$row->id_mst_items]['item_description']=$row->item_description;
								$itemlistArray[$main_menu_name][$row->id_mst_items]['main_menu_name']=$row->field_value;
								$itemlistArray[$main_menu_name][$row->id_mst_items]['id_main_menu']=$id_main_menu;
								$itemlistArray[$main_menu_name][$row->id_mst_items]['max_qty'] += round($row->qty);
								$itemlistArray[$main_menu_name][$row->id_mst_items]['id_pos_purch']=$row->id_pos_purch;
							}
							} 
?>