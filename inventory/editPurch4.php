<?php 
include_once("../config/auto_loader.php");

if($_REQUEST['eId']=='') 
 	checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_PURCH,'add');
else
	checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_PURCH,'edit');

$image_path = $UPLOAD_FILES.'/hotel_gallery/';

$image_display_path = $UPLOAD_FILES_PATH ."/hotel_gallery/";

//Main Concept Here
	$doc_type = $_GET['doc_type'];
	if($doc_type == 4){
		$table_doc_type = "4";
		$redirect_page="manageGRN.php?submenu=".$_GET['submenu']."&session=".$_GET['session']."&doc_type=".$_GET['doc_type']." ";
		$print_redirect_page = "printGRN.php";
	}elseif($doc_type == 5){
		$table_doc_type = "5";
		$redirect_page="managePurch.php?submenu=".$_GET['submenu']."&session=".$_GET['session']."&doc_type=".$_GET['doc_type']." ";
		$print_redirect_page ="printPurchasebill.php";
	}elseif($doc_type == 12){
		$table_doc_type = "12";
		$redirect_page="managePurch.php?submenu=".$_GET['submenu']."&session=".$_GET['session']."&doc_type=".$_GET['doc_type']." ";
		$print_redirect_page= "printGRN.php";
	}

	
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){

	$err = 0;
	
	
	//Insert Here
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Save') && empty($_POST['eId'])){//add 

			 checkUserLevelPermission($_SESSION['userLevel'], TBL_INV_PURCH,'add');
			 //Indent No Check Here
			 $doc_no = $_POST['doc_no'];
			 
			 $doc= $_GET['doc_type'];
			 
			 if($doc != '12'){
				 $sql5 = " SELECT * FROM `".TBL_INV_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_no`='".$doc_no."'  and `doc_type` = '4'  ";
			 }else{
				 $sql5 = " SELECT * FROM `".TBL_INV_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_no`='".$doc_no."'  and `doc_type` = '12'  ";
			 }
//echo $sql5;
			 
				$db->query($sql5);
				$numRows= $db->num_rows();
					if($numRows > 0)   {
						while($row5 = $db->fetch_object()){ 
							$doc_no= $row5->doc_no; 
							$doc_no = $doc_no+1; 
						} 
					}else{
						 $doc_no = $_POST['doc_no'];
					}

			 //Values Add Here

			if($_POST['prefix'] !='' OR $_POST['suffix'] !=''){
				$mdoc_no = $_POST['prefix'].''.$doc_no.''.$_POST['suffix'];
			}else{
				$mdoc_no = $_POST['mdoc_no'];
			}
			//PO Table Section Here
			
			
$itemDetailSizeOf=	sizeof($_POST['id_inv_poo']);
 for($i=0;$i<$itemDetailSizeOf;$i++){
        $id_po .= $_REQUEST['id_inv_poo'][$i].',';
 }		
		
 $id_inv_poo = rtrim($id_po,',');
			
			$addSql = " INSERT INTO `".TBL_INV_PURCH."` SET
							`doc_type` = '".addslashes($_POST['doc_type'])."', 
							`doc_no` = '".addslashes($doc_no)."',  
							`id_inv_po` = '".addslashes($id_inv_poo)."',  
							`doc_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['doc_date1'])))."',  
							`supplier_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['supplier_date'])))."',  
							`supplier_ref_no` = '".addslashes($_POST['supplier_ref_no'])."',     
							`id_mst_party_supplier` = '".addslashes($_POST['id_mst_party_supplier1'])."',     
							`id_mst_party_billtobe` = '".addslashes($_POST['id_mst_party_billtobe'])."',     
							`credit_days` = '".addslashes($_POST['credit_days'])."',     
							`id_doc_type_configuration` = '".addslashes($_POST['id_doc_type_configuration'])."',     
							`mdoc_no` = '".addslashes($mdoc_no)."',
							`sgst_net_amount` = '".addslashes($_POST['sgst_net_amount'])."',
							`oc_sgst_total` = '".addslashes($_POST['oc_sgst_total'])."',
							`cgst_net_amount` = '".addslashes($_POST['cgst_net_amount'])."',
							`oc_cgst_total` = '".addslashes($_POST['oc_cgst_total'])."',
							`igst_net_amount` = '".addslashes($_POST['igst_net_amount'])."',
							`oc_igst_total` = '".addslashes($_POST['oc_igst_total'])."',
							`id_mst_charges_discounts_items` = '".addslashes($_POST['id_mst_charges_discounts_items'])."',
							`discount_percent_items` = '".addslashes($_POST['discount_percent_items'])."',
							`sub_total_items` = '".addslashes($_POST['sub_total_items'])."',
							`total_discount_items` = '".addslashes($_POST['total_discount_items'])."',
							`others_charges_net_amount` = '".addslashes($_POST['others_charges_net_amount'])."',
							`disc_amount_additional` = '".addslashes($_POST['disc_amount_additional'])."',
							`disc_amount_additional1` = '".addslashes($_POST['disc_amount_additional1'])."',
							`net_amount_items` = '".addslashes($_POST['net_amount_items'])."',
							`net_amount` = '".addslashes($_POST['net_amount'])."',
							`round_off_amount` = '".addslashes($_POST['round_off_amount'])."',
							`base_currency_code` = '".addslashes($_POST['base_currency_code1'])."',
							`transaction_currency_code` = '".addslashes($_POST['transaction_currency_code1'])."',
							`exchange_rate` = '".addslashes($_POST['exchange_rate'])."',
							`sub_total_items1` = '".addslashes($_POST['sub_total_items1'])."',
							`sgst1` = '".addslashes($_POST['sgst1'])."',
							`cgst1` = '".addslashes($_POST['cgst1'])."',
							`igst1` = '".addslashes($_POST['igst1'])."',
							`total_discount_items1` = '".addslashes($_POST['total_discount_items1'])."',
							`others_charges_net_amount1` = '".addslashes($_POST['others_charges_net_amount1'])."',
							`sgst2` = '".addslashes($_POST['sgst2'])."',
							`cgst2` = '".addslashes($_POST['cgst2'])."',
							`igst2` = '".addslashes($_POST['igst2'])."',
							`oc_sgst1` = '".addslashes($_POST['oc_sgst1'])."',
							`oc_sgst2` = '".addslashes($_POST['oc_sgst2'])."',
							`oc_cgst1` = '".addslashes($_POST['oc_cgst1'])."',
							`oc_cgst2` = '".addslashes($_POST['oc_cgst2'])."',
							`oc_igst1` = '".addslashes($_POST['oc_igst1'])."',
							`oc_igst2` = '".addslashes($_POST['oc_igst2'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`status` = '".addslashes($_POST['status'])."'";
							
							
					executeSql($addSql);

							$lastInsertId= $db->insert_id();

					//PO Details Table Here Detault Value Set
  						if($_POST["id_inv_po"] == 'na'){
  							$id_inv_po = 0;
  							$type = 0;
  						}else{
  							$id_inv_po = $_POST["id_inv_po"];
  							$type = 1;
  						}

  						//Qty And Alt Qty Calcuations
  						$per_unit = $_POST["per_unit"]; 
  						$main_unit = $_POST["main_unit"];
  						$alt_unit = $_POST["alt_unit"];
  						$transaction_unit = $_POST["transaction_unit"];
  						$conver_rate_per_unit = $_POST['conver_rate_per_unit'];
  						$qty = $_POST["qty"];
  						$alt_qty = 0;
  						$rate_per_main_unit = $_POST['rate_per_main_unit'];
  						$rate_per_alt_unit = 0;

  						if($per_unit == $transaction_unit){

  							if($alt_unit == $transaction_unit){
  								$qty_total = ($qty/$conver_rate_per_unit); 
  								$alt_qty = $qty;
  								//Main Unit Section
  								$rate_per_main_unit = $rate_per_main_unit * $conver_rate_per_unit;
  								//Alt Unit
  								$rate_per_alt_unit = $_POST['rate_per_main_unit'];

  							}else{
					 			$qty_total = $qty;
					 			$alt_qty = $qty_total * $conver_rate_per_unit; 
					 			//Main Unit Section
					 			$rate_per_main_unit = $_POST['rate_per_main_unit'];
					 			//Alt Unit Section
					 			$rate_per_alt_unit = $rate_per_main_unit/$conver_rate_per_unit;
					 		}
					 	}
					 	else{
					 		if($main_unit == $transaction_unit){
					 			$qty_total = $qty;
					 			$alt_qty = $qty_total * $conver_rate_per_unit;
					 			
					 			if($per_unit == $alt_unit){

									$rate_per_main_unit = $_POST['rate_per_main_unit'];
  									$rate_per_main_unit = $rate_per_main_unit * $conver_rate_per_unit;

	  								//Alt Unit
  									$rate_per_alt_unit = $_POST['rate_per_main_unit'];
								}else{
									$rate_per_main_unit = $_POST['rate_per_main_unit'];
  									$rate_per_main_unit = $rate_per_main_unit;

	  								//Alt Unit
  									$rate_per_alt_unit = $_POST['rate_per_main_unit'];
								}
					 		}else{

								$qty_total = ($qty/$conver_rate_per_unit);
								$alt_qty = $qty;
								//Main Unit Section
								if($per_unit == $alt_unit){

									$rate_per_main_unit = $_POST['rate_per_main_unit'];
  									$rate_per_main_unit = $rate_per_main_unit * $conver_rate_per_unit;

	  								//Alt Unit
  									$rate_per_alt_unit = $_POST['rate_per_main_unit'];
								}else{
									$rate_per_main_unit = $_POST['rate_per_main_unit'];
  									$rate_per_main_unit = $rate_per_main_unit;

	  								//Alt Unit
  									$rate_per_alt_unit = $_POST['rate_per_main_unit']/$conver_rate_per_unit;
								}
					 		}
					 	} 
					 	//Goods Receipt Note
					 	if($_GET['doc_type'] == '5'){
					 		$id_goods_receipt_note = $_POST["id_inv_po_details"];
					 		$gsn_qty =selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)','WHERE id="'.$id_goods_receipt_note.'" and doc_type="4"');
					 		$balance_qty =($gsn_qty-$qty_total);
					 		$bal_qty_total =($gsn_qty-$qty_total);
					 	}else{
					 		$id_goods_receipt_note = 0;
					 		$bal_qty_total=$qty_total;
					 	}
						
				

				$addSql = " INSERT INTO `".TBL_INV_PURCH_DETAILS."` SET
							`id_inv_purch` = '".addslashes($lastInsertId)."',  
							`id_inv_po` = '".addslashes($id_inv_po )."', 
							`id_inv_po_details` = '".addslashes($_POST["id_inv_po_details"])."',
							`base_currency_code` = '".addslashes($_POST['base_currency_code1'])."',
							`transaction_currency_code` = '".addslashes($_POST['transaction_currency_code1'])."',
							`exchange_rate` = '".addslashes($_POST['exchange_rate'])."', 
							`doc_type` = '".addslashes($table_doc_type)."', 
							`id_mst_attributes_store` = '".addslashes($_POST['id_mst_attributes_store'])."', 
							`id_goods_receipt_note` = '".addslashes($id_goods_receipt_note)."', 
							`id_inv_items` = '".addslashes($_POST['id_inv_items'])."', 
							`id_mst_charges_sgst` = '".addslashes($_POST['id_mst_charges_sgst'])."', 
							`id_mst_charges_cgst` = '".addslashes($_POST['id_mst_charges_cgst'])."', 
							`id_mst_charges_igst` = '".addslashes($_POST['id_mst_charges_igst'])."', 
							`transaction_unit` = '".addslashes($_POST["transaction_unit"])."', 
							`qty` = '".addslashes($qty_total)."', 
							`bal_qty`='".addslashes($qty_total)."',
							`main_unit` = '".addslashes($_POST["main_unit"])."', 
							`per_unit` = '".addslashes($_POST["per_unit"])."', 
							`alt_unit` = '".addslashes($_POST["alt_unit"])."', 
							`alt_qty` = '".addslashes($alt_qty)."', 
							`conver_rate_per_unit` = '".addslashes($_POST['conver_rate_per_unit'])."', 
							`id_mst_charges_purchase_local` = '".addslashes($_POST['id_mst_charges_purchase_local'])."', 
							`id_mst_charges_purchase_interstate` = '".addslashes($_POST['id_mst_charges_purchase_interstate'])."', 
							`rate_per_main_unit` = '".addslashes($rate_per_main_unit)."', 
							`rate_per_alt_unit` = '".addslashes($rate_per_alt_unit)."', 
							`item_amount_before_discount` = '".addslashes($_POST['item_amount_before_discount'])."', 
							`discount_percent` = '".addslashes($_POST['discount_percent'])."', 
							`discount_amount` = '".addslashes($_POST['discount_amount'])."', 
							`item_sgst_percent` = '".addslashes($_POST['item_sgst_percent'])."', 
							`item_sgst_amount` = '".addslashes($_POST['item_sgst_amount'])."', 
							`item_cgst_percent` = '".addslashes($_POST['item_cgst_percent'])."', 
							`item_cgst_amount` = '".addslashes($_POST['item_cgst_amount'])."', 
							`item_igst_percent` = '".addslashes($_POST['item_igst_percent'])."', 
							`item_igst_amount` = '".addslashes($_POST['item_igst_amount'])."', 
							`item_amount` = '".addslashes($_POST['item_amount'])."', 
							`item_remarks` = '".addslashes($_POST['item_remarks'])."',  
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$addSql .= "	,`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`status` = '".addslashes($_POST['status'])."'";
							//echo $addSql.'<br>';
						executeSql($addSql);

						// updating GRN	
						if($_REQUEST['doc_type']=='5'){
							$updateGRN = "UPDATE ".TBL_INV_PURCH_DETAILS." SET ordered_qty='".$qty_total."',bal_qty='".$balance_qty."' WHERE id='".$id_goods_receipt_note."' AND doc_type='4' ";
							
						executeSql($updateGRN);
						}	
						// GRN update end

						if($type == 1 && $_GET['doc_type'] != '5'){
						//Order Qty Check Here
							$order_total= 0;$balance_qty =0;
							$id_inv_po_details = $_POST["id_inv_po_details"];
							$sql1 = "SELECT sum(qty) as qty FROM `".TBL_INV_PURCH_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_po_details`='".$id_inv_po_details."'  ";
		                   	$db->query($sql1);
		                    $row1 = $db->fetch_object();
		                    $order_total = $row1->qty;
						//Total Qty Get
						$total_qty=selectColumn(TBL_INV_PO_DETAILS,'qty'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$id_inv_po_details."' ");
						$balance_qty = $total_qty - $order_total;
						//Order Qty Update Indent Details Table
						
							
						
						 $editSql = "UPDATE `".TBL_INV_PO_DETAILS."` SET `ordered_qty` = '".$order_total."', `bal_qty` = '".$balance_qty."' WHERE `id` = '".addslashes($id_inv_po_details)."'";
						 
						
					executeSql($editSql); 
						}
						//PO Details Table Here For Loop Value Set
							$counter1 = $_POST['counter1'];


						for($i = 1; $i <= $counter1; $i++){

								if($_POST['id_inv_po'.''.$i] == 'na'){
		  							$id_inv_po = 0;
		  							$type = 0;
		  						}else{
		  							$id_inv_po = $_POST['id_inv_po'.''.$i];
		  							$type = 1;
		  						}

		  						//Qty And Alt Qty Calcuations
  						$per_unit = $_POST["per_unit".''.$i]; 
  						$main_unit = $_POST["main_unit".''.$i];
  						$alt_unit = $_POST["alt_unit".''.$i];
  						$transaction_unit = $_POST["transaction_unit".''.$i];
  						$conver_rate_per_unit = $_POST['conver_rate_per_unit'.''.$i];
  						$qty = $_POST["qty".''.$i];
  						$alt_qty = 0;
  						$rate_per_main_unit = $_POST['rate_per_main_unit'.''.$i];
  						$rate_per_alt_unit = 0;

  						if($per_unit == $transaction_unit){

  							if($alt_unit == $transaction_unit){
  								$qty_total = ($qty/$conver_rate_per_unit); 
  								$alt_qty = $qty;
  								//Main Unit Section
  								$rate_per_main_unit = $rate_per_main_unit * $conver_rate_per_unit;
  								//Alt Unit
  								$rate_per_alt_unit = $_POST['rate_per_main_unit'.''.$i];

  							}else{
					 			$qty_total = $qty;
					 			$alt_qty = $qty_total * $conver_rate_per_unit; 
					 			//Main Unit Section
					 			$rate_per_main_unit = $_POST['rate_per_main_unit'.''.$i];
					 			//Alt Unit Section
					 			$rate_per_alt_unit = $rate_per_main_unit/$conver_rate_per_unit;
					 		}
					 	}
					 	else{
					 		if($main_unit == $transaction_unit){
					 			$qty_total = $qty;
					 			$alt_qty = $qty_total * $conver_rate_per_unit;
					 			
					 			if($per_unit == $alt_unit){

									$rate_per_main_unit = $_POST['rate_per_main_unit'.''.$i];
  									$rate_per_main_unit = $rate_per_main_unit * $conver_rate_per_unit;

	  								//Alt Unit
  									$rate_per_alt_unit = $_POST['rate_per_main_unit'.''.$i];
								}else{
									$rate_per_main_unit = $_POST['rate_per_main_unit'.''.$i];
  									$rate_per_main_unit = $rate_per_main_unit;

	  								//Alt Unit
  									$rate_per_alt_unit = $_POST['rate_per_main_unit'.''.$i];
								}
					 		}else{

								$qty_total = ($qty/$conver_rate_per_unit);
								$alt_qty = $qty;
								//Main Unit Section
								if($per_unit == $alt_unit){

									$rate_per_main_unit = $_POST['rate_per_main_unit'.''.$i];
  									$rate_per_main_unit = $rate_per_main_unit * $conver_rate_per_unit;

	  								//Alt Unit
  									$rate_per_alt_unit = $_POST['rate_per_main_unit'.''.$i];
								}else{
									$rate_per_main_unit = $_POST['rate_per_main_unit'.''.$i];
  									$rate_per_main_unit = $rate_per_main_unit;

	  								//Alt Unit
  									$rate_per_alt_unit = $_POST['rate_per_main_unit'.''.$i]/$conver_rate_per_unit;
								}

					 		}
					 	}

					 	//Goods Receipt Note
					 	if($_GET['doc_type'] == '5'){
					 		$balance_qty=0;
					 		$id_goods_receipt_note = $_POST["id_inv_po_details".''.$i];

					 		//calculate ordered qty
					 		$gsn_qty =selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)','WHERE id="'.$_POST["id_inv_po_details".''.$i].'" and doc_type="4"');
					 						 		
					 		$balance_qty =($gsn_qty-$qty_total);
					 	}else{
					 		$id_goods_receipt_note = 0;
					 	}

						if($_POST['id_inv_po'.''.$i] != '' && $_POST['item_amount'.''.$i] >=1){

									$addSql = "INSERT INTO `".TBL_INV_PURCH_DETAILS."` SET

									`id_inv_purch` = '".addslashes($lastInsertId)."',  
									`id_inv_po` = '".addslashes($id_inv_po)."', 
									`id_inv_po_details` = '".addslashes($_POST["id_inv_po_details".''.$i])."',
									`base_currency_code` = '".addslashes($_POST['base_currency_code1'])."',
									`transaction_currency_code` = '".addslashes($_POST['transaction_currency_code1'])."',
									`exchange_rate` = '".addslashes($_POST['exchange_rate'])."',
									`doc_type` = '".addslashes($table_doc_type)."', 
									`id_mst_attributes_store` = '".addslashes($_POST['id_mst_attributes_store'.''.$i])."', 
									`id_goods_receipt_note` = '".addslashes($id_goods_receipt_note)."', 
									`id_inv_items` = '".addslashes($_POST['id_inv_items'.''.$i])."', 
									`id_mst_charges_sgst` = '".addslashes($_POST['id_mst_charges_sgst'.''.$i])."', 
									`id_mst_charges_cgst` = '".addslashes($_POST['id_mst_charges_cgst'.''.$i])."', 
									`id_mst_charges_igst` = '".addslashes($_POST['id_mst_charges_igst'.''.$i])."', 
									`transaction_unit` = '".addslashes($_POST["transaction_unit".''.$i])."', 
									`qty` = '".addslashes($qty_total)."',
									`bal_qty` = '".addslashes($qty_total)."',
									`main_unit` = '".addslashes($_POST["main_unit".''.$i])."', 
									`per_unit` = '".addslashes($_POST["per_unit".''.$i])."', 
									`alt_unit` = '".addslashes($_POST["alt_unit".''.$i])."', 
									`alt_qty` = '".addslashes($alt_qty)."',   
									`conver_rate_per_unit` = '".addslashes($_POST['conver_rate_per_unit'.''.$i])."', 
									`id_mst_charges_purchase_local` = '".addslashes($_POST['id_mst_charges_purchase_local'.''.$i])."', 
									`id_mst_charges_purchase_interstate` = '".addslashes($_POST['id_mst_charges_purchase_interstate'.''.$i])."', 
									`rate_per_main_unit` = '".addslashes($rate_per_main_unit)."', 
									`rate_per_alt_unit` = '".addslashes($rate_per_alt_unit)."',  
									`item_amount_before_discount` = '".addslashes($_POST['item_amount_before_discount'.''.$i])."', 
									`discount_percent` = '".addslashes($_POST['discount_percent'.''.$i])."', 
									`discount_amount` = '".addslashes($_POST['discount_amount'.''.$i])."', 
									`item_sgst_percent` = '".addslashes($_POST['item_sgst_percent'.''.$i])."', 
									`item_sgst_amount` = '".addslashes($_POST['item_sgst_amount'.''.$i])."', 
									`item_cgst_percent` = '".addslashes($_POST['item_cgst_percent'.''.$i])."', 
									`item_cgst_amount` = '".addslashes($_POST['item_cgst_amount'.''.$i])."', 
									`item_igst_percent` = '".addslashes($_POST['item_igst_percent'.''.$i])."', 
									`item_igst_amount` = '".addslashes($_POST['item_igst_amount'.''.$i])."',   
									`item_amount` = '".addslashes($_POST['item_amount'.''.$i])."', 
									`item_remarks` = '".addslashes($_POST['item_remarks'.''.$i])."', 
									`id_shop` = '".addslashes($_SESSION['shop'])."'";

									$addSql .= "	,`date_created` = '".currenDateTime()."',
									`last_modified` = '".currenDateTime()."',
									`id_mst_user_modified_by` = '".$_SESSION['userId']."',
									`id_mst_user_created_by` = '".$_SESSION['userId']."',
									`status` = '".addslashes($_POST['status'])."'";
								//echo $addSql.'<br>';	
									
								executeSql($addSql);

								
								// updating GRN	
								if($_REQUEST['doc_type']=='5'){
								$updateGRN = "UPDATE ".TBL_INV_PURCH_DETAILS." SET ordered_qty='".$qty_total."',bal_qty='".$balance_qty."' WHERE id='".$id_goods_receipt_note."' AND doc_type='4' "  ; 
								
								executeSql($updateGRN);
								}	
								// GRN update end	

								if($type == 1 && $_GET['doc_type'] != '5'){
									//Order Qty Check Here
									$order_total= 0;$balance_qty =0;
									$id_inv_po_details = $_POST["id_inv_po_details".''.$i];
									 $sql1 = "SELECT sum(qty) as qty FROM `".TBL_INV_PURCH_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_po_details`='".$id_inv_po_details."'  ";
				                   	$db->query($sql1);
				                    $row1 = $db->fetch_object();
				                    $order_total = $row1->qty;
									//Total Qty Get
										$total_qty=selectColumn(TBL_INV_PO_DETAILS,'qty'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$id_inv_po_details."' ");
										$balance_qty = $total_qty - $order_total;
										
								
									//Order Qty Update Indent Details Table
									 $editSql = "UPDATE `".TBL_INV_PO_DETAILS."` SET `ordered_qty` = '".$order_total."', `bal_qty` = '".$balance_qty."' WHERE `id` = '".addslashes($id_inv_po_details)."'";
								
							executeSql($editSql);  
									}
								}
							}

						//Others Table Section Here
							$addSql = "   	INSERT INTO `".TBL_INV_OTHERS_CHARGES_PURCH."` SET

							`id_inv_purch` = '".addslashes($lastInsertId)."', 
							`type` = '".addslashes($_POST['type'])."', 
							`id_mst_charges_others` = '".addslashes($_POST['id_mst_charges_others'])."', 
							`id_mst_charges_discounts` = '".addslashes($_POST['id_mst_charges_discounts'])."', 
							`others_discount_percent` = '".addslashes($_POST['others_discount_percent'])."', 
							`others_discount_amount` = '".addslashes($_POST['others_discount_amount'])."', 
							`others_charges_sgst_percent` = '".addslashes($_POST['others_charges_sgst_percent'])."', 
							`others_charges_sgst_amount` = '".addslashes($_POST['others_charges_sgst_amount'])."', 
							`others_charges_cgst_percent` = '".addslashes($_POST['others_charges_cgst_percent'])."', 
							`others_charges_cgst_amount` = '".addslashes($_POST['others_charges_cgst_amount'])."', 
							`others_charges_igst_percent` = '".addslashes($_POST['others_charges_igst_percent'])."', 
							`others_charges_igst_amount` = '".addslashes($_POST['others_charges_igst_amount'])."',  
							`others_charges_amount` = '".addslashes($_POST['others_charges_amount'])."', 
							`others_charges_percent` = '".addslashes($_POST['others_charges_percent'])."',  
							`total_amount_others` = '".addslashes($_POST['total_amount_others'])."',    
							`id_mst_charges_sgst_others` = '".addslashes($_POST['id_mst_charges_sgst_others'])."',    
							`id_mst_charges_cgst_others` = '".addslashes($_POST['id_mst_charges_cgst_others'])."',    
							`id_mst_charges_igst_others` = '".addslashes($_POST['id_mst_charges_igst_others'])."',    
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$addSql .= "	,`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`status` = '".addslashes($_POST['status'])."'";
							executeSql($addSql);

					//Others Tables Details Table Here For Loop Value Set
							$counter2 = $_POST['counter2'];

							for($i = 1; $i <= $counter2; $i++){

								if($_POST['type'.''.$i] != ''){

									$addSql = "   	INSERT INTO `".TBL_INV_OTHERS_CHARGES_PURCH."` SET

									`id_inv_purch` = '".addslashes($lastInsertId)."',  
									`type` = '".addslashes($_POST['type'.''.$i])."', 
									`id_mst_charges_others` = '".addslashes($_POST['id_mst_charges_others'.''.$i])."', 
									`id_mst_charges_discounts` = '".addslashes($_POST['id_mst_charges_discounts'.''.$i])."', 
									`others_discount_percent` = '".addslashes($_POST['others_discount_percent'.''.$i])."', 
									`others_discount_amount` = '".addslashes($_POST['others_discount_amount'.''.$i])."', 
									`others_charges_sgst_percent` = '".addslashes($_POST['others_charges_sgst_percent'.''.$i])."', 
									`others_charges_sgst_amount` = '".addslashes($_POST['others_charges_sgst_amount'.''.$i])."', 
									`others_charges_cgst_percent` = '".addslashes($_POST['others_charges_cgst_percent'.''.$i])."', 
									`others_charges_cgst_amount` = '".addslashes($_POST['others_charges_cgst_amount'.''.$i])."', 
									`others_charges_igst_percent` = '".addslashes($_POST['others_charges_igst_percent'.''.$i])."', 
									`others_charges_igst_amount` = '".addslashes($_POST['others_charges_igst_amount'.''.$i])."',
									`others_charges_amount` = '".addslashes($_POST['others_charges_amount'.''.$i])."',  
									`others_charges_percent` = '".addslashes($_POST['others_charges_percent'.''.$i])."', 
									`total_amount_others` = '".addslashes($_POST['total_amount_others'.''.$i])."',
									`id_mst_charges_sgst_others` = '".addslashes($_POST['id_mst_charges_sgst_others'.''.$i])."',    
									`id_mst_charges_cgst_others` = '".addslashes($_POST['id_mst_charges_cgst_others'.''.$i])."',    
									`id_mst_charges_igst_others` = '".addslashes($_POST['id_mst_charges_igst_others'.''.$i])."', 
									`id_shop` = '".addslashes($_SESSION['shop'])."'";

									$addSql .= "	,`date_created` = '".currenDateTime()."',
									`last_modified` = '".currenDateTime()."',
									`id_mst_user_modified_by` = '".$_SESSION['userId']."',
									`id_mst_user_created_by` = '".$_SESSION['userId']."',
									`status` = '".addslashes($_POST['status'])."'";
									executeSql($addSql);
								}
							} 

			if(1){
				$_SESSION['successMsg'] = 'New Details has been added sucessfully.';
				
				if($_POST['another']!=''){
					header("location:editPurch.php?submenu=".$_GET['submenu']."&session=".$_GET['session']."&doc_type=".$_GET['doc_type']."&print=1");	
				}else{
					header("location:editPurch.php?eId=".addslashes(encryptor(encrypt,$lastInsertId))."&submenu=".$_GET['submenu']."&session=".$_GET['session']."&action=edit&page=".$_REQUEST['page']."&doc_type=".$_REQUEST['doc_type']."&print=1");
				}
				
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = ' Details has not been saved. Please make corrections below.';
			}
		}

		//Update Section Here

		else if(($_POST['Save'] == 'Save') && !empty($_POST['eId'])){//update
		
		 
			checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_PURCH,'update');

			if($_POST['prefix'] !='' && $_POST['suffix'] !=''){
				$mdoc_no = $_POST['prefix'].''.$_POST['doc_no'].''.$_POST['suffix'];
			}else{
				$mdoc_no = $_POST['mdoc_no'];
			}
			
$itemDetailSizeOf=	sizeof($_POST['id_inv_poo']);
 for($i=0;$i<$itemDetailSizeOf;$i++){
        $id_po .= $_REQUEST['id_inv_poo'][$i].',';
 }		
		
 $id_inv_poo = rtrim($id_po,',');


if($_GET['doc_type']=='4'){
$sql12 = "SELECT * FROM inv_po WHERE id IN ($id_inv_poo) ";
		$res1 = mysqli_query($connNew,$sql12);
			while($row1 = mysqli_fetch_object($res1)){
				$no1 .=  $row1->doc_no.',';
				$no_1 .=  date('d-m-Y' , strtotime(addslashes($row1->doc_date)));
			}
		$no = rtrim($no1,',');	
		$no_11 = rtrim($no_1,',');	 
}else {
	$sql12 = "SELECT * FROM inv_purch WHERE id IN ($id_inv_poo) ";
		$res1 = mysqli_query($connNew,$sql12);
			while($row1 = mysqli_fetch_object($res1)){
				$no1 .=  $row1->doc_no.',';
				$no_1 .=  date('d-m-Y' , strtotime(addslashes($row1->doc_date)));
			}
		$no = rtrim($no1,',');	
		$no_11 = rtrim($no_1,',');
}			
			

				//Audit Data Insert Here
			 $auditquery = "SELECT * From `".TBL_INV_PURCH."` WHERE id = '".addslashes(encryptor(decrypt,$_POST[eId]))."'  ";

			  $auditresSQL = mysqli_query($connNew, $auditquery);	
				while($auditrow = mysqli_fetch_object($auditresSQL)){ 
				 
				  $id_inv_po = $auditrow ->id_inv_po; 
				  $exchange_rate = $auditrow ->exchange_rate; 
				  $supplier_ref_no = $auditrow ->supplier_ref_no; 
				  //$supplier_date = date('d-m-Y' , strtotime(addslashes($auditrow ->supplier_date))); 
				  $supplier_date = $auditrow ->supplier_date; 
				  $id_mst_charges_discounts_items = $auditrow ->id_mst_charges_discounts_items; 
				  			 
if($_GET['doc_type']=='4'){
					$sql122 = "SELECT * FROM inv_po WHERE id IN ($id_inv_po) ";
					$res12 = mysqli_query($connNew,$sql122);
						while($row11 = mysqli_fetch_object($res12)){
							$no2 .=  $row11->doc_no.',';
							$no_21 .=  date('d-m-Y' , strtotime(addslashes($row11->doc_date)));
						}
					$no21 = rtrim($no2,',');
					$no211 = rtrim($no_21,',');
}else{
	$sql122 = "SELECT * FROM inv_purch WHERE id IN ($id_inv_po) ";
					$res12 = mysqli_query($connNew,$sql122);
						while($row11 = mysqli_fetch_object($res12)){
							$no2 .=  $row11->doc_no.',';
							$no_21 .=  date('d-m-Y' , strtotime(addslashes($row11->doc_date)));
						}
					$no21 = rtrim($no2,',');
					$no211 = rtrim($no_21,',');
}

if($_GET['doc_type']=='4'){ $nam="Po"; }else if($_GET['doc_type']=='5'){ $nam="GRN"; }

if($id_inv_indent != $id_inv_poo){
	$ch_1 = $nam." No Changed from " . $no21 ." - to - ".$no ;
}


				 //Change Data
			    if($exchange_rate != $_POST['exchange_rate']){ 
					$ch1 = "Exchange Rate Changed from ". $exchange_rate." - to - " . $_POST['exchange_rate'];
				}
				
				if($supplier_ref_no != $_POST['supplier_ref_no']){ 
					if($supplier_ref_no==''){
						$ch2 = "Supplier Invoice/ref No Added to " . $_POST['supplier_ref_no'];
					}else{
						$ch2 = "Supplier Invoice/ref No Changed from ". $supplier_ref_no." - to - " . $_POST['supplier_ref_no'];
					}
				}
				
				if($_POST['id_mst_charges_discounts_items']!=''){
					$emp = $_POST['id_mst_charges_discounts_items'];
				}else{
					$emp = '0';
				}
				
				if($id_mst_charges_discounts_items != $emp){
					$old_data = selectColumn('mst_charges','name'," WHERE `id` = '".$id_mst_charges_discounts_items."'  AND charges_account = '6'");
					$new_data = selectColumn('mst_charges','name'," WHERE `id` = '".$_POST['id_mst_charges_discounts_items']."'  AND charges_account = '6' ");
					
					if($id_mst_charges_discounts_items =='0'){
						$ch4 = "Discount Scheme Apply Added to " . $new_data;
					}else{
						$ch4 = "Discount Scheme Apply Changed from ". $old_data." - to - " . $new_data;
					}
					
				}
				
				if($supplier_date != date('Y-m-d' , strtotime(addslashes($_POST['supplier_date'])))){
					$ch3 = "Supplier Date Changed from ". date('d-m-Y' , strtotime(addslashes($supplier_date)))." - to - " . date('d-m-Y' , strtotime(addslashes($_POST['supplier_date'])));
				}
				
			}
			
			
			
			 $editSql = "   	UPDATE `".TBL_INV_PURCH."`  SET  

							`doc_type` = '".addslashes($_POST['doc_type'])."',
							`id_inv_po` = '".addslashes($id_inv_poo)."', 								
							`doc_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['doc_date1'])))."',  
							`id_mst_party_supplier` = '".addslashes($_POST['id_mst_party_supplier1'])."',  
							`supplier_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['supplier_date'])))."',  
							`supplier_ref_no` = '".addslashes($_POST['supplier_ref_no'])."',         
							`id_mst_party_billtobe` = '".addslashes($_POST['id_mst_party_billtobe'])."',     
							`credit_days` = '".addslashes($_POST['credit_days'])."',     
							`id_doc_type_configuration` = '".addslashes($_POST['id_doc_type_configuration'])."',     
							`mdoc_no` = '".addslashes($mdoc_no)."',
							`sgst_net_amount` = '".addslashes($_POST['sgst_net_amount'])."',
							`oc_sgst_total` = '".addslashes($_POST['oc_sgst_total'])."',
							`cgst_net_amount` = '".addslashes($_POST['cgst_net_amount'])."',
							`oc_cgst_total` = '".addslashes($_POST['oc_cgst_total'])."',
							`igst_net_amount` = '".addslashes($_POST['igst_net_amount'])."',
							`oc_igst_total` = '".addslashes($_POST['oc_igst_total'])."',
							`id_mst_charges_discounts_items` = '".addslashes($_POST['id_mst_charges_discounts_items'])."',
							`discount_percent_items` = '".addslashes($_POST['discount_percent_items'])."',
							`sub_total_items` = '".addslashes($_POST['sub_total_items'])."',
							`total_discount_items` = '".addslashes($_POST['total_discount_items'])."',
							`others_charges_net_amount` = '".addslashes($_POST['others_charges_net_amount'])."',
							`disc_amount_additional` = '".addslashes($_POST['disc_amount_additional'])."',
							`net_amount_items` = '".addslashes($_POST['net_amount_items'])."',
							`net_amount` = '".addslashes($_POST['net_amount'])."',
							`round_off_amount` = '".addslashes($_POST['round_off_amount'])."',
							`base_currency_code` = '".addslashes($_POST['base_currency_code1'])."',
							`transaction_currency_code` = '".addslashes($_POST['transaction_currency_code1'])."',
							`exchange_rate` = '".addslashes($_POST['exchange_rate'])."',
							`sub_total_items1` = '".addslashes($_POST['sub_total_items1'])."',
							`sgst1` = '".addslashes($_POST['sgst1'])."',
							`cgst1` = '".addslashes($_POST['cgst1'])."',
							`igst1` = '".addslashes($_POST['igst1'])."',
							`total_discount_items1` = '".addslashes($_POST['total_discount_items1'])."',
							`others_charges_net_amount1` = '".addslashes($_POST['others_charges_net_amount1'])."',
							`disc_amount_additional1` = '".addslashes($_POST['disc_amount_additional1'])."',
							`sgst2` = '".addslashes($_POST['sgst2'])."',
							`cgst2` = '".addslashes($_POST['cgst2'])."',
							`igst2` = '".addslashes($_POST['igst2'])."',
							`oc_sgst1` = '".addslashes($_POST['oc_sgst1'])."',
							`oc_sgst2` = '".addslashes($_POST['oc_sgst2'])."',
							`oc_cgst1` = '".addslashes($_POST['oc_cgst1'])."',
							`oc_cgst2` = '".addslashes($_POST['oc_cgst2'])."',
							`oc_igst1` = '".addslashes($_POST['oc_igst1'])."',
							`oc_igst2` = '".addslashes($_POST['oc_igst2'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$editSql .= "	
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'
							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'";
							executeSql($editSql);

				//Update INV PO DETAILS Details
							if($_POST["id_inv_po"] == 'na'){
	  							$id_inv_po = 0;
	  							$type = 0;
	  						}else{
	  							$id_inv_po = $_POST["id_inv_po"];
	  							$type = 1;
	  						}
	  						//Qty And Alt Qty Calcuations
  						$per_unit = $_POST["per_unit"]; 
  						$main_unit = $_POST["main_unit"];
  						$alt_unit = $_POST["alt_unit"];
  						$transaction_unit = $_POST["transaction_unit"];
  						$conver_rate_per_unit = $_POST['conver_rate_per_unit'];
  						$qty = $_POST["qty"];
  						$alt_qty = 0;
  						$rate_per_main_unit = $_POST['rate_per_main_unit'];
  						$rate_per_alt_unit = 0;

  						if($per_unit == $transaction_unit){

  							if($alt_unit == $transaction_unit){
  								$qty_total = ($qty/$conver_rate_per_unit); 
  								$alt_qty = $qty;
  								//Main Unit Section
  								$rate_per_main_unit = $rate_per_main_unit * $conver_rate_per_unit;
  								//Alt Unit
  								$rate_per_alt_unit = $_POST['rate_per_main_unit'];

  							}else{
					 			$qty_total = $qty;
					 			$alt_qty = $qty_total * $conver_rate_per_unit; 
					 			//Main Unit Section
					 			$rate_per_main_unit = $_POST['rate_per_main_unit'];
					 			//Alt Unit Section
					 			$rate_per_alt_unit = $rate_per_main_unit/$conver_rate_per_unit;
					 		}
					 	}
					 	else{
					 		if($main_unit == $transaction_unit){
					 			$qty_total = $qty;
					 			$alt_qty = $qty_total * $conver_rate_per_unit;
					 			
					 			if($per_unit == $alt_unit){

									$rate_per_main_unit = $_POST['rate_per_main_unit'];
  									$rate_per_main_unit = $rate_per_main_unit * $conver_rate_per_unit;

	  								//Alt Unit
  									$rate_per_alt_unit = $_POST['rate_per_main_unit'];
								}else{
									$rate_per_main_unit = $_POST['rate_per_main_unit'];
  									$rate_per_main_unit = $rate_per_main_unit;

	  								//Alt Unit
  									$rate_per_alt_unit = $_POST['rate_per_main_unit'];
								}
					 		}else{

								$qty_total = ($qty/$conver_rate_per_unit);
								$alt_qty = $qty;
								//Main Unit Section
								if($per_unit == $alt_unit){

									$rate_per_main_unit = $_POST['rate_per_main_unit'];
  									$rate_per_main_unit = $rate_per_main_unit * $conver_rate_per_unit;

	  								//Alt Unit
  									$rate_per_alt_unit = $_POST['rate_per_main_unit'];
								}else{
									$rate_per_main_unit = $_POST['rate_per_main_unit'];
  									$rate_per_main_unit = $rate_per_main_unit;

	  								//Alt Unit
  									$rate_per_alt_unit = $_POST['rate_per_main_unit']/$conver_rate_per_unit;
								}
								

					 		}
					 	}
					 	//Goods Receipt Note
					 	if($_GET['doc_type'] == '5'){
					 		$id_goods_receipt_note = $_POST["id_inv_po_details"];

					 		$balance_qty=0;
					 		
					 		//calculate ordered qty
					 		$gsn_qty =selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)','WHERE id="'.$id_goods_receipt_note .'" and doc_type="4"');
					 						 		
					 		$balance_qty =($gsn_qty-$qty_total);
					 	}else{
					 		$id_goods_receipt_note = 0;
					 	}


					 	//Audit Files Select Here
					 	$auditquery = "SELECT * From `".TBL_INV_PURCH_DETAILS."` WHERE id = '".addslashes($_POST['update_id'])."'  ";

						  $auditresSQL = mysqli_query($connNew, $auditquery);	
							while($auditrow = mysqli_fetch_object($auditresSQL)){ 
							 
							  $id_inv_po_details = $auditrow ->id_inv_po_details; 
							  $qty = $auditrow ->qty; 
							  $main_unit = $auditrow ->main_unit;							 
							  $transaction_unit = $auditrow ->transaction_unit;					 
							  $store = $auditrow ->id_mst_attributes_store;					 
							  $rate_per_main_unit = $auditrow ->rate_per_main_unit;				 
							  $per_unit = $auditrow ->per_unit;							 
							  $discount_percent = $auditrow ->discount_percent;					 
							  $item_remarks = $auditrow ->item_remarks;							 
							  $id_mst_charges_purchase_local = $auditrow ->id_mst_charges_purchase_local;							 

							 //Change Data
						    if($id_inv_indent != $_POST["id_inv_po_details"]){
						    	
								//$id_inv_indent_s = "Inventory Indent Changed from ". $id_inv_indent." - to - " . $_POST["id_inv_po_details"].'  in Rowno 1 ';
							}
							if($qty != $qty_total){ 
							
								if($_POST["qty"] != ''){
									$indent_qty_s = "Qty Changed  from ". $qty." - to - " .$qty_total.'  in Rowno 1';
								}
							}
							if($main_unit != $_POST["main_unit"]){ 
								//$indent_main_unit_s = "Indent Main Unit Changed from ". $main_unit." - to - " .$_POST["main_unit"].' in Rowno 1';
							}
							if($transaction_unit != $_POST["transaction_unit"]){ 
							if($_POST["transaction_unit"]!=''){
								$indent_transaction_unit_s = " Unit Changed from ". $transaction_unit." - to - " .$_POST["transaction_unit"].' in Rowno 1 ';
							}
							}
							if($rate_per_main_unit != $_POST["rate_per_main_unit"]){
if($_POST["rate_per_main_unit"]!=''){								
								$indent_rate_per_main_unit_s = " Rate Changed from ". $rate_per_main_unit." - to - " .$_POST["rate_per_main_unit"];
							}
							}
							if($per_unit != $_POST["per_unit"]){
								if($_POST["id_mst_charges_purchase_local"] !=''){ 
								$indent_per_unit_s = " Per  Changed from ". $per_unit." - to - " .$_POST["per_unit"].' in Rowno 1 ';
							}
							}
							if($store != $_POST["id_mst_attributes_store"]){ 
								$old_data = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$store."'");
								$new_data = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$_POST['id_mst_attributes_store']."'  ");

								if($_POST["id_mst_attributes_store"] != ''){
								if($_POST["id_mst_charges_purchase_local"] !=''){
									$sto = "Store Changed from ". $old_data." - to - " .$new_data.' in Rowno 1 ';
								}
								}
								
							}
							if($discount_percent != $_POST["discount_percent"]){ 
								if($_POST["discount_percent"] !=''){
								$indent_discount_percent_s = " Discount  Changed from ". $discount_percent." - to - " .$_POST["discount_percent"].'  in  Rowno 1';
							}
							}
							if($item_remarks != $_POST["item_remarks"]){ 
								if($_POST["item_remarks"] !=''){
								$indent_item_remarks_s = " Remarks Changed from ". $item_remarks." - to - " .$_POST["item_remarks"].' in Rowno 1 ';
							}
							}
							if($id_mst_charges_purchase_local != $_POST["id_mst_charges_purchase_local"]){ 
								$old_data = selectColumn('mst_charges','name'," WHERE `id` = '".$id_mst_charges_purchase_local."'");
								$new_data = selectColumn('mst_charges','name'," WHERE `id` = '".$_POST['id_mst_charges_purchase_local']."'  ");
								if($_POST["id_mst_charges_purchase_local"] !=''){
								$indent_charge_purchase_s = "Tax Register Changed from ". $old_data." - to - " .$new_data.' in Rowno 1 ';
							}
							}
						}

							$editSql = "   	UPDATE `".TBL_INV_PURCH_DETAILS."`  SET  

							`id_inv_purch` = '".addslashes(encryptor(decrypt,$_POST[eId]))."', 
							`id_inv_po` = '".addslashes($id_inv_po )."',  
							`id_inv_po_details` = '".addslashes($_POST["id_inv_po_details"])."',
							`base_currency_code` = '".addslashes($_POST['base_currency_code1'])."',
							`transaction_currency_code` = '".addslashes($_POST['transaction_currency_code1'])."',
							`exchange_rate` = '".addslashes($_POST['exchange_rate'])."',
							`doc_type` = '".addslashes($table_doc_type)."', 
							`id_mst_attributes_store` = '".addslashes($_POST['id_mst_attributes_store'])."', 
							`id_goods_receipt_note` = '".addslashes($id_goods_receipt_note)."', 
							`id_inv_items` = '".addslashes($_POST['id_inv_items'])."', 
							`id_mst_charges_sgst` = '".addslashes($_POST['id_mst_charges_sgst'])."', 
							`id_mst_charges_cgst` = '".addslashes($_POST['id_mst_charges_cgst'])."', 
							`id_mst_charges_igst` = '".addslashes($_POST['id_mst_charges_igst'])."',
							`transaction_unit` = '".addslashes($_POST["transaction_unit"])."', 
							`qty` = '".addslashes($qty_total)."',
							`bal_qty` = '".addslashes($qty_total)."', 
							`main_unit` = '".addslashes($_POST["main_unit"])."', 
							`per_unit` = '".addslashes($_POST["per_unit"])."', 
							`alt_unit` = '".addslashes($_POST["alt_unit"])."',  
							`alt_qty` = '".addslashes($alt_qty)."',   
							`conver_rate_per_unit` = '".addslashes($_POST['conver_rate_per_unit'])."', 
							`id_mst_charges_purchase_local` = '".addslashes($_POST['id_mst_charges_purchase_local'])."', 
							`id_mst_charges_purchase_interstate` = '".addslashes($_POST['id_mst_charges_purchase_interstate'])."', 
							`rate_per_main_unit` = '".addslashes($_POST['rate_per_main_unit'])."', 
							`rate_per_alt_unit` = '".addslashes($rate_per_alt_unit)."',
							`item_amount_before_discount` = '".addslashes($_POST['item_amount_before_discount'])."', 
							`discount_percent` = '".addslashes($_POST['discount_percent'])."', 
							`discount_amount` = '".addslashes($_POST['discount_amount'])."', 
							`item_sgst_percent` = '".addslashes($_POST['item_sgst_percent'])."', 
							`item_sgst_amount` = '".addslashes($_POST['item_sgst_amount'])."', 
							`item_cgst_percent` = '".addslashes($_POST['item_cgst_percent'])."', 
							`item_cgst_amount` = '".addslashes($_POST['item_cgst_amount'])."', 
							`item_igst_percent` = '".addslashes($_POST['item_igst_percent'])."', 
							`item_igst_amount` = '".addslashes($_POST['item_igst_amount'])."',   
							`item_amount` = '".addslashes($_POST['item_amount'])."', 
							`item_remarks` = '".addslashes($_POST['item_remarks'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$editSql .= "
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'
							WHERE `id` = '".addslashes($_POST['update_id'])."'";
							executeSql($editSql);

							// updating GRN	
								if($_REQUEST['doc_type']=='5'){
									$updateGRN = "UPDATE ".TBL_INV_PURCH_DETAILS." SET ordered_qty='".$qty_total."',bal_qty='".$balance_qty."' WHERE id='".$id_goods_receipt_note."' AND doc_type='4' ";
									executeSql($updateGRN);
								}	
								// GRN update end


							if($type == 1 && $_GET['doc_type'] != '5'){
								//Order Qty Check Here
								$order_total= 0;$balance_qty =0;
								$id_inv_po_details = $_POST["id_inv_po_details"];
								$sql1 = "SELECT sum(qty) as qty FROM `".TBL_INV_PURCH_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_po_details`='".$id_inv_po_details."'  ";
			                   	$db->query($sql1);
			                    $row1 = $db->fetch_object();
			                    $order_total = $row1->qty;
							//Total Qty Get
								$total_qty=selectColumn(TBL_INV_PO_DETAILS,'qty'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$id_inv_po_details."' ");
								$balance_qty = $total_qty - $order_total;
							//Order Qty Update Indent Details Table
							 $editSql = "UPDATE `".TBL_INV_PO_DETAILS."` SET `ordered_qty` = '".$order_total."', `bal_qty` = '".$balance_qty."' WHERE `id` = '".addslashes($id_inv_po_details)."'";
						executeSql($editSql);
							}

							
				//Update INV PO DETAILS Id For Loops Section
							if($_POST['update_count'] == ''){
								$update_count = 0;								
							}else{
								$update_count = $_POST['update_count'];									
							}

							for($i = 1; $i <= $update_count; $i++){

								if($_POST['id_inv_po'.''.$i] == 'na'){
		  							$id_inv_po = 0;
		  							$type = 0;
		  						}else{
		  							$id_inv_po = $_POST['id_inv_po'.''.$i];
		  							$type = 1;
		  						}

		  						//Qty And Alt Qty Calcuations
  						$per_unit = $_POST["per_unit".''.$i]; 
  						$main_unit = $_POST["main_unit".''.$i];
  						$alt_unit = $_POST["alt_unit".''.$i];
  						$transaction_unit = $_POST["transaction_unit".''.$i];
  						$conver_rate_per_unit = $_POST['conver_rate_per_unit'.''.$i];
  						$qty = $_POST["qty".''.$i];
  						$alt_qty = 0;
  						$rate_per_main_unit = $_POST['rate_per_main_unit'.''.$i];
  						$rate_per_alt_unit = 0;

  						if($per_unit == $transaction_unit){

  							if($alt_unit == $transaction_unit){
  								$qty_total = ($qty/$conver_rate_per_unit); 
  								$alt_qty = $qty;
  								//Main Unit Section
  								$rate_per_main_unit = $rate_per_main_unit * $conver_rate_per_unit;
  								//Alt Unit
  								$rate_per_alt_unit = $_POST['rate_per_main_unit'.''.$i];

  							}else{
					 			$qty_total = $qty;
					 			$alt_qty = $qty_total * $conver_rate_per_unit; 
					 			//Main Unit Section
					 			$rate_per_main_unit = $_POST['rate_per_main_unit'.''.$i];
					 			//Alt Unit Section
					 			$rate_per_alt_unit = $rate_per_main_unit/$conver_rate_per_unit;
					 		}
					 	}
					 	else{
					 		if($main_unit == $transaction_unit){
					 			$qty_total = $qty;
					 			$alt_qty = $qty_total * $conver_rate_per_unit;
					 			
					 			if($per_unit == $alt_unit){

									$rate_per_main_unit = $_POST['rate_per_main_unit'.''.$i];
  									$rate_per_main_unit = $rate_per_main_unit * $conver_rate_per_unit;

	  								//Alt Unit
  									$rate_per_alt_unit = $_POST['rate_per_main_unit'.''.$i];
								}else{
									$rate_per_main_unit = $_POST['rate_per_main_unit'.''.$i];
  									$rate_per_main_unit = $rate_per_main_unit;

	  								//Alt Unit
  									$rate_per_alt_unit = $_POST['rate_per_main_unit'.''.$i];
								}
					 		}else{

								$qty_total = ($qty/$conver_rate_per_unit);
								$alt_qty = $qty;
								//Main Unit Section
								if($per_unit == $alt_unit){

									$rate_per_main_unit = $_POST['rate_per_main_unit'.''.$i];
  									$rate_per_main_unit = $rate_per_main_unit * $conver_rate_per_unit;

	  								//Alt Unit
  									$rate_per_alt_unit = $_POST['rate_per_main_unit'.''.$i];
								}else{
									$rate_per_main_unit = $_POST['rate_per_main_unit'.''.$i];
  									$rate_per_main_unit = $rate_per_main_unit;

	  								//Alt Unit
  									$rate_per_alt_unit = $_POST['rate_per_main_unit'.''.$i]/$conver_rate_per_unit;
								}
								

					 		}
					 	}
					 	//Goods Receipt Note
					 	if($_GET['doc_type'] == '5'){
					 		$id_goods_receipt_note = $_POST["id_inv_po_details".''.$i];
					 		$balance_qty=0;
					 		

					 		//calculate ordered qty
					 		$gsn_qty =selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)','WHERE id="'.$_POST["id_inv_po_details".''.$i].'" and doc_type="4"');
					 						 		
					 		$balance_qty =($gsn_qty-$qty_total);
					 	}else{
					 		$id_goods_receipt_note = 0;
					 	}

			 		 	$auditquery = "SELECT * From `".TBL_INV_PURCH_DETAILS."` WHERE id = '".addslashes($_POST['update_id'.''.$i])."'  ";

						  $auditresSQL = mysqli_query($connNew, $auditquery);	
							while($auditrow = mysqli_fetch_object($auditresSQL)){ 
							 
							  $id_inv_po_details = $auditrow ->id_inv_po_details; 
							  $qty = $auditrow ->qty; 
							  $main_unit = $auditrow ->main_unit;							 
							  $store = $auditrow ->id_mst_attributes_store;						 
							  $transaction_unit = $auditrow ->transaction_unit;					 
							  $rate_per_main_unit = $auditrow ->rate_per_main_unit;				 
							  $per_unit = $auditrow ->per_unit;							 
							  $discount_percent = $auditrow ->discount_percent;					 
							  $item_remarks = $auditrow ->item_remarks;							 
							  $id_mst_charges_purchase_local = $auditrow ->id_mst_charges_purchase_local;							 

							 //Change Data
							  $val = $i;
							  $val = $val + 1;
						    if($id_inv_indent != $_POST["id_inv_po_details"]){
						    	
								//$id_inv_indent_s = "Inventory Indent Changed from ". $id_inv_indent." - to - " . $_POST["id_inv_po_details"].'  in Rowno 1 ';
							}
							if($qty != $qty_total){
							
							if($_POST["qty".''.$i] != ''){ 
								$indent_qty_s .= " | Qty Changed  from ". $qty." - to - " .$qty_total.' in Rowno '.$val;
							}
							}
							if($main_unit != $_POST["main_unit".''.$i]){ 
								//$indent_main_unit_s .= " | Indent Main Unit Changed from ". $main_unit." - to - " .$_POST["main_unit".''.$i].' in Rowno '.$val;
							}
							if($store != $_POST["id_mst_attributes_store".''.$i]){ 
								$old_data = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$store."'");
								$new_data = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$_POST["id_mst_attributes_store".''.$i]."'  ");
								if($_POST["id_mst_attributes_store".''.$i] != ''){
								$sto .= "| Store Changed from ". $old_data." - to - " .$new_data.' in Rowno '.$val;
							}
							}
							if($transaction_unit != $_POST["transaction_unit".''.$i]){
if($_POST["transaction_unit".''.$i]!=''){								
								$indent_transaction_unit_s .= "| Unit Changed from ". $transaction_unit." - to - " .$_POST["transaction_unit".''.$i].' in Rowno '.$val;
							}
							}
							if($rate_per_main_unit != $_POST["rate_per_main_unit".''.$i]){ 
							
							if($_POST["rate_per_main_unit".''.$i] != ''){
								$indent_rate_per_main_unit_s .= " | Rate Changed from ". $rate_per_main_unit." - to - " .$_POST["rate_per_main_unit".''.$i].' in Rowno '.$val;
							}
							}
							if($per_unit != $_POST["per_unit".''.$i]){ 
								$indent_per_unit_s .= " | Per Unit Changed from ". $per_unit." - to - " .$_POST["per_unit".''.$i].' in Rowno '.$val;
							}
							if($discount_percent != $_POST["discount_percent".''.$i]){ 
							if($_POST["discount_percent".''.$i] != ''){
								$indent_discount_percent_s .= " | Discount Changed from ". $discount_percent." - to - " .$_POST["discount_percent".''.$i].' in Rowno '.$val;
							}
							}
							if($item_remarks != $_POST["item_remarks".''.$i]){ 
							if($_POST["item_remarks".''.$i] != ''){
								$indent_item_remarks_s .= " | Remarks Changed from ". $item_remarks." - to - " .$_POST["item_remarks".''.$i].' in Rowno '.$val;
							}
							}
							if($id_mst_charges_purchase_local != $_POST["id_mst_charges_purchase_local".''.$i]){ 
								$old_data = selectColumn('mst_charges','name'," WHERE `id` = '".$id_mst_charges_purchase_local."'");
								$new_data = selectColumn('mst_charges','name'," WHERE `id` = '".$_POST['id_mst_charges_purchase_local'.''.$i]."'");
if($_POST["id_mst_charges_purchase_local".''.$i] != ''){
								$indent_charge_purchase_s .= " | Tax Register Changed from ". $old_data." - to - " .$new_data.' in Rowno '.$val;
							}
							}
							}
							
						$editSql = "   	UPDATE `".TBL_INV_PURCH_DETAILS."`  SET  
								`id_inv_purch` = '".addslashes(encryptor(decrypt,$_POST[eId]))."',
								`id_inv_po` = '".addslashes($id_inv_po )."',   
								`id_inv_po_details` = '".addslashes($_POST["id_inv_po_details".''.$i])."',  
								`base_currency_code` = '".addslashes($_POST['base_currency_code1'])."',
								`transaction_currency_code` = '".addslashes($_POST['transaction_currency_code1'])."',
								`exchange_rate` = '".addslashes($_POST['exchange_rate'])."',
								`doc_type` = '".addslashes($table_doc_type)."', 
								`id_mst_attributes_store` = '".addslashes($_POST['id_mst_attributes_store'.''.$i])."', 
								`id_goods_receipt_note` = '".addslashes($id_goods_receipt_note)."', 
								`id_inv_items` = '".addslashes($_POST['id_inv_items'.''.$i])."', 
								`id_mst_charges_sgst` = '".addslashes($_POST['id_mst_charges_sgst'.''.$i])."', 
								`id_mst_charges_cgst` = '".addslashes($_POST['id_mst_charges_cgst'.''.$i])."', 
								`id_mst_charges_igst` = '".addslashes($_POST['id_mst_charges_igst'.''.$i])."',
								`transaction_unit` = '".addslashes($_POST["transaction_unit".''.$i])."', 
								`qty` = '".addslashes($qty_total)."',
								`bal_qty` = '".addslashes($qty_total)."',  
								`main_unit` = '".addslashes($_POST["main_unit".''.$i])."', 
								`per_unit` = '".addslashes($_POST["per_unit".''.$i])."', 
								`alt_unit` = '".addslashes($_POST["alt_unit".''.$i])."', 
								`alt_qty` = '".addslashes($alt_qty)."',   
								`conver_rate_per_unit` = '".addslashes($_POST['conver_rate_per_unit'.''.$i])."', 
								`id_mst_charges_purchase_local` = '".addslashes($_POST['id_mst_charges_purchase_local'.''.$i])."', 
								`id_mst_charges_purchase_interstate` = '".addslashes($_POST['id_mst_charges_purchase_interstate'.''.$i])."', 
								`rate_per_main_unit` = '".addslashes($_POST['rate_per_main_unit'.''.$i])."', 
								`rate_per_alt_unit` = '".addslashes($rate_per_alt_unit)."', 
								`item_amount_before_discount` = '".addslashes($_POST['item_amount_before_discount'.''.$i])."', 
								`discount_percent` = '".addslashes($_POST['discount_percent'.''.$i])."', 
								`discount_amount` = '".addslashes($_POST['discount_amount'.''.$i])."', 
								`item_sgst_percent` = '".addslashes($_POST['item_sgst_percent'.''.$i])."', 
								`item_sgst_amount` = '".addslashes($_POST['item_sgst_amount'.''.$i])."', 
								`item_cgst_percent` = '".addslashes($_POST['item_cgst_percent'.''.$i])."', 
								`item_cgst_amount` = '".addslashes($_POST['item_cgst_amount'.''.$i])."', 
								`item_igst_percent` = '".addslashes($_POST['item_igst_percent'.''.$i])."', 
								`item_igst_amount` = '".addslashes($_POST['item_igst_amount'.''.$i])."',   
								`item_amount` = '".addslashes($_POST['item_amount'.''.$i])."', 
								`item_remarks` = '".addslashes($_POST['item_remarks'.''.$i])."',
								`id_shop` = '".addslashes($_SESSION['shop'])."'";

								$editSql .= "	
								,`last_modified` = '".currenDateTime()."'
								,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
								,`status` = '".addslashes($_POST['status'])."'
								WHERE `id` = '".addslashes($_POST['update_id'.''.$i])."'";
							executeSql($editSql);
								// updating GRN	
								if($_REQUEST['doc_type']=='5'){
									$updateGRN = "UPDATE ".TBL_INV_PURCH_DETAILS." SET ordered_qty='".$qty_total."',bal_qty='".$balance_qty."' WHERE id='".$id_goods_receipt_note."' AND doc_type='4' ";
								executeSql($updateGRN);
								}	
								// GRN update end

								if($type == 1 && $_GET['doc_type'] != '5'){
								//Order Qty Check Here
									$order_total= 0;$balance_qty =0;
									$id_inv_po_details = $_POST["id_inv_po_details".''.$i];
									$sql1 = "SELECT sum(qty) as qty FROM `".TBL_INV_PURCH_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_po_details`='".$id_inv_po_details."'  ";
				                   	$db->query($sql1);
				                    $row1 = $db->fetch_object();
				                    $order_total = $row1->qty;
									//Total Qty Get
										$total_qty=selectColumn(TBL_INV_PO_DETAILS,'qty'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$id_inv_po_details."' ");
										$balance_qty = $total_qty - $order_total;
									//Order Qty Update Indent Details Table
									 $editSql = "UPDATE `".TBL_INV_PO_DETAILS."` SET `ordered_qty` = '".$order_total."', `bal_qty` = '".$balance_qty."' WHERE `id` = '".addslashes($id_inv_po_details)."'";
										executeSql($editSql);
								}

							}
				//Update  INV PO DETAILS Field More Fields Add Here

							if($_POST['counter1'] == ''){
								$counter1 = 0;								
							}else{
								$counter1 = $_POST['counter1'];									
							}

							for($i = $counter1; $i > $update_count; $i--){

								if($_POST['id_inv_po'.''.$i] == 'na'){
		  							$id_inv_po = 0;
		  							$type = 0;
		  						}else{
		  							$id_inv_po = $_POST['id_inv_po'.''.$i];
		  							$type = 1;
		  						}

		  								//Qty And Alt Qty Calcuations
  						$per_unit = $_POST["per_unit".''.$i]; 
  						$main_unit = $_POST["main_unit".''.$i];
  						$alt_unit = $_POST["alt_unit".''.$i];
  						$transaction_unit = $_POST["transaction_unit".''.$i];
  						$conver_rate_per_unit = $_POST['conver_rate_per_unit'.''.$i];
  						$qty = $_POST["qty".''.$i];
  						$alt_qty = 0;
  						$rate_per_main_unit = $_POST['rate_per_main_unit'.''.$i];
  						$rate_per_alt_unit = 0;

  						if($per_unit == $transaction_unit){

  							if($alt_unit == $transaction_unit){
  								$qty_total = ($qty/$conver_rate_per_unit); 
  								$alt_qty = $qty;
  								//Main Unit Section
  								$rate_per_main_unit = $rate_per_main_unit * $conver_rate_per_unit;
  								//Alt Unit
  								$rate_per_alt_unit = $_POST['rate_per_main_unit'.''.$i];

  							}else{
					 			$qty_total = $qty;
					 			$alt_qty = $qty_total * $conver_rate_per_unit; 
					 			//Main Unit Section
					 			$rate_per_main_unit = $_POST['rate_per_main_unit'.''.$i];
					 			//Alt Unit Section
					 			$rate_per_alt_unit = $rate_per_main_unit/$conver_rate_per_unit;
					 		}
					 	}
					 	else{
					 		if($main_unit == $transaction_unit){
					 			$qty_total = $qty;
					 			$alt_qty = $qty_total * $conver_rate_per_unit;
					 			
					 			if($per_unit == $alt_unit){

									$rate_per_main_unit = $_POST['rate_per_main_unit'.''.$i];
  									$rate_per_main_unit = $rate_per_main_unit * $conver_rate_per_unit;

	  								//Alt Unit
  									$rate_per_alt_unit = $_POST['rate_per_main_unit'.''.$i];
								}else{
									$rate_per_main_unit = $_POST['rate_per_main_unit'.''.$i];
  									$rate_per_main_unit = $rate_per_main_unit;

	  								//Alt Unit
  									$rate_per_alt_unit = $_POST['rate_per_main_unit'.''.$i];
								}
					 		}else{

								$qty_total = ($qty/$conver_rate_per_unit);
								$alt_qty = $qty;
								//Main Unit Section
								if($per_unit == $alt_unit){

									$rate_per_main_unit = $_POST['rate_per_main_unit'.''.$i];
  									$rate_per_main_unit = $rate_per_main_unit * $conver_rate_per_unit;

	  								//Alt Unit
  									$rate_per_alt_unit = $_POST['rate_per_main_unit'.''.$i];
								}else{
									$rate_per_main_unit = $_POST['rate_per_main_unit'.''.$i];
  									$rate_per_main_unit = $rate_per_main_unit;

	  								//Alt Unit
  									$rate_per_alt_unit = $_POST['rate_per_main_unit'.''.$i]/$conver_rate_per_unit;
								}
								

					 		}
					 	}
					 	//Goods Receipt Note
					 	if($_GET['doc_type'] == '5'){
					 		$id_goods_receipt_note = $_POST["id_inv_po_details".''.$i];
					 		$balance_qty=0;
					 		
					 		//calculate ordered qty
					 		$gsn_qty =selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)','WHERE id="'.$id_goods_receipt_note.'" and doc_type="4"');
					 						 		
					 		$balance_qty =($gsn_qty-$qty_total);
					 	}else{
					 		$id_goods_receipt_note = 0;
					 	}
								 
								if($_POST['id_inv_po'.''.$i] != '' && $_POST['item_amount'.''.$i] >=1){
									
									if($_POST['id_inv_po'.''.$i]){ 
										//$ch5 = "NA Details Added ";
										$ne = selectColumn(TBL_INV_ITEMS,'name','WHERE id="'.$_POST['id_inv_items'.''.$i].'" ');
										$ch5 .= $ne." Details Added ";
									}

								/*	if( $qty_total){ 
										$indent_qty_s .= " | Indent Quantity Insert " .$qty_total;
									}
									if($_POST["main_unit".''.$i]){ 
										$indent_main_unit_s .= " | Indent Main Unit  Insert " .$_POST["main_unit".''.$i];
									}
									if($_POST["transaction_unit".''.$i]){ 
										$indent_transaction_unit_s .= "| Indent Transaction Unit  Insert ".$_POST["transaction_unit".''.$i];
									}
									if($_POST["rate_per_main_unit".''.$i]){ 
										$indent_rate_per_main_unit_s .= " | Rate  Insert ".$_POST["rate_per_main_unit".''.$i];
									}
									if($_POST["per_unit".''.$i]){ 
										$indent_per_unit_s .= " | Per Unit  Insert ".$_POST["per_unit".''.$i];
									}
									if($_POST["discount_percent".''.$i]){ 
										$indent_discount_percent_s .= " | Indent Discount Rate  Insert ".$_POST["discount_percent".''.$i];
									}
									if($_POST["item_remarks".''.$i]){ 
										$indent_item_remarks_s .= " | Indent Item Remarks  Insert ".$_POST["item_remarks".''.$i];
									}
									if($_POST["id_mst_charges_purchase_local".''.$i]){ 
										$old_data = selectColumn('mst_charges','name'," WHERE `id` = '".$id_mst_charges_purchase_local."'");
										$new_data = selectColumn('mst_charges','name'," WHERE `id` = '".$_POST['id_mst_charges_purchase_local'.''.$i]."'");

										$indent_charge_purchase_s .= " | Indent Charge Purchase Local Insert ".$new_data;
									} */


									$addSql = "INSERT INTO `".TBL_INV_PURCH_DETAILS."` SET

									`id_inv_purch` = '".addslashes(encryptor(decrypt,$_POST[eId]))."',
									`id_inv_po` = '".addslashes($id_inv_po )."',  
									`id_inv_po_details` = '".addslashes($_POST["id_inv_po_details".''.$i])."',
									`base_currency_code` = '".addslashes($_POST['base_currency_code1'])."',
									`transaction_currency_code` = '".addslashes($_POST['transaction_currency_code1'])."',
									`exchange_rate` = '".addslashes($_POST['exchange_rate'])."',
									`doc_type` = '".addslashes($table_doc_type)."', 
									`id_mst_attributes_store` = '".addslashes($_POST['id_mst_attributes_store'.''.$i])."', 
									`id_goods_receipt_note` = '".addslashes($id_goods_receipt_note)."', 
									`id_inv_items` = '".addslashes($_POST['id_inv_items'.''.$i])."', 
									`id_mst_charges_sgst` = '".addslashes($_POST['id_mst_charges_sgst'.''.$i])."', 
									`id_mst_charges_cgst` = '".addslashes($_POST['id_mst_charges_cgst'.''.$i])."', 
									`id_mst_charges_igst` = '".addslashes($_POST['id_mst_charges_igst'.''.$i])."',
									`transaction_unit` = '".addslashes($_POST["transaction_unit".''.$i])."', 
									`qty` = '".addslashes($qty_total)."', 
									`bal_qty` = '".addslashes($qty_total)."',  
									`main_unit` = '".addslashes($_POST["main_unit".''.$i])."', 
									`per_unit` = '".addslashes($_POST["per_unit".''.$i])."', 
									`alt_unit` = '".addslashes($_POST["alt_unit".''.$i])."', 
									`alt_qty` = '".addslashes($alt_qty)."', 
									`conver_rate_per_unit` = '".addslashes($_POST['conver_rate_per_unit'.''.$i])."', 
									`id_mst_charges_purchase_local` = '".addslashes($_POST['id_mst_charges_purchase_local'.''.$i])."', 
									`id_mst_charges_purchase_interstate` = '".addslashes($_POST['id_mst_charges_purchase_interstate'.''.$i])."', 
									`rate_per_main_unit` = '".addslashes($_POST['rate_per_main_unit'.''.$i])."', 
									`rate_per_alt_unit` = '".addslashes($rate_per_alt_unit)."',  
									`item_amount_before_discount` = '".addslashes($_POST['item_amount_before_discount'.''.$i])."', 
									`discount_percent` = '".addslashes($_POST['discount_percent'.''.$i])."', 
									`discount_amount` = '".addslashes($_POST['discount_amount'.''.$i])."', 
									`item_sgst_percent` = '".addslashes($_POST['item_sgst_percent'.''.$i])."', 
									`item_sgst_amount` = '".addslashes($_POST['item_sgst_amount'.''.$i])."', 
									`item_cgst_percent` = '".addslashes($_POST['item_cgst_percent'.''.$i])."', 
									`item_cgst_amount` = '".addslashes($_POST['item_cgst_amount'.''.$i])."', 
									`item_igst_percent` = '".addslashes($_POST['item_igst_percent'.''.$i])."', 
									`item_igst_amount` = '".addslashes($_POST['item_igst_amount'.''.$i])."',   
									`item_amount` = '".addslashes($_POST['item_amount'.''.$i])."', 
									`item_remarks` = '".addslashes($_POST['item_remarks'.''.$i])."',
									`id_shop` = '".addslashes($_SESSION['shop'])."'";

									$addSql .= "	,`date_created` = '".currenDateTime()."',
									`last_modified` = '".currenDateTime()."',
									`id_mst_user_modified_by` = '".$_SESSION['userId']."',
									`id_mst_user_created_by` = '".$_SESSION['userId']."',
									`status` = '".addslashes($_POST['status'])."'";
								executeSql($addSql);

									// updating GRN	
								if($_REQUEST['doc_type']=='5'){
									$updateGRN = "UPDATE ".TBL_INV_PURCH_DETAILS." SET ordered_qty='".$qty_total."',bal_qty='".$balance_qty."' WHERE id='".$id_goods_receipt_note."' AND doc_type='4' ";
									executeSql($updateGRN);
								}	
								// GRN update end


									if($type == 1 && $_GET['doc_type'] != '5'){
										//Order Qty Check Here
										$order_total= 0;$balance_qty =0;
										$id_inv_po_details = $_POST["id_inv_po_details".''.$i];
										$sql1 = "SELECT sum(qty) as qty FROM `".TBL_INV_PURCH_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_po_details`='".$id_inv_po_details."'  ";
					                   	$db->query($sql1);
					                    $row1 = $db->fetch_object();
					                    $order_total = $row1->qty;
										//Total Qty Get
											$total_qty=selectColumn(TBL_INV_PO_DETAILS,'qty'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$id_inv_po_details."' ");
											$balance_qty = $total_qty - $order_total;
										//Order Qty Update Indent Details Table
										 $editSql = "UPDATE `".TBL_INV_PO_DETAILS."` SET `ordered_qty` = '".$order_total."', `bal_qty` = '".$balance_qty."' WHERE `id` = '".addslashes($id_inv_po_details)."'";
									executeSql($editSql);
									}
								}
							}


							//Audit Files Select Here
					 	$auditquery = "SELECT * From `".TBL_INV_OTHERS_CHARGES_PURCH."` WHERE id = '".addslashes($_POST['chargesupdate_id'])."'  ";

						  $auditresSQL = mysqli_query($connNew, $auditquery);	
							while($auditrow = mysqli_fetch_object($auditresSQL)){ 
							 
							  $type = $auditrow ->type; 
							  $others_charges_percent = $auditrow ->others_charges_percent; 
							  $others_charges_amount = $auditrow ->others_charges_amount; 
							  $id_mst_charges_discounts = $auditrow ->id_mst_charges_discounts; 
							  $id_mst_charges_others = $auditrow ->id_mst_charges_others; 
							 							 

							 //Change Data
						    if($type != $_POST['type']){ 
						    	if($type == '1'){$type="Others";}else{$type="Discount";}
						    	if($_POST['type'] == '1'){$newdata="Others";}else{$newdata="Discount";}
								//$others_type_s = "Others/Discount Changed from ". $type." - to - " .$newdata." in Rowno 1";
							}
							//echo $id_mst_charges_others.'<br>';
							//echo $id_mst_charges_others.'<br>';
							
							if($_POST['id_mst_charges_others'] == ''){
								$var = '0';
							}else{
								$var = $_POST['id_mst_charges_others'];
							}
							
							if($id_mst_charges_others != $var){ 
								$old_data = selectColumn('mst_charges','name'," WHERE `id` = '".$id_mst_charges_others."'");
								$new_data = selectColumn('mst_charges','name'," WHERE `id` = '".$_POST['id_mst_charges_others']."'  ");
								
								if($id_mst_charges_others == '0'){
									$others_charge_discount_s = $new_data." Charges/Discount Details Added ";
								}else{
									$others_charge_discount_s = "Charges/Discount Changed from ". $old_data." - to - " .$new_data."  in Rowno 1 ";
								}
							}
							if($others_charges_percent != $_POST['others_charges_percent']){ 
								$others_charge_percent_s = " Other charges Percentage Changed from ". $others_charges_percent." - to - " .$_POST['others_charges_percent']." in Rowno 1 ";
							}
							if($others_charges_amount != $_POST['others_charges_amount']){ 
								$others_charge_amount_s = " Other charges  Amount Changed from ". $others_charges_amount." - to - " .$_POST['others_charges_amount']." in Rowno 1 ";
							}
						}
					//Update Others Charges Details
							$editSql = "   	UPDATE `".TBL_INV_OTHERS_CHARGES_PURCH."`  SET  

							`id_inv_purch` = '".addslashes(encryptor(decrypt,$_POST[eId]))."',   
							`type` = '".addslashes($_POST['type'])."', 
							`id_mst_charges_others` = '".addslashes($_POST['id_mst_charges_others'])."', 
							`id_mst_charges_discounts` = '".addslashes($_POST['id_mst_charges_discounts'])."', 
							`others_discount_percent` = '".addslashes($_POST['others_discount_percent'])."', 
							`others_discount_amount` = '".addslashes($_POST['others_discount_amount'])."', 
							`others_charges_sgst_percent` = '".addslashes($_POST['others_charges_sgst_percent'])."', 
							`others_charges_sgst_amount` = '".addslashes($_POST['others_charges_sgst_amount'])."', 
							`others_charges_cgst_percent` = '".addslashes($_POST['others_charges_cgst_percent'])."', 
							`others_charges_cgst_amount` = '".addslashes($_POST['others_charges_cgst_amount'])."', 
							`others_charges_igst_percent` = '".addslashes($_POST['others_charges_igst_percent'])."', 
							`others_charges_igst_amount` = '".addslashes($_POST['others_charges_igst_amount'])."',
							`others_charges_amount` = '".addslashes($_POST['others_charges_amount'])."',  
							`others_charges_percent` = '".addslashes($_POST['others_charges_percent'])."', 
							`total_amount_others` = '".addslashes($_POST['total_amount_others'])."', 
							`id_mst_charges_sgst_others` = '".addslashes($_POST['id_mst_charges_sgst_others'])."',    
								`id_mst_charges_cgst_others` = '".addslashes($_POST['id_mst_charges_cgst_others'])."',    
								`id_mst_charges_igst_others` = '".addslashes($_POST['id_mst_charges_igst_others'])."',      
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$editSql .= "	
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'
							WHERE `id` = '".addslashes($_POST['chargesupdate_id'])."'";
						executeSql($editSql);

				//Update Others Charges Id For Loops Section
							if($_POST['chargesupdate_count'] == ''){
								$chargesupdate_count = 0;								
							}else{
								$chargesupdate_count = $_POST['chargesupdate_count'];									
							}

							for($i = 1; $i <= $chargesupdate_count; $i++){

								$val = $i;
								$val = $val + 1;

								//Audit Files Select Here
					 			$auditquery = "SELECT * From `".TBL_INV_OTHERS_CHARGES_PURCH."` WHERE id = '".addslashes($_POST['chargesupdate_id'.$i])."'  ";

						  	$auditresSQL = mysqli_query($connNew, $auditquery);	
							while($auditrow = mysqli_fetch_object($auditresSQL)){ 
							 
							  $type = $auditrow ->type; 
							  $others_charges_percent = $auditrow ->others_charges_percent; 
							  $others_charges_amount = $auditrow ->others_charges_amount; 
							  $id_mst_charges_others = $auditrow ->id_mst_charges_others; 
							 							 

							 //Change Data
							    if($type != $_POST['type'.$i]){ 
							    	if($type == '1'){$type="Others";}else{$type="Discount";}
							    	if($_POST['type'.$i] == '1'){$newdata="Others";}else{$newdata="Discount";}
									//$others_type_s .= " | Others/Discount Changed from ". $type." - to - " .$newdata." in Rowno ".$val;
								}
								if($id_mst_charges_others != $_POST['id_mst_charges_others'.$i]){ 
									$old_data = selectColumn('mst_charges','name'," WHERE `id` = '".$id_mst_charges_others."' ");
									$new_data = selectColumn('mst_charges','name'," WHERE `id` = '".$_POST['id_mst_charges_others'.$i]."'  ");
									$others_charge_discount_s .= " Charges/Discount Changed from ". $old_data." - to - " .$new_data." in Rowno ".$val;
								}
								if($others_charges_percent != $_POST['others_charges_percent'.$i]){ 
									$others_charge_percent_s .= " | Percentage Changed from ". $others_charges_percent." - to - " .$_POST['others_charges_percent'.$i]." in Rowno ".$val;
								}
								if($others_charges_amount != $_POST['others_charges_amount'.$i]){ 
									$others_charge_amount_s .= " | Amount Changed from ". $others_charges_amount." - to - " .$_POST['others_charges_amount'.$i]." in Rowno ".$val;
								}
							}



								$editSql = "   	UPDATE `".TBL_INV_OTHERS_CHARGES_PURCH."`  SET  

								`id_inv_purch` = '".addslashes(encryptor(decrypt,$_POST[eId]))."', 
									`type` = '".addslashes($_POST['type'.''.$i])."', 
							`id_mst_charges_others` = '".addslashes($_POST['id_mst_charges_others'.''.$i])."', 
							`id_mst_charges_discounts` = '".addslashes($_POST['id_mst_charges_discounts'.''.$i])."', 
							`others_discount_percent` = '".addslashes($_POST['others_discount_percent'.''.$i])."', 
							`others_discount_amount` = '".addslashes($_POST['others_discount_amount'.''.$i])."', 
							`others_charges_sgst_percent` = '".addslashes($_POST['others_charges_sgst_percent'.''.$i])."', 
							`others_charges_sgst_amount` = '".addslashes($_POST['others_charges_sgst_amount'.''.$i])."', 
							`others_charges_cgst_percent` = '".addslashes($_POST['others_charges_cgst_percent'.''.$i])."', 
							`others_charges_cgst_amount` = '".addslashes($_POST['others_charges_cgst_amount'.''.$i])."', 
							`others_charges_igst_percent` = '".addslashes($_POST['others_charges_igst_percent'.''.$i])."', 
							`others_charges_igst_amount` = '".addslashes($_POST['others_charges_igst_amount'.''.$i])."',
							`others_charges_amount` = '".addslashes($_POST['others_charges_amount'.''.$i])."',  
							`others_charges_percent` = '".addslashes($_POST['others_charges_percent'.''.$i])."', 
							`total_amount_others` = '".addslashes($_POST['total_amount_others'.''.$i])."',    
							`id_mst_charges_sgst_others` = '".addslashes($_POST['id_mst_charges_sgst_others'.''.$i])."',    
								`id_mst_charges_cgst_others` = '".addslashes($_POST['id_mst_charges_cgst_others'.''.$i])."',    
								`id_mst_charges_igst_others` = '".addslashes($_POST['id_mst_charges_igst_others'.''.$i])."', 
								`id_shop` = '".addslashes($_SESSION['shop'])."'";

								$editSql .= "	
								,`last_modified` = '".currenDateTime()."'
								,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
								,`status` = '".addslashes($_POST['status'])."'
								WHERE `id` = '".addslashes($_POST['chargesupdate_id'.''.$i])."'";
							executeSql($editSql);

							}
				//Update  Others Charges Field More Fields Add Here

							if($_POST['counter2'] == ''){
								$counter2 = 0;								
							}else{
								$counter2 = $_POST['counter2'];									
							}

							for($i = $counter2; $i > $chargesupdate_count; $i--){

								 
								if($_POST['type'.''.$i] != ''){

									//Audit Trai Insert
								if($_POST['type'.$i]){ 
							    	
							    	if($_POST['type'.$i] == '1'){$newdata="Others";}else{$newdata="Discount";}
									//$others_type_s .= " | Others/Discount Insert to ".$newdata;
								}
								
								if($_POST['id_mst_charges_others'.$i]){ 
									$old_data = selectColumn('mst_charges','name'," WHERE `id` = '".$id_mst_charges_others."' ");
									$new_data = selectColumn('mst_charges','name'," WHERE `id` = '".$_POST['id_mst_charges_others'.$i]."'  ");
									$others_charge_discount_s .= $new_data. " Details Added ";
								}
								
								
								/*if($_POST['id_mst_charges_others'.$i]){ 
									$old_data = selectColumn('mst_charges','name'," WHERE `id` = '".$id_mst_charges_others."' ");
									$new_data = selectColumn('mst_charges','name'," WHERE `id` = '".$_POST['id_mst_charges_others'.$i]."'  ");
									$others_charge_discount_s .= " | Charges Insert to ".$new_data;
								}
								if($_POST['others_charges_percent'.$i]){ 
									$others_charge_percent_s .= " | Percentage Insert to ".$_POST['others_charges_percent'.$i];
								}
								if($_POST['others_charges_amount'.$i]){ 
									$others_charge_amount_s .= " | Amount Insert to ". $_POST['others_charges_amount'.$i];
								} */

									$addSql = "INSERT INTO `".TBL_INV_OTHERS_CHARGES_PURCH."` SET

									`id_inv_purch` = '".addslashes(encryptor(decrypt,$_POST[eId]))."',  
										`type` = '".addslashes($_POST['type'.''.$i])."', 
							`id_mst_charges_others` = '".addslashes($_POST['id_mst_charges_others'.''.$i])."', 
							`id_mst_charges_discounts` = '".addslashes($_POST['id_mst_charges_discounts'.''.$i])."', 
							`others_discount_percent` = '".addslashes($_POST['others_discount_percent'.''.$i])."', 
							`others_discount_amount` = '".addslashes($_POST['others_discount_amount'.''.$i])."', 
							`others_charges_sgst_percent` = '".addslashes($_POST['others_charges_sgst_percent'.''.$i])."', 
							`others_charges_sgst_amount` = '".addslashes($_POST['others_charges_sgst_amount'.''.$i])."', 
							`others_charges_cgst_percent` = '".addslashes($_POST['others_charges_cgst_percent'.''.$i])."', 
							`others_charges_cgst_amount` = '".addslashes($_POST['others_charges_cgst_amount'.''.$i])."', 
							`others_charges_igst_percent` = '".addslashes($_POST['others_charges_igst_percent'.''.$i])."', 
							`others_charges_igst_amount` = '".addslashes($_POST['others_charges_igst_amount'.''.$i])."',
							`others_charges_amount` = '".addslashes($_POST['others_charges_amount'.''.$i])."',  
							`others_charges_percent` = '".addslashes($_POST['others_charges_percent'.''.$i])."', 
							`total_amount_others` = '".addslashes($_POST['total_amount_others'.''.$i])."',      
							`id_mst_charges_sgst_others` = '".addslashes($_POST['id_mst_charges_sgst_others'.''.$i])."',    
								`id_mst_charges_cgst_others` = '".addslashes($_POST['id_mst_charges_cgst_others'.''.$i])."',    
								`id_mst_charges_igst_others` = '".addslashes($_POST['id_mst_charges_igst_others'.''.$i])."', 
									`id_shop` = '".addslashes($_SESSION['shop'])."'";

									$addSql .= "	,`date_created` = '".currenDateTime()."',
									`last_modified` = '".currenDateTime()."',
									`id_mst_user_modified_by` = '".$_SESSION['userId']."',
									`id_mst_user_created_by` = '".$_SESSION['userId']."',
									`status` = '".addslashes($_POST['status'])."'";
									executeSql($addSql);

								}
							} 


//`changes` =  '".addslashes($exchange_rate_s).",".addslashes($discount_s).",".addslashes($indent_qty_s).",".addslashes($indent_main_unit_s).",".addslashes($sto).",".addslashes($indent_transaction_unit_s).",".addslashes($indent_rate_per_main_unit_s).",".addslashes($indent_discount_percent_s).",".addslashes($indent_item_remarks_s).",".addslashes($indent_charge_purchase_s).",".addslashes($others_type_s).",".addslashes($others_charge_percent_s).",".addslashes($others_charge_amount_s).",".addslashes($others_charge_discount_s)."',

$doc_type = $_GET['doc_type'];

if($doc_type == '4'){
	$form_name = "Good Receipt Note";
}else if($doc_type == '5'){
	$form_name = "Purchase Bill";
}else if($doc_type == '12'){
	$form_name = "Direct Purchase";
}

							$auditeditSql = " INSERT audit_trail SET 
			                `voucher_id` = '".addslashes(encryptor(decrypt,$_REQUEST[eId]))."',
							`tables_name` = 'pos_purch , pos_purch_details',
							`form_code` = '".$form_name."',
							`changes` =  '".addslashes($ch_1).",".addslashes($ch1).",".addslashes($ch2).",".addslashes($ch3).",".addslashes($indent_qty_s).",".addslashes($ch4).",".addslashes($exchange_rate_s).",".addslashes($discount_s).",".addslashes($ch5).",".addslashes($indent_main_unit_s).",".addslashes($sto).",".addslashes($indent_transaction_unit_s).",".addslashes($indent_rate_per_main_unit_s).",".addslashes($indent_discount_percent_s).",".addslashes($indent_item_remarks_s).",".addslashes($indent_charge_purchase_s).",".addslashes($others_type_s).",".addslashes($others_charge_percent_s).",".addslashes($others_charge_amount_s).",".addslashes($others_charge_discount_s)."',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 2 ";	
							
if($ch_1=='' && $ch1=='' && $ch2=='' && $ch3=='' && $ch4=='' && $exchange_rate_s=='' && $discount_s=='' && $ch5=='' && $indent_qty_s=='' && $indent_main_unit_s=='' && $sto=='' && $indent_transaction_unit_s=='' && $indent_rate_per_main_unit_s=='' && $indent_discount_percent_s=='' && $indent_item_remarks_s=='' && $indent_charge_purchase_s=='' && $others_type_s=='' && $others_charge_percent_s=='' && $others_charge_amount_s=='' && $others_charge_discount_s==''  ){
						
}else{
	executeSql($auditeditSql);
}		
           				
							
			if(1){  

				$_SESSION['successMsg'] = selectColumn(TBL_INV_PURCH, 'doc_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has been updated sucessfully.';  
				
				if($_POST['another']!=''){
					header("location:editPurch.php?submenu=".$_GET['submenu']."&session=".$_GET['session']."&doc_type=".$_GET['doc_type']."&print=1");	
				}else{
					header("location:editPurch.php?eId=".$_GET['eId']."&submenu=".$_GET['submenu']."&session=".$_GET['session']."&action=edit&page=".$_REQUEST['page']."&doc_type=".$_GET['doc_type']."&print=1"); 
				}

				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = selectColumn(TBL_INV_PURCH,'doc_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND 'id' = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = ' PO has not been saved. Please make corrections.';
	}
}
// ----------cate---------

if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	//Indent Table

	 $sql = "  SELECT * FROM `".TBL_INV_PURCH."`
			WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
	 $db->query($sql);
	
	if($db->num_rows() > 0){
		$row = $db->fetch_object(); 
		
	}  
		  			 
}	
?>
<?php   

	if($_GET['eId'] == ''){
		$id_indent_id =  encryptor(decrypt,$_GET['id_indent_id']);
	}else{
 
		$id_indent_id = encryptor(decrypt,$_GET['id_indent_id']);
		encryptor(decrypt, $_REQUEST['eId']); 
 
	} 
	//Main Concept Here
	$doc_type = $_GET['doc_type'];
	if($doc_type == 4){
		$add = "Goods Receipt Note";
		$popup = "Pending Purchase Order";
		$table_field = "PO";
		$field = "GRN";
	}else if($doc_type == 5){
		$add = "Purchase Bill";
		$table_field = "GRN";
		$popup = "Purchase Bill";
		$field = "PU";
	}else if($doc_type == 12){
		$add = "Direct Purchase";
		$table_field = "PO";
	}
?>


<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<?php include_once("bodycontent.php")?>
	

 		
<?php include_once("../includes/footer.php");?> 
<script type="text/javascript">


function saveornot(){
		var id_mst_party_supplier = document.getElementById("id_mst_party_supplier").value;
		var submenu = document.getElementById("submenu").value;
		var eId = document.getElementById("eId").value;
		var session = document.getElementById("session").value;
		var doctype = document.getElementById("doctype").value;
		
		
		if(eId==''){
			 $("#mge").html("You want to Add the Current Records ?");
			//document.getElementById("mge").value = "You want to Add the Current Records ?";
		}else{
			 $("#mge").html("You want to Save the Current Changes of the Records ?");
			//document.getElementById("mge").value = "You want to Save the Current Changes of the Records ?";
		}
		
		if(id_mst_party_supplier == ''){
			window.location.href="editPurch.php?submenu="+submenu+"&session="+session+"&doc_type="+doctype;
		}else{
			//alert();
			$('#anotherModal').modal('show');
		}
	}	
	
	function yes(){
		
		var submenu = document.getElementById("submenu").value;
		var session = document.getElementById("session").value;
		var doctype = document.getElementById("doctype").value;
		$('#anotherModal').modal('hide');
		
		document.getElementById("another").value = "Another";
		
		//window.location.href="editPurch.php?submenu="+submenu+"&session="+session+"&doc_type="+doctype;
	}
	
	
	
	function nosave(){
		var submenu = document.getElementById("submenu").value;
		var session = document.getElementById("session").value;
		var doctype = document.getElementById("doctype").value;
		//alert(session)
		window.location.href="editPurch.php?submenu="+submenu+"&session="+session+"&doc_type="+doctype;
	}



	function audittrial(clicked_value){ 
	
		var doc_type = document.getElementById("doctype").value;
		var id = document.getElementById("purchid").value;
		
		
if(doc_type == '4'){
	var form_name = "Good Receipt Note";
}else if(doc_type == '5'){
    var form_name = "Purchase Bill";
}else if(doc_type == '12'){
	var form_name = "Direct Purchase";
}
	
		

		$('#auditModal').modal('show');
		var form_name1 = form_name;
		$.ajax({
			url: "../functions/ajaxAuditTrail.php",
			  type: 'POST',
				data: 'form_name='+form_name+'&id='+id,
				dataType: "JSON",
				success: function(data) {
				// alert(data);
			  $('#roombutton').html(data);
			}
	   });
	}
	
	
</script>
<script type="text/javascript">
	//Net Amount Adding Section
	function net_amount(){

		var net_amount_items = document.getElementById("net_amount_items").value;
		var others_charges = document.getElementById("others_charges_net_amount").value;
		var sgst_net_amount = document.getElementById("sgst_net_amount").value;
		var cgst = document.getElementById("cgst_net_amount").value;
		var igst = document.getElementById("igst_net_amount").value;
		var disc_amount_additional = document.getElementById("disc_amount_additional").value;

		var total = Number(net_amount_items)+Number(others_charges)+Number(sgst_net_amount)+Number(cgst)+Number(igst);
		total = Number(total) - Number(disc_amount_additional);
		//Net Amount Fetch
		var adjust_amount =  Math.round(total);
		var adjust_amount1 =  Math.round(total);
		 adjust_amount = adjust_amount - total;
		document.getElementById("round_off_amount").value = adjust_amount.toFixed(2);
		document.getElementById("net_amount").value = adjust_amount1;
	}
	//Charges Subtotal Value Based Calations
	function subtotal_calc(clicked_id){
		var regex = /[+-]?\d+(?:\.\d+)?/g;
		var match = parseInt(regex.exec(clicked_id));
		
		if(match >=1){
			var others_charges_percent = document.getElementById("others_charges_percent"+match).value;
			var others_charges_igst_amount = document.getElementById("others_charges_igst_amount"+match).value;
			
			var sub_total_items = document.getElementById("sub_total_items").value;
			var others_charges_percent = (sub_total_items - (sub_total_items * (others_charges_percent / 100)))+others_charges_igst_amount;
			//Amount Fetch
			 document.getElementById("others_charges_amount"+match).value = Number(others_charges_percent).toFixed(2);
			 document.getElementById('others_charges_amount'+match).click();
		}else{
			var others_charges_percent = document.getElementById("others_charges_percent").value;
			var others_charges_igst_amount = document.getElementById("others_charges_igst_amount"+match).value;
			var sub_total_items = document.getElementById("sub_total_items").value;
			var others_charges_percent = (sub_total_items - (sub_total_items * (others_charges_percent / 100)))+others_charges_igst_amount;
			//Amount Fetch
			 document.getElementById("others_charges_amount").value = Number(others_charges_percent).toFixed(2);
			 document.getElementById('others_charges_amount').click();
			 
		}
		//Total Amount Adding
	 	net_amount();
	}
	//Percentage Apply
	function apply_percentage(clicked_id){
		//alert(clicked_id);
		var percentage = document.getElementById("discount_percent_items").value;
		var counter1 = document.getElementById("counter1").value;
		
		//Values Apply here
		document.getElementById("discount_percent").value = percentage ;
		document.getElementById('discount_percent').click();
		
		//Percentage Apply
		if(counter1>=1){
			//alert(counter1);
			for(var i=1; i<=counter1; i++){
				var value = document.getElementById("item_amount"+i).value;
				if(value>=1){
					document.getElementById("discount_percent"+i).value = Number(percentage).toFixed(2) ;
					document.getElementById('discount_percent'+i).click();
				}
			}
		}
		//Total Amount Adding
	 	net_amount();

	}
	 //Apply Percenatage
	 function discount_all(clicked_id){

	 	var id_mst_charges_discounts_items = document.getElementById("id_mst_charges_discounts_items");
		var id_mst_charges_discounts_items = id_mst_charges_discounts_items.options[id_mst_charges_discounts_items.selectedIndex].value;

		$.ajax({
				type: "POST",
				url: "../ajax/DiscountPercentageGet.php",
				data:{id_mst_charges_discounts_all:id_mst_charges_discounts_items},
				dataType: "html", 	
				success: function(data){  
					//console.log(data);
					var mydata = JSON.parse(data);
 					document.getElementById("discount_percent_items").value = mydata['percentage']; 
				}
		});
		//Total Amount Adding
	 	net_amount();
	 }
	//Others Charges Discount Tax Configurations
	function otherscharges_discount(clicked_id){ 
		var regex = /[+-]?\d+(?:\.\d+)?/g;
		var match = parseInt(regex.exec(clicked_id));
		if(match >=1){
			var id_mst_charges_discounts = document.getElementById("id_mst_charges_discounts"+match);
			var id_mst_charges_discounts = id_mst_charges_discounts.options[id_mst_charges_discounts.selectedIndex].value;  
		}else{

			var id_mst_charges_discounts = document.getElementById("id_mst_charges_discounts");
			var id_mst_charges_discounts = id_mst_charges_discounts.options[id_mst_charges_discounts.selectedIndex].value; 
		}
		var others_charges  = 'discount';

		//Ajax Via Get Percentage
		$.ajax({
				type: "POST",
				url: "../ajax/ChargesPercentageget.php",
				data:{id_mst_charges_discounts:id_mst_charges_discounts,others_charges:others_charges},
				dataType: "html", 	
				success: function(data){  
					//console.log(data);
					  var mydata = JSON.parse(data);

					if(match >=1){
						 document.getElementById("others_discount_percent"+match).value = mydata['discount']; 
						 //Discount
						 var discount = mydata['discount'];
						 var amount = document.getElementById("others_charges_amount"+match).value;
						 var netamount = Number(amount * (discount / 100)).toFixed(2);
						 document.getElementById("others_discount_amount"+match).value = Number(netamount).toFixed(2);
						 //Amount Set Here
						 document.getElementById("others_charges_sgst_amount"+match).value = 0; 
						 document.getElementById("others_charges_cgst_amount"+match).value = 0; 
						 document.getElementById("others_charges_igst_amount"+match).value = 0;

					}else{
						 document.getElementById("others_discount_percent").value = mydata['discount']; 
						 //Discount
						 var discount = mydata['discount'];
						 var amount = document.getElementById("others_charges_amount").value;
						 var netamount = (amount * (discount / 100));
						 document.getElementById("others_discount_amount").value = Number(netamount).toFixed(2); 
						 //Amount Set Here
						 document.getElementById("others_charges_sgst_amount").value = 0; 
						 document.getElementById("others_charges_cgst_amount").value = 0; 
						 document.getElementById("others_charges_igst_amount").value = 0; 
					}
				}
		});
		//Total Amount Adding
	 	net_amount();
	} 
	
	
//Others Charges Charges Percentage Get
	function charges_others(clicked_id){
		var regex = /[+-]?\d+(?:\.\d+)?/g;
		var match = parseInt(regex.exec(clicked_id));
		if(match >=1){
			var id_mst_charges_others = document.getElementById("id_mst_charges_others"+match);
			var id_mst_charges_others = id_mst_charges_others.options[id_mst_charges_others.selectedIndex].value;  
		}else{

			var id_mst_charges_others = document.getElementById("id_mst_charges_others");
			var id_mst_charges_others = id_mst_charges_others.options[id_mst_charges_others.selectedIndex].value; 
		}
		//Ledger ID
		var ledger_id = document.getElementById("ledger_id").value;

		var others_charges  = 'others';
		//Ajax Via Get Percentage
		$.ajax({
				type: "POST",
				url: "../ajax/ChargesPercentageget.php",
				data:{id_mst_charges_others:id_mst_charges_others,others_charges:others_charges,ledger_id:ledger_id},
				dataType: "html", 	
				success: function(data){  
					//console.log(data);
					  var mydata = JSON.parse(data);
var charges_account = mydata['account'];
	//alert(charges_account);				
					
if(match >=1){ 
					
if(charges_account=='4'){
	document.getElementById("type"+match).value = '1';
}else if(charges_account=='7'){
	document.getElementById("type"+match).value = '2';
}


var type = document.getElementById("type"+match);
var type = type.options[type.selectedIndex].value;

if(type == 1){
$("#others"+match).show();
$("#others_charges_percent"+match).show();
$("#discounts"+match).hide();
}else{
$("#others"+match).show();
$("#others_charges_percent"+match).hide();
$("#discounts"+match).hide();
}					
					
						 document.getElementById("others_charges_sgst_percent"+match).value = mydata['sgst'];
						 document.getElementById("others_charges_cgst_percent"+match).value = mydata['cgst'];
						 document.getElementById("others_charges_igst_percent"+match).value = mydata['igst'];
						 //ID Store Here
						 document.getElementById("id_mst_charges_sgst_others"+match).value = mydata['id_mst_charges_sgst_others'];
						 document.getElementById("id_mst_charges_cgst_others"+match).value = mydata['id_mst_charges_cgst_others'];
						 document.getElementById("id_mst_charges_igst_others"+match).value = mydata['id_mst_charges_igst_others'];
						 //SGST
						 var sgst1 = mydata['sgst'];
						
						if(sgst1 == undefined){
							 var sgst = 0;
						}else{
							 var sgst = mydata['sgst'];
						}
						 
						 var amount = document.getElementById("others_charges_amount"+match).value;
						// alert(amount);
						 var sgstnetamount = Number(amount * (sgst / 100)).toFixed(2);
						 document.getElementById("others_charges_sgst_amount"+match).value = Number(sgstnetamount).toFixed(2);
						 //CGST
						 
						 var cgst1 = mydata['cgst'];
						 if(cgst1 == undefined){
							 var cgst = 0;
						}else{
							 var cgst = mydata['cgst'];
						}
						
						 var amount = document.getElementById("others_charges_amount"+match).value;
						 var cgstnetamount = Number(amount * (cgst / 100)).toFixed;
						// alert(cgstnetamount);
						 
						 document.getElementById("others_charges_cgst_amount"+match).value =Number(cgstnetamount).toFixed(2);
						 //IGST 
						 var igst1 = mydata['igst'];
						 
						 if(igst1 == undefined){
							 var igst = 0;
						}else{
							 var igst = mydata['igst'];
						}
						 var amount = document.getElementById("others_charges_amount"+match).value;
						 var igstnetamount = Number(amount * (igst / 100)).toFixed(2);
						 document.getElementById("others_charges_igst_amount"+match).value =igstnetamount;

						 var counter2 = document.getElementById("counter2").value;

						 var sgstamount =document.getElementById("others_charges_sgst_amount").value;
						 var cgstamount =document.getElementById("others_charges_cgst_amount").value;
						 var igstamount =document.getElementById("others_charges_igst_amount").value;

						//Tax Config Method
 						var sgst2 = document.getElementById("sgst2").value;
 						var cgst2 = document.getElementById("cgst2").value;
 						var igst2 = document.getElementById("igst2").value;
 

						 if(counter2 >=1){
			 				for(var i=1;i<=counter2;i++){

			 					var condition = document.getElementById("type"+i);
								var condition = condition.options[condition.selectedIndex].value; 

							if(condition == 1){
			 					var others_charges_sgst_amount = document.getElementById("others_charges_sgst_amount"+i).value;
								var others_charges_cgst_amount = document.getElementById("others_charges_cgst_amount"+i).value;	 	
								var others_charges_igst_amount = document.getElementById("others_charges_igst_amount"+i).value;

								var value = document.getElementById("others_charges_amount"+i).value;
								if(value >=1 ){
			 						sgstamount = Number(sgstamount) + Number(others_charges_sgst_amount); 
			 						cgstamount = Number(cgstamount) + Number(others_charges_cgst_amount); 
			 						igstamount = Number(igstamount) + Number(others_charges_igst_amount); 
			 					} 
				 				}
				 			}  
				 				document.getElementById("oc_sgst_total").value =Number(sgstamount).toFixed(2); 
				 				document.getElementById("oc_sgst2").value =Number(sgstamount).toFixed(2);
				 				document.getElementById("sgst_net_amount").value =Number(Number(sgstamount) +Number(sgst2)).toFixed(2) ; 
				 				//CGST Total Section 
				 				document.getElementById("oc_cgst_total").value =Number(cgstamount).toFixed(2); 
				 				document.getElementById("oc_cgst2").value =Number(cgstamount).toFixed(2);
				 				document.getElementById("cgst_net_amount").value =Number(Number(cgstamount) +Number(cgst2)).toFixed(2) ;  
				 				//IGST  
				 				document.getElementById("oc_igst_total").value =Number(igstamount).toFixed(2); 
				 				document.getElementById("oc_igst2").value =Number(igstamount).toFixed(2);
				 				document.getElementById("igst_net_amount").value =Number(Number(igstamount) +Number(igst2)).toFixed(2) ;
				 			 	

			 			} 

					}else{
						
	
if(charges_account=='4'){
	document.getElementById("type").value = '1';
}else if(charges_account=='7'){
	document.getElementById("type").value = '2';
}
//alert(charges_account);						
						
var type = document.getElementById("type");
var type = type.options[type.selectedIndex].value;
//alert(type);

if(type == 1){
	$("#others").show();
	$("#others_charges_percent").show();
	$("#discounts").hide();
}else{
	$("#others").show();
	$("#others_charges_percent").hide();
	$("#discounts").hide();
}


	
						 document.getElementById("others_charges_sgst_percent").value = mydata['sgst'];
						 document.getElementById("others_charges_cgst_percent").value = mydata['cgst'];
						 document.getElementById("others_charges_igst_percent").value = mydata['igst'];
						 //ID Store Here
						 document.getElementById("id_mst_charges_sgst_others").value = mydata['id_mst_charges_sgst_others'];
						 document.getElementById("id_mst_charges_cgst_others").value = mydata['id_mst_charges_cgst_others'];
						 document.getElementById("id_mst_charges_igst_others").value = mydata['id_mst_charges_igst_others'];
						 //SGST
						 var sgst1 = mydata['sgst'];
						 var amount = document.getElementById("others_charges_amount").value;
						
						if(sgst1 == undefined){
							 var sgst = 0;
						}else{
							 var sgst = mydata['sgst'];
						}
						
						//alert(sgst); 
						 
						 var sgstnetamount = Number(amount * (sgst / 100)).toFixed(2);
						 document.getElementById("others_charges_sgst_amount").value = sgstnetamount;
						 //CGST
						 var cgst1 = mydata['cgst'];
						 
						if(cgst1 == undefined){
							 var cgst = 0;
						}else{
							 var cgst = mydata['cgst'];
						}
						 
						 var amount = document.getElementById("others_charges_amount").value;
						 var cgstnetamount = Number(amount * (cgst / 100)).toFixed(2);
						 document.getElementById("others_charges_cgst_amount").value =cgstnetamount;
						 //IGST 
						 var igst1 = mydata['igst'];	

						if(igst1 == undefined){
							 var igst = 0;
						}else{
							 var igst = mydata['igst'];
						}

						 
						 var amount = document.getElementById("others_charges_amount").value;
						 var igstnetamount = Number(amount * (igst / 100)).toFixed(2);
						 document.getElementById("others_charges_igst_amount").value =igstnetamount;

						 var counter2 = document.getElementById("counter2").value;						 
						 var sgstamount =0;
						 var cgstamount =0;
						 var igstamount =0;
 						
 						//Tax Config Method
 						var sgst2 = document.getElementById("sgst2").value;
 						var cgst2 = document.getElementById("cgst2").value;
 						var igst2 = document.getElementById("igst2").value;

						 if(counter2 >=1){

			 				for(var i=1;i<=counter2;i++){

			 					var condition = document.getElementById("type"+i);
								var condition = condition.options[condition.selectedIndex].value; 

							if(condition == 1){
			 					var others_charges_sgst_amount = document.getElementById("others_charges_sgst_amount"+i).value;
								var others_charges_cgst_amount = document.getElementById("others_charges_cgst_amount"+i).value;	 	
								var others_charges_igst_amount = document.getElementById("others_charges_igst_amount"+i).value;
								var value = document.getElementById("others_charges_amount"+i).value;
								if(value >=1 ){
			 						sgstamount = Number(sgstamount) + Number(others_charges_sgst_amount); 
			 						cgstamount = Number(cgstamount) + Number(others_charges_cgst_amount); 
			 						igstamount = Number(igstamount) + Number(others_charges_igst_amount); 
			 					} 
			 					}
			 				}
			 				sgstamount = Number(sgstamount) + Number(sgstnetamount);
			 				document.getElementById("oc_sgst_total").value =Number(sgstamount).toFixed(2); 
			 				document.getElementById("oc_sgst2").value =Number(sgstamount).toFixed(2); 
			 				document.getElementById("sgst_net_amount").value =Number(Number(sgstamount) + Number(sgst2)).toFixed(2);
			 				//CGST Total Section
			 				cgstamount = Number(cgstamount) + Number(cgstnetamount);
			 				document.getElementById("oc_cgst_total").value =Number(cgstamount).toFixed(2); 
			 				document.getElementById("oc_cgst2").value =Number(cgstamount).toFixed(2);
			 				document.getElementById("cgst_net_amount").value =Number(Number(cgstamount)+ Number(cgst2)).toFixed(2); 
			 				//IGST
			 				igstamount = Number(igstamount) + Number(igstnetamount);
			 				document.getElementById("oc_igst_total").value =Number(igstamount).toFixed(2); 
			 				document.getElementById("oc_igst2").value =Number(igstamount).toFixed(2); 
			 				document.getElementById("igst_net_amount").value =Number(Number(igstamount)+Number(igst2)).toFixed(2);

			 			}else{ 

			 				//Tax Config Method
	 						var sgst2 = document.getElementById("sgst2").value;
	 						var cgst2 = document.getElementById("cgst2").value;
	 						var igst2 = document.getElementById("igst2").value;

			 				var condition = document.getElementById("type");
							var condition = condition.options[condition.selectedIndex].value; 

							if(condition == 1){

				 			document.getElementById("oc_sgst_total").value =Number(sgstnetamount).toFixed(2);
				 			document.getElementById("oc_sgst1").value =Number(sgstnetamount).toFixed(2);  		 				
				 			document.getElementById("oc_sgst2").value =Number(sgstnetamount).toFixed(2);  		 				
				 			document.getElementById("sgst_net_amount").value =Number(Number(sgstnetamount)+Number(sgst2)).toFixed(2);  		 				
				 			 
				 			document.getElementById("oc_cgst_total").value =Number(cgstnetamount).toFixed(2);
				 			document.getElementById("oc_cgst1").value =Number(cgstnetamount).toFixed(2); 		 				
				 			document.getElementById("oc_cgst2").value =Number(cgstnetamount).toFixed(2);
				 			document.getElementById("cgst_net_amount").value =Number(Number(cgstnetamount)+Number(cgst2)).toFixed(2); 		 				
				 			 
				 			document.getElementById("oc_igst_total").value =Number(igstnetamount).toFixed(2);
				 			document.getElementById("oc_igst1").value =Number(igstnetamount).toFixed(2); 
				 			document.getElementById("oc_igst2").value =Number(igstnetamount).toFixed(2); 
				 			document.getElementById("igst_net_amount").value =Number(Number(igstnetamount)+Number(igst2)).toFixed(2); 
				 			}
				 		}			 				
			 			   						 						 			

					}
				}
		});
		//Total Amount Adding
	 	net_amount();
	}	
	
	
	
	//Others Charges Amount Calculation
	//Amount Calculate
	function charges_amount_calc(clicked_id){

		var regex = /[+-]?\d+(?:\.\d+)?/g;
		var match = parseInt(regex.exec(clicked_id));
		var ledger_id = document.getElementById("ledger_id").value;

		//Tax Config Method
		if(ledger_id == 1){
			var sgst2 = document.getElementById("sgst2").value;
			var cgst2 = document.getElementById("cgst2").value;
			var igst2 = 0;
		}
		if(ledger_id == 2){
			var igst2 = document.getElementById("igst2").value;
			var sgst2 = 0;
			var cgst2 = 0;
		}

		if(match >=1)
		{

			//Others Select Here
			var type =$("#type"+match).val();
			//var type = type.options[type.selectedIndex].value;
			 

			if(type == 1){

				var others_charges_amount = document.getElementById("others_charges_amount"+match).value;
				var discount = document.getElementById("others_charges_percent"+match).value;
		 		 
		 		var netamount = others_charges_amount - (others_charges_amount * (discount / 100));
		 		document.getElementById("total_amount_others"+match).value = Number(netamount).toFixed(2);
				 
		 		
		 		if(ledger_id == 1){
			 		var others_charges_sgst_percent = document.getElementById("others_charges_sgst_percent"+match).value;
					var others_charges_cgst_percent = document.getElementById("others_charges_cgst_percent"+match).value;
					var others_charges_igst_percent = 0; 
				}
				if(ledger_id == 2){
					var others_charges_igst_percent = document.getElementById("others_charges_igst_percent"+match).value;
					var others_charges_sgst_percent = 0;
					var others_charges_cgst_percent = 0;
				}
				
				//alert(others_charges_sgst_percent);


			}else if(type == 2){
				//Amount Set Here
				
				
				 $("#others_charges_sgst_amount"+match).val(0); 
				 $("#others_charges_cgst_amount"+match).val(0); 
				 $("#others_charges_igst_amount"+match).val(0); 

				//Discount Amount
				var others_charges_amount = document.getElementById("others_charges_amount"+match).value;
				var discount = document.getElementById("disc_amount_additional").value ;
				
				//alert(others_charges_sgst_percent);
				//alert(discount);

				document.getElementById("disc_amount_additional").value=Number(others_charges_amount-discount).toFixed(2);
		 		 
		 		//var discountnetamount = others_charges_amount - Number(others_charges_amount * (discount / 100)).toFixed(2);
		 		//document.getElementById("total_amount_others"+match).value = discountnetamount;

		 		//document.getElementById("disc_amount_additional1").value =discountnetamount;

				//Discount Calcualtion
				/*var others_discount_percent = document.getElementById("others_discount_percent").value; 
				var discountamount = document.getElementById("others_charges_amount").value;
				var discountnetamount = Number(discountamount * (others_discount_percent / 100)).toFixed(2);
				document.getElementById("others_discount_amount").value = discountnetamount; */

				others_charges_sgst_percent=0;
				others_charges_cgst_percent=0;
				others_charges_igst_percent=0;
			}else{

			}
			 //Tax Config Section
			if(others_charges_sgst_percent != '' && others_charges_cgst_percent != ''){
				//SGST
//alert(others_charges_sgst_percent);

if(others_charges_sgst_percent==undefined){
	var osg = '0';
}else{
	var osg = others_charges_sgst_percent;
}

if(others_charges_cgst_percent==undefined){
	var ocg = 0;
}else{
	var ocg = others_charges_cgst_percent;
}
if(others_charges_igst_percent==undefined){
	var oig = 0;
}else{
	var oig = others_charges_igst_percent;
}

//alert(osg);
				var amount = $("#others_charges_amount"+match).val();
				
				//alert(osg);
				
				var others_charges_sgst_percent = Number(amount * (Number(osg) / 100)).toFixed(2);

				document.getElementById("others_charges_sgst_amount"+match).value = Number(others_charges_sgst_percent).toFixed(2);
				//CGST
				var amount = document.getElementById("others_charges_amount"+match).value;
				var others_charges_cgst_percent = Number(amount * (ocg / 100)).toFixed(2);
				document.getElementById("others_charges_cgst_amount"+match).value = Number(others_charges_cgst_percent).toFixed(2);
			}
			if(others_charges_igst_percent != ''){
				//IGST
				var amount = document.getElementById("others_charges_amount"+match).value;
				var others_charges_igst_percent = (amount * (oig / 100));
				document.getElementById("others_charges_igst_amount"+match).value = Number(others_charges_igst_percent).toFixed(2); 
			}

			//Charges Amount Calculations   		 
				var counter2 = document.getElementById("counter2").value;
				
				
				var k=0;
				var j=0;
				
				
				for(var i=0;i<=counter2;i++){
					
					if(i==0){
						var type = document.getElementById("type");
						var type = type.options[type.selectedIndex].value;
						//alert(type);
						if(type == 2){
							var values = document.getElementById("others_charges_amount").value;
						}else{
							var values = 0;
						}
					}else{
					
						//for(var k=1;k<=counter2;k++){
							var type = document.getElementById("type"+i);
							var type = type.options[type.selectedIndex].value;
							if(type == 2){
								var sum = document.getElementById("others_charges_amount"+i).value;
								//alert(sum);
								k +=  parseFloat(sum);
								var totall = k ;
								//var totall = k - parseInt(1) ;
							}else{
								var totall =0;
							}
						}
					//}
				}
				
				var gtot = parseFloat(values) + parseFloat(totall);
				
				
				
				for(var i=0;i<=counter2;i++){
					
					if(i==0){
						var type = document.getElementById("type");
						var type = type.options[type.selectedIndex].value;
						//alert(type);
						if(type == 1){
							var values1 = document.getElementById("others_charges_amount").value;
						}else{
							var values1 = 0;
						}
					}else{
					
						//for(var j=1;j<=counter2;j++){
							var type = document.getElementById("type"+i);
							var type = type.options[type.selectedIndex].value;
							if(type == 1){
								var sum = document.getElementById("others_charges_amount"+i).value;
								//alert(sum);
								k +=  parseFloat(sum);
								var total2 = k ;
								//var total2 = j - parseInt(2) ;
							}else{
								var total2 =0;
							}
						//}
					}
				}
				
				//alert(total2);
				var otot = parseFloat(values1) + parseFloat(total2);
				
	 			var otherscharges = document.getElementById("others_charges_net_amount1").value;
	 			var disc_amount_additional1 = document.getElementById("disc_amount_additional1").value;
 
 
	 				//Calc
		 			var total = 0;  
		 			var discount_total = 0;
		 			var oc_sgst_total = 0;	 		 
	 				var oc_cgst_toal = 0;	 		 
	 				var oc_igst_toal = 0;
		 			//Tax 
		 				if(ledger_id == 1){
				 			var sgst_start = document.getElementById("others_charges_sgst_amount").value;
							var cgst_start = document.getElementById("others_charges_cgst_amount").value;
							var igst_start = 0;
						}
						if(ledger_id == 2){
							var igst_start = document.getElementById("others_charges_igst_amount").value;
							var sgst_start = 0;
							var cgst_start = 0;
						}

		 			//Calc 
		 			if(counter2 >=1){

		 				for(var i=1;i<=counter2;i++){
						//alert(counter2);
	 	 					//Charges Selection Test Here
							var type = document.getElementById("type"+i);
							var type = type.options[type.selectedIndex].value;
							//var values = document.getElementById("others_charges_amount"+i).value;
							//alert(values);

							if(type == 1){
			 					var value = document.getElementById("others_charges_amount"+i).value;
			 					if(ledger_id == 1){
				 					var others_charges_sgst_amount = document.getElementById("others_charges_sgst_amount"+i).value;
									var others_charges_cgst_amount = document.getElementById("others_charges_cgst_amount"+i).value;
									var others_charges_igst_amount = 0;
								}
								if(ledger_id == 2){	 	
									var others_charges_igst_amount = document.getElementById("others_charges_igst_amount"+i).value;
									var others_charges_sgst_amount = 0;
									var others_charges_cgst_amount = 0;
								} 
			 					if(value >=1 ){
			 						total = Number(total) + Number(value); 
			 						if(ledger_id == 1){
				 						oc_sgst_total = Number(oc_sgst_total) + Number(others_charges_sgst_amount);
				 						oc_cgst_toal = Number(oc_cgst_toal) + Number(others_charges_cgst_amount);
				 					}
				 					if(ledger_id == 2){
			 							oc_igst_toal = Number(oc_igst_toal) + Number(others_charges_igst_amount); 
			 						}
			 					} 
		 					}else if(type == 2){
		 						var values = document.getElementById("others_charges_amount"+i).value; 
								
			 					if(values >=1 ){
									//alert(discount_total);
			 						discount_total = Number(discount_total) + Number(values); 
			 					}
		 					}
		 				
		 				}
		 				//Other Charges Totla
		 				total = Number(total) + Number(otherscharges);
		 				//document.getElementById("others_charges_net_amount").value =total; 
		 				document.getElementById("others_charges_net_amount").value =otot; 
						
						
						
						
						
		 				//SGST - CGST -IGST
		 				if(ledger_id == 1){
		 					
			 				oc_sgst_total = Number(oc_sgst_total) + Number(sgst_start);

			 				document.getElementById("oc_sgst_total").value =Number(oc_sgst_total).toFixed(2);
			 				document.getElementById("oc_sgst2").value =Number(oc_sgst_total).toFixed(2);
			 				document.getElementById("sgst_net_amount").value =Number(Number(oc_sgst_total) + Number(sgst2)).toFixed(2);
			 				//CGST
			 				
			 				oc_cgst_toal = Number(Number(oc_cgst_toal)+ Number(cgst_start)).toFixed(2);
			 				
			 				document.getElementById("oc_cgst_total").value =oc_cgst_toal;
			 				document.getElementById("oc_cgst2").value =oc_cgst_toal;
			 				document.getElementById("cgst_net_amount").value =Number(Number(oc_cgst_toal) + Number(cgst2)).toFixed(2);
			 			}
			 			if(ledger_id == 2){
			 				//IGST
			 				oc_igst_toal = Number(oc_igst_toal) + Number(igst_start);
			 				document.getElementById("oc_igst_total").value =oc_igst_toal.toFixed(2);
			 				document.getElementById("oc_igst2").value =oc_igst_toal.toFixed(2);
			 				document.getElementById("igst_net_amount").value =Number(Number(oc_igst_toal) + Number(igst2)).toFixed(2); 
			 			}
		 				//Additional Discount Amount
						//alert(discount_total);
						//alert(disc_amount_additional1);
						
		 				discount_total = Number(discount_total) + Number(disc_amount_additional1);
		 				//document.getElementById("disc_amount_additional").value =Number(discount_total).toFixed(2);
						
		 				document.getElementById("disc_amount_additional").value =Number(gtot).toFixed(2);

		 			}
		 			if(match >=1){
						
		 			}else{ 
		 				//Calc
			 			var total = 0;  
			 			var oc_sgst_total = 0;	 		 
		 				var oc_cgst_toal = 0;	 		 
		 				var oc_igst_toal = 0;
			 			var discount_total = 0;
			 			//Tax 
			 			if(ledger_id == 1){
				 			var sgst_start = document.getElementById("others_charges_sgst_amount").value;
							var cgst_start = document.getElementById("others_charges_cgst_amount").value;
							var igst_start = 0;
						}
						if(ledger_id == 2){
							var igst_start = document.getElementById("others_charges_igst_amount").value;
							var sgst_start = 0;
							var cgst_start = 0;
						}
		 				//Charges Total Section 
		 				document.getElementById("others_charges_net_amount1").value =Number(others_charges_amount).toFixed(2);
		 				document.getElementById("disc_amount_additional1").value =Number(discountnetamount).toFixed(2); 

		 				var otherscharges = document.getElementById("others_charges_net_amount1").value;
	 					var disc_amount_additional1 = document.getElementById("disc_amount_additional1").value;

						if(counter2 >=1){
			 				for(var i=1;i<=counter2;i++){

	 	 					//Charges Selection Test Here
							var type = document.getElementById("type"+i);
							var type = type.options[type.selectedIndex].value;

							if(type == 1){
			 					var value = document.getElementById("others_charges_amount"+i).value; 
			 					if(ledger_id == 1){
				 					var others_charges_sgst_amount = document.getElementById("others_charges_sgst_amount"+i).value;
									var others_charges_cgst_amount = document.getElementById("others_charges_cgst_amount"+i).value;
									var others_charges_igst_amount = 0;
								}
								if(ledger_id == 2){	 	
									var others_charges_igst_amount = document.getElementById("others_charges_igst_amount"+i).value;
									var others_charges_sgst_amount = 0;
									var others_charges_cgst_amount = 0;
								}
			 					if(value >=1 ){
			 						total = Number(total) + Number(value);
			 						if(ledger_id == 1){
				 						oc_sgst_total = Number(oc_sgst_total) + Number(others_charges_sgst_amount);
				 						oc_cgst_toal = Number(oc_cgst_toal) + Number(others_charges_cgst_amount);
				 					}
				 					if(ledger_id == 2){
			 							oc_igst_toal = Number(oc_igst_toal) + Number(others_charges_igst_amount);
			 						} 
			 					} 
		 					}else if(type == 2){ 
		 						var values = document.getElementById("others_charges_amount"+i).value; 
			 					if(values >=1 ){
			 						discount_total = Number(discount_total) + Number(values); 
			 					}
		 					}
		 				
		 				}
			 				//Other Charges Totla
		 				total = Number(total) + Number(otherscharges);
		 				document.getElementById("others_charges_net_amount").value =total;
		 				if(ledger_id == 1){
			 				//SGST - CGST -IGST
			 				oc_sgst_total = Number(oc_sgst_total) + Number(sgst_start);
			 				document.getElementById("oc_sgst_total").value =Number(oc_sgst_total).toFixed(2);
			 				document.getElementById("oc_sgst2").value =Number(oc_sgst_total).toFixed(2);
			 				document.getElementById("sgst_net_amount").value =Number(Number(oc_sgst_total) + Number(sgst2)).toFixed(2);
			 				//CGST
			 				oc_cgst_toal = Number(oc_cgst_toal) + Number(cgst_start);
			 				document.getElementById("oc_cgst_total").value =Number(oc_cgst_toal).toFixed(2);
			 				document.getElementById("oc_cgst2").value =Number(oc_cgst_toal).toFixed(2);
			 				document.getElementById("cgst_net_amount").value =Number(Number(oc_cgst_toal) + Number(cgst2)).toFixed(2);
			 			}
			 			if(ledger_id == 2){
			 				//IGST
			 				oc_igst_toal = Number(oc_igst_toal) + Number(igst_start);
			 				document.getElementById("oc_igst_total").value =Number(oc_igst_toal).toFixed(2);
			 				document.getElementById("oc_igst2").value =Number(oc_igst_toal).toFixed(2);
			 				document.getElementById("igst_net_amount").value =Number(Number(oc_igst_toal) + Number(igst2)).toFixed(2);
			 			} 
			 			
		 				//Additional Discount Amount
		 				discount_total = Number(discount_total) + Number(disc_amount_additional1);
		 				document.getElementById("disc_amount_additional").value =Number(discount_total).toFixed(2); 
		 				

			 			}
		 			}  
		}else{ 

			//Others Select Here
			var type = $("#type").val();
			//var type = type.options[type.selectedIndex].value;
		

			if(type == 1){

				var others_charges_amount = document.getElementById("others_charges_amount").value;
				var discount = document.getElementById("others_charges_percent").value;
		 		 
		 		var netamount = others_charges_amount - (others_charges_amount * (discount / 100));
		 		document.getElementById("total_amount_others").value = Number(netamount).toFixed(2);
		 		document.getElementById("others_charges_net_amount1").value =Number(others_charges_amount).toFixed(2);
 
		 		if(ledger_id == 1){
					
					var others_charges_sgst_percent = document.getElementById("others_charges_sgst_percent").value;
					var others_charges_cgst_percent = document.getElementById("others_charges_cgst_percent").value;
					var others_charges_igst_percent = 0;
				}if(ledger_id == 2){
					var others_charges_igst_percent = document.getElementById("others_charges_igst_percent").value;
					var others_charges_sgst_percent = 0;
					var others_charges_cgst_percent = 0; 
				}

			}else if(type == 2){

				//Amount Set Here
				 $("#others_charges_sgst_amount").val(0); 
				 $("#others_charges_cgst_amount").val(0); 
				 $("#others_charges_igst_amount").val(0); 

				//Discount Amount
				var others_charges_amount = document.getElementById("others_charges_amount").value;
				var discount = document.getElementById("others_charges_percent").value;
		 		 
		 		var discountnetamount = others_charges_amount - Number(others_charges_amount * (discount / 100)).toFixed(2);
		 		document.getElementById("total_amount_others").value = discountnetamount;
		 		document.getElementById("disc_amount_additional1").value =discountnetamount;

			//Discount Calcualtion
				var others_discount_percent = document.getElementById("others_discount_percent").value; 
				var discountamount = document.getElementById("others_charges_amount").value;
				var discountnetamount = Number(discountamount * (others_discount_percent / 100)).toFixed(2);
				document.getElementById("others_discount_amount").value = discountnetamount; 
				 
			}else{

			}

			//Tax Configurations
			//Tax Config Section
		
//alert(others_charges_sgst_percent);		
if(others_charges_sgst_percent==undefined){
	var osg = '0';
}else{
	var osg = others_charges_sgst_percent;
}

//alert(osg);	


if(others_charges_cgst_percent=='undefined'){
	var ocg = 0;
}else{
	var ocg = others_charges_cgst_percent;
}
if(others_charges_igst_percent=='undefined'){
	var oig = 0;
}else{
	var oig = others_charges_igst_percent;
}

		
			
			if(others_charges_sgst_percent != '' && others_charges_cgst_percent != ''){
				//SGST
				var amount = document.getElementById("others_charges_amount").value;
				
				//alert(amount);
				//alert(osg);
				
				var others_charges_sgst_percent = Number(amount * (osg / 100)).toFixed(2);
				
				//alert(others_charges_sgst_percent);
				document.getElementById("others_charges_sgst_amount").value = others_charges_sgst_percent;
				//CGST
				var amount = document.getElementById("others_charges_amount").value;
				var others_charges_cgst_percent = Number(amount * (ocg / 100)).toFixed(2);
				document.getElementById("others_charges_cgst_amount").value = others_charges_cgst_percent;
			}
			if(others_charges_igst_percent != ''){
				//IGST
				var amount = document.getElementById("others_charges_amount").value;
				var others_charges_igst_percent = Number(amount * (oig / 100)).toFixed(2);
				document.getElementById("others_charges_igst_amount").value = others_charges_igst_percent; 

			}

			//Charges Amount Calculations  

	 			var counter2 = document.getElementById("counter2").value; 			
  

	 				var total = 0;	 		 
	 				var oc_sgst_total = 0;	 		 
	 				var oc_cgst_toal = 0;	 		 
	 				var oc_igst_toal = 0;	 		 
	 				//Charges Total Section	 					 
				
				if(type == 1 ){ 
					var ocharges = document.getElementById("others_charges_net_amount1").value;
					if(ledger_id == 1){
						var sgst_start = document.getElementById("others_charges_sgst_amount").value;
						var cgst_start = document.getElementById("others_charges_cgst_amount").value;
						var igst_start = 0;
					}
					if(ledger_id == 2){
						var igst_start = document.getElementById("others_charges_igst_amount").value;
						var sgst_start = 0;
						var cgst_start = 0;
					}
					
/*				
var values1 = document.getElementById("others_charges_amount").value;
var k1=0;
for(var i=1;i<=counter2;i++){
	
	var type = document.getElementById("type"+i);
	var type = type.options[type.selectedIndex].value;

	if(type == 2){
		var sum = document.getElementById("others_charges_amount"+i).value;
		//alert(sum);
		k1 +=  parseFloat(sum);
		var totall =k1;
	}
}	
var gtot1 = parseFloat(values1) + parseFloat(totall);	 */
	//alert(gtot1);			 
					 
					//Total Charges
					if(counter2 >=1){

		 				for(var i=1;i<=counter2;i++){

		 					var value = document.getElementById("others_charges_amount"+i).value; 
		 					if(ledger_id == 1){
			 					var others_charges_sgst_amount = document.getElementById("others_charges_sgst_amount"+i).value;
								var others_charges_cgst_amount = document.getElementById("others_charges_cgst_amount"+i).value;
								var others_charges_igst_amount = 0;
							}
							if(ledger_id == 2){	 	
								var others_charges_igst_amount = document.getElementById("others_charges_igst_amount"+i).value;
								var others_charges_sgst_amount = 0;
								var others_charges_cgst_amount = 0;
							}

		 					var condition = document.getElementById("type"+i);
							var condition = condition.options[condition.selectedIndex].value;  

		 					if(value >=1 && condition == 1){
		 						//Charges Total Section Here
		 						total = Number(total) + Number(value);
		 						if(ledger_id == 1){
			 						oc_sgst_total = Number(oc_sgst_total) + Number(others_charges_sgst_amount);
			 						oc_cgst_toal = Number(oc_cgst_toal) + Number(others_charges_cgst_amount);
			 					}
			 					if(ledger_id == 2){
		 							oc_igst_toal = Number(oc_igst_toal) + Number(others_charges_igst_amount);
		 						}
 
		 					}
		 				}
		 				//Charges Total Section
		 				total = Number(total) + Number(ocharges);
		 				document.getElementById("others_charges_net_amount").value =Number(total).toFixed(2);
		 				if(ledger_id == 1){
			 				//SGST - CGST -IGST
			 				oc_sgst_total = Number(oc_sgst_total) + Number(sgst_start);
			 				document.getElementById("oc_sgst_total").value =Number(oc_sgst_total).toFixed(2);
			 				document.getElementById("oc_sgst2").value =Number(oc_sgst_total).toFixed(2);
			 				document.getElementById("sgst_net_amount").value =Number(Number(oc_sgst_total) + Number(sgst2)).toFixed(2);
			 				//CGST
			 				oc_cgst_toal = Number(oc_cgst_toal) + Number(cgst_start);
			 				document.getElementById("oc_cgst_total").value =Number(oc_cgst_toal).toFixed(2);
			 				document.getElementById("oc_cgst2").value =Number(oc_cgst_toal).toFixed(2);
							
							///alert(oc_cgst_toal);
							//alert(cgst2);
							document.getElementById("cgst_net_amount").value =Number(Number(oc_cgst_toal) + Number(cgst2)).toFixed(2);
			 				//document.getElementById("cgst_net_amount").value =Number(parseFloat(oc_cgst_toal) + parseFloat(cgst2)).toFixed(2);
			 			}
			 			if(ledger_id == 2){
			 				//IGST
			 				oc_igst_toal = Number(oc_igst_toal) + Number(igst_start);
			 				document.getElementById("oc_igst_total").value =Number(oc_igst_toal).toFixed(2);
			 				document.getElementById("oc_igst2").value =Number(oc_igst_toal).toFixed(2);
			 				document.getElementById("igst_net_amount").value =Number(Number(oc_igst_toal) + Number(igst2)).toFixed(2);
			 			}

		 			}else{

		 				document.getElementById("others_charges_net_amount").value =Number(ocharges).toFixed(2);
		 				if(ledger_id == 1){
			 				//SGST
			 				document.getElementById("oc_sgst_total").value = Number(sgst_start).toFixed(2);
			 				document.getElementById("oc_sgst2").value = Number(sgst_start).toFixed(2);
							
							//alert(sgst_start);
							//alert(sgst2);
							
			 				document.getElementById("sgst_net_amount").value =Number(Number(sgst_start) + Number(sgst2)).toFixed(2);
			 				//CGST
			 				document.getElementById("oc_cgst_total").value =Number(cgst_start).toFixed(2);
			 				document.getElementById("oc_cgst2").value =Number(cgst_start).toFixed(2);
							
			 				document.getElementById("cgst_net_amount").value =Number(Number(cgst_start) + Number(cgst2)).toFixed(2);
			 			}
			 			if(ledger_id == 2){
			 				//IGST
			 				document.getElementById("oc_igst_total").value =Number(igst_start).toFixed(2);
			 				document.getElementById("oc_igst2").value =Number(igst_start).toFixed(2);
			 				document.getElementById("igst_net_amount").value =Number(Number(igst_start)+ Number(igst2)).toFixed(2);
			 			} 
		 			}
		 		}

		 		else if(type == 2){

		 			//Amount Set Here
					 document.getElementById("others_charges_sgst_amount").value = 0; 
					 document.getElementById("others_charges_cgst_amount").value = 0; 
					 document.getElementById("others_charges_igst_amount").value = 0;

		 			//Charges Discount Section 
					var ocharges_discount = document.getElementById("disc_amount_additional1").value;


					var total = 0;
		 			//Discount Amount Get
		 			if(counter2 >=1){
		 				for(var i=1;i<=counter2;i++){

		 					var value = document.getElementById("others_charges_amount"+i).value;

		 					var condition = document.getElementById("type"+i);
							var condition = condition.options[condition.selectedIndex].value;   

		 					if(value >=1 && condition == 2 ){
		 						//Charges Discount Section Here
		 						total = Number(total) + Number(value);
 
		 					}
		 				}
		 				//Charges Discount Section
		 				total = Number(total) + Number(ocharges_discount);
		 				document.getElementById("disc_amount_additional").value =total; 

		 			}else{
		 				document.getElementById("disc_amount_additional").value =ocharges_discount; 
		 			}
		 		}
			
	 	}
	 	//Total Amount Adding
	 	net_amount();

	}
	
	//Tax Config PO
	function po_locals(clicked_id){		
//alert(clicked_id);
		var regex = /[+-]?\d+(?:\.\d+)?/g;
		var match = parseInt(regex.exec(clicked_id));
		//alert(match);
		if(match >=1){
			var id_mst_charges_purchase_local = document.getElementById("id_mst_charges_purchase_local"+match);
			var id_mst_charges_purchase_local = id_mst_charges_purchase_local.options[id_mst_charges_purchase_local.selectedIndex].value
			
			$('#s_amount'+match).show();
			$('#c_amount'+match).show();  
		}else{
			var id_mst_charges_purchase_local = document.getElementById("id_mst_charges_purchase_local");
			var id_mst_charges_purchase_local = id_mst_charges_purchase_local.options[id_mst_charges_purchase_local.selectedIndex].value; 
			$('#s_amount').show();
			$('#c_amount').show();
		} 

		var po  = 'locals';

		$.ajax({
				type: "POST",
				url: "../ajax/Taxconfigs.php",
				data:{id_mst_charges_purchase_local:id_mst_charges_purchase_local,po:po},
				dataType: "html", 	
				success: function(data){  
					 var mydata = JSON.parse(data);
//alert(id_mst_charges_purchase_local);
					if(match >=1){
						//alert('with');
						//alert( mydata['sgst']);
						
						
						$( "#item_sgst_percent"+match).val(mydata['sgst']); 
						//document.getElementById("item_sgst_percent"+match).value = mydata['sgst'];
						document.getElementById("item_cgst_percent"+match).value = mydata['cgst'];
						 //Id Get Here
						document.getElementById("id_mst_charges_sgst"+match).value = mydata['id_mst_charges_sgst'];
						document.getElementById("id_mst_charges_cgst"+match).value = mydata['id_mst_charges_cgst'];
//alert('1');
						 //SGST
						 var sgst = mydata['sgst'];
						 var amount = document.getElementById("item_amount"+match).value;
						 var sgstnetamount = (amount * (sgst / 100));
						 $( "#item_sgst_amount"+match).val(Number(sgstnetamount).toFixed(2)); 
						 // $( "#item_sgst_amount").val(Number(sgstnetamount).toFixed(2)); 
						// document.getElementById("item_sgst_amount"+match).value = Number(sgstnetamount).toFixed(2);
						 $("#s_amount"+match).html('SGST: ' + Number(sgstnetamount).toFixed(2));
						 
						 
						 //CGST
						 var cgst = mydata['cgst'];
						 var amount = document.getElementById("item_amount"+match).value;
						 var cgstnetamount = (amount * (cgst / 100));
						 document.getElementById("item_cgst_amount"+match).value =Number(cgstnetamount).toFixed(2);
						$("#c_amount"+match).html('CGST: ' + Number(cgstnetamount).toFixed(2));

						 var counter1 = document.getElementById("counter1").value;
						 var sgstamount =document.getElementById("item_sgst_amount").value;
						 var cgstamount =document.getElementById("item_cgst_amount").value;

						 if(counter1 >=1){
			 				for(var i=1;i<=counter1;i++){
			 					var item_sgst_amount = $("#item_sgst_amount"+i).val();
								var item_cgst_amount = document.getElementById("item_cgst_amount"+i).value;	 	
								var value = document.getElementById("item_amount"+i).value;
								if(value >=1 ){
			 						sgstamount = Number(sgstamount) + Number(item_sgst_amount); 
			 						cgstamount = Number(cgstamount) + Number(item_cgst_amount); 
			 					} 
			 				} 
			 				document.getElementById("sgst_net_amount").value =Number(sgstamount).toFixed(2);
			 				document.getElementById("sgst2").value =Number(sgstamount).toFixed(2);
			 				//Discount Total Section 
			 				document.getElementById("cgst_net_amount").value =Number(cgstamount).toFixed(2);
			 				document.getElementById("cgst2").value =Number(cgstamount).toFixed(2);
			 				document.getElementById('qty').click(); 
			 			} 

					}else{
						//$( "#item_sgst_percent").val(mydata['sgst']);
						document.getElementById("item_sgst_percent").value = mydata['sgst'];
						 document.getElementById("item_cgst_percent").value = mydata['cgst'];
						 //Id Get Here
						 document.getElementById("id_mst_charges_sgst").value = mydata['id_mst_charges_sgst'];
						 document.getElementById("id_mst_charges_cgst").value = mydata['id_mst_charges_cgst'];
						 //SGST
						 var sgst = mydata['sgst'];
						 var amount = document.getElementById("item_amount").value;
						 var sgstnetamount = Number(amount * (sgst / 100)).toFixed(2);
						//alert(amount);
						 $( "#item_sgst_amount").val(Number(sgstnetamount).toFixed(2));
						// document.getElementById("item_sgst_amount").value = sgstnetamount; 
						 $("#s_amount").html('SGST: ' + Number(sgstnetamount).toFixed(2));
						 //CGST
						 var cgst = mydata['cgst'];
						 var amount = document.getElementById("item_amount").value;
						 var cgstnetamount = Number(amount * (cgst / 100)).toFixed(2);
						 document.getElementById("item_cgst_amount").value =Number(cgstnetamount).toFixed(2);
						 $("#c_amount").html('CGST: ' + Number(cgstnetamount).toFixed(2));

						 var counter1 = document.getElementById("counter1").value;
						 var sgstamount =0;
						 var cgstamount =0;
						 if(counter1 >=1){
			 				for(var i=1;i<=counter1;i++){

			 					//var item_sgst_amount = document.getElementById("item_sgst_amount"+i).value;
			 					var item_sgst_amount = $("#item_sgst_amount"+i).val();
								var item_cgst_amount = document.getElementById("item_cgst_amount"+i).value;	 	
								var value = document.getElementById("item_amount"+i).value;
								
								
								if(value >=1 ){
			 						sgstamount = Number(sgstamount).toFixed(2) + Number(item_sgst_amount).toFixed(2); 
			 						cgstamount = Number(cgstamount).toFixed(2) + Number(item_cgst_amount).toFixed(2); 
			 					} 
			 				}
			 				sgstamount = Number(sgstamount).toFixed(2) + Number(sgstnetamount).toFixed(2);
			 				document.getElementById("sgst_net_amount").value =Number(sgstamount).toFixed(2);
			 				document.getElementById("sgst2").value =Number(sgstamount).toFixed(2);
			 				//Discount Total Section
			 				cgstamount = Number(cgstamount).toFixed(2) + Number(cgstnetamount).toFixed(2);
			 				document.getElementById("cgst_net_amount").value =Number(cgstamount).toFixed(2);
			 				document.getElementById("cgst2").value =Number(cgstamount).toFixed(2);
			 				document.getElementById('qty').click();  

			 			}else{
				 			document.getElementById("sgst_net_amount").value =Number(sgstnetamount).toFixed(2);
				 			document.getElementById("cgst_net_amount").value =Number(cgstnetamount).toFixed(2);

				 			document.getElementById("sgst1").value =Number(sgstnetamount).toFixed(2);
				 			document.getElementById("cgst1").value =Number(cgstnetamount).toFixed(2);

				 			document.getElementById("sgst2").value =Number(sgstnetamount).toFixed(2);
				 			document.getElementById("cgst2").value =Number(cgstnetamount).toFixed(2);
				 			document.getElementById('qty').click();  
						 }
					}
				}
		});
		//Total Amount Adding
	 	net_amount(); 
	}

	//Po Tax Config IGST
	function po_interstate(clicked_id){

		var regex = /[+-]?\d+(?:\.\d+)?/g;
		var match = parseInt(regex.exec(clicked_id));
		if(match >=1){
			var id_mst_charges_purchase_interstate = document.getElementById("id_mst_charges_purchase_interstate"+match);
			var id_mst_charges_purchase_interstate = id_mst_charges_purchase_interstate.options[id_mst_charges_purchase_interstate.selectedIndex].value;
			$('#i_amount'+match).show();  
		}else{

			var id_mst_charges_purchase_interstate = document.getElementById("id_mst_charges_purchase_interstate");
			var id_mst_charges_purchase_interstate = id_mst_charges_purchase_interstate.options[id_mst_charges_purchase_interstate.selectedIndex].value; 
			$('#i_amount').show();
		} 

		var po  = 'interstate';

		$.ajax({
				type: "POST",
				url: "../ajax/Taxconfigs.php",
				data:{id_mst_charges_purchase_interstate:id_mst_charges_purchase_interstate,po:po},
				dataType: "html", 	
				success: function(data){  
					 var mydata = JSON.parse(data);

					if(match >=1){
						document.getElementById("item_igst_percent"+match).value = mydata['igst']; 
						//Id Get Here
						 document.getElementById("id_mst_charges_igst"+match).value = mydata['id_mst_charges_igst']; 
						 //IGST
						 var igst = mydata['igst'];
						 var amount = document.getElementById("item_amount"+match).value;
						 var igstnetamount = Number(amount * (igst / 100)).toFixed(2);
						 document.getElementById("item_igst_amount"+match).value = igstnetamount;				
						 $("#i_amount"+match).html('IGST: ' + igstnetamount);

						 var counter1 = document.getElementById("counter1").value;
						 var igstamount =document.getElementById("item_igst_amount").value; 

						 if(counter1 >=1){
			 				for(var i=1;i<=counter1;i++){

			 					var item_igst_amount = document.getElementById("item_igst_amount"+i).value;i	 	
								var value = document.getElementById("item_amount"+i).value;
								if(value >=1 ){
			 						igstamount = Number(igstamount) + Number(item_igst_amount);i 
			 					} 
			 				} 
			 				document.getElementById("igst_net_amount").value =Number(igstamount).toFixed(2); 
			 				document.getElementById("igst2").value =Number(igstamount).toFixed(2);
			 				document.getElementById('qty').click();  

			 			} 
				
					}else{
						 document.getElementById("item_igst_percent").value = mydata['igst']; 
						 //Id Get Here
						 document.getElementById("id_mst_charges_igst").value = mydata['id_mst_charges_igst']; 
						 //IGST
						 var igst = mydata['igst'];
						 var amount = document.getElementById("item_amount").value;
						 var igstnetamount = Number(amount * (igst / 100)).toFixed(2);
						 document.getElementById("item_igst_amount").value = Number(igstnetamount).toFixed(2);
						 $("#i_amount").html('IGST: ' + Number(igstnetamount).toFixed(2));

						 var counter1 = document.getElementById("counter1").value;
						 var igstamount =0;
						 if(counter1 >=1){
			 				for(var i=1;i<=counter1;i++){

			 					var item_igst_amount = document.getElementById("item_igst_amount"+i).value;	 	
								var value = document.getElementById("item_amount"+i).value;
								if(value >=1 ){
			 						igstamount = Number(igstamount) + Number(item_igst_amount); 
			 					} 
			 				}
			 				igstamount = Number(igstamount).toFixed(2) + Number(igstnetamount).toFixed(2);
			 				document.getElementById("igst_net_amount").value =igstamount; 
			 				document.getElementById("igst2").value =igstamount; 
			 				document.getElementById('qty').click(); 

			 			}else{
				 			document.getElementById("igst_net_amount").value =Number(igstnetamount).toFixed(2);
				 			document.getElementById("igst1").value =Number(igstnetamount).toFixed(2);
				 			document.getElementById("igst2").value =Number(igstnetamount).toFixed(2);
				 			document.getElementById('qty').click(); 
				 		}
 
					}
				}
		});

		//Total Amount Adding
	 	net_amount(); 
	}


	//Amount Calculate
	function amount_calc(clicked_id){
		//alert(); 
		var regex = /[+-]?\d+(?:\.\d+)?/g;
		var match = parseInt(regex.exec(clicked_id));

		var sgst2 = document.getElementById("oc_sgst2").value;
		var cgst2 = document.getElementById("oc_cgst2").value;
		var igst2 = document.getElementById("oc_igst2").value; 
		//alert(match);  

		if(match >=1){
			//alert();
			
			var qty = document.getElementById("qty"+match).value;
			var rate = document.getElementById("rate_per_main_unit"+match).value;
			var discount = document.getElementById("discount_percent"+match).value;
	 	//alert(qty);	
	 		//Unit Select Here
	 		var per_unit = document.getElementById("per_unit"+match);
			var per_unit = per_unit.options[per_unit.selectedIndex].value;  
			var transaction_unit = document.getElementById("transaction_unit"+match);
			var transaction_unit = transaction_unit.options[transaction_unit.selectedIndex].value; 
			var main_unit = document.getElementById("main_unit"+match).value

			if(per_unit == transaction_unit){
		 		var totals = qty * rate;
		 		var totals1 = qty * rate;
		 		var netamount = totals - (totals * (discount / 100));
		 		document.getElementById("item_amount"+match).value = Number(netamount);
		 		document.getElementById("item_amount_before_discount"+match).value = Number(totals1);
		 	}else{

		 		if(main_unit == transaction_unit){
		 			var totals = (qty*1000) * rate;
			 		var totals1 = (qty*1000) * rate;
			 		var netamount = totals - (totals * (discount / 100));
			 		document.getElementById("item_amount"+match).value = Number(netamount);
			 		document.getElementById("item_amount_before_discount"+match).value = Number(totals1);
		 		}else{

					var totals = (qty/1000) * rate;
			 		var totals1 = (qty/1000) * rate;
			 		var netamount = totals - (totals * (discount / 100));
			 		document.getElementById("item_amount"+match).value = Number(netamount);
			 		document.getElementById("item_amount_before_discount"+match).value = Number(totals1);
			 	}
		 	}

	 		//Tax Configuration
	 		var item_sgst_percent1 = document.getElementById("item_sgst_percent"+match).value;
			var item_cgst_percent1 = document.getElementById("item_cgst_percent"+match).value;
			var item_igst_percent1 = document.getElementById("item_igst_percent"+match).value;
			
if(item_cgst_percent1 == 'undefined'){
	var item_cgst_percent = 0;
}else{
	var item_cgst_percent = document.getElementById("item_cgst_percent"+match).value;
}

if(item_sgst_percent1 == 'undefined'){
	var item_sgst_percent = 0;
}else{
	var item_sgst_percent = document.getElementById("item_sgst_percent"+match).value;
}

if(item_igst_percent1 == 'undefined'){
	var item_igst_percent = 0;
}else{
	var item_igst_percent = document.getElementById("item_igst_percent"+match).value;
}			
			
//alert(item_cgst_percent);
			//Tax Config Section
			if(item_sgst_percent1 != '' && item_cgst_percent1 != ''){
				//SGST
				//alert(item_sgst_percent);
				var amount = document.getElementById("item_amount"+match).value;
				var item_sgst_percent = Number(amount * (item_sgst_percent / 100));
				document.getElementById("item_sgst_amount"+match).value = Number(item_sgst_percent).toFixed(2);
				$("#s_amount"+match).html('SGST: ' + Number(item_sgst_percent).toFixed(2));
				//CGST
				var amount = document.getElementById("item_amount"+match).value;
				var item_cgst_percent = Number(amount * (item_cgst_percent / 100));
				document.getElementById("item_cgst_amount"+match).value = item_cgst_percent;
				$("#c_amount"+match).html('CGST: ' + Number(item_cgst_percent).toFixed(2));
			}
			if(item_igst_percent1 != ''){
				//IGST
				var amount = document.getElementById("item_amount"+match).value;
				var item_igst_percent = Number(amount * (item_igst_percent / 100));
				document.getElementById("item_igst_amount"+match).value = item_igst_percent; 
				$("#i_amount"+match).html('IGST: ' + item_igst_percent);
			}

	 		//Sub Total Calculation 
			    var counter1 = document.getElementById("counter1").value;
	 			var sub_1 = document.getElementById("net_amount_items1").value;
	 			var sub = document.getElementById("sub_total_items1").value;

	 			//SGST - CGST
				var sgst_first = document.getElementById("sgst1").value;
				var cgst_first = document.getElementById("cgst1").value;
				//IGST
				var igst_first = document.getElementById("igst1").value;

	 			//Discount Section 
	 				var totalrate = document.getElementById("item_amount_before_discount").value;
	 				var discounted_price = Number(Number(totalrate / 100) * Number(discount));
	 			//Discounted Price					
					var total_discount_items = document.getElementById("total_discount_items1").value; 

	 			//Calc
	 			

	 			//Calc 
	 			if(counter1 >=1){

	 				var total = 0; 
	 				var discount_total = 0;
	 				var sgst_total = 0; 
		 			var cgst_total = 0; 
		 			var igst_total = 0;
		 			var total_rate = 0;

	 				for(var i=1;i<=counter1;i++){

	 					var qty = document.getElementById("qty"+i).value;
						var rate = document.getElementById("rate_per_main_unit"+i).value;	 					
	 					var value = document.getElementById("item_amount"+i).value;
	 					var totalrate = document.getElementById("item_amount_before_discount"+i).value;
	 					var discount = document.getElementById("discount_percent"+i).value; 

	 					//SGST- CGST 
	 					var sgst = document.getElementById("item_sgst_amount"+i).value; 
	 					//var sgst = $("#item_sgst_amount"+i).val(); 
	 					var cgst = document.getElementById("item_cgst_amount"+i).value;
	 					//IGST 
	 					var igst = document.getElementById("item_igst_amount"+i).value; 

	 					if(value >=1 ){
	 						total = Number(total) + Number(value);
	 						//Total Rate
	 						total_rate = Number(total_rate) + Number(totalrate);

	 						//Total Discount Section Here
	 						discounted_calc = Number(totalrate / 100) * Number(discount);
	 						discount_total = Number(discount_total) + Number(discounted_calc);

	 						//SGST-CGST
	 						sgst_total = Number(sgst_total) + Number(sgst);
	 						cgst_total = Number(cgst_total) + Number(cgst);
	 						//IGST
	 						igst_total = Number(igst_total) + Number(igst);
	 					} 
	 				}
	 				total = Number(total) + Number(sub);
	 				document.getElementById("net_amount_items").value =Number(total).toFixed(2);
	 				//Total Rate Section
	 				total_rate = Number(total_rate) + Number(sub_1);
	 				document.getElementById("sub_total_items").value =Number(total_rate).toFixed(2);
	 				//Discount Total Section
	 				discount_total = Number(discount_total) + Number(total_discount_items);
	 				document.getElementById("total_discount_items").value =Number(discount_total).toFixed(2);

	 				//SGST
	 				sgst_total = Number(sgst_total) + Number(sgst_first);
	 				document.getElementById("sgst_net_amount").value =Number(sgst_total).toFixed(2);
	 				document.getElementById("sgst2").value =Number(sgst_total).toFixed(2);
	 				document.getElementById("sgst_net_amount").value =Number(Number(sgst_total) + Number(sgst2)).toFixed(2);
	 				//CGST
	 				cgst_total = Number(cgst_total) + Number(cgst_first);
	 				document.getElementById("cgst_net_amount").value =Number(cgst_total).toFixed(2);
	 				document.getElementById("cgst2").value =Number(cgst_total).toFixed(2);
	 				document.getElementById("cgst_net_amount").value =Number(Number(cgst_total) + Number(cgst2)).toFixed(2);
	 				//IGST
	 				igst_total = Number(igst_total) + Number(igst_first);
	 				document.getElementById("igst_net_amount").value =Number(igst_total).toFixed(2);
	 				document.getElementById("igst2").value =Number(igst_total).toFixed(2);
	 				document.getElementById("igst_net_amount").value =Number(Number(igst_total) + Number(igst2)).toFixed(2);

	 			}
	 			if(match >=1){
					
	 			}else{

	 				var total = 0; 
	 				var discount_total = 0;
	 				var sgst_total = 0; 
		 			var cgst_total = 0; 
		 			var igst_total = 0;
		 			var total_rate = 0;

	 				//Sub Total Section
					document.getElementById("net_amount_items1").value =Number(netamount).toFixed(2);
					document.getElementById("sub_total_items1").value =Number(totals1).toFixed(2);
					document.getElementById("total_discount_items1").value =Number(discounted_price).toFixed(2);
					//SGST - CGST
					var sgst_first = document.getElementById("sgst1").value;
					var cgst_first = document.getElementById("cgst1").value;
					//IGST
					var igst_first = document.getElementById("igst1").value;

					if(counter1 >=1){
		 				for(var i=1;i<=counter1;i++){
		 					var qty = document.getElementById("qty"+i).value;
							var rate = document.getElementById("rate_per_main_unit"+i).value;
		 					var value = document.getElementById("item_amount"+i).value; 
		 					var totalrate = document.getElementById("item_amount_before_discount"+i).value; 
		 					var discount = document.getElementById("discount_percent"+i).value; 
		 					//SGST- CGST 
		 					var sgst = document.getElementById("item_sgst_amount"+i).value; 
		 					var cgst = document.getElementById("item_cgst_amount"+i).value;
		 					//IGST 
		 					var igst = document.getElementById("item_igst_amount"+i).value; 

		 					if(value >=1 ){
		 						total = Number(total) + Number(value);
		 						//Total Rate
		 						total_rate = Number(total_rate) + Number(totalrate);
		 						//Total Discount Section Here
		 						discounted_calc = (totalrate / 100) * discount;
		 						discount_total = Number(discount_total) + Number(discounted_calc);

		 						//SGST-CGST
		 						sgst_total = Number(sgst_total) + Number(sgst);
		 						cgst_total = Number(cgst_total) + Number(cgst);
		 						//IGST
		 						igst_total = Number(igst_total) + Number(igst);
		 					}
		 				}
		 				//Sub Total Section Here
		 				total = Number(total) + Number(sub);
		 				document.getElementById("net_amount_items").value =Number(total).toFixed(2);
		 				//Total Rate
		 				total_rate = Number(total_rate) + Number(sub_1);
		 				document.getElementById("sub_total_items").value =Number(total_rate).toFixed(2);

		 				//Discount Total Section
		 				discount_total = Number(discount_total) + Number(total_discount_items);
		 				document.getElementById("total_discount_items").value =Number(discount_total).toFixed(2);

		 				//SGST
		 				sgst_total = Number(sgst_total) + Number(sgst_first);
		 				document.getElementById("sgst_net_amount").value =Number(sgst_total).toFixed(2);
		 				document.getElementById("sgst2").value =Number(sgst_total).toFixed(2);
		 				document.getElementById("sgst_net_amount").value =Number(Number(sgst_total) + Number(sgst2)).toFixed(2);
		 				//CGST
		 				cgst_total = Number(cgst_total) + Number(cgst_first);
		 				document.getElementById("cgst_net_amount").value =Number(cgst_total).toFixed(2);
		 				document.getElementById("cgst2").value =Number(cgst_total).toFixed(2);
						document.getElementById("cgst_net_amount").value =Number(Number(cgst_total)+ Number(cgst2)).toFixed(2);
		 				//IGST
		 				igst_total = Number(igst_total) + Number(igst_first);
		 				document.getElementById("igst_net_amount").value =Number(igst_total).toFixed(2);
		 				document.getElementById("igst2").value =Number(igst_total).toFixed(2);
						document.getElementById("igst_net_amount").value =Number(Number(igst_total) + Number(igst2)).toFixed(2);

		 			}
	 			}							

		}else{

			var qty = document.getElementById("qty").value;
			var rate = document.getElementById("rate_per_main_unit").value;
			var discount = document.getElementById("discount_percent").value;
			//Unit Select Here 
			var per_unit = document.getElementById("per_unit");
			var per_unit = per_unit.options[per_unit.selectedIndex].value;

			var transaction_unit = document.getElementById("transaction_unit");
			var transaction_unit = transaction_unit.options[transaction_unit.selectedIndex].value;
			var main_unit = document.getElementById("main_unit").value 

			if(per_unit == transaction_unit){

		 		var totals = qty * rate;
		 		var totals1 = qty * rate;
		 		var netamount = totals - (totals * (discount / 100));
		 		document.getElementById("item_amount").value = Number(netamount).toFixed(2);
		 		document.getElementById("item_amount_before_discount").value = Number(totals1).toFixed(2);
		 	}
		 	else{

		 		if(main_unit == transaction_unit){
		 			var totals = (qty*1000) * rate;
			 		var totals1 = (qty*1000) * rate;
			 		var netamount = totals - (totals * (discount / 100));
			 		document.getElementById("item_amount").value = Number(netamount).toFixed(2);
			 		document.getElementById("item_amount_before_discount").value = Number(totals1).toFixed(2);
		 		}else{
					var totals = (qty/1000) * rate;
			 		var totals1 = (qty/1000) * rate;
			 		var netamount = totals - (totals * (discount / 100));
			 		document.getElementById("item_amount").value = Number(netamount).toFixed(2);
			 		document.getElementById("item_amount_before_discount").value = Number(totals1).toFixed(2);
		 		}
		 	}
 		 

			//Tax Config Section
			var item_sgst_percent1 = document.getElementById("item_sgst_percent").value;
			var item_cgst_percent1 = document.getElementById("item_cgst_percent").value;
			var item_igst_percent1 = document.getElementById("item_igst_percent").value;
			
			
		
if(item_cgst_percent1 == 'undefined'){
	var item_cgst_percent = 0;
}else{
	var item_cgst_percent = document.getElementById("item_cgst_percent").value;
}

if(item_sgst_percent1 == 'undefined'){
	var item_sgst_percent = 0;
}else{
	var item_sgst_percent = document.getElementById("item_sgst_percent").value;
}

if(item_igst_percent1 == 'undefined'){
	var item_igst_percent = 0;
}else{
	var item_igst_percent = document.getElementById("item_igst_percent").value;
}			
		
		

			if(item_sgst_percent1 != '' && item_cgst_percent1 != ''){
				//SGST
				var amount = document.getElementById("item_amount").value;
				
			//alert(item_sgst_percent1);	
				var item_sgst_percent = (amount * (item_sgst_percent / 100));
				//alert(item_sgst_percent);
				document.getElementById("item_sgst_amount").value = Number(item_sgst_percent).toFixed(2);
				//$("#item_sgst_amount").val(Number(item_sgst_percent).toFixed(2));
				$("#s_amount").html('SGST: ' + Number(item_sgst_percent).toFixed(2));
				//CGST
				var amount = document.getElementById("item_amount").value;
				var item_cgst_percent = (amount * (item_cgst_percent / 100));
				document.getElementById("item_cgst_amount").value = Number(item_cgst_percent).toFixed(2);
				$("#c_amount").html('CGST: ' + Number(item_cgst_percent).toFixed(2));  
			}
			if(item_igst_percent1 != ''){

				//IGST
				var amount = document.getElementById("item_amount").value;
				var item_igst_percent = (amount * (item_igst_percent / 100));
				document.getElementById("item_igst_amount").value = Number(item_igst_percent).toFixed(2);
				$("#i_amount").html('IGST: ' + Number(item_igst_percent).toFixed(2)); 
 
			}


	 		//Discount Value Get 
	 		var totalrate = document.getElementById("item_amount_before_discount").value;
	 		var discounted_price = (totalrate / 100) * discount; 

	 		//Sub Total Calculation 
	 			var counter1 = document.getElementById("counter1").value;	 			
	 			var total = 0;	 			 
	 			var discount_total = 0;
	 			var sgst_total = 0; 
	 			var cgst_total = 0; 
	 			var igst_total = 0; 
	 			var total_rate = 0; 

	 			if(match >=1){
					
	 			}else{	 	

	 				//Sub Total Section				 
					document.getElementById("net_amount_items1").value =Number(totals1);
					var sub_1 = document.getElementById("net_amount_items1").value;
					//Total Rate
					
					//alert(sub_1);
					
					
					document.getElementById("sub_total_items1").value =netamount;
					var sub = document.getElementById("sub_total_items1").value;
					//TAxConfig
					 document.getElementById("sgst1").value = item_sgst_percent;
					 document.getElementById("cgst1").value = item_cgst_percent;
					 document.getElementById("igst1").value = item_igst_percent;

					//SGST - CGST
					var sgst_first = document.getElementById("sgst1").value;
					var cgst_first = document.getElementById("cgst1").value;
					//IGST
					var igst_first = document.getElementById("igst1").value;

					//Discounted Price
					document.getElementById("total_discount_items1").value =Number(discounted_price).toFixed(2);
					var total_discount_items = document.getElementById("total_discount_items1").value;

					if(counter1 >=1){
					//alert(counter1);	
		 				for(var i=1;i<=counter1;i++){

		 					var qty = document.getElementById("qty"+i).value;
							var rate = document.getElementById("rate_per_main_unit"+i).value;
		 					var value = document.getElementById("item_amount"+i).value; 
		 					var totalrate = document.getElementById("item_amount_before_discount"+i).value; 
		 					var discount = document.getElementById("discount_percent"+i).value;
		 					//SGST- CGST 
		 					var sgst = document.getElementById("item_sgst_amount"+i).value; 
		 					//var sgst = $("#item_sgst_amount"+i).val();
		 					var cgst = document.getElementById("item_cgst_amount"+i).value;
		 					//IGST 
		 					var igst = document.getElementById("item_igst_amount"+i).value; 



		 					if(value >=1 ){
		 						//Sub Total Section Here
		 						total = Number(total) + Number(value);
		 						//Total Rate
		 						total_rate = Number(total_rate) + Number(totalrate);

		 						//Total Discount Section Here
		 						discounted_calc = (totalrate / 100) * discount;
		 						discount_total = Number(discount_total) + Number(discounted_calc);

		 						//SGST-CGST
		 						sgst_total = Number(sgst_total) + Number(sgst);
		 						cgst_total = Number(cgst_total) + Number(cgst);
		 						//IGST
		 						igst_total = Number(igst_total) + Number(igst);

		 					}
		 				}
		 				//Sub Total Section
		 				total = Number(total) + Number(sub);
		 				document.getElementById("net_amount_items").value =Number(total).toFixed(2);
		 				//Total Rate
		 				total_rate = Number(total_rate) + Number(sub_1);
		 				document.getElementById("sub_total_items").value =Number(total_rate).toFixed(2);
		 				//Discount Total Section
		 				discount_total = Number(discount_total) + Number(total_discount_items);
		 				document.getElementById("total_discount_items").value =discount_total;
		 				//SGST
						//alert(sgst_total);
						
		 				sgst_total = Number(sgst_total) + Number(sgst_first);
		 				document.getElementById("sgst_net_amount").value =Number(sgst_total).toFixed(2);
		 				document.getElementById("sgst2").value =Number(sgst_total).toFixed(2);
						
						//
						
						
		 				document.getElementById("sgst_net_amount").value =Number(Number(sgst_total) + Number(sgst2)).toFixed(2);
		 				//CGST
		 				cgst_total = Number(cgst_total) + Number(cgst_first);
		 				document.getElementById("cgst_net_amount").value =Number(cgst_total).toFixed(2);
		 				document.getElementById("cgst2").value =cgst_total;
						document.getElementById("cgst_net_amount").value =Number(Number(cgst_total) + Number(cgst2)).toFixed(2);
		 				//IGST
		 				igst_total = Number(igst_total) + Number(igst_first);
		 				document.getElementById("igst_net_amount").value =igst_total;
		 				document.getElementById("igst2").value =igst_total;
						document.getElementById("igst_net_amount").value =Number(Number(igst_total) + Number(igst2)).toFixed(2);

		 			}else{
		 				document.getElementById("sub_total_items").value =sub_1;
		 				document.getElementById("net_amount_items").value =sub;
		 				document.getElementById("total_discount_items").value =discounted_price;
		 				//SGST-CGST-IGST
		 				document.getElementById("sgst_net_amount").value =Number(sgst_first).toFixed(2);
		 				document.getElementById("sgst2").value =sgst_first;
		 				document.getElementById("sgst_net_amount").value =Number(Number(sgst_first) + Number(sgst2)).toFixed(2);
		 				//CGST
		 				document.getElementById("cgst_net_amount").value =Number(cgst_first).toFixed(2);
		 				document.getElementById("cgst2").value =cgst_first;
						document.getElementById("cgst_net_amount").value =Number(Number(cgst_first) + Number(cgst2)).toFixed(2);
						//IGST
		 				document.getElementById("igst_net_amount").value =igst_first;
		 				document.getElementById("igst2").value =igst_first;
						document.getElementById("igst_net_amount").value =Number(Number(igst_first) + Number(igst2)).toFixed(2);

		 			}
	 			}

	 	}
	 	//Total Amount Adding
	 	net_amount();

	}
	
	
	//Popup Window Data Get Here
	function dismiss(){
		var checkbox = document.getElementById("checkbox").checked;
		if(checkbox == true){
	 	    $("#checkbox").prop("checked", false);
	 	}
	 	document.getElementById('myInput').value='';
	}


function po_details(sel,clicked_id){
var id_mst_party_supplier = $("#id_mst_party_supplier").val();
var doctype = $("#doctype").val();
var eid = document.getElementById("eId").value; 
var id_inv_po = [],opt;	
var len = sel.options.length;

	for (var i = 0; i < len; i++) {
		opt = sel.options[i];	
		if (opt.selected) {
			id_inv_po.push(opt.value);
		}
	}
//alert(id_inv_po);
	
 		var regex = /[+-]?\d+(?:\.\d+)?/g;
		var match =  parseInt(regex.exec(clicked_id));
		var myArray = new Array();
		var counter1 = $("#counter1").val();
		
		
		if(isNaN(parseInt(match)) == true ){
			match = '0';
		}else{
			
		}
		
		if(counter1 == 0){
	   		myArray[0] = 0;
	    } else{
		}
		
	 	for(var i=0; i<=counter1; i++){
	 		if(i == 0){
	 			var id_inv_details = document.getElementById("id_inv_po_details").value; 
		 			myArray[i] = id_inv_details;
	 		}else{
			 	var id_inv_details = document.getElementById("id_inv_po_details"+i).value;
	 			var value = document.getElementById("item_amount"+i).value;
	 			if(value >= 0){
			 		myArray[i] = id_inv_details;
			 	}
			}
		}
		
			if(match >=1){ 
				$('#hideshow_item_code'+match).show();
				$('#hideshow_item_codes'+match).hide();
			}else{ 
				$('#hideshow_item_code').show();
				$('#hideshow_item_codes').hide();
			}
			
			var doc_type = '<?php echo $_GET['doc_type'];?>'; 
		   /* if(doc_type == 5){
		    	var phpfile = 'PopupdataPurch.php';
		    }else{
		    	var phpfile = 'PopupdataGRN.php';
		    } */

			if(doc_type == 5){
		    	var phpfile = 'GRN_details.php';
		    }else {
		    	var phpfile = 'po_details.php';
		    }

		   // Pass Ajax Data  $( "#po_list" ).append(data);  $("#not_applicable").hide();$("#polist").html(data);
			
	
			 $.ajax({
					type: "POST",
					//url: "../ajax/"+phpfile,
					url: "ajax/"+phpfile,
					data:{id_inv_po:id_inv_po, array:myArray,counter1:counter1,match:match,id_mst_party_supplier:id_mst_party_supplier,doctype:doctype,eid:eid},
					//dataType: "html", 
					datatype:'JSON',					
					success: function(data){
						//var mydata = JSON.parse(data);
						//console.log(data);//if(i==0){var k=''}else{var k=i}
						//document.getElementById('addrow4').click();
						data = JSON.parse(data);
						$("#polist").html(data.data);
						$("#taxconfig").remove();
						$("#counter1").val(data.count);
						var counerby = data.countby;
						var type = data.type;
											
						for(var i=0; i<=counerby; i++){
							if(i==0){ 
								$("#id_mst_charges_purchase_interstate").change();
								$("#id_mst_charges_purchase_local").change();
								$("#qty").click();
								//document.getElementById('qty').click();
							}else{
								$("#id_mst_charges_purchase_interstate"+i).change();
								$("#id_mst_charges_purchase_local"+i).change();
								$("#qty"+i).click();
								//document.getElementById('qty'+i).click(); 
							}
						}
						//alert(type);
					if(type != 0 && type != null){
							fetchOtherCharges(id_inv_po);
						}
						
						net_amount();
					}
			}); 
		
	}
	
	
	
	
	
	//Popup Show Section
  	function popupshow(clicked_id){
		//alert(clicked_id);
  		var id_mst_party_supplier = $("#id_mst_party_supplier").val();
		//var id_mst_party_supplier = id_mst_party_supplier.options[id_mst_party_supplier.selectedIndex].value;
 		var regex = /[+-]?\d+(?:\.\d+)?/g;
		var match = parseInt(regex.exec(clicked_id));
		var myArray = new Array();
		var counter1 = $("#counter1").val();
		if(isNaN(parseInt(match)) == true ){
			match = '0';
		}else{}
		if(counter1 == 0){
	   		myArray[0] = 0;	   		 
	    } 
	 	for(var i=0; i<=counter1; i++){
	 		if(i == 0){
	 			var id_inv_details = document.getElementById("id_inv_po_details").value; 
		 			myArray[i] = id_inv_details;
		 			
	 		}else{
			 	var id_inv_details = document.getElementById("id_inv_po_details"+i).value;
	 			var value = document.getElementById("item_amount"+i).value;
	 			if(value >= 0){
			 		myArray[i] = id_inv_details;
			 	}
			}
		}  
		if(match >=1){ 
			var id_inv_po = $("#id_inv_po"+match).val();
	    	//var id_inv_po = id_inv_po.options[id_inv_po.selectedIndex].value; 
			
		}else{
			var id_inv_po = $("#id_inv_po").val();
	    	//var id_inv_po = id_inv_po.options[id_inv_po.selectedIndex].value;
		}
		if(id_inv_po == 'na'){
			if(match >=1){ 
				$('#hideshow_item_code'+match).hide();
				$('#hideshow_item_codes'+match).show();
			}else{ 
				$('#hideshow_item_code').hide();
				$('#hideshow_item_codes').show();
			}
		}else{ 
			if(match >=1){ 
				$('#hideshow_item_code'+match).show();
				$('#hideshow_item_codes'+match).hide();
			}else{ 
				$('#hideshow_item_code').show();
				$('#hideshow_item_codes').hide();
			}
			alert(id_inv_po);
			console.log(id_inv_po);
			var doc_type = '<?php echo $_GET['doc_type'];?>'; 
		    if(doc_type == 5){
		    	var phpfile = 'PopupdatashowPurch.php';
		    }else{
		    	var phpfile = 'PopupdatashowGRN.php';
		    }
		   // Pass Ajax Data
		    $.ajax({
					type: "POST",
					url: "../ajax/"+phpfile,
					data:{id_inv_po:id_inv_po, array:myArray,counter1:counter1,match:match,id_mst_party_supplier:id_mst_party_supplier},
					dataType: "html", 	
					success: function(data){  
						//console.log(data);
						$("#myTables").html(data); 
						document.getElementById('myInput').value='';
					}
			}); 
	 	    document.getElementById('config_button').click(); 
	 	}
		
 	    //Total Amount Adding
	 	net_amount(); 
  	}

  	//Popup Show Section
  	function popupshow_checkbox(clicked_id){
//alert(clicked_id);
  		var checkbox = document.getElementById("checkbox").checked;
 		if(checkbox == true){
 			var checkbox = 1;
 		}else{
 			var checkbox = 0;
 		}
 		var id_mst_party_supplier = $("#id_mst_party_supplier").val();
		//var id_mst_party_supplier = id_mst_party_supplier.options[id_mst_party_supplier.selectedIndex].value; 

 		var regex = /[+-]?\d+(?:\.\d+)?/g;
		var match = parseInt(regex.exec(clicked_id));
		var myArray = new Array();
		var counter1 = document.getElementById("counter1").value;
		if(isNaN(parseInt(match)) == true){
			match = '0';
		}else{}
		if(counter1 == 0){
	   		myArray[0] = 0;	   		 
	    } 
	 	for(var i=0; i<=counter1; i++){
	 		if(i == 0){
	 			var id_inv_details = document.getElementById("id_inv_po_details").value; 
		 			myArray[i] = id_inv_details;
		 			
	 		}else{
			 	var id_inv_details = document.getElementById("id_inv_po_details"+i).value;
	 			var value = document.getElementById("item_amount"+i).value;
	 			if(value >= 0){
			 		myArray[i] = id_inv_details;
			 	}
			}
		}  
		if(match >=1){ 
			var id_inv_po = $("#id_inv_po"+match).val();
	    	//var id_inv_po = id_inv_po.options[id_inv_po.selectedIndex].value; 
			
		}else{
			var id_inv_po = $("#id_inv_po").val();
	    	//var id_inv_po = id_inv_po.options[id_inv_po.selectedIndex].value;
		}
		if(id_inv_po == 'na'){
			if(match >=1){ 
				$('#hideshow_item_code'+match).hide();
				$('#hideshow_item_codes'+match).show();
			}else{ 
				$('#hideshow_item_code').hide();
				$('#hideshow_item_codes').show();
			}
		}else{ 
			if(match >=1){ 
				$('#hideshow_item_code'+match).show();
				$('#hideshow_item_codes'+match).hide();
			}else{ 
				$('#hideshow_item_code').show();
				$('#hideshow_item_codes').hide();
			}
			
			var doc_type = '<?php echo $_GET['doc_type'];?>'; 
		    if(doc_type == 5){
		    	var phpfile = 'PopupdatashowPurch.php';
		    }else{
		    	var phpfile = 'PopupdatashowGRN.php';
		    }
		   // Pass Ajax Data
		    $.ajax({
					type: "POST",
					url: "../ajax/"+phpfile,
					data:{id_inv_po:id_inv_po, array:myArray,counter1:counter1,match:match,checkbox:checkbox,id_mst_party_supplier:id_mst_party_supplier},
					dataType: "html", 	
					success: function(data){  

					$("#myTables").html(data); 
						document.getElementById('myInput').value='';
					}
			});  
	 	}
 	    //Total Amount Adding
	 	net_amount(); 
  	}
	
	
	function po(){
		var ids_inv_po=[];
		var checkbox = document.getElementById("checkbox").checked;
		if(checkbox == true){
	 	    $("#checkbox").prop("checked", false);
	 	}
		var wcounts = document.getElementById("wcounts").value;
		var counter1 = document.getElementById("counter1").value; 			 
		var wmatch = document.getElementById("wmatch").value;
		var ottype = document.getElementById("ot").value;
		 
		if(wmatch == counter1) {

			if(counter1 == 0){
				var loopcounting = 0;
			}else{
				var loopcounting = counter1;
			} 
			var count = 0;

			 
			for(var i = 1; i <= wcounts; i++){

				var wselect = document.getElementById("wselect"+i).value; 		 
				if(wselect >=1 && loopcounting == 0){
					//Widnow Form Date Get Here
					var wpop = document.getElementById("wpop"+i).value;	
					ids_inv_po.push(wpop.split('|')[0]);
					var wid = document.getElementById("wid"+i).value; 
					var pono = document.getElementById("pono"+i).value; 
					var podate = document.getElementById("podate"+i).value; 
					var witemid = document.getElementById("witemid"+i).value; 	 					 
					var witem_code = document.getElementById("witem_code"+i).value;  
					var witem_description = document.getElementById("witem_description"+i).value;  
					var windent_qty = document.getElementById("windent_qty"+i).value;  
					var wbalance = document.getElementById("wbalance"+i).value;  
					var wmain_unit = document.getElementById("wmain_unit"+i).value; 
					var walt_unit = document.getElementById("walt_unit"+i).value; 
					var wtransaction_unit = document.getElementById("wtransaction_unit"+i).value; 
					var wper_unit = document.getElementById("wper_unit"+i).value; 
					var wconver_rate_per_unit = document.getElementById("wconver_rate_per_unit"+i).value; 
					var wrate_per_main_unit = document.getElementById("wrate_per_main_unit"+i).value; 
					var witem_amount = document.getElementById("witem_amount"+i).value; 
					var wdiscount_percent = document.getElementById("wdiscount_percent"+i).value; 
					var witem_remarks = document.getElementById("witem_remarks"+i).value; 
					var wid_mst_charges_purchase_local = document.getElementById("wid_mst_charges_purchase_local"+i).value;  
					var wid_mst_charges_purchase_interstate = document.getElementById("wid_mst_charges_purchase_interstate"+i).value;  
					var wlocal = document.getElementById("wlocal"+i).value;  
					var winterstate = document.getElementById("winterstate"+i).value; 
					 
					//Table Row Date Fetch Here  
					
					$("#id_inv_po").val(wpop);
					//$("#id_inv_po").change();
					document.getElementById("id_inv_po_details").value = wid;
					document.getElementById("item_code").value = witem_code;
					document.getElementById("id_inv_items").value = witemid;
					document.getElementById("item_description").value = witem_description;
					document.getElementById("qty").value = wbalance; 
					document.getElementById("main_unit").value = wmain_unit; 
					document.getElementById("alt_unit").value = walt_unit; 
					document.getElementById("conver_rate_per_unit").value = wconver_rate_per_unit; 
					document.getElementById("rate_per_main_unit").value = wrate_per_main_unit; 
					document.getElementById("item_amount").value = witem_amount; 
					document.getElementById("discount_percent").value = wdiscount_percent; 
					document.getElementById("item_remarks").value = witem_remarks; 

					document.getElementById("transaction_unit").innerHTML = "<option value='"+wtransaction_unit+"' selected='selected'>" + wtransaction_unit + "</option>"+"<option value='"+wmain_unit+"'>" + wmain_unit + "</option>"+"<option value='"+walt_unit+"'>" + walt_unit + "</option>"; 
					
					document.getElementById("per_unit").innerHTML = "<option value='"+wper_unit+"' selected='selected'>" + wper_unit + "</option>"+"<option value='"+wmain_unit+"' >" + wmain_unit + "</option>"+"<option value='"+walt_unit+"'>" + walt_unit + "</option>";
					
					//Local Set
					if(wid_mst_charges_purchase_local !=0){ 
						document.getElementById("id_mst_charges_purchase_local").innerHTML = "<option value='"+wid_mst_charges_purchase_local+"' selected='selected'>" + wlocal + "</option>"+"<?php 
	                $sql = "SELECT mst_charges.* FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '1'";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->name ?></option> <?php } ?>";
	                	$("#id_mst_charges_purchase_local").change();
	            	}
	            	//Interstate Set
					if(wid_mst_charges_purchase_interstate !=0){ 
						document.getElementById("id_mst_charges_purchase_interstate").innerHTML = "<option value='"+wid_mst_charges_purchase_interstate+"' selected='selected'>" + winterstate + "</option>"+"<?php 
	                $sql = "SELECT mst_charges.* FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '2'";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->name ?></option> <?php } ?>";
	                	$("#id_mst_charges_purchase_interstate").change();
	            	}

	            	var doc_type = '<?php echo $_GET['doc_type'];?>';
	            	//if(doc_type == '5'){
	            		var widstore = document.getElementById("widstore"+i).value; 
	            		var wstorename = document.getElementById("wstorename"+i).value; 
	            		//Store value Fetch Here
	            		document.getElementById("id_mst_attributes_store").innerHTML = "<option value='"+widstore+"' selected='selected'>" + wstorename + "</option>";
	            	//}
 

					document.getElementById('qty').click();  
					//Form Data Empty
					document.getElementById("wselect"+i).value = '';

					loopcounting = loopcounting + 1;  
					count = count + 1;

				}else if(wselect >=1 && loopcounting>= 1){ 
					// //Button Click Here
					if(count != 0){
					 	document.getElementById('addrow4').click();
					}
					var counter1 = document.getElementById("counter1").value; 

					//Widnow Form Date Get Here
					var wpop = document.getElementById("wpop"+i).value; 
					ids_inv_po.push(wpop.split('|')[0]);
					var wid = document.getElementById("wid"+i).value; 
					var podate = document.getElementById("podate"+i).value; 
					var pono = document.getElementById("pono"+i).value; 
					var witemid = document.getElementById("witemid"+i).value; 		 					 
					var witem_code = document.getElementById("witem_code"+i).value;  
					var witem_description = document.getElementById("witem_description"+i).value;  
					var windent_qty = document.getElementById("windent_qty"+i).value;  
					var wbalance = document.getElementById("wbalance"+i).value;  
					var wmain_unit = document.getElementById("wmain_unit"+i).value; 
					var walt_unit = document.getElementById("walt_unit"+i).value; 
					var wtransaction_unit = document.getElementById("wtransaction_unit"+i).value; 
					var wper_unit = document.getElementById("wper_unit"+i).value; 
					var wconver_rate_per_unit = document.getElementById("wconver_rate_per_unit"+i).value; 
					var wrate_per_main_unit = document.getElementById("wrate_per_main_unit"+i).value; 
					var witem_amount = document.getElementById("witem_amount"+i).value; 
					var wdiscount_percent = document.getElementById("wdiscount_percent"+i).value; 
					var witem_remarks = document.getElementById("witem_remarks"+i).value; 
					var wid_mst_charges_purchase_local = document.getElementById("wid_mst_charges_purchase_local"+i).value;  
					var wid_mst_charges_purchase_interstate = document.getElementById("wid_mst_charges_purchase_interstate"+i).value;  
					var wlocal = document.getElementById("wlocal"+i).value;  
					var winterstate = document.getElementById("winterstate"+i).value; 
					 
					//Table Row Date Fetch Here   
					//Purchase Order Select Here
			    	var id_mst_party_supplier = document.getElementById("id_mst_party_supplier");
				    var id_mst_party_supplier = id_mst_party_supplier.options[id_mst_party_supplier.selectedIndex].value; 
				     	var doc_type = '<?php echo $_GET['doc_type'];?>';
				     	if(doc_type == 5){
					    	var phpfile = 'Purchsuppliercreditdaysget.php';
					    }else{
					    	var phpfile = 'GRNsuppliercreditdaysget.php';
					    }

						$.ajax({
							type: "POST",
							url: "../ajax/"+phpfile,
							data:{id_mst_party_supplier:id_mst_party_supplier,counter1:counter1,wpop:wpop,pono:pono,podate:podate},
							success: function(data){
								//console.log(data);
								var mydata = JSON.parse(data); 

				    			var count = mydata['i'];
				    			var counter1 = mydata['counter1'];
				    			var wpop = mydata['wpop'];
				    			var pono = mydata['pono'];
				    			var podate = mydata['podate'];
				    			ids_inv_po.push(wpop.split('|')[0]);
				    			var doc_type = '<?php echo $_GET['doc_type'];?>';
				    			if(doc_type == 5){
				    				var join = "<option value='"+wpop+"' selected='selected'>" + pono + ' | ' + podate + "</option>";
				    			}else{
				    				var join = "<option value='"+wpop+"' selected='selected'>" + pono + ' | ' + podate + "</option>"+"<option value='na'>NA</option>";
				    			}

				    			
				    			for(var i=1; i<count;i++){
				    				if(wpop != mydata['id'+i]){
										join += "<option value='" + mydata['id'+i] + "'>" + mydata['doc_no'+i] + " | " + mydata['date'+i] + "</option>";
				    					//join += "<option value='" + mydata['id'+i] + "'>" + mydata['id'+i] + "</option>";
				    				}
				    			}  
				    			document.getElementById("id_inv_po"+counter1).innerHTML = join;

				    		}
				    	}); 

				$("#id_inv_po"+counter1).val(wpop);
					document.getElementById("id_inv_po_details"+counter1).value = wid;
					document.getElementById("item_code"+counter1).value = witem_code;
					document.getElementById("id_inv_items"+counter1).value = witemid;
					document.getElementById("item_description"+counter1).value = witem_description;
					document.getElementById("qty"+counter1).value = wbalance; 
					document.getElementById("main_unit"+counter1).value = wmain_unit; 
					document.getElementById("alt_unit"+counter1).value = walt_unit; 
					document.getElementById("conver_rate_per_unit"+counter1).value = wconver_rate_per_unit; 
					document.getElementById("rate_per_main_unit"+counter1).value = wrate_per_main_unit; 
					document.getElementById("item_amount"+counter1).value = witem_amount; 
					document.getElementById("discount_percent"+counter1).value = wdiscount_percent; 
					document.getElementById("item_remarks"+counter1).value = witem_remarks; 

					document.getElementById("transaction_unit"+counter1).innerHTML = "<option value='"+wtransaction_unit+"' selected='selected'>" + wtransaction_unit + "</option>"+"<option value='"+wmain_unit+"'>" + wmain_unit + "</option>"+"<option value='"+walt_unit+"'>" + walt_unit + "</option>"; 
					
					document.getElementById("per_unit"+counter1).innerHTML = "<option value='"+wper_unit+"' selected='selected'>" + wper_unit + "</option>"+"<option value='"+wmain_unit+"' >" + wmain_unit + "</option>"+"<option value='"+walt_unit+"'>" + walt_unit + "</option>";
					
					//Local Set
					if(wid_mst_charges_purchase_local !=0){ 
						document.getElementById("id_mst_charges_purchase_local"+counter1).innerHTML = "<option value='"+wid_mst_charges_purchase_local+"' selected='selected'>" + wlocal + "</option>"+"<?php 
	                $sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '1'";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->name ?></option> <?php } ?>";
	                $("#id_mst_charges_purchase_local"+counter1).change();
	            	}
	            	//Interstate Set
					if(wid_mst_charges_purchase_interstate !=0){ 
						document.getElementById("id_mst_charges_purchase_interstate"+counter1).innerHTML = "<option value='"+wid_mst_charges_purchase_interstate+"' selected='selected'>" + winterstate + "</option>"+"<?php 
	                $sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '2'";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->name ?></option> <?php } ?>";
	               	 $("#id_mst_charges_purchase_interstate"+counter1).change();
	            	}

	            	var doc_type = '<?php echo $_GET['doc_type'];?>';
	            	//if(doc_type == '5'){
	            		var widstore = document.getElementById("widstore"+i).value; 
	            		var wstorename = document.getElementById("wstorename"+i).value; 
	            		//Store value Fetch Here
	            	document.getElementById("id_mst_attributes_store"+counter1).innerHTML = "<option value='"+widstore+"' selected='selected'>" + wstorename + "</option>";
	            		
	            	//}
					 
					 document.getElementById('qty'+counter1).click();
					//Form Data Empty
					document.getElementById("wselect"+i).value = '';
					loopcounting = loopcounting + 1;
					count = count +1;

				}else{

				}
			}
		}else if(wmatch != counter1) {
			if(wmatch == 0){

				for(var i = 1; i <= wcounts; i++){

					var wselect = document.getElementById("wselect"+i).value; 		 
					if(wselect >=1){
						//Widnow Form Date Get Here
					var wpop = document.getElementById("wpop"+i).value;
					ids_inv_po.push(wpop.split('|')[0]);
					var wid = document.getElementById("wid"+i).value; 
					var witemid = document.getElementById("witemid"+i).value;  			 
					 
					var witem_code = document.getElementById("witem_code"+i).value;  
					var witem_description = document.getElementById("witem_description"+i).value;  
					var windent_qty = document.getElementById("windent_qty"+i).value;  
					var wbalance = document.getElementById("wbalance"+i).value;  
					var wmain_unit = document.getElementById("wmain_unit"+i).value; 
					var walt_unit = document.getElementById("walt_unit"+i).value; 
					var wtransaction_unit = document.getElementById("wtransaction_unit"+i).value; 
					var wper_unit = document.getElementById("wper_unit"+i).value; 
					var wconver_rate_per_unit = document.getElementById("wconver_rate_per_unit"+i).value; 
					var wrate_per_main_unit = document.getElementById("wrate_per_main_unit"+i).value; 
					var witem_amount = document.getElementById("witem_amount"+i).value; 
					var wdiscount_percent = document.getElementById("wdiscount_percent"+i).value; 
					var witem_remarks = document.getElementById("witem_remarks"+i).value; 
					var wid_mst_charges_purchase_local = document.getElementById("wid_mst_charges_purchase_local"+i).value;  
					var wid_mst_charges_purchase_interstate = document.getElementById("wid_mst_charges_purchase_interstate"+i).value;  
					var wlocal = document.getElementById("wlocal"+i).value;  
					var winterstate = document.getElementById("winterstate"+i).value; 
					 
					//Table Row Date Fetch Here  
					$("#id_inv_po").val(wpop).change();
					document.getElementById("id_inv_po_details").value = wid;
					document.getElementById("item_code").value = witem_code;
					document.getElementById("id_inv_items").value = witemid;
					document.getElementById("item_description").value = witem_description;
					document.getElementById("qty").value = wbalance; 
					document.getElementById("main_unit").value = wmain_unit; 
					document.getElementById("alt_unit").value = walt_unit; 
					document.getElementById("conver_rate_per_unit").value = wconver_rate_per_unit; 
					document.getElementById("rate_per_main_unit").value = wrate_per_main_unit; 
					document.getElementById("item_amount").value = witem_amount; 
					document.getElementById("discount_percent").value = wdiscount_percent; 
					document.getElementById("item_remarks").value = witem_remarks; 

					document.getElementById("transaction_unit").innerHTML = "<option value='"+wtransaction_unit+"' selected='selected'>" + wtransaction_unit + "</option>"+"<option value='"+wmain_unit+"'>" + wmain_unit + "</option>"+"<option value='"+walt_unit+"'>" + walt_unit + "</option>"; 
					
					document.getElementById("per_unit").innerHTML = "<option value='"+wper_unit+"' selected='selected'>" + wper_unit + "</option>"+"<option value='"+wmain_unit+"' >" + wmain_unit + "</option>"+"<option value='"+walt_unit+"'>" + walt_unit + "</option>";
					
					//Local Set
					if(wid_mst_charges_purchase_local !=0){ 
						document.getElementById("id_mst_charges_purchase_local").innerHTML = "<option value='"+wid_mst_charges_purchase_local+"' selected='selected'>" + wlocal + "</option>"+"<?php 
	                $sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '1'";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->name ?></option> <?php } ?>";
	                	$("#id_mst_charges_purchase_local").change();
	            	}
	            	//Interstate Set
					if(wid_mst_charges_purchase_interstate !=0){ 
						document.getElementById("id_mst_charges_purchase_interstate").innerHTML = "<option value='"+wid_mst_charges_purchase_interstate+"' selected='selected'>" + winterstate + "</option>"+"<?php 
	                $sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '2'";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->name ?></option> <?php } ?>";
	               	 $("#id_mst_charges_purchase_interstate").change();
	            	}
	            	var doc_type = '<?php echo $_GET['doc_type'];?>';
	            	//if(doc_type == '5'){
	            		var widstore = document.getElementById("widstore"+i).value; 
	            		var wstorename = document.getElementById("wstorename"+i).value; 
	            		//Store value Fetch Here
	            		document.getElementById("id_mst_attributes_store").innerHTML = "<option value='"+widstore+"' selected='selected'>" + wstorename + "</option>";
	            	//}

						document.getElementById('qty').click();				 

						//Form Data Empty
						document.getElementById("wselect"+i).value = '';
						loopcounting = loopcounting + 1;  
						count = count + 1;

					}
				}

			}else if(wmatch >=1){
				for(var i = 1; i <= wcounts; i++){

					var wselect = document.getElementById("wselect"+i).value; 		 
					if(wselect >=1){
						//Widnow Form Date Get Here 
					var wpop = document.getElementById("wpop"+i).value;
					ids_inv_po.push(wpop.split('|')[0]);
					var wid = document.getElementById("wid"+i).value; 
					var witemid = document.getElementById("witemid"+i).value; 		 					 
					var witem_code = document.getElementById("witem_code"+i).value;  
					var witem_description = document.getElementById("witem_description"+i).value;  
					var windent_qty = document.getElementById("windent_qty"+i).value;  
					var wbalance = document.getElementById("wbalance"+i).value;  
					var wmain_unit = document.getElementById("wmain_unit"+i).value; 
					var walt_unit = document.getElementById("walt_unit"+i).value; 
					var wtransaction_unit = document.getElementById("wtransaction_unit"+i).value; 
					var wper_unit = document.getElementById("wper_unit"+i).value; 
					var wconver_rate_per_unit = document.getElementById("wconver_rate_per_unit"+i).value; 
					var wrate_per_main_unit = document.getElementById("wrate_per_main_unit"+i).value; 
					var witem_amount = document.getElementById("witem_amount"+i).value; 
					var wdiscount_percent = document.getElementById("wdiscount_percent"+i).value; 
					var witem_remarks = document.getElementById("witem_remarks"+i).value; 
					var wid_mst_charges_purchase_local = document.getElementById("wid_mst_charges_purchase_local"+i).value;  
					var wid_mst_charges_purchase_interstate = document.getElementById("wid_mst_charges_purchase_interstate"+i).value;  
					var wlocal = document.getElementById("wlocal"+i).value;  
					var winterstate = document.getElementById("winterstate"+i).value; 
					 
					//Table Row Date Fetch Here   
					//Purchase Order Select Here
			    	var id_mst_party_supplier = document.getElementById("id_mst_party_supplier");
				    var id_mst_party_supplier = id_mst_party_supplier.options[id_mst_party_supplier.selectedIndex].value; 
				    
				    var doc_type = '<?php echo $_GET['doc_type'];?>'; 
				    if(doc_type == 5){
				    	var phpfile = 'Purchsuppliercreditdaysget.php';
				    }else{
				    	var phpfile = 'GRNsuppliercreditdaysget.php';
				    }
					if(id_mst_party_supplier != '') {

						$.ajax({
							type: "POST",
							url: "../ajax/"+phpfile,
							data:{id_mst_party_supplier:id_mst_party_supplier},
							success: function(data){
								//console.log(data);
								var mydata = JSON.parse(data); 

				    			var count = mydata['i'];
				    			var wpop = mydata['wpop'];
				    			ids_inv_po.push(wpop.split('|')[0]);
				    			if(doc_type == 5){
				    				var join = "<option value='"+wpop+"' selected='selected'>" + wpop + "</option>";
				    			}else{
				    				var join = "<option value='"+wpop+"' selected='selected'>" + wpop + "</option>"+"<option value='na'>NA</option>";
				    			}
				    			for(var i=1; i<count;i++){
				    				if(wpop != mydata['id'+i]){
										join += "<option value='" + mydata['id'+i] + "'>" + mydata['doc_no'+i] + " | " + mydata['date'+i] + "</option>";
				    					//join += "<option value='" + mydata['id'+i] + "'>" + mydata['id'+i] + "</option>";
				    				}
				    			} 
				    			//document.getElementById("id_inv_po"+wmatch).innerHTML = join;
				    		}
				    	});
				    }
$("#id_inv_po"+wmatch).val(wpop);
					document.getElementById("id_inv_po_details"+wmatch).value = wid;
					document.getElementById("item_code"+wmatch).value = witem_code;
					document.getElementById("id_inv_items"+wmatch).value = witemid;
					document.getElementById("item_description"+wmatch).value = witem_description;
					document.getElementById("qty"+wmatch).value = wbalance; 
					document.getElementById("main_unit"+wmatch).value = wmain_unit; 
					document.getElementById("alt_unit"+wmatch).value = walt_unit; 
					document.getElementById("conver_rate_per_unit"+wmatch).value = wconver_rate_per_unit; 
					document.getElementById("rate_per_main_unit"+wmatch).value = wrate_per_main_unit; 
					document.getElementById("item_amount"+wmatch).value = witem_amount; 
					document.getElementById("discount_percent"+wmatch).value = wdiscount_percent; 
					document.getElementById("item_remarks"+wmatch).value = witem_remarks; 

					document.getElementById("transaction_unit"+wmatch).innerHTML = "<option value='"+wtransaction_unit+"' selected='selected'>" + wtransaction_unit + "</option>"+"<option value='"+wmain_unit+"'>" + wmain_unit + "</option>"+"<option value='"+walt_unit+"'>" + walt_unit + "</option>"; 
					
					document.getElementById("per_unit"+wmatch).innerHTML = "<option value='"+wper_unit+"' selected='selected'>" + wper_unit + "</option>"+"<option value='"+wmain_unit+"' >" + wmain_unit + "</option>"+"<option value='"+walt_unit+"'>" + walt_unit + "</option>";
					
					//Local Set
					if(wid_mst_charges_purchase_local !=0){ 
						document.getElementById("id_mst_charges_purchase_local"+wmatch).innerHTML = "<option value='"+wid_mst_charges_purchase_local+"' selected='selected'>" + wlocal + "</option>"+"<?php 
	                $sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '1'";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->name ?></option> <?php } ?>";
	                $("#id_mst_charges_purchase_local"+wmatch).change();
	            	}
	            	//Interstate Set
					if(wid_mst_charges_purchase_interstate !=0){ 
						document.getElementById("id_mst_charges_purchase_interstate"+wmatch).innerHTML = "<option value='"+wid_mst_charges_purchase_interstate+"' selected='selected'>" + winterstate + "</option>"+"<?php 
	                $sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '2'";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->name ?></option> <?php } ?>";
	                	$("#id_mst_charges_purchase_interstate"+wmatch).change();
	            	} 
	            	var doc_type = '<?php echo $_GET['doc_type'];?>';
	            	//if(doc_type == '5'){
	            		var widstore = document.getElementById("widstore"+i).value; 
	            		var wstorename = document.getElementById("wstorename"+i).value; 
	            		//Store value Fetch Here
	            		document.getElementById("id_mst_attributes_store"+wmatch).innerHTML = "<option value='"+widstore+"' selected='selected'>" + wstorename + "</option>";
	            	//}

					 document.getElementById('qty'+wmatch).click();

					//Form Data Empty
					document.getElementById("wselect"+i).value = '';

					}
				}
			}else{}
		}
		// document.getElementById("doc_date").disabled = true;
		// document.getElementById("id_mst_party_supplier").disabled = true; 
		//Total Amount Adding
		
		//ES6 syntax to filter unique values for other charges
		var uniqueArray = [...new Set(ids_inv_po)];
		console.log(uniqueArray);
		
		//end other charges
		//alert(ottype);
		if(ottype!=0 && ottype!=null){
			fetchOtherCharges(uniqueArray);
		}
		
		
	 	net_amount(); 

	}
	
	

/*function po(){
		
		var ids_inv_po=[];
		var wcounts  = document.getElementById("wcounts").value;
		var counter12 = document.getElementById("counter1").value;
		var podate = document.getElementById("podate").value;
		
//alert(wcounts);


if(counter12==0){
	var counter1 = document.getElementById("counter1").value;
	var wmatch   = document.getElementById("counter1").value;
}else{
	//document.getElementById('addrow4').click();
	var counter1 = document.getElementById("counter1").value;
	var wmatch   = document.getElementById("counter1").value;
}
		
	//alert(wmatch);	
	//alert(counter1);	
	
		if(wmatch == counter1) {

			if(counter1 == 0){
				var loopcounting = 0;
			}else{
				var loopcounting = counter1;
			} 
			var count = 0;
			 
			for(var i = 1; i <= wcounts; i++){

				var wselect = document.getElementById("wselect"+i).value; 
				
			//alert(wselect);
			
				if(wselect >= 1 && loopcounting == 0){
				//alert('hi');	
					
					//Widnow Form Date Get Here
					var wpop = document.getElementById("wpop"+i).value;	
					ids_inv_po.push(wpop.split('|')[0]);
					var wid = document.getElementById("wid"+i).value; 
					var witemid = document.getElementById("witemid"+i).value; 	 					 
					var witem_code = document.getElementById("witem_code"+i).value;  
					var witem_description = document.getElementById("witem_description"+i).value;  
					var windent_qty = document.getElementById("windent_qty"+i).value;  
					var wbalance = document.getElementById("wbalance"+i).value;  
					var wmain_unit = document.getElementById("wmain_unit"+i).value; 
					var walt_unit = document.getElementById("walt_unit"+i).value; 
					var wtransaction_unit = document.getElementById("wtransaction_unit"+i).value; 
					var wper_unit = document.getElementById("wper_unit"+i).value; 
					var wconver_rate_per_unit = document.getElementById("wconver_rate_per_unit"+i).value; 
					var wrate_per_main_unit = document.getElementById("wrate_per_main_unit"+i).value; 
					var witem_amount = document.getElementById("witem_amount"+i).value; 
					var wdiscount_percent = document.getElementById("wdiscount_percent"+i).value; 
					var witem_remarks = document.getElementById("witem_remarks"+i).value; 
					var wid_mst_charges_purchase_local = document.getElementById("wid_mst_charges_purchase_local"+i).value;  
					var wid_mst_charges_purchase_interstate = document.getElementById("wid_mst_charges_purchase_interstate"+i).value;  
					var wlocal = document.getElementById("wlocal"+i).value;  
					var winterstate = document.getElementById("winterstate"+i).value; 
					 
					//Table Row Date Fetch Here


					var id_mst_party_supplier = document.getElementById("id_mst_party_supplier");
				    var id_mst_party_supplier = id_mst_party_supplier.options[id_mst_party_supplier.selectedIndex].value; 
				     	var doc_type = '<?php echo $_GET['doc_type'];?>';
				     	if(doc_type == 5){
					    	var phpfile = 'Purchsuppliercreditdaysget.php';
					    }else{
					    	var phpfile = 'GRNsuppliercreditdaysget.php';
					    }
//alert(wpop);
						$.ajax({
							type: "POST",
							url: "../ajax/"+phpfile,
							data:{id_mst_party_supplier:id_mst_party_supplier,counter1:counter1,wpop:wpop},
							success: function(data){
								//console.log(data);
								var mydata = JSON.parse(data); 
				    			var count = mydata['i'];
				    			var counter1 = mydata['counter1'];
				    			var wpop = mydata['wpop'];
				    			ids_inv_po.push(wpop.split('|')[0]);
				    			var doc_type = '<?php echo $_GET['doc_type'];?>';
										
				    			if(doc_type == 5){
				    				//var join = "<option value='"+wpop+"' selected='selected'>" + wpop + " | " + mydata['date'+i] + "</option>";
				    				var join = "<option value='"+wpop+"' selected='selected'>" + wpop + " | " + podate + "</option>";
				    			}else{
				    				var join = "<option value='"+wpop+"' selected='selected'>" + wpop+ " | " + podate +  "</option>";
				    			}

				    			
				    			
				    			for(var i=1; i<count;i++){
									if(wpop != mydata['id'+i]){	
				    					join += "<option value='" + mydata['id'+i] + "'>" + mydata['id'+i]  + " | " + mydata['date'+i] + "</option>";
				    				}
				    			}
				    			document.getElementById("id_inv_po").innerHTML = join;
				    			//document.getElementById("id_inv_poo").innerHTML = join;
				    		}
				    	}); 


					$("#id_inv_po").val(wpop);
					//$("#id_inv_po").change();
					document.getElementById("id_inv_po_details").value = wid;
					document.getElementById("item_code").value = witem_code;
					document.getElementById("id_inv_items").value = witemid;
					document.getElementById("item_description").value = witem_description;
					document.getElementById("qty").value = wbalance; 
					document.getElementById("main_unit").value = wmain_unit; 
					document.getElementById("alt_unit").value = walt_unit; 
					document.getElementById("conver_rate_per_unit").value = wconver_rate_per_unit; 
					document.getElementById("rate_per_main_unit").value = wrate_per_main_unit; 
					document.getElementById("item_amount").value = witem_amount; 
					document.getElementById("discount_percent").value = wdiscount_percent; 
					document.getElementById("item_remarks").value = witem_remarks; 

					document.getElementById("transaction_unit").innerHTML = "<option value='"+wtransaction_unit+"' selected='selected'>" + wtransaction_unit + "</option>"+"<option value='"+wmain_unit+"'>" + wmain_unit + "</option>"+"<option value='"+walt_unit+"'>" + walt_unit + "</option>"; 
					
					document.getElementById("per_unit").innerHTML = "<option value='"+wper_unit+"' selected='selected'>" + wper_unit + "</option>"+"<option value='"+wmain_unit+"' >" + wmain_unit + "</option>"+"<option value='"+walt_unit+"'>" + walt_unit + "</option>";
					
					//Local Set
					if(wid_mst_charges_purchase_local !=0){ 
						document.getElementById("id_mst_charges_purchase_local").innerHTML = "<option value='"+wid_mst_charges_purchase_local+"' selected='selected'>" + wlocal + "</option>"+"<?php 
	                $sql = "SELECT mst_charges.* FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '1'";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->name ?></option> <?php } ?>";
	                	$("#id_mst_charges_purchase_local").change();
	            	}
	            	//Interstate Set
					if(wid_mst_charges_purchase_interstate !=0){ 
						document.getElementById("id_mst_charges_purchase_interstate").innerHTML = "<option value='"+wid_mst_charges_purchase_interstate+"' selected='selected'>" + winterstate + "</option>"+"<?php 
	                $sql = "SELECT mst_charges.* FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '2'";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->name ?></option> <?php } ?>";
	                	$("#id_mst_charges_purchase_interstate").change();
	            	}

	            	var doc_type = '<?php echo $_GET['doc_type'];?>';
	            	//if(doc_type == '5'){
	            		var widstore = document.getElementById("widstore"+i).value; 
	            		var wstorename = document.getElementById("wstorename"+i).value; 
	            		//Store value Fetch Here
	            		document.getElementById("id_mst_attributes_store").innerHTML = "<option value='"+widstore+"' selected='selected'>" + wstorename + "</option>";
						
	            	//}
 
				document.getElementById('qty').click();  
					//Form Data Empty
					
				document.getElementById("wselect"+i).value = '';
					
					loopcounting = loopcounting + 1;  
					count = count + 1;

				}else if(wselect >=1 && loopcounting>= 1){ 
					// //Button Click Here
					
			//alert(count);
			
					if(count != 0){
					 	document.getElementById('addrow4').click();
					}
					var counter1 = document.getElementById("counter1").value; 
//alert(counter1);
					//Widnow Form Date Get Here
					var wpop = document.getElementById("wpop"+i).value; 
					ids_inv_po.push(wpop.split('|')[0]);
					var wid = document.getElementById("wid"+i).value; 
					var witemid = document.getElementById("witemid"+i).value; 		 					 
					var witem_code = document.getElementById("witem_code"+i).value;  
					var witem_description = document.getElementById("witem_description"+i).value;  
					var windent_qty = document.getElementById("windent_qty"+i).value;  
					var wbalance = document.getElementById("wbalance"+i).value;  
					var wmain_unit = document.getElementById("wmain_unit"+i).value; 
					var walt_unit = document.getElementById("walt_unit"+i).value; 
					var wtransaction_unit = document.getElementById("wtransaction_unit"+i).value; 
					var wper_unit = document.getElementById("wper_unit"+i).value; 
					var wconver_rate_per_unit = document.getElementById("wconver_rate_per_unit"+i).value; 
					var wrate_per_main_unit = document.getElementById("wrate_per_main_unit"+i).value; 
					var witem_amount = document.getElementById("witem_amount"+i).value; 
					var wdiscount_percent = document.getElementById("wdiscount_percent"+i).value; 
					var witem_remarks = document.getElementById("witem_remarks"+i).value; 
					var wid_mst_charges_purchase_local = document.getElementById("wid_mst_charges_purchase_local"+i).value;  
					var wid_mst_charges_purchase_interstate = document.getElementById("wid_mst_charges_purchase_interstate"+i).value;  
					var wlocal = document.getElementById("wlocal"+i).value;  
					var winterstate = document.getElementById("winterstate"+i).value; 
					 
					//Table Row Date Fetch Here   
					//Purchase Order Select Here
			    	var id_mst_party_supplier = document.getElementById("id_mst_party_supplier");
				    var id_mst_party_supplier = id_mst_party_supplier.options[id_mst_party_supplier.selectedIndex].value; 
				     	var doc_type = '<?php echo $_GET['doc_type'];?>';
				     	if(doc_type == 5){
					    	var phpfile = 'Purchsuppliercreditdaysget.php';
					    }else{
					    	var phpfile = 'GRNsuppliercreditdaysget.php';
					    }
//alert(wpop);
						$.ajax({
							type: "POST",
							url: "../ajax/"+phpfile,
							data:{id_mst_party_supplier:id_mst_party_supplier,counter1:counter1,wpop:wpop},
							success: function(data){
								//console.log(data);
								var mydata = JSON.parse(data); 

				    			var count = mydata['i'];
				    			var counter1 = mydata['counter1'];
				    			var wpop = mydata['wpop'];
				    			ids_inv_po.push(wpop.split('|')[0]);
				    			var doc_type = '<?php echo $_GET['doc_type'];?>';
								
				    			if(doc_type == 5){
				    				var join = "<option value='"+wpop+"' selected='selected'>" + wpop + " | " + podate  + "</option>";
				    			}else{
				    				var join = "<option value='"+wpop+"' selected='selected'>" + wpop + " | " + podate + "</option>";
				    			}

				    			
				    			for(var i=1; i<count;i++){
				    				if(wpop != mydata['id'+i]){
				    					join += "<option value='" + mydata['id'+i] + "'>" + mydata['id'+i]  + " | " + mydata['date'+i] + "</option>";
				    				}
				    			}  
				    			document.getElementById("id_inv_po"+counter1).innerHTML = join;
				    			//document.getElementById("id_inv_poo").innerHTML = join;
				    		}
				    	}); 

					document.getElementById("id_inv_po_details"+counter1).value = wid;
					document.getElementById("item_code"+counter1).value = witem_code;
					document.getElementById("id_inv_items"+counter1).value = witemid;
					document.getElementById("item_description"+counter1).value = witem_description;
					document.getElementById("qty"+counter1).value = wbalance; 
					document.getElementById("main_unit"+counter1).value = wmain_unit; 
					document.getElementById("alt_unit"+counter1).value = walt_unit; 
					document.getElementById("conver_rate_per_unit"+counter1).value = wconver_rate_per_unit; 
					document.getElementById("rate_per_main_unit"+counter1).value = wrate_per_main_unit; 
					document.getElementById("item_amount"+counter1).value = witem_amount; 
					document.getElementById("discount_percent"+counter1).value = wdiscount_percent; 
					document.getElementById("item_remarks"+counter1).value = witem_remarks; 

					document.getElementById("transaction_unit"+counter1).innerHTML = "<option value='"+wtransaction_unit+"' selected='selected'>" + wtransaction_unit + "</option>"+"<option value='"+wmain_unit+"'>" + wmain_unit + "</option>"+"<option value='"+walt_unit+"'>" + walt_unit + "</option>"; 
					
					document.getElementById("per_unit"+counter1).innerHTML = "<option value='"+wper_unit+"' selected='selected'>" + wper_unit + "</option>"+"<option value='"+wmain_unit+"' >" + wmain_unit + "</option>"+"<option value='"+walt_unit+"'>" + walt_unit + "</option>";
					
					//Local Set
					if(wid_mst_charges_purchase_local !=0){ 
						document.getElementById("id_mst_charges_purchase_local"+counter1).innerHTML = "<option value='"+wid_mst_charges_purchase_local+"' selected='selected'>" + wlocal + "</option>"+"<?php 
	                $sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '1'";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->name ?></option> <?php } ?>";
	                $("#id_mst_charges_purchase_local"+counter1).change();
	            	}
	            	//Interstate Set
					if(wid_mst_charges_purchase_interstate !=0){ 
						document.getElementById("id_mst_charges_purchase_interstate"+counter1).innerHTML = "<option value='"+wid_mst_charges_purchase_interstate+"' selected='selected'>" + winterstate + "</option>"+"<?php 
	                $sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '2'";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->name ?></option> <?php } ?>";
	               	 $("#id_mst_charges_purchase_interstate"+counter1).change();
	            	}

	            	var doc_type = '<?php echo $_GET['doc_type'];?>';
	            	//if(doc_type == '5'){
	            		var widstore = document.getElementById("widstore"+i).value; 
	            		var wstorename = document.getElementById("wstorename"+i).value; 
	            		//Store value Fetch Here
	            		document.getElementById("id_mst_attributes_store"+counter1).innerHTML = "<option value='"+widstore+"' selected='selected'>" + wstorename + "</option>";
	            	//}
					 
					 document.getElementById('qty'+counter1).click();
					//Form Data Empty
					document.getElementById("wselect"+i).value = '';
					loopcounting = loopcounting + 1;
					count = count +1;

				}else{

				}
			}
		}
							
		
		
		else if(wmatch != counter1) {
			
			//alert(wmatch);	
			if(wmatch == 0){

				for(var i = 1; i <= wcounts; i++){

					var wselect = document.getElementById("wselect"+i).value; 
				
					if(wselect >=1){
						
						//alert(wselect);	
						//Widnow Form Date Get Here
					var wpop = document.getElementById("wpop"+i).value;
					ids_inv_po.push(wpop.split('|')[0]);
					var wid = document.getElementById("wid"+i).value; 
					var witemid = document.getElementById("witemid"+i).value;  			 
					 
					var witem_code = document.getElementById("witem_code"+i).value;  
					var witem_description = document.getElementById("witem_description"+i).value;  
					var windent_qty = document.getElementById("windent_qty"+i).value;  
					var wbalance = document.getElementById("wbalance"+i).value;  
					var wmain_unit = document.getElementById("wmain_unit"+i).value; 
					var walt_unit = document.getElementById("walt_unit"+i).value; 
					var wtransaction_unit = document.getElementById("wtransaction_unit"+i).value; 
					var wper_unit = document.getElementById("wper_unit"+i).value; 
					var wconver_rate_per_unit = document.getElementById("wconver_rate_per_unit"+i).value; 
					var wrate_per_main_unit = document.getElementById("wrate_per_main_unit"+i).value; 
					var witem_amount = document.getElementById("witem_amount"+i).value; 
					var wdiscount_percent = document.getElementById("wdiscount_percent"+i).value; 
					var witem_remarks = document.getElementById("witem_remarks"+i).value; 
					var wid_mst_charges_purchase_local = document.getElementById("wid_mst_charges_purchase_local"+i).value;  
					var wid_mst_charges_purchase_interstate = document.getElementById("wid_mst_charges_purchase_interstate"+i).value;  
					var wlocal = document.getElementById("wlocal"+i).value;  
					var winterstate = document.getElementById("winterstate"+i).value; 
					 
					//Table Row Date Fetch Here  
					$("#id_inv_po").val(wpop).change();
					document.getElementById("id_inv_po_details").value = wid;
					document.getElementById("item_code").value = witem_code;
					document.getElementById("id_inv_items").value = witemid;
					document.getElementById("item_description").value = witem_description;
					document.getElementById("qty").value = wbalance; 
					document.getElementById("main_unit").value = wmain_unit; 
					document.getElementById("alt_unit").value = walt_unit; 
					document.getElementById("conver_rate_per_unit").value = wconver_rate_per_unit; 
					document.getElementById("rate_per_main_unit").value = wrate_per_main_unit; 
					document.getElementById("item_amount").value = witem_amount; 
					document.getElementById("discount_percent").value = wdiscount_percent; 
					document.getElementById("item_remarks").value = witem_remarks; 

					document.getElementById("transaction_unit").innerHTML = "<option value='"+wtransaction_unit+"' selected='selected'>" + wtransaction_unit + "</option>"+"<option value='"+wmain_unit+"'>" + wmain_unit + "</option>"+"<option value='"+walt_unit+"'>" + walt_unit + "</option>"; 
					
					document.getElementById("per_unit").innerHTML = "<option value='"+wper_unit+"' selected='selected'>" + wper_unit + "</option>"+"<option value='"+wmain_unit+"' >" + wmain_unit + "</option>"+"<option value='"+walt_unit+"'>" + walt_unit + "</option>";
					
					//Local Set
					if(wid_mst_charges_purchase_local !=0){ 
						document.getElementById("id_mst_charges_purchase_local").innerHTML = "<option value='"+wid_mst_charges_purchase_local+"' selected='selected'>" + wlocal + "</option>"+"<?php 
	                $sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '1'";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->name ?></option> <?php } ?>";
	                	$("#id_mst_charges_purchase_local").change();
	            	}
	            	//Interstate Set
					if(wid_mst_charges_purchase_interstate !=0){ 
						document.getElementById("id_mst_charges_purchase_interstate").innerHTML = "<option value='"+wid_mst_charges_purchase_interstate+"' selected='selected'>" + winterstate + "</option>"+"<?php 
	                $sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '2'";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->name ?></option> <?php } ?>";
	               	 $("#id_mst_charges_purchase_interstate").change();
	            	}
	            	var doc_type = '<?php echo $_GET['doc_type'];?>';
	            	//if(doc_type == '5'){
	            		var widstore = document.getElementById("widstore"+i).value; 
	            		var wstorename = document.getElementById("wstorename"+i).value; 
	            		//Store value Fetch Here
	            		document.getElementById("id_mst_attributes_store").innerHTML = "<option value='"+widstore+"' selected='selected'>" + wstorename + "</option>";
	            	//}

						document.getElementById('qty').click();				 

						//Form Data Empty
						document.getElementById("wselect"+i).value = '';
						loopcounting = loopcounting + 1;  
						count = count + 1;

					}
				}

			}else if(wmatch >=1){
				
		//alert(wmatch);	
				
				for(var i = 1; i <= wcounts; i++){
//alert('2');
					var wselect = document.getElementById("wselect"+i).value; 		 
					if(wselect >=1){
						//Widnow Form Date Get Here 
					var wpop = document.getElementById("wpop"+i).value;
					ids_inv_po.push(wpop.split('|')[0]);
					var wid = document.getElementById("wid"+i).value; 
					var witemid = document.getElementById("witemid"+i).value; 		 					 
					var witem_code = document.getElementById("witem_code"+i).value;  
					var witem_description = document.getElementById("witem_description"+i).value;  
					var windent_qty = document.getElementById("windent_qty"+i).value;  
					var wbalance = document.getElementById("wbalance"+i).value;  
					var wmain_unit = document.getElementById("wmain_unit"+i).value; 
					var walt_unit = document.getElementById("walt_unit"+i).value; 
					var wtransaction_unit = document.getElementById("wtransaction_unit"+i).value; 
					var wper_unit = document.getElementById("wper_unit"+i).value; 
					var wconver_rate_per_unit = document.getElementById("wconver_rate_per_unit"+i).value; 
					var wrate_per_main_unit = document.getElementById("wrate_per_main_unit"+i).value; 
					var witem_amount = document.getElementById("witem_amount"+i).value; 
					var wdiscount_percent = document.getElementById("wdiscount_percent"+i).value; 
					var witem_remarks = document.getElementById("witem_remarks"+i).value; 
					var wid_mst_charges_purchase_local = document.getElementById("wid_mst_charges_purchase_local"+i).value;  
					var wid_mst_charges_purchase_interstate = document.getElementById("wid_mst_charges_purchase_interstate"+i).value;  
					var wlocal = document.getElementById("wlocal"+i).value;  
					var winterstate = document.getElementById("winterstate"+i).value; 
					 
					//Table Row Date Fetch Here   
					//Purchase Order Select Here
			    	var id_mst_party_supplier = document.getElementById("id_mst_party_supplier");
				    var id_mst_party_supplier = id_mst_party_supplier.options[id_mst_party_supplier.selectedIndex].value; 
				    
				    var doc_type = '<?php echo $_GET['doc_type'];?>'; 
				    if(doc_type == 5){
				    	var phpfile = 'Purchsuppliercreditdaysget.php';
				    }else{
				    	var phpfile = 'GRNsuppliercreditdaysget.php';
				    }
					if(id_mst_party_supplier != '') {

						$.ajax({
							type: "POST",
							url: "../ajax/"+phpfile,
							data:{id_mst_party_supplier:id_mst_party_supplier},
							success: function(data){
								//console.log(data);
								var mydata = JSON.parse(data); 

				    			var count = mydata['i'];
				    			var wpop = mydata['wpop'];
				    			ids_inv_po.push(wpop.split('|')[0]);
				    			if(doc_type == 5){
				    				var join = "<option value='"+wpop+"' selected='selected'>" + wpop + "</option>";
				    			}else{
				    				var join = "<option value='"+wpop+"' selected='selected'>" + wpop + "</option>"+"<option value='na'>NA</option>";
				    			}
				    			for(var i=1; i<count;i++){
				    				if(wpop != mydata['id'+i]){
				    					join += "<option value='" + mydata['id'+i] + "'>" + mydata['id'+i] + "</option>";
				    				}
				    			} 
				    			document.getElementById("id_inv_po"+wmatch).innerHTML = join;
				    			document.getElementById("id_inv_poo").innerHTML = join;
				    		}
				    	});
				    }
					
					document.getElementById("id_inv_po_details"+wmatch).value = wid;
					document.getElementById("item_code"+wmatch).value = witem_code;
					document.getElementById("id_inv_items"+wmatch).value = witemid;
					document.getElementById("item_description"+wmatch).value = witem_description;
					document.getElementById("qty"+wmatch).value = wbalance; 
					document.getElementById("main_unit"+wmatch).value = wmain_unit; 
					document.getElementById("alt_unit"+wmatch).value = walt_unit; 
					document.getElementById("conver_rate_per_unit"+wmatch).value = wconver_rate_per_unit; 
					document.getElementById("rate_per_main_unit"+wmatch).value = wrate_per_main_unit; 
					document.getElementById("item_amount"+wmatch).value = witem_amount; 
					document.getElementById("discount_percent"+wmatch).value = wdiscount_percent; 
					document.getElementById("item_remarks"+wmatch).value = witem_remarks; 

					document.getElementById("transaction_unit"+wmatch).innerHTML = "<option value='"+wtransaction_unit+"' selected='selected'>" + wtransaction_unit + "</option>"+"<option value='"+wmain_unit+"'>" + wmain_unit + "</option>"+"<option value='"+walt_unit+"'>" + walt_unit + "</option>"; 
					
					document.getElementById("per_unit"+wmatch).innerHTML = "<option value='"+wper_unit+"' selected='selected'>" + wper_unit + "</option>"+"<option value='"+wmain_unit+"' >" + wmain_unit + "</option>"+"<option value='"+walt_unit+"'>" + walt_unit + "</option>";
					
					//Local Set
					if(wid_mst_charges_purchase_local !=0){ 
						document.getElementById("id_mst_charges_purchase_local"+wmatch).innerHTML = "<option value='"+wid_mst_charges_purchase_local+"' selected='selected'>" + wlocal + "</option>"+"<?php 
	                $sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '1'";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->name ?></option> <?php } ?>";
	                $("#id_mst_charges_purchase_local"+wmatch).change();
	            	}
	            	//Interstate Set
					if(wid_mst_charges_purchase_interstate !=0){ 
						document.getElementById("id_mst_charges_purchase_interstate"+wmatch).innerHTML = "<option value='"+wid_mst_charges_purchase_interstate+"' selected='selected'>" + winterstate + "</option>"+"<?php 
	                $sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '2'";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->name ?></option> <?php } ?>";
	                	$("#id_mst_charges_purchase_interstate"+wmatch).change();
	            	} 
	            	var doc_type = '<?php echo $_GET['doc_type'];?>';
	            	//if(doc_type == '5'){
	            		var widstore = document.getElementById("widstore"+i).value; 
	            		var wstorename = document.getElementById("wstorename"+i).value; 
	            		//Store value Fetch Here
	            		document.getElementById("id_mst_attributes_store"+wmatch).innerHTML = "<option value='"+widstore+"' selected='selected'>" + wstorename + "</option>";
	            	//}

					 document.getElementById('qty'+wmatch).click();

					//Form Data Empty
					document.getElementById("wselect"+i).value = '';

					}
				}
			}else{}
		}
		
		
		// document.getElementById("doc_date").disabled = true;
		// document.getElementById("id_mst_party_supplier").disabled = true; 
		//Total Amount Adding
		
		//ES6 syntax to filter unique values for other charges
		var uniqueArray = [...new Set(ids_inv_po)];
		console.log(uniqueArray);
		
		//end other charges

	 	//fetchOtherCharges(uniqueArray);
		net_amount(); 

	} */
</script> 
<script type="text/javascript">

	//Ledger
	 

	//Charges Select
	function type_funt(clicked_id){

		var regex = /[+-]?\d+(?:\.\d+)?/g;
		var match = parseInt(regex.exec(clicked_id));



		if(match >=1){

			var type = document.getElementById("type"+match);
		    var type = type.options[type.selectedIndex].value;

		    if(type == 1){
		    	$("#otherss"+match).show();
		    	$("#others_charges_percent"+match).show();
		    	$("#dis"+match).hide();
		    }else{
		    	$("#otherss"+match).hide();
		    	$("#others_charges_percent"+match).hide();
		    	$("#dis"+match).show();
		    }

		}else{
			var type = document.getElementById("type");
		    var type = type.options[type.selectedIndex].value;

		    if(type == 1){
		    	$("#others").show();
		    	$("#others_charges_percent").show();
		    	$("#discounts").hide();
		    }else{
		    	$("#others").hide();
		    	$("#others_charges_percent").hide();
		    	$("#discounts").show();
		    }
		}
		//Total Amount Adding
	 	net_amount();
	}

	
  	 

  //Hide And Show Method

	function hideandshow() {
		var doc_type = '<?php echo $_GET['doc_type'];?>'; 
	//	var doc_type = document.getElementById("doc_type");
	  //  var doc_type = doc_type.options[doc_type.selectedIndex].value;



	    var doc_date = document.getElementById("doc_date").value; 
	    document.getElementById("doc_date1").value = doc_date; 
		 
		if(doc_type != '' && doc_date !='') {
			$('#ind').show(); 
			
			$.ajax({
				type: "POST",
				url: "../ajax/GRNManage.php",
				data:{doc_type:doc_type, doc_date:doc_date},
				success: function(data){
					var mydata = JSON.parse(data);  
					if(mydata['method'] == 1){
						$('#hideandshow').hide();   
						$('#ind').show();   
						<?php if($row->id == ''){?>
						$("#mdoc_no2").val( mydata['prefix']+mydata['doc_no']+ mydata['suffix']);
							document.getElementById("doc_no").value = mydata['doc_no'];
							document.getElementById("id_doc_type_configuration").value = mydata['id_doc_type_configuration'];
						<?php } ?>
						document.getElementById("prefix").value = mydata['prefix'];
						document.getElementById("suffix").value = mydata['suffix'];

					}else{
						$('#hideandshow').show();
						$('#ind').hide(); 
						<?php if($row->id == ''){?> 
							document.getElementById("doc_no").value = mydata['doc_no'];
							document.getElementById("id_doc_type_configuration").value = mydata['id_doc_type_configuration'];
						<?php } ?>
						document.getElementById("prefix").value = '';
						document.getElementById("suffix").value = '';
					}
				}
			});
		}

		//Total Amount Adding
	 	net_amount();
	} 

	//Bill To Be Credit Days Get Here
	function billtobe(){
		var id_mst_party_billtobe = document.getElementById("id_mst_party_billtobe");
	    var id_mst_party_billtobe = id_mst_party_billtobe.options[id_mst_party_billtobe.selectedIndex].value;
	    document.getElementById("id_mst_party_billtobe").value=id_mst_party_billtobe;
	}

	//Supplier Credit Days Get Here
	function supplier(){
		var id_mst_party_supplier = document.getElementById("id_mst_party_supplier");
	    var id_mst_party_supplier = id_mst_party_supplier.options[id_mst_party_supplier.selectedIndex].value;
	    document.getElementById("id_mst_party_supplier1").value=id_mst_party_supplier;
	    var doc_type = '<?php echo $_GET['doc_type'];?>'; 
	    if(doc_type == 5){
	    	var phpfile = 'Purchsuppliercreditdaysget.php';
	    }else{
	    	var phpfile = 'GRNsuppliercreditdaysget.php';
	    }
	    
	    if(id_mst_party_supplier != '') {

			$.ajax({
				type: "POST",
				url: "../ajax/"+phpfile,
				data:{id_mst_party_supplier:id_mst_party_supplier},
				success: function(data){
					//console.log(data);
					var mydata = JSON.parse(data);
					document.getElementById("credit_days").value = mydata['credit_days'];
					document.getElementById("transaction_currency_code").value = mydata['transaction_currency_code'];
					document.getElementById("transaction_currency_code1").value = mydata['transaction_currency_code1'];
					//Net Amount 
					document.getElementById("sgst_net_amount").value = 0;  
	    			document.getElementById("cgst_net_amount").value = 0; 
	    			document.getElementById("igst_net_amount").value = 0;
	    			net_amount();

	    			//Select Box Id Set Here
	    			var count = mydata['i'];
	    			var doc_type = '<?php echo $_GET['doc_type'];?>';
	    			if(doc_type == 5){
	    				var join = "<option value=''  > Select Purchse </option>";
	    				var join1 = "<option value='' selected > Select Purchse </option>";
	    				//var join = "";
	    			}else if(doc_type == 4){
	    				var join = "<option value=''  > Select Po</option>";
	    				var join1 ="<option value='' selected > Select Po</option>"+"<option value='na'>NA</option>";
	    				//var join = "";
	    			}else if(doc_type == 12){
	    				var join1 ="<option value='' > Select Po</option>"+"<option value='na' selected >NA</option>";
	    				//var join = "";
	    			}
	    			for(var i=1; i<count;i++){
	    				join1 += "<option value='" + mydata['id'+i] + "'>" + mydata['doc_no'+i] + " | " + mydata['date'+i] + "</option>";
	    				join += "<option value='" + mydata['id'+i] + "'>" + mydata['doc_no'+i] + " | " + mydata['date'+i] + "</option>";
	    			} 
					
					
						document.getElementById("id_inv_po").innerHTML = join1;
						document.getElementById("id_inv_poo").innerHTML = join;
					


					if(mydata['ledger'] == 1) {
						$("#locals").show();
						$("#localss").show();
		    			$("#interstates").hide();
		    			$("#interstatess").hide();
		    			$("#chargeslocal").show();
	    				$("#chargesinterstate").hide();
		    			document.getElementById("ledger_id").value = '1';

		    			//SGST, Amount, CGST, Amount SET
		    			document.getElementById("id_mst_charges_sgst").value = 0;
		    			document.getElementById("id_mst_charges_cgst").value = 0;
		    			document.getElementById("item_sgst_percent").value = 0;
		    			document.getElementById("item_cgst_percent").value = 0;
		    			document.getElementById("item_sgst_amount").value = 0;
		    			document.getElementById("item_cgst_amount").value = 0;  

		    			document.getElementById("id_mst_charges_igst").value = 0; 
		    			document.getElementById("item_igst_percent").value = 0; 
		    			document.getElementById("item_igst_amount").value = 0;

		    			$('#s_amount').hide();
		    			$('#c_amount').hide();
		    			$('#i_amount').hide();
						
						document.getElementById("id_mst_charges_purchase_local").innerHTML = "<option value='' selected='selected'> Select Tax Register </option>"+"<?php 
	                $sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '1'";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->name ?></option> <?php } ?>"; 
	                	
		    			var counter1 =document.getElementById("counter1").value;
		    			//console.log(counter1);
		    			//Leder Method
		    			for(var i=1;i<=counter1;i++){
			    			$("#local"+i).show();
			    			$("#localsss"+i).show();
		    			    $("#interstate"+i).hide();
		    			    $("#interstatesss"+i).hide();
		    			    //SGST, Amount, CGST, Amount SET
			    			document.getElementById("id_mst_charges_sgst"+i).value = 0;
			    			document.getElementById("id_mst_charges_cgst"+i).value = 0;
			    			document.getElementById("item_sgst_percent"+i).value = 0;
			    			document.getElementById("item_cgst_percent"+i).value = 0;
			    			document.getElementById("item_sgst_amount"+i).value = 0;
			    			document.getElementById("item_cgst_amount"+i).value = 0;  
			    			document.getElementById("id_mst_charges_igst"+i).value = 0; 
			    			document.getElementById("item_igst_percent"+i).value = 0; 
			    			document.getElementById("item_igst_amount"+i).value = 0;
			    			$('#s_amount'+i).hide();
		    				$('#c_amount'+i).hide(); 
		    				$('#i_amount'+i).hide(); 
			    			//Loop Sections
			    			document.getElementById("id_mst_charges_purchase_local"+i).innerHTML = "<option value='' selected='selected'> Select Tax Register </option>"+"<?php 
	                $sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '1'";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->name ?></option> <?php } ?>"; 
		    			}
					}else{
						$("#locals").hide();
						$("#localss").hide();
		    			$("#interstates").show();
		    			$("#interstatess").show();
		    			$("#chargeslocal").hide();
	    				$("#chargesinterstate").show();
	    				//IGST SET
		    			document.getElementById("ledger_id").value = '2';
		    			document.getElementById("id_mst_charges_igst").value = 0; 
		    			document.getElementById("item_igst_percent").value = 0; 
		    			document.getElementById("item_igst_amount").value = 0;

		    			document.getElementById("id_mst_charges_sgst").value = 0;
		    			document.getElementById("id_mst_charges_cgst").value = 0;
		    			document.getElementById("item_sgst_percent").value = 0;
		    			document.getElementById("item_cgst_percent").value = 0;
		    			document.getElementById("item_sgst_amount").value = 0;
		    			document.getElementById("item_cgst_amount").value = 0;  
		    			  
		    			$('#i_amount').hide(); 
		    			$('#s_amount').hide();
		    			$('#c_amount').hide();
		    			document.getElementById("id_mst_charges_purchase_interstate").innerHTML = "<option value='' selected='selected'> Select </option>"+"<?php 
	                $sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '2'";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->name ?></option> <?php } ?>";

		    			var counter1 = document.getElementById("counter1").value;
		    			//Leder Method
		    			for( var i=1;i<=counter1;i++){
			    			$("#local"+i).hide();
			    			$("#localsss"+i).hide();
		    			    $("#interstate"+i).show();
		    			    $("#interstatesss"+i).show();
		    			    //SGST, Amount, CGST, Amount SET
			    			document.getElementById("id_mst_charges_igst"+i).value = 0; 
			    			document.getElementById("item_igst_percent"+i).value = 0;  
			    			document.getElementById("item_igst_amount"+i).value = 0; 
			    			document.getElementById("id_mst_charges_sgst"+i).value = 0;
			    			document.getElementById("id_mst_charges_cgst"+i).value = 0;
			    			document.getElementById("item_sgst_percent"+i).value = 0;
			    			document.getElementById("item_cgst_percent"+i).value = 0;
			    			document.getElementById("item_sgst_amount"+i).value = 0;
			    			document.getElementById("item_cgst_amount"+i).value = 0;  
			    			$('#i_amount'+i).hide(); 
			    			$('#s_amount'+i).hide();
		    				$('#c_amount'+i).hide();
			    			//Loop Sections
			    			document.getElementById("id_mst_charges_purchase_interstate"+i).innerHTML = "<option value='' selected='selected'> Select </option>"+"<?php 
	                $sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '2'";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->name ?></option> <?php } ?>";
		    			}
					}
					var base_currency = document.getElementById("base_currency_code").value;


					if(base_currency != mydata['transaction_currency_code']){
						$("#xchange_rate").show();
						$("#base_currency").show();
						$("#trans_currency").show();
						$("#sup_date").show();
						$("#supplier_inv_no").show();
						document.getElementById("exchange_rate").value = 0;
					}else{
						$("#xchange_rate").hide();
						$("#base_currency").hide();
						$("#trans_currency").hide();
						$("#sup_date").hide();
						$("#supplier_inv_no").hide();
						document.getElementById("exchange_rate").value = 1;
					}

				}
			});
		}
		//Net Amount 
		document.getElementById("sgst_net_amount").value = 0;  
		document.getElementById("cgst_net_amount").value = 0; 
		document.getElementById("igst_net_amount").value = 0;
		net_amount();
	}
	//Combo Box
	function itemget(clicked_id) {
//alert(itemget);
			var id_inv_items_po = document.getElementById("id_inv_items_po");
		    var id_inv_items_po = id_inv_items_po.options[id_inv_items_po.selectedIndex].value;


		    var regex = /[+-]?\d+(?:\.\d+)?/g;
		    var match = parseInt(regex.exec(clicked_id));
			if(match >=1 ){

				var id_inv_items_po = document.getElementById("id_inv_items_po"+match);
			   	var id_inv_items_po = id_inv_items_po.options[id_inv_items_po.selectedIndex].value;

		     
 			 
		     	var regex = /[+-]?\d+(?:\.\d+)?/g;
		     	var match = parseInt(regex.exec(clicked_id));
		     	var id_inv_items_po = document.getElementById("id_inv_items_po"+match);
		    	var id_inv_items_po = id_inv_items_po.options[id_inv_items_po.selectedIndex].value;
		    	 
 			 
		    $.ajax({

					type: "POST",
					url: "../ajax/Itemsget.php",
					data:{id_inv_items:id_inv_items_po},
					success: function(data){
						//console.log(data); 
						var mydata = JSON.parse(data); 
						document.getElementById("item_description"+match).value = mydata['name']; 
						document.getElementById("id_inv_items"+match).value = id_inv_items_po; 
						var alt_unit = mydata['alt_unit'];
						var main_unit = mydata['main_unit'];
						var id_mst_attributes_store = mydata['id_mst_attributes_store'];
						var store = mydata['store'];
						var conversion_qty = mydata['conversion_qty'];
						document.getElementById("main_unit"+match).value = main_unit; 
						document.getElementById("alt_unit"+match).value = alt_unit; 
						document.getElementById("conver_rate_per_unit"+match).value = conversion_qty; 
						document.getElementById("transaction_unit"+match).innerHTML = "<option value='" + main_unit + "' selected='selected'>" + main_unit + "</option>"+"<option value='" + alt_unit + "'>" + alt_unit + "</option>";

						document.getElementById("per_unit"+match).innerHTML = "<option value='" + main_unit + "' selected='selected'>" + main_unit + "</option>"+"<option value='" + alt_unit + "'>" + alt_unit + "</option>";
						//Store Section Here
						document.getElementById("id_mst_attributes_store"+match).innerHTML = "<option value='" + id_mst_attributes_store + "' selected='selected'>" + store + "</option>"+"<?php  $sql = "SELECT mst_attributes.*FROM mst_attributes WHERE mst_attributes.id_shop = '".addslashes($_SESSION['shop'])."' AND  status = '1' AND table_name ='store' ";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->field_value ?></option> <?php } ?>"; 
						fetchCharges(id_inv_items_po,'id_mst_charges_purchase_local'+match,match);
						
 					}
				}); 
		}else{		 
		 
			 
			if(id_inv_items_po != '') {

				$.ajax({
					type: "POST",
					url: "../ajax/Itemsget.php",
					data:{id_inv_items:id_inv_items_po},
					success: function(data){
						//console.log(data); 
						var mydata = JSON.parse(data);
						document.getElementById("item_description").value = mydata['name'];
						document.getElementById("id_inv_items").value = id_inv_items_po; 
						var alt_unit = mydata['alt_unit'];
						var main_unit = mydata['main_unit'];
						var id_mst_attributes_store = mydata['id_mst_attributes_store'];
						var id_mst_charges_purchase_local = mydata['id_mst_charges_purchase_local'];
						var store = mydata['store'];
						var conversion_qty = mydata['conversion_qty'];
						document.getElementById("main_unit").value = main_unit; 
						document.getElementById("alt_unit").value = alt_unit; 
						document.getElementById("conver_rate_per_unit").value = conversion_qty; 
						document.getElementById("transaction_unit").innerHTML = "<option value='" + main_unit + "' selected='selected'>" + main_unit + "</option>"+"<option value='" + alt_unit + "'>" + alt_unit + "</option>";

						document.getElementById("per_unit").innerHTML = "<option value='" + main_unit + "' selected='selected'>" + main_unit + "</option>"+"<option value='" + alt_unit + "'>" + alt_unit + "</option>"; 
						//Store Section Here
						document.getElementById("id_mst_attributes_store").innerHTML = "<option value='" + id_mst_attributes_store + "' selected='selected'>" + store + "</option>"+"<?php  $sql = "SELECT mst_attributes.*FROM mst_attributes WHERE mst_attributes.id_shop = '".addslashes($_SESSION['shop'])."' AND  status = '1' AND table_name ='store' ";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->field_value ?></option> <?php } ?>";   
						
						//document.getElementById("id_mst_charges_purchase_local").innerHTML = "<option value='" + id_mst_charges_purchase_local + "' selected='selected'>" + store + "</option>"+"<?php  $sql = "SELECT mst_attributes.*FROM mst_attributes WHERE mst_attributes.id_shop = '".addslashes($_SESSION['shop'])."' AND  status = '1' AND table_name ='store' ";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->field_value ?></option> <?php } ?>"; 
						
						fetchCharges(id_inv_items_po,'id_mst_charges_purchase_local');
						
					}
				});
			}	
	    
		}
			
	}
	
	
function fetchCharges(itemId='',returnTag='',counter=''){
		//alert(returnTag);
		//console.log(itemId);
		$.ajax({
			url:'ajax/fetchCharges.php',
			data:'itemId='+itemId+'&item_charge=id_mst_charges_purchase_local&charges_account=2&transaction_type=1',
			type:'POST',
			success:function(dataset){
				//console.log(dataset);
				$('#'+returnTag).html(dataset);
				po_locals(counter);
				//alert(counter);
			}
		});
	}	
	

</script>
<?php 
	$sql2 = " SELECT max(doc_date) as doc_date FROM `".TBL_INV_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' ";
		$db->query($sql2);   
			while($row2 = $db->fetch_object()){ 
				$doc_date= $row2->doc_date;
				$doc_date = date('d-m-Y' , strtotime(addslashes($doc_date)));  
			} 
			if($row->id != '' && $_REQUEST['print'] == 1){ 

?>
<script type="text/javascript">
	var eid = '<?php echo $_GET['eId']; ?>';  
</script>

	<button type="button" id="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" style="display: none;">
    </button>
	<!-- The Modal -->
	<div class="modal" id="myModal">
	    <div class="modal-dialog">
	      <div class="modal-content"  style="margin-top: 50%; width: 72%;margin-left: 20%;"> 
	       
	        <!-- Modal body -->
	        <div class="modal-body">
	        	<center>
	          <a href="editPurch.php?doc_type=<?php echo $_GET['doc_type'] ?>&session=<?php echo $_GET['session']; ?>&submenu=<?php echo $_GET['submenu']; ?>" type="button" class="btn btn-success"  id="buttons_radius"><i class="fa fa-plus-circle" aria-hidden="true"> Another Indent</i></a> 
	          <a href="print.php?eId='<?php echo $_GET['eId']; ?>'&action=edit&page=<?php $_REQUEST['page']?>&doc_type=<?php echo $_GET['doc_type'] ?>&session=<?php echo $_GET['session']; ?>&submenu=<?php echo $_GET['submenu']; ?>&doc_type=<?php echo $_GET['doc_type']; ?>"  type="button" class="btn btn-primary"  id="buttons_radius"><i class="fa fa-print" aria-hidden="true"> Print</i></a> 
	          <button type="button" class="btn btn-danger" data-dismiss="modal"  id="buttons_radius"><i class="fa fa-times-circle" aria-hidden="true"> Cancel</i></button>
	          <a href="managePurch.php?submenu=<?php echo $_GET['submenu']; ?>" type="button" class="btn btn-info"  id="buttons_radius"><i class="fa fa-info-circle" aria-hidden="true"> Close</i></a>  
        	  <!-- <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button> -->
        	</center>
	        </div> 
	        
	      </div>
		</div>
	</div>
	<script type="text/javascript">
		//document.getElementById('button').click();
	</script>

<?php } ?>

 <script type="text/javascript">
 
 
	$( document ).ready(function() {
 		<?php 
 		/*	if($row->id == ''){
			$sql2 = " SELECT max(doc_date) as doc_date FROM `".TBL_INV_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' ";
			$db->query($sql2);   
			while($row2 = $db->fetch_object()){ 
				$doc_date= $row2->doc_date; 
				if($doc_date == ''){
					$doc_date = selectColumn(TBL_DOC_TYPE_CONFIG,'effective_date'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' ");
				}
				$doc_date = date('d-m-Y' , strtotime(addslashes($doc_date)));  
			}  */

?>
 	/*	var dates = '<?php echo ($doc_date!=''?date("d-m-Y",strtotime($doc_date)):date('d-m-Y')); ?>';
 		//document.getElementById("doc_date").value = dates; 
 		document.getElementById('doc_date').click();  
		$('.dates').datepicker({ dateFormat: "dd-mm-yy" , minDate: dates }); */	

		//Button hide 
	<?php //} ?>
		 
	});
 
 

	$( document ).ready(function() {
 		
 		var dates = '<?php echo ($doc_date!=''?date("d-m-Y",strtotime($doc_date)):date('d-m-Y')); ?>';
 		//document.getElementById("doc_date").value = dates; 
 		$('#doc_date').click();  
		$('.dates').datepicker({ dateFormat: "dd-mm-yy" , minDate: dates });

		//Button hide 
		 
	}); 
	
	$( document ).ready(function() {
 		
 		var dates = '<?php echo ($doc_date!=''?date("d-m-Y",strtotime($doc_date)):date('d-m-Y')); ?>';
 		//document.getElementById("doc_date").value = dates; 
 		$('#doc_date').click();  
		$('.dates1').datepicker({ dateFormat: "dd-mm-yy"  });

		//Button hide 
		 
	}); 
	

	
	
	

//Select 2  Resolve Here
	 

    $("#addrow1").on("click", function () { 

    	var counter11 =  document.getElementById("counter1").value;  
		var ledger_id =  document.getElementById("ledger_id").value;

	
        counter11++; 

//alert(counter1);
if(counter11==0){
	var counter1 =  ''; 
}else{
	var counter1 =  counter11; 
}


    	//Purchase Order Select Here
    	var id_mst_party_supplier = document.getElementById("id_mst_party_supplier");
	    var id_mst_party_supplier = id_mst_party_supplier.options[id_mst_party_supplier.selectedIndex].value; 
	    var doc_type = '<?php echo $_GET['doc_type'];?>'; 
	    if(doc_type == 5){
	    	var phpfile = 'Purchsuppliercreditdaysget.php';
	    }else{
	    	var phpfile = 'GRNsuppliercreditdaysget.php';
	    }
		//alert("#id_inv_po"+counter1);
		if(doc_type =='12'){
			$(document).ready(function() {
				$("#id_inv_po"+counter1).click();
			});
		}
		
	    
	  if(id_mst_party_supplier != '') {

			$.ajax({
				type: "POST",
				url: "../ajax/"+phpfile,
				data:{id_mst_party_supplier:id_mst_party_supplier},
				success: function(data){
					//console.log(data);
					var mydata = JSON.parse(data); 

	    			var count = mydata['i'];
	    			var doc_type = '<?php echo $_GET['doc_type'];?>';
	    			if(doc_type == 5){
	    				var join = "<option value='' selected='selected'> Select </option>";
	    			}else if(doc_type == 4){
	    				var join = "<option value='' selected='selected'> Select Po </option>"+"<option value='na'>NA</option>";
	    			}else if(doc_type == 12){
	    				var join = "<option value='na' selected='selected'> NA</option>";
	    			}
	    			for(var i=1; i<count;i++){
						join += "<option value='" + mydata['id'+i] + "'>" + mydata['doc_no'+i] + " | " + mydata['date'+i] + "</option>";
	    			//	join += "<option value='" + mydata['id'+i] + "'>" + mydata['id'+i] + "</option>";
	    			} 
	    			document.getElementById("id_inv_po"+counter1).innerHTML = join;
	    		}
	    	});
	    }	
		
		
		
		

	   //Table Row Add Section Here
	   
        var newRow1 = $('<tr id="trdelete' + counter1 + '">');
        var cols1 = ""; 
        var cols2 = ""; 
		
		
		

      //  cols1 += '<td><select onchange="popupshow(this.id);" onclick="popupshow(this.id);"  name="id_inv_po' + counter1 + '" id="id_inv_po' + counter1 + '" class="form-control select2" style="width:100%"><option value="">Select <?php echo $table_field; ?></option><?php if($_GET['doc_type'] == '12'){?><option value="na" selected >NA</option><?php }else if($_GET['doc_type'] == '4' && $_GET['doc_type'] != '5'){ ?><option value="na" >NA</option><?php	} ?></select> </td>'; 
	  
	   cols1 += '<td><select onchange="popupshow(this.id);" onclick="popupshow(this.id);" name="id_inv_po' + counter1 + '" id="id_inv_po' + counter1 + '" class="form-control select3"  style="width:100%" required ><option>Select</option><?php if($_GET['doc_type'] == '12'){?><option value="na" selected >NA</option><?php } ?></select> </td>'; 

        cols1 += '<td style="display:none;"><input type="text"  autocomplete="off" placeholder="ID" class="form-control" name="id_inv_po_details' + counter1 + '" id="id_inv_po_details' + counter1 + '" readonly=""/></td>';

        cols1 += '<td><div id="hideshow_item_code'+ counter1 +'"><input type="text"  autocomplete="off" placeholder="Item Code" class="form-control" name="item_code' + counter1 + '" id="item_code' + counter1 + '" readonly=""/></div><div id="hideshow_item_codes'+ counter1 +'" style="display: none;"><select onchange="itemget(this.id)" name="id_inv_items_po' + counter1 + '" id="id_inv_items_po' + counter1 + '" class="form-control select3"  style="width:100%"><option>Select Item Code</option><?php 
	                $sql = "SELECT inv_items.*, mst_attributes.field_value FROM inv_items, mst_attributes WHERE  mst_attributes.id=inv_items.id_mst_attributes_group_main and  inv_items.id_mst_attributes_item_type IN ($item_list) and inv_items.id_shop = '".addslashes($_SESSION['shop'])."'";
	                   	 $db->query($sql); 
	                    while($row1 = $db->fetch_object()){ ?>
	                  		<option value="<?php echo $row1->id; ?>"><?php echo $row1->item_code.' | '.$row1->name; ?></option> <?php } 
                  	?></select></div><input type="text"  autocomplete="off" placeholder="Item ID" class="form-control" name="id_inv_items' + counter1 + '" id="id_inv_items' + counter1 + '" style="display:none;"/></td>';  
		
		cols1 += '<td><input type="text"  autocomplete="off" placeholder="Item Description" class="form-control" name="item_description' + counter1 + '" id="item_description' + counter1 + '" readonly=""/></td>';
		cols1 += '<td><select  name="id_mst_attributes_store' + counter1 + '" id="id_mst_attributes_store' + counter1 + '" class="form-control select3"  style="width:100%"><option>Select Store</option><?php 
	                $sql = "SELECT mst_attributes.field_value FROM  mst_attributes WHERE id_shop = '".addslashes($_SESSION['shop'])."' and table_name ='store' ";
	                   	 $db->query($sql); 
	                    while($row1 = $db->fetch_object()){ ?>
	                  		<option value="<?php echo $row1->id; ?>"><?php echo $row1->field_value; ?></option> <?php } 
                  	?></select></td>';

		cols1 += '<td><input onkeyup="amount_calc(this.id)" onclick="amount_calc(this.id)"  type="text"  autocomplete="off" placeholder="Qty" class="form-control discountvalue"  name="qty' + counter1 + '" id="qty' + counter1 + '"/></td>'; 


        cols1 += '<td> <select class="form-control select3" id="transaction_unit' + counter1 +'" name="transaction_unit' + counter1 +'" onchange="amount_calc(this.id);" style="width:100%"></select><input type="text"  autocomplete="off" placeholder="Main Unit" class="form-control"  name="main_unit' + counter1 + '" id="main_unit' + counter1 + '" style="display:none;"/><input type="text"  autocomplete="off" placeholder="Alt Unit" class="form-control"  name="alt_unit' + counter1 + '" id="alt_unit' + counter1 + '" style="display:none;"/><input type="text"  autocomplete="off" placeholder="conver_rate_per_unit" class="form-control"  name="conver_rate_per_unit' + counter1 + '" id="conver_rate_per_unit' + counter1 + '" style="display:none;"/></td>'; 

		cols1 += '<td><input  onkeyup="amount_calc(this.id)" type="text"  autocomplete="off" placeholder="Rate" class="form-control discountvalue" name="rate_per_main_unit' + counter1 + '" id="rate_per_main_unit' + counter1 + '" required /><input style="display:none;" type="text"  autocomplete="off"  class="form-control discountvalue"  name="item_amount_before_discount' + counter1 + '" id="item_amount_before_discount' + counter1 + '"/></td>'; 

		 cols1 += '<td> <select class="form-control select3" id="per_unit' + counter1 +'" name="per_unit' + counter1 +'" onchange="amount_calc(this.id);" style="width:100%"></select></td>'; 

        cols1 += '<td><input onkeyup="amount_calc(this.id);" onclick="amount_calc(this.id);"  type="text"  autocomplete="off" placeholder="%Discount" class="form-control discountvalue" name="discount_percent' + counter1 + '" id="discount_percent' + counter1 + '"/></td>'; 

        cols1 += '<td><input  type="text"  autocomplete="off" placeholder="Amount" class="form-control" name="item_amount' + counter1 + '" id="item_amount' + counter1 + '"  readonly/></td>'; 

                 
        cols1 += '<td style="display:none;"><div id="taxconfig" id="taxconfig"><!-- SGST --><input type="text"  autocomplete="off"  name="id_mst_charges_sgst' + counter1 + '" id="id_mst_charges_sgst' + counter1 + '" placeholder="SGST"  class="form-control" /><input type="text"  autocomplete="off"  name="item_sgst_percent' + counter1 + '" id="item_sgst_percent' + counter1 + '" placeholder="SGST"  class="form-control" /><input type="text"  autocomplete="off"  name="item_sgst_amount' + counter1 + '" id="item_sgst_amount' + counter1 + '" placeholder="SGST Amount"  class="form-control"  /><!-- CGST --><input type="text"  autocomplete="off"  name="id_mst_charges_cgst' + counter1 + '" id="id_mst_charges_cgst' + counter1 + '" placeholder="CGST"  class="form-control" /><input type="text"  autocomplete="off"  name="item_cgst_percent' + counter1 + '" id="item_cgst_percent' + counter1 + '" placeholder="CGST"  class="form-control" /><input type="text"  autocomplete="off"  name="item_cgst_amount' + counter1 + '" id="item_cgst_amount' + counter1 + '" placeholder="CGST Amount"  class="form-control" /><!-- IGST --><input type="text"  autocomplete="off"  name="id_mst_charges_igst' + counter1 + '" id="id_mst_charges_igst' + counter1 + '" placeholder="IGST"  class="form-control" /><input type="text"  autocomplete="off"  name="item_igst_percent' + counter1 + '" id="item_igst_percent' + counter1 + '" placeholder="IGST"  class="form-control" /><input type="text"  autocomplete="off"  name="item_igst_amount' + counter1 + '" id="item_igst_amount' + counter1 + '" placeholder="IGST Amount"  class="form-control" /></div></td>';		 
		 $("</tr>"); 

		 var newRow11 =  $('<tr id="trdeletes' + counter1 + '">');

		 	 cols2 += '<td><input type="text"  autocomplete="off" placeholder="Remarks2" class="form-control" name="item_remarks' + counter1 + '" id="item_remarks' + counter1 + '"/></td>';
		 	 cols2 += '<td><div id="local'+counter1+'" name="local'+counter1+'" style="display:none;"><select  name="id_mst_charges_purchase_local' + counter1 + '" id="id_mst_charges_purchase_local' + counter1 + '" class="form-control select3" onchange="po_locals(this.id)" style="width:100%;"><option>Select Tax Register</option><?php 
	                $sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '1'";
	                   	 $db->query($sql); 
	                    while($row1 = $db->fetch_object()){ ?>
	                  		<option value="<?php echo $row1->id; ?>"><?php echo $row1->name ?></option> <?php } ?></select></div><div id="interstate'+counter1+'" name="interstate'+counter1+'" style="display:none;"><select  onchange="po_interstate(this.id)"  name="id_mst_charges_purchase_interstate' + counter1 + '" id="id_mst_charges_purchase_interstate' + counter1 + '" class="form-control select3" style="width:100%;" ><option>Select</option><?php 
	                $sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '2'";
	                   	 $db->query($sql); 
	                    while($row1 = $db->fetch_object()){ ?>
	                  		<option value="<?php echo $row1->id; ?>"><?php echo $row1->name ?></option> <?php }?></select></div></td> '; 
	                  	cols2 += '<td><div id="localsss'+counter1+'" name="localsss'+counter1+'" style="display:none;"><span style="color:red;font-size:14px;" id="s_amount'+counter1+'"></span> <span style="color:red;font-size:14px;;font-size:14px;" id="c_amount'+counter1+'"></span></div><div id="interstatesss'+counter1+'" name="interstatesss'+counter1+'" style="display:none;"><span style="color:red;font-size:14px;" id="i_amount' + counter1 + '" name="i_amount' + counter1 + '"></span></div></td>';
	                  	cols2 += '<td></td>';
	                  	cols2 += '<td></td>';
	                  	cols2 += '<td></td>';
	                  	cols2 += '<td></td>';
	                  	cols2 += '<td></td>';
	                  	cols2 += '<td></td>';
	                  		cols2 += '<td><img src="images/delete.gif"  class="ibtnDel1" id="deletes' + counter1 + '" name="deletes' + counter1 + '" style="cursor:pointer;" title="Delete"/></td>';
		 $("</tr>"); 

		document.getElementById("counter1").value = counter1;
		newRow1.append(cols1);
		newRow11.append(cols2);
		//supplier();
        $("table.order-list1").append(newRow1); 
        $("table.order-list1").append(newRow11); 
		$(".select3").select2({});
         
         $(".select3").last().next().next().remove();

        

        if(ledger_id == 1){
	    	$("#local"+counter1).show();
	    	$("#localsss"+counter1).show();
	    	$("#interstate"+counter1).hide();
	    	$("#interstatesss"+counter1).hide();
	    	
	    }else if(ledger_id == 2){
	    	$("#local"+counter1).hide();
	    	$("#localsss"+counter1).hide();
	    	$("#interstate"+counter1).show(); 
	    	$("#interstatesss"+counter1).show(); 
	    }  
    });

    $("table.order-list1").on("click", ".ibtnDel1", function (event) { 
       //alert();
    		var clicked_id = $(this).attr("id");
    		var deletes = clicked_id.indexOf("deletes"); 
    		 
    			var ids = this.id; 
	        	var regex = /[+-]?\d+(?:\.\d+)?/g;
				var match = parseInt(regex.exec(ids));
    			var total  =-1;
	 			document.getElementById("item_amount"+match).value =total;

//alert(match);
				
 			//Tax Config Method
			var sgst2 = document.getElementById("oc_sgst2").value;
			var cgst2 = document.getElementById("oc_cgst2").value;
			var igst2 = document.getElementById("oc_igst2").value;  
         
			//Sub Total
        	var counter1 = document.getElementById("counter1").value;
 			var sub = document.getElementById("sub_total_items1").value;
 			var sub_1 = document.getElementById("net_amount_items1").value;
 			//SGST - CGST
			var sgst_first = document.getElementById("sgst1").value;
			var cgst_first = document.getElementById("cgst1").value;
			//IGST
			var igst_first = document.getElementById("igst1").value;

 			//Discount 
			var total_discount_items = document.getElementById("total_discount_items1").value;

 			var total = 0;
 			var discount_total = 0;
 			var sgst_total = 0; 
 			var cgst_total = 0; 
 			var igst_total = 0;
 			var total_rate = 0;

 			if(counter1 >=1){

 				for(var i=1;i<=counter1;i++){

 					var qty = document.getElementById("qty"+i).value;
					var rate = document.getElementById("rate_per_main_unit"+i).value;
 					var value = document.getElementById("item_amount"+i).value; 
 					var totalrate = document.getElementById("item_amount_before_discount"+i).value; 
 					var discount = document.getElementById("discount_percent"+i).value;

 					//SGST- CGST 
 					var sgst = document.getElementById("item_sgst_amount"+i).value; 
 					var cgst = document.getElementById("item_cgst_amount"+i).value;
 					//IGST 
 					var igst = document.getElementById("item_igst_amount"+i).value;  

 					if(value >=1 ){
 						//Sub Total Section Here
 						total = Number(total) + Number(value);
 						//Total Rate
 						total_rate = Number(total_rate) + Number(totalrate);


 						//Total Discount Section Here
 						discounted_calc = (totalrate / 100) * discount;
 						discount_total = Number(discount_total) + Number(discounted_calc);

 						//SGST-CGST
 						sgst_total = Number(sgst_total) + Number(sgst);
 						cgst_total = Number(cgst_total) + Number(cgst);
 						//IGST
 						igst_total = Number(igst_total) + Number(igst);
 
 					}
 				}

 				//Sub Total Section
 				total = Number(total) + Number(sub);
 				document.getElementById("net_amount_items").value =total;
 				//Total Rate
 				total_rate = Number(total_rate) + Number(sub_1);
 				document.getElementById("sub_total_items").value =total_rate;

 				//Discount Total Section
 				discount_total = Number(discount_total) + Number(total_discount_items);
 				document.getElementById("total_discount_items").value =discount_total;

 				//SGST
 				sgst_total = Number(sgst_total) + Number(sgst_first);
 				document.getElementById("sgst_net_amount").value =Number(sgst_total).toFixed(2);
 				document.getElementById("sgst2").value =Number(sgst_total).toFixed(2);
 				document.getElementById("sgst_net_amount").value =Number(Number(sgst_total) + Number(sgst2)).toFixed(2) ;  
 				//CGST
 				cgst_total = Number(cgst_total) + Number(cgst_first);
 				document.getElementById("cgst_net_amount").value =Number(cgst_total).toFixed(2);
 				document.getElementById("cgst2").value =cgst_total;
 				document.getElementById("cgst_net_amount").value =Number(Number(cgst_total)+Number(cgst2)).toFixed(2);
 				//IGST
 				igst_total = Number(igst_total) + Number(igst_first);
 				document.getElementById("igst_net_amount").value =igst_total;
 				document.getElementById("igst2").value =igst_total;
 				document.getElementById("igst_net_amount").value =Number(Number(igst_total)+Number(igst2)).toFixed(2);

 			}  			
 			//$(this).closest("tr").hide(); 
 			$("#trdelete"+match).hide();
 			$("#trdeletes"+match).hide();
			$("#edittrdelete"+match).hide();
 				$("#edittrdeletes"+match).hide(); 
			
 			<?php if($row->id !=''){?>
			//alert(match);
 				$("#edittrdelete"+match).hide();
 				$("#edittrdeletes"+match).hide(); 
				
 				var dbid = document.getElementById("dbid"+match).value;
				var doc_type = document.getElementById("doctype").value;
				//alert(dbid);
 				//Others Delete
	 			var others = 'po';
	 			$.ajax({
					type: "POST",
					url: "../ajax/Podelete.php",
					data:{clicked_id:dbid, others:others,doc_type:doc_type},
					success: function(data){
						var mydata = JSON.parse(data);  
						if(mydata['delete'] == 1){      
						} 
					}
				});				  
				 //document.getElementById("indent_form").submit();
 			<?php } ?> 
 			    
 			//Total Amount Adding
	 	net_amount();
    }); 
	
    //Table 2 Section Here
    $("#addrow2").on("click", function () { 	
		
		var counter2 =  document.getElementById("counter2").value;  
		//var counter211 =  document.getElementById("counter2").value; 
		
/* if(counter211=='-1'){
	var counter21 =  '0'; 
}else{
	var counter21 =  counter211; 
}	*/
		
        var ledger_id =  document.getElementById("ledger_id").value;
        counter2++; 


/* if(counter21 == 0 && counter21 != ''){
	var counter2 =  ''; 
}else{
	var counter2 =  counter22; 
} */
		

        var newRow2 = $("<tr>");
        var cols2 = ""; 

        cols2 += '<td style="display:none"><select class="form-control select3" id="type' + counter2 + '" name="type' + counter2 + '" onchange="type_funt(this.id)" style="width:100%"><option value="">select Charges</option><option value="1">OTHERS</option><option value="2">DISCOUNT</option></select></td>';


        cols2 += '<td><div id="otherss' + counter2 + '"name="otherss' + counter2 + '" ><select  name="id_mst_charges_others' + counter2 + '" id="id_mst_charges_others' + counter2 + '" class="form-control select3" style="width:100%;" onchange="charges_others(this.id)" style="width:100%"><option>Select Others Charges</option><?php 
	                $sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND mst_charges.charges_account IN (4) ";
	                   	 $db->query($sql); 
	                    while($row1 = $db->fetch_object()){ ?>
	                  		<option value="<?php echo $row1->id; ?>"><?php echo $row1->name ?></option> <?php } 
                  	?></select></div><div style="display:none;"  id="dis' + counter2 + '"name="dis' + counter2 + '" ><select onchange="otherscharges_discount(this.id)"  name="id_mst_charges_discounts' + counter2 + '" id="id_mst_charges_discounts' + counter2 + '" class="form-control select3" style="width:100%;" ><option>Select Discounts Charges</option><?php 
	                $sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND mst_charges.charges_account = '6' ";
	                   	 $db->query($sql); 
	                    while($row1 = $db->fetch_object()){ ?>
	                  		<option value="<?php echo $row1->id; ?>"><?php echo $row1->name ?></option> <?php } 
                  	?></select></div></td>';               

		cols2 += '<td><input onkeyup="subtotal_calc(this.id)" type="text"  autocomplete="off" placeholder="Percentage" class="form-control discountvalue" name="others_charges_percent' + counter2 + '" id="others_charges_percent' + counter2 + '" /></td>'; 

        cols2 += '<td><input onkeyup="charges_amount_calc(this.id);" onclick="charges_amount_calc(this.id);" type="text"  autocomplete="off" placeholder="Amount" class="form-control discountvalue" name="others_charges_amount' + counter2 + '" id="others_charges_amount' + counter2 + '" /></td>';  

		cols2 += '<td style="display:none;"><input type="text"  autocomplete="off" placeholder="Total" class="form-control" name="total_amount_others' + counter2 + '" id="total_amount_others' + counter2 + '" readonly/></td>'; 



        cols2 += '<td><input type="text"  autocomplete="off"  name="others_charges_sgst_amount' + counter2 + '" id="others_charges_sgst_amount' + counter2 + '" placeholder="SGST Amount"  class="form-control"  readonly/></td>';

        cols2 += '<td><input type="text"  autocomplete="off"  name="others_charges_cgst_amount' + counter2 + '" id="others_charges_cgst_amount' + counter2 + '" placeholder="CGST Amount"  class="form-control" readonly /></td>';

        cols2 += '<td><input type="text"  autocomplete="off"  name="others_charges_igst_amount' + counter2 + '" id="others_charges_igst_amount' + counter2 + '" placeholder="IGST Amount"  class="form-control" readonly/></td>';
 

        cols2 += '<td  style="display:none;"><div id="chargestaxconfig" id="chargestaxconfig"><!-- SGST --><input type="text"  autocomplete="off"  name="others_charges_sgst_percent' + counter2 + '" id="others_charges_sgst_percent' + counter2 + '" placeholder="SGST"  class="form-control" /><input type="text"  autocomplete="off"  name="id_mst_charges_sgst_others' + counter2 + '" id="id_mst_charges_sgst_others' + counter2 + '" placeholder="SGST"  class="form-control" /><!-- CGST --><input type="text"  autocomplete="off"  name="others_charges_cgst_percent' + counter2 + '" id="others_charges_cgst_percent' + counter2 + '" placeholder="CGST"  class="form-control" /><input type="text"  autocomplete="off"  name="id_mst_charges_cgst_others' + counter2 + '" id="id_mst_charges_cgst_others' + counter2 + '" placeholder="CGST"  class="form-control" /><!-- IGST --><input type="text"  autocomplete="off"  name="others_charges_igst_percent' + counter2 + '" id="others_charges_igst_percent' + counter2 + '" placeholder="IGST"  class="form-control" /><input type="text"  autocomplete="off"  name="id_mst_charges_igst_others' + counter2 + '" id="id_mst_charges_igst_others' + counter2 + '" placeholder="IGST"  class="form-control" /></div></td>';  

        cols2 += '<td  style="display:none;"><div id="otherschargestaxconfig" id="otherschargestaxconfig"><!-- Discount --><input type="text"  autocomplete="off"  name="others_discount_percent' + counter2 + '" id="others_discount_percent' + counter2 + '" placeholder="Discount"  class="form-control" /><input type="text"  autocomplete="off"  name="others_discount_amount' + counter2 + '" id="others_discount_amount' + counter2 + '" placeholder="Amount"  class="form-control"  /></div></td>';        
 		  
		cols2 += '<td><img src="images/close.png"  class="ibtnDel2 " id="deletes' + counter2 + '" name="deletes' + counter2 + '" style="height: 23px;margin-top: 5px;cursor:pointer;" title="Delete"/></td>';
		 $("</tr>"); 




		
		newRow2.append(cols2);
        $("table.order-list2").append(newRow2);  

          $(".select3").select2({});
         
         $(".select3").last().next().next().remove();

        document.getElementById("counter2").value = counter2;

        if(ledger_id == 1){
	    	$("#chargeslocal"+counter2).show();
	    	$("#chargesinterstate"+counter2).hide();
	    	
	    }else if(ledger_id == 2){
	    	$("#chargeslocal"+counter2).hide();
	    	$("#chargesinterstate"+counter2).show(); 
	    }   
    });

    $("table.order-list2").on("click", ".ibtnDel2", function (event) {

    		var clicked_id = $(this).attr("id");
        	var ids = this.id;  
        	var regex = /[+-]?\d+(?:\.\d+)?/g;
			var match = parseInt(regex.exec(ids));

			var total  = -1;
 			document.getElementById("others_charges_amount"+match).value =total;

 			//Tax Config Method
			var sgst2 = document.getElementById("sgst2").value;
			var cgst2 = document.getElementById("cgst2").value;
			var igst2 = document.getElementById("igst2").value; 
         
			//Sub Total
        	var counter2 = document.getElementById("counter2").value;
			
			//alert();	
				var k=0;
				var j=0;
				
				for(var i=0;i<=counter2;i++){
					
					if(i==0){
						var type = document.getElementById("type");
						var type = type.options[type.selectedIndex].value;
						
						if(type == 2){
							var valuesl = document.getElementById("others_charges_amount").value;
						}else{
							var valuesl = '0';
						}
					}else{
					
						var type = document.getElementById("type"+i);
						var type = type.options[type.selectedIndex].value;
						if(type == 2){
							var sum = document.getElementById("others_charges_amount"+i).value;
							if(sum=='-1'){
							var sum1 = 0;
						}else{
							var sum1 = sum;
						}
						k +=  parseFloat(sum1);
						var totall = k;
						
					}
					}
				}
				
				if(totall==undefined){
					var tot = 0;
				}else{
					var tot = totall;
				}
				if(valuesl==undefined){
					var val = 0;
				}else{
					var val = valuesl;
				}
				
				var gtot = parseFloat(val) + parseFloat(tot);
				
			
			
 			for(var i=0;i<=counter2;i++){
					
					if(i==0){
						var type = document.getElementById("type");
						var type = type.options[type.selectedIndex].value;
						
						if(type == 1){
							var valuesl2 = document.getElementById("others_charges_amount").value;
						}else{
							var valuesl2 = '0';
						}
					}else{
						var type = document.getElementById("type"+i);
						var type = type.options[type.selectedIndex].value;
						if(type == 1){
							var sum = document.getElementById("others_charges_amount"+i).value;
							if(sum=='-1'){
							var sum1 = 0;
						}else{
							var sum1 = sum;
						}
						j +=  parseFloat(sum1);
						var totall2 =j;
						
					}
					}
				}
				
				if(totall2==undefined){
					var tot1 = 0;
				}else{
					var tot1 = totall2;
				}
				if(valuesl2==undefined){
					var val1 = 0;
				}else{
					var val1 = valuesl2;
				}
				
				var otot = parseFloat(val1) + parseFloat(tot1);
				
				
 			var others_charges = document.getElementById("others_charges_net_amount1").value;
 			var sgstamount =document.getElementById("others_charges_sgst_amount").value;
			var cgstamount =document.getElementById("others_charges_cgst_amount").value;
			var igstamount =document.getElementById("others_charges_igst_amount").value;

 			//Discount 
			var disc_amount_additional1 = document.getElementById("disc_amount_additional1").value;

 			var total = 0;
 			var discount_total = 0; 
			

 			if(counter2 >=1){

 				for(var i=1;i<=counter2;i++){

 					var value = document.getElementById("others_charges_amount"+i).value;
 					var others_charges_sgst_amount = document.getElementById("others_charges_sgst_amount"+i).value;
					var others_charges_cgst_amount = document.getElementById("others_charges_cgst_amount"+i).value;	 	
					var others_charges_igst_amount = document.getElementById("others_charges_igst_amount"+i).value;

 					var condition = document.getElementById("type"+i);
					var condition = condition.options[condition.selectedIndex].value;  

 					if(value >=1 && condition == 1){
 						//Charges Total Section Here
 						total = Number(total) + Number(value);
 						sgstamount = Number(sgstamount) + Number(others_charges_sgst_amount);
 						cgstamount = Number(cgstamount) + Number(others_charges_cgst_amount);
 						igstamount = Number(igstamount) + Number(others_charges_igst_amount);

 					}
 				}
 				//Charges Total Section
 				total = Number(total) + Number(others_charges);
 				//document.getElementById("others_charges_net_amount").value =total;
				
				document.getElementById("others_charges_net_amount").value =otot;
				
				
 				//SGST
 				document.getElementById("oc_sgst_total").value =sgstamount; 
 				document.getElementById("oc_sgst2").value =sgstamount;
 				document.getElementById("sgst_net_amount").value =Number(Number(sgstamount) + Number(sgst2)).toFixed(2) ;  
 				//CGST Total Section 
 				document.getElementById("oc_cgst_total").value =cgstamount; 
 				document.getElementById("oc_cgst2").value =cgstamount;
 				document.getElementById("cgst_net_amount").value =Number(Number(cgstamount)+Number(cgst2)).toFixed(2);  
 				//IGST  
 				document.getElementById("oc_igst_total").value =igstamount; 
 				document.getElementById("oc_igst2").value =igstamount;
 				document.getElementById("igst_net_amount").value =Number(Number(igstamount)+Number(igst2)).toFixed(2);


 			}  		
 			if(counter2 >=1){

 				for(var i=1;i<=counter2;i++){

 					var value = document.getElementById("others_charges_amount"+i).value; 					

 					var condition = document.getElementById("type"+i);
					var condition = condition.options[condition.selectedIndex].value;    

 					if(value >=1 && condition == 2 ){
 						//Charges Discount Section Here
 						discount_total = Number(discount_total) + Number(value);

 					}
 				}
 				//Charges Discount Section
 				discount_total = Number(discount_total) + Number(disc_amount_additional1);
			//	alert(gtot);
 				//document.getElementById("disc_amount_additional").value =discount_total; 
 				document.getElementById("disc_amount_additional").value =gtot; 

 			}	
 			$(this).closest("tr").hide();
			
 			<?php if($row->id !=''){?>
			
			
	 			var dbid2 = document.getElementById("dbid2"+match).value;
				var doc_type = document.getElementById("doctype").value;
	 			//Others Delete
	 			var others = 'others';
//alert(dbid2);
	 			$.ajax({
					type: "POST",
					url: "../ajax/Podelete.php",
					data:{clicked_id:dbid2, others:others,doc_type:doc_type},
					success: function(data){
						var tst =document.getElementById("disc_amount_additional").value;
						var ot =document.getElementById("others_charges_net_amount").value;
						//alert(tst);
						var mydata = JSON.parse(data);  
						if(mydata['delete'] == 1){ 
							document.getElementById("disc_amount_additional").value =tst; 
							document.getElementById("others_charges_net_amount").value =ot; 
						} 
					}
				});   
			<?php } ?>   
			//Total Amount Adding
	 	net_amount();        
    });   
   
</script>

<script type="text/javascript">
//Select 4  Resolve Here

$(document).ready(function() {
	let docType="<?php echo $_REQUEST['doc_type']; ?>";
	if(docType==12){ 
		$("#id_inv_po").click();
	}
});




function fetchOtherCharges(id_po=[]){

var eid = document.getElementById("eId").value; 
var counter2 = document.getElementById("counter2").value;
var id_inv_pooo = document.getElementById("id_inv_poo").value;
//var id_inv_poo = '220';

if(id_inv_pooo != ''){
	var id_inv_poo = document.getElementById("id_inv_poo").value;
}

var inv_item_array_exist 	= $("#inv_item_array").val();

if(inv_item_array_exist!=''){	
	id_po_update=inv_item_array_exist +','+ id_po;	
	$('#inv_item_array').val(id_po_update);
	var inv_itemArray=id_po_update;
}else{
	$('#inv_item_array').val(id_po);
	var inv_itemArray=id_po;
	
	}
//alert(id_po);

	 	let fileName = "";
	 	let docType="<?php echo $_REQUEST['doc_type']; ?>";

	 	if(docType==4){
	 		fileName='fetchOtherCharges.php';
	 	}
	 	else if(docType==5){
	 		fileName='fetchOtherChargesPurch.php';
	 	}

	 	if(id_po != ''){
	 		$.ajax({
	 			url:'ajax/'+fileName,
	 			type:'POST',
	 			datatype:'JSON',
	 			data:'eid='+eid+'&id_inv_po='+id_po+'&id_inv_poo='+id_inv_poo+'&inv_itemArray='+inv_itemArray,
	 			success(data){
	 				data = JSON.parse(data);
	 				console.log(data.error);
	 				$("#discountData").html(data.data);
						//$("#otherschargesdiscount").remove();
						$("#otherstaxconfig").remove();
	 				$("#disc_amount_additional1").val(data.discount_total);
					$("#disc_amount_additional").val(data.discount_total);
					$("#others_charges_net_amount").val(data.others_total);
	 				$("#counter2").val(data.count);
					
					
					var igst = document.getElementById("igst_net_amount").value;
					document.getElementById("igst_net_amount").value =Number(Number(data.others_charges_igst_amount) +Number(igst)).toFixed(2) ;

					
					
					//otherscharges_discount(data.id_mst_charges_others);alert('check2');
					net_amount();
						//loadscheck();
					var count = data.count;
						for(var i=0; i<=count; i++){
							if(i==0){ 
								$("#others_charges_amount").click();
							}else{
								$("#others_charges_amount"+i).click();
							}
						}
						
	 				net_amount();
	 			}
	 		});
	 	}
	 	else{
	 		return;
	 	}
	 }

    $("#addrow4").on("click", function () { 

    	var counter4 =  document.getElementById("counter1").value;  
		var ledger_id =  document.getElementById("ledger_id").value;  
        counter4++;      

	   //Table Row Add Section Here
	   
        var newRow1 = $('<tr id="trdelete' + counter4 + '">');
        var cols1 = ""; 
        var cols2 = ""; 
        cols1 += '<td><select onchange="popupshow(this.id)"  name="id_inv_po' + counter4 + '" id="id_inv_po' + counter4 + '" class="form-control select3"  style="width:100%" required ><option>Select po</option></select> </td>';
        

        cols1 += '<td style="display:none;"><input type="text"  autocomplete="off" placeholder="ID" class="form-control" name="id_inv_po_details' + counter4 + '" id="id_inv_po_details' + counter4 + '" readonly=""/></td>';

        cols1 += '<td><div id="hideshow_item_code'+ counter4 +'"><input type="text"  autocomplete="off" placeholder="Item Code" class="form-control" name="item_code' + counter4 + '" id="item_code' + counter4 + '" readonly=""/></div><div id="hideshow_item_codes'+ counter4 +'" style="display: none;"><select onchange="itemget(this.id)" name="id_inv_items_po' + counter4 + '" id="id_inv_items_po' + counter4 + '" class="form-control select3" style="width:100%"><option>Select Item Code</option><?php 
	                $sql = "SELECT inv_items.*, mst_attributes.field_value FROM inv_items, mst_attributes WHERE  mst_attributes.id=inv_items.id_mst_attributes_group_main and inv_items.id_shop = '".addslashes($_SESSION['shop'])."'";
	                   	 $db->query($sql); 
	                    while($row1 = $db->fetch_object()){ ?>
	                  		<option value="<?php echo $row1->id; ?>"><?php echo $row1->item_code.' | '.$row1->name; ?></option> <?php } 
                  	?></select></div><input type="text"  autocomplete="off" placeholder="Item ID" class="form-control" name="id_inv_items' + counter4 + '" id="id_inv_items' + counter4 + '" style="display:none;"/></td>';  
		
		cols1 += '<td><input type="text"  autocomplete="off" placeholder="Item Description" class="form-control" name="item_description' + counter4 + '" id="item_description' + counter4 + '" readonly=""/></td>';

		cols1 += '<td><select  name="id_mst_attributes_store' + counter4 + '" id="id_mst_attributes_store' + counter4 + '" class="form-control select3"  style="width:100%"><option>Select Store</option><?php 
	                $sql = "SELECT mst_attributes.field_value FROM  mst_attributes WHERE id_shop = '".addslashes($_SESSION['shop'])."' and table_name ='store' ";
	                   	 $db->query($sql); 
	                    while($row1 = $db->fetch_object()){ ?>
	                  		<option value="<?php echo $row1->id; ?>"><?php echo $row1->field_value; ?></option> <?php } 
                  	?></select></td>'; 

		cols1 += '<td><input onkeyup="amount_calc(this.id)" onclick="amount_calc(this.id)"  type="text"  autocomplete="off" placeholder="Qty" class="form-control discountvalue"  name="qty' + counter4 + '" id="qty' + counter4 + '"/></td>'; 


        cols1 += '<td> <select class="form-control select3" id="transaction_unit' + counter4 +'" name="transaction_unit' + counter4 +'" onchange="amount_calc(this.id);" style="width:100%"></select><input type="text"  autocomplete="off" placeholder="Main Unit" class="form-control"  name="main_unit' + counter4 + '" id="main_unit' + counter4 + '" style="display:none;"/><input type="text"  autocomplete="off" placeholder="Alt Unit" class="form-control"  name="alt_unit' + counter4 + '" id="alt_unit' + counter4 + '" style="display:none;"/><input type="text"  autocomplete="off" placeholder="conver_rate_per_unit" class="form-control"  name="conver_rate_per_unit' + counter4 + '" id="conver_rate_per_unit' + counter4 + '" style="display:none;"/></td>'; 

		cols1 += '<td><input  onkeyup="amount_calc(this.id)" type="text"  autocomplete="off" placeholder="Rate" class="form-control discountvalue" name="rate_per_main_unit' + counter4 + '" id="rate_per_main_unit' + counter4 + '" required /><input style="display:none;" type="text"  autocomplete="off"  class="form-control discountvalue"  name="item_amount_before_discount' + counter4 + '" id="item_amount_before_discount' + counter4 + '"/></td>'; 

		 cols1 += '<td> <select class="form-control select3" id="per_unit' + counter4 +'" name="per_unit' + counter4 +'" onchange="amount_calc(this.id);" style="width:100%"></select></td>'; 

        cols1 += '<td><input onkeyup="amount_calc(this.id);" onclick="amount_calc(this.id);"  type="text"  autocomplete="off" placeholder="%Discount" class="form-control" name="discount_percent' + counter4 + '" id="discount_percent' + counter4 + '"/></td>'; 

        cols1 += '<td><input  type="text"  autocomplete="off" placeholder="Amount" class="form-control" name="item_amount' + counter4 + '" id="item_amount' + counter4 + '" readonly/></td>'; 

                 
        cols1 += '<td style="display:none;"><div id="taxconfig" id="taxconfig"><!-- SGST --><input type="text"  autocomplete="off"  name="id_mst_charges_sgst' + counter4 + '" id="id_mst_charges_sgst' + counter4 + '" placeholder="SGST"  class="form-control" /><input type="text"  autocomplete="off"  name="item_sgst_percent' + counter4 + '" id="item_sgst_percent' + counter4 + '" placeholder="SGST"  class="form-control" /><input type="text"  autocomplete="off"  name="item_sgst_amount' + counter4 + '" id="item_sgst_amount' + counter4 + '" placeholder="SGST Amount"  class="form-control"  /><!-- CGST --><input type="text"  autocomplete="off"  name="id_mst_charges_cgst' + counter4 + '" id="id_mst_charges_cgst' + counter4 + '" placeholder="CGST"  class="form-control" /><input type="text"  autocomplete="off"  name="item_cgst_percent' + counter4 + '" id="item_cgst_percent' + counter4 + '" placeholder="CGST"  class="form-control" /><input type="text"  autocomplete="off"  name="item_cgst_amount' + counter4 + '" id="item_cgst_amount' + counter4 + '" placeholder="CGST Amount"  class="form-control" /><!-- IGST --><input type="text"  autocomplete="off"  name="id_mst_charges_igst' + counter4 + '" id="id_mst_charges_igst' + counter4 + '" placeholder="IGST"  class="form-control" /><input type="text"  autocomplete="off"  name="item_igst_percent' + counter4 + '" id="item_igst_percent' + counter4 + '" placeholder="IGST"  class="form-control" /><input type="text"  autocomplete="off"  name="item_igst_amount' + counter4 + '" id="item_igst_amount' + counter4 + '" placeholder="IGST Amount"  class="form-control" /></div></td>';		 
		 $("</tr>"); 

		 var newRow11 =  $('<tr id="trdeletes' + counter4 + '">');

		 	 cols2 += '<td><input type="text"  autocomplete="off" placeholder="Remarks3" class="form-control" name="item_remarks' + counter4 + '" id="item_remarks' + counter4 + '"/></td>';
		 	 cols2 += '<td><div id="local'+counter4+'" name="local'+counter4+'" style="display:none;"><select  name="id_mst_charges_purchase_local' + counter4 + '" id="id_mst_charges_purchase_local' + counter4 + '" class="form-control select3" onchange="po_locals(this.id)" style="width:100%;"><option>Select</option><?php 
	                $sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '1'";
	                   	 $db->query($sql); 
	                    while($row1 = $db->fetch_object()){ ?>
	                  		<option value="<?php echo $row1->id; ?>"><?php echo $row1->name ?></option> <?php } ?></select></div><div id="interstate'+counter4+'" name="interstate'+counter4+'" style="display:none;"><select  onchange="po_interstate(this.id)"  name="id_mst_charges_purchase_interstate' + counter4 + '" id="id_mst_charges_purchase_interstate' + counter4 + '" class="form-control select3" style="width:100%;" ><option>Select</option><?php 
	                $sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '2'";
	                   	 $db->query($sql); 
	                    while($row1 = $db->fetch_object()){ ?>
	                  		<option value="<?php echo $row1->id; ?>"><?php echo $row1->name ?></option> <?php }?></select></div></td> '; 
	                  	cols2 += '<td><div id="localsss'+counter4+'" name="localsss'+counter4+'" style="display:none;"><span style="color:red;font-size:14px;" id="s_amount'+counter4+'"></span> <span style="color:red;font-size:14px;;font-size:14px;" id="c_amount'+counter4+'"></span></div><div id="interstatesss'+counter4+'" name="interstatesss'+counter4+'" style="display:none;"><span style="color:red;font-size:14px;" id="i_amount' + counter4 + '" name="i_amount' + counter4 + '"></span></div></td>';
	                  	cols2 += '<td></td>';
	                  	cols2 += '<td></td>';
	                  	cols2 += '<td></td>';
	                  	cols2 += '<td></td>';
	                  	cols2 += '<td></td>';
	                  	cols2 += '<td></td>';
	                  		cols2 += '<td><img src="images/delete.gif"  class="ibtnDel1" id="deletes' + counter4 + '" name="deletes' + counter4 + '" style="cursor:pointer;" title="Delete"/></td>';
		 $("</tr>"); 

		document.getElementById("counter1").value = counter4;
		newRow1.append(cols1);
		newRow11.append(cols2);
        $("table.order-list1").append(newRow1); 
        $("table.order-list1").append(newRow11); 
		$(".select3").select2({});
         
         $(".select3").last().next().next().remove();

        

        if(ledger_id == 1){
	    	$("#local"+counter4).show();
	    	$("#localsss"+counter4).show();
	    	$("#interstate"+counter4).hide();
	    	$("#interstatesss"+counter4).hide();
	    	
	    }else if(ledger_id == 2){
	    	$("#local"+counter4).hide();
	    	$("#localsss"+counter4).hide();
	    	$("#interstate"+counter4).show(); 
	    	$("#interstatesss"+counter4).show(); 
	    }  
    }); 


	 
</script>	



<script>
$(document).on('click', '.discountvalue' ,function (e) {
 $(".discountvalue").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
        });
		
//below code to show address for supplier

function comShow(id){
			var comId = id;
			 $.ajax({
			 type        : 'POST',
			 url         : 'ajax/ajaxComShow.php', 
			 data        : 'comId='+comId,
			 success     : function(data){
			   $("#comData").html(data);
			    //$("#comData2").val($(this).val());
             // $("#comData2").html(data);
			 } 
			})
		} 


		//second function

	function comShow2(id){
			var comId = id;
			 $.ajax({
			 type        : 'POST',
			 url         : 'ajax/ajaxComShow.php', 
			 data        : 'comId='+comId,
			 success     : function(data){
			   $("#comData2").html(data);
			 } 
			})
		} 

function partybilltobe(value){
	
			 $.ajax({
			 type        : 'POST',
			 url         : 'ajax/ajaxPartyBilltobe.php', 
			 data        : 'Id='+value,
			 success     : function(data){ 
			   $("#id_mst_party_billtobe").html(data);
			   comShow2(value);
			 } 
			})
	}

</script>
