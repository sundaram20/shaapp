<?php
include_once("../../config/auto_loader.php");

$id=$_POST['id']; 
$first_name=$_POST['first_name']; 

			  $selectnew="SELECT * FROM ".TBL_GUEST."  where id=$id  ";
				$resnew = mysqli_query($connNew,$selectnew); 	
  			 
					while($rownew = mysqli_fetch_object($resnew)){ 
						$data['id'] =$rownew->id;
						$data['first_name'] =$rownew->first_name;
						$data['last_name'] =$rownew->last_name;
						$data['email'] =$rownew->email;
						$data['city'] =$rownew->city;
						$data['id_mst_country_lang'] =$rownew->id_mst_country_lang;
						$data['id_mst_country_lang_nationality'] = $rownew->id_mst_country_lang_nationality;
						$data['primary_mobile'] =$rownew->primary_mobile;
						$data['id_mst_attributes_title'] =$rownew->id_mst_attributes_title;
						$data['guest_vipstatus'] =$rownew->guest_vipstatus;
						
						$data['proof_type'] =$rownew->proof_type;
						$data['voter_no'] =$rownew->voter_no;
						$data['adhar_no'] =$rownew->adhar_no;
						$data['passport_no'] =$rownew->passport_no;
						
						$data['authority'] =$rownew->authority;
						$data['passport_expiry_date'] =$rownew->passport_expiry_date;
						$data['visa_expiry_date'] =$rownew->visa_expiry_date;
						$data['cform_expiry_date'] =$rownew->cform_expiry_date;
						
						$id_owner_room	= selectColumn('fo_bill','id_owner_room','WHERE `id_fo_folio_to` = "'.$_POST['id_folio'].'"  ');
						$data['owner_guest'] =$id_owner_room==$_POST['id_mst_room_no_allocation']?'1':0;

						$option = "";
						$resCat = selectSql(TBL_COUNTRY_LANG," where id_country='".$rownew->id_mst_country_lang."' ",' ORDER BY `name` ');
						if($db->num_rows2($resCat)){
							$resultCat = $db->fetch_object2($resCat);
							if($resultCat->nationality != ''){
								$option = '<option value="'.htmlentities($resultCat->id_country).'">'.ucfirst($resultCat->nationality) .'</option>';
							}else{
								$option .= '<option value="notFound">Record not found</option>';
								$option .= '<option value="10000">other</option>';
							}
						}
						$data['nationality'] = json_encode($option);
				 	}

 
	echo json_encode($data);

	
 ?>


