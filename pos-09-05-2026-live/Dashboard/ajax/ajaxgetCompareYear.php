<?php include_once("../../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$CompareYear=$_REQUEST['CompareYear'];
$financial_year=explode('-',$_REQUEST['Currentfinancialyear']);

//echo "SELECT * from `".TBL_BUDGET_YEAR."` where status='1'  and id_shop='".addslashes($_SESSION['shop'])."'  order by name desc";
//$FinanceStarLastYear=$financial_year[0]-1;
//$FinanceEndLastYear=$financial_year[1]-1;
//$Last_financial_year=$FinanceStarLastYear."-".$FinanceEndLastYear;

$resContact = "SELECT * from `".TBL_BUDGET_YEAR."` where status='1'  and id_shop='".addslashes($_SESSION['shop'])."'  order by name desc";
$resQyery =  mysqli_query($connNew,$resContact);
if(mysqli_num_rows($resQyery) > 0){	
	$contact  =	'<select class="form-control select2" name="CompareYearselected" id="CompareYearselected"  >
				';
		while($rowContact = $db->fetch_object2($resQyery)){	
		
		    $SelectYear =   explode('-',$rowContact->name);
		    $CompareYears =   explode('-',$CompareYear);
		    
		    if($SelectYear[0]<$CompareYears[0]){
                //if($rowContact->name==$Last_financial_year){
                //$selected = 'selected="selected"';
                //}else {
               // $selected = '';
                //}
			$contact .= '<option '.$selected.'  value="'.$rowContact->name.'" >'.$rowContact->name.'</option>';
		    }
			
		}				 
		$contact .=	'</select>';
	}else{
	$contact .= '<option value="">--</option>';
	}
echo $contact;
?>