<?php
include_once("../../config/auto_loader.php");



$first_name = $_REQUEST['booker_first_name'];
$last_name = $_REQUEST['booker_last_name'];
$email = $_REQUEST['booker_email'];
$primary_mobile = $_REQUEST['booker_mobile'];
$city = $_REQUEST['booker_city'];
$postcode = $_REQUEST['booker_postcode']; 
$id=$_POST['id']; 
$id_mst_company_new = $_REQUEST['id_mst_company_new'];



 $insertGrid = "INSERT INTO  ".TBL_COMPANY_CONTACTS." SET
		`first_name`='".$first_name."',
		`last_name`='".$last_name."',
		`email`='".$email."',
		`id_shop_group` = '1',
		`id_shop` = '".addslashes($_SESSION['shop'])."',
		
		`id_mst_company` = '".$id_mst_company_new."',
		`primary_mobile`='".$primary_mobile."',
		`city`='".$city."',
		`status`='1',
		`postcode`='".$postcode."' ";
	
	
	 mysqli_query($connNew,$insertGrid); 
$lastInsertId= mysqli_insert_id();

                $categoryDropDown = '<select class="form-control select2" name="id_mst_company_contacts_new" id="id_mst_company_contacts_new" style="flex-grow: 1; margin-right: 5px;">
                                        <option value="">Select Company Contact</option>';

                $SQL = "select *  from ".TBL_COMPANY_CONTACTS." where status='1' and first_name !='' ";
                $query = mysqli_query($connNew, $SQL);

                while($resultCat = mysqli_fetch_assoc($query)){
                    $selected = ($row->id_mst_company_contacts == $lastInsertId) ? 'selected="selected"' : '';
                    $categoryDropDown .= '<option value="'.$resultCat['id'].'" '.$selected.' >'. $resultCat['first_name'].' '.$resultCat['last_name'].' - '.$resultCat['email'].' - '.$resultCat['primary_mobile'].'</option>';
                }

                echo $categoryDropDown .= '</select>';
            
/*$insertGrid = "UPDATE ".TBL_COMPANY_CONTACTS." SET
		`first_name`='".$first_name."',
		`last_name`='".$last_name."',
		`email`='".$email."',
		`primary_mobile`='".$primary_mobile."',
		`city`='".$city."',
		`postcode`='".$postcode."' ";
	$insertGrid .=" WHERE id = '$id' ";	
	
	 mysqli_query($connNew,$insertGrid); */
	 


	//echo json_encode($data);

	
 ?>


