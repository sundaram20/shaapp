<?php include_once("../config/auto_loader.php");
$image_display_path = $UPLOAD_FILES_PATH."/outlets/";
include_once("include/pos_function.php");
include_once("include/function.php");
?>

<?php $session=$_SESSION['id_document']; ?>
<title>RoomStatusHUB | <?php echo currentNavigation_s($session)['submenu']; ?></title>
<?php
$k=0;

////////////////////////////////////////////////////////////////////////
// echo '<pre>';
 // print_r($_REQUEST);
 //echo '</pre>';
// exit;

$attribute_type = "table";
$attribute_id = "";
$room_id ="";
$id_fo_folio_to = "";
$id_fo_bill = "";
$guest_id = "";
$sql = "  SELECT * FROM `".TBL_ATTRIBUTES."` WHERE `id` = '".$_REQUEST['id_attribute_table']."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
$db->query($sql);
if($db->num_rows() > 0) {
	$firstRecord = $db->fetch_object();
	$attribute_id = $firstRecord->id_mst_room_no;
	$room_id = $firstRecord->field_value;
	$table_sql = "  SELECT * FROM `".TBL_ATTRIBUTES."` WHERE `id` = '".$firstRecord->id_table_group."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
	$db->query($table_sql);
	if($db->num_rows() > 0) {
		$tableRecord = $db->fetch_object();
		if ($tableRecord->id_table_group == 1) {
			$attribute_type = "room";
		} else {
			$attribute_type = "table";
		}
	}
}

if ($room_id != "") {
	$sqlRoomNumber = mysqli_query($connNew, "SELECT DISTINCT 
		room.id, room.room_no, room.id_mst_room_types, room.room_status,
		resdetails.id_fo_reservations, resdetails.id_mst_guest, resdetails.id_fo_folio_to,
		resdetails.id_fo_bill, resdetails.order_by_room, fo_bill.status as occupanyStatus
		FROM mst_room_no_allocation as room 
		INNER JOIN fo_reservations_details as resdetails ON room.id = resdetails.id_mst_room_no_allocation 
		INNER JOIN fo_bill as fo_bill ON fo_bill.id = resdetails.id_fo_bill 
		WHERE fo_bill.status = '1'  
		AND resdetails.checkout_status = '0' 
		AND resdetails.no_showoff = '0' 
		AND resdetails.id_mst_room_no_allocation = '".$attribute_id."' and resdetails.`room_availability`='Checkin'");
	
	if ($sqlRoomNumber) {
		$firstRecord = mysqli_fetch_assoc($sqlRoomNumber);
		if ($firstRecord) {
			$id_fo_folio_to = $firstRecord['id_fo_folio_to'];
			$id_fo_bill = $firstRecord['id_fo_bill'];
			$guest_id = $firstRecord['id_mst_guest'];
		}
	} else {
		echo "Error: " . mysqli_error($connNew);
	}
}

$GuestName = "";
$lastName = "";
$Title = "";
if ($guest_id != "") {
	$GuestName	=	selectColumn("mst_guest",'first_name'," WHERE `id` = '".$guest_id."'");
	$lastName	=	selectColumn("mst_guest",'last_name'," WHERE `id` = '".$guest_id."'");
	$id_mst_attributes_title	=	selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$guest_id."'");
	$Title=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title."'");
}

$date 				   =    date('Y-m-d');
$po_date		 		=	date('Y-m-d' , strtotime(addslashes($_POST['po_date'])));
$doc_type		 	   =	$_REQUEST['doc_type'];
$pos_bill_type	      = 	2; //'1 For KOT and 2 For sale';
$kot_doc_no	         =	@implode(',' , $_REQUEST['id_kot']);
$pos_purch_id_array	 =	'';
$item_BillSplit		 =	array();

foreach($_REQUEST['id_pos_detail'] as $porchdetailID =>$dataCode){
	array_push($item_BillSplit,$_REQUEST['id_pos_detail'][$porchdetailID]['item_BillSplit']);
}	
sort($item_BillSplit);
$arrayBillSplit = array_unique($item_BillSplit);
count($item_BillSplit);

$count_no = count($arrayBillSplit);

//echo $_REQUEST['id_pos_detail'][$porchdetailID]['item_BillSplit'];
//exit;


foreach($arrayBillSplit as $Count){

$doc_type=$_REQUEST['doc_type'];
$id_subsection	=	$_REQUEST['outlet'];
$retunDocConfig	=	docConfigNoValidator($doc_type,$po_date,$id_subsection);

$id_doc_type_configuration	=	$retunDocConfig['id_doc_type_configuration'];
$po_no=$retunDocConfig['po_no'];
$mdoc_no=$retunDocConfig['prefix'].$doc_no.$retunDocConfig['suffix'];	
$prefix=$retunDocConfig['prefix'];
$suffix	= $retunDocConfig['suffix'];

$id_pos_details_split	= array();
		
	foreach($_REQUEST['id_pos_detail'] as $porchdetailID =>$dataCode){
		
			if($_REQUEST['id_pos_detail'][$porchdetailID]['item_BillSplit']==$Count){
				
				$id_pos_details_split[]	=	$_REQUEST['id_pos_detail'][$porchdetailID]['id'];
				
				$discount_amount_additional = ($_REQUEST['additional_discount_amount']/count($arrayBillSplit));

				$others_charges_net_amount = ($_REQUEST['others_charges_net_amount']/count($arrayBillSplit));
			
				$sgst_net_amount += $_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_sgst'];

				$cgst_net_amount += $_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cgst'];

				$igst_net_amount += $_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_igst'];

				$cess_net_amount += $_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cess'];

				$vat_net_amount += $_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_vat'];

				$surcharge_net_amount += $_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_surcharge'];
 		
				$sub_total_items += $_REQUEST['id_pos_detail'][$porchdetailID]['item_TotalAmountItem'];

				$total_item_discount_amount  += $_REQUEST['id_pos_detail'][$porchdetailID]['item_discount_amount'];
							
				$round_off_amount	  = $_REQUEST['round_off_amount'];
				
				$sub_total_items1 += $_REQUEST['id_pos_detail'][$porchdetailID]['item_TotalAmountItem1'];
				
				}
			}		
			
			$net_amount			= ($sub_total_items1+$_REQUEST['sc_sgst']+$_REQUEST['sc_cgst']+$_REQUEST['service_charge_amount']+$sgst_net_amount+$cgst_net_amount +$igst_net_amount + $cess_net_amount +$vat_net_amount +$surcharge_net_amount)-($total_item_discount_amount);
			
			$totalBeforeRound	=(($net_amount+$others_charges_net_amount)-$discount_amount_additional);
			$net_amount		 =(($net_amount+$others_charges_net_amount)-$discount_amount_additional);
			
			$RoundOfAmount		=	round((round($net_amount,0)-$totalBeforeRound),2);
			
			$grant_total_amount=stripslashes(round($net_amount,0));
			
			$id_pos_details_split2	=implode(',',$id_pos_details_split);
			 
			$sub_total_items	  = $_REQUEST['sub_total_items'];
	
			if($_REQUEST['id_posbilling']==''){ //ADD 
//Duplicate POS Bill Check ====================			
 $SqlPosAlreadyExist = "SELECT * From `".TBL_PURCH."` WHERE `id_doc_type_configuration`	='".$id_doc_type_configuration."'  and `doc_type` = '".$doc_type."' and `id_attribute_shift` = '".$_REQUEST['id_attribute_shift']."' AND	`id_attribute_steward` = '".$_REQUEST['id_attribute_steward']."' AND 	`id_attribute_table` = '".$_REQUEST['id_attribute_table']."' and  `id_pos_details_split` = '".$id_pos_details_split2."' and cancelled=0  ";
//die;

  $QueryPosAlreadyExist = mysqli_query($connNew, $SqlPosAlreadyExist);	
  $RecordAlreadyExist	=	mysqli_num_rows($QueryPosAlreadyExist);
  if($RecordAlreadyExist>0){
	  echo '<script>alert("Bill Already Processed");window.setTimeout(function() {window.location.href = "'.$SITE_URL.'/pos/manageOutletBilling.php?submenu=177&session=21";}, 100);</script>';
	  exit;
  }else{
	  
			
					$addSql  = "  INSERT INTO `".TBL_PURCH."` SET
							`id_shop` = '".$_SESSION['shop']."',
							`id_doc_type_configuration`	='".$id_doc_type_configuration."',				
							`doc_no`='".$po_no."',
							`sc_reverse`= '".$_REQUEST['revServiceCharge']."',	
							`doc_date`='".date('Y-m-d',strtotime($_REQUEST['po_date']))."',
							`mdoc_no`=	'".($_REQUEST['prefix']).($po_no).($_REQUEST['suffix'])."',
							`doc_type` = '".$doc_type."',
							`id_mst_outlet` = '".$_REQUEST['outlet']."',	
							`id_mst_charges_discounts` = '".addslashes($_REQUEST['id_mst_charges_discounts'])."',
							`discount_charges_percent` = '".addslashes($_REQUEST['total_discount_percentage'])."',
										
							`pos_bill_type`='".$pos_bill_type."',
							`kot_doc_no` = '".$kot_doc_no."',
							`id_pos_details_split` = '".$id_pos_details_split2."',
							`id_attribute_shift` = '".$_REQUEST['id_attribute_shift']."',
							`id_attribute_steward` = '".$_REQUEST['id_attribute_steward']."',
							`id_mst_country_lang` = '".$_REQUEST['id_mst_country_lang']."',
							`pax` = '".$_REQUEST['noOfPax']."',
							`id_attribute_table` = '".$_REQUEST['id_attribute_table']."',
							`sc_charges_net_amount` = '".$_REQUEST['service_charge_amount']."',
							`discount_amount_additional`= '".$discount_amount_additional."',
							`others_charges_net_amount`= '".$others_charges_net_amount."',			
							`sgst_total_items` = '".$sgst_net_amount."',
							`cgst_total_items`= '".$cgst_net_amount."',
							`igst_total_items`= '".$igst_net_amount."',
							`cess_total_items`= '".$cess_net_amount."',
							`vat_total_items`= '".$vat_net_amount."',
							`sc_sgst`= '".$_REQUEST['sc_sgst']."',
							`sc_cgst`= '".$_REQUEST['sc_cgst']."',
							`surcharge_total_items`= '".$surcharge_net_amount."',		
							`sub_total_items`= '".$sub_total_items1."',
							`total_discount_items`= '".$total_item_discount_amount."',
							`net_amount_items`= '".$net_amount."',
							`round_off_amount`= '".$RoundOfAmount."',
							`grant_total_amount`= '".$grant_total_amount."',
							`remarks` = '".$_REQUEST['remarks']."',
							";
					$addSql .= "	`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."' ";	
				
			$grant_total_amount ='';
			$sub_total_items1 = '';
			$net_amount = '';
			$totalBeforeRound='';
			$RoundOfAmount='';
			$total_item_discount_amount='';

executeSql($addSql);

		$lastInsertId_purch1	= $db->insert_id();	
		$_REQUEST['editid_posbilling']	=$lastInsertId_purch1;
		$pos_purch_id 	= $lastInsertId_purch1;		
		$pos_purch_id1[] 	= $lastInsertId_purch1;		
		$value	=explode(',',$id_pos_details_split2);
				
				for($i=0;$i<count($value);$i++){				 
					$pos_val[]=$value[$i].'-'.$lastInsertId_purch1;
					sort($pos_val);
				//	$val = $pos_val[$i];
		//$purch = explode('-',$val);
				} 
 }
		//INSERT END =================================
			}else{
			
				if($count_no == '1'){
					
	
	
					$addSql  = " UPDATE `".TBL_PURCH."` SET
					`id_shop` = '".$_SESSION['shop']."',
					`id_doc_type_configuration`	='".$id_doc_type_configuration."',	
					`doc_date`='".date('Y-m-d',strtotime($_REQUEST['po_date']))."',
					`sc_reverse`= '".$_REQUEST['revServiceCharge']."',	
					`doc_type` = '".$doc_type."',
					`id_mst_outlet` = '".$_REQUEST['outlet']."',		
					`id_mst_charges_discounts` = '".addslashes($_REQUEST['id_mst_charges_discounts'])."',
					`discount_charges_percent` = '".addslashes($_REQUEST['total_discount_percentage'])."',		
					`pos_bill_type`='".$pos_bill_type."',
					`id_pos_details_split` = '".$id_pos_details_split2."',
					`id_attribute_shift` = '".$_REQUEST['id_attribute_shift']."',
					`id_attribute_steward` = '".$_REQUEST['id_attribute_steward']."',
					`id_mst_country_lang` = '".$_REQUEST['id_mst_country_lang']."',
					`pax` = '".$_REQUEST['noOfPax']."',
					`id_attribute_table` = '".$_REQUEST['id_attribute_table']."',
					`sc_charges_net_amount` = '".$_REQUEST['service_charge_amount']."',
					`discount_amount_additional`= '".$discount_amount_additional."',
					`others_charges_net_amount`= '".$others_charges_net_amount."',			
					`sgst_total_items` = '".$sgst_net_amount."',
					`cgst_total_items`= '".$cgst_net_amount."',
					`igst_total_items`= '".$igst_net_amount."',
					`cess_total_items`= '".$cess_net_amount."',
					`vat_total_items`= '".$vat_net_amount."',
					`sc_sgst`= '".$_REQUEST['sc_sgst']."',
					`sc_cgst`= '".$_REQUEST['sc_cgst']."',
					`surcharge_total_items`= '".$surcharge_net_amount."',		
					`sub_total_items`= '".$sub_total_items1."',
					`total_discount_items`= '".$total_item_discount_amount."',
					`net_amount_items`= '".$net_amount."',
					`round_off_amount`= '".$RoundOfAmount."',
					`grant_total_amount`= '".$grant_total_amount."',
					`remarks` = '".$_REQUEST['remarks']."' ,
					`last_modified` = '".currenDateTime()."',
					`id_mst_user_modified_by` = '".$_SESSION['userId']."' 	

					WHERE id='".$_REQUEST['id_posbilling']."' ";
				
						$grant_total_amount ='';
						$sub_total_items1 = '';
						$net_amount = '';
						$totalBeforeRound='';
						$RoundOfAmount='';
						$total_item_discount_amount='';
						
					executeSql($addSql);
				
					$pos_purch_id=$_REQUEST['id_posbilling'];
				
				}else{
					
				$addSql  = "  INSERT INTO `".TBL_PURCH."` SET
							`id_shop` = '".$_SESSION['shop']."',
							`id_doc_type_configuration`	='".$id_doc_type_configuration."',				
							`doc_no`='".$po_no."',
							`sc_reverse`= '".$_REQUEST['revServiceCharge']."',	
							`doc_date`='".date('Y-m-d',strtotime($_REQUEST['po_date']))."',
							`mdoc_no`=	'".($_REQUEST['prefix']).($po_no).($_REQUEST['suffix'])."',
							`id_mst_charges_discounts` = '".addslashes($_REQUEST['id_mst_charges_discounts'])."',
					`discount_charges_percent` = '".addslashes($_REQUEST['total_discount_percentage'])."',		
							`doc_type` = '".$doc_type."',
							`id_mst_outlet` = '".$_REQUEST['outlet']."',				
							`pos_bill_type`='".$pos_bill_type."',
							`kot_doc_no` = '".$kot_doc_no."',
							`id_pos_details_split` = '".$id_pos_details_split2."',
							`id_attribute_shift` = '".$_REQUEST['id_attribute_shift']."',
							`id_attribute_steward` = '".$_REQUEST['id_attribute_steward']."',
							`id_mst_country_lang` = '".$_REQUEST['id_mst_country_lang']."',
							`pax` = '".$_REQUEST['noOfPax']."',
							`id_attribute_table` = '".$_REQUEST['id_attribute_table']."',
							`sc_charges_net_amount` = '".$_REQUEST['service_charge_amount']."',
							`discount_amount_additional`= '".$discount_amount_additional."',
							`others_charges_net_amount`= '".$others_charges_net_amount."',			
							`sgst_total_items` = '".$sgst_net_amount."',
							`cgst_total_items`= '".$cgst_net_amount."',
							`igst_total_items`= '".$igst_net_amount."',
							`cess_total_items`= '".$cess_net_amount."',
							`vat_total_items`= '".$vat_net_amount."',
							`sc_sgst`= '".$_REQUEST['sc_sgst']."',
							`sc_cgst`= '".$_REQUEST['sc_cgst']."',
							`surcharge_total_items`= '".$surcharge_net_amount."',		
							`sub_total_items`= '".$sub_total_items1."',
							`total_discount_items`= '".$total_item_discount_amount."',
							`net_amount_items`= '".$net_amount."',
							`round_off_amount`= '".$RoundOfAmount."',
							`grant_total_amount`= '".$grant_total_amount."',
							`remarks` = '".$_REQUEST['remarks']."',
							";
				$addSql .= "	`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."' ";
			
				$grant_total_amount ='';
				$sub_total_items1 = '';
				$net_amount = '';
				$totalBeforeRound='';
				$RoundOfAmount='';
				$total_item_discount_amount='';
				
				executeSql($addSql);
				
				$lastInsertId_purch= $db->insert_id();	
				$_REQUEST['editid_posbilling']=$lastInsertId_purch;
				$pos_purch_id = $lastInsertId_purch;
				
				$pos_purch_id2[] = $lastInsertId_purch;
				$value=explode(',',$id_pos_details_split2);
				
				for($i=0;$i<count($value);$i++){					
					//$pos_val[]=$value[$i];					 
					$pos_val[]=$value[$i].'-'.$lastInsertId_purch;
					sort($pos_val);
				} 
				
				
				}
			}
			
			$pos_purch_id_array = array();
			$pos_purch_id_array[]	=	$pos_purch_id;
			$net_amount	='';						
			$discount_percent_additional= '';
			$discount_amount_additional ='';
			$others_charges_net_amount ='';			
			$sgst_net_amount = '';
			$cgst_net_amount = '';
			$igst_net_amount = '';
			$cess_net_amount = '';
			$vat_net_amount = '';
			$surcharge_net_amount = ''; 		
			$sub_total_items = '';
			$sub_total_items1 = '';
			$total_item_discount_amount ='';			
			$id_pos_details_split='';

	}
//var_dump(ksort($pos_val));
 

//var_dump($pos_val);
//exit;
$item_BillSplit=array();

$i=0;
		
foreach($_REQUEST['id_pos_detail'] as $porchdetailID =>$dataCode){

		

 $insertPosKotDetail = "UPDATE `".TBL_PURCH_DETAILS."` SET 
						  `adj_qty`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_qty'])."',
						  `item_sgst_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_sgst'])."',
						  `item_cgst_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cgst'])."',
						  `item_igst_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_igst'])."',
						  `item_cess_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cess'])."',
						  `item_vat_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_vat'])."',
						  `item_surcharge_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_surcharge'])."',
						  `item_sgst_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_sgst_percentage'])."',
						  `item_cgst_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cgst_percentage'])."',
						  `item_igst_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_igst_percentage'])."',
						  `item_cess_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cess_percentage'])."',
						  `item_vat_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_vat_percentage'])."',
						  `item_surcharge_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_surcharge_percentage'])."',
						  `item_discount_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_discount_percentage'])."',
						  `item_discount_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_discount_amount'])."',
						  `id_mst_charges_sales_interstate`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_mst_charges_sales_Interstate'])."',
						  `main_unit`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_mst_attributes_unit_main'])."',
						  `rate_per_main_unit`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['sale_rate'])."'
						  ";
 $insertPosKotDetail .= "	
							WHERE `id`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id'])."'
						";	

executeSql($insertPosKotDetail);
		
		
$pur_details = "SELECT * From `".TBL_PURCH_DETAILS."` WHERE id = '".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id'])."'  ";
$pur_details1 = mysqli_query($connNew, $pur_details);	
$pur_details2 = mysqli_fetch_object($pur_details1);	
$item_description = $pur_details2->item_description;		
$items = $pur_details2->id_mst_items;		
$items_details = $pur_details2->id_mst_items_details;		
$outlet = $pur_details2->id_mst_outlet;		
$id_mst_charges_sales_local = $pur_details2->id_mst_charges_sales_local;		
$id_mst_charges_sales_interstate = $pur_details2->id_mst_charges_sales_interstate;		
$qty = $pur_details2->qty;		
$main_unit = $pur_details2->main_unit;		
$item_amount_before_discount = $pur_details2->item_amount_before_discount;		
$item_amount = $pur_details2->item_amount;		
$id_mst_charges_sgst = $pur_details2->id_mst_charges_sgst;		
$id_mst_charges_cgst = $pur_details2->id_mst_charges_cgst;		
$id_mst_charges_igst = $pur_details2->id_mst_charges_igst;		
$id_mst_charges_cess = $pur_details2->id_mst_charges_cess;		
$id_mst_charges_vat = $pur_details2->id_mst_charges_vat;		
$id_mst_charges_surcharge = $pur_details2->id_mst_charges_surcharge;
$item_amount = addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_rate']);
$item_description = addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_description']);
/*
$sqlitemDetail = mysqli_query($connNew,"SELECT *  from `".TBL_INV_ITEMS_DETAILS."` WHERE id_item='".$items."' ");
$numitemDetail=	mysqli_num_rows($sqlitemDetail);
$rowitemDetail1     =  mysqli_fetch_object($sqlitemDetail1);
if($numitemDetail>0){
	
}else{
	$sqlitemDetail1     = mysqli_query($connNew,"SELECT *  from `".TBL_INV_ITEMS."` WHERE id='".$id."' ");
		$numitemDetail1     =  mysqli_num_rows($sqlitemDetail1);
		$rowitemDetail1     =  mysqli_fetch_object($sqlitemDetail1);
	 
} */


	
if($_REQUEST['id_posbilling']==''){	
if($count_no == '1'){		
   $insertPosKotDetail1 = "INSERT INTO `".TBL_PURCH_DETAILS."` SET 
						`id_pos_purch`='".addslashes($pos_purch_id)."',
						`id_mst_items`='".addslashes($items)."',
						`id_mst_items_details`='".addslashes($items_details)."',
						`id_mst_outlet`='".addslashes($outlet)."',
						`id_mst_charges_sales_local`='".addslashes($id_mst_charges_sales_local)."',
						`qty`='".addslashes($qty)."',
						`item_amount`='".addslashes($item_amount)."',
						`item_description`='".addslashes($item_description)."',
						`id_mst_charges_sgst`='".addslashes($id_mst_charges_sgst)."',
						`id_mst_charges_cgst`='".addslashes($id_mst_charges_cgst)."',
						`id_mst_charges_igst`='".addslashes($id_mst_charges_igst)."',
						`id_mst_charges_cess`='".addslashes($id_mst_charges_cess)."',
						`id_mst_charges_vat`='".addslashes($id_mst_charges_vat)."',
						`id_mst_charges_surcharge`='".addslashes($id_mst_charges_surcharge)."',
						`item_amount_before_discount`='".addslashes($item_amount_before_discount)."',
						  `adj_qty`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_qty'])."',
						  `item_sgst_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_sgst'])."',
						  `item_cgst_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cgst'])."',
						  `item_igst_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_igst'])."',
						  `item_cess_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cess'])."',
						  `item_vat_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_vat'])."',
						  `item_surcharge_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_surcharge'])."',
						  `item_sgst_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_sgst_percentage'])."',
						  `item_cgst_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cgst_percentage'])."',
						  `item_igst_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_igst_percentage'])."',
						  `item_cess_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cess_percentage'])."',
						  `item_vat_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_vat_percentage'])."',
						  `item_surcharge_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_surcharge_percentage'])."',
						  `item_discount_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_discount_percentage'])."',
						  `item_discount_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_discount_amount'])."',
						  `id_mst_charges_sales_interstate`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_mst_charges_sales_Interstate'])."',
						  `main_unit`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_mst_attributes_unit_main'])."',
						  `rate_per_main_unit`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['sale_rate'])."',
						  `date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."' ";
		executeSql($insertPosKotDetail1); 	
}else{
	
	$val = $pos_val[$i];
		$purch = explode('-',$val);
		
	  $insertPosKotDetail1 = "INSERT INTO `".TBL_PURCH_DETAILS."` SET 
						`id_pos_purch`='".addslashes($purch[1])."',
						`id_mst_items`='".addslashes($items)."',
						`id_mst_items_details`='".addslashes($items_details)."',
						`id_mst_outlet`='".addslashes($outlet)."',
						`id_mst_charges_sales_local`='".addslashes($id_mst_charges_sales_local)."',
						`qty`='".addslashes($qty)."',
						`item_amount`='".addslashes($item_amount)."',
						`item_description`='".addslashes($item_description)."',
						`id_mst_charges_sgst`='".addslashes($id_mst_charges_sgst)."',
						`id_mst_charges_cgst`='".addslashes($id_mst_charges_cgst)."',
						`id_mst_charges_igst`='".addslashes($id_mst_charges_igst)."',
						`id_mst_charges_cess`='".addslashes($id_mst_charges_cess)."',
						`id_mst_charges_vat`='".addslashes($id_mst_charges_vat)."',
						`id_mst_charges_surcharge`='".addslashes($id_mst_charges_surcharge)."',
						`item_amount_before_discount`='".addslashes($item_amount_before_discount)."',
						  `adj_qty`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_qty'])."',
						  `item_sgst_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_sgst'])."',
						  `item_cgst_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cgst'])."',
						  `item_igst_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_igst'])."',
						  `item_cess_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cess'])."',
						  `item_vat_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_vat'])."',
						  `item_surcharge_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_surcharge'])."',
						  `item_sgst_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_sgst_percentage'])."',
						  `item_cgst_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cgst_percentage'])."',
						  `item_igst_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_igst_percentage'])."',
						  `item_cess_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cess_percentage'])."',
						  `item_vat_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_vat_percentage'])."',
						  `item_surcharge_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_surcharge_percentage'])."',
						  `item_discount_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_discount_percentage'])."',
						  `item_discount_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_discount_amount'])."',
						  `id_mst_charges_sales_interstate`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_mst_charges_sales_Interstate'])."',
						  `main_unit`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_mst_attributes_unit_main'])."',
						  `rate_per_main_unit`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['sale_rate'])."',
						  `date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."' ";
		executeSql($insertPosKotDetail1); 
}
		
}

else{

	if($count_no == '1'){
		 $insertPosKotDetail2 = "UPDATE `".TBL_PURCH_DETAILS."` SET 
						  `adj_qty`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_qty'])."',
						  `item_sgst_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_sgst'])."',
						  `item_cgst_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cgst'])."',
						  `item_igst_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_igst'])."',
						  `item_cess_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cess'])."',
						  `item_vat_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_vat'])."',
						  `item_surcharge_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_surcharge'])."',
						  `item_sgst_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_sgst_percentage'])."',
						  `item_cgst_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cgst_percentage'])."',
						  `item_igst_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_igst_percentage'])."',
						  `item_cess_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cess_percentage'])."',
						  `item_vat_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_vat_percentage'])."',
						  `item_surcharge_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_surcharge_percentage'])."',
						  `item_discount_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_discount_percentage'])."',
						  `item_discount_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_discount_amount'])."',
						  `id_mst_charges_sales_interstate`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_mst_charges_sales_Interstate'])."',
					`item_description`='".addslashes($item_description)."',	  
`item_amount`='".addslashes($item_amount)."',
`item_amount_before_discount`='".addslashes($item_amount_before_discount)."',
						  `main_unit`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_mst_attributes_unit_main'])."',
						  `rate_per_main_unit`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['sale_rate'])."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."' 	
						  WHERE `id`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id'])."' ";
	executeSql($insertPosKotDetail2);
	
	}else{
		$val = $pos_val[$i];
		$purch = explode('-',$val);
		  $insertPosKotDetail1 = "INSERT INTO `".TBL_PURCH_DETAILS."` SET 
						`id_pos_purch`='".addslashes($purch[1])."',
						`id_mst_items`='".addslashes($items)."',
						`id_mst_items_details`='".addslashes($items_details)."',
						`id_mst_outlet`='".addslashes($outlet)."',
						`id_mst_charges_sales_local`='".addslashes($id_mst_charges_sales_local)."',
						`qty`='".addslashes($qty)."',
						`item_amount`='".addslashes($item_amount)."',
						`item_description`='".addslashes($item_description)."',
						`id_mst_charges_sgst`='".addslashes($id_mst_charges_sgst)."',
						`id_mst_charges_cgst`='".addslashes($id_mst_charges_cgst)."',
						`id_mst_charges_igst`='".addslashes($id_mst_charges_igst)."',
						`id_mst_charges_cess`='".addslashes($id_mst_charges_cess)."',
						`id_mst_charges_vat`='".addslashes($id_mst_charges_vat)."',
						`id_mst_charges_surcharge`='".addslashes($id_mst_charges_surcharge)."',
						`item_amount_before_discount`='".addslashes($item_amount_before_discount)."',
						`adj_qty`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_qty'])."',
						`item_sgst_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_sgst'])."',
						`item_cgst_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cgst'])."',
						`item_igst_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_igst'])."',
						`item_cess_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cess'])."',
						`item_vat_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_vat'])."',
						`item_surcharge_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_surcharge'])."',
						`item_sgst_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_sgst_percentage'])."',
						`item_cgst_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cgst_percentage'])."',
						`item_igst_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_igst_percentage'])."',
						`item_cess_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cess_percentage'])."',
						`item_vat_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_vat_percentage'])."',
						`item_surcharge_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_surcharge_percentage'])."',
						  `item_discount_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_discount_percentage'])."',
						  `item_discount_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_discount_amount'])."',
						  `id_mst_charges_sales_interstate`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_mst_charges_sales_Interstate'])."',
						  `main_unit`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_mst_attributes_unit_main'])."',
						  `rate_per_main_unit`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['sale_rate'])."',
						  `date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."' ";
		executeSql($insertPosKotDetail1);
	}
}

$i++;
			
				




	if(!in_array($_REQUEST['id_pos_detail'][$porchdetailID]['item_BillSplit'],$item_BillSplit)){

		$item_BillSplit[]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_BillSplit'];

	}
		$_REQUEST['id_pos_detail'][$porchdetailID]['item_BillSplit'];
	}
			
	
	//Guest DEATLS ADD===================================================================>
	
		
	//Update Total QTY=START===============================================
	foreach($_REQUEST['id_kot'] as $idkot){
	
	
	$total_qty = selectColumn(TBL_PURCH_DETAILS,'sum(qty)'," WHERE `id_pos_purch`='".addslashes($idkot)."'");
	$total_adj_qty = selectColumn(TBL_PURCH_DETAILS,'sum(adj_qty)'," WHERE `id_pos_purch`='".addslashes($idkot)."'");
	$UpdateTotalQTY =  "UPDATE `".TBL_PURCH."` SET 
				
				 `total_qty`='".$total_qty."',
				 `total_adj_qty`='".$total_adj_qty."'
				
				 
				  where`id` = '".$idkot."'
				  ";   
				  mysqli_query($connNew,$UpdateTotalQTY);
	}
	//Update Total QTY=END===============================================	
	
			
	
	
	
			
//Guest DEATLS ADD===================================================================>
	if($_REQUEST['guest_name']!='' && $_REQUEST['guest_mobile']!=''){ //BOTH	
		$sqlGuest =" AND mobile = '".$_REQUEST['guest_mobile']."' AND name = '".$_REQUEST['guest_name']."' ";	
		
	}elseif($_REQUEST['guest_mobile']=='' && ($_REQUEST['guest_name']!='')){ //Only Guest Name
		$sqlGuest ="   AND name = '".$_REQUEST['guest_name']."' ";	
	
	}elseif($_REQUEST['guest_mobile']!='' && ($_REQUEST['guest_name']=='')){ //Only Mobile Number
		$sqlGuest ="  AND mobile = '".$_REQUEST['guest_mobile']."'  ";	
	
	}
	
	
	
	
	
		
	 $pos_Guest_sql="SELECT * FROM pos_guest   WHERE  id=1". $sqlGuest;
	$resultPosGuest = mysqli_query($connNew, $pos_Guest_sql); 
	$numGuestRows = mysqli_num_rows($resultPosGuest);
	$posGuestResult = mysqli_fetch_object($resultPosGuest);
	if($numGuestRows=='0'){
	
	$pos_purch_id=$_REQUEST['id_posbilling']!=''?$_REQUEST['id_posbilling']:$_REQUEST['editid_posbilling'];
	
	  $posGuestSql = " INSERT pos_guest SET 
	`name` = '".addslashes($_REQUEST['guest_name'])."',
	`mobile` = '".addslashes($_REQUEST['guest_mobile'])."',	
	`ids_pos_purch` = '".$pos_purch_id."'";//die;
	 executeSql($posGuestSql);
	$lastInsertId_posGuest	= $db->insert_id();
	}else{
		$pos_purch_id=$_REQUEST['id_posbilling']!=''?$_REQUEST['id_posbilling']:$_REQUEST['editid_posbilling'];
		//=============================================
		 $pos_Purch_sql="SELECT * FROM pos_guest   WHERE  FIND_IN_SET(".$pos_purch_id.",ids_pos_purch)";
		$resultPurchGuest = mysqli_query($connNew, $pos_Purch_sql); 
		$numPurchRows = mysqli_num_rows($resultPurchGuest);
		while($posPurchResult = mysqli_fetch_object($resultPurchGuest)){
			
			$array	= explode(',',$posPurchResult->ids_pos_purch);		
			
			$removedArray = array_diff( $array, array($pos_purch_id) );
			$array = implode(',',array_unique($removedArray));
			
	
	$posGuestSql = " UPDATE pos_guest SET 	
				
				`ids_pos_purch` = '".$array."' WHERE id='".$posPurchResult->id."' ";
				 executeSql($posGuestSql);
		}
		
		
	
		
		
		
		$Array1 = explode(',',$posGuestResult->ids_pos_purch);
		$Array2 = array($pos_purch_id);
		$array = implode(',',array_unique(array_merge($Array1, $Array2)));
		
		
		   $posGuestSql = " UPDATE pos_guest SET 	
				`ids_pos_purch` = '".$array."' WHERE id='".$posGuestResult->id."' ";
				 executeSql($posGuestSql);
		
		
		
		}
		
		
		
	
	
	
			
	
//Guest DEATLS ADD END===================================================================>		
	
//Guest DEATLS ADD END===================================================================>



//$pos_purch_id_array[]	=	encryptor(decrypt, $_REQUEST['printPreviewid']);

	$pos_purch_sql="SELECT * FROM ".TBL_PURCH." AS A  WHERE A.id_shop='".$_SESSION['shop']."'  AND id='".$pos_purch_id."'";
	$resultPosPurch = mysqli_query($connNew, $pos_purch_sql); 
	$numRows = mysqli_num_rows($resultPosPurch);
	$posPurchResult = mysqli_fetch_object($resultPosPurch);
	
	$pos_purch_sqlDoc="SELECT max(id) as ids FROM ".TBL_DOC_TYPE_CONFIG." AS A  WHERE doc_type='".$posPurchResult->id_doc_type_configuration."'";
	$resultPosPurchDoc = mysqli_query($connNew, $pos_purch_sqlDoc); 
	$numRowsDoc = mysqli_num_rows($resultPosPurchDoc);
	$posDocResult = mysqli_fetch_object($resultPosPurchDoc);
	
	
	$pos_AccountDoc="SELECT enable_split_bill_by_sales_account_group FROM ".TBL_DOC_TYPE_CONFIG." AS A  WHERE id='".$posPurchResult->id_doc_type_configuration."'";
	$resultpos_AccountDoc = mysqli_query($connNew, $pos_AccountDoc); 
	$numRowspos_AccountDoc = mysqli_num_rows($resultpos_AccountDoc);
	$posDocResultpos_AccountDoc = mysqli_fetch_object($resultpos_AccountDoc);
	$enable_split_bill_by_sales_account_group	= $posDocResultpos_AccountDoc->enable_split_bill_by_sales_account_group;
	
	
	if($enable_split_bill_by_sales_account_group=='1'){
		$printgroup			  = 	fetchdataSalesGroupPrint($pos_purch_id_array,$grouparray=0);
		}else{

  		$printgroup			  = 	fetchdataprint($pos_purch_id_array,$grouparray=0);
		}
//$printgroup	= fetchdataprint($pos_purch_id_array,$grouparray=0);
	
$bill_print_preview=1;
	
	
	if($bill_print_preview==1){
		include_once("printPreview.php");
		//echo printPreview($printgroup);
	}else{
		printBill($printgroup);
	}
	

session_start();
 $_SESSION["myidpos"] =  $pos_purch_id;


?>
	
	<script>
		var attribute_type = "<?php echo $attribute_type ?>";
		var update_id = "<?php echo $_REQUEST['updateid'] ?>";
		var id_attribute_table = "<?php echo $_REQUEST['id_attribute_table'] ?>";
		var attribute_id = "<?php echo $attribute_id ?>";
		var id_posbilling = "<?php echo $_REQUEST['editid_posbilling']; ?>";
		var net_amount = "<?php echo $_REQUEST['net_amount'] ?>";
		var po_date1 = "<?php echo $_REQUEST['po_date'] ?>";
		var room_id = "<?php echo $room_id ?>";
		var guest_name = "<?php echo $GuestName!=''?$Title.' '.$GuestName.' '.$lastName:'' ?>";
		var guest_id = "<?php echo $guest_id; ?>";
		$(document).ready(function() {
			if (attribute_type == "room" && update_id == "") {
				RemoveDetailRecord();
			}
		});
	function RemoveDetailRecord() {
    //serializeArray();
    bootbox.confirm({
        title: "Settle",
        message: "Settle Bill To Room " + room_id + " (" + guest_name + ") ?",
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> Cancel'
            },
            confirm: {
                label: '<i class="fa fa-check"></i> Confirm'
            }
        },
        callback: function(result) {
            if (result == true) {
                $.ajax({
                    type: "POST",
                    url: 'ajax/AjaxSaveReceipt.php',
                    data: {
						'id_attribute_table' : id_attribute_table,
						'attribute_id' : attribute_id,
						'id_posbilling' : id_posbilling,
						'net_amount' : net_amount,
						'po_date1' : po_date1,
						'guest_id' : guest_id,
					},
                    success: function(result) {
                    }
                });
            }
        }
    });

}
	
	
	
	
	</script>
	
		