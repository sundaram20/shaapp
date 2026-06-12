<?php
	include_once("../../config/auto_loader.php");

	if($_REQUEST['transaction_type']!='')
		$transaction_type = "AND transaction_type='".$_REQUEST['transaction_type']."'";
	else
		$transaction_type='';

	if($_REQUEST['charges_account']!='')
		$charges_account = "AND charges_account='".$_REQUEST['charges_account']."'";
	else
		$charges_account ="";
	
	
	if($_REQUEST['ledger_id']==1){
		$_REQUEST['item_charge'] ='id_mst_charges_purchase_local';
		}else{
			$_REQUEST['item_charge'] = 'id_mst_charges_purchase_interstate';
			
			
			}
	
	if($_REQUEST['itemId']!='' && $_REQUEST['itemId']!=0 ){
		  $sqlItem = "SELECT ".$_REQUEST['item_charge']." FROM ".TBL_INV_ITEMS." WHERE id='".$_REQUEST['itemId']."' ";
		$resItem = mysqli_query($connNew,$sqlItem);
		//$item_charge_id = mysqli_fetch_object($resItem)->$_REQUEST['item_charge'];
		if($_REQUEST['ledger_id']==1){
		$item_charge_id = mysqli_fetch_object($resItem)->id_mst_charges_purchase_local;
		}else{
			 $item_charge_id = mysqli_fetch_object($resItem)->id_mst_charges_purchase_interstate;
			$charges_account = "AND charges_account='2'";
	
		$transaction_type = "AND transaction_type='2'";
			
			}

	}

	 $sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' and status = '1' ".$transaction_type." ".$charges_account." ";
	$res = mysqli_query($connNew,$sql);
	
	echo "<option  value=''>Select Tax Register</option>";
	while($row = mysqli_fetch_object($res)){

		if($row->id == $item_charge_id){ $selected = "";
			$selected = "selected='selected'";
		}else{
			$selected = "";
		}
		echo "<option ".$selected." value='".$row->id."'>".$row->name."</option>";
	}



?>	