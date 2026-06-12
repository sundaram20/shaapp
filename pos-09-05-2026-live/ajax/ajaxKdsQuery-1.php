<?php 
//debugData($_REQUEST);


$POSCurrentStartDate = date('d-m-Y',strtotime("-3 day", strtotime(date('d-m-Y'))));
$POSCurrentEndDate 	= 	date('Y-m-d');

$DateFilter ="and (DATE(pp.date_created) BETWEEN '".$POSCurrentStartDate."' and '".$POSCurrentEndDate."' )";
if($_GET['search_name'] != ''){
						$searchPurch = " AND `mdoc_no` ='".addslashes($_GET['search_name'])."'";
					}
						
					if($_GET['id_attribute_table_search'] != ''){
						$searchPurch .= " AND `id_attribute_table` ='".addslashes($_GET['id_attribute_table_search'])."'";
					}
					
					/*if($_GET['outlet'] != ''){
						$searchDocumentType .= " AND ppp.`id_mst_outlet` ='".addslashes($_GET['outlet'])."'";
					}*/
					
					if($_GET['printer'] != ''){
						$searchDocumentType .= " AND inv.id_mst_attributes_printer ='".addslashes($_GET['printer'])."'";
					}	
					if($_GET['fstatus'] == 'Ready'){
						//$searchDocumentType .= " AND pos_purch_details.`check_orderis_ready`>0";
						$searchPurchDetails .= " AND serve_status='2'";
						$disabled ="disabled='disabled'";//$disable='disable="disabled"';
						$ServedText='Served';
						$servestatus='2';
					}else{
						$searchPurchDetails .= " AND serve_status='0'";
						$ServedText='Serve';
						$servestatus='0';
						}
					
					if($_GET['item'] != ''){
						$searchDocumentType .= " AND inv_items.id='".$_GET['item']."'";
					}
					
					if($_GET['order'] == 'Newest'){
						$order = " order by `date_created` desc" ;
					}elseif($_GET['order'] == 'Oldest'){
						$order = " order by `date_created` asc"; 
					}elseif($_GET['order'] == 'Top Priority'){
						$order = " order by `date_created` desc";
					}else{
						$order = " order by `date_created` desc";
					}
					
					if($_GET['id_mst_items'] != ''){
					$searchPurchDetails .= " AND id_mst_items='".$_GET['id_mst_items']."' and cook_status='0'";
					}
					if($_SESSION['outlet_access']!=''){
					$searchPurchDetails	.="  AND `id_mst_outlet` IN ('".addslashes($_SESSION['outlet_access'])."')";
					}
					if($_SESSION['userPrinter']!=''){
					$outletFilterPurch	="  AND inv.`id_mst_attributes_printer` IN (".addslashes($_SESSION['userPrinter']).")";
					}


					  $SQL1="select pp.*,*,ppp.id_mst_items ,ppp.item_description,ppp.qty,ppp.id as id_pos_purch_details 
					  from pos_purch pp 
					  left join pos_purch_details ppp ON ppp.id_pos_purch=pp.id
					  LEFT JOIN inv_items inv ON inv.id=ppp.id_mst_items
					  
					   where pp.id_shop= '2' AND pp.pos_bill_type=1 AND pp.doc_type=22 and (ppp.qty-ppp.adj_qty)>0 and pp.cancelled!=1 $DateFilter $searchDocumentType $outletFilterPurch $order
					";


					$POSCurrentStartDate = date('d-m-Y',strtotime("-3 day", strtotime(date('d-m-Y'))));
					$POSCurrentEndDate 	= 	date('Y-m-d');
				   $SQL ="SELECT pos_purch.*,id_attribute_table,sum(total_qty) as total_qty, sum(total_adj_qty) as total_adj_qty FROM `pos_purch` WHERE `pos_bill_type` = 1 and cancelled!=1 and (DATE(date_created) BETWEEN '".$POSCurrentStartDate."' and '".$POSCurrentEndDate."' ) and doc_type='22' and total_qty-total_adj_qty>0 $searchPurch GROUP BY mdoc_no $order ";



					//echo  $SQL;
					
						$SqlKotList = mysqli_query($connNew, $SQL); 
						$numRows =	mysqli_num_rows($SqlKotList);

						$i=1;
						$listPrintArray = array();
						$listprintHeaderArray = array();
						$pendingKotArray = array();
						$itemlistArray=array();
						while($row = mysqli_fetch_object($SqlKotList)){ 
						
						$SQL_purch_details ="SELECT * FROM `pos_purch_details` WHERE id_pos_purch='".$row->id."' ".$searchPurchDetails;
			

							$Query_purch_details = mysqli_query($connNew, $SQL_purch_details); 
				 
						   while($Result_purch_details = mysqli_fetch_object($Query_purch_details)){ 

							$table_name=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'table' AND id= '".$row->id_attribute_table."'"); 
							$shift_name=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'shift' AND id= '".$row->id_attribute_shift."'"); 
							$steward_name=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'steward' AND id= '".$row->id_attribute_steward."'"); 
							
							
								 
						
						
						
							$row->mdoc_no;
							$datetime = new DateTime($row->date_created);
							$time = $datetime->format('h:i A');
							$qty = $Result_purch_details->qty;
							$old_qty = $Result_purch_details->old_qty;
							$check_orderis_ready = (int)$Result_purch_details->check_orderis_ready;
							if($qty=='0' || $old_qty>'0'){
								$tot_qty = $old_qty;
							}else{
								$tot_qty = $qty;
							}
							
							if($Result_purch_details->cook_status==0){
								$checked = "";
								$checkBoxServed='0';							
							
							}else{
								$checked = "checked";
								$checkBoxServed='1';
								
							}
							if($Result_purch_details->serve_status==0){								
								$checkServedStatus="";							
							}else{								
								$checkServedStatus="checked";
							}	
							
						if($_SESSION['shop_code']=='icon' || $_SESSION['shop_code']=='demo'){
								$SetEnable	='1';
								}else{
									$SetEnable	='0';
									}	
							
							
							
							$pendingKotArray[$row->mdoc_no][$Result_purch_details->id_mst_outlet]['table_name']=$table_name;
							$pendingKotArray[$row->mdoc_no][$Result_purch_details->id_mst_outlet]['steward_name']=$steward_name;	
							$pendingKotArray[$row->mdoc_no][$Result_purch_details->id_mst_outlet]['mdoc_no']=$row->mdoc_no;	
							$pendingKotArray[$row->mdoc_no][$Result_purch_details->id_mst_outlet]['time']=$time;	
							$pendingKotArray[$row->mdoc_no][$Result_purch_details->id_mst_outlet]['id_pos_purch']=$Result_purch_details->id_pos_purch;
							$pendingKotArray[$row->mdoc_no][$Result_purch_details->id_mst_outlet]['served_status']=$Result_purch_details->serve_status;
							$pendingKotArray[$row->mdoc_no][$Result_purch_details->id_mst_outlet]['checkServedStatus']=$checkServedStatus;
							$pendingKotArray[$row->mdoc_no][$Result_purch_details->id_mst_outlet]['PreparingStatus']=$row->kot_preparing;
							$pendingKotArray[$row->mdoc_no][$Result_purch_details->id_mst_outlet]['doc_enable_status']=$SetEnable;

								
							
							$pendingKotArray[$row->mdoc_no][$Result_purch_details->id_mst_outlet]['item'][$Result_purch_details->id]['item_description']=$Result_purch_details->item_description;
							$pendingKotArray[$row->mdoc_no][$Result_purch_details->id_mst_outlet]['item'][$Result_purch_details->id]['id_pos_purch_details']=$Result_purch_details->id;
							$pendingKotArray[$row->mdoc_no][$Result_purch_details->id_mst_outlet]['item'][$Result_purch_details->id]['check_orderis_ready']=$Result_purch_details->check_orderis_ready;
							$pendingKotArray[$row->mdoc_no][$Result_purch_details->id_mst_outlet]['item'][$Result_purch_details->id]['qty']=$Result_purch_details->qty;
							$pendingKotArray[$row->mdoc_no][$Result_purch_details->id_mst_outlet]['item'][$Result_purch_details->id]['old_qty']=$old_qty;
							$pendingKotArray[$row->mdoc_no][$Result_purch_details->id_mst_outlet]['item'][$Result_purch_details->id]['checked']=$checked;
							$pendingKotArray[$row->mdoc_no][$Result_purch_details->id_mst_outlet]['item'][$Result_purch_details->id]['tot_qty']=$tot_qty;
							$pendingKotArray[$row->mdoc_no][$Result_purch_details->id_mst_outlet]['item'][$Result_purch_details->id]['KotNo']=$row->mdoc_no;
							$pendingKotArray[$row->mdoc_no][$Result_purch_details->id_mst_outlet]['item'][$Result_purch_details->id]['CookStatus']=$Result_purch_details->cook_status;
							$pendingKotArray[$row->mdoc_no][$Result_purch_details->id_mst_outlet]['item'][$Result_purch_details->id]['special_request_name']=$Result_purch_details->item_special_request;
							
							//$pendingKotArray[$row->mdoc_no][$row->id_mst_outlet]['item'][$row->id_pos_purch_details]['checkBoxServed']=$checkBoxServed;
							
							
							 $id_main_menu = selectColumn('inv_items','id_mst_attributes_group_main'," WHERE  status = '1' AND id= '".$Result_purch_details->id_mst_items."'"); 
							 $main_menu_name = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE  status = '1' and `table_name` = 'item_group_main' AND id= '".$id_main_menu."'"); 
							if($Result_purch_details->cook_status==0){
								$itemlistArray[$main_menu_name][$Result_purch_details->id_mst_items]['item_description']=$Result_purch_details->item_description;
								$itemlistArray[$main_menu_name][$Result_purch_details->id_mst_items]['main_menu_name']=$row->field_value;
								$itemlistArray[$main_menu_name][$Result_purch_details->id_mst_items]['id_main_menu']=$id_main_menu;
								$itemlistArray[$main_menu_name][$Result_purch_details->id_mst_items]['max_qty'] += round($Result_purch_details->qty);
								$itemlistArray[$main_menu_name][$Result_purch_details->id_mst_items]['id_pos_purch']=$Result_purch_details->id_pos_purch;
							}
							} 
						}
							//debugData($itemlistArray);
?>