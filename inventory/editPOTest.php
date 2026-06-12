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
	//hideandshow();
}
</script>
<?php } ?>
  <script>
<?php  
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){} ?>	
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
									
									//debugData($rowsID);die;
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
die;
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
										
										

											$sqlResult1 = "SELECT * FROM ".TBL_ATTRIBUTES." WHERE table_name = 'items_type' AND field_category IN ('Ingredients Items','Both') AND id_shop = ".$_SESSION['shop'] ." ";
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
								while($rowsID = $db->fetch_object()){}  
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

<!-- End Audit trail Modal -->
	
<?php
echo '1111111111111111111';die;

											 	 ?>
 		
<?php include_once("../includes/footer.php");?> 
