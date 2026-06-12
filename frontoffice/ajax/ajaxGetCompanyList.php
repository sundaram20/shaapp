<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$id_bill_to_company	=  selectColumn('fo_folio','id_bill_to_company'," WHERE `id_fo_bill` = '".$_REQUEST['idfobill']."'");

$resContact =  mysqli_query($connNew,"SELECT * from ".TBL_COMPANY." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  name !=' '  ");
if(mysqli_num_rows($resContact) > 0){	
	$CompanyList = '<select class="form-control first-input select2" style="width:100% !important;" name="id_bill_to_company" id="id_bill_to_company">';
	
		 $CompanyList .= '<option value="0" >--Select Company---</option>';
		while($rowContact =  mysqli_fetch_object($resContact)){	
			
					$selected = ($id_bill_to_company == $rowContact->id) ? 'selected="selected"' : '';
					$CompanyList .= '<option '.$selected.' value="'.$rowContact->id.'" >'.ucwords($rowContact->name).'</option>';
		}									
			
echo $CompanyList .= '</select>';
}
?>