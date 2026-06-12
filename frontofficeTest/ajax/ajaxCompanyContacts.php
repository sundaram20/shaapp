<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$companyId=$_REQUEST['companyId'];
$contactId=$_REQUEST['contactId'];
$resContact = "SELECT * from `".TBL_COMPANY_CONTACTS."` where status='1' and id_mst_company='".addslashes($companyId)."' ";
$query = mysqli_query($connNew, $resContact);

if(mysqli_num_rows($query) > 0){	
	//$contact  =	'<select class="form-control select2" name="id_contacts" id="id_contacts" data-parsley-errors-container="#contactError" >';
					$contact  =	'<option value="">Select Company Contact</option>';
					while($rowContact = mysqli_fetch_object($query)){
		
			if($contactId==$rowContact->id){
				$selected = 'selected="selected"';
			}else {
				$selected = '';
			}
			
			$contact .= '<option value="'.$rowContact->id.'" '.$selected.'>Name: '.ucfirst($rowContact->title).''.$rowContact->first_name.' '.ucfirst($rowContact->last_name).'  | Email : '.$rowContact->email.' | Mobile : '.$rowContact->mobile.'</option>';
				
			
		}				 
		//$contact .=	'</select>';
	}else{
	$contact .= '<option value="">Select Company Contact </option>';
	}
echo $contact;
?>