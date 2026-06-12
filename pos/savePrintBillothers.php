<?php
include_once("../config/auto_loader.php");
$image_display_path = $UPLOAD_FILES_PATH."/outlets/";
include_once("include/inv_function.php");

if($_GET['submenu']==213 & $_GET['session']==26){
include_once("include/Spafunction.php"); 


} else{
  include_once("include/function1.php"); 

    


}

?>

<?php $session=$_SESSION['id_document']; ?>
<title>RoomStatusHUB | <?php echo currentNavigation_s($session)['submenu']; ?></title>
<?php


/*
if($_REQUEST['id_posbilling']!=''){
	$sql2 = " SELECT * FROM  pos_purch WHERE  `id`='".$_REQUEST['id_posbilling']."'";
	$db->query($sql2); 
	while($row2 = $db->fetch_object()){ 
		$id_pos_details_split = $row2->id_pos_details_split; 
	}
} */
//echo $_REQUEST['sc_sgst'].'<br>';
//echo $_REQUEST['sc_cgst'];
  
//exit;


/*for($i = 0; $i<$itemDetailSizeOf; $i++)
{
  $tyy .= $test[$i].",";
} */
//echo $id_item_name = rtrim($tyy,',');


$itemDetailSizeOf=	sizeof($_REQUEST['itemName']);
$test = $_REQUEST['itemName'];

for($i=0;$i<$itemDetailSizeOf;$i++){
        $id_item_name1 = $_REQUEST['itemName'][$i].',';
         
		 
	//echo "SELECT *  from `".TBL_INV_ITEMS_DETAILS."` WHERE id_item='".$id_item_name1."'";
		
			 
			$sqlitemDetail1 ="SELECT *  from `".TBL_INV_ITEMS_DETAILS."` WHERE id='".$id_item_name1."' ";
			$QuerySQL	=	mysqli_query($connNew,$sqlitemDetail1);
				  $numitemDetail=	mysqli_num_rows($QuerySQL);
				  $id_item_name2 = rtrim($id_item_name1,',');
			if($numitemDetail>0){
				//echo "detail";
				 $sqlitemDetail = "SELECT *  from `".TBL_INV_ITEMS_DETAILS."` WHERE `id` IN ($id_item_name2) ";
				$resitem1 = mysqli_query($connNew,$sqlitemDetail);
				$rowitemDetail = mysqli_fetch_object($resitem1);
				 $newid2 =  $rowitemDetail->id;
				 
			}else{
				//echo "main";
				  $sqlitemDetail = "SELECT *  from `".TBL_INV_ITEMS."` WHERE `id` IN ($id_item_name2) ";
				$resitem1 = mysqli_query($connNew,$sqlitemDetail);
				$rowitemDetail = mysqli_fetch_object($resitem1);
				 $newid3 =  $rowitemDetail->id;
			}
			
			 $string1 .= $newid2.',';
			 $string2 .= $newid3.',';
		
}
   $id_select1 = rtrim($string1,',');

//exit;

$countno = $_REQUEST['counter'];
$date 				   =    date('Y-m-d');
$po_date		 		=	date('Y-m-d' , strtotime(addslashes($_POST['po_date'])));
$doc_type		 	   =	$_REQUEST['doc_type'];
$pos_bill_type	      = 	2; //'1 For KOT and 2 For sale';
//$kot_doc_nokot_doc_no	         =	@implode(',' , $_REQUEST['id_kot']);
$pos_purch_id_array	 =	'';
$item_BillSplit		 =	array();

foreach($_REQUEST['id_pos_detail'] as $porchdetailID =>$dataCode){
		array_push($item_BillSplit,$_REQUEST['id_pos_detail'][$porchdetailID]['item_BillSplit']);
	}	
 //$_REQUEST['id_pos_detail'][$porchdetailID]['id_no'];
///exit;
	
	
sort($item_BillSplit);
$arrayBillSplit = array_unique($item_BillSplit);
count($arrayBillSplit);

foreach($arrayBillSplit as $Count){
	
	
$doc_type=$_REQUEST['doc_type'];
$id_subsection	=	$_REQUEST['outlet'];
 $retunDocConfig	=	docConfigNoValidator($doc_type,$po_date,$id_subsection);

$id_doc_type_configuration	=	$retunDocConfig['id_doc_type_configuration'];
$po_no=$retunDocConfig['po_no'];
$mdoc_no=$retunDocConfig['prefix'].$doc_no.$retunDocConfig['suffix'];	
$prefix=$retunDocConfig['prefix'];
$suffix	= $retunDocConfig['suffix'];

//die;
	//debugData($_REQUEST);
	
	

	$id_pos_details_split	=array();
		
	foreach($_REQUEST['id_pos_detail'] as $porchdetailID =>$dataCode){
		
			if($_REQUEST['id_pos_detail'][$porchdetailID]['item_BillSplit']==$Count){
				
				$id_pos_details_split[]	=	$_REQUEST['id_pos_detail'][$porchdetailID]['id_no'];
				
				$discount_amount_additional = ($_REQUEST['additional_discount_amount']/count($arrayBillSplit));

				$others_charges_net_amount = ($_REQUEST['others_charges_net_amount']/count($arrayBillSplit));
			
				$sgst_net_amount += $_REQUEST['id_pos_detail'][$porchdetailID]['item_sgst'];
				
				$cgst_net_amount += $_REQUEST['id_pos_detail'][$porchdetailID]['item_cgst'];

				$igst_net_amount += $_REQUEST['id_pos_detail'][$porchdetailID]['item_igst'];

				$cess_net_amount += $_REQUEST['id_pos_detail'][$porchdetailID]['item_cess'];

				$vat_net_amount += $_REQUEST['id_pos_detail'][$porchdetailID]['item_vat'];

				$surcharge_net_amount += $_REQUEST['id_pos_detail'][$porchdetailID]['item_sur'];
 		
				$sub_total_items += $_REQUEST['id_pos_detail'][$porchdetailID]['total'];
				
				$sub_total_items1 += $_REQUEST['id_pos_detail'][$porchdetailID]['item_amount'];
			
				$total_item_discount_amount  = $_REQUEST['total_discount_amount'];
				
				$round_off_amount	  = $_REQUEST['round_off_amount'];
				
				}
			}
		
		
			$net_amount			= ($sub_total_items1+$_REQUEST['sc_sgst']+$_REQUEST['sc_cgst']+$_REQUEST['service_charge_amount']+$sgst_net_amount+$cgst_net_amount +$igst_net_amount + $cess_net_amount +$vat_net_amount +$surcharge_net_amount)-($total_item_discount_amount);
			
			$totalBeforeRound	=(($net_amount+$others_charges_net_amount)-$discount_amount_additional);
			$net_amount		 =(($net_amount+$others_charges_net_amount)-$discount_amount_additional);
			
			$RoundOfAmount		=	round((round($net_amount,0)-$totalBeforeRound),2);
			
			$grant_total_amount=stripslashes(round($net_amount,0));
			
			
			$id_pos_details_split2	=implode(',' , $id_pos_details_split);
			
			$sub_total_items	  = $_REQUEST['sub_total_items'];
			
			if($_REQUEST['id_posbilling']==''){
				
			       $addSql  = "  INSERT INTO `".TBL_PURCH."` SET
						`id_mst_outlet` = '".$_REQUEST['outlet']."',
				        `id_pos_details_split` = '".$id_select."',
						`id_attribute_steward` = '".$_REQUEST['id_attribute_steward']."',
						`id_attribute_shift` = '".$_REQUEST['id_attribute_shift']."',

						`id_shop` = '".$_SESSION['shop']."',
						`id_doc_type_configuration`	='".$id_doc_type_configuration."',				
						`doc_no`='".$po_no."',
						`doc_date`='".date('Y-m-d',strtotime($_REQUEST['po_date']))."',
						`mdoc_no`=	'".($_REQUEST['prefix']).($po_no).($_REQUEST['suffix'])."',
						`doc_type` = '".$doc_type."',				
				    `pos_bill_type`='".$pos_bill_type."',
					`discount_amount_additional`= '".$discount_amount_additional."',
					`others_charges_net_amount`= '".$others_charges_net_amount."',	
				    `sc_charges_net_amount` = '".$_REQUEST['service_charge_amount']."',		
					`sgst_total_items` = '".$sgst_net_amount."',
					`cgst_total_items`= '".$cgst_net_amount."',
					`igst_total_items`= '".$igst_net_amount."',
					`sub_total_items`= '".$sub_total_items."',
					`total_discount_items`= '".$total_item_discount_amount."',
					`grant_total_amount`= '".$grant_total_amount."',
					`net_amount_items`= '".$net_amount."',
				    `round_off_amount`= '".$RoundOfAmount."',
					`cess_total_items`= '".$cess_net_amount."',
					`vat_total_items`= '".$vat_net_amount."',
					`surcharge_total_items`= '".$surcharge_net_amount."',
					`sc_reverse`= '".$_REQUEST['revServiceCharge']."',
					`sc_sgst`= '".$_REQUEST['sc_sgst']."',
					`sc_cgst`= '".$_REQUEST['sc_cgst']."',
					`remarks` = '".$_REQUEST['remarks']."',
						";
				$addSql .= " `date_created` = '".currenDateTime()."'
					,`last_modified` = '".currenDateTime()."'
					,`id_mst_user_created_by` = '".$_SESSION['userId']."'
					,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
				    ";	
					
			executeSql($addSql);
			$lastInsertId_purch1= $db->insert_id();
			$pos_purch_id = $lastInsertId_purch1;

	
			
			}else{
				
				
$auditquery2 = "SELECT * From `".TBL_PURCH."` WHERE id = '".$_REQUEST['id_posbilling']."'  ";
  $auditresSQL2 = mysqli_query($connNew, $auditquery2);	
	while($auditrow2 = mysqli_fetch_object($auditresSQL2)){ 
	
		$c1 = $auditrow2 -> id_attribute_steward;
		$c2 = $auditrow2 -> doc_date;
		$c3 = $auditrow2 -> id_mst_outlet;
		$c4 = $auditrow2 -> pax;
		$c5 = $auditrow2 -> mdoc_no;
		//$c4 = $auditrow1 -> remarks;
		 
		if($c1 != $_REQUEST['id_attribute_steward']){
			$old_data = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$c1."'");
			$new_data = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$_REQUEST['id_attribute_steward']."' ");
		   $ch5 ="Steward Name Details Changed from " .   $old_data ." - to - " . $new_data;
		} 
		

	$c22 =	date('Y-m-d',strtotime($c2));
		
		if($c22 != date('Y-m-d',strtotime($_REQUEST['po_date']))){
		   $ch7 ="Date Details Changed from " .   date('d-m-Y',strtotime($c2)) ." - to - ". date('d-m-Y',strtotime($_REQUEST['po_date']));
		}
		if($c3 != $_REQUEST['outlet']){
		   $old_data = selectColumn(mst_outlets,'name'," WHERE `id` = '".$c3."'");
			$new_data = selectColumn(mst_outlets,'name'," WHERE `id` = '".$_REQUEST['outlet']."'  ");
		   $ch4 ="Outlet Details Changed from " .   $old_data ." - to - " . $new_data;
		}
	}				
				
			
			    $editSql  = "  UPDATE `".TBL_PURCH."` SET
					`id_mst_outlet` = '".$_REQUEST['outlet']."',
					`id_pos_details_split` = '".$id_pos_details_split2."',
					`id_attribute_steward` = '".$_REQUEST['id_attribute_steward']."',
											`id_attribute_shift` = '".$_REQUEST['id_attribute_shift']."',

					`id_shop` = '".$_SESSION['shop']."',
					`id_doc_type_configuration`	='".$id_doc_type_configuration."',	
					`doc_date`='".date('Y-m-d',strtotime($_REQUEST['po_date']))."',
					`doc_type` = '".$doc_type."',				
					`pos_bill_type`='".$pos_bill_type."',
					`discount_amount_additional`= '".$discount_amount_additional."',
					`others_charges_net_amount`= '".$others_charges_net_amount."',	
				    `sc_charges_net_amount` = '".$_REQUEST['service_charge_amount']."',		
					`sgst_total_items` = '".$sgst_net_amount."',
					`cgst_total_items`= '".$cgst_net_amount."',
					`igst_total_items`= '".$igst_net_amount."',
					`sub_total_items`= '".$sub_total_items."',
					`total_discount_items`= '".$total_item_discount_amount."',
					`grant_total_amount`= '".$grant_total_amount."',
					`net_amount_items`= '".$net_amount."',
				    `round_off_amount`= '".$RoundOfAmount."',
					`cess_total_items`= '".$cess_net_amount."',
					`vat_total_items`= '".$vat_net_amount."',
					`surcharge_total_items`= '".$surcharge_net_amount."',
					`sc_reverse`= '".$_REQUEST['revServiceCharge']."',
					`sc_sgst`= '".$_REQUEST['sc_sgst']."',
					`sc_cgst`= '".$_REQUEST['sc_cgst']."',
					`remarks` = '".$_REQUEST['remarks']."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."' 	
					WHERE id='".$_REQUEST['id_posbilling']."'  ";
					
			executeSql($editSql);
			
			$pos_purch_id=$_REQUEST['id_posbilling'];
			
		 $auditquery11 = "SELECT * From `".TBL_PURCH_DETAILS."` WHERE id_pos_purch = '".$pos_purch_id."'  ";
			$auditresSQL11 = mysqli_query($connNew, $auditquery11);	
				while($auditrow11 = mysqli_fetch_object($auditresSQL11)){
				   $qty[] = $auditrow11 -> qty;
				   $item_discount_percent[] = $auditrow11 -> item_discount_percent;
				   $item = $auditrow11 -> item_description.',';
				    // $qty.'-'.$item.'<br>';
				}	
			
			 
		executeSql("DELETE from `".TBL_PURCH_DETAILS."` where `id_pos_purch`='".$pos_purch_id."' "); 
			 
			}
			
			
			$pos_purch_id_array = array();
			
			$pos_purch_id_array[] = $pos_purch_id;	
								
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
			$total_item_discount_amount ='';			
			$id_pos_details_split='';

	}
//die;

	$item_BillSplit = array();
	
$k='0';	

foreach($_REQUEST['id_pos_detail'] as $porchdetailID =>$dataCode){
	
	$item_id =  $_REQUEST['id_pos_detail'][$porchdetailID]['items_idd'];
	if($item_id != ''){
		$id_item = $_REQUEST['id_pos_detail'][$porchdetailID]['items_idd'];
	}else{
		$id_item = $_REQUEST['id_pos_detail'][$porchdetailID]['mainitem_id'];
	}
	$id_item4 = $_REQUEST['id_pos_detail'][$porchdetailID]['mainitem_id'];
	
		  $insertPosKotDetail = "INSERT INTO `".TBL_PURCH_DETAILS."` SET 
						`id_pos_purch`='".addslashes($pos_purch_id)."',
						`id_mst_outlet`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['outlet'])."',
						`id_mst_items`='".addslashes($id_item4)."',
						`id_mst_items_details`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['subitems_id'])."',
						
				`id_mst_charges_sgst`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_mst_charges_sgst'])."',
						`id_mst_charges_cgst`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_mst_charges_cgst'])."',
						`id_mst_charges_igst`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_mst_charges_igst'])."',		
						
						
						`id_mst_charges_sales_local`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_mst_charges_sales_local'])."',
						`item_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_amount'])."',
						`item_description`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_name'])."',
						`item_code`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_code'])."',
						`qty`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_qty'])."',
						  `adj_qty`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_qty'])."',
						  `item_discount_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['discount'])."',
						  `item_discount_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_dis'])."',
						  `id_mst_charges_sales_interstate`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_mst_charges_sales_Interstate'])."',
						  `main_unit`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_unit'])."',
						  `rate_per_main_unit`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_rate'])."',
						  `item_sgst_amount` = '".$_REQUEST['id_pos_detail'][$porchdetailID]['item_sgst']."',
						  `item_sgst_percent` = '".$_REQUEST['Tax_sgst_percentage']."',
						  `item_cgst_amount`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['item_cgst']."',
						  `item_cgst_percent`= '".$_REQUEST['Tax_cgst_percentage']."',
						  `item_igst_amount`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['item_igst']."',
						  `item_igst_percent`= '".$_REQUEST['Tax_igst_percentage']."',
						  `item_cess_amount`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['item_cess']."',
						  `item_vat_amount`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['item_vat']."',
						  `item_surcharge_amount`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['item_sur']."',
						  `item_cess_percent`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['Tax_cess_percentage']."',
						  `item_surcharge_percent`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['Tax_surcharge_percentage']."',
						  `item_vat_percent`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['Tax_vat_percentage']."',
						  `date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'	
						  
						  ";
			executeSql($insertPosKotDetail);
			$lastInsertId_purch= $db->insert_id();	
			

if($k < count($qty)){
	$qtyy =  " Qty Changed from " . $qty[$k]  ." - to - " .  $_REQUEST['id_pos_detail'][$porchdetailID]['item_qty']  ;
}else{
	$qtyy = " Details Added " ;
}

if($k < count($item_discount_percent)){
$dis = " Discount Percentage Changed from " . $item_discount_percent[$k]  ." - to - " . $_REQUEST['id_pos_detail'][$porchdetailID]['discount'] ;
}else{
	$dis = "" ;
}


 $auditquery1 = "SELECT * From `".TBL_PURCH_DETAILS."` WHERE id = '".$lastInsertId_purch."'  ";
			$auditresSQL1 = mysqli_query($connNew, $auditquery1);	
			while($auditrow1 = mysqli_fetch_object($auditresSQL1)){
				 $id = $auditrow1 -> id;
				 $item = $auditrow1 -> item_description;
				 $pos_id = $auditrow1 -> id_pos_purch;
				$qy[] = $auditrow1 -> qty;
				$sc = $auditrow1 -> sc_reverse;
				$sc_amonut = $auditrow1 -> sc_charges_net_amount;
				 
			$bill = selectColumn(TBL_PURCH,'mdoc_no'," WHERE `id` = '".$pos_id."'");
			
				if($qty[$k] != $_REQUEST['id_pos_detail'][$porchdetailID]['item_qty']){
					
					 $ch2 .= $item. $qtyy.'<br>'  ;
					 
				}
				
				if($item_discount_percent[$k] != $_REQUEST['id_pos_detail'][$porchdetailID]['discount']){
					 $ch1 .= $item ." Discount Percentage Changed from " . $item_discount_percent[$k]  ." - to - " . $_REQUEST['id_pos_detail'][$porchdetailID]['discount'] ;
				}
				
				if($sc=='1'){
					 $ch3 = "Sc Charges Amount ".$sc_amonut." Added " ;
				}
			}	
			

if($k < count($item_discount_percent)){
 $chh1 = $ch1;
}else{
	$chh1 = '';
}
if($_REQUEST['id_posbilling']!=''){
	 $chh2 = $ch2;
}else{
	 $chh2 = '';
}
 $countqty1 = count($qy);

$number1 = $countqty1;
$countqty = $number1 % 10;
$countqty; 
	

$k++;

 $auditeditSql = " INSERT audit_trail SET 
	`voucher_id` = '".addslashes($pos_purch_id)."',
	`tables_name` = 'pos_purch , pos_purch_details',
	`form_code` = 'LaundrySpaOthers',
	`changes` =  '".addslashes($chh1).",".addslashes($chh2).",".addslashes($ch3).",".addslashes($ch4).",".addslashes($ch5).",".addslashes($ch6).",".addslashes($ch7)."',
	`date_created` = '".currenDateTime()."',
	`last_modified` = '".currenDateTime()."',
	`id_mst_user_modified_by` = '".$_SESSION['userId']."',
	`id_mst_user_created_by` = '".$_SESSION['userId']."',
	`type` = 2 ";


if($chh1=='' && $chh2=='' && $ch3=='' && $ch4=='' && $ch5=='' && $ch6=='' && $ch7==''){
	
}else{
	executeSql($auditeditSql);
}



$ch1='';
$ch2='';
$ch3='';
$ch4='';
$ch5='';
$ch6='';
$ch7='';


				if(!in_array($_REQUEST['id_pos_detail'][$porchdetailID]['item_BillSplit'],$item_BillSplit)){
					$item_BillSplit[]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_BillSplit'];
				}

				 $_REQUEST['id_pos_detail'][$porchdetailID]['item_BillSplit'];
				//$splitIDarray=$_REQUEST['id_pos_detail'][$porchdetailID]['item_BillSplit'];

			}
	 //$pos_purch_id_array	=	array('0' => 12,'1' => 13 ); 
	

//$rowcount = $countqty;

$rowcount = count($qty);
$number = $k;
$lastDigit = $number % 10;
$lastDigit; // 6
 
//echo '<br>'.$rowcount.'<br>';//5
// echo $countqty.'<br>';//4
 

$value = $rowcount - $countqty;
$value1 = $countqty - $rowcount;

if($rowcount > $countqty){
	$delete1 =  $value. " Item Row Removed ";
}else{
	$delete1 =  '';
}



 /*
 if($value != 0){
	$delete1 =  $value. " Item Details Deleted ";
 }else{
	 $delete1 = 'insert';
 }*/
 
if($_REQUEST['id_posbilling'] == ''){
	$delete = '';
}else{
	$delete= $delete1;
}


 	 $auditeditSql1 = " INSERT audit_trail SET 
	`voucher_id` = '".addslashes($pos_purch_id)."',
	`tables_name` = 'pos_purch , pos_purch_details',
	`form_code` = 'LaundrySpaOthers',
	`changes` =  '".addslashes($delete)."',
	`date_created` = '".currenDateTime()."',
	`last_modified` = '".currenDateTime()."',
	`id_mst_user_modified_by` = '".$_SESSION['userId']."',
	`id_mst_user_created_by` = '".$_SESSION['userId']."',
	`type` = 2 ";


if($delete==''){
	
}else{
	executeSql($auditeditSql1);
}
 


	$printgroup	= fetchdataprint($pos_purch_id_array,$grouparray=0);
	$bill_print_preview=1;
	
	//echo $pos_purch_id;
	
	if($bill_print_preview==1){
		include_once("printPreviewlaundryspaothers.php");
		//echo printPreview($printgroup);
	}else{
		printBill($printgroup);
	}
	
//echo 'qweqwwqwee';die;
		/*	die;
			

			

			unset($_SESSION['POSKOT']);

			$pos_purch_id.'_'.'Bill Generated Successfully. Please Wait...';

			echo '<script>window.setTimeout(function() {window.location.href = "'.$SITE_URL.'/pos/managePosKot.php";}, 2000);</script>';

			

			exit;*/

 /* $auditquery1 = "SELECT * From `".TBL_PURCH."` WHERE id = '".$_REQUEST['id_posbilling']."'  ";
  $auditresSQL1 = mysqli_query($connNew, $auditquery1);	
	while($auditrow1 = mysqli_fetch_object($auditresSQL1)){ 
	
		$c1 = $auditrow1 -> id_attribute_steward;
		$c2 = $auditrow1 -> doc_date;
		$c3 = $auditrow1 -> id_mst_outlet;
		//$c4 = $auditrow1 -> remarks;
		 
		if($c1 != $_REQUEST['id_attribute_steward']){
			$old_data = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$c1."'");
			$new_data = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$_REQUEST['id_attribute_steward']."'  ");
		   $ch2 ="Steward Name Details Changed from " .   $old_data ." - to - " . $new_data;
		} 
		if($c2 != date('Y-m-d',strtotime($_REQUEST['po_date']))){
		   $ch3 ="Date Details Changed from " .   date('d-m-Y',strtotime($c2)) ." - to - ". date('d-m-Y',strtotime($_REQUEST['po_date']));
		}
		if($c3 != $_REQUEST['outlet']){
		   $old_data = selectColumn(mst_outlets,'name'," WHERE `id` = '".$c3."'");
			$new_data = selectColumn(mst_outlets,'name'," WHERE `id` = '".$_REQUEST['outlet']."'  ");
		   $ch4 ="Outlet Details Changed from " .   $old_data ." - to - " . $new_data;
		}
	}	*/
	
	
	/*
	if($_REQUEST['id_posbilling']==''){
		//echo "insert";
		  $insertPosKotDetail = "INSERT INTO `".TBL_PURCH_DETAILS."` SET 
						`id_pos_purch`='".addslashes($pos_purch_id)."',
						`id_mst_outlet`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['outlet'])."',
						`id_mst_items`='".addslashes($id_item)."',
						`id_mst_items_details`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['subitems_id'])."',
						`id_mst_charges_sales_local`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_mst_charges_sales_local'])."',
						`item_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_amount'])."',
						`item_description`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_name'])."',
						`item_code`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_code'])."',
						`qty`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_qty'])."',
						  `adj_qty`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_qty'])."',
						  `item_discount_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['discount'])."',
						  `item_discount_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_dis'])."',
						  `id_mst_charges_sales_interstate`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_mst_charges_sales_Interstate'])."',
						  `main_unit`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_unit'])."',
						  `rate_per_main_unit`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_rate'])."',
						  `item_sgst_amount` = '".$_REQUEST['id_pos_detail'][$porchdetailID]['item_sgst']."',
						  `item_sgst_percent` = '".$_REQUEST['Tax_sgst_percentage']."',
						  `item_cgst_amount`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['item_cgst']."',
						  `item_cgst_percent`= '".$_REQUEST['Tax_cgst_percentage']."',
						  `item_igst_amount`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['item_igst']."',
						  `item_igst_percent`= '".$_REQUEST['Tax_igst_percentage']."',
						  `item_cess_amount`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['item_cess']."',
						  `item_vat_amount`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['item_vat']."',
						  `item_surcharge_amount`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['item_sur']."',
						  `item_cess_percent`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['Tax_cess_percentage']."',
						  `item_surcharge_percent`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['Tax_surcharge_percentage']."',
						  `item_vat_percent`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['Tax_vat_percentage']."',
						  `date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'	
						  
						  ";
			executeSql($insertPosKotDetail);			  
	}else{
		//echo "update";
		 $insertPosKotDetail = "UPDATE `".TBL_PURCH_DETAILS."` SET 
						`item_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_amount'])."',
						`item_description`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_name'])."',
						`item_code`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_code'])."',
						`qty`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_qty'])."',
						  `adj_qty`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_qty'])."',
						  `item_discount_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['discount'])."',
						  `item_discount_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_dis'])."',
						  `id_mst_charges_sales_interstate`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_mst_charges_sales_Interstate'])."',
						  `main_unit`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_unit'])."',
						  `rate_per_main_unit`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_rate'])."',
						  `item_sgst_amount` = '".$_REQUEST['id_pos_detail'][$porchdetailID]['item_sgst']."',
						  `item_sgst_percent` = '".$_REQUEST['Tax_sgst_percentage']."',
						  `item_cgst_amount`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['item_cgst']."',
						  `item_cgst_percent`= '".$_REQUEST['Tax_cgst_percentage']."',
						  `item_igst_amount`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['item_igst']."',
						  `item_igst_percent`= '".$_REQUEST['Tax_igst_percentage']."',
						  `item_cess_amount`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['item_cess']."',
						  `item_vat_amount`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['item_vat']."',
						  `item_surcharge_amount`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['item_sur']."',
						  `item_cess_percent`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['Tax_cess_percentage']."',
						  `item_surcharge_percent`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['Tax_surcharge_percentage']."',
						  `item_vat_percent`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['Tax_vat_percentage']."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."' 	
						  where `id`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['id_no']."';
						  
						  ";
			executeSql($insertPosKotDetail);	
	}
	
	*/
?>

<?php
session_start();
 $_SESSION["myid"] =  $pos_purch_id;


?>

	
<script>
<?php if($bill_print_preview==1){ ?>
$('document').ready(function(){
	
	var vals = '<?php echo $pos_purch_id; ?>';
	$.ajax({
		type: "GET",
		url: 'printPreviewlaudryspaothers.php?value='+vals,
		data: 'value='+vals,
		success: function (result) {
			//alert(vals);
			$( "#counter1" ).val(vals);
			
			$.ajax({
				type: "GET",
				url: 'editOutletBill.php?myid='+vals,
				//data: 'value='+vals,
				success: function (result) {
					//alert(vals);
					//$( "#counter1" ).val(vals);
				}
			});
			
	 	}
	});
	
});
<?php } ?>
	
</script>