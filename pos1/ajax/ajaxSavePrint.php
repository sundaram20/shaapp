<?php include_once("../../config/auto_loader.php");
$image_display_path = $UPLOAD_FILES_PATH."/outlets/";
include_once("../include/pos_function.php");


////////////////////////////////////////////////////////////////////////

/*echo '<pre>';

print_r($_REQUEST);

print_r($_SESSION);

echo '</pre>';*/



//die;

$kot_doc_no	=	implode(',' , $_REQUEST['id_kot']);

$pos_bill_type= 2; //'1 For KOT and 2 For sale';



$addSql  = "  INSERT INTO `".TBL_PURCH."` SET

				`id_shop` = '".addslashes($_SESSION['shop'])."',
				`id_doc_type_configuration`	='".addslashes($_REQUEST['id_doc_type_configuration'])."',
				`doc_no`='".addslashes($_REQUEST['po_no'])."',
				`doc_date`='".date('Y-m-d',strtotime($_REQUEST['po_date']))."',
				`mdoc_no`=	'".addslashes($_REQUEST['prefix']).addslashes($_REQUEST['po_no']).addslashes($_REQUEST['suffix'])."',
				`doc_type` = '".addslashes($_REQUEST['id_doc_type_configuration'])."',
				`pos_bill_type`='".$pos_bill_type."',
				`kot_doc_no` = '".$kot_doc_no."',
				`id_attribute_shift` = '".addslashes($_REQUEST['id_attribute_shift'])."',
				`id_attribute_steward` = '".addslashes($_REQUEST['id_attribute_steward'])."',
				`id_discount_ledger` = '".addslashes($_REQUEST['id_discount_ledger'])."',
				`pax` = '".$_REQUEST['noOfPax']."',
				`id_attribute_table` = '".addslashes($_REQUEST['id_attribute_table'])."',
				`discount_percent_additional`= '".$_REQUEST['discount_percent_additional']."',
				`discount_amount_additional`= '".$_REQUEST['discount_amount_additional']."',
				`others_charges_net_amount`= '".$_REQUEST['others_charges_net_amount']."',
				`sgst_total_items` = '".$_REQUEST['sgst_net_amount']."',
				`cgst_total_items`= '".$_REQUEST['cgst_net_amount']."',
				`igst_total_items`= '".$_REQUEST['igst_net_amount']."',
				`cess_total_items`= '".$_REQUEST['cess_net_amount']."',
				`vat_total_items`= '".$_REQUEST['vat_net_amount']."',
				`surcharge_total_items`= '".$_REQUEST['surcharge_net_amount']."',
				`sub_total_items`= '".$_REQUEST['sub_total_items']."',
				`total_discount_items`= '".$_REQUEST['total_discount_amount']."',
				`net_amount_items`= '".$_REQUEST['net_amount']."',
				`round_off_amount`= '".$_REQUEST['round_off_amount']."',
				`grant_total_amount`= '".$_REQUEST['net_amount']."',
				`remarks` = '".$_REQUEST['remarks']."',
				";

$addSql .= "	`date_created` = '".currenDateTime()."'
				,`last_modified` = '".currenDateTime()."'
				,`id_mst_user_created_by` = '".$_SESSION['userId']."'
				,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
				";	

	//echo $addSql;			

	//		die;	

			executeSql($addSql);

			$pos_purch_id= mysql_insert_id();

			$item_BillSplit=array();

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
						  `id_mst_charges_sgst`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_sgst_id'])."',
						  `id_mst_charges_cgst`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cgst_id'])."',
						  `id_mst_charges_igst`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_igst_id'])."',
						  `id_mst_charges_cess`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cess_id'])."',
						  `id_mst_charges_vat`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_vat_id'])."',
						  `id_mst_charges_surcharge`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_surcharge_id'])."',
						  `item_discount_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_discount_percentage'])."',
						  `item_discount_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_discount_amount'])."',
						  `id_mst_charges_sales_interstate`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_mst_charges_sales_Interstate'])."',
						  `id_mst_charges_sales_local`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_mst_charges_sales_local'])."',
						  `main_unit`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_mst_attributes_unit_main'])."',
						  `rate_per_main_unit`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['sale_rate'])."'
						";

		$insertPosKotDetail .= "
						WHERE `id`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id'])."'
						  ";		

		executeSql($insertPosKotDetail);			

							if(!in_array($_REQUEST['id_pos_detail'][$porchdetailID]['item_BillSplit'],$item_BillSplit)){
								$item_BillSplit[]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_BillSplit'];
							}

							 $_REQUEST['id_pos_detail'][$porchdetailID]['item_BillSplit'];
							//$splitIDarray=$_REQUEST['id_pos_detail'][$porchdetailID]['item_BillSplit'];
			}

			//print_r($item_BillSplit);

			//$grouparray1 = array();
			
foreach($_REQUEST['id_pos_detail'] as $porchdetailID =>$dataCode){
	
$grouparray123	= $_REQUEST['id_pos_detail'][$porchdetailID]['item_BillSplit'];

$printgroup['print_BillSplit'][$grouparray123]['item_id'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_id'];

$printgroup['print_BillSplit'][$grouparray123]['item_description'][]= $_REQUEST['id_pos_detail'][$porchdetailID]['item_description'];

$printgroup['print_BillSplit'][$grouparray123]['item_qty'][]= $_REQUEST['id_pos_detail'][$porchdetailID]['item_qty'];

$printgroup['print_BillSplit'][$grouparray123]['item_rate'][]= $_REQUEST['id_pos_detail'][$porchdetailID]['item_rate'];

$printgroup['print_BillSplit'][$grouparray123]['item_amount'][]= $_REQUEST['id_pos_detail'][$porchdetailID]['item_amount'];

$printgroup['print_BillSplit'][$grouparray123]['item_discount_amount'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_discount_amount']; 

$printgroup['print_BillSplit'][$grouparray123]['item_discount_percentage'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_discount_percentage']; 

$printgroup['print_BillSplit'][$grouparray123]['item_tax_account_name'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_tax_account_name']; 

$printgroup['print_BillSplit'][$grouparray123]['item_tax_account_id'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_tax_account_id']; 



$printgroup['print_BillSplit'][$grouparray123]['item_sumTaxPersentge'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_sumTaxPersentge']; 

$printgroup['print_BillSplit'][$grouparray123]['item_sumTaxAmount'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_sumTaxAmount']; 

$printgroup['print_BillSplit'][$grouparray123]['item_Tax_sgst'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_sgst']; 

$printgroup['print_BillSplit'][$grouparray123]['item_Tax_cgst'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cgst']; 

$printgroup['print_BillSplit'][$grouparray123]['item_Tax_igst'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_igst']; 

$printgroup['print_BillSplit'][$grouparray123]['item_Tax_cess'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cess']; 

$printgroup['print_BillSplit'][$grouparray123]['item_Tax_vat'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_vat']; 

$printgroup['print_BillSplit'][$grouparray123]['item_Tax_surcharge'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_surcharge']; 



$printgroup['print_BillSplit'][$grouparray123]['item_TotalAmountItem'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_TotalAmountItem']; 

$printgroup['print_BillSplit'][$grouparray123]['item_Tax_sgst_percentage'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_sgst_percentage']; 

$printgroup['print_BillSplit'][$grouparray123]['item_Tax_cgst_percentage'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cgst_percentage']; 

$printgroup['print_BillSplit'][$grouparray123]['item_Tax_igst_percentage'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_igst_percentage']; 

$printgroup['print_BillSplit'][$grouparray123]['item_Tax_cess_percentage'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cess_percentage']; 

$printgroup['print_BillSplit'][$grouparray123]['item_Tax_vat_percentage'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_vat_percentage']; 

$printgroup['print_BillSplit'][$grouparray123]['item_Tax_surcharge_percentage'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_surcharge_percentage']; 



$printgroup['print_BillSplit'][$grouparray123]['item_Tax_sgst_id'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_sgst_id']; 

$printgroup['print_BillSplit'][$grouparray123]['item_Tax_cgst_id'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cgst_id']; 

$printgroup['print_BillSplit'][$grouparray123]['item_Tax_igst_id'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_igst_id']; 

$printgroup['print_BillSplit'][$grouparray123]['item_Tax_cess_id'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cess_id']; 

$printgroup['print_BillSplit'][$grouparray123]['item_Tax_vat_id'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_vat_id']; 

$printgroup['print_BillSplit'][$grouparray123]['item_Tax_surcharge_id'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_surcharge_id']; 

$printgroup['print_BillSplit'][$grouparray123]['id_mst_charges_sales_Interstate'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_description']; 

$printgroup['print_BillSplit'][$grouparray123]['id_mst_charges_sales_local'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['id_mst_charges_sales_local'];

 

$printgroup['print_BillSplit'][$grouparray123]['id_mst_attributes_unit_main'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['id_mst_attributes_unit_main']; 

$printgroup['print_BillSplit'][$grouparray123]['sale_rate'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['sale_rate']; 

$printgroup['print_BillSplit'][$grouparray123]['discount_amount_additional'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['discount_amount_additional']; 

$printgroup['print_BillSplit'][$grouparray123]['others_charges_net_amount'][]=$_REQUEST['id_pos_detail'][$porchdetailID]['others_charges_net_amount']; 



$printgroup['print_BillSplit'][$grouparray123]['sub_total_items']  +=($_REQUEST['id_pos_detail'][$porchdetailID]['item_amount']*$_REQUEST['id_pos_detail'][$porchdetailID]['item_qty']);

$printgroup['print_BillSplit'][$grouparray123]['total_discount_amount']  +=$_REQUEST['id_pos_detail'][$porchdetailID]['item_discount_amount'];

$printgroup['print_BillSplit'][$grouparray123]['net_amount_items'] +=( $_REQUEST['id_pos_detail'][$porchdetailID]['item_amount']*$_REQUEST['id_pos_detail'][$porchdetailID]['item_qty']-$_REQUEST['id_pos_detail'][$porchdetailID]['item_discount_amount']);



$printgroup['print_BillSplit'][$grouparray123]['sgst_net_amount'] += $_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_sgst'];

$printgroup['print_BillSplit'][$grouparray123]['cgst_net_amount'] += $_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cgst'];

$printgroup['print_BillSplit'][$grouparray123]['igst_net_amount'] += $_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_igst'];

$printgroup['print_BillSplit'][$grouparray123]['cess_net_amount'] += $_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cess'];

$printgroup['print_BillSplit'][$grouparray123]['vat_net_amount']  += $_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_vat'];

$printgroup['print_BillSplit'][$grouparray123]['surcharge_net_amount'] += $_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_surcharge'];


//$printgroup['print_BillSplit'][$grouparray123]['others_charges_net_amount'] += $_REQUEST['id_pos_detail'][$porchdetailID]['others_charges_net_amount'];

//$printgroup['print_BillSplit'][$grouparray123]['additional_discount_amount'] += $_REQUEST['id_pos_detail'][$porchdetailID]['additional_discount_amount'];



$RoundOfAmount	=	(($_REQUEST['id_pos_detail'][$porchdetailID]['item_amount']*$_REQUEST['id_pos_detail'][$porchdetailID]['item_qty'])+$_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_sgst']+$_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cgst']+$_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_igst']+$_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cess']+$_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_vat']+$_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_surcharge']+$_REQUEST['id_pos_detail'][$porchdetailID]['others_charges_net_amount'])-$_REQUEST['id_pos_detail'][$porchdetailID]['discount_amount_additional'];

	/*$NetAmount		=	($TotalAmountFinal+$TotalTax_sgst+$TotalTax_cgst+$TotalTax_igst+$TotalTax_cess+$TotalTax_vat+$TotalTax_surcharge+$_SESSION['AdditionalChargeamount'])-$_SESSION['discountamount'];

*/

$printgroup['print_BillSplit'][$grouparray123]['round_off_amount'] += $RoundOfAmount;

$printgroup['print_BillSplit'][$grouparray123]['net_amount'] +=$RoundOfAmount;

			}

			//print_r($printgroup);


			printBill($printgroup['print_BillSplit']);

			/*foreach($printgroup['print_BillSplit'] as $BillGroupId => $ids){

				echo '<br>Bill No: '.$BillGroupId;

?>

<table id="myTableOrder1" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" >

            <thead>

              <tr>

                <th> S.No.&nbsp;</th>

                <th>Items Name</th>

                <th>Qty</th>

                <th>Rate</th>

                

                </tr>

                </thead>

                <tbody>

                

<?php					$i=1;	foreach($ids['item_id'] as  $billcode2=>$y){

						echo '<br>';?>

                        <tr>

				<td><?php echo $i++;?></td>

				<td><?php echo $ids['item_description'][$billcode2];?></td>

				<td><?php echo $ids['item_qty'][$billcode2];?></td>

				<td><?php echo $ids['item_rate'][$billcode2];?></td>

                </tr>

                        <?php 

							//echo 'ProductName: '.$ids['item_description'][$billcode2];

							//echo $ids['item_description'];

						}

						?>

                        

						 </tbody>

                         </table>

				

				<?php }*/

			

			

			

			

			unset($_SESSION['POSKOT']);

			$pos_purch_id.'_'.'Bill Generated Successfully. Please Wait...';

			echo '<script>window.setTimeout(function() {window.location.href = "'.$SITE_URL.'/pos/managePosKot.php";}, 2000);</script>';

			

			exit;