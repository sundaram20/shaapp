<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_PO,'view');

if($_REQUEST['eId']=='')
	checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_PO,'add');
else
	checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_PO,'edit');

//debugData($_REQUEST);
//exit; //

//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){

	$err = 0;
	
	//debugData($_REQUEST);
	//die;
	//Insert Here !
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Save') && empty($_POST['eId'])){//add 

			 checkUserLevelPermission($_SESSION['userLevel'], TBL_INV_PO,'add');
			 //Indent No Check Here
			 $doc_no = $_POST['doc_no'];

			$sql5 = " SELECT * FROM `".TBL_INV_PO."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_no`='".$doc_no."'  and `id_doc_type_configuration` = '".addslashes($_POST['id_doc_type_configuration'])."'  and `doc_type` = '3'  ";
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
			$addSql = "   	INSERT INTO `".TBL_INV_PO."` SET

							`doc_type` = '".addslashes($_POST['doc_type'])."', 
							`doc_no` = '".addslashes($doc_no)."',  
							`doc_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['doc_date1'])))."',  
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
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$addSql .= ",`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`status` = '".addslashes($_POST['status'])."'";
							executeSql($addSql);

							$lastInsertId= $db->insert_id();
							//print_r($lastInsertId);

				//PO Details Table Here Detault Value Set
  						if($_POST["id_inv_indent"] == 'na'){
  							$id_inv_indent = 0;
  							$type = 0;
  						}else{
  							$id_inv_indent = $_POST["id_inv_indent"];
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
		
$ins_ind_id =explode('-',$id_inv_indent)[1];
		
				$addSql = " INSERT INTO `".TBL_INV_PO_DETAILS."` SET

							`id_inv_po` = '".addslashes($lastInsertId)."',  
							`id_inv_indent` = '".explode('-',$id_inv_indent)[1]."', 
							`id_inv_indent_details` = '".addslashes($_POST["id_inv_indent_details"])."',
							`base_currency_code` = '".addslashes($_POST['base_currency_code1'])."',
							`transaction_currency_code` = '".addslashes($_POST['transaction_currency_code1'])."',
							`exchange_rate` = '".addslashes($_POST['exchange_rate'])."', 
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
							executeSql($addSql);

						if($type == 1){
						//Order Qty Check Here
							$order_total= 0;$balance_qty =0;
							$id_inv_indent_details = $_POST["id_inv_indent_details"];
							$sql1 = "SELECT sum(qty) as qty FROM `".TBL_INV_PO_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_indent_details`='".$id_inv_indent_details."'  ";
		                   	$db->query($sql1);
		                    $row1 = $db->fetch_object();
		                    $order_total = $row1->qty;
						//Total Qty Get
						$total_qty=selectColumn(TBL_INV_INDENT_DETAILS,'qty'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$id_inv_indent_details."' ");
						$balance_qty = $total_qty - $order_total;
						//Order Qty Update Indent Details Table
						 $editSql = "UPDATE `".TBL_INV_INDENT_DETAILS."` SET `ordered_qty` = '".$order_total."', `bal_qty` = '".$balance_qty."' WHERE `id` = '".addslashes($id_inv_indent_details)."'";
							executeSql($editSql); 
						}
						//PO Details Table Here For Loop Value Set
							$counter1 = $_POST['counter1'];


							for($i = 1; $i <= $counter1; $i++){

								if($_POST['id_inv_indent'.''.$i] == 'na'){
		  							$id_inv_indent = 0;
		  							$type = 0;
		  						}else{
		  							$id_inv_indent = $_POST['id_inv_indent'.''.$i];
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
						
		$ins_indent_id = explode('-',$id_inv_indent)[1];				

								//if($_POST['id_inv_indent'.''.$i] != '' && $_POST['item_amount'.''.$i] >=1){
								if($_POST['id_inv_indent'.''.$i] != ''){
									$addSql = "INSERT INTO `".TBL_INV_PO_DETAILS."` SET

									`id_inv_po` = '".addslashes($lastInsertId)."',  
									`id_inv_indent` = '".explode('-',$id_inv_indent)[1]."', 
									`id_inv_indent_details` = '".addslashes($_POST["id_inv_indent_details".''.$i])."',
									`base_currency_code` = '".addslashes($_POST['base_currency_code1'])."',
									`transaction_currency_code` = '".addslashes($_POST['transaction_currency_code1'])."',
									`exchange_rate` = '".addslashes($_POST['exchange_rate'])."',
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
									executeSql($addSql);

								if($type ==1){
									//Order Qty Check Here
									$order_total= 0;$balance_qty =0;
									$id_inv_indent_details = $_POST["id_inv_indent_details".''.$i];
									$sql1 = "SELECT sum(qty) as qty FROM `".TBL_INV_PO_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_indent_details`='".$id_inv_indent_details."'  ";
				                   	$db->query($sql1);
				                    $row1 = $db->fetch_object();
				                    $order_total = $row1->qty;
									//Total Qty Get
										$total_qty=selectColumn(TBL_INV_INDENT_DETAILS,'qty'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$id_inv_indent_details."' ");
										$balance_qty = $total_qty - $order_total;
									//Order Qty Update Indent Details Table
									 $editSql = "UPDATE `".TBL_INV_INDENT_DETAILS."` SET `ordered_qty` = '".$order_total."', `bal_qty` = '".$balance_qty."' WHERE `id` = '".addslashes($id_inv_indent_details)."'";
										executeSql($editSql);  
									}
								}
							}
//if($_POST['others_charges_amount']>0  || $_POST['others_charges_amount']>0){
						//Others Table Section Here
							$addSql = "   	INSERT INTO `".TBL_INV_OTHERS_CHARGES."` SET

							`id_inv_po` = '".addslashes($lastInsertId)."', 
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
//}
					//Others Tables Details Table Here For Loop Value Set
							$counter2 = $_POST['counter2'];

							for($i = 1; $i <= $counter2; $i++){

								if($_POST['type'.''.$i] != ''){

									$addSql = "   	INSERT INTO `".TBL_INV_OTHERS_CHARGES."` SET

									`id_inv_po` = '".addslashes($lastInsertId)."',  
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
if($_POST['terms']!=''){
						//Terms And Condtions Table Section Here
							$addSql = "   	INSERT INTO `".TBL_INV_TERMS_AND_CONDITIONS."` SET

							`id_inv_po` = '".addslashes($lastInsertId)."',   
							`terms` = '".addslashes($_POST['terms'])."',   
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$addSql .= "	,`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`status` = '".addslashes($_POST['status'])."'";
							executeSql($addSql);
				}
					//Terms And Conditions Tables Details Table Here For Loop Value Set
							$counter3 = $_POST['counter3'];

							for($i = 1; $i <= $counter3; $i++){

								if($_POST['terms'.''.$i] != ''){
if($_POST['terms'.''.$i]!=''){
									$addSql = "   	INSERT INTO `".TBL_INV_TERMS_AND_CONDITIONS."` SET

									`id_inv_po` = '".addslashes($lastInsertId)."',   
									`terms` = '".addslashes($_POST['terms'.''.$i])."', 
									`id_shop` = '".addslashes($_SESSION['shop'])."'";

									$addSql .= "	,`date_created` = '".currenDateTime()."',
									`last_modified` = '".currenDateTime()."',
									`id_mst_user_modified_by` = '".$_SESSION['userId']."',
									`id_mst_user_created_by` = '".$_SESSION['userId']."',
									`status` = '".addslashes($_POST['status'])."'";
									executeSql($addSql);
}
								}
							} 

			if(1){
				$_SESSION['successMsg'] = 'New PO has been added sucessfully.';
				//$auditquery2 = "SELECT * From `".TBL_INV_PO_DETAILS."` ";

						 // $auditresSQL2 = mysqli_query($connNew, $auditquery);	
						// $auditrow2 = mysqli_fetch_object($auditresSQL2);
						 //print_r($auditrow2->id);
				header("location:printPO.php?eId=".addslashes(encryptor(encrypt,$lastInsertId))."&submenu=".$_GET['submenu']."&session=".$_GET['session']."&action=edit&page=".$_REQUEST['page']."&print=1"); 
			
				
				
				
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = ' PO has not been saved. Please make corrections below.';
			}
		}



		//Update Section Here

		else if(($_POST['Save'] == 'Save') && !empty($_POST['eId'])){//update
		
		 
			checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_PO,'update');

			if($_POST['prefix'] !='' OR $_POST['suffix'] !=''){
				$mdoc_no = $_POST['prefix'].''.$_POST['doc_no'].''.$_POST['suffix'];
			}else{
				$mdoc_no = $_POST['mdoc_no'];
			}

			//Purchase Order Request

			 $auditquery = "SELECT * From `".TBL_INV_PO."` WHERE id = '".addslashes(encryptor(decrypt,$_POST[eId]))."'  ";

			  $auditresSQL = mysqli_query($connNew, $auditquery);	
				while($auditrow = mysqli_fetch_object($auditresSQL)){ 
				 
				  $id_mst_party_billtobe = $auditrow ->id_mst_party_billtobe; 
				  $exchange_rate = $auditrow ->exchange_rate; 
				  $id_mst_charges_discounts_items = $auditrow ->id_mst_charges_discounts_items; 
				 


				 //Change Data
			    if($id_mst_party_billtobe != $_POST['id_mst_party_billtobe']){
					$old_data = selectColumn(TBL_PARTY,'company_name'," WHERE `id` = '".$id_mst_party_billtobe."'");
					$new_data = selectColumn(TBL_PARTY,'company_name'," WHERE `id` = '".$_POST['id_mst_party_billtobe']."'  ");
					$bill_s = "Bill To Be Changed from ". $old_data." - to - " . $new_data;
				}
				if($exchange_rate != $_POST['exchange_rate']){
					 
					$exchange_s = "Exchange Rate Changed from ". $exchange_rate." - to - " . $_POST['exchange_rate'];
				}
				
				if($_POST['id_mst_charges_discounts_items']!=''){
					$emp = $_POST['id_mst_charges_discounts_items'];
				}else{
					$emp = '0';
				}
				
				if($id_mst_charges_discounts_items != $emp){
					$old_data = selectColumn('mst_charges','name'," WHERE `id` = '".$id_mst_charges_discounts_items."'  AND charges_account = '6'");
					$new_data = selectColumn('mst_charges','name'," WHERE `id` = '".$_POST['id_mst_charges_discounts_items']."'  AND charges_account = '6' ");
					$discount_s = "Discount Scheme Apply Changed from ". $old_data." - to - " . $new_data;
				}
			}

					
			//Update Indent Table
			 $editSql = "   	UPDATE `".TBL_INV_PO."`  SET  

							`doc_type` = '".addslashes($_POST['doc_type'])."', 
							`doc_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['doc_date1'])))."',  
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
								if($_POST["id_inv_indent"] == 'na'){
									$id_inv_indent = 0;
									$type = 0;
								}else{
									$id_inv_indent = $_POST["id_inv_indent"];
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

					 	//Audit Files Select Here
					 	$auditquery = "SELECT * From `".TBL_INV_PO_DETAILS."` WHERE id = '".addslashes($_POST['update_id'])."'  ";

						  $auditresSQL = mysqli_query($connNew, $auditquery);	
							while($auditrow = mysqli_fetch_object($auditresSQL)){ 
							 
							  $id_inv_indent = $auditrow ->id_inv_indent; 
							  $qty = $auditrow ->qty; 
							  $main_unit = $auditrow ->main_unit;							 
							  $transaction_unit = $auditrow ->transaction_unit;					 
							  $rate_per_main_unit = $auditrow ->rate_per_main_unit;				 
							  $per_unit = $auditrow ->per_unit;							 
							  $discount_percent = $auditrow ->discount_percent;					 
							  $item_remarks = $auditrow ->item_remarks;							 
							  $id_mst_charges_purchase_local = $auditrow ->id_mst_charges_purchase_local;							 
							  $bill_no = selectColumn('inv_po','mdoc_no'," WHERE `id` = '".$auditrow ->id_inv_po."' ");							 

							 //Change Data
						    if($id_inv_indent != explode('-',$id_inv_indent)[1]){
						    	$inv = $_POST["id_inv_indent"];
								//$id_inv_indent_s = "Purchase Order Details Inventory Indent Changed from ". $id_inv_indent." - to - " . explode('-',$inv)[1];
							}
							if($qty != $qty_total){ 
								$indent_qty_s = "Purchase Order Details Qty Changed from ". $qty." - to - " .$qty_total.'  in Rowno 1 ';
							}
							if($main_unit != $_POST["main_unit"]){ 
								//$indent_main_unit_s = "Unit Changed from ". $main_unit." - to - " .$_POST["main_unit"].'  in Rowno 1';
							}
							if($transaction_unit != $_POST["transaction_unit"]){ 
								$indent_transaction_unit_s = "Purchase Order Details Unit Changed from ". $transaction_unit." - to - " .$_POST["transaction_unit"].' in Rowno 1 ';
							}
							if($rate_per_main_unit != $_POST["rate_per_main_unit"]){ 
								$indent_rate_per_main_unit_s = "Purchase Order Details  Rate Changed from ". $rate_per_main_unit." - to - " .$_POST["rate_per_main_unit"].' in Rowno 1 ';
							}
							if($per_unit != $_POST["per_unit"]){ 
								$indent_per_unit_s = "Purchase Order Details Per Changed from ". $per_unit." - to - " .$_POST["per_unit"].' in Rowno  1 ';
							}
							if($discount_percent != $_POST["discount_percent"]){ 
								$indent_discount_percent_s = "Purchase Order Details  %Discount Changed from ". $discount_percent." - to - " .$_POST["discount_percent"].' in Rowno  1 ';
							}
							if($item_remarks != $_POST["item_remarks"]){ 
								$indent_item_remarks_s = "Purchase Order Details Remarks Changed from ". $item_remarks." - to - " .$_POST["item_remarks"].' in Rowno 1 ';
							}
							if($id_mst_charges_purchase_local != $_POST["id_mst_charges_purchase_local"]){ 
								$old_data = selectColumn('mst_charges','name'," WHERE `id` = '".$id_mst_charges_purchase_local."'");
								$new_data = selectColumn('mst_charges','name'," WHERE `id` = '".$_POST['id_mst_charges_purchase_local']."'  ");

								$indent_charge_purchase_s = "Purchase Order Details Select Tax Register Changed from ". $old_data." - to - " .$new_data.'  in Rowno 1 ';
							}
						}
						
	 						//Update Ordered_qty and Bal_qty=======================
							$SQLordered_qty ="SELECT sum(qty) as ordered_qty ,id_inv_items from inv_purch_details where id_inv_po = '".addslashes(encryptor(decrypt,$_POST[eId]))."' AND `id_inv_items` = '".addslashes($_POST['id_inv_items'])."'";
							
							$QuerySQL1			= mysqli_query($connNew,$SQLordered_qty);	
							$resultOrdered_qty	= mysqli_fetch_object($QuerySQL1);							

							 $ordered_qty 		 = $resultOrdered_qty->ordered_qty;
							 $bal_qty 			 = $qty_total	- $resultOrdered_qty->ordered_qty;

							 //Update Ordered_qty and Bal_qty=======================
							 
							 $id_indentt1 = explode('-',$id_inv_indent)[1];					
						
							$editSql = "   	UPDATE `".TBL_INV_PO_DETAILS."`  SET  

							`id_inv_po` = '".addslashes(encryptor(decrypt,$_POST[eId]))."', 
							`id_inv_indent` = '".explode('-',$id_inv_indent)[1]."', 
									`id_inv_indent_details` = '".addslashes($_POST["id_inv_indent_details"])."',
							`base_currency_code` = '".addslashes($_POST['base_currency_code1'])."',
							`transaction_currency_code` = '".addslashes($_POST['transaction_currency_code1'])."',
							`exchange_rate` = '".addslashes($_POST['exchange_rate'])."',
							`id_inv_items` = '".addslashes($_POST['id_inv_items'])."', 
							`id_mst_charges_sgst` = '".addslashes($_POST['id_mst_charges_sgst'])."', 
							`id_mst_charges_cgst` = '".addslashes($_POST['id_mst_charges_cgst'])."', 
							`id_mst_charges_igst` = '".addslashes($_POST['id_mst_charges_igst'])."',
							`transaction_unit` = '".addslashes($_POST["transaction_unit"])."', 
							`qty` = '".addslashes($qty_total)."', 
							`bal_qty` = '".addslashes($bal_qty)."',
							`ordered_qty` = '".addslashes($ordered_qty)."',
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

							if($type = 1){
								//Order Qty Check Here
								$order_total= 0;$balance_qty =0;
								$id_inv_indent_details = $_POST["id_inv_indent_details"];
								$sql1 = "SELECT sum(qty) as qty FROM `".TBL_INV_PO_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_indent_details`='".$id_inv_indent_details."'  ";
			                   	$db->query($sql1);
			                    $row1 = $db->fetch_object();
			                    $order_total = $row1->qty;
							//Total Qty Get
								$total_qty=selectColumn(TBL_INV_INDENT_DETAILS,'qty'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$id_inv_indent_details."' ");
								$balance_qty = $total_qty - $order_total;
							//Order Qty Update Indent Details Table
							 $editSql = "UPDATE `".TBL_INV_INDENT_DETAILS."` SET `ordered_qty` = '".$order_total."', `bal_qty` = '".$balance_qty."' WHERE `id` = '".addslashes($id_inv_indent_details)."'";
								executeSql($editSql);
							}

							
				//Update INV PO DETAILS Id For Loops Section
							if($_POST['update_count'] == ''){
								$update_count = 0;								
							}else{
								$update_count = $_POST['update_count'];									
							}

							for($i = 1; $i <= $update_count; $i++){

								if($_POST['id_inv_indent'.''.$i] == 'na'){
		  							$id_inv_indent = 0;
		  							$type = 0;
		  						}else{
		  							$id_inv_indent = $_POST['id_inv_indent'.''.$i];
		  							$type = 1;
		  						}


 $id_indentt = explode('-',$id_inv_indent)[1];
//echo $id_inv_indent;
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

			 		 	$auditquery = "SELECT * From `".TBL_INV_PO_DETAILS."` WHERE id = '".addslashes($_POST['update_id'.''.$i])."'  ";

						  $auditresSQL = mysqli_query($connNew, $auditquery);	
							while($auditrow = mysqli_fetch_object($auditresSQL)){ 
							 
							  $id_inv_indent = $auditrow ->id_inv_indent; 
							  $qty = $auditrow ->qty; 
							  $main_unit = $auditrow ->main_unit;							 
							  $transaction_unit = $auditrow ->transaction_unit;					 
							  $rate_per_main_unit = $auditrow ->rate_per_main_unit;				 
							  $per_unit = $auditrow ->per_unit;							 
							  $discount_percent = $auditrow ->discount_percent;					 
							  $item_remarks = $auditrow ->item_remarks;							 
							  $id_mst_charges_purchase_local = $auditrow ->id_mst_charges_purchase_local;							 
							  $bill_no = selectColumn('inv_po','mdoc_no'," WHERE `id` = '".$auditrow ->id_inv_po."' ");							 

							 //Change Data
							  $val = $i;
							  $val = $val + 1;
						    if($id_inv_indent != explode('-',$id_inv_indent)[1]){
						    	$inv = $_POST["id_inv_indent".''.$i];
								//$id_inv_indent_s = "Purchase Order Details Inventory Indent Changed from ". $id_inv_indent." - to - " . explode('-',$inv)[1].' in Rowno '.$val.' and Bill No '.$bill_no;
							}
							if($qty != $qty_total){ 
								$indent_qty_s .= " | Purchase Order Details Qty Changed from ". $qty." - to - " .$qty_total.' in Rowno '.$val;
							}
							if($main_unit != $_POST["main_unit".''.$i]){ 
								//$indent_main_unit_s .= " | Unit Changed from ". $main_unit." - to - " .$_POST["main_unit".''.$i].' in Rowno '.$val;
							}
							if($transaction_unit != $_POST["transaction_unit".''.$i]){ 
								$indent_transaction_unit_s .= "| Purchase Order Details Unit Changed from ". $transaction_unit." - to - " .$_POST["transaction_unit".''.$i].' in Rowno '.$val;
							}
							if($rate_per_main_unit != $_POST["rate_per_main_unit".''.$i]){ 
								$indent_rate_per_main_unit_s .= " | Purchase Order Details Rate Changed from ". $rate_per_main_unit." - to - " .$_POST["rate_per_main_unit".''.$i].' in Rowno '.$val;
							}
							if($per_unit != $_POST["per_unit".''.$i]){ 
								$indent_per_unit_s .= " | Purchase Order Details Per Unit Changed from ". $per_unit." - to - " .$_POST["per_unit".''.$i].' in Rowno '.$val;
							}
							if($discount_percent != $_POST["discount_percent".''.$i]){ 
								$indent_discount_percent_s .= " |  Purchase Order Details %Discount Changed from ". $discount_percent." - to - " .$_POST["discount_percent".''.$i].' in Rowno '.$val;
							}
							if($item_remarks != $_POST["item_remarks".''.$i]){ 
								$indent_item_remarks_s .= " | Purchase Order Details Remarks Changed from ". $item_remarks." - to - " .$_POST["item_remarks".''.$i].' in Rowno '.$val;
							}
							if($id_mst_charges_purchase_local != $_POST["id_mst_charges_purchase_local".''.$i]){ 
								$old_data = selectColumn('mst_charges','name'," WHERE `id` = '".$id_mst_charges_purchase_local."'");
								$new_data = selectColumn('mst_charges','name'," WHERE `id` = '".$_POST['id_mst_charges_purchase_local'.''.$i]."'");

								$indent_charge_purchase_s .= " | Purchase Order Details Select Tax Register Changed from ". $old_data." - to - " .$new_data.' in Rowno '.$val;
							}
							}
							
							
							//Update Ordered_qty and Bal_qty=======================
							$SQLordered_qty ="SELECT sum(qty) as ordered_qty ,id_inv_items from inv_purch_details where id_inv_po = '".addslashes(encryptor(decrypt,$_POST[eId]))."' AND `id_inv_items` = '".addslashes($_POST['id_inv_items'.''.$i])."'";
							
							$QuerySQL1			= mysqli_query($connNew,$SQLordered_qty);	
							$resultOrdered_qty	= mysqli_fetch_object($QuerySQL1);							

							 $ordered_qty 		 = $resultOrdered_qty->ordered_qty;
							 $bal_qty 			 = $qty_total	- $resultOrdered_qty->ordered_qty;

							 //Update Ordered_qty and Bal_qty=======================
							 
							 
								$editSql = "   	UPDATE `".TBL_INV_PO_DETAILS."`  SET  

								`id_inv_po` = '".addslashes(encryptor(decrypt,$_POST[eId]))."', 
								`id_inv_indent` = '".explode('-',$id_inv_indent)[1]."', 
								`id_inv_indent_details` = '".addslashes($_POST["id_inv_indent_details".''.$i])."',  
								`base_currency_code` = '".addslashes($_POST['base_currency_code1'])."',
								`transaction_currency_code` = '".addslashes($_POST['transaction_currency_code1'])."',
								`exchange_rate` = '".addslashes($_POST['exchange_rate'])."',
								`id_inv_items` = '".addslashes($_POST['id_inv_items'.''.$i])."', 
								`id_mst_charges_sgst` = '".addslashes($_POST['id_mst_charges_sgst'.''.$i])."', 
								`id_mst_charges_cgst` = '".addslashes($_POST['id_mst_charges_cgst'.''.$i])."', 
								`id_mst_charges_igst` = '".addslashes($_POST['id_mst_charges_igst'.''.$i])."',
								`transaction_unit` = '".addslashes($_POST["transaction_unit".''.$i])."', 
								`qty` = '".addslashes($qty_total)."', 
								`bal_qty` = '".addslashes($bal_qty)."', 
								`ordered_qty` = '".addslashes($ordered_qty)."', 
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

								if($type ==1){
								//Order Qty Check Here
									$order_total= 0;$balance_qty =0;
									$id_inv_indent_details = $_POST["id_inv_indent_details".''.$i];
									$sql1 = "SELECT sum(qty) as qty FROM `".TBL_INV_PO_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_indent_details`='".$id_inv_indent_details."'  ";
				                   	$db->query($sql1);
				                    $row1 = $db->fetch_object();
				                    $order_total = $row1->qty;
									//Total Qty Get
										$total_qty=selectColumn(TBL_INV_INDENT_DETAILS,'qty'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$id_inv_indent_details."' ");
										$balance_qty = $total_qty - $order_total;
										
										
										
									//Order Qty Update Indent Details Table
									 $editSql = "UPDATE `".TBL_INV_INDENT_DETAILS."` SET `ordered_qty` = '".$order_total."', `bal_qty` = '".$balance_qty."' WHERE `id` = '".addslashes($id_inv_indent_details)."'";
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

								if($_POST['id_inv_indent'.''.$i] == 'na'){
		  							$id_inv_indent = 0;
		  							$type = 0;
		  						}else{
		  							$id_inv_indent = $_POST['id_inv_indent'.''.$i];
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
								 
								if($_POST['id_inv_indent'.''.$i] != '' ){
									//if($_POST['id_inv_indent'.''.$i] != '' && $_POST['item_amount'.''.$i] >=1){
									if($_POST['id_inv_indent'.''.$i]){ 
										//$ch5 = "NA Details Added ";
										$ne = selectColumn(TBL_INV_ITEMS,'name','WHERE id="'.$_POST['id_inv_items'.''.$i].'" ');
										$ch_5 .= $ne." Details Added ";
									}

									/*if( $qty_total){ 
										$indent_qty_s .= " | Indent Quantity Insert " .$qty_total;
									}
									if($_POST["main_unit".''.$i]){ 
										$indent_main_unit_s .= " | Indent Main Unit  Insert " .$_POST["main_unit".''.$i];
									}
									if($_POST["transaction_unit".''.$i]){ 
										$indent_transaction_unit_s .= "| Indent Transaction Unit  Insert ".$_POST["transaction_unit".''.$i];
									}
									if($_POST["rate_per_main_unit".''.$i]){ 
										$indent_rate_per_main_unit_s .= " | Indent Rate  Insert ".$_POST["rate_per_main_unit".''.$i];
									}
									if($_POST["per_unit".''.$i]){ 
										$indent_per_unit_s .= " | Indent Rate Per Unit  Insert ".$_POST["per_unit".''.$i];
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

									$addSql = "INSERT INTO `".TBL_INV_PO_DETAILS."` SET

									`id_inv_po` = '".addslashes(encryptor(decrypt,$_POST[eId]))."',  
									`id_inv_indent` = '".addslashes($id_inv_indent)."', 
									`id_inv_indent_details` = '".addslashes($_POST["id_inv_indent_details".''.$i])."',
									`base_currency_code` = '".addslashes($_POST['base_currency_code1'])."',
									`transaction_currency_code` = '".addslashes($_POST['transaction_currency_code1'])."',
									`exchange_rate` = '".addslashes($_POST['exchange_rate'])."',
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

									if($type = 1){
										//Order Qty Check Here
										$order_total= 0;$balance_qty =0;
										$id_inv_indent_details = $_POST["id_inv_indent_details".''.$i];
										$sql1 = "SELECT sum(qty) as qty FROM `".TBL_INV_PO_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_indent_details`='".$id_inv_indent_details."'  ";
					                   	$db->query($sql1);
					                    $row1 = $db->fetch_object();
					                    $order_total = $row1->qty;
										//Total Qty Get
											$total_qty=selectColumn(TBL_INV_INDENT_DETAILS,'qty'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$id_inv_indent_details."' ");
											$balance_qty = $total_qty - $order_total;
											
											
										//Order Qty Update Indent Details Table
										 $editSql = "UPDATE `".TBL_INV_INDENT_DETAILS."` SET `ordered_qty` = '".$order_total."', `bal_qty` = '".$balance_qty."' WHERE `id` = '".addslashes($id_inv_indent_details)."'";
									executeSql($editSql);
									}
								}
							}

							//Audit Files Select Here
					 	 $auditquery = "SELECT * From `".TBL_INV_OTHERS_CHARGES."` WHERE id = '".addslashes($_POST['chargesupdate_id'])."'  ";

						  $auditresSQL = mysqli_query($connNew, $auditquery);	
							while($auditrow = mysqli_fetch_object($auditresSQL)){ 
							 
							  $type = $auditrow ->type; 
							  $others_charges_percent = $auditrow ->others_charges_percent; 
							  $others_charges_amount = $auditrow ->others_charges_amount; 
							  $id_mst_charges_discounts = $auditrow ->id_mst_charges_discounts; 							 
							  $id_mst_charges_others = $auditrow ->id_mst_charges_others; 							 
							  $bill_no = selectColumn('inv_po','mdoc_no'," WHERE `id` = '".$auditrow ->id_inv_po."' ");
							 							 

							 //Change Data
						    if($type != $_POST['type']){ 
						    	if($type == '1'){$type="Others";}else{$type="Discount";}
						    	if($_POST['type'] == '1'){$newdata="Others";}else{$newdata="Discount";}
								//$others_type_s = "Others/Discount  Changed from ". $type." - to - " .$newdata." in Rowno 1 and Bill no ".$bill_no;
							}

							//echo $id_mst_charges_others.'<br>';
							//echo $_POST['id_mst_charges_others'].'<br>';
							
							if($_POST['id_mst_charges_others']==''){
								$var = '0';
							}else{
								$var = $_POST['id_mst_charges_others'];
							}
							
							if($id_mst_charges_others != $var){ 
								$old_data = selectColumn('mst_charges','name'," WHERE `id` = '".$id_mst_charges_discounts."'  AND charges_account = '6'");
								$new_data = selectColumn('mst_charges','name'," WHERE `id` = '".$_POST['id_mst_charges_discounts']."'  AND charges_account = '6' ");
								$others_charge_discount_s = "Charges/Discount Changed from ". $old_data." - to - " .$new_data."  in Rowno 1 ";
							}
							
							
							if($others_charges_percent != $_POST['others_charges_percent']){ 
								$others_charge_percent_s = "Others/Discount  Percentage Changed from ". $others_charges_percent." - to - " .$_POST['others_charges_percent']."  in Rowno 1 ";
							}
							if($others_charges_amount != $_POST['others_charges_amount']){ 
								$others_charge_amount_s = "Others/Discount Amount Changed from ". $others_charges_amount." - to - " .$_POST['others_charges_amount']." |  in Rowno 1 ";
							}
						}

					    //Update Others Charges Details
							$editSql = "   	UPDATE `".TBL_INV_OTHERS_CHARGES."`  SET  

							`id_inv_po` = '".addslashes(encryptor(decrypt,$_POST[eId]))."',   
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
					 			$auditquery = "SELECT * From `".TBL_INV_OTHERS_CHARGES."` WHERE id = '".addslashes($_POST['chargesupdate_id'.$i])."'  ";

						  	$auditresSQL = mysqli_query($connNew, $auditquery);	
							while($auditrow = mysqli_fetch_object($auditresSQL)){ 
							 
							  $type = $auditrow ->type; 
							  $others_charges_percent = $auditrow ->others_charges_percent; 
							  $others_charges_amount = $auditrow ->others_charges_amount; 
							  $id_mst_charges_discounts = $auditrow ->id_mst_charges_discounts; 							 
							  $bill_no = selectColumn('inv_po','mdoc_no'," WHERE `id` = '".$auditrow ->id_inv_po."' ");
							 							 

							 //Change Data
							    if($type != $_POST['type'.$i]){ 
							    	if($type == '1'){$type="Others";}else{$type="Discount";}
							    	if($_POST['type'.$i] == '1'){$newdata="Others";}else{$newdata="Discount";}
									$others_type_s .= " | Others/Discount Changed from ". $type." - to - " .$newdata." in Rowno ".$val;
								}
								if($others_charges_percent != $_POST['others_charges_percent'.$i]){ 
									$others_charge_percent_s .= " | Others/Discount  Percentage Changed from ". $others_charges_percent." - to - " .$_POST['others_charges_percent'.$i]." in Rowno ".$val;
								}
								if($others_charges_amount != $_POST['others_charges_amount'.$i]){ 
									$others_charge_amount_s .= " | Others/Discount Amount Changed from ". $others_charges_amount." - to - " .$_POST['others_charges_amount'.$i]." in Rowno ".$val;
								}
								if($id_mst_charges_discounts != $_POST['id_mst_charges_discounts'.$i]){ 
									$old_data = selectColumn('mst_charges','name'," WHERE `id` = '".$id_mst_charges_discounts."'  AND charges_account = '6'");
									$new_data = selectColumn('mst_charges','name'," WHERE `id` = '".$_POST['id_mst_charges_discounts'.$i]."'  AND charges_account = '6' ");
									//$others_charge_discount_s .= " | Others/Discount charges/Discount Changed from ". $old_data." - to - " .$new_data." in Rowno ".$val."and Bill no ".$bill_no;
								}
							}


								$editSql = "   	UPDATE `".TBL_INV_OTHERS_CHARGES."`  SET  

								`id_inv_po` = '".addslashes(encryptor(decrypt,$_POST[eId]))."', 
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
									$others_charge_discount_s .= $new_data. " Details Added in Other Charges ";
								}
								
								/*if($_POST['others_charges_percent'.$i]){ 
									$others_charge_percent_s .= " | Percentage Insert to ".$_POST['others_charges_percent'.$i];
								}
								if($_POST['others_charges_amount'.$i]){ 
									$others_charge_amount_s .= " | Amount Insert to ". $_POST['others_charges_amount'.$i];
								}
								if($_POST['id_mst_charges_discounts'.$i]){ 
									$old_data = selectColumn('mst_charges','name'," WHERE `id` = '".$id_mst_charges_discounts."'  AND charges_account = '6'");
									$new_data = selectColumn('mst_charges','name'," WHERE `id` = '".$_POST['id_mst_charges_discounts'.$i]."'  AND charges_account = '6' ");
									$others_charge_discount_s .= " | Discount Insert to ".$new_data;
								}  */

									$addSql = "INSERT INTO `".TBL_INV_OTHERS_CHARGES."` SET

									`id_inv_po` = '".addslashes(encryptor(decrypt,$_POST[eId]))."',  
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


						//Update Terms And Conditinos Details
							$auditquery = "SELECT * From `".TBL_INV_TERMS_AND_CONDITIONS."` WHERE id = '".addslashes($_POST['termsupdate_id'])."'  ";

							  $auditresSQL = mysqli_query($connNew, $auditquery);	
								while($auditrow = mysqli_fetch_object($auditresSQL)){ 
								 
								  $terms = $auditrow->terms; 
							  $bill_no = selectColumn('inv_po','mdoc_no'," WHERE `id` = '".$auditrow ->id_inv_po."' ");
								 //Change Data
									if($terms != $_POST['terms']){ 
										 $terms_s = "Terms & Condition Changed from ". $terms." - to - " . $_POST['terms']." in Row no 1 ";
									}
								}

							$editSql = "   	UPDATE `".TBL_INV_TERMS_AND_CONDITIONS."`  SET  

							`id_inv_po` = '".addslashes(encryptor(decrypt,$_POST[eId]))."',  
							`terms` = '".addslashes($_POST['terms'])."',    
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$editSql .= "	
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'
							WHERE `id` = '".addslashes($_POST['termsupdate_id'])."'";
						executeSql($editSql);


						//Update Terms And Conditions For Loops Section

							if($_POST['termsupdate_count'] == ''){
								$termsupdate_count = 0;								
							}else{
								$termsupdate_count = $_POST['termsupdate_count'];									
							}

							for($i = 1; $i <= $termsupdate_count; $i++){
								$val  = $i;
								$val = $val + 1;
								$auditquery = "SELECT * From `".TBL_INV_TERMS_AND_CONDITIONS."` WHERE id = '".addslashes($_POST['termsupdate_id'.''.$i])."'  ";

							  $auditresSQL = mysqli_query($connNew, $auditquery);	
								while($auditrow = mysqli_fetch_object($auditresSQL)){ 
								 
								  $terms = $auditrow->terms;
							  $bill_no = selectColumn('inv_po','mdoc_no'," WHERE `id` = '".$auditrow ->id_inv_po."' ");  


								 //Change Data
							    	if($terms != $_POST['terms'.''.$i]){ 
									 	$terms_s .= " | Terms and Condition Changed from ". $terms." - to - " . $_POST['terms'.''.$i] ." in Rowno ". $val;
									}

								}

								$editSql = "   	UPDATE `".TBL_INV_TERMS_AND_CONDITIONS."`  SET  

								`id_inv_po` = '".addslashes(encryptor(decrypt,$_POST[eId]))."', 
								`terms` = '".addslashes($_POST['terms'.''.$i])."',    
								`id_shop` = '".addslashes($_SESSION['shop'])."'";

								$editSql .= "	,`date_created` = '".currenDateTime()."'
								,`last_modified` = '".currenDateTime()."'
								,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
								,`id_mst_user_created_by` = '".$_SESSION['userId']."'
								,`status` = '".addslashes($_POST['status'])."'
								WHERE `id` = '".addslashes($_POST['termsupdate_id'.''.$i])."'";
							executeSql($editSql);

							}

							
if($_POST['terms']!='' && $_POST['counter3'] <0){
						//Update  Terms and Conditions Field More Fields Add Here
							$addSql = "   	INSERT INTO `".TBL_INV_TERMS_AND_CONDITIONS."` SET

							`id_inv_po` =  '".addslashes(encryptor(decrypt,$_POST[eId]))."', 
							`terms` = '".addslashes($_POST['terms'])."',   
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$addSql .= "	,`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`status` = '".addslashes($_POST['status'])."'";
							executeSql($addSql);
		}
							if($_POST['counter3'] == ''){
								$counter3 = 0;								
							}else{
								$counter3 = $_POST['counter3'];									
							}

							for($i = $counter3; $i > $termsupdate_count; $i--){

								 
								if($_POST['terms'.''.$i] != ''){
 
									if($_POST['terms'.''.$i]){ 
									 	$terms_s .=  " | Terms and Condition Added to ". $_POST['terms'.''.$i] ;
									} 
							if($_POST['terms'.''.$i]!=''){
									$addSql = "INSERT INTO `".TBL_INV_TERMS_AND_CONDITIONS."` SET

									`id_inv_po` = '".addslashes(encryptor(decrypt,$_POST['eId']))."', 
									`terms` = '".addslashes($_POST['terms'.''.$i])."',  
									`id_shop` = '".addslashes($_SESSION['shop'])."'";

									$addSql .= "	,`date_created` = '".currenDateTime()."',
									`last_modified` = '".currenDateTime()."',
									`id_mst_user_modified_by` = '".$_SESSION['userId']."',
									`id_mst_user_created_by` = '".$_SESSION['userId']."',
									`status` = '".addslashes($_POST['status'])."'";
								executeSql($addSql);
							}
								}
							}

							$auditeditSql = " INSERT audit_trail SET 
			                `voucher_id` = '".addslashes(encryptor(decrypt,$_REQUEST[eId]))."',
							`tables_name` = 'inv_po , inv_po_details',
							`form_code` = 'Purchase Order',
							`changes` =  '".addslashes($bill_s).",".addslashes($exchange_s).",".addslashes($ch_5).",".addslashes($discount_s).",".addslashes($terms_s).",".addslashes($indent_qty_s).",".addslashes($indent_main_unit_s).",".addslashes($indent_transaction_unit_s).",".addslashes($indent_rate_per_main_unit_s).",".addslashes($indent_per_unit_s).",".addslashes($indent_discount_percent_s).",".addslashes($indent_item_remarks_s).",".addslashes($indent_charge_purchase_s).",".addslashes($others_type_s).",".addslashes($others_charge_percent_s).",".addslashes($others_charge_amount_s).",".addslashes($others_charge_discount_s)."',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 2 ";


//if($bill_s=='' && $exchange_s=='' && $discount_s==''&& $terms_s==''&& $ch_5==''&& $indent_qty_s=='' && $indent_main_unit_s=='' && $indent_transaction_unit_s=='' && $indent_rate_per_main_unit_s=='' && $indent_per_unit_s=='' && $indent_discount_percent_s=='' && $indent_item_remarks_s=='' && $indent_charge_purchase_s=='' && $others_type_s=='' && $others_charge_percent_s=='' && $others_charge_amount_s=='' && $others_charge_discount_s==''  ){
						
//}else{
		executeSql($auditeditSql);
//}
							
			if(1){  

				$_SESSION['successMsg'] = selectColumn(TBL_INV_PO, 'doc_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has been updated sucessfully.';  

				
				//if($_POST['another']!=''){
					//header("location:editPO.php?submenu=".$_GET['submenu']."&session=".$_GET['session']."&print=1");	
				//}else{
						header("location:printPO.php?eId=".$_GET['eId']."&submenu=".$_GET['submenu']."&session=".$_GET['session']."&action=edit&page=".$_REQUEST['page']."&print=1"); 
				//}
				exit;
			
			
			
			}else{
				$err++;
				$_SESSION['errorMsg'] = selectColumn(TBL_INV_PO,'doc_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND 'id' = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = ' PO has not been saved. Please make corrections.';
	}
}
// ----------cate---------

$name = $_POST['id_mst_charges_purchase_local'];


if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	//Indent Table

	$sql = "  SELECT * FROM `".TBL_INV_PO."`
		WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
	 $db->query($sql);
	
	if($db->num_rows() > 0){
		$row = $db->fetch_object(); 
		
	}  
		  			 
}else{	
?>
<script>
window.onload = function() {
	hideandshow();
}
</script>
<?php } ?>
  <script>
<?php  
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){?>

window.onload = function() {
	
onLoadIdCompany(<?php echo $row->id_mst_party_supplier; ?>);
comShow(<?php echo $row->id_mst_party_supplier; ?>);
comShow2(<?php echo $row->id_mst_party_billtobe; ?>);
var ledger_id =  document.getElementById("ledger_id").value;  

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
//supplier();
						};
							
<?php } ?>	
</script>
<?php   

	if($_GET['eId'] == ''){
		$id_indent_id =  encryptor(decrypt,$_GET['id_indent_id']);
	}else{
 
		$id_indent_id = encryptor(decrypt,$_GET['id_indent_id']);
		encryptor(decrypt, $_REQUEST['eId']); 
 
	} 
?>


<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
	
	
   <?php  $session=$_GET['submenu']; ?>
    <section class="content-header">
      <!--<h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
		<?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>-->
        <h5 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation_id($session)['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo $row->mdoc_no ?> </span></h5>
      <?php echo breadCrumbs(); ?>
    </section>
	
	
    <!-- Main content -->
    <section class="content">
	
		 	<hr class="br-line">
			
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
         
           
			 <div class="nav-tabs-custom mb-0 shadow-none">

		 
			<!--<div class="box-header with-border">
               <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation_id($session)['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo $row->mdoc_no ?> </span></h3>
            </div>-->
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="indent_form"  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off" id="indent_form">
                <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" id="eId" />
		<input type="hidden" value="<?php echo encryptor(decrypt,$_REQUEST['eId']);?>" name="indent_po" id="indent_po">	


                <input type="hidden" value="<?php echo $_GET['submenu'];?>" name="submenu" id="submenu" />
				<input type="hidden" value="<?php echo $_GET['session'];?>" name="session" id="session" />		
				
					<div class="form-group has-error" align="center">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div> 

				

              <div class="box-body">

              	<div class="card text-dark bg-light">
              	

	              	<div class="row">	

	              		<div class="form-group  col-xs-6 col-md-2 col-sm-6 form-p date-wd"  >
	              			<label for="name">Document Type</label>
	              			
		              			<select class="form-control select2" id="doc_type" name="doc_type" onchange="hideandshow()" style="width:100%">	                	  		                  	  
			                  	 	<option selected="selected" value="3">Purchase Order</option>  
			                  	</select>	 
	              			<?php 
	              				$sql2 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$row->id_doc_type_configuration."' ";

								$db->query($sql2);   
									while($row2 = $db->fetch_object()){ 
										$prefix= $row2->prefix; 
										$suffix = $row2->suffix; 

									} 
	              			?>
	              			<?php if($row->id !=''){
	              				$readonly = 'disabled';
	              			}else{
	              				$readonly = '';
	              			}
	              			?>
 
	              		</div>  
	              		<div class="form-group col-xs-6 col-sm-6 date-wd form-p" >
	              			<label for="name">Date <font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-calendar"></i> 
						   	</div>
		               <!--   <input data-parsley-required type="text" class="form-control pickerdate" placeholder="Enter PO Date" id="doc_date" name="doc_date" value="<?php if($_POST) echo $_POST['doc_date'];else echo stripslashes($row->doc_date);?>" onchange="hideandshow()" <?php echo $readonly; ?>> -->
						  
						     <input data-parsley-required type="text" class="form-control pickerdate" placeholder="Enter PO Date" id="doc_date" name="doc_date" value="<?php if($_POST) echo $_POST['doc_date'];elseif($row->doc_date!='') echo date('d-m-Y',strtotime($row->doc_date));else echo date('d-m-Y');?>" onchange="hideandshow()" onclick="hideandshow()" <?php //echo $readonly; ?>>

		                   <input style="display: none;"  type="text" class="form-control pickerdate" placeholder="Enter PO Date" id="doc_date1" name="doc_date1" value="<?php if($_POST) echo $_POST['doc_date'];else echo stripslashes($row->doc_date);?>" >
		                  </div> 
	              		</div>


		                <div class="form-group col-xs-12 col-md-1 col-sm-6 p-0  form-p">
		                  <?php if($row->id ==''){?>
	              			<style type="text/css">
	              				 #ind{
	              				 	display: none;
	              				 }
	              			</style>
	              			<?php } ?>
	              			<div class=" col-xs-12 col-md-12 col-sm-12 form-p">
		              				<label for="name">PO No</label>
		              			
			              		
		              				<input type="text" class="form-control" placeholder="PO No" id="mdoc_no2" name="mdoc_no2" value="<?php if($_POST) echo $_POST['mdoc_no2'];else echo stripslashes($row->mdoc_no);?>" readonly>
		              				 
			                	</div>
	              			<div id="ind" name="ind" style="display:none;">

	              				<div class=" col-xs-6 col-md-4 col-sm-6 tab-mb" style="display:none;">
	              					<label for="name">Prefix</label>
	              					<div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-caret-square-o-left"></i> 
								   	</div>
		              				<input type="text" class="form-control" placeholder="Prefix" id="prefix" name="prefix" value="<?php if($_POST) echo $_POST['prefix'];else echo stripslashes($prefix);?>" readonly> 
		              				</div>
			                	</div>
		              			<div class="  col-xs-6 col-md-4 col-sm-6" style="display:none;">
		              				<label for="name">PO No</label>
		              				<div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-list-ol"></i> 
								   	</div>
		              				<input type="text" class="form-control" placeholder="PO No" id="doc_no" name="doc_no" value="<?php if($_POST) echo $_POST['doc_no'];else echo stripslashes($row->doc_no);?>" readonly>
		              				</div> 
			                	</div>
			                	<div class=" col-xs-12 col-md-4 col-sm-12" style="display:none;">
			                		<label for="name">Suffix</label>
			                		<div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-caret-square-o-right"></i> 
								   	</div>
		              				<input type="text" class="form-control" placeholder="Suffix" id="suffix" name="suffix" value="<?php if($_POST) echo $_POST['suffix'];else echo stripslashes($suffix);?>" readonly> 
		              				</div>
			                	</div>
			                </div>
			                <?php if($row->id ==''  || $prefix != ''){ 
			                	$mdocRequired='data-parsley-required';	
			                	?>
			                  <style type="text/css">
			                  	#hideandshow{
			                  		display: none;
			                  	}
			                  </style>
		              	  	<?php }else{$mdocRequired='';} ?>
		                  	<div id="hideandshow" name="hideandshow">
				                <div class="form-group col-xs-12 col-md-12 col-sm-6 form-p">
				                  <label for="name">Manual PO No</label>
				                  <div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-list-ol"></i> 
								   	</div>
				                  <input  type="text" class="form-control" placeholder="Enter Manual PO No" id="mdoc_no" name="mdoc_no" value="<?php if($_POST) echo $_POST['mdoc_no'];else echo stripslashes($row->mdoc_no); ?>">
				                  </div> 
				                </div> 			                 
				            </div> 
		                </div> 			                	                
						
		       
             

	              		<div class="form-group col-xs-12 col-md-4 col-sm-12 form-p" >
	              			<label for="name">Supplier <font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fas fa-truck-loading"></i> 
						   	</div>
	              			<select class="form-control select2 id_mst_party_supplier" name="id_mst_party_supplier" id="id_mst_party_supplier" onchange="supplier();comShow(this.value);//partybilltobe(this.value);//comShow2(this.value);" data-parsley-required  data-parsley-errors-container="#outletError"  <?php echo $readonly; ?> style="width:100%">
								<?php /*?><?php $categoryDropDown = '	<option value="">Select Supplier</option>';
								  $resCat = selectSql(TBL_PARTY,"where id_shop='".$_SESSION['shop']."' and status = '1'",' ORDER BY `company_name`');
								  if($db->num_rows2($resCat)){
								  	while($resultCat = $db->fetch_object2($resCat)){
										if($_REQUEST['id_mst_party_supplier'] == $resultCat->id){
											$selected = 'selected="selected"';
										}elseif($row->id_mst_party_supplier == $resultCat->id){
											$selected = 'selected="selected"';
											$ledger = $resultCat->ledger;
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option'.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->company_name).' - '.ucfirst($resultCat->city).'</option>';
									}
								  }
								 	echo $categoryDropDown .= ' ';
								  ?><?php */?>
                                  </select>
						<?php echo $err_deparment;?>
								</div><span id="outletError"></span>
								<?php //if($ledger == 1){ ?>

		                  	 	<input type="text" class="form-control "  id="id_mst_party_supplier1" name="id_mst_party_supplier1" value="<?php if($_POST) echo $_POST['id_mst_party_supplier'];else echo stripslashes($row->id_mst_party_supplier);?>" style="display: none;" >
		                  	 	    <div><span id="comData" style="color: red"></span></div>
	                  </div>

	                

	              
						
		                <div class="form-group  col-xs-12 col-md-4 col-sm-6 form-p">
		                  <label for="name">Bill To Be  <font color="#FF0000">*</font></label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="far fa-money-bill-alt"></i> 
						   	</div>


						   	  <?php                   		 
			                  $sql1 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and `doc_type`=3 order by effective_date  DESC LIMIT 1 ";
			                // echo $sql1;
			                  //die();
			                   $db->query($sql1); 
			               //   $numrows = $mysqli_result->num_rows;
			                 // echo $numrows;
			                   $noOfRows= $db->num_rows();
			                   //echo 'NO OF ROWS : '.$noOfRows;
			                  // if($noOfRows>0){
                                   $rows2 =  $db->fetch_object();

                                  $idconfigParty =	$rows2->id_mst_party_billtobe;
                                  if($idconfigParty>0){

                                //    echo 'Party name : '.$idconfigParty;
                                  //  die();

                                    ?>
                                

		                             <select  onchange="billtobe();comShow2(this.value);"  class="form-control select2" name="id_mst_party_billtobe" id="id_mst_party_billtobe" style="width:100%">
												<?php $categoryDropDown = '	<option value="0">Select Bill To Be</option>';
												  $resCat = selectSql(TBL_PARTY,"where id_shop='".$_SESSION['shop']."'  AND id='".$idconfigParty."' AND  status = '1'",' ORDER BY `company_name`');

												//  echo $resCat;
												  if($db->num_rows2($resCat)){
												  	while($resultCat = $db->fetch_object2($resCat)){
															if($_REQUEST['id_mst_party_billtobe'] = $idconfigParty){
															$selected = 'selected="selected"';
														}elseif($row->id_mst_party_billtobe == $resultCat->id){
															$selected = 'selected="selected"';
														}else{
															$selected = '';
														}
														$categoryDropDown .= '<option '.$selected.' value="'.$idconfigParty.'">'.ucfirst($resultCat->company_name).' - '.ucfirst($resultCat->city).'</option>';
													}
												  }
												 	echo $categoryDropDown .= '</select>';

                                     //end of while
		                            
		                           

			                   }  else{

	                           	
                          	?>

			                  <select onchange="billtobe();comShow2(this.value);" class="form-control select2" name="id_mst_party_billtobe" id="id_mst_party_billtobe" data-parsley-required data-parsley-errors-container="#outletError2"  style="width:100%">
									<?php $categoryDropDown = '	<option value="">Select Bill To Be</option>';
									  $resCat = selectSql(TBL_PARTY,"where id_shop='".$_SESSION['shop']."' and status = '1'",' ORDER BY `company_name`');
									  if($db->num_rows2($resCat)){
									  	while($resultCat = $db->fetch_object2($resCat)){
											if($_REQUEST['id_mst_party_billtobe'] == $resultCat->id){
												$selected = 'selected="selected"';
											}elseif($row->id_mst_party_billtobe == $resultCat->id){
												$selected = 'selected="selected"';
											}else{
												$selected = '';
											}
											$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->company_name).' - '.ucfirst($resultCat->city).'</option>';
										}
									  }
									 	echo $categoryDropDown .= '</select>';
								}

						  ?>
							<?php echo $err_deparment;?> 
		              		</div><span id="outletError2"></span>
		              		  <div><span id="comData2" style="color: red"></span></div>
		                </div>
		            
		                     </div>
		                  
		              <div class="row">
		                  <div class=" b-box" id="b-box">
		                 
		                <?php if($row->id !='' && $row->base_currency_code != $row->transaction_currency_code  ){?>
		                	<style type="text/css">
		                		#xchange_rate{
		                			display: block;
		                		}
		                		#base_currency{
		                			display: block;
		                		}
		                		#trans_currency{
		                			display: block;
		                		}
		                		#credit_day{
		                			display: flex;
		                		}
		                	</style>

		                <?php  } else{ ?>
		                	<style type="text/css">
		                		#xchange_rate{
		                			display: none;
		                		}
		                		#base_currency{
		                			display: none;
		                		}
		                		#trans_currency{
		                			display: none;
		                		}
		                		#credit_day{
		                			display: none;
		                		}
		                	</style>
		                <?php } ?>

		            <div  id="credit_day" name="credit_day">
		                  <div class="in-box d-flex">
		                  <label for="name" class="col-form-label">Credit Days :</label>
		                
		                  <input type="text" class="form-control bg-none br-none"  id="credit_days" name="credit_days" value="<?php if($_POST) echo $_POST['credit_days'];else echo stripslashes($row->credit_days); ?>" readonly>
		                  </div> 
		                </div> 

		               <div  id="base_currency" name="base_currency" >
		                   <div class="in-box d-flex">
		                    <label for="name" class="col-form-label">Base Currency :</label>
		           
	              		
						   	<?php $base_currency  =  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($_SESSION['base_currency_code'])."'"); ?>
		                  <input type="text" class="form-control br-none bg-none" id="base_currency_code" name="base_currency_code" value="<?php echo stripslashes($base_currency); ?>" readonly>
		                  <input type="text" class="form-control b-none bg-none" placeholder="Base Currency Code" id="base_currency_code1" name="base_currency_code1" value="<?php echo $_SESSION['base_currency_code']; ?>"  style="display: none;">
		                  </div>
		                </div>
		                <?php if($row->id !=''){ 

		                	$transaction_currency_code  =  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row->transaction_currency_code)."'");
		                }else{
		                	$transaction_currency_code  = '';
		                }?>

		                <div  id="xchange_rate" name="xchange_rate">
		                <div class="in-box d-flex">
		                  <label for="name" class="col-form-label">Exchange Rate :</label>
		                
	              		
						   	<input type="text" class="form-control  br-none bg-none" placeholder="Exchange Rate" id="exchange_rate" name="exchange_rate" value="<?php if($_POST) echo $_POST['exchange_rate'];else echo stripslashes($row->exchange_rate); ?>" > 
		                </div>
		                </div>

		                <div class="" id="trans_currency" name="trans_currency">
		               <div class="in-box d-flex">
		                  <label for="name" class=" col-form-label">Transaction Currency:</label>
		              
		               
		                  <input type="text" class="form-control br-none bg-none" placeholder="Transaction Currency" id="transaction_currency_code" name="transaction_currency_code" value="<?php if($_POST) echo $_POST['transaction_currency_code'];else echo stripslashes($transaction_currency_code); ?>" readonly>

		                  <input type="text" class="form-control" placeholder="Transaction Currency" id="transaction_currency_code1" name="transaction_currency_code1" value="<?php if($_POST) echo $_POST['transaction_currency_code'];else echo stripslashes($row->transaction_currency_code); ?>" style="display: none;">
		                
		                </div></div>
		      </div>


		            
		              </div> <!--end of row-->


		             
		                                 
 						<div class="form-group col-xs-12 col-md-6 col-sm-2" style="display: none;">
		                  <label for="name">Id Doc Type</label>
		                  <input type="text" class="form-control" placeholder="Enter Id Doc Type" id="id_doc_type_configuration" name="id_doc_type_configuration" value="<?php if($_POST) echo $_POST['id_doc_type_configuration'];else echo stripslashes($row->id_doc_type_configuration); ?>"> 
		                </div>			                	                
						
		          

		            <div class="row">
	              	</div> 

		        </div>
		     
		         <div class="box-body  ">

              	<div class="card text-dark bg-light">
              		
	              	<div class="row">
	              		<?php 
              				$sql2 = " SELECT * FROM `".TBL_INV_PO."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".'3'."' ";
							$db->query($sql2);  
							$numRows= $db->num_rows();

								if($numRows != 0){
									while($row2 = $db->fetch_object()){ 
										$id_po_id= $row2->id; 
										$id_po_id = $id_po_id + 1; 
									}
								}
								else{
									 $id_po_id = '1'; 
								}
              			?>	
	              		<div class="form-group col-xs-12 col-md-6 col-sm-2" style="display: none;" >
		                  <label for="name"></label>
		                  <input  type="text" class="form-control" id="id_inv_po" name="id_inv_po"  value="<?php echo $id_po_id; ?>" > 
		                </div>
		            </div>
		            
					<!-- The Modal -->
					<div class="modal" id="config_model">
					    <div class="modal-dialog modal-wd95">
					      <div class="modal-content" >
					      <!--<button type="button" class="close" data-dismiss="modal"  onclick="dismiss()">&times;</button>-->

					      
					        <!-- Modal body -->
					        <div class="modal-body ox-sl">
					        	<input type="text" id="myInput"  class=" p-5 br-grey search-wid"  onkeyup="myFunction()" placeholder="Search For Item Description" title="Type In Item Description">
					        	 <input type="checkbox" name="checkbox" id="checkbox"  onclick="popupshow_checkbox(this.id);" ><span> Show All</span>
					        
					        	 <div id="myTables">
					        
					            </div>
					        </div>
					        
					        <!-- Modal footer -->
					        <div class="modal-footer">
					        	<button type="button" class="btn o-btn ok"  data-dismiss="modal" onclick="po();"><i class="fa fa-plus-circle" aria-hidden="true"> </i> Insert</button>
					          <button type="button" class="btn c-btn" data-dismiss="modal"  onclick="dismiss()"><i class="far fa-window-close" aria-hidden="true"></i> Close</button>
					        </div> 
		              		</div>
		                </div> 
		            </div>
		            <button type="button" id="config_button" name="config_button" class="btn btn-info" data-toggle="modal" data-target="#config_model"  style="display: none"><i class="fa fa-check-square-o"> PO Help</i>
    				</button>
		            <div class="row">
		            	 <hr class="br-line">
	              		<div class=" text-center ">
	              			<h6 class="tb-heads">Purchase Order Details</h6>
	              		</div>  
		            <div class="">
		            	<table id="myTable1" class="table table-striped table-responsive table-bordered dataTable no-footer order-list1 max-h">
				             <thead>
				                <tr class="th-bg">
				                    <th >Indent No</th> 
				                    <th >Item Code</th>
				                    <th >Item Description</th> 
				                    <th  >Qty</th> 
				                    <th >Unit</th> 
				                    <th>Rate</th>  
				                    <th>Per</th>  
				                    <th >%Dis</th> 
				                    <th >Amount</th> 
				                
				                     <th>Purchase Accounts</th>  
				                       <th>Tax</th>  
				                           <th>Remarks</th>    
				                </tr>
				               
				      
				            </thead >
				            	  <tbody >
				            	
				            	<?php
				            	$k='';
				            	if($row->id ==''){
								 	$i=1;
								 }else{
								 	$i=0;
								 } 
				            	//Indent Details Here First Row Only Select
				            	 $sql2 = "SELECT * FROM  `".TBL_INV_PO_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_po` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";

								 $db->query($sql2); 

								while($rowsID = $db->fetch_object()){
							 		 $array['id'.''.$i] = $rowsID->id;
							 		 $array['id_inv_po'.''.$i] = $rowsID->id_inv_po;
							 		 $array['id_inv_indent'.''.$i] = $rowsID->id_inv_indent; 
							 		 $array['id_inv_indent_details'.''.$i] = $rowsID->id_inv_indent_details;
							 		 $array['id_inv_items'.''.$i] = $rowsID->id_inv_items; 
							 		 $array['transaction_unit'.''.$i] = $rowsID->transaction_unit; 
							 		 $array['qty'.''.$i] = $rowsID->qty; 
							 		 $array['conver_rate_per_unit'.''.$i] = $rowsID->conver_rate_per_unit;
							 		 $array['id_mst_charges_purchase_interstate'.''.$i] = $rowsID->id_mst_charges_purchase_interstate;
							 		 $array['id_mst_charges_purchase_local'.''.$i] = $rowsID->id_mst_charges_purchase_local;
							 		 $array['rate_per_main_unit'.''.$i] = $rowsID->rate_per_main_unit;
							 		 $array['discount_percent'.''.$i] = $rowsID->discount_percent;
							 		 $array['discount_amount'.''.$i] = $rowsID->discount_amount;
							 		 $array['item_amount_before_discount'.''.$i] = $rowsID->item_amount_before_discount; 
							 		 $array['item_amount'.''.$i] = $rowsID->item_amount; 
							 		 $array['id_mst_charges_sgst'.''.$i] = $rowsID->id_mst_charges_sgst;
							 		 $array['item_sgst_percent'.''.$i] = $rowsID->item_sgst_percent;
							 		 $array['item_sgst_amount'.''.$i] = $rowsID->item_sgst_amount;
							 		 $array['id_mst_charges_cgst'.''.$i] = $rowsID->id_mst_charges_cgst;
							 		 $array['item_cgst_percent'.''.$i] = $rowsID->item_cgst_percent;
							 		 $array['item_cgst_amount'.''.$i] = $rowsID->item_cgst_amount;
							 		 $array['id_mst_charges_igst'.''.$i] = $rowsID->id_mst_charges_igst;
							 		 $array['item_igst_percent'.''.$i] = $rowsID->item_igst_percent;
							 		 $array['item_igst_amount'.''.$i] = $rowsID->item_igst_amount;
							 		 $array['item_remarks'.''.$i] = $rowsID->item_remarks;
							 		 $array['main_unit'.''.$i] = $rowsID->main_unit;
							 		 $array['alt_unit'.''.$i] = $rowsID->alt_unit;
							 		 $array['per_unit'.''.$i] = $rowsID->per_unit; 
							 		 $array['alt_qty'.''.$i] = $rowsID->alt_qty; 
							 		 $array['rate_per_alt_unit'.''.$i] = $rowsID->rate_per_alt_unit; 
									 
							$array['id_indentt'.''.$i] = selectColumn('inv_indent_details','id_inv_indent','where id="'.$array['id_inv_indent_details'.''.$i].'" ');
							$array['final'.''.$i] = selectColumn('inv_indent','doc_no','where id="'.$array['id_indentt'.''.$i].'" ');
							 		 
							 		 $i++;
								}  
								for($j=0; $j<$i; $j++){ 
								 if($j == 0){
								 	$k='';
								 }else{
								 	$k = $j;
								 } 
				            	?>
				            	<div class="form-group col-xs-12 col-md-6 col-sm-2" style="display: none;"  >
				                  <label for="name">Update Id</label>
				                  <input type="text" class="form-control" id="update_id<?php echo $k;?>" name="update_id<?php echo $k;?>" value="<?php echo $array['id'.''.$j];?>"> 

				                  <label for="name">Update Count</label>
				                  <input type="text" class="form-control" id="update_count" name="update_count" value="<?php echo $k;?>"> 
				                </div>
				                <?php 
								
								$edit_ledger_id = selectColumn(TBL_PARTY,'ledger'," WHERE `id` = '".$row->id_mst_party_supplier."'");
								if($row->id == ''){ $ledger_id = ''; ?>
					                <style type="text/css">
					                	#locals{
					                		display: none;
					                	}
					                	#interstates{
					                		display: none;
					                	}
					                	#localss{
					                		display: none;
					                	}
					                	#interstatess{
					                		display: none;
					                	}
					                </style>
					                <?php } elseif($edit_ledger_id == 1) {
					                 $ledger_id = 1; ?>
					                	<style type="text/css">
					                	#locals<?php echo $k;?>{
					                		display: block;
					                	}
					                	#interstates<?php echo $k;?>{
					                		display: none;
					                	}
					                	#localss<?php echo $k;?>{
					                		display: block;
					                	}
					                	#interstatess<?php echo $k;?>{
					                		display: none;
					                	}
					                	</style>
					                <?php } elseif($edit_ledger_id == 2) {$ledger_id = 2; ?>
					                	<style type="text/css"> 
					                	#locals<?php echo $k;?>{
					                		display: none;
					                	}
					                	#interstates<?php echo $k;?>{
					                		display: block;
					                	}
					                	#localss<?php echo $k;?>{
					                		display: none;
					                	}
					                	#interstatess<?php echo $k;?>{
					                		display: block;
					                	}
					                	tbody{
					                		border:1px solid red;
					                	}
					                	</style>
					                <?php } ?>
					                <input id="ledger_id" name="ledger_id" value="<?php if($_POST) echo $ledger_id;else echo stripslashes($ledger_id); ?>" type="hidden">
					              
				                <tr id="edittrdelete<?php echo $k;?>">
					                <input hidden id="select<?php echo $k;?>" name="select<?php echo $k;?>">
					                <td style="width:10%"> 
					                 <select data-parsley-required data-parsley-errors-container="#outletError3" class="form-control select2" name="id_inv_indent<?php echo $k;?>" id="id_inv_indent<?php echo $k;?>" onchange="popupshow(this.id);" style="width:100%">
										<?php $categoryDropDown = '<option value=""> Select Indent No </option>'.'<option value="na">NA</option>';

											if($_REQUEST['eId']==''){
						                   		$condChk = 'AND inv_indent_details.bal_qty > 0';
						                   	}
						                   	else{
						                   		$condChk = '';
						                   	}	

											$sql = "SELECT inv_indent.doc_date, inv_indent.mdoc_no,  inv_indent.doc_no, 
						                   	inv_indent_details.qty,inv_indent_details.alt_qty, inv_indent_details.id, inv_indent_details.id_inv_indent, inv_indent_details.main_unit, inv_indent_details.alt_unit, 
						                   	inv_items.item_code, inv_items.name, 
						                   	mst_attributes.field_value 
						                   	FROM inv_items, mst_attributes, inv_indent_details, inv_indent WHERE mst_attributes.id=inv_indent.id_mst_attributes_department and inv_indent.id = inv_indent_details.id_inv_indent and inv_indent_details.id_inv_items = inv_items.id ".$condChk."  and inv_indent.id_shop = '".addslashes($_SESSION['shop'])."' and  inv_indent.doc_type = '2'   group by inv_indent.doc_no,inv_indent.id_doc_type_configuration ";	

						                   	/*if($_REQUEST['eId']==''){
						                   		$condChk = 'AND B.bal_qty!=0';
						                   	}
						                   	else{
						                   		$condChk = '';
						                   	}	

						                   	$sql="SELECT DISTINCT B.id_inv_indent,A.doc_no,A.doc_date FROM ".TBL_INV_INDENT." A CROSS JOIN ".TBL_INV_INDENT_DETAILS." B ON A.id=B.id_inv_indent WHERE  A.id_shop='".$_SESSION['shop']."' ".$condChk."  AND B.doc_type=2 ";*/	

												$db->query($sql); 
							                    while($row1 = $db->fetch_object()){	
												
											if($row->id !=''){
												if($_REQUEST['id_inv_indent'] == $row1->id){
													$selected = 'selected="selected"';
												}elseif($array['final'.''.$j] == $row1->doc_no){
													$selected = 'selected="selected"';													
												}else{
													$selected = '';
												}
											}else{
												if($_REQUEST['id_inv_indent'] == $row1->id){
													$selected = 'selected="selected"';
												}elseif($array['id_inv_indent'.''.$j] == $row1->doc_no){
													$selected = 'selected="selected"';													
												}else{
													$selected = '';
												}
											}
												$categoryDropDown .= '<option '.$selected.' value="'.$row1->id.'-'.$row1->id_inv_indent.'">'.ucfirst($row1->doc_no.' | '.date('d-m-Y' , strtotime(addslashes($row1->doc_date)))).'</option>';
												}
												if($row->id !=''){
													if($array['final'.''.$j] == '')	 {
														$categoryDropDown .= '<option selected="selected" value="na">NA</option>';
													}
												}
											 	echo $categoryDropDown .= '</select><span id="outletError3"></span>';  
										?> 
					                </td>   
					               <input type="text"  autocomplete="off" name="id_inv_indent_details<?php echo $k;?>" id="id_inv_indent_details<?php echo $k;?>" placeholder="ID"  class="form-control"  value="<?php if($_POST) echo $_POST['id_inv_indent_details'];else echo stripslashes($array['id_inv_indent_details'.''.$j]); ?>" readonly  style="display:none;" />
									
					              
				                	<td style="width:7%"> 
				                		<input type="text"  autocomplete="off" name="id_inv_items<?php echo $k;?>" id="id_inv_items<?php echo $k;?>" placeholder="Item ID"  class="form-control"  value="<?php if($_POST) echo $_POST['id_inv_items'];else echo stripslashes($array['id_inv_items'.''.$j]); ?>" style="display:none;" /> 

				                		<?php 
				                		//Name Get
				                			$item_code  =  selectColumn(TBL_INV_ITEMS,'item_code'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND status=1 AND `id` = '".addslashes($array['id_inv_items'.''.$j])."'");
				                			//Item Description Get
				                			$item_description  =  selectColumn(TBL_INV_ITEMS,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($array['id_inv_items'.''.$j])."'");
											
				                			//$item_description1  =  selectColumn(TBL_INV_ITEMS,'id_mst_charges_purchase_local'," WHERE `id` = '".addslashes($array['id_inv_items'.''.$j])."'");
				                		?>
				                	<div id="hideshow_item_code">
					                 	<input type="text"  autocomplete="off" name="item_code<?php echo $k;?>" id="item_code<?php echo $k;?>" placeholder="Item Code"  class="form-control"  value="<?php echo $item_code; ?>" readonly />
					                </div>
					                <div id="hideshow_item_codes" style="display: none;">
					                	<select class="form-control select2" name="id_inv_items_po<?php echo $k;?>" id="id_inv_items_po<?php echo $k;?>" onchange="itemget(this.id)" style="width:100%">
										<?php $categoryDropDown = '<option value="">Select Item Code</option>';
										
										

											$sqlResult1 = "SELECT * FROM ".TBL_ATTRIBUTES." WHERE table_name = 'items_type' AND field_category IN ('Ingredients Items','Both','Other Items') AND id_shop = ".$_SESSION['shop'] ." ";
												$QuerySQL1	=	mysqli_query($connNew,$sqlResult1);
												
													while($sqlRow = mysqli_fetch_object($QuerySQL1)){
												        $list = $sqlRow->id;
														$string .= $list.',';
													}	
											$item_list = rtrim($string,',');										
											 						 
							                   	$sql = "SELECT inv_items.*, mst_attributes.field_value FROM inv_items, mst_attributes WHERE mst_attributes.id=inv_items.id_mst_attributes_group_main and inv_items.status=1 and inv_items.id_mst_attributes_item_type IN ($item_list) and inv_items.id_shop = '".addslashes($_SESSION['shop'])."'";
							                  
							                   	 $db->query($sql); 
							                    while($row1 = $db->fetch_object()){	

							                    	if($_REQUEST['id_inv_items'] == $row1->id){
														$selected = 'selected="selected"';
													}elseif($array['id_inv_items'.''.$j] == $row1->id){
														$selected = 'selected="selected"';
														$item_description =  $row1->name;
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$row1->id.'">'.ucfirst($row1->item_code.' | '.$row1->name).'</option>';
												} 
											  
											 	echo $categoryDropDown .= '</select>'; 
										?>
					                  </div>
									
					                </td> 
				                    <td style="width:18%;">
				                        <input type="text"  autocomplete="off" name="item_description<?php echo $k;?>" id="item_description<?php echo $k;?>" placeholder="Item Description"  class="form-control"   value="<?php echo $item_description; ?>" readonly />
				                    </td> 
				                    <?php 
				                    	$transaction_unit = $array['transaction_unit'.''.$j];
				                    	$main_unit = $array['main_unit'.''.$j];
				                    	$alt_unit = $array['alt_unit'.''.$j];
				                    	$per_unit = $array['per_unit'.''.$j];
				                    	if($transaction_unit == $main_unit){
				                    		$qty = $array['qty'.''.$j]; 
				                    	}else{
				                    		$qty = $array['alt_qty'.''.$j]; 
				                    	}
				                    	if($per_unit == $main_unit){ 
				                    		$rate_per_main_unit = $array['rate_per_main_unit'.''.$j];
				                    	}else{ 
				                    		$rate_per_main_unit = $array['rate_per_alt_unit'.''.$j];
				                    	}
										//echo $rate_per_main_unit;
				                    ?>
									
									
									
				                    <td  style="width:6%"> 
				                        <input data-parsley-required type="text"  autocomplete="off"  name="qty<?php echo $k;?>" id="qty<?php echo $k;?>" placeholder="Qty" onkeyup="amount_calc(this.id);" onclick="amount_calc(this.id);"  class="form-control discou" value="<?php if($qty=='') echo '0';else echo $qty; ?>" />
				                    </td>
				                    <td style="width:6%;">  
				                        <select class="form-control select2" id="transaction_unit<?php echo $k;?>" name="transaction_unit<?php echo $k;?>" onchange="amount_calc(this.id);" style="width:100%"> 
				                        <?php if($row->id != ''){?> <option value="<?php echo $array['transaction_unit'.''.$j];?>" selected="selected"><?php echo $array['transaction_unit'.''.$j];?></option> <option value="<?php echo $array['main_unit'.''.$j];?>" ><?php echo $array['main_unit'.''.$j];?></option><option value="<?php echo $array['alt_unit'.''.$j];?>" ><?php echo $array['alt_unit'.''.$j];?></option><?php } ?>
					                  	 </select>
					                  	 <!-- Main Unit -->
					                  	 <input type="text"  autocomplete="off" name="main_unit<?php echo $k;?>" id="main_unit<?php echo $k;?>" placeholder="Main Unit"  class="form-control"   value="<?php if($_POST) echo $_POST['main_unit'];else echo stripslashes($array['main_unit'.''.$j]); ?>"  style="display:none;"/>
					                  	 <!-- Alt Unit -->
					                  	 <input type="text"  autocomplete="off" name="alt_unit<?php echo $k;?>" id="alt_unit<?php echo $k;?>" placeholder="Alt Unit"  class="form-control"   value="<?php if($_POST) echo $_POST['alt_unit'];else echo stripslashes($array['alt_unit'.''.$j]); ?>"  style="display:none;"/>
					                  	 <!-- Conversion Rate Per Unit -->
					                  	 <input  type="text"  autocomplete="off" name="conver_rate_per_unit<?php echo $k;?>" id="conver_rate_per_unit<?php echo $k;?>" placeholder="conver_rate_per_unit"  class="form-control discountvalue"   value="<?php if($_POST) echo $_POST['conver_rate_per_unit'];else echo stripslashes($array['conver_rate_per_unit'.''.$j]); ?>"  style="display:none;"/>
				                    </td>
				                     
				                    <td style="width:5%"> 
					                 	 <input type="text"  autocomplete="off"  name="rate_per_main_unit<?php echo $k;?>" id="rate_per_main_unit<?php echo $k;?>" placeholder="Rate"  class="form-control discountvalue" value="<?php if($rate_per_main_unit == ''){ echo '0';  }else {  echo $rate_per_main_unit; } ?>" onkeyup="amount_calc(this.id)" required />

					                 	 <input type="text"  autocomplete="off"  name="item_amount_before_discount<?php echo $k;?>" id="item_amount_before_discount<?php echo $k;?>" placeholder="Rate"  class="form-control discountvalue" value="<?php if($_POST) echo $_POST['item_amount_before_discount'];else echo stripslashes($array['item_amount_before_discount'.''.$j]); ?>" style="display:none;" />
					                </td>
					                <td style="width:5%">  
				                        <select class="form-control select2" id="per_unit<?php echo $k;?>" name="per_unit<?php echo $k;?>" onchange="amount_calc(this.id);" style="width:100%"> 
				                        <?php if($row->id != ''){?> <option value="<?php echo $array['per_unit'.''.$j];?>" selected="selected"><?php echo $array['per_unit'.''.$j];?></option><option value="<?php echo $array['main_unit'.''.$j];?>" ><?php echo $array['main_unit'.''.$j];?></option><option value="<?php echo $array['alt_unit'.''.$j];?>" ><?php echo $array['alt_unit'.''.$j];?></option> <?php } ?>
					                  	 </select>
				                    </td>
					                <td style="width:4%;">
				                         <input type="text"  autocomplete="off"  name="discount_percent<?php echo $k;?>" id="discount_percent<?php echo $k;?>" placeholder="%Discount"  class="form-control discountvalue" value="<?php if($array['discount_percent'.''.$j]=='') echo '0';else echo stripslashes($array['discount_percent'.''.$j]); ?>" onkeyup="amount_calc(this.id);"  onclick="amount_calc(this.id);" />
				                    </td>
					                <td style="width:6%;"> 
					                 	 <input type="text"  data-parsley-required data-parsley-errors-container="#item_amount<?php echo $k;?>Error3" autocomplete="off"  name="item_amount<?php echo $k;?>" id="item_amount<?php echo $k;?>" placeholder="Amount"  class="form-control discountvalue" value="<?php if($array['item_amount'.''.$j]=='') echo '';else echo stripslashes($array['item_amount'.''.$j]); ?>" readonly/>
					                </td>					                
					                
				                  	<div id="taxconfig" id="taxconfig" style="display: none;">
				                    	<!-- SGST -->
				                    	<input type="text"  autocomplete="off"  name="id_mst_charges_sgst<?php echo $k;?>" id="id_mst_charges_sgst<?php echo $k;?>" placeholder="SGST"  class="form-control" value="<?php if($_POST) echo $_POST['id_mst_charges_sgst'];else echo stripslashes($array['id_mst_charges_sgst'.''.$j]); ?>" />

										<input type="text"  autocomplete="off"  name="item_sgst_percent<?php echo $k;?>" id="item_sgst_percent<?php echo $k;?>" placeholder="SGST"  class="form-control" value="<?php if($_POST) echo $_POST['item_sgst_percent'];else echo stripslashes($array['item_sgst_percent'.''.$j]); ?>" />

										<input type="text"  autocomplete="off"  name="item_sgst_amount<?php echo $k;?>" id="item_sgst_amount<?php echo $k;?>" placeholder="SGST Amount"  class="form-control" value="<?php if($_POST) echo $_POST['item_sgst_amount'];else echo stripslashes($array['item_sgst_amount'.''.$j]); ?>" />
										
										<!-- CGST -->
										<input type="text"  autocomplete="off"  name="id_mst_charges_cgst<?php echo $k;?>" id="id_mst_charges_cgst<?php echo $k;?>" placeholder="CGST"  class="form-control" value="<?php if($_POST) echo $_POST['id_mst_charges_cgst'];else echo stripslashes($array['id_mst_charges_cgst'.''.$j]); ?>" />

										<input type="text"  autocomplete="off"  name="item_cgst_percent<?php echo $k;?>" id="item_cgst_percent<?php echo $k;?>" placeholder="CGST"  class="form-control" value="<?php if($_POST) echo $_POST['item_cgst_percent'];else echo stripslashes($array['item_cgst_percent'.''.$j]); ?>" />

										<input type="text"  autocomplete="off"  name="item_cgst_amount<?php echo $k;?>" id="item_cgst_amount<?php echo $k;?>" placeholder="CGST Amount"  class="form-control" value="<?php if($_POST) echo $_POST['item_cgst_amount'];else echo stripslashes($array['item_cgst_amount'.''.$j]); ?>" />
										<!-- IGST -->
										<input type="text"  autocomplete="off"  name="id_mst_charges_igst<?php echo $k;?>" id="id_mst_charges_igst<?php echo $k;?>" placeholder="IGST"  class="form-control" value="<?php if($_POST) echo $_POST['id_mst_charges_igst'];else echo stripslashes($array['id_mst_charges_igst'.''.$j]); ?>" />

										<input type="text"  autocomplete="off"  name="item_igst_percent<?php echo $k;?>" id="item_igst_percent<?php echo $k;?>" placeholder="IGST"  class="form-control" value="<?php if($_POST) echo $_POST['item_igst_percent'];else echo stripslashes($array['item_igst_percent'.''.$j]); ?>" />

										<input type="text"  autocomplete="off"  name="item_igst_amount<?php echo $k;?>" id="item_igst_amount<?php echo $k;?>" placeholder="IGST Amount"  class="form-control" value="<?php if($_POST) echo $_POST['item_igst_amount'];else echo stripslashes($array['item_igst_amount'.''.$j]); ?>" />
									</div>		                				                    				                  	

				                    
				                    <?php if($k>=1){ ?>
				                    
 
					                <?php if($row->id != ''){?>
                                     <td style="display:none;">
					                   	<input type="text"  autocomplete="off"  name="dbid<?php echo $k;?>" id="dbid<?php echo $k;?>" class="form-control" value="<?php if($_POST) echo $_POST['dbid'];else echo stripslashes($array['id'.''.$j]); ?>" style="display: none;"/>
					                </td>
                                     <?php } ?>
				                    
				                	<?php } 
				                	 if($row->id ==''){
				                	 	$counts = 0;
				                	 }else{
				                	 	$counts = $k;
				                	 }
				                	 ?>
				           
				                        
								
									
				                		
				                    <td style="width:6%;"> 
					                					                    
					                   <div id="locals<?php echo $k;?>" name="locals<?php echo $k;?>" >

					                  	<select onchange="po_locals(this.id);" class="form-control select2" name="id_mst_charges_purchase_local<?php echo $k;?>" id="id_mst_charges_purchase_local<?php echo $k;?>" style="width:100%;">

										 <?php $categoryDropDown = '<option value="">Select Tax Register</option>';
										  $resCat = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and status = '1'  and charges_account = '2' and transaction_type = '1' ",' ORDER BY `name`');
										  if($db->num_rows2($resCat)){
										  	while($resultCat = $db->fetch_object2($resCat)){
												//$_REQUEST['id_mst_charges_purchase_local'] = '6' ;
												//id_mst_charges_purchase_local1
												
												if($_REQUEST['id_mst_charges_purchase_local'] == $resultCat->id){
													$selected = 'selected="selected"';
												}elseif($array['id_mst_charges_purchase_local'.''.$j] == $resultCat->id){
													$selected = 'selected="selected"';
												}else{
													$selected = '';
												}
												$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
											}
										  }
										 	echo $categoryDropDown .= '</select>';
										  ?>
											<?php echo $err_item_chargestax;?>
											<?php if($row->id !=''){
												$sgst = 'SGST: '.''.$array['item_sgst_amount'.''.$j];
												$cgst = 'CGST: '.''.$array['item_cgst_amount'.''.$j];
												$igst = 'IGST: '.''.$array['item_igst_amount'.''.$j];
											}else{
												$sgst = '';
												$cgst = '';
												$igst = '';
											}
											?>
											
										</div>

					                  	 <div id="interstates<?php echo $k;?>" name="interstates<?php echo $k;?>">
					                  	 	<select  onchange="po_interstate(this.id)" class="form-control select2" name="id_mst_charges_purchase_interstate<?php echo $k;?>" id="id_mst_charges_purchase_interstate<?php echo $k;?>"  style="width:100%;" >
											<?php $categoryDropDown = '<option value="">Select Tax Register</option>';
											  $resCat = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and status = '1' and charges_account = '2' and transaction_type = '2' ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['id_mst_charges_purchase_interstate'] == $resultCat->id){
														$selected = 'selected="selected"';
													}elseif( $array['id_mst_charges_purchase_interstate'.''.$j] == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?>
											<?php echo $err_item_chargestax;?>
											
					                  	</div>
				                  	</td>
				                  	<td width="8%">
				                  	  <!--<input type="text"  placeholder="Tax here"  class="form-control" value="" />-->
				                  		<div id="localss<?php echo $k;?>" name="localss<?php echo $k;?>" >
					                  		<div style="color:red;font-size:11px;" id="s_amount<?php echo $k;?>">
												<?php echo $sgst;?>
											</div>
											<div style="color:red;font-size:11px;" id="c_amount<?php echo $k;?>">
												<?php echo $cgst;?>
											</div>

											<div style="color:red;font-size:11px;" id="vat_amount<?php echo $k;?>">
												<?php echo $vat;?>
											</div>
												
											<div style="color:red;font-size:11px;" id="cess_amount<?php echo $k;?>">
												<?php echo $cess;?>
											</div>
												
											
											<div style="color:red;font-size:11px;" id="surcharge_amount<?php echo $k;?>">
												<?php echo $surcharge;?>
											</div>
										</div>
										
										 <div id="interstatess<?php echo $k;?>" name="interstatess<?php echo $k;?>">
											<div style="color:red;font-size:11px;" id="i_amount<?php echo $k;?>">
													<?php echo $igst;?>
											</div>
										</div>
									</td>

				                 <td style="width:15%;"> 
				                       <input type="text"  autocomplete="off"  name="item_remarks<?php echo $k;?>" id="item_remarks<?php echo $k;?>" placeholder="Remarks"  class="form-control" value="<?php if($_POST) echo $_POST['item_remarks'];else echo stripslashes($array['item_remarks'.''.$j]); ?>" />
				                    </td>
									
									
									
									
				                  	
				                 
				                  	<td style="width:1%;"><?php if($k>=1){ ?>
                                    <a class="btn n-btn  abtn ibtnDel1 " style="cursor:pointer;" title="Delete" id="ibtn<?php echo $k;?>"  name="ibtn<?php echo $k;?>"><i class="fa fa-trash-o"></i></a>
                                    
                  <?php /*?><img src="images/delete.gif"  class="ibtnDel1" style="cursor:pointer;" title="Delete" id="ibtn<?php echo $k;?>"  name="ibtn<?php echo $k;?>"/><?php */?>
				  
				  <?php } ?></td>
				                	
				                	 <!--<td class="form-group col-xs-12 col-sm-2"><a class="deleteRows" ></a></td>-->
				                </tr>

				            	<?php } ?>
				            	<input type="text" name="counter1" id="counter1" value="<?php echo 
				                    $counts; ?>" hidden=""> 

				               
				            </tbody>
				            </div>

				            <tfoot>
				                <tr> 
				                        <td colspan="12" style="text-align: left;">
				                          
											  <a  type="button" class="btn n-btn btn-block" style="font-size:14px;font-weight:700" id="addrow1" value="Add Row" ><span style="font-size:14px;font-weight:700"><i class="fa fa-plus"></i> Add Row</span> </a>
				                        </td> 
				                </tr>
				                <tr>
				                </tr>
				            </tfoot> 
				        </table>

				        
		            </div>
		            <hr class="br-line mt-10 mb-10">

		           <div class="row">
                   
                   
 					<div class="col-md-8" id="left-pane">
                    
                    
                    <!--charges starts-->
		            <div class="card text-dark bg-light" >
	              		<!--<div class="">
	              			<h5 class="tb-heads d-inline">Others Charges</h5>
	              			
	              		</div>  -->    
			            <div class="container-fluid p-0">
			            	<table id="myTable2" class="table table-bordered table-striped  order-list2 mt-10 max-h2">
				            <thead>
				                <tr>
                             <!--<td>Others Charges</td>-->
				                    <th style="width:21%;">Charges </th>
				                    <?php /*?><th>Percentage</th> <?php */?>
				                    <th>Amount</th>
				                    <th>SGST</th> 
				                    <th>CGST</th> 
				                    <th>IGST</th>  
				                    <th style="width:4%;"></th>
				                </tr>
				            </thead>
				            <tbody>
				            	<?php
				            	$k='';
				            	if($row->id ==''){
								 	$i=1;
								 }else{
								 	//$i=1;
								 } 
				            	//Indent Details Here First Row Only Select
				            	$sql2 = "SELECT * FROM  `".TBL_INV_OTHERS_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_po` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";
								 $db->query($sql2); 
								 $numRows= $db->num_rows();
								if($numRows=='0'){
								 	$i=1;
								 } else{
									 $i=0;
									 }
								while($rowsID = $db->fetch_object()){
							 		 $array['id'.''.$i] = $rowsID->id;
							 		 $array['id_inv_po'.''.$i] = $rowsID->id_inv_po;
							 		 $array['type'.''.$i] = $rowsID->type; 
							 		 $array['id_mst_charges'.''.$i] = $rowsID->id_mst_charges;
							 		 $array['id_mst_charges_discounts'.''.$i] = $rowsID->id_mst_charges_discounts; 
							 		 $array['others_discount_percent'.''.$i] = $rowsID->others_discount_percent; 
							 		 $array['others_discount_amount'.''.$i] = $rowsID->others_discount_amount; 
							 		 $array['others_charges_sgst_percent'.''.$i] = $rowsID->others_charges_sgst_percent; 
							 		 $array['others_charges_sgst_amount'.''.$i] = $rowsID->others_charges_sgst_amount; 
							 		 $array['others_charges_cgst_percent'.''.$i] = $rowsID->others_charges_cgst_percent; 
							 		 $array['others_charges_cgst_amount'.''.$i] = $rowsID->others_charges_cgst_amount; 
							 		 $array['others_charges_igst_percent'.''.$i] = $rowsID->others_charges_igst_percent; 
							 		 $array['others_charges_igst_amount'.''.$i] = $rowsID->others_charges_igst_amount;  
							 		 $array['others_charges_amount'.''.$i] = $rowsID->others_charges_amount;
							 		 $array['others_charges_percent'.''.$i] = $rowsID->others_charges_percent; 
							 		 $array['total_amount_others'.''.$i] = $rowsID->total_amount_others;  
							 		 $array['id_mst_charges_others'.''.$i] = $rowsID->id_mst_charges_others;  
							 		 $array['id_mst_charges_sgst_others'.''.$i] = $rowsID->id_mst_charges_sgst_others;  
							 		 $array['id_mst_charges_cgst_others'.''.$i] = $rowsID->id_mst_charges_cgst_others;  
							 		 $array['id_mst_charges_igst_others'.''.$i] = $rowsID->id_mst_charges_igst_others;  
							 		 $i++;
								}  
								for($j=0; $j<$i; $j++){ 
								 if($j == 0){
								 	$k='';
								 }else{
								 	$k = $j;
								 }
				            	?>
				            	<div class="form-group col-xs-12 col-md-6 col-sm-2" style="display: none;"  >
				                  <label for="name">Update Id</label>
				                  <input type="text" class="form-control" id="chargesupdate_id<?php echo $k;?>" name="chargesupdate_id<?php echo $k;?>" value="<?php echo $array['id'.''.$j];?>"> 

				                  <label for="name">Update Count</label>
				                  <input type="text" class="form-control" id="chargesupdate_count" name="chargesupdate_count" value="<?php echo $k;?>"> 
				                </div>
				                <tr>
					                <input hidden id="chargesselect<?php echo $k;?>" name="chargesselect<?php echo $k;?>" >

					                <td class="form-group col-xs-12 col-md-2 col-sm-2" style="display:none"> 
					                
					                <?php if($row->id == !''){  ?>			                 		 
			                 		<?php 
			                 		if($array['type'.''.$j] == '1'){ ?>
			                 			<select class="form-control select2" name="type<?php echo $k;?>" id="type<?php echo $k;?>" style="width:100%"><?php
										$categoryDropDown = '<option value="1">OTHERS</option>';
										echo $categoryDropDown;?>
			                 			<option value="1">OTHERS</option>
				                  	 	<option value="2">DISCOUNT</option>
										</select> 
			                 		<?php
			                 		}else if($array['type'.''.$j] == '2'){ ?>
			                 			<select class="form-control select2" name="type<?php echo $k;?>" id="type<?php echo $k;?>" style="width:100%"><?php
										$categoryDropDown = '<option value="2">DISCOUNT</option>';
			                 			echo $categoryDropDown;?>
			                 			<option value="1">OTHERS</option>
				                  	 	<option value="2">DISCOUNT</option>
										</select> 
			                 		<?php }else{
			                 			?>
					               	<select class="form-control select2" id="type<?php echo $k;?>" name="type<?php echo $k;?>" onchange="type_funt(this.id)" style="width:100%"> 
					                  	 	<option value="">select Charges</option>
					                  	 	<option value="1">OTHERS</option>
					                  	 	<option value="2">DISCOUNT</option> 
					                  	 </select> 

			                 		<?php } ?>			                 	    
										
									</div>
				                 	<?php 
									} else{ ?>
										
										<select class="form-control select2" id="type<?php echo $k;?>" name="type<?php echo $k;?>" onchange="type_funt(this.id)" style="width:100%;"> 
					                  	 	<option value="">select Charges</option>
					                  	 	<option value="1">OTHERS</option>
					                  	 	<option value="2">DISCOUNT</option> 
					                  	 </select> 
										 
					                <?php } ?>		                  	
					                </td>
									
					                <?php if($row->id != ''){ ?>
						                <?php if($array['id_mst_charges_others'.''.$j] != 0) { ?>
						                	<style type="text/css">
						                	#others<?php echo $k;?>{
						                		display: block;
						                	}
						                	#discounts<?php echo $k;?>{
						                		display: none;
						                	}
						                	</style>
						                <?php } else if($array['id_mst_charges_discounts'.''.$j] != 0) { ?>
						                	<style type="text/css">						                	
						                	#discounts<?php echo $k;?>{
						                		display: block;
						                	}
						                	#others1<?php echo $k;?>{
						                		display: none;
						                	}
						                	</style>
						                <?php }else{ ?>
						                	<style type="text/css">
						                	#others1{
						                		display: none;
						                	}
						                	#discounts{
						                		display: none;
						                	}
						                </style>
					                <?php } }else{ ?>
					                <style type="text/css">
					                	#others1{
					                		display: none;
					                	}
					                	#discounts{
					                		display: none;
					                	}
					                </style>
					                <?php } ?>
									
									
									
									
								
					                <td class="form-group col-xs-12 col-md-2 col-sm-1">
					                	<div id="others<?php echo $k;?>" name="others<?php echo $k;?>">
					                 		<select class="form-control select2" name="id_mst_charges_others<?php echo $k;?>" id="id_mst_charges_others<?php echo $k;?>"  style="width: 100%;" onchange="charges_others(this.id)" style="width:100%">
												<?php $categoryDropDown = '<option value="">Select Others Charges</option>';
											 						 
							                   	$sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND mst_charges.charges_account IN (4) ";
							                   	 $db->query($sql); 
							                    while($row1 = $db->fetch_object()){	

							                    	if($_REQUEST['id_mst_charges_others'] == $row1->id){
														$selected = 'selected="selected"';
													}elseif($array['id_mst_charges_others'.''.$j] == $row1->id){
														$selected = 'selected="selected"'; 
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$row1->id.'">'.ucfirst($row1->name).'</option>';

													
												} 
											  
											 	echo $categoryDropDown .= '</select>';
											?>
										</div>
										
										
										<div id="discounts<?php echo $k;?>" name="discounts<?php echo $k;?>">
											<select class="form-control select2" name="id_mst_charges_discounts<?php echo $k;?>" id="id_mst_charges_discounts<?php echo $k;?>"  style="width: 100%;" onchange="otherscharges_discount(this.id)" style="width:100%">
												<?php $categoryDropDown = '<option value="">Select Discount Charges</option>';
											 						 
							                   	$sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND mst_charges.charges_account = '6' ";
							                   	 $db->query($sql); 
							                    while($row1 = $db->fetch_object()){	

							                    	if($_REQUEST['id_mst_charges_discounts'] == $row1->id){
														$selected = 'selected="selected"';
													}elseif($array['id_mst_charges_discounts'.''.$j] == $row1->id){
														$selected = 'selected="selected"'; 
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$row1->id.'">'.ucfirst($row1->name).'</option>';
												} 
											  
											 	echo $categoryDropDown .= '</select>';
											?>
										</div>
					                </td>	
					                <div id="otherschargesdiscount" name="otherschargesdiscount"   style="display: none;">
					                	<!-- Discount -->
										<input type="text"  autocomplete="off"  name="others_discount_percent<?php echo $k;?>" id="others_discount_percent<?php echo $k;?>" placeholder="Discount"  class="form-control" value="<?php if($_POST) echo $_POST['others_discount_percent'];else echo stripslashes($array['others_discount_percent'.''.$j]); ?>" />

										<input type="text"  autocomplete="off"  name="others_discount_amount<?php echo $k;?>" id="others_discount_amount<?php echo $k;?>" placeholder="Amount"  class="form-control" value="<?php if($_POST) echo $_POST['others_discount_amount'];else echo stripslashes($array['others_discount_amount'.''.$j]); ?>" />
					                </div>
					                <div id="otherstaxconfig" id="otherstaxconfig" style="display:none;">
				                    	<!-- SGST -->
										<input type="text"  autocomplete="off"  name="others_charges_sgst_percent<?php echo $k;?>" id="others_charges_sgst_percent<?php echo $k;?>" placeholder="SGST"  class="form-control" value="<?php if($_POST) echo $_POST['others_charges_sgst_percent'];else echo stripslashes($array['others_charges_sgst_percent'.''.$j]); ?>" />

										<input type="text"  autocomplete="off"  name="id_mst_charges_sgst_others<?php echo $k;?>" id="id_mst_charges_sgst_others<?php echo $k;?>" placeholder="SGST"  class="form-control" value="<?php if($_POST) echo $_POST['id_mst_charges_sgst_others'];else echo stripslashes($array['id_mst_charges_sgst_others'.''.$j]); ?>" />

										
										<!-- CGST -->
										<input type="text"  autocomplete="off"  name="others_charges_cgst_percent<?php echo $k;?>" id="others_charges_cgst_percent<?php echo $k;?>" placeholder="CGST"  class="form-control" value="<?php if($_POST) echo $_POST['others_charges_cgst_percent'];else echo stripslashes($array['others_charges_cgst_percent'.''.$j]); ?>" />

										<input type="text"  autocomplete="off"  name="id_mst_charges_cgst_others<?php echo $k;?>" id="id_mst_charges_cgst_others<?php echo $k;?>" placeholder="CGST"  class="form-control" value="<?php if($_POST) echo $_POST['id_mst_charges_cgst_others'];else echo stripslashes($array['id_mst_charges_cgst_others'.''.$j]); ?>" />

										
										<!-- IGST -->
										<input type="text"  autocomplete="off"  name="others_charges_igst_percent<?php echo $k;?>" id="others_charges_igst_percent<?php echo $k;?>" placeholder="IGST"  class="form-control" value="<?php if($_POST) echo $_POST['others_charges_igst_percent'];else echo stripslashes($array['others_charges_igst_percent'.''.$j]); ?>" />

										<input type="text"  autocomplete="off"  name="id_mst_charges_igst_others<?php echo $k;?>" id="id_mst_charges_igst_others<?php echo $k;?>" placeholder="IGST"  class="form-control" value="<?php if($_POST) echo $_POST['id_mst_charges_igst_others'];else echo stripslashes($array['id_mst_charges_igst_others'.''.$j]); ?>" />
										
									</div>

									<td class="form-group col-xs-12 col-md-1 col-sm-2" style="display:none;" > 
										<?php //if( $array['others_charges_percent'.''.$j] >=1 || $row->id == '') {?>
				                        <input type="text"  autocomplete="off"  name="others_charges_percent<?php echo $k;?>" id="others_charges_percent<?php echo $k;?>" placeholder="Percentage"  class="form-control discountvalue" value="<?php if($_POST) echo $_POST['others_charges_percent'];else echo stripslashes($array['others_charges_percent'.''.$j]); ?>" onkeyup="subtotal_calc(this.id)"  />
				                    	<?php //} ?>
				                    </td>		                
				                    
				                	<td class="form-group col-xs-12 col-md-2  col-sm-2">   

					                 	<input type="text"  autocomplete="off"  name="others_charges_amount<?php echo $k;?>" id="others_charges_amount<?php echo $k;?>" placeholder="Amount"  class="form-control discountvalue" value="<?php if($_POST) echo $_POST['others_charges_amount'];else echo stripslashes($array['others_charges_amount'.''.$j]); ?>" onkeyup="charges_amount_calc(this.id)" onclick="charges_amount_calc(this.id)" />
									
					                </td> 
					                <td class="form-group col-xs-12 col-md-2  col-sm-2"> 
					                	<input type="text"  autocomplete="off"  name="others_charges_sgst_amount<?php echo $k;?>" id="others_charges_sgst_amount<?php echo $k;?>" placeholder="SGST Amount"  class="form-control" value="<?php if($_POST) echo $_POST['others_charges_sgst_amount'];else echo stripslashes($array['others_charges_sgst_amount'.''.$j]); ?>" readonly />
					                </td>
					                <td class="form-group col-xs-12 col-md-2  col-sm-2"> 
					                	<input type="text"  autocomplete="off"  name="others_charges_cgst_amount<?php echo $k;?>" id="others_charges_cgst_amount<?php echo $k;?>" placeholder="CGST Amount"  class="form-control" value="<?php if($_POST) echo $_POST['others_charges_cgst_amount'];else echo stripslashes($array['others_charges_cgst_amount'.''.$j]); ?>" readonly/>
					                </td>
					                <td class="form-group col-xs-12 col-md-2  col-sm-2"> 
					                	<input type="text"  autocomplete="off"  name="others_charges_igst_amount<?php echo $k;?>" id="others_charges_igst_amount<?php echo $k;?>" placeholder="IGST Amount"  class="form-control" value="<?php if($_POST) echo $_POST['others_charges_igst_amount'];else echo stripslashes($array['others_charges_igst_amount'.''.$j]); ?>" readonly/>
					                </td>  
					                <td class="form-group col-xs-12 col-md-2 col-sm-2" style="display: none;"> 
				                        <input type="text"  autocomplete="off"  name="total_amount_others<?php echo $k;?>" id="total_amount_others<?php echo $k;?>" placeholder="Total"  class="form-control" value="<?php if($_POST) echo $_POST['total_amount_others'];else echo stripslashes($array['total_amount_others'.''.$j]); ?>" readonly/>
				                    </td> 
				                   		 
									<?php if($j==0){?><td>
				                    	 <a  type="button" class="btn n-btn abtn" id="addrow2" value="Add Row"> <span><i class="fa fa-plus"></i> </span> </a>
                                         </td><?php }?>
				                    	

				                    
				                    <?php if($k>=1){ ?>
				                    <td> 
<a class="btn n-btn  abtn ibtnDel2 "id="ibtns<?php echo $k;?>"  name="ibtns<?php echo $k;?>" style="cursor:pointer;" title="Delete"><i class="fa fa-trash-o"></i></a>
					                   	<?php /*?><img src="images/delete.gif"  class="ibtnDel2 " style="cursor:pointer;" title="Delete" id="ibtns<?php echo $k;?>"  name="ibtns<?php echo $k;?>"/><?php */?>

					                <?php if($row->id != ''){?>
					                   	<input type="text"  autocomplete="off"  name="dbid2<?php echo $k;?>" id="dbid2<?php echo $k;?>" class="form-control" value="<?php if($_POST) echo $_POST['dbid2'];else echo stripslashes($array['id'.''.$j]); ?>" style="display: none;"/>
					                 <?php } ?>
				                    </td>
				                	<?php } 
				                	 if($row->id ==''){
				                	 	$counts = 0;
				                	 }else{
				                	 	$counts = $k;
				                	 }
				                	 ?>
				                	 <td class="form-group col-xs-12  col-sm-2 d-none"><a class="deleteRow" ></a></td>
				                </tr> 				                 
				            	<?php } ?>
				            	<input type="text" name="counter2" id="counter2" value="<?php echo 
				                    $counts; ?>" hidden=""> 
				            </tbody>
				         
				        </table>
			            </div>
		        	</div>
		        	<!--End of Charges-->

                 <!--start of discount-->
                 <div class="card text-dark bg-light" >
	              		
			            <div class="container-fluid mb-10 p-0">
			            	<div class="form-group col-xs-12 col-md-3 col-sm-2">
							
							<?php //echo $_POST['id_mst_charges_purchase_local'].'hi'; ?>
				        		<label>Discount Scheme Apply</label>
				        		<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fas fa-tag"></i>
							   	</div>
						        <select class="form-control select2" name="id_mst_charges_discounts_items" id="id_mst_charges_discounts_items"  style="width: 100%;" onchange="discount_all(this.id)" style="width:100%">
									<?php $categoryDropDown = '<option value="">Select Discount</option>';
								 						 
				                   	$sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND mst_charges.charges_account = '6' ";
				                   	 $db->query($sql); 
				                    while($row1 = $db->fetch_object()){	

				                    	if($_REQUEST['id_mst_charges_discounts_items'] == $row1->id){
											$selected = 'selected="selected"';
										}elseif($row->id_mst_charges_discounts_items == $row1->id){
											$selected = 'selected="selected"'; 
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option '.$selected.' value="'.$row1->id.'">'.ucfirst($row1->name).'</option>';
									} 
								  
								 	echo $categoryDropDown .= '</select>';
								?>
								</div>
							</div>
                            <div class=" col-xs-12 col-md-2 col-sm-2">
								<label>Percentage</label>
								<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-percent"></i>
							   	</div>
								<input type="text" class="form-control" id="discount_percent_items" name="discount_percent_items" placeholder="Percentage" value="<?php echo $row->discount_percent_items;?>" ></div>
							</div>
							<div class="col-xs-12 col-md-2 col-sm-2">
								<label>Apply</label><br>
								<button type="button" id="button" class="btn c-btn" onclick="apply_percentage(this.id)">Apply</button>
							</div>
			            </div>
		        	</div>
                 <!--End of discount-->


		        	<!-- Terms And Conditions -->
		        	 <div class="card text-dark bg-light border">
	              
			            <div class="container-fluid p-0">
			            	<table id="myTable3" class="table table-bordered table-striped order-list3 mt-10 max-h2">
					            <thead>
					                <tr>
					                    <th>Terms & Condition</th> 
					                    <th class="p-0"> 

						                  	 <a  type="button" title="Add Terms & Conditions" class="btn n-btn"  id="addrow3" value="Add Row"> <span ><i class="fa fa-plus"></i> </span> </a>
						                  </th>
					                </tr>
					            </thead>
					            <tbody>
					            	<?php
					            	$k='';
					            	if($row->id ==''){
									 	$i=1;
									 }else{
									 	$i=0;
									 } 
					            	//Indent Details Here First Row Only Select
					            	$sql2 = "  SELECT * FROM  `".TBL_INV_TERMS_AND_CONDITIONS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_po` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";
									$db->query($sql2);
									$numRows= $db->num_rows();
									while($rowsID = $db->fetch_object()){
								 		 $array['id'.''.$i] = $rowsID->id;
								 		 $array['id_inv_po'.''.$i] = $rowsID->id_inv_po;
								 		 $array['terms'.''.$i] = $rowsID->terms; 
								 		  $i= $i+1;
									}  

if($numRows==0){ $i=1;
										 }
									for($j=0; $j<$i; $j++){ 
									 if($j == 0){
									 	$k='';
									 }else{
									 	$k = $j;
									 } 
									 
										
					            	?>
					            	<div class="form-group col-xs-12 col-md-6 col-sm-2 mb-0"  >
					                  <label for="name" style="display: none">Update Id</label>
					                  <input type="hidden" class="form-control" id="termsupdate_id<?php echo $k;?>" name="termsupdate_id<?php echo $k;?>" value="<?php echo $array['id'.''.$j];?>"> 

					                  <label for="name" style="display: none">Update Count</label>
					                  <input type="hidden" class="form-control" id="termsupdate_count" name="termsupdate_count" value="<?php echo $k;?>">
					                  	<tr>
						                  <td  class="form-group col-xs-12 col-md-12 col-sm-2">
						                  	<input type="text"  autocomplete="off"  name="terms<?php echo $k;?>" id="terms<?php echo $k;?>" placeholder="Terms And Conditions"  class="form-control" value="<?php if($_POST) echo $_POST['terms'];else echo stripslashes($array['terms'.''.$j]); ?>" />
						                 </td>	
                                         
                                         
						                  <!--<td>	

						                  	 <a  type="button" title="Add Row" class="btn n-btn abtn" style="font-size:14px;font-weight:700" id="addrow3" value="Add Row"> <span style="font-size:14px;font-weight:700"><i class="fa fa-plus"></i> </span> </a>
						                  </td>	-->

						                   </a>
						                  	<?php 
						                  	if($row->id ==''){
						                	 	$counts = 0;
						                	}else{
						                	 	$counts = $numRows - 1;
						                	}
						                	?> 
						                  
						                	 <td class="form-group col-xs-12  col-sm-2"><a class="deleteRow2" ></a></td>
						                	
					              		</tr>
					              	<?php } ?>
					              	<input type="text" name="counter3" id="counter3" value="<?php echo $counts; ?>" hidden>
					              		
				                	</div>
					            </tbody>
					           </table>
					       </div>
					   </div>
					   <!--end of terms and condition-->
					</div>
					<div class="col-md-4 " id="right-pane">

		        	<!-- Total Amount Section -->
		            <div class="card text-dark bg-light add-l">
                    
                    
                    
                    
                    
                    
	              	<?php /*?>	<div class="text-center ">
	              			<h6 class="tb-heads" style="background:#eeeeee">Total Amount</h6>
	              		</div> <?php */?> 
	              	
			            <div class="row">
			            	<!--<div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
			            	 <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
			            	 <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>-->
			            	<div class="form-group col-xs-12 col-md-12 col-sm-12 mb-3" >
			                  <label for="name">Sub Total</label>
			                  <div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-plus"></i>
							   	</div>
							   	<?php if($row->id == ''){
							   		$sub_total_items = 0;
							   	}else{
							   		$sub_total_items = $row->sub_total_items;
							   	}
							   	?>
			                  	<input type="text" class="form-control" placeholder="0" id="sub_total_items" name="sub_total_items" value="<?php if($_POST) echo $_POST['sub_total_items'];else echo stripslashes($sub_total_items); ?>" readonly>
			                   <input type="text" class="form-control" placeholder="0" id="sub_total_items1" name="sub_total_items1" value="<?php if($_POST) echo $_POST['sub_total_items1'];else echo stripslashes($row->sub_total_items1); ?>" style="display: none;">
			                  </div> 
			                  </div> 
			                	
			            </div>
                        
                        <!---DISCOUNT START---->
                        
                        <div class="row">
			            	
				        	<?php /*?><div class="col-xs-12 col-md-2 col-sm-2">
							
							<?php //echo $_POST['id_mst_charges_purchase_local'].'hi'; ?>
				        		<label>Discount Scheme Apply</label>
				        		<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fas fa-tag"></i>
							   	</div>
						        <select class="form-control select2" name="id_mst_charges_discounts_items" id="id_mst_charges_discounts_items"  style="width: 100%;" onchange="discount_all(this.id)" style="width:100%">
									<?php $categoryDropDown = '<option value="">Select Discount</option>';
								 						 
				                   	$sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND mst_charges.charges_account = '6' ";
				                   	 $db->query($sql); 
				                    while($row1 = $db->fetch_object()){	

				                    	if($_REQUEST['id_mst_charges_discounts_items'] == $row1->id){
											$selected = 'selected="selected"';
										}elseif($row->id_mst_charges_discounts_items == $row1->id){
											$selected = 'selected="selected"'; 
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option '.$selected.' value="'.$row1->id.'">'.ucfirst($row1->name).'</option>';
									} 
								  
								 	echo $categoryDropDown .= '</select>';
								?>
								</div>
							</div>
							<div class=" col-xs-12 col-md-2 col-sm-2">
								<label>Percentage</label>
								<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-percent"></i>
							   	</div>
								<input type="text" class="form-control" id="discount_percent_items" name="discount_percent_items" placeholder="Percentage" value="<?php echo $row->discount_percent_items;?>" readonly></div>
							</div>
							<div class="col-xs-12 col-md-2 col-sm-2">
								<label>Apply</label><br>
								<button type="button" id="button" class="btn c-btn" onclick="apply_percentage(this.id)">Apply</button>
							</div><?php */?>
						
                        
                        
			                <div class="form-group col-xs-12 col-md-6 col-sm-6"  style="margin-bottom: 6px;">
			                  <label for="name">Discount</label>
			                  <div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fas fa-tag"></i>
							   	</div>
			                  <input type="text" class="form-control" placeholder="0" id="total_discount_items" name="total_discount_items" value="<?php if($_POST) echo $_POST['total_discount_items'];else echo stripslashes($row->total_discount_items); ?>" readonly>
			                  <input type="text" class="form-control" placeholder="0" id="total_discount_items1" name="total_discount_items1" value="<?php if($_POST) echo $_POST['total_discount_items1'];else echo stripslashes($row->total_discount_items1); ?>" style="display: none;"> 
			                  </div> 
			                </div>	

			                <div class="form-group col-xs-12 col-md-6 col-sm-6 mb-0">
			                  <label for="name">Total</label>
			                  <div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-plus"></i>
							   	</div>
			                  <input type="text" class="form-control" placeholder="0" id="net_amount_items" name="net_amount_items" value="<?php if($_POST) echo $_POST['net_amount_items'];else echo stripslashes($row->net_amount_items); ?>" readonly style="text-align:right;">
			                  <input type="text" class="form-control" placeholder="0" id="net_amount_items1" name="net_amount_items1" value="<?php if($_POST) echo $_POST['net_amount_items1'];else echo stripslashes($row->net_amount_items1); ?>" style="display: none;">  
			                  </div> 
			                </div>
			            </div>
                        
                        
			            <div class="row">
			                
			                <div class="form-group col-xs-12 col-md-6 col-sm-6"><label for="name">Others Charges</label></div>
			                <div class="form-group mb-3 col-xs-12 col-md-6 col-sm-6">
			                  
			                  <div class="input-group"> 
		              			<div class="input-group-addon">
		              				<i class="far fa-bookmark"></i>
							   	</div>
			                  <input type="text" class="form-control" placeholder="0" id="others_charges_net_amount" name="others_charges_net_amount" value="<?php if($_POST) echo $_POST['others_charges_net_amount'];else echo stripslashes($row->others_charges_net_amount); ?>" readonly style="text-align:right;">
			                  <input type="text" class="form-control" placeholder="others_charges_net_amount" id="others_charges_net_amount1" name="others_charges_net_amount1" value="<?php if($_POST) echo $_POST['others_charges_net_amount1'];else echo stripslashes($row->others_charges_net_amount1); ?>" style="display: none;">

			                  </div> 
			                </div>
			           	</div>

			           	<!-- SGST -->
			           	<div class="row">
			           		 <?php if($row->id == ''){
							   		$sgst_net_amount = 0;
							   	}else{
							   		$sgst_net_amount = $row->sgst_net_amount;
							   	}
							   	?>
			              
			                <div class="form-group col-xs-12 col-md-6 col-sm-6"> <label for="name">SGST</label></div>
			                <div class="form-group mb-3 col-xs-12 col-md-6 col-sm-6">
			                 
			                  <div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-caret-square-o-down"></i>
							   	</div>
			                  <input type="text" class="form-control" placeholder="0" id="sgst_net_amount" name="sgst_net_amount" value="<?php if($_POST) echo $_POST['sgst_net_amount'];else echo stripslashes($sgst_net_amount); ?>" readonly style="text-align:right;">
			                  <input type="text" class="form-control" placeholder="0" id="sgst1" name="sgst1" value="<?php if($_POST) echo $_POST['sgst1'];else echo stripslashes($row->sgst1); ?>" style="display: none;">
			                  <input type="text" class="form-control" placeholder="0" id="sgst2" name="sgst2" value="<?php if($_POST) echo $_POST['sgst2'];else echo stripslashes($row->sgst2); ?>" style="display: none;"> 

			                  <!-- OC SGST--> 
			                  <input type="text" class="form-control" placeholder="0" id="oc_sgst_total" name="oc_sgst_total" value="<?php if($_POST) echo $_POST['oc_sgst_total'];else echo stripslashes($row->oc_sgst_total); ?>" style="display: none;">
			                  <input type="text" class="form-control" placeholder="0" id="oc_sgst1" name="oc_sgst1" value="<?php if($_POST) echo $_POST['oc_sgst1'];else echo stripslashes($row->oc_sgst1); ?>" style="display: none;">
			                  <input type="text" class="form-control" placeholder="0" id="oc_sgst2" name="oc_sgst2" value="<?php if($_POST) echo $_POST['oc_sgst2'];else echo stripslashes($row->oc_sgst2); ?>" style="display: none;">  
			                  </div> 
			                </div> 
			           	</div>

			           	<!-- CGST -->
			           	<div class="row">
			           		 <?php if($row->id == ''){
							   		$cgst_net_amount = 0;
							   	}else{
							   		$cgst_net_amount = $row->cgst_net_amount;
							   	}
							   	?>
			           
			                <div class="form-group col-xs-12 col-md-6 col-sm-6"> <label for="name">CGST</label></div>
			                <div class="form-group mb-3 col-xs-12 col-md-6 col-sm-6"> 
			                  <div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-caret-square-o-left"></i>
							   	</div>
			                  <input type="text" class="form-control" placeholder="0" id="cgst_net_amount" name="cgst_net_amount" value="<?php if($_POST) echo $_POST['cgst_net_amount'];else echo stripslashes($cgst_net_amount); ?>" readonly style="text-align:right;">
			                  <input type="text" class="form-control" placeholder="0" id="cgst1" name="cgst1" value="<?php if($_POST) echo $_POST['cgst1'];else echo stripslashes($row->cgst1); ?>" style="display: none;"> 
			                  <input type="text" class="form-control" placeholder="0" id="cgst2" name="cgst2" value="<?php if($_POST) echo $_POST['cgst2'];else echo stripslashes($row->cgst2); ?>" style="display: none;"> 

			                  <!-- OC CGST--> 
			                  <input type="text" class="form-control" placeholder="0" id="oc_cgst_total" name="oc_cgst_total" value="<?php if($_POST) echo $_POST['oc_cgst_total'];else echo stripslashes($row->oc_cgst_total); ?>" style="display: none;">
			                  <input type="text" class="form-control" placeholder="0" id="oc_cgst1" name="oc_cgst1" value="<?php if($_POST) echo $_POST['oc_cgst1'];else echo stripslashes($row->oc_cgst1); ?>" style="display: none;"> 
			                  <input type="text" class="form-control" placeholder="0" id="oc_cgst2" name="oc_cgst2" value="<?php if($_POST) echo $_POST['oc_cgst2'];else echo stripslashes($row->oc_cgst2); ?>" style="display: none;">
			                  </div> 
			                </div> 
			           	</div>

			           	<!-- IGST -->
			           	<div class="row">
			           		 <?php if($row->id == ''){
							   		$igst_net_amount = 0;
							   	}else{
							   		$igst_net_amount = $row->igst_net_amount;
							   	}
							   	?>
			                
			                <div class="form-group col-xs-12 col-md-6 col-sm-6"> <label for="name">IGST</label></div>
			                 <div class="form-group  mb-3 col-xs-12 col-md-6 col-sm-6"> 
			                  <div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-caret-square-o-right"></i>
							   	</div>
			                  <input type="text" class="form-control" placeholder="0" id="igst_net_amount" name="igst_net_amount" value="<?php if($_POST) echo $_POST['igst_net_amount'];else echo stripslashes($igst_net_amount); ?>" readonly style="text-align:right;">
			                  <input type="text" class="form-control" placeholder="0" id="igst1" name="igst1" value="<?php if($_POST) echo $_POST['igst1'];else echo stripslashes($row->igst1); ?>" style="display: none;"> 
			                  <input type="text" class="form-control" placeholder="0" id="igst2" name="igst2" value="<?php if($_POST) echo $_POST['igst2'];else echo stripslashes($row->igst2); ?>" style="display: none;">

			                  <!-- OC IGST--> 
			                  <input type="text" class="form-control" placeholder="0" id="oc_igst_total" name="oc_igst_total" value="<?php if($_POST) echo $_POST['oc_igst_total'];else echo stripslashes($row->oc_igst_total); ?>" style="display: none;">
			                  <input type="text" class="form-control" placeholder="0" id="oc_igst1" name="oc_igst1" value="<?php if($_POST) echo $_POST['oc_igst1'];else echo stripslashes($row->oc_igst1); ?>" style="display: none;">
			                  <input type="text" class="form-control" placeholder="0" id="oc_igst2" name="oc_igst2" value="<?php if($_POST) echo $_POST['oc_igst2'];else echo stripslashes($row->oc_igst2); ?>" style="display: none;">  
			                  </div> 
			                </div>
			           	</div>

			           	<!-- Additional Discount -->
			           	<div class="row">
			           	
			                <div class="form-group col-xs-12 col-md-6 col-sm-6"> <label for="name">Misc Discount Amount</label></div>
			                 <div class="form-group  mb-3 col-xs-12 col-md-6 col-sm-6"> 
			                  <div class="input-group"> 
		              			<div class="input-group-addon">
								<i class="fas fa-tag"></i>							   	
							</div>
			                  <input type="text" class="form-control" placeholder="Discount Amount" id="disc_amount_additional" name="disc_amount_additional" value="<?php if($_POST) echo round($_POST['disc_amount_additional'],2);else echo round($row->disc_amount_additional,2); ?>" readonly style="text-align:right;">
			                  <input type="text" class="form-control" placeholder="disc_amount_additional" id="disc_amount_additional1" name="disc_amount_additional1" value="<?php if($_POST) echo round($_POST['disc_amount_additional1'],2);else echo round($row->disc_amount_additional1,2); ?>" style="display: none;">
			                  </div> 
			                </div>
			           	</div> 
			           	<!-- Round Amount -->
			           	<div class="row">
			           		  
			              
			                <div class="form-group col-xs-12 col-md-6 col-sm-6"> <label for="name">Round Of Amount</label></div>
			                 <div class="form-group mb-3 col-xs-12 col-md-6 col-sm-6"> 
			                  <div class="input-group"> 
		              			<div class="input-group-addon">
							      <i class="fa fa-inr"></i>

							   	</div>
			                  <input type="text" class="form-control" placeholder="0" id="round_off_amount" name="round_off_amount" value="<?php if($_POST) echo round($_POST['round_off_amount'],2);else echo round($row->round_off_amount,2); ?>" readonly style="text-align:right;">
			                  </div> 
			                </div>
			           	</div>

			           	<!-- Net Amount -->
			           	<div class="row">
			           		  
			            
			                <div class="form-group col-xs-12 col-md-6 col-sm-6"> <label for="name">Net Amount</label></div>
			                 <div class="form-group mb-3 col-xs-12 col-md-6 col-sm-6"> 
			                  <div class="input-group"> 
		              			<div class="input-group-addon">
		              				<i class="fa fa-inr"></i>
							   	</div>
			                  <input type="text" class="form-control" placeholder="0" id="net_amount" name="net_amount" value="<?php if($_POST) echo round($_POST['net_amount'],2);else echo round($row->net_amount,2); ?>" readonly style="text-align:right;">
			                  </div> 
			                </div>
			           	</div> 
		        	</div>
		        </div>  
		          </div>   
		         <hr class="br-line mb-10">  
             
		            
		        <?php 
		        	if($row->status == ''){
		        		$status = 1;
		        	}else{
		        		$status = $row->status;
		        	}
		        ?>
		           
		            <!--end of row-->
                   
 				
 				<input type='submit' value='<?=($_REQUEST['eId']==''?'Save':'Save')?>' class="btn c-btn" name="Save"  >	
			   <a type='button' value='Cancel' class="btn c-btn" onclick='location.replace("managePO.php?submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>"); '><i class="far fa-window-close" aria-hidden="true"></i> Close</a>

				<?php if($row->date_created){?>
					<div class="row mt-10">
						<div class="form-group col-md-3">
		                	<label for="date_created">Date Created</label>
		                	<input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($row->date_created));?>">				
		                </div> 

		                <div class="form-group col-md-3">
		                  <label for="last_modified_by">Created By</label>
						   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_created_by.'" ');?>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
		                </div> 
				
						<div class="form-group col-md-3">
		                  <label for="last_modified">Last Updated</label>
		                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($row->last_modified));?>">				
		                </div>  
				
						<div class="form-group col-md-3">
		                  <label for="last_modified_by">Last Updated By</label>
						   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_modified_by.'" ');?>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
		                </div> 
					</div> 
						   <a type='button' value='Alteration History' class="btn o-btn"  onclick="audittrial(this.value);" style="float:right">  <i class="fas fa-history"></i> Alteration History</a>
				<?php } ?>  
         	</div>
			
			 
			 
		<!-- Another Modal -->
<div class="modal fade" id="anotherModal" tabindex="-1" role="dialog" aria-labelledby="anotherModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
               <!-- <h4 class="modal-title" id="roomtypeModalLabel">Rooms Select</h4>  -->
                <label class="modal-title" id="roomtitle1" style="font-size:22px;">Purchase Order</label>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped">
				
				<div style="text-align:center;font-weight:600;font-size:15px"> 
				
				<div id="mge"></div>
				<br/>
					<input type='submit' value="<?=($_REQUEST['eId']==''?'Add':'Edit')?>" class="btn btn-success" onclick="yes();" name="Save"  >
					<input type='button' value="No" class="btn btn-success" onclick="nosave();" name="no"  >
					<input type='hidden' value="" id="another" name="another"  >
				</div> 
				
			</table>
            </div>
        </div>
    </div>
</div>
			 
			 		
			
			
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
			
			   
			  <!--<input type='button' value='Another' class="btn btn-success"  onclick="saveornot();">-->
		
			   
			   <?php if($row->id !=''){?>
			   	
		       <!--   <a href="editPO.php?session=<?php echo $_GET['session']; ?>&submenu=<?php echo $_GET['submenu']; ?>" type="button" class="btn btn-info"><i class="fa fa-plus-circle" aria-hidden="true"> Another Purchase Order</i></a> -->
		          <?php                   		 
	                  $sql1 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `doc_type`='".$row->doc_type."' and `id`='".$row->id_doc_type_configuration."' limit 1 ";
	                   $db->query($sql1); 
	                   while($row1 = $db->fetch_object()){ 

	                  		$custom_print_file = $row1->custom_print_file;	                  		 
		                  	if($custom_print_file !=''){
		                  		$print = $custom_print_file;
		                  	}else{
		                  		$print = 'printPO.php';


		                  	}
	                  	} 
	                  		                  	
                  	?>
		        <!--<a href="<?php echo $print; ?>?eId='<?php echo $_GET['eId']; ?>'&session=<?php echo $_GET['session']; ?>&submenu=<?php echo $_GET['submenu']; ?>&action=edit&page=<?php $_REQUEST['page']?>"  type="button" class="btn btn-success"><i class="fa fa-print" aria-hidden="true"> Print</i></a>-->   
	        	
			   <?php } ?>
			 </div>
			 
			 
            </form>			
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>	

  		
<!-- Audit Trail Modal -->
<div class="modal fade" id="auditModal" tabindex="-1" role="dialog" aria-labelledby="auditModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-head">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
               <!-- <h4 class="modal-title" id="roomtypeModalLabel">Rooms Select</h4>  -->
                <label class="modal-title" id="roomtitle1">Alteration History</label>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped">
				<div class="alt-bill"> Bill No - <?php echo $row->mdoc_no ?> </div>
				<thead>
					<tr>
						<th>Details</th>   
					</tr>
				</thead>
				
				<tbody id="roombutton">
					
				</tbody>
			</table>
            </div>
			
            <div class="modal-footer"  style="background-color: #e4e4e4;color: #fff;text-align:center">
               <button type="button" class="btn n-btn" data-dismiss="modal"><i class="far fa-window-close"></i> Close</button> 
            </div>
        </div>
    </div>
</div>
<!-- End Audit trail Modal -->
	
<?php

											$sqlResult1 = "SELECT * FROM ".TBL_ATTRIBUTES." WHERE table_name = 'items_type' AND field_category IN ('Ingredients Items','Both','Other Items') AND id_shop = ".$_SESSION['shop'] ." ";
												$QuerySQL1	=	mysqli_query($connNew,$sqlResult1);
												$list2=array();
													while($sqlRow = mysqli_fetch_object($QuerySQL1)){
												        $list2[] = $sqlRow->id;
														//$string .= $list.',';
													}	print_r($list2);$uarray =array_unique($list2);
													
													
													$input = array_map("unserialize", array_unique(array_map("serialize",$list2)));
											$item_list2 = implode(',',$input);										
											 						 
							                   
											 	 ?>
 		
<?php include_once("../includes/footer.php");?> 
<script type="text/javascript">


	function saveornot(){
		var id_mst_party_supplier = document.getElementById("id_mst_party_supplier").value;
		var submenu = document.getElementById("submenu").value;
		var session = document.getElementById("session").value;
		var eId = document.getElementById("eId").value;
		
		
		if(eId==''){
			 $("#mge").html("You want to Add the Current Records ?");
			//document.getElementById("mge").value = "You want to Add the Current Records ?";
		}else{
			 $("#mge").html("You want to Save the Current Changes of the Records ?");
			//document.getElementById("mge").value = "You want to Save the Current Changes of the Records ?";
		}
		
		if(id_mst_party_supplier == ''){
			window.location.href="editPO.php?submenu="+submenu+"&session="+session;
		}else{
			//alert();
			$('#anotherModal').modal('show');
		}
	}	
	
	function yes(){
		
		var submenu = document.getElementById("submenu").value;
		var session = document.getElementById("session").value;
		$('#anotherModal').modal('hide');
		
		document.getElementById("another").value = "Another";
		
		//window.location.href="editPO.php?submenu="+submenu+"&session="+session;
	}
	
	
	
	function nosave(){
		var submenu = document.getElementById("submenu").value;
		var session = document.getElementById("session").value;
		//alert(session)
		window.location.href="editPO.php?submenu="+submenu+"&session="+session;
	}



function audittrial(clicked_value){ 
var id = document.getElementById("indent_po").value;
		$('#auditModal').modal('show');
		//var table ='table_po';
		var form_name ='Purchase Order';
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
		//alert(sgst_net_amount);
		
		var igst = document.getElementById("igst_net_amount").value;
		var disc_amount_additional = document.getElementById("disc_amount_additional").value;

		var total = Number(net_amount_items)+Number(others_charges)+Number(sgst_net_amount)+Number(cgst)+Number(igst);
		total = Number(total) - Number(disc_amount_additional);
		//Net Amount Fetch
		var adjust_amount =  Math.round(total);
		var adjust_amount1 =  Math.round(total);
		adjust_amount = adjust_amount - total;
		document.getElementById("round_off_amount").value =  adjust_amount.toFixed(2);
		document.getElementById("net_amount").value =  Math.round(adjust_amount1);
	}
	//Charges Subtotal Value Based Calations
	function subtotal_calc(clicked_id){
		//alert();
		var regex = /[+-]?\d+(?:\.\d+)?/g;
		var match = parseInt(regex.exec(clicked_id));
		
		
		
		if(match >=1){
			var others_charges_percent = document.getElementById("others_charges_percent"+match).value;
			var sub_total_items = document.getElementById("sub_total_items").value;
			var others_charges_percent = sub_total_items - (sub_total_items * (others_charges_percent / 100));
			//Amount Fetch
			 document.getElementById("others_charges_amount"+match).value = others_charges_percent;
			 document.getElementById('others_charges_amount'+match).click();
			 
			 document.getElementById("others_charges_sgst_amount"+match).value = 0; 
			 document.getElementById("others_charges_cgst_amount"+match).value = 0; 
			 document.getElementById("others_charges_igst_amount"+match).value = 0;
		}else{
			var others_charges_percent = document.getElementById("others_charges_percent").value;
			var sub_total_items = document.getElementById("sub_total_items").value;
			var others_charges_percent = sub_total_items - (sub_total_items * (others_charges_percent / 100));
			//Amount Fetch
			
			 document.getElementById("others_charges_amount").value = others_charges_percent;
			 document.getElementById('others_charges_amount').click();
			 
			 document.getElementById("others_charges_sgst_amount").value = 0; 
			 document.getElementById("others_charges_cgst_amount").value = 0; 
			 document.getElementById("others_charges_igst_amount").value = 0;
		}
		//Total Amount Adding
	 	net_amount();
	}
	//Percentage Apply
	function apply_percentage(clicked_id){
		var percentage = document.getElementById("discount_percent_items").value;
		var counter1 = document.getElementById("counter1").value;
		
		//Values Apply here
		document.getElementById("discount_percent").value = percentage ;
		document.getElementById('discount_percent').click();
		
		//Percentage Apply
		if(counter1>=1){
			for(var i=1; i<=counter1; i++){
				var value = document.getElementById("item_amount"+i).value;
				if(value>=1){
					document.getElementById("discount_percent"+i).value = percentage ;
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
						 var netamount = (amount * (discount / 100));
						 document.getElementById("others_discount_amount"+match).value = netamount;
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
						 document.getElementById("others_discount_amount").value = netamount; 
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
	//Others Charges Amount Calculation
	//Amount Calculate
	function charges_amount_calc(clicked_id){

//alert(clicked_id);
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
			var type = document.getElementById("type"+match);
			var type = type.options[type.selectedIndex].value;
			 

			if(type == 1){


				document.getElementById("others_charges_sgst_amount"+match).value = 0; 
				 document.getElementById("others_charges_cgst_amount"+match).value = 0; 
				 document.getElementById("others_charges_igst_amount"+match).value = 0; 

				var others_charges_amount = document.getElementById("others_charges_amount"+match).value;
				var discount = document.getElementById("others_charges_percent"+match).value;
		 		 
		 		var netamount = others_charges_amount - (others_charges_amount * (discount / 100));
		 		document.getElementById("total_amount_others"+match).value = netamount;
				 
		 		
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

			}else if(type == 2){
				//Amount Set Here
				 document.getElementById("others_charges_sgst_amount"+match).value = 0; 
				 document.getElementById("others_charges_cgst_amount"+match).value = 0; 
				 document.getElementById("others_charges_igst_amount"+match).value = 0; 

				//Discount Amoun Set
				var others_charges_amount = document.getElementById("others_charges_amount"+match).value;
				var discount = document.getElementById("others_charges_percent"+match).value;
		 		 
		 		var discountnetamount = others_charges_amount - (others_charges_amount * (discount / 100));
		 		document.getElementById("total_amount_others"+match).value = discountnetamount;
		 		

				//Discount Calcualtion
				var others_discount_percent = document.getElementById("others_discount_percent"+match).value;				 
				var discount_amounts = document.getElementById("others_charges_amount"+match).value;
				var discount_netamounts = (discount_amounts * (others_discount_percent / 100));
				document.getElementById("others_discount_amount"+match).value = discount_netamounts; 

				

			}else{

			}
			
				

			
			
			 //Tax Config Section
			//if(others_charges_sgst_percent != '' && others_charges_cgst_percent != ''){
				//SGST
				
				if(others_charges_sgst_percent== 'undefined'){
					//alert('em');
					var others_charges_sgst_percent1 = 0;
				}else{
					//alert(va);
					var others_charges_sgst_percent1 = others_charges_sgst_percent;
				}
				if(others_charges_cgst_percent == 'undefined'){
					var others_charges_cgst_percent1 = 0;
				}else{
					var others_charges_cgst_percent1 = others_charges_cgst_percent;
				}
				
				if(others_charges_igst_percent == 'undefined'){
					var others_charges_igst_percent1 = 0;
				}else{
					var others_charges_igst_percent1 = others_charges_igst_percent;
				}
				
				
				
				var amount = document.getElementById("others_charges_amount"+match).value;
				
				//alert(amount);
				//alert(others_charges_sgst_percent1);
				
				var others_charges_sgst_percent = (amount * (others_charges_sgst_percent1 / 100));
				document.getElementById("others_charges_sgst_amount"+match).value = others_charges_sgst_percent;
				//CGST
				var amount = document.getElementById("others_charges_amount"+match).value;
				var others_charges_cgst_percent = (amount * (others_charges_cgst_percent1 / 100));
				document.getElementById("others_charges_cgst_amount"+match).value = others_charges_cgst_percent;
			//}
			if(others_charges_igst_percent != ''){
				//IGST
				var amount = document.getElementById("others_charges_amount"+match).value;
				var others_charges_igst_percent = (amount * (others_charges_igst_percent1 / 100));
				document.getElementById("others_charges_igst_amount"+match).value = others_charges_igst_percent; 
				//alert('==='+others_charges_igst_percent);
				
				
				//var amount = document.getElementById("others_charges_amount").value;
				//var others_charges_igst_percent = (amount * (others_charges_igst_percent / 100));
				//document.getElementById("others_charges_igst_amount").value = others_charges_igst_percent; 
			}

			//Charges Amount Calculations   		 
				var counter2 = document.getElementById("counter2").value;
			
				
	 			var otherscharges = document.getElementById("others_charges_net_amount1").value;
				
	
	 			var disc_amount_additional1 = document.getElementById("disc_amount_additional1").value;
 
	 				//Calc
		 			var total = 0;  
		 			var discount_total = 0;
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

		 			//Calc 
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
		 				document.getElementById("others_charges_net_amount").value = total; 
		 				//SGST - CGST -IGST
		 				if(ledger_id == 1){
							
						
	////////////////////////////////////////////////////////////////////////					
		if(isNaN(oc_sgst_total) == true ){
			var oc_sgst_total1 = '0';
		}else{
			var oc_sgst_total1 = oc_sgst_total;
		}				
						
		if(isNaN(oc_cgst_toal) == true ){
			var oc_cgst_toal1 = '0';
		}else{
			var oc_cgst_toal1 = oc_cgst_toal;
		}			
						
		if(isNaN(oc_igst_toal) == true ){
			var oc_igst_toal1 = '0';
		}else{
			var oc_igst_toal1 = oc_igst_toal;
		}
						
			 				oc_sgst_total = Number(oc_sgst_total1) + Number(sgst_start);
			 				document.getElementById("oc_sgst_total").value =oc_sgst_total;
			 				document.getElementById("oc_sgst2").value =oc_sgst_total;
							
			 				document.getElementById("sgst_net_amount").value =(Number(oc_sgst_total) + Number(sgst2)).toFixed(2);
			 				//CGST
			 				oc_cgst_toal = Number(oc_cgst_toal1) + Number(cgst_start);
			 				document.getElementById("oc_cgst_total").value =oc_cgst_toal;
			 				document.getElementById("oc_cgst2").value =oc_cgst_toal;
			 				document.getElementById("cgst_net_amount").value =(Number(oc_cgst_toal) + Number(cgst2)).toFixed(2);
			 			}
			 			if(ledger_id == 2){
			 				//IGST
			 				oc_igst_toal = Number(oc_igst_toal) + Number(igst_start);
			 				document.getElementById("oc_igst_total").value =oc_igst_toal;
			 				document.getElementById("oc_igst2").value =oc_igst_toal;
			 				document.getElementById("igst_net_amount").value =(Number(oc_igst_toal) + Number(igst2)).toFixed(2); 
							
							
							//document.getElementById("oc_igst_total").value =igst_start;
			 				//document.getElementById("oc_igst2").value =igst_start;
			 				//document.getElementById("igst_net_amount").value =(Number(igst_start) + Number(igst2)).toFixed(2);
			 			}
		 				//Additional Discount Amount
		 				discount_total = Number(discount_total) + Number(disc_amount_additional1);
		 				document.getElementById("disc_amount_additional").value =discount_total;

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
		 				document.getElementById("others_charges_net_amount1").value =others_charges_amount;
		 				document.getElementById("disc_amount_additional1").value =discountnetamount; 

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
			 				document.getElementById("oc_sgst_total").value =oc_sgst_total;
			 				document.getElementById("oc_sgst2").value =oc_sgst_total;
			 				document.getElementById("sgst_net_amount").value =(Number(oc_sgst_total) + Number(sgst2)).toFixed(2);
			 				//CGST
			 				oc_cgst_toal = Number(oc_cgst_toal) + Number(cgst_start);
			 				document.getElementById("oc_cgst_total").value =oc_cgst_toal;
			 				document.getElementById("oc_cgst2").value =oc_cgst_toal;
			 				document.getElementById("cgst_net_amount").value =(Number(oc_cgst_toal) + Number(cgst2)).toFixed(2);
			 			}
			 			if(ledger_id == 2){ 
			 				//IGST
			 				oc_igst_toal = Number(oc_igst_toal) + Number(igst_start);
			 				document.getElementById("oc_igst_total").value =oc_igst_toal;
			 				document.getElementById("oc_igst2").value =oc_igst_toal;
			 				document.getElementById("igst_net_amount").value =(Number(oc_igst_toal) + Number(igst2)).toFixed(2);
			 			} 
			 			
		 				//Additional Discount Amount
		 				discount_total = Number(discount_total) + Number(disc_amount_additional1);
		 				document.getElementById("disc_amount_additional").value =discount_total; 
		 				

			 			}
		 			}  
		}else{ 

			//Others Select Here
			var type = document.getElementById("type");
			var type = type.options[type.selectedIndex].value;
		
		
		//alert(type);
		
			if(type == 1){

				var others_charges_amount = document.getElementById("others_charges_amount").value;
				var discount = document.getElementById("others_charges_percent").value;
		 		 
		 		var netamount = others_charges_amount - (others_charges_amount * (discount / 100));
		 		document.getElementById("total_amount_others").value = netamount;
		 		document.getElementById("others_charges_net_amount1").value =others_charges_amount;
 
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
				 document.getElementById("others_charges_sgst_amount").value = 0; 
				 document.getElementById("others_charges_cgst_amount").value = 0; 
				 document.getElementById("others_charges_igst_amount").value = 0; 

				//Discount Amount
				var others_charges_amount = document.getElementById("others_charges_amount").value;
				var discount = document.getElementById("others_charges_percent").value;
		 		 
		 		var discountnetamount = others_charges_amount - (others_charges_amount * (discount / 100));
		 		document.getElementById("total_amount_others").value = discountnetamount;
		 		document.getElementById("disc_amount_additional1").value =discountnetamount;

			//Discount Calcualtion
				var others_discount_percent = document.getElementById("others_discount_percent").value; 
				var discountamount = document.getElementById("others_charges_amount").value;
				var discountnetamount = (discountamount * (others_discount_percent / 100));
				document.getElementById("others_discount_amount").value = discountnetamount; 
				 
			}else {
			
			} 
if(others_charges_sgst_percent=='undefined'){
	var others_charges_sgst_percent = '0';
}else{
	var others_charges_sgst_percent = others_charges_sgst_percent;
}

//alert(osg);	


if(others_charges_cgst_percent=='undefined'){
	var others_charges_cgst_percent = 0;
}else{
	var others_charges_cgst_percent = others_charges_cgst_percent;
}
if(others_charges_igst_percent=='undefined'){
	var others_charges_igst_percent = 0;
}else{
	var others_charges_igst_percent = others_charges_igst_percent;
}
			//Tax Configurations
			//Tax Config Section
			//if(others_charges_sgst_percent != '' && others_charges_cgst_percent != ''){
				//SGST 
			//alert();
				var amount = document.getElementById("others_charges_amount").value;
				var others_charges_sgst_percent = (amount * (others_charges_sgst_percent / 100));
				document.getElementById("others_charges_sgst_amount").value = others_charges_sgst_percent;
				//CGST
				var amount = document.getElementById("others_charges_amount").value;
				var others_charges_cgst_percent = (amount * (others_charges_cgst_percent / 100));
				document.getElementById("others_charges_cgst_amount").value = others_charges_cgst_percent;
			//}
			//if(others_charges_igst_percent != ''){
				//IGST
				// --
				
				var amount = document.getElementById("others_charges_amount").value;
				var others_charges_igst_percent = (amount * (others_charges_igst_percent / 100));
				document.getElementById("others_charges_igst_amount").value = others_charges_igst_percent; 

			//}

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
		 				document.getElementById("others_charges_net_amount").value =total;
		 				if(ledger_id == 1){
			 				//SGST - CGST -IGST
			 				oc_sgst_total = Number(oc_sgst_total) + Number(sgst_start);
			 				document.getElementById("oc_sgst_total").value =oc_sgst_total;
			 				document.getElementById("oc_sgst2").value =oc_sgst_total;
			 				document.getElementById("sgst_net_amount").value =(Number(oc_sgst_total) + Number(sgst2)).toFixed(2);
			 				//CGST
			 				oc_cgst_toal = Number(oc_cgst_toal) + Number(cgst_start);
			 				document.getElementById("oc_cgst_total").value =oc_cgst_toal;
			 				document.getElementById("oc_cgst2").value =oc_cgst_toal;
			 				document.getElementById("cgst_net_amount").value =(Number(oc_cgst_toal) + Number(cgst2)).toFixed(2);
			 			}
			 			if(ledger_id == 2){ 
			 				//IGST
			 				oc_igst_toal = Number(oc_igst_toal) + Number(igst_start);
			 				document.getElementById("oc_igst_total").value =oc_igst_toal;
			 				document.getElementById("oc_igst2").value =oc_igst_toal;
			 				document.getElementById("igst_net_amount").value =(Number(oc_igst_toal) + Number(igst2)).toFixed(2);
			 			}

		 			}else{

		 				document.getElementById("others_charges_net_amount").value =ocharges;
		 				if(ledger_id == 1){
			 				//SGST
			 				document.getElementById("oc_sgst_total").value = sgst_start;
			 				document.getElementById("oc_sgst2").value = sgst_start;
			 				document.getElementById("sgst_net_amount").value =(Number(sgst_start) + Number(sgst2)).toFixed(2);
			 				//CGST
			 				document.getElementById("oc_cgst_total").value =cgst_start;
			 				document.getElementById("oc_cgst2").value =cgst_start;
			 				document.getElementById("cgst_net_amount").value =(Number(cgst_start) + Number(cgst2)).toFixed(2);
			 			}
			 			if(ledger_id == 2){ 
			 				//IGST
			 				document.getElementById("oc_igst_total").value =igst_start;
			 				document.getElementById("oc_igst2").value =igst_start;
			 				document.getElementById("igst_net_amount").value =(Number(igst_start) + Number(igst2)).toFixed(2);
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
			
	 	}//persentage
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
						
					if(match >=1){ 
					
if(charges_account=='4'){
	document.getElementById("type"+match).value = '1';
}else if(charges_account=='6'){
	document.getElementById("type"+match).value = '2';
}


var type = document.getElementById("type"+match);
var type = type.options[type.selectedIndex].value;

if(type == 1){
$("#others"+match).show();
$("#others_charges_percent"+match).show();
$("#discounts"+match).hide();
}else{
$("#others"+match).hide();
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
						 var sgst = mydata['sgst'];
						 var amount = document.getElementById("others_charges_amount"+match).value;
						 
						 if(sgst==undefined){
							  var sgst1 = 0;
						 }else{
							  var sgst1 = mydata['sgst'];
						 }
						 
						
						 
						 var sgstnetamount = (amount * (sgst1 / 100));
						 document.getElementById("others_charges_sgst_amount"+match).value = sgstnetamount;
						 //CGST
						 var cgst = mydata['cgst'];
						 var amount = document.getElementById("others_charges_amount"+match).value;
						 
						 
						 if(cgst==undefined){
							  var cgst1 = 0;
						 }else{
							  var cgst1 = mydata['cgst'];
						 }
						 var cgstnetamount = (amount * (cgst1 / 100));
						 document.getElementById("others_charges_cgst_amount"+match).value =cgstnetamount;
						 //IGST 
						 var igst = mydata['igst'];
						 var amount = document.getElementById("others_charges_amount"+match).value;
						 
						 
						 if(igst==undefined){
							  var igst1 = 0;
						 }else{
							  var igst1 = mydata['igst'];
						 }
						 var igstnetamount = (amount * (igst1 / 100));
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
				 				document.getElementById("oc_sgst_total").value =sgstamount; 
				 				document.getElementById("oc_sgst2").value =sgstamount;
				 				document.getElementById("sgst_net_amount").value =(Number(sgstamount)+Number(sgst2)).toFixed(2); 
				 				//CGST Total Section 
				 				document.getElementById("oc_cgst_total").value =cgstamount; 
				 				document.getElementById("oc_cgst2").value =cgstamount;
				 				document.getElementById("cgst_net_amount").value =(Number(cgstamount)+Number(cgst2)).toFixed(2);  
				 				//IGST  
				 				document.getElementById("oc_igst_total").value =igstamount; 
				 				document.getElementById("oc_igst2").value =igstamount;
				 				document.getElementById("igst_net_amount").value =(Number(igstamount)+Number(igst2)).toFixed(2);
				 			 	

			 			} 

					}else{
						
	
if(charges_account=='4'){
	document.getElementById("type").value = '1';
}else if(charges_account=='6'){
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
		//type_funt('type');
	 	net_amount();
	}
	//Tax Config PO
	function po_locals(clicked_id){		

		var regex = /[+-]?\d+(?:\.\d+)?/g;
		var match = parseInt(regex.exec(clicked_id));
		//console.log(match);
		
		//alert(match);
	var ledger_id =  document.getElementById("ledger_id").value;
	if(ledger_id == '1'){	
		if(match >=1){
			var id_mst_charges_purchase_local = document.getElementById("id_mst_charges_purchase_local"+match);
			var id_mst_charges_purchase_local = id_mst_charges_purchase_local.options[id_mst_charges_purchase_local.selectedIndex].value;
			$('#s_amount'+match).show();
			$('#c_amount'+match).show();

		}else{

			var id_mst_charges_purchase_local = document.getElementById("id_mst_charges_purchase_local");
			var id_mst_charges_purchase_local = id_mst_charges_purchase_local.options[id_mst_charges_purchase_local.selectedIndex].value; 
			$('#s_amount').show();
			$('#c_amount').show();
		} 
		var id_mst_charges_purchase=id_mst_charges_purchase_local
	}else{
		if(match >=1){
			var id_mst_charges_purchase_interstate = document.getElementById("id_mst_charges_purchase_interstate"+match);
			var id_mst_charges_purchase_interstate = id_mst_charges_purchase_interstate.options[id_mst_charges_purchase_interstate.selectedIndex].value;
			$('#i_amount'+match).show();  
		}else{

			var id_mst_charges_purchase_interstate = document.getElementById("id_mst_charges_purchase_interstate");
			var id_mst_charges_purchase_interstate = id_mst_charges_purchase_interstate.options[id_mst_charges_purchase_interstate.selectedIndex].value; 
			$('#i_amount').show();
		} 
		var id_mst_charges_purchase=id_mst_charges_purchase_interstate
	}

		var po  = 'locals';


if(ledger_id == '1'){
			                 			var po = 'locals';
			                 		}else{
			                 			var po  = 'interstate';
			                 		}
		$.ajax({
				type: "POST",
				url: "../ajax/TaxconfigsPO.php",
				data:{id_mst_charges_purchase_local:id_mst_charges_purchase,po:po},
				dataType: "html", 	
				success: function(data){
					if(ledger_id == '1'){  
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
					
				}else{//INTERSATATE
					
					
					  
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
				
				
				}//Success==
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
var ledger_id =  document.getElementById("ledger_id").value;

if(ledger_id == '1'){
			                 			var po = 'locals';
			                 		}else{
			                 			var po  = 'interstate';
			                 		}
		$.ajax({
				type: "POST",
				url: "../ajax/TaxconfigsPO.php",
				data:{id_mst_charges_purchase_local:id_mst_charges_purchase_interstate,po:po},
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

		var regex = /[+-]?\d+(?:\.\d+)?/g;
		var match = parseInt(regex.exec(clicked_id));

		var sgst2 = document.getElementById("oc_sgst2").value;
		var cgst2 = document.getElementById("oc_cgst2").value;
		var igst2 = document.getElementById("oc_igst2").value; 
		  

		if(match >=1){
			var qty = document.getElementById("qty"+match).value;
			var rate = document.getElementById("rate_per_main_unit"+match).value;
			var discount = document.getElementById("discount_percent"+match).value;
	 		
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
		 		document.getElementById("item_amount"+match).value = netamount;
		 		document.getElementById("item_amount_before_discount"+match).value = totals1;
		 	}else{

		 		if(main_unit == transaction_unit){
		 			var totals = (qty*1000) * rate;
			 		var totals1 = (qty*1000) * rate;
			 		var netamount = totals - (totals * (discount / 100));
			 		document.getElementById("item_amount"+match).value = netamount;
			 		document.getElementById("item_amount_before_discount"+match).value = totals1;
		 		}else{

					var totals = (qty/1000) * rate;
			 		var totals1 = (qty/1000) * rate;
			 		var netamount = totals - (totals * (discount / 100));
			 		document.getElementById("item_amount"+match).value = netamount;
			 		document.getElementById("item_amount_before_discount"+match).value = totals1;
			 	}
		 	}

	 		//Tax Configuration
	 		var item_sgst_percent1 = document.getElementById("item_sgst_percent"+match).value;
			var item_cgst_percent1 = document.getElementById("item_cgst_percent"+match).value;
			var item_igst_percent1 = document.getElementById("item_igst_percent"+match).value;
			/*additional taxes*/
			/*var item_vat_percent = document.getElementById("item_vat_percent"+match).value;
			var item_cess_percent = document.getElementById("item_cess_percent"+match).value;
			var item_surcharge_percent = document.getElementById("item_surcharge_percent"+match).value;*/
			
			
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
			
			

			//Tax Config Section
			if(item_sgst_percent1 != '' && item_cgst_percent1 != ''){
				//SGST
				var amount = document.getElementById("item_amount"+match).value;
				var item_sgst_percent = (amount * (item_sgst_percent / 100));
				document.getElementById("item_sgst_amount"+match).value = item_sgst_percent;
				if(item_sgst_percent>0){
					$("#s_amount"+match).html(' SGST : ' + Number(item_sgst_percent).toFixed(2));
				}
				//CGST
				var amount = document.getElementById("item_amount"+match).value;
				var item_cgst_percent = (amount * (item_cgst_percent / 100));
				document.getElementById("item_cgst_amount"+match).value = item_cgst_percent;
				if(item_cgst_percent>0){
					$("#c_amount"+match).html(' CGST : ' + Number(item_cgst_percent).toFixed(2));
				}
			}
			if(item_igst_percent1 != ''){
				//IGST
				var amount = document.getElementById("item_amount"+match).value;
				var item_igst_percent = (amount * (item_igst_percent / 100));
				document.getElementById("item_igst_amount"+match).value = item_igst_percent; 
				$("#i_amount"+match).html('IGST: ' + Number(item_igst_percent).toFixed(2));
			}

			/*additional taxes*/
			/*if(item_vat_percent != ''){
				//VAT
				var amount = document.getElementById("item_amount"+match).value;
				var item_vat_percent = (amount * (item_vat_percent / 100));
				document.getElementById("item_vat_amount"+match).value = item_vat_percent; 
				$("#vat_amount"+match).html(' VAT : ' + item_igst_percent);
			}

			if(item_surcharge_percent != ''){
				//SURCHARGE
				var amount = document.getElementById("item_amount"+match).value;
				var item_surcharge_percent = (amount * (item_surcharge_percent / 100));
				document.getElementById("item_surcharge_amount"+match).value = item_surcharge_percent; 
				$("#surcharge_amount"+match).html(' SURCHARGE : ' + item_igst_percent);
			}

			if(item_cess_percent != ''){
				//cess
				var amount = document.getElementById("item_amount"+match).value;
				var item_cess_percent = (amount * (item_cess_percent / 100));
				document.getElementById("item_cess_amount"+match).value = item_cess_percent; 
				$("#cess_amount"+match).html(' CESS : ' + item_igst_percent);
			}*/

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
	 				var discounted_price = (totalrate / 100) * discount;
	 			//Discounted Price					
					var total_discount_items = document.getElementById("total_discount_items1").value; 

	 			//Calc
	 			

	 			//Calc 
				
		//alert(counter1);		
	 			if(counter1 >=1){

	 				var total = 0; 
	 				var discount_total = 0;
	 				var sgst_total = 0; 
		 			var cgst_total = 0; 
		 			var igst_total = 0;

		 			var vat_total = 0; 
		 			var cess_total = 0; 
		 			var surcharge_total = 0;

		 			var total_rate = 0;

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
	 				total = Number(total) + Number(sub);
	 				document.getElementById("net_amount_items").value =total;
	 				//Total Rate Section
	 				total_rate = Number(total_rate) + Number(sub_1);
	 				document.getElementById("sub_total_items").value =total_rate;
	 				//Discount Total Section
	 				discount_total = Number(discount_total) + Number(total_discount_items);
	 				document.getElementById("total_discount_items").value =(discount_total).toFixed(2);

	 				//SGST
	 				sgst_total = Number(sgst_total) + Number(sgst_first);
	 				document.getElementById("sgst_net_amount").value =sgst_total;
	 				document.getElementById("sgst2").value =sgst_total;
	 				document.getElementById("sgst_net_amount").value =(Number(sgst_total) + Number(sgst2)).toFixed(2);
	 				//CGST
	 				cgst_total = Number(cgst_total) + Number(cgst_first);
	 				document.getElementById("cgst_net_amount").value =cgst_total;
	 				document.getElementById("cgst2").value =cgst_total;
	 				document.getElementById("cgst_net_amount").value =(Number(cgst_total) + Number(cgst2)).toFixed(2);
	 				//IGST
	 				igst_total = Number(igst_total) + Number(igst_first);
	 				document.getElementById("igst_net_amount").value =igst_total;
	 				document.getElementById("igst2").value =igst_total;
	 				document.getElementById("igst_net_amount").value =(Number(igst_total) + Number(igst2)).toFixed(2);

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
					document.getElementById("net_amount_items1").value =netamount;
					document.getElementById("sub_total_items1").value =totals1;
					document.getElementById("total_discount_items1").value =discounted_price;
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
		 				document.getElementById("net_amount_items").value =total;
		 				//Total Rate
		 				total_rate = Number(total_rate) + Number(sub_1);
		 				document.getElementById("sub_total_items").value =total_rate;

		 				//Discount Total Section
		 				discount_total = Number(discount_total) + Number(total_discount_items);
		 				document.getElementById("total_discount_items").value =(discount_total).toFixed(2);

		 				//SGST
		 				sgst_total = Number(sgst_total) + Number(sgst_first);
		 				document.getElementById("sgst_net_amount").value =sgst_total;
		 				document.getElementById("sgst2").value =sgst_total;
		 				document.getElementById("sgst_net_amount").value =(Number(sgst_total) + Number(sgst2)).toFixed(2);
		 				//CGST
		 				cgst_total = Number(cgst_total) + Number(cgst_first);
		 				document.getElementById("cgst_net_amount").value =cgst_total;
		 				document.getElementById("cgst2").value =cgst_total;
						document.getElementById("cgst_net_amount").value =(Number(cgst_total) + Number(cgst2)).toFixed(2);
		 				//IGST
		 				igst_total = Number(igst_total) + Number(igst_first);
		 				document.getElementById("igst_net_amount").value =igst_total;
		 				document.getElementById("igst2").value =igst_total;
						document.getElementById("igst_net_amount").value =Number(igst_total) + Number(igst2);

		 			}
	 			}							

		}else{
//var regex1 = /[+-]?\d+(?:\.\d+)?/g;
			var qty= document.getElementById("qty").value;
			var rate = document.getElementById("rate_per_main_unit").value;
			var discount = document.getElementById("discount_percent").value;
			//Unit Select Here 
			
			
			//alert(rate);
			
			if(rate==''){
				//document.getElementById("rate_per_main_unit").value = 0;
			}
			
			var per_unit = document.getElementById("per_unit");
			var per_unit = per_unit.options[per_unit.selectedIndex].value;

			var transaction_unit = document.getElementById("transaction_unit");
			var transaction_unit = transaction_unit.options[transaction_unit.selectedIndex].value;
			var main_unit = document.getElementById("main_unit").value 

			if(per_unit == transaction_unit){

		 		var totals = qty * rate;
		 		var totals1 = qty * rate;
		 		var netamount = totals - (totals * (discount / 100));
		 		document.getElementById("item_amount").value = netamount;
		 		document.getElementById("item_amount_before_discount").value = totals1;
		 	}
		 	else{

		 		if(main_unit == transaction_unit){
		 			var totals = (qty*1000) * rate;
			 		var totals1 = (qty*1000) * rate;
			 		var netamount = totals - (totals * (discount / 100));
			 		document.getElementById("item_amount").value = netamount;
			 		document.getElementById("item_amount_before_discount").value = totals1;
		 		}else{
					var totals = (qty/1000) * rate;
			 		var totals1 = (qty/1000) * rate;
			 		var netamount = totals - (totals * (discount / 100));
			 		document.getElementById("item_amount").value = netamount;
			 		document.getElementById("item_amount_before_discount").value = totals1;
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
				var item_sgst_percent = (amount * (item_sgst_percent / 100));
				document.getElementById("item_sgst_amount").value = item_sgst_percent;
				$("#s_amount").html('SGST: ' + Number(item_sgst_percent).toFixed(2));
				//CGST
				var amount = document.getElementById("item_amount").value;
				//alert(item_cgst_percent);
				var item_cgst_percent = (amount * (item_cgst_percent / 100));
				document.getElementById("item_cgst_amount").value = item_cgst_percent;
				$("#c_amount").html('CGST: ' + Number(item_cgst_percent).toFixed(2));  
			}
			if(item_igst_percent1 != ''){

				//IGST
				var amount = document.getElementById("item_amount").value;
				var item_igst_percent = (amount * (item_igst_percent / 100));
				document.getElementById("item_igst_amount").value = item_igst_percent;
				$("#i_amount").html('IGST: ' +  Number(item_igst_percent).toFixed(2));
 
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
					document.getElementById("net_amount_items1").value =totals1;
					var sub_1 = document.getElementById("net_amount_items1").value;
					//Total Rate
					document.getElementById("sub_total_items1").value =netamount;
					var sub = document.getElementById("sub_total_items1").value;
					//TAxConfig
					 document.getElementById("sgst1").value = item_sgst_percent;
					 document.getElementById("cgst1").value = item_cgst_percent;
					 document.getElementById("igst1").value = item_igst_percent;

//alert(item_sgst_percent);


					//SGST - CGST
					var sgst_first = document.getElementById("sgst1").value;
					var cgst_first = document.getElementById("cgst1").value;
					//IGST
					var igst_first = document.getElementById("igst1").value;

					//Discounted Price
					document.getElementById("total_discount_items1").value =discounted_price;
					var total_discount_items = document.getElementById("total_discount_items1").value;

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
		 				document.getElementById("total_discount_items").value =(discount_total).toFixed(2);
		 				//SGST
		 				sgst_total = Number(sgst_total) + Number(sgst_first);
		 				document.getElementById("sgst_net_amount").value =sgst_total;
		 				document.getElementById("sgst2").value =sgst_total;
		 				document.getElementById("sgst_net_amount").value =(Number(sgst_total) + Number(sgst2)).toFixed(2);
		 				//CGST
		 				cgst_total = Number(cgst_total) + Number(cgst_first);
		 				document.getElementById("cgst_net_amount").value =cgst_total;
		 				document.getElementById("cgst2").value =cgst_total;
						document.getElementById("cgst_net_amount").value =(Number(cgst_total) + Number(cgst2)).toFixed(2);
		 				//IGST
		 				igst_total = Number(igst_total) + Number(igst_first);
		 				document.getElementById("igst_net_amount").value =igst_total;
		 				document.getElementById("igst2").value =igst_total;
						document.getElementById("igst_net_amount").value =(Number(igst_total) + Number(igst2)).toFixed(2);

		 			}else{
		 				document.getElementById("sub_total_items").value =sub_1;
		 				document.getElementById("net_amount_items").value =sub;
		 				document.getElementById("total_discount_items").value =(discounted_price).toFixed(2);
		 				//SGST-CGST-IGST
		 				document.getElementById("sgst_net_amount").value =sgst_first;
		 				document.getElementById("sgst2").value =sgst_first;
		 				document.getElementById("sgst_net_amount").value =(Number(sgst_first) + Number(sgst2)).toFixed(2);
		 				//CGST
		 				document.getElementById("cgst_net_amount").value =cgst_first;
		 				document.getElementById("cgst2").value =cgst_first;
						document.getElementById("cgst_net_amount").value =(Number(cgst_first) + Number(cgst2)).toFixed(2);
						//IGST
		 				document.getElementById("igst_net_amount").value =igst_first;
		 				document.getElementById("igst2").value =igst_first;
						document.getElementById("igst_net_amount").value =(Number(igst_first) + Number(igst2)).toFixed(2);

		 			}
	 			}		


	 	}
	 	//Total Amount Adding
	 	net_amount();

	}
	
	
	
	//STEP-1
	
	
	//Popup Window Data Get Here
	function dismiss(){
		var checkbox = document.getElementById("checkbox").checked;
		if(checkbox == true){
	 	    $("#checkbox").prop("checked", false);
	 	}
	 	document.getElementById('myInput').value='';
	}
	
	

function po(){
	
		var checkbox = document.getElementById("checkbox").checked;
		if(checkbox == true){
	 	    $("#checkbox").prop("checked", false);
	 	}

		var wcounts = $("#wcounts").val();
		var counter1 = $("#counter1").val(); 		
		var wmatch = $("#wmatch").val();
		 
		if(wmatch == counter1) {

			if(counter1 == 0){
				var loopcounting = 0;
			}else{
				var loopcounting = counter1;
			} 

			var count = 0;

			 
			for(var i = 1; i <= wcounts; i++){
				
				var wselect = $("#wselect"+i).val(); 		 
				if(wselect >=1 && loopcounting == 0){
					//Widnow Form Date Get Here
					var wpop = $("#wpop"+i).val();
					var wid = $("#wid"+i).val(); 
					var witemid = $("#witemid"+i).val();  			 
					 
					var witem_code = $("#witem_code"+i).val();  
					var witem_description = $("#witem_description"+i).val();  
					var windent_qty = $("#windent_qty"+i).val();  
					var wmain_unit = $("#wmain_unit"+i).val(); 
					var walt_unit = $("#walt_unit"+i).val(); 
					var wconversion_qty = $("#wconversion_qty"+i).val(); 
					var wbalance = $("#wbalance"+i).val(); 
					//Table Row Date Fetch Here  
					$("#id_inv_indent").val(wpop);
					$("#id_inv_indent").change();
					
					$("#id_inv_indent_details").val(wid);
					$("#item_code").val(witem_code) ;
					$("#id_inv_items").val(witemid) ;
					$("#item_description").val(witem_description);
					$("#qty").val(wbalance); 
					$("#main_unit").val(wmain_unit); 
					$("#alt_unit").val(walt_unit); 
					$("#conver_rate_per_unit").val(wconversion_qty); 

					$("#transaction_unit").html("<option value='"+wmain_unit+"' selected='selected'>" + wmain_unit + "</option>"+"<option value='"+walt_unit+"'>" + walt_unit + "</option>"); 

					$("#per_unit").html("<option value='"+wmain_unit+"' selected='selected'>" + wmain_unit + "</option>"+"<option value='"+walt_unit+"'>" + walt_unit + "</option>"); 				 

					 $('#qty').click();  
					//Form Data Empty
					$("#wselect"+i).val('');
					loopcounting = loopcounting + 1;  
					count = count + 1;

				}else if(wselect >=1 && loopcounting>= 1){ 
					// //Button Click Here

					if(count != 0){
					 	document.getElementById('addrow1').click();
					}
					 var counter1 = $("#counter1").val(); 
					 

					//Widnow Form Date Get Here
					var wpop = $("#wpop"+i).val();
					var wid = $("#wid"+i).val();
					var witemid = $("#witemid"+i).val(); 
					var witem_code = $("#witem_code"+i).val(); 
					var witem_description = $("#witem_description"+i).val();
					var windent_qty = $("#windent_qty"+i).val();   
					var wmain_unit = $("#wmain_unit"+i).val(); 
					var walt_unit = $("#walt_unit"+i).val();
					var wconversion_qty = $("#wconversion_qty"+i).val();
					var wbalance = $("#wbalance"+i).val();
					 
					//Table Row Date Fetch Here  
					$("#id_inv_indent"+counter1).val(wpop);
					$("#id_inv_indent"+counter1).change();
					$("#id_inv_indent_details"+counter1).val(wid)  ;
					$("#item_code"+counter1).val(witem_code);
					$("#id_inv_items"+counter1).val(witemid);
					$("#item_description"+counter1).val(witem_description);
					$("#qty"+counter1).val(wbalance);
					$("#main_unit"+counter1).val(wmain_unit)  ; 
					$("#alt_unit"+counter1).val(walt_unit) ;
					$("#conver_rate_per_unit"+counter1).val(wconversion_qty);
					$("#transaction_unit"+counter1).html("<option value='"+wmain_unit+"' selected='selected'>" + wmain_unit + "</option>"+"<option value='"+walt_unit+"'>" + walt_unit + "</option>") ; 
					$("#per_unit"+counter1).html("<option value='"+wmain_unit+"' selected='selected'>" + wmain_unit + "</option>"+"<option value='"+walt_unit+"'>" + walt_unit + "</option>");  

						
					 $('#qty'+counter1).click();
					//Form Data Empty
					$("#wselect"+i).val('');
					loopcounting = loopcounting + 1;
					count = count +1;
					/*alert('intersect');
					var ledger_id =  document.getElementById("ledger_id").value; 
						if(ledger_id==1){
						fetchCharges(id_inv_items_po,'id_mst_charges_purchase_local'+counter1, counter1);
						}else{
						fetchCharges(id_inv_items_po,'id_mst_charges_purchase_interstate'+counter1, counter1);
						}*/
					fetchCharges($('#id_inv_items'+counter1).val(),'id_mst_charges_purchase_local'+counter1, counter1);

				}else{

				}
			}
		}else if(wmatch != counter1) {
			if(wmatch == 0){

				for(var i = 1; i <= wcounts; i++){

					var wselect = $("#wselect"+i).val(); 		 
					if(wselect >=1){
						//Widnow Form Date Get Here
						var wpop = $("#wpop"+i).val();
						var wid = $("#wid"+i).val();  			 
						var witemid = $("#witemid"+i).val();  			 
						 
						var witem_code = $("#witem_code"+i).val();  
						var witem_description = $("#witem_description"+i).val();  
						var windent_qty = $("#windent_qty"+i).val();  
						var wmain_unit = $("#wmain_unit"+i).val(); 
						var walt_unit = $("#walt_unit"+i).val();
						var wconversion_qty = $("#wconversion_qty"+i).val(); 
						var wbalance = $("#wbalance"+i).val();
						//Table Row Date Fetch Here  
						$("#id_inv_indent").val(wpop).change();
						$("#id_inv_indent_details").val(wid);
						$("#id_inv_items").val(witemid);
						$("#item_code").val(witem_code);
						$("#item_description").val(witem_description);
						$("#qty").val(wbalance);
						$("#main_unit").val(wmain_unit); 
						$("#alt_unit").val(walt_unit); 
						$("#conver_rate_per_unit").val(wconversion_qty) ; 

						document.getElementById("transaction_unit").innerHTML = "<option value='"+wmain_unit+"' selected='selected'>" + wmain_unit + "</option>"+"<option value='"+walt_unit+"'>" + walt_unit + "</option>"; 

						$("#per_unit").html("<option value='"+wmain_unit+"' selected='selected'>" + wmain_unit + "</option>"+"<option value='"+walt_unit+"'>" + walt_unit + "</option>")  ; 

						$('#qty').click();				 

						//Form Data Empty
						$("#wselect"+i).val('');
						loopcounting = loopcounting + 1;  
						count = count + 1;

					}
				}

			}else if(wmatch >=1){
				for(var i = 1; i <= wcounts; i++){

					var wselect = $("#wselect"+i).val(); 		 
					if(wselect >=1){
						//Widnow Form Date Get Here 
						var wpop = $("#wpop"+i).val();
						var wid = $("#wid"+i).val();
						var witemid = $("#witemid"+i).val(); 
						var witem_code = $("#witem_code"+i).val(); 
						var witem_description = $("#witem_description"+i).val();
						var windent_qty = $("#windent_qty"+i).val();   
						var wmain_unit = $("#wmain_unit"+i).val(); 
						var walt_unit = $("#walt_unit"+i).val();
						var wconversion_qty = $("#wconversion_qty"+i).val();
						var wbalance = $("#wbalance"+i).val();
						 
						//Table Row Date Fetch Here  
						$("#id_inv_indent"+wmatch).val(wpop);
						$("#id_inv_indent"+wmatch).change();
						$("#id_inv_indent_details"+wmatch).val(wid);
						$("#item_code"+wmatch).val(witem_code);
						$("#id_inv_items"+wmatch).val(witemid) ;
						$("#item_description"+wmatch).val(witem_description);
						$("#qty"+wmatch).val(wbalance);
						$("#main_unit"+wmatch).val(wmain_unit); 
						$("#alt_unit"+wmatch).val(walt_unit);
						$("#conver_rate_per_unit"+wmatch).val(wconversion_qty);
						$("#transaction_unit"+wmatch).html("<option value='"+wmain_unit+"' selected='selected'>" + wmain_unit + "</option>"+"<option value='"+walt_unit+"'>" + walt_unit + "</option>");

						$("#per_unit"+wmatch).html("<option value='"+wmain_unit+"' selected='selected'>" + wmain_unit + "</option>"+"<option value='"+walt_unit+"'>" + walt_unit + "</option>");  

					 $('#qty'+wmatch).click();

					//Form Data Empty
					$("#wselect"+i).val('');

					}
				}
			}else{}
		}
		// document.getElementById("doc_date").disabled = true;
		// document.getElementById("id_mst_party_supplier").disabled = true; 
		//Total Amount Adding
		let itemId =$('#id_inv_items').val();
		fetchCharges(itemId,'id_mst_charges_purchase_local');
	 	net_amount(); 
	}
</script> 
<script type="text/javascript">

	//Ledger
	 

	//Charges Select
	function type_funt(clicked_id){
//alert(clicked_id);
		var regex = /[+-]?\d+(?:\.\d+)?/g;
		var match = parseInt(regex.exec(clicked_id));

		if(match >=1){
//alert();
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
	//alert('null');		
			var type = document.getElementById("type");
		    var type = type.options[type.selectedIndex].value;
//alert('null');

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
	
	
/*$(document).ready(function (){
		$('#checkbox').click();
	}); */
		
	

	//Popup Show Section
  	function popupshow(clicked_id){
  		
 		var checkbox = document.getElementById("checkbox").checked;

  		var regex = /[+-]?\d+(?:\.\d+)?/g;
		var match = parseInt(regex.exec(clicked_id));
		var myArray = new Array();
		var counter1 = $("#counter1").val();

		

		if(isNaN(parseInt(match)) == true){
			match = '0';
		}else{}
		if(counter1 == 0){
	   		myArray[0] = 0;	   		 
	    } 
	 	for(var i=0; i<=counter1; i++){
	 		if(i == 0){
	 			var id_inv_details = $("#id_inv_indent_details").val(); 
		 			myArray[i] = id_inv_details;
		 			
	 		}else{
			 	var id_inv_details = $("#id_inv_indent_details"+i).val();
	 			var value = $("#item_amount"+i).val();
	 			if(value >= 0){
			 		myArray[i] = id_inv_details;
			 	}
			}
		} 
		  
		
		if(match >=1){
			var id_inv_indent = $("#id_inv_indent"+match).val();
			
	    	//var id_inv_indent = id_inv_indent.options[id_inv_indent.selectedIndex].value; 
		}else{

			var id_inv_indent = $("#id_inv_indent").val();
	    	//var id_inv_indent = id_inv_indent.options[id_inv_indent.selectedIndex].value;
		}
		if(id_inv_indent == 'na'){
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
			//console.log(id_inv_indent);
		   // Pass Ajax Data
		    $.ajax({
					type: "POST",
					url: "../ajax/PopupdatashowPO.php",
					data:{id_inv_indent:id_inv_indent, array:myArray,counter1:counter1,match:match},
					dataType: "html", 	
					success: function(data){  

						$("#myTables").html(data); 
						$('#myInput').val('');
					}
			});  
	 	    
	 	    document.getElementById('config_button').click(); 
			//$('#checkbox').click();
	 	     
	 	}
 	    //Total Amount Adding
	 	net_amount(); 
  	}
  	//
	
	
	
	
	
  	function popupshow_checkbox(clicked_id){
		
 		var checkbox = document.getElementById("checkbox").checked;
		//alert(checkbox);
		
 		if(checkbox == true){
 			var checkbox = 1;
 		}else{
 			var checkbox = 0;
 		}
 		
		//var regex = /[+-]?\d+(?:\.\d+)?/g;
		//var match = parseInt(regex.exec(clicked_id));
		var myArray = new Array();
		var counter1 = $("#counter1").val();
		match = counter1;
		if(isNaN(parseInt(match)) == true){
			match = '0';
		}else{}
		if(counter1 == 0){
	   		myArray[0] = 0;	   		 
	    } 
	 	for(var i=0; i<=counter1; i++){
	 		if(i == 0){
	 			var id_inv_details = $("#id_inv_indent_details").val(); 
		 			myArray[i] = id_inv_details;
		 			
	 		}else{
			 	var id_inv_details = $("#id_inv_indent_details"+i).val();
	 			var value = $("#item_amount"+i).val();
	 			if(value >= 0){
			 		myArray[i] = id_inv_details;
			 	}
			}
		} 
		  
		
		if(match >=1){
			var id_inv_indent = $("#id_inv_indent"+match).val();
	    	//var id_inv_indent = id_inv_indent.options[id_inv_indent.selectedIndex].value; 
		}else{
			var id_inv_indent = $("#id_inv_indent").val();
	    	//var id_inv_indent = id_inv_indent.options[id_inv_indent.selectedIndex].value;
		}
		if(id_inv_indent == 'na'){
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
			//console.log(id_inv_indent);
		   // Pass Ajax Data
		  
		    $.ajax({
					type: "POST",
					url: "../ajax/PopupdatashowPO.php",
					data:{id_inv_indent:id_inv_indent, array:myArray,counter1:counter1,match:match,checkbox:checkbox},
					dataType: "html", 	
					success: function(data){  
						console.log(data);
						$("#myTables").html(data); 
						$('#myInput').val('');
					}
			});  	
			//document.getElementById('config_button').click(); 	     
	 	     
	 	}
 	    //Total Amount Adding
	 	net_amount(); 
  	}
 

  //Hide And Show Method

	function hideandshow() {
		
		var doc_type = document.getElementById("doc_type");
	    var doc_type = doc_type.options[doc_type.selectedIndex].value;

	    var doc_date = document.getElementById("doc_date").value; 
	    document.getElementById("doc_date1").value = doc_date; 
		 
		if(doc_type != '' && doc_date !='') {
			$('#ind').show(); 
			
			$.ajax({
				type: "POST",
				url: "../ajax/POManage.php",
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
							//document.getElementById("doc_no").value = mydata['doc_no'];
							//document.getElementById("id_doc_type_configuration").value = mydata['id_doc_type_configuration'];
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
	    
	    if(id_mst_party_supplier != '') {

			$.ajax({
				type: "POST",
				url: "../ajax/POsuppliercreditdaysget.php",
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
		    			var counter1 = document.getElementById("counter1").value;
		    			
		    			/*var itemId = $('#id_inv_items'+counter1).val();
		    			


						document.getElementById("id_mst_charges_purchase_local").innerHTML = "<option value='' > Select Tax Register </option>"+"<?php 
	                	$sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '1'";$db->query($sql); 
		
	                	while($row1 = $db->fetch_object()){ 
	                		?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->name ?></option><?php } ?>"; */
	                	
		    			

		    			//Leder Method
		    			for(var i=1;i <=counter1;i++){
		    				


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
			    			document.getElementById("id_mst_charges_purchase_local"+i).innerHTML = "<option value='' >Select Tax Register</option>"+"<?php 
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
		    			document.getElementById("id_mst_charges_purchase_interstate").innerHTML = "<option value='' selected='selected'>Select Tax Register</option>"+"<?php 
	                $sql = "SELECT mst_charges.* FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '2'";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->name ?></option> <?php } ?>";

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
			    			document.getElementById("id_mst_charges_purchase_interstate"+i).innerHTML = "<option value='' selected='selected'> Select Tax Register </option>"+"<?php 
	                $sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '2'";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->name ?></option> <?php } ?>";
		    			}
						
					}
					var base_currency = document.getElementById("base_currency_code").value;


					if(base_currency != mydata['transaction_currency_code']){
						$("#xchange_rate").show();
						$("#base_currency").show();
							$("#credit_day").show()
						$("#trans_currency").show();
						   $("#b-box").show();
						document.getElementById("exchange_rate").value = 0;
					}else{
						$("#xchange_rate").hide();
						$("#base_currency").hide();
						$("#trans_currency").hide();
				        $("#credit_day").hide()
				        $("#b-box").hide();
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
	
	
	//STEP-2 c

	  
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
		

$(document).on('click', '.discou' ,function (e) {
$(".discou").on("keypress keyup blur",function (event) {
   // $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });	
 });	

		
</script>
<script>


//first function

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
$(function (){
//	$("#id_mst_party_supplier").change(function (){
	//	$("#id_mst_party_billtobe").val($(this).val());
	//});
});


 //COMPANY AUTO COMPLETE START==================================================================
	comCheck = () =>{
		window.location.href='https://www.app.roomstatushub.in/adminpanel/index.php';
	}
     $('.id_mst_party_supplier').select2({
        placeholder: 'Select Supplier ',
        ajax: {
          url: "ajax/ajaxSearchSupplierName.php",
          dataType: 'json',
          delay: 50,
		  processResults: function (data) {
			  console.log(data[0].id);
			  //data1 = JSON.parse(data);
			  //alert(data1);
			 if(data[0].id){
			 	return { results: data};
			 }
			 else{
				comCheck(); 
				return { results: data};
				
			 }
          },
           cache: true
        }//ajax end
		
      });
	  //COMPANY AUTO COMPLETE END==================================================================
	  
	  function onLoadIdCompany(id){	 
	 $.ajax({	  
	   url: "ajax/ajaxSearchSupplierName.php?id_mst_party_supplier="+id,
	   dataType: 'json',
          delay: 1,
	  success: function(data){
		
                var id = data[0].id;
                var companyname = data[0].text;
				var tr_str = "<option value=" + id +">" + companyname + "</option>" ;
				$("#id_mst_party_supplier").append(tr_str);
				//$("#CompanyGroupDetails").html(companyname);
					    
          }           
	})

	}
</script>


<!--Start Session From-->
<script type="text/javascript">



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
	//Combo Box
	function itemget(clicked_id) {

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
						
						//alert(id_inv_items_po);
						var mydata = JSON.parse(data); 
						
						//alert(match);
						
						document.getElementById("item_description"+match).value = mydata['name']; 
						document.getElementById("id_mst_charges_purchase_local"+match).value = mydata['id_mst_charges_purchase_local'];
						document.getElementById("id_inv_items"+match).value = id_inv_items_po; 
						var alt_unit = mydata['alt_unit'];
						var main_unit = mydata['main_unit'];
						var conversion_qty = mydata['conversion_qty'];
						document.getElementById("main_unit"+match).value = main_unit; 
						document.getElementById("alt_unit"+match).value = alt_unit; 
						document.getElementById("conver_rate_per_unit"+match).value = conversion_qty; 
						
						document.getElementById("transaction_unit"+match).innerHTML = "<option value='" + main_unit + "' selected='selected'>" + main_unit + "</option>"+"<option value='" + alt_unit + "'>" + alt_unit + "</option>";

						document.getElementById("per_unit"+match).innerHTML = "<option value='" + main_unit + "' selected='selected'>" + main_unit + "</option>"+"<option value='" + alt_unit + "'>" + alt_unit + "</option>";
						
						//document.getElementById("id_mst_charges_purchase_local"+match).innerHTML = "<option value='" + main_unit + "' selected='selected'>" + main_unit + "</option>"+"<option value='" + alt_unit + "'>" + alt_unit + "</option>";
						
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
						var ne = mydata['id_mst_charges_purchase_local'];
						
						//alert(ne);
						document.getElementById("item_description").value = mydata['name'];
						//document.getElementById("id_mst_charges_purchase_local").value = mydata['id_mst_charges_purchase_local'];
						//document.getElementById("id_mst_charges_purchase_local1").value = mydata['id_mst_charges_purchase_local'];
						
						document.getElementById("id_inv_items").value = id_inv_items_po; 
						var alt_unit = mydata['alt_unit'];
						var main_unit = mydata['main_unit'];
						var conversion_qty = mydata['conversion_qty'];
						document.getElementById("main_unit").value = main_unit; 
						document.getElementById("alt_unit").value = alt_unit; 
						document.getElementById("conver_rate_per_unit").value = conversion_qty; 
						document.getElementById("transaction_unit").innerHTML = "<option value='" + main_unit + "' selected='selected'>" + main_unit + "</option>"+"<option value='" + alt_unit + "'>" + alt_unit + "</option>";

						document.getElementById("per_unit").innerHTML = "<option value='" + main_unit + "' selected='selected'>" + main_unit + "</option>"+"<option value='" + alt_unit + "'>" + alt_unit + "</option>"; 
						fetchCharges(id_inv_items_po,'id_mst_charges_purchase_local');
						
					}
				});
			}	
	    
		}
			
	}

</script>
<?php 
	$sql2 = " SELECT max(doc_date) as doc_date FROM `".TBL_INV_PO."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".'3'."' ";
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
	          <a href="editPO.php?session=<?php echo $_GET['session']; ?>&submenu=<?php echo $_GET['submenu']; ?>" type="button" class="btn btn-success"  id="buttons_radius"><i class="fa fa-plus-circle" aria-hidden="true"> Another Indent</i></a> 
	          <a href="printPO.php?eId='<?php echo $_GET['eId']; ?>'&action=edit&page=<?php $_REQUEST['page']?>"  type="button" class="btn btn-primary"  id="buttons_radius"><i class="fa fa-print" aria-hidden="true"> Print</i></a> 
	          <button type="button" class="btn btn-danger" data-dismiss="modal"  id="buttons_radius"><i class="fa fa-times-circle" aria-hidden="true" > Cancel</i></button>
	          <a href="managePO.php?submenu=<?php echo $_GET['submenu']; ?>" type="button" class="btn btn-info"  id="buttons_radius"><i class="fa fa-info-circle" aria-hidden="true"> Close</i></a>  
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
		$('#doc_date').click(); 
 		var dates = '<?php echo $doc_date; ?>'; 
		
		$('.dates').datepicker({ dateFormat: "dd-mm-yy" , minDate: dates });

		//Button hide 
		 
	});


//Select 2  Resolve Here
	 

    $("#addrow1").on("click", function () { 	
		
		var counter1 =  document.getElementById("counter1").value;  
		var ledger_id =  document.getElementById("ledger_id").value;  
        counter1++;          	            

        var newRow1 = $('<tr id="trdelete' + counter1 + '">');
        var cols1 = ""; 
        var cols2 = ""; 
        cols1 += '<td><select onchange="popupshow(this.id)"  name="id_inv_indent' + counter1 + '" id="id_inv_indent' + counter1 + '" class="form-control select3"  style="width:100%"><option>Select Indent No </option><option value="na">NA</option><?php 
	                $sql = "SELECT inv_indent.doc_date, inv_indent.mdoc_no,  inv_indent.doc_no, 
						                   	inv_indent_details.qty,inv_indent_details.alt_qty, inv_indent_details.id, inv_indent_details.id_inv_indent, inv_indent_details.main_unit, inv_indent_details.alt_unit, 
						                   	inv_items.item_code, inv_items.name, 
						                   	mst_attributes.field_value 
						                   	FROM inv_items, mst_attributes, inv_indent_details, inv_indent WHERE mst_attributes.id=inv_indent.id_mst_attributes_department and inv_indent.id = inv_indent_details.id_inv_indent and inv_indent_details.id_inv_items = inv_items.id  and inv_indent.id_shop = '".addslashes($_SESSION['shop'])."' and inv_indent.doc_type = '2' and inv_indent_details.bal_qty>0 group by inv_indent.doc_no,inv_indent.id_doc_type_configuration  ";
	                   	 $db->query($sql); 
	                    while($row1 = $db->fetch_object()){ ?>
	                  		<option value="<?php echo $row1->id."-".$row1->id_inv_indent; ?>"><?php echo $row1->doc_no.' | '.date('d-m-Y' , strtotime(addslashes($row1->doc_date))) ?></option> <?php } 
                  	?></select> </td>';

        cols1 += '<td style="display:none;"><input type="text"  autocomplete="off" placeholder="ID" class="form-control" name="id_inv_indent_details' + counter1 + '" id="id_inv_indent_details' + counter1 + '" readonly=""/></td>';



        cols1 += '<td><div id="hideshow_item_code'+ counter1 +'"><input type="text"  autocomplete="off" placeholder="Item Code" class="form-control" name="item_code' + counter1 + '" id="item_code' + counter1 + '" readonly=""/></div><div id="hideshow_item_codes'+ counter1 +'" style="display: none;"><select onchange="itemget(this.id)" name="id_inv_items_po' + counter1 + '" id="id_inv_items_po' + counter1 + '" class="form-control select3"  style="width:100%"><option>Select Item Code</option><?php 
	                $sql = "SELECT inv_items.*, mst_attributes.field_value FROM inv_items, mst_attributes WHERE mst_attributes.id=inv_items.id_mst_attributes_group_main and inv_items.status=1 and inv_items.id_mst_attributes_item_type IN (".$item_list2.") and inv_items.id_shop = '".addslashes($_SESSION['shop'])."'  ";
	                   	 $db->query($sql); 
	                    while($row1 = $db->fetch_object()){ ?>
	                  		<option value="<?php echo $row1->id; ?>"><?php echo $row1->item_code.' | '.addslashes($row1->name) ?></option> <?php } 
                  	?></select></div><input type="text"  autocomplete="off" placeholder="Item ID" class="form-control" name="id_inv_items' + counter1 + '" id="id_inv_items' + counter1 + '" style="display:none;"/></td>';  
		
		cols1 += '<td><input type="text"  autocomplete="off" placeholder="Item Description" class="form-control" name="item_description' + counter1 + '" id="item_description' + counter1 + '" readonly=""/></td>';





		cols1 += '<td><input type="text" onkeyup="amount_calc(this.id);" onclick="amount_calc(this.id);"  autocomplete="off"  class="form-control discou" value="0" name="qty' + counter1 + '" id="qty' + counter1 + '"  /></td>'; 


        cols1 += '<td> <select class="form-control select3" id="transaction_unit' + counter1 +'" name="transaction_unit' + counter1 +'" onchange="amount_calc(this.id);" style="width:100%"></select><input type="text"  autocomplete="off" placeholder="Main Unit" class="form-control"  name="main_unit' + counter1 + '" id="main_unit' + counter1 + '" style="display:none;"/><input type="text"  autocomplete="off" placeholder="Alt Unit" class="form-control"  name="alt_unit' + counter1 + '" id="alt_unit' + counter1 + '" style="display:none;"/><input  type="text"  autocomplete="off" placeholder="conver_rate_per_unit" class="form-control"  name="conver_rate_per_unit' + counter1 + '" id="conver_rate_per_unit' + counter1 + '" style="display:none;"/></td>'; 

		cols1 += '<td><input onkeyup="amount_calc(this.id)" type="text"  autocomplete="off" placeholder="Rate" class="form-control discountvalue" value="0" name="rate_per_main_unit' + counter1 + '" id="rate_per_main_unit' + counter1 + '" required  /> <input style="display:none;" type="text"  autocomplete="off"  class="form-control discountvalue"  name="item_amount_before_discount' + counter1 + '" id="item_amount_before_discount' + counter1 + '" /></td>'; 

		 cols1 += '<td> <select class="form-control select3" id="per_unit' + counter1 +'" name="per_unit' + counter1 +'" onchange="amount_calc(this.id);" style="width:100%"></select></td>'; 

        cols1 += '<td><input  onkeyup="amount_calc(this.id);" onclick="amount_calc(this.id);"  type="text"  autocomplete="off" placeholder="%Discount" class="form-control discountvalue" name="discount_percent' + counter1 + '"  value="0" id="discount_percent' + counter1 + '"/></td>'; 

        cols1 += '<td><input data-parsley-required data-parsley-errors-container="#item_amount' + counter1 + 'Error3" type="text"  autocomplete="off" placeholder="Amount" class="form-control" name="item_amount' + counter1 + '" id="item_amount' + counter1 + '" readonly/></td>'; 

                 
        cols1 += '<td style="display:none;"><div id="taxconfig" id="taxconfig"><!-- SGST --><input type="text"  autocomplete="off"  name="id_mst_charges_sgst' + counter1 + '" id="id_mst_charges_sgst' + counter1 + '" placeholder="SGST"  class="form-control" /><input type="text"  autocomplete="off"  name="item_sgst_percent' + counter1 + '" id="item_sgst_percent' + counter1 + '" placeholder="SGST"  class="form-control" /><input type="text"  autocomplete="off"  name="item_sgst_amount' + counter1 + '" id="item_sgst_amount' + counter1 + '" placeholder="SGST Amount"  class="form-control"  /><!-- CGST --><input type="text"  autocomplete="off"  name="id_mst_charges_cgst' + counter1 + '" id="id_mst_charges_cgst' + counter1 + '" placeholder="CGST"  class="form-control" /><input type="text"  autocomplete="off"  name="item_cgst_percent' + counter1 + '" id="item_cgst_percent' + counter1 + '" placeholder="CGST"  class="form-control" /><input type="text"  autocomplete="off"  name="item_cgst_amount' + counter1 + '" id="item_cgst_amount' + counter1 + '" placeholder="CGST Amount"  class="form-control" /><!-- IGST --><input type="text"  autocomplete="off"  name="id_mst_charges_igst' + counter1 + '" id="id_mst_charges_igst' + counter1 + '" placeholder="IGST"  class="form-control" /><input type="text"  autocomplete="off"  name="item_igst_percent' + counter1 + '" id="item_igst_percent' + counter1 + '" placeholder="IGST"  class="form-control" /><input type="text"  autocomplete="off"  name="item_igst_amount' + counter1 + '" id="item_igst_amount' + counter1 + '" placeholder="IGST Amount"  class="form-control" /></div></td>';		 
		// $("</tr>"); 

		// var newRow11 = $('<tr id="trdeletes' + counter1 + '">');

		 	 
		 	 




		 	 cols1 += '<td><div id="local'+counter1+'" name="local'+counter1+'" style="display:none;"><select  name="id_mst_charges_purchase_local' + counter1 + '" id="id_mst_charges_purchase_local' + counter1 + '" class="form-control select3" onchange="po_locals(this.id)" style="width:100%;"><option>Select Tax Register</option><?php 
 $sql = "SELECT mst_charges.* FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '1'";
	                   	 $db->query($sql); 
	                    while($row1 = $db->fetch_object()){ ?>
	                  		<option value="<?php echo $row1->id; ?>"><?php echo $row1->name ?></option> <?php } ?></select></div><div id="interstate'+counter1+'" name="interstate'+counter1+'" style="display:none;"><select data-parsley-required  onchange="po_interstate(this.id)"  name="id_mst_charges_purchase_interstate' + counter1 + '" id="id_mst_charges_purchase_interstate' + counter1 + '" class="form-control select3" style="width:100%;" ><option>Select Tax Register</option><?php 
	                $sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND  charges_account = '2' and transaction_type = '2' ";
	                   	 $db->query($sql); 
	                    while($row1 = $db->fetch_object()){ ?>
	                  		<option value="<?php echo $row1->id; ?>"><?php echo $row1->name ?></option> <?php }?></select></div></td> '; 
	                
	                	

	                cols1 += '<td><div id="localsss'+counter1+'" name="localsss'+counter1+'" style="display:none;"><div style="color:red;font-size:11px;" id="s_amount'+counter1+'"></div> <div style="color:red;font-size:11px;" id="c_amount'+counter1+'"></div><div style="color:red;font-size:11px;" id="vat_amount'+counter1+'"></div><div style="color:red;font-size:11px;" id="cess_amount'+counter1+'"></div><div style="color:red;font-size:11px;" id="surcharge_amount'+counter1+'"></div></div><div id="interstatesss'+counter1+'" name="interstatesss'+counter1+'" style="display:none;"><div style="color:red;font-size:11px;" id="i_amount' + counter1 + '" name="i_amount' + counter1 + '"></div></div></td>';
	                  	
	                  cols1 += '<td><input type="text"  autocomplete="off" placeholder="Remarks" class="form-control" name="item_remarks' + counter1 + '" id="item_remarks' + counter1 + '"/></td>';
	                 
					 cols1 += '<td><a class="btn n-btn  abtn ibtnDel1 " id="deletes' + counter1 + '" name="deletes' + counter1 + '" style="cursor:pointer;" title="Delete"><i class="fa fa-trash-o"></i></a></td>';
					 
					 
		 $("</tr>"); 

		document.getElementById("counter1").value = counter1;
		newRow1.append(cols1);
		//newRow11.append(cols2);
		
        $("table.order-list1").append(newRow1); 
        //$("table.order-list1").append(newRow11); 
        
        
		
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
	    //supplier();
		$(".select3").select2({});
        $(".select3").last().next().next().remove();
        //let itemId=$("#id_inv_items"+counter1).val();
        //fetchCharges(itemId,'id_mst_charges_purchase_local'+counter1,counter1);
        

         
    });

    $("table.order-list1").on("click", ".ibtnDel1", function (event) {
       
    		var clicked_id = $(this).attr("id");
    		var deletes = clicked_id.indexOf("deletes"); 
    		 
    			var ids = this.id; 
	        	var regex = /[+-]?\d+(?:\.\d+)?/g;
				var match = parseInt(regex.exec(ids));
    			var total  =-1;
	 			document.getElementById("item_amount"+match).value =total;			  			  					 
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
 				document.getElementById("sgst_net_amount").value =Number(sgst_total)+Number(sgst2); 
 				//CGST
 				cgst_total = Number(cgst_total) + Number(cgst_first);
 				document.getElementById("cgst_net_amount").value =cgst_total;
 				document.getElementById("cgst2").value =cgst_total;
 				document.getElementById("cgst_net_amount").value =Number(cgst_total)+Number(cgst2);
 				//IGST
 				igst_total = Number(igst_total) + Number(igst_first);
 				document.getElementById("igst_net_amount").value =igst_total;
 				document.getElementById("igst2").value =igst_total;
 				document.getElementById("igst_net_amount").value =Number(igst_total)+Number(igst2);



 			}  			
 			//$(this).closest("tr").hide();
 			   
 			$("#trdelete"+match).hide();
 			$("#trdeletes"+match).hide();
 			 
 			<?php if($row->id !=''){?>
 				$("#edittrdelete"+match).hide();
 				$("#edittrdeletes"+match).hide(); 
 				var dbid = document.getElementById("dbid"+match).value;
 				//var id_ot = document.getElementById("id_mst_charges_others"+match).value;
 				//Others Delete
		//var id_ot = document.getElementById("id_mst_charges_others"+match).value;
 				//, ot:id_ot,
	 			var others = 'po';

	 			$.ajax({
					type: "POST",
					url: "../ajax/PODeleteForm.php",
					data:{clicked_id:dbid, others:others},
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
        var ledger_id =  document.getElementById("ledger_id").value;
        counter2++;        

        var newRow2 = $("<tr>");
        var cols2 = ""; 
<!-- <input type="hidden" id="type' + counter2 + '" name="type' + counter2 + '"> -->
        cols2 += '<td style="display:none"><select class="form-control select3" id="type' + counter2 + '" name="type' + counter2 + '" onchange="type_funt(this.id)" style="width:100%"><option value="">select Charges</option><option value="1">OTHERS</option><option value="2">DISCOUNT</option></select></td>';


        cols2 += '<td><div  id="otherss' + counter2 + '"name="otherss' + counter2 + '" ><select  name="id_mst_charges_others' + counter2 + '" id="id_mst_charges_others' + counter2 + '" class="form-control select3" style="width:100%;" onchange="charges_others(this.id)"><option>Select Others Charges</option><?php 
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

		cols2 += '<td style="display:none;"><input onkeyup="subtotal_calc(this.id)" type="text"  autocomplete="off" placeholder="Percentage" class="form-control discountvalue" name="others_charges_percent' + counter2 + '" id="others_charges_percent' + counter2 + '" /></td>'; 

        cols2 += '<td><input onkeyup="charges_amount_calc(this.id);" onclick="charges_amount_calc(this.id);" type="text"  autocomplete="off" placeholder="Amount" class="form-control discountvalue" name="others_charges_amount' + counter2 + '" id="others_charges_amount' + counter2 + '" /></td>';  

		cols2 += '<td style="display:none;"><input type="text"  autocomplete="off" placeholder="Total" class="form-control" name="total_amount_others' + counter2 + '" id="total_amount_others' + counter2 + '" readonly/></td>'; 



        cols2 += '<td><input type="text"  autocomplete="off"  name="others_charges_sgst_amount' + counter2 + '" id="others_charges_sgst_amount' + counter2 + '" placeholder="SGST Amount"  class="form-control"  readonly/></td>';

        cols2 += '<td><input type="text"  autocomplete="off"  name="others_charges_cgst_amount' + counter2 + '" id="others_charges_cgst_amount' + counter2 + '" placeholder="CGST Amount"  class="form-control" readonly /></td>';

        cols2 += '<td><input type="text"  autocomplete="off"  name="others_charges_igst_amount' + counter2 + '" id="others_charges_igst_amount' + counter2 + '" placeholder="IGST Amount"  class="form-control" readonly/></td>';
 

        cols2 += '<td  style="display:none;"><div id="chargestaxconfig" id="chargestaxconfig"><!-- SGST --><input type="text"  autocomplete="off"  name="others_charges_sgst_percent' + counter2 + '" id="others_charges_sgst_percent' + counter2 + '" placeholder="SGST"  class="form-control" /><input type="text"  autocomplete="off"  name="id_mst_charges_sgst_others' + counter2 + '" id="id_mst_charges_sgst_others' + counter2 + '" placeholder="SGST"  class="form-control" /><!-- CGST --><input type="text"  autocomplete="off"  name="others_charges_cgst_percent' + counter2 + '" id="others_charges_cgst_percent' + counter2 + '" placeholder="CGST"  class="form-control" /><input type="text"  autocomplete="off"  name="id_mst_charges_cgst_others' + counter2 + '" id="id_mst_charges_cgst_others' + counter2 + '" placeholder="CGST"  class="form-control" /><!-- IGST --><input type="text"  autocomplete="off"  name="others_charges_igst_percent' + counter2 + '" id="others_charges_igst_percent' + counter2 + '" placeholder="IGST"  class="form-control" /><input type="text"  autocomplete="off"  name="id_mst_charges_igst_others' + counter2 + '" id="id_mst_charges_igst_others' + counter2 + '" placeholder="IGST"  class="form-control" /></div></td>';  

        cols2 += '<td  style="display:none;"><div id="otherschargestaxconfig" id="otherschargestaxconfig"><!-- Discount --><input type="text"  autocomplete="off"  name="others_discount_percent' + counter2 + '" id="others_discount_percent' + counter2 + '" placeholder="Discount"  class="form-control" /><input type="text"  autocomplete="off"  name="others_discount_amount' + counter2 + '" id="others_discount_amount' + counter2 + '" placeholder="Amount"  class="form-control"  /></div></td>';        
 		  
	//cols2 += '<td><img src="images/delete.gif"  class="ibtnDel2" id="deletes' + counter2 + '" name="deletes' + counter2 + '" style="cursor:pointer;" title="Delete"/></td>';
	cols2 += '<td><a class="btn n-btn  abtn ibtnDel2 " id="deletes' + counter2 + '" name="deletes' + counter2 + '" style="cursor:pointer;" title="Delete"><i class="fa fa-trash-o"></i></a></td>';
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
 				document.getElementById("others_charges_net_amount").value =total;
 				//SGST
 				document.getElementById("oc_sgst_total").value =sgstamount; 
 				document.getElementById("oc_sgst2").value =sgstamount;
 				document.getElementById("sgst_net_amount").value =Number(sgstamount)+Number(sgst2); 
 				//CGST Total Section 
 				document.getElementById("oc_cgst_total").value =cgstamount; 
 				document.getElementById("oc_cgst2").value =cgstamount;
 				document.getElementById("cgst_net_amount").value =Number(cgstamount)+Number(cgst2);  
 				//IGST  
 				document.getElementById("oc_igst_total").value =igstamount; 
 				document.getElementById("oc_igst2").value =igstamount;
 				document.getElementById("igst_net_amount").value =Number(igstamount)+Number(igst2);


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
 				document.getElementById("disc_amount_additional").value =discount_total; 

 			}	
 			$(this).closest("tr").hide(); 
 			<?php if($row->id !=''){?>
	 			var dbid2 = document.getElementById("dbid2"+match).value;
	 			//Others Delete
	 			var others = 'others';

	 			$.ajax({
					type: "POST",
					url: "../ajax/PODeleteForm.php",
					data:{clicked_id:dbid2, others:others},
					success: function(data){
						var mydata = JSON.parse(data);  
						if(mydata['delete'] == 1){      
						} 
					}
				});   
			<?php } ?>  
			//Total Amount Adding
	 	net_amount();        
    });   


//Terms And Conditions 
    $("#addrow3").on("click", function () { 	
		
		var counter3 =  document.getElementById("counter3").value;   
        counter3++;        

        var newRow3 = $("<tr>");
        var cols3 = ""; 

                      

		cols3 += '<td><input type="text"  autocomplete="off" placeholder="Terms And Conditions" class="form-control" name="terms' + counter3 + '" id="terms' + counter3 + '" /></td>'; 
          
 		 cols3 += '<td><a class="btn n-btn  abtn ibtnDel3 " id="deletes' + counter3 + '" name="deletes' + counter3 + '" style="cursor:pointer;" title="Delete"><i class="fa fa-trash-o"></i></a></td>'; 
		//cols3 += '<td><img src="images/delete.gif"  class="ibtnDel3" id="termsdeletes' + counter3 + '" name="termsdeletes' + counter3 + '" style="cursor:pointer;" title="Delete"/></td>';
		//
		 $("</tr>"); 

		newRow3.append(cols3);
        $("table.order-list3").append(newRow3);  
        document.getElementById("counter3").value = counter3;
         
    });

    $("table.order-list3").on("click", ".ibtnDel3", function (event) {
    	$(this).closest("tr").remove();
    }); 
	</script>
