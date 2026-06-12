<?php 
ob_start();
//----------------------------------------------------------------------------------
function local_SelectQuery_Mst_Company($tableName = '', $field_name = '' , $EnableOrderBy = ''){
	
	
				$ConnMstSql=array();

					if(in_array('address', $field_name)){
						$namearra= array_search("address",$field_name,true);
						$ConnMstSql[$namearra] = "a.address AS Address ";
					}

					if(in_array('id_area', $field_name)){
						$namearra= array_search("id_area",$field_name,true);
						$ConnMstSql[$namearra] = "e.name AS Area ";
					}

					if(in_array('id_mst_state', $field_name)){						
						$namearra= array_search("id_mst_state",$field_name,true);
						$ConnMstSql[$namearra]= "CASE WHEN a.id_mst_state = 10000 THEN a.other_state ELSE f.name END AS State ";
					}

					if(in_array('city', $field_name)){
						$namearra=array_search("city",$field_name,true);
						$ConnMstSql[$namearra]= "a.city AS City";
					}
					if(in_array('name', $field_name)){						
						$namearra= array_search("name",$field_name,true);
						$ConnMstSql[$namearra]= "a.name AS Company";
					}
				
					if(in_array('id_mst_country_lang', $field_name)){						
						$namearra= array_search("id_mst_country_lang",$field_name,true);
						$ConnMstSql[$namearra] = "CASE WHEN a.id_mst_country_lang = 10000 THEN a.other_country ELSE d.name END AS Country ";
					}

					if(in_array('postcode', $field_name)){
						$namearra= array_search("postcode",$field_name,true);
						$ConnMstSql[$namearra] = "a.postcode AS Postcode ";

					}

					if(in_array('primary_contact', $field_name)){
						$namearra= array_search("primary_contact",$field_name,true);
						$ConnMstSql[$namearra] = "CASE WHEN a.primary_contact = 1 THEN a.primary_mobile WHEN a.primary_contact = 2 THEN a.primary_landline END AS Primary_contact ";
					}

					if(in_array('email', $field_name)){
						$namearra= array_search("email",$field_name,true);
						$ConnMstSql[$namearra] = "a.email AS Email ";
					}

					if(in_array('company_credibility', $field_name)){
						$namearra= array_search("company_credibility",$field_name,true);						
						$ConnMstSql[$namearra] = "CASE WHEN a.company_credibility = 1 THEN 'Allowed' WHEN a.company_credibility = 2 THEN 'Not Allowed' END  As `Company Credibility` ";
					}
					
					ksort($ConnMstSql);
					
					$ConnMstSql	=	implode(",",$ConnMstSql);
					
					
					//=======================================================SEARCH
					if($_REQUEST['search_name'] != ''){
						$SearchConnSQL .= " AND a.`id` =".$_REQUEST['search_name'];
					}
					if($_REQUEST['id_area'] != ''){
						$SearchConnSQL .= " AND a.`id_area` = '".addslashes($_REQUEST['id_area'])."%'";
					}
					if($_REQUEST['status'] != ''){
						$SearchConnSQL .= " AND a.`status` = '".addslashes($_REQUEST['status'])."%'";
					}
					if($_REQUEST['id_default_group'] != ''){
						$SearchConnSQL .= " AND a.`id_company_group` = '".addslashes($_REQUEST['id_default_group'])."'";
					}
					if($_REQUEST['id_email'] != ''){
						$SearchConnSQL .= " AND a.`email` LIKE '%".addslashes($_REQUEST['id_email'])."%' ";
					}
					if($_REQUEST['id_phone'] != ''){
						$SearchConnSQL .= " AND a.`primary_mobile` LIKE '%".addslashes($_REQUEST['id_phone'])."%' ";
						$SearchConnSQL .= " AND a.`primary_landline` LIKE '%".addslashes($_REQUEST['id_phone'])."%' ";
					}
					//=======================================================SEARCH
					
					$query = "SELECT ";
					$query .=	$ConnMstSql;
					$query .= ", a.date_created AS `Creation Date`, a.last_modified AS `Modified Date` 
					
					FROM mst_company a 
					
					LEFT JOIN mst_country_lang d ON a.id_mst_country_lang = d.id_country 
					LEFT JOIN mst_state f ON a.id_mst_state = f.id_state 
					LEFT JOIN mst_portfolio_account e ON a.id_mst_portfolio_account = e.id 
					LEFT JOIN mst_users g ON e.id_user = g.id 
					LEFT JOIN mst_users h ON a.last_modified = h.id 
					WHERE a.id_shop =  '".addslashes($_SESSION['shop'])."' AND a.name!='' $SearchConnSQL 
					ORDER BY a.".$EnableOrderBy." ASC ";
				    return $query;
					
	
		}


		function local_SelectQuery_Mst_Guest($tableName = '', $field_name = '' , $EnableOrderBy = ''){
	
	
				$ConnMstSql=array();

					if(in_array('address', $field_name)){
						$namearra= array_search("address",$field_name,true);
						$ConnMstSql[$namearra] = "a.address AS Address ";
					}

										if(in_array('id_mst_state', $field_name)){						
						$namearra= array_search("id_mst_state",$field_name,true);
						$ConnMstSql[$namearra]= "CASE WHEN a.id_mst_state = 10000 THEN a.other_state ELSE f.name END AS State ";
					}

					if(in_array('city', $field_name)){
						$namearra=array_search("city",$field_name,true);
						$ConnMstSql[$namearra]= "a.city AS City";
					}
					if(in_array('guest_reg_no', $field_name)){
						$namearra=array_search("guest_reg_no",$field_name,true);
						$ConnMstSql[$namearra]= "a.guest_reg_no AS `Guest Reg No`";
					}
					if(in_array('first_name', $field_name)){						
						$namearra= array_search("first_name",$field_name,true);
						$ConnMstSql[$namearra]= "a.first_name AS firstname";

					}
					if(in_array('last_name', $field_name)){						
						$namearra= array_search("last_name",$field_name,true);
						$ConnMstSql[$namearra]= "a.last_name AS Lastname";

					}
				
					if(in_array('id_mst_country_lang', $field_name)){						
						$namearra= array_search("id_mst_country_lang",$field_name,true);
						$ConnMstSql[$namearra] = "CASE WHEN a.id_mst_country_lang = 10000 THEN a.other_country ELSE d.name END AS Country ";
					}

					if(in_array('postcode', $field_name)){
						$namearra= array_search("postcode",$field_name,true);
						$ConnMstSql[$namearra] = "a.postcode AS Postcode ";

					}

					if(in_array('primary_contact', $field_name)){
						$namearra= array_search("primary_contact",$field_name,true);
						$ConnMstSql[$namearra] = "CASE WHEN a.primary_contact = 1 THEN a.primary_mobile WHEN a.primary_contact = 2 THEN a.primary_landline END AS Primary_contact ";
					}

					if(in_array('email', $field_name)){
						$namearra= array_search("email",$field_name,true);
						$ConnMstSql[$namearra] = "a.email AS Email ";
					}
									
					ksort($ConnMstSql);					
					$ConnMstSql	=	implode(",",$ConnMstSql);
										
					//=======================================================SEARCH
					if($_REQUEST['search_name'] != ''){
						$SearchConnSQL .= " AND a.`id` =".$_REQUEST['search_name'];
					}
					
					if($_REQUEST['status'] != ''){
						$SearchConnSQL .= " AND a.`status` = '".addslashes($_REQUEST['status'])."%'";
					}
					//=======================================================SEARCH
					
					$query = "SELECT ";
					$query .=	$ConnMstSql;
					$query .= ", a.date_created AS `Creation Date`, a.last_modified AS `Modified Date` 
					
					FROM `mst_guest` a 
					
					LEFT JOIN `mst_country_lang` c ON a.id_mst_country_lang = c.id_country
					LEFT JOIN `mst_state` d ON a.id_mst_state = d.id_state
					LEFT JOIN  `mst_users` e ON a.id_mst_user_modified_by = e.id
					WHERE a.id_shop =  '".addslashes($_SESSION['shop'])."' $SearchConnSQL 
					ORDER BY a.".$EnableOrderBy." ASC ";
								
				    return $query;
					
	
}



?>
