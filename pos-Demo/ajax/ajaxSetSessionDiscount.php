<?php include_once("../../config/auto_loader.php");
if($_REQUEST['value']>0){
$discount_ledger_percentage  =  selectColumn(TBL_CHARGES,'percentage'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".$_REQUEST['value']."'");
//debugData($_SESSION);
}
//Com

if($discount_ledger_percentage==0 || $_REQUEST['value']==0){ echo 'shafeer';
unset($_SESSION['LINELEVEL']);
$_REQUEST['total_discount_amount']=0;
unset($_REQUEST['total_discount_amount']);
}

/*** printing end ***/


