<?php include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],'exportTable','view');
///////////////////////////////////////////////////////////////////////////////////
function exportTable($dataBaseName ='', $tableName = '', $fileType = '',$itemType=''){
	global $appConnect;
	global $connNew;
	$err = 0;
	@mysqli_select_db($dataBaseName,$conn);
	//endDbConnection-------------------------------------
	if($dataBaseName != ''){
		if($tableName != ''){
			if($fileType != ''){
				//Export type---------------------------------------	
				if($fileType == 'csv'){
					$contentType = '"Content-Type: text/comma-separated-values"';
					$fileEnding = "csv";
				}elseif($fileType = 'xls'){
					$contentType = '"Content-Type: application/vnd.ms-excel"';
					$fileEnding = "xls";
				}elseif($fileType = 'doc'){
					$contentType = '"Content-Type: msword"';
					$fileEnding = "doc";
				}
				//End export type-----------------------------------
				$filename = tempnam(sys_get_temp_dir(), $fileType);
				$dataWrite = '';
				// Write column names
				//--------------------------------------------------
				/*SELECT a.name As Company , a.address As Address, b.name As Area
FROM  `fs_company` AS a,  `fs_areas_assign` AS b
WHERE a.`id_shop` =2
AND a.area = b.id*/
				if($tableName	=='mst_company'){

					$query=" SELECT b.field_value AS `Company Group`, a.name AS Company, a.email AS Email, a.secondary_email AS `Secondry Email`, d.name AS Country, a.other_country AS `Other Country`, f.name AS State, a.other_state AS `Other State`, a.postcode AS Postcode, a.city AS City, a.address AS Address, CASE WHEN a.primary_contact_type=1 THEN 'Mobile' WHEN a.primary_contact_type=2 THEN 'Landline' END AS `Primary Contact Type`, a.primary_mobile AS `Primary Mobile`, a.primary_landline AS `Primary Landline`, CASE WHEN a.secondary_contact_type=1 THEN 'Landline' WHEN a.secondary_contact_type=2 THEN 'Mobile' END AS `Secondary Contact Type`, a.secondary_mobile AS `Secondary Mobile`,  a.secondary_landline AS `Secondary Landline`, a.fax AS Fax, e.name AS Area, CASE WHEN a.company_credibility=1 THEN 'Credit Allowed' WHEN a.company_credibility = 2 THEN 'Credit Not Allowed' END AS `Company Credibility`,a.credit_limit AS `Credit Limit`, a.id_nac AS `National Account`, a.deals_in AS `Deals In`, a.details AS Details, CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS `Status`, a.date_created AS `Creation Date`, a.last_modified AS `Modified Date`, k.name AS `Created By`, h.name AS `Modified By` FROM mst_company a 
						LEFT JOIN mst_attributes b ON a.id_mst_attributes_company_group = b.id 
						LEFT JOIN mst_company_area c ON a.deals_in = c.id 
						LEFT JOIN mst_country_lang d ON a.id_mst_country_lang = d.id_country 
						LEFT JOIN mst_state f ON a.id_mst_state = f.id_state 
						LEFT JOIN mst_portfolio_account e ON a.id_mst_portfolio_account = e.id 
                        LEFT JOIN mst_users k ON a.id_mst_user_created_by = k.id
						LEFT JOIN mst_users h ON a.id_mst_user_modified_by = h.id WHERE a.id_shop = '".addslashes($_SESSION['shop'])."' AND a.name!='' ORDER BY a.name ASC ";	
					$fileName = 'Company Data Base Report As On ';	
					$result = mysqli_query($connNew, $query);
				}

				if($tableName	=='inv_items'){

					$query=" SELECT a.item_code AS `Item Code`, a.name AS `Item`, b.field_value AS `Item Type`, c.field_value AS `Item Group Main`, d.field_value AS `Item Group `, s.field_value AS `Item Main Unit`, e.field_value AS `Item Alt Unit`, ch.name AS `Sales Account Local`, chi.name AS `Sales Account Interstate`, chp.name AS `Purchase Account Local`, chpi.name AS `Purchase Account Interstate`, f.field_value AS `Store`, g.field_value AS `Printer`,  a.ids_mst_outlet AS `Outlet`, a.conversion_qty AS `Conversion Qty`, a.min_qty AS `Min Qty`, a.max_qty AS `Max Qty`, a.rol AS Rol, a.roq AS Roq,
						a.item_class AS `Item Class`, a.bal_qty AS `Bal Qty`,a.open_qty AS `Open Qty`,a.open_amount AS `Open Amount`, a.last_purchase_rate AS `Last Purchase Rate`, 
						CASE WHEN a.item_enable_desc_billing = 1 THEN 'Active' ELSE 'Inactive' END AS `Enable Desc Billing`,
						CASE WHEN a.stockable_enable_disable = 1 THEN 'Active' ELSE 'Inactive' END AS `Stock Enable Disable`,
						CASE WHEN a.edit_name_enable_disable = 1 THEN 'Active' ELSE 'Inactive' END AS `Edit Name Enable Disable`,
						CASE WHEN a.item_get_expiry_details=1 THEN 'Active' ELSE 'Inactive' END AS `Item Get Expiry Detyail`,
						CASE WHEN a.item_production_item=1 THEN 'Active' ELSE 'Inactive' END AS `Item Production Item`,
						CASE WHEN a.item_allow_additional=1 THEN 'Active' ELSE 'Inactive' END AS `Item Allowed Additional`,
						CASE WHEN a.item_disable=1 THEN 'Active' ELSE 'Inactive' END AS `Item Disable`,
						a.sale_rate AS `Sale Rate`, a.purchase_rate AS `Purchase Rate`,

						CASE WHEN a.batch_details=1 THEN 'Active' ELSE 'Inactive' END AS `Batch Details`,
						CASE WHEN a.item_details=1 THEN 'Active' ELSE 'Inactive' END AS `Item Details`,
						a.display_order AS `Display Order`,
						CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS `Status`,
						a.date_created AS `Creation Date`, a.last_modified AS `Modified Date`, uc.name AS `Created By`,
						um.name AS `Modified By`
						FROM `inv_items` a 
						LEFT JOIN mst_attributes b ON a.id_mst_attributes_item_type = b.id
						LEFT JOIN mst_attributes c ON a.id_mst_attributes_group_main = c.id
						LEFT JOIN mst_attributes d ON a.id_mst_attributes_group_sub = d.id
                        LEFT JOIN mst_attributes s ON a.id_mst_attributes_unit_main = s.id
						LEFT JOIN mst_attributes e ON a.id_mst_attributes_unit_alt = e.id
						LEFT JOIN mst_charges ch ON a.id_mst_charges_sales_local = ch.id
						LEFT JOIN mst_charges chi ON a.id_mst_charges_sales_interstate = chi.id
						LEFT JOIN mst_charges chp ON a.id_mst_charges_purchase_local = chp.id
						LEFT JOIN mst_charges chpi ON a.id_mst_charges_purchase_interstate = chpi.id
						LEFT JOIN mst_attributes f ON a.id_mst_attributes_store = f.id
						LEFT JOIN mst_attributes g ON a.id_mst_attributes_printer = g.id
						LEFT JOIN mst_users uc ON a.id_mst_user_created_by = uc.id
						LEFT JOIN mst_users um ON a.id_mst_user_modified_by = um.id
						WHERE  a.id_shop = '".addslashes($_SESSION['shop'])."
						h' AND a.name!='' ORDER BY a.name ASC ";	
					$fileName = 'Items Data Base Report As On ';	
					$result = mysqli_query($connNew, $query);
				}


				if($tableName	=='mst_users'){
					
					$query = "SELECT 
							 a.name AS `Name`,
							 a.user_name AS `User Name`,
							 a.password AS `Password`,
							 b.name AS `User Level`,
							 a.email AS `Email`,
							 c.field_value AS `Designation`,
							 a.other_designation AS `Other Designation`,
							  CASE WHEN a.primary_contact_type=1 THEN 'Mobile' WHEN a.primary_contact_type=2 THEN 'Landline' END AS `Primary Contact Type`,
							 a.primary_mobile AS `Primary Mobile`,
							 a.primary_landline AS `Primary Landline`,
							  CASE WHEN a.secondary_contact_type=1 THEN 'Landline' WHEN a.secondary_contact_type=2 THEN 'Mobile' END AS `Secondary Contact Type`,
							 a.secondary_mobile AS `Secondary Mobile`,
							 a.secondary_landline AS `Secondary Landline`,
							 a.ids_mst_hotels AS `Hotel Access`,
							 a.ids_mst_outlet AS `Outlet Access`,
							 d.name AS `Team`,
							 a.ids_mst_team AS 'Members of Team',
							 a.company AS `Company Name`,
							 a.address AS `Address 1`,
							 a.address AS `Address 2`,
							 a.city AS `City`,
							 e.name AS State,
							 a.zip AS `ZIP Code`,
							 a.skype AS `Skype`,
							 a.comments AS `Comments`,
							 a.ip_address AS `IP Address`,
							 a.browser AS `Browser`,
							 a.id_session AS `id session`,
							 a.dsr_num_days AS `DSR Back Date Allow`,
							 a.geo_location_interval AS `App Goe-Location intervals`,
							  CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS `Status`,
							 a.date_created AS `Creation Date`,
							 a.last_modified AS `Modified Date`,
							 uc.name AS `Created By`,
							 um.name AS `Modified By`
							
							 FROM `mst_users` a
							 LEFT JOIN `mst_user_levels` b ON a.id_mst_user_levels = b.id
							 LEFT JOIN `mst_attributes` c ON 
							 a.id_mst_attributes_designations = c.id
							 LEFT JOIN `mst_team` d ON a.id_mst_team = d.id
							 LEFT JOIN `mst_state` e ON a.id_mst_state = e.id_state
							 LEFT JOIN `mst_users` uc ON a.id_mst_user_created_by = uc.id
							 LEFT JOIN `mst_users` um ON a.id_mst_user_modified_by = um.id
							 WHERE a.id_shop = '".$_SESSION['shop']."'  ORDER BY a.name ASC ";
				
					$fileName = 'Items Data Base Report As On ';	
					$result = mysqli_query($connNew, $query);

				}

				if($tableName	=='mst_guest'){
					
					$query = "SELECT 
							 a.guest_reg_no AS `Guest Reg No`,
							 c.field_value AS `Title`,
							 a.first_name AS `First Name`,
							 a.last_name AS `Last Name`,
							 CASE WHEN a.primary_contact_type=1 THEN 'Mobile' WHEN a.primary_contact_type=2 THEN 'Landline' END AS `Primary Contact Type`,
							 a.primary_mobile AS `Primary Mobile`,
							 a.primary_landline AS `Primary Landline`,
							  CASE WHEN a.secondary_contact_type=1 THEN 'Landline' WHEN a.secondary_contact_type=2 THEN 'Mobile' END AS `Secondary Contact Type`,
							 a.secondary_mobile AS `Secondary Mobile`,
							 a.secondary_landline AS `Secondary Landline`,
							 a.email AS `Email`,
							 a.address AS `Address`,
							 a.city AS `City`,
							 a.postcode AS `Postcode`,
							 d.name AS `Country`,
							 a.other_country AS `Other Country`,
							 f.name AS `State`,
							 a.other_state AS `Other State`,
							 n.nationality AS `Nationality`,
							 a.other_nationality AS `Other Nationality`,
							 a.date_birth_month AS `Date Birth Month`,
							 a.date_birth_day AS `Date Birth Day`,
							 
							 a.date_anniversary_month AS `Anniversary Month`,
							 a.date_anniversary_day AS `Anniversary Day`,

							 CASE WHEN a.gender=1 THEN 'Male' WHEN a.gender=2 THEN 'Female' WHEN a.gender=3 THEN 'Other' END AS `Gender`,
							 
							 CASE WHEN a.guest_vipstatus=1 THEN 'VIP' WHEN a.guest_vipstatus=2 THEN 'CIP'  END AS `Guest VIP Status`,
							 CASE WHEN a.membership_status=0 THEN 'Non Member' WHEN a.membership_status=1 THEN 'Member' END AS `Membership Status`,
							 a.guest_note AS `Guest Note`,
							 CASE WHEN a.proof_type=1 THEN 'Voter Id' WHEN a.proof_type=2 THEN 'Adhar' WHEN a.proof_type=3 THEN 'Passport' END AS `Id Proof Type`,
							 a.voter_no AS `Voter no`,
							 a.adhar_no AS 'Adhar no',
							 a.passport_no AS `Passport no`,
							 a.authority AS `Authority`,
							 a.passport_expiry_date AS `Passport Expiry Date`,
							 
							 CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS `Status`,
							 a.date_created AS `Creation Date`,
							 a.last_modified AS `Modified Date`,
							 uc.name AS `Created By`,
							 um.name AS `Modified By`
							
							 FROM `mst_guest` a
							 LEFT JOIN `mst_attributes` c ON 
							 a.id_mst_attributes_title = c.id
							 LEFT JOIN `mst_country_lang` d ON a.id_mst_country_lang = d.id_country
							 LEFT JOIN `mst_state` f ON a.id_mst_state = f.id_state
							 LEFT JOIN `mst_country_lang` n ON a.id_mst_country_lang_nationality = n.id_country
							 LEFT JOIN `mst_users` uc ON a.id_mst_user_created_by = uc.id
							 LEFT JOIN `mst_users` um ON a.id_mst_user_modified_by = um.id
							 WHERE a.id_shop = '".$_SESSION['shop']."'  ORDER BY a.first_name ASC ";
				
					$fileName = 'Guests Data Base Report As On ';	
					$result = mysqli_query($connNew, $query);

				}

				if($tableName	=='mst_hotels'){

				$query = "SELECT 
							a.name AS `Hotel Name`, 
							a.hotel_code AS `Hotel Code`,
							b.name AS `Hotel Category`,
							a.address AS `Address`,
							d.name AS `Country`,
							a.other_country AS `Other Country`,
							f.name AS `State`,
							a.other_state AS `Other State`, 
							a.city AS `City`,  
							a.pincode AS `Pincode`,
							z.name AS `Zonal`,
							a.gstin As `GSTIN`,
							a.pan AS `PAN`,
							a.google_map_url As `Google Map Url`,
							a.review_url AS `Review Url`,
							a.review_url AS `Website Url`,
							a.hotel_tagline AS `Hotel Tagline`,
							a.brief_description AS `Description`, 

							CASE WHEN a.primary_contact_type=1 THEN 'Mobile' WHEN a.primary_contact_type=2 THEN 'Landline' END AS `Primary Contact Type`,
							 a.primary_mobile AS `Primary Mobile`,
							 a.primary_landline AS `Primary Landline`,
							  CASE WHEN a.secondary_contact_type=1 THEN 'Landline' WHEN a.secondary_contact_type=2 THEN 'Mobile' END AS `Secondary Contact Type`,
							 a.secondary_mobile AS `Secondary Mobile`,
							 a.secondary_landline AS `Secondary Landline`,
							 a.email AS `Email`,

							 a.general_manager AS `General Manager`,
							 a.general_manager_contact AS `General Manager Contact`,
							 a.general_manager_email AS `General Manager Email`,
							 a.bank_account_legal_name AS `Account Name`, 
							
							 a.bank_account_no AS `Account No.`, 
							 a.bank_account_type AS `Account Type`,
							 a.bank_name AS `Bank Name`, 
							 a.bank_ifsc_code AS `IFSC Code`, 
							 a.bank_swift_code AS `SWIFT Code`, 
							 a.bank_branch AS `Bank Branch`,

							 a.ids_mst_hotel_general_services AS `General Services`, 
							 a.ids_mst_hotel_outdoor_services AS `Outdoor Actitvites`,
							 a.ids_mst_hotel_dining_services AS `Dining Services`,
							 a.ids_mst_hotel_kids_related_services AS `Kids Related Facilities`, 
							 a.ids_mst_hotel_conference_services AS `Conferences & Meetings Services`, 
							 CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS `Status`,
							 a.display_order AS `Display Order`, 
							 a.date_created AS `Creation Date`,
							 a.last_modified AS `Modified Date`,
							 uc.name AS `Created By`,
							 um.name AS `Modified By` 
							
							FROM `mst_hotels` a 

							LEFT JOIN `mst_hotel_category` b ON a.id_mst_hotel_category = b.id 
							LEFT JOIN `mst_country_lang` d ON a.id_mst_country_lang = d.id_country
							LEFT JOIN `mst_state` f ON a.id_mst_state = f.id_state
							LEFT JOIN `mst_zonal` z ON a.id_mst_zonal = z.id
							
							LEFT JOIN `mst_users` uc ON a.id_mst_user_created_by = uc.id
							LEFT JOIN `mst_users` um ON a.id_mst_user_modified_by = um.id
							WHERE a.id_shop = '".$_SESSION['shop']."'  ORDER BY a.name ASC ";	
					
					$fileName = 'Hotels Data Base Report As On ';	
					$result = mysqli_query($connNew, $query);
				}


				if($tableName	=='mst_attributes'){
					$query = "SELECT 
									a.field_value AS `Designation`,
									a.field_description AS `Description`,
									CASE WHEN a.status = 1 THEN 'Active' ELSE 'Inactive' END AS `Status`,
									a.date_created AS `Creation Date`,
							 		a.last_modified AS `Modified Date`,
							 		uc.name AS `Created By`,
							 		um.name AS `Modified By` 
									FROM `mst_attributes` a
									LEFT JOIN `mst_users` uc ON a.id_mst_user_created_by = uc.id
									LEFT JOIN `mst_users` um ON a.id_mst_user_modified_by = um.id
									WHERE a.table_name ='designations' AND a.id_shop = '".addslashes($_SESSION['shop'])."' ORDER BY a.field_value ASC ";			
					$fileName = 'Designation Data Base Report As On ';	
					$result = mysqli_query($connNew, $query);
				}

				
				if($tableName	=='fs_hotel_type'){
					$query = "SELECT 
									a.name AS `Category Title`,
									a.description AS `Description`,
									a.date_created AS `Creation Date`,
									a.last_modified AS `Modified Date`,
									b.name AS `Modified By`,
									a.status AS Status
									FROM `fs_hotel_type` a
									LEFT JOIN `fs_users` b ON a.last_modified_by = b.id
								WHERE a.id_shop = '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC ";			
					$result = mysqli_query($connNew, $query);
				}

				if($tableName	=='fs_room_type'){
					$query = "SELECT 
									a.name AS `Hotel Type`,
									a.description AS Description,
									a.date_created AS `Creation Date`,
									a.last_modified AS `Modified Date`,
									b.name AS `Modified By`,
									a.status AS Status
									FROM `fs_room_type` a
									LEFT JOIN  `fs_users` b ON a.last_modified_by = b.id
								WHERE a.id_shop = '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC ";			
					$result = mysqli_query($connNew, $query);
				}

				if($tableName	=='fs_general_services'){
					$query = "SELECT 
									a.name AS `General service Title`,
									a.date_created AS `Creation Date`,
									a.last_modified AS `Modified Date`,
									b.name AS `Modified By`,
									a.status AS Status
									FROM `fs_general_services` a
									LEFT JOIN  `fs_users` b ON a.last_modified_by = b.id
								WHERE a.id_shop = '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC";			
					$result = mysqli_query($connNew, $query);
				}

				if($tableName	=='fs_outdoor_activities'){
					$query = "SELECT 
									a.name AS `Outdoor Activity Title`,
									a.date_created AS `Creation Date`,
									a.last_modified AS `Modified Date`,
									b.name AS `Modified By`,
									a.status AS Status
									FROM `fs_outdoor_activities` a
									LEFT JOIN  `fs_users` b ON a.last_modified_by = b.id
								WHERE a.id_shop = '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC";			
					$result = mysqli_query($connNew, $query);
				}

				if($tableName	=='fs_dining_services'){
					$query = "SELECT 
									a.name AS `Dining Service`,
									a.date_created AS `Creation Date`,
									a.last_modified AS `Modified Date`,
									b.name AS `Modified By`,
									a.status AS Status
									FROM `fs_dining_services` a
									LEFT JOIN  `fs_users` b ON a.last_modified_by = b.id
								WHERE a.id_shop = '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC";			
					$result = mysqli_query($connNew, $query);
				}

				if($tableName	=='fs_hotel_services'){
					$query = "SELECT 
									a.name AS `Hotel Service`,
									a.date_created AS `Creation Date`,
									a.last_modified AS `Modified Date`,
									b.name AS `Modified By`,
									a.status AS Status
									FROM `fs_hotel_services` a
									LEFT JOIN  `fs_users` b ON a.last_modified_by = b.id
								WHERE a.id_shop = '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC";
											
					$result = mysqli_query($connNew, $query);
				}

				if($tableName	=='fs_company_group'){
					$query = "SELECT 
									a.name AS `Comapany Name`,
									a.reduction AS `Reduction`,
									a.price_display_method AS `Price Display`,
									a.status AS Status,
									a.date_created AS `Creation Date`,
									a.last_modified AS `Modified Date`,
									b.name AS `Modified By`
									FROM `fs_company_group` a
									LEFT JOIN  `fs_users` b ON a.last_modified_by = b.id
								WHERE a.id_shop = '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC ";
											
					$result = mysqli_query($connNew, $query);
				}

				if($tableName	=='fs_company_area'){
					$query = "SELECT 
									a.name AS `Comapany Domain`,
									a.status AS Status,
									a.date_created AS `Creation Date`,
									a.last_modified AS `Modified Date`,
									b.name AS `Modified By`
									FROM `fs_company_area` a
									LEFT JOIN  `fs_users` b ON a.last_modified_by = b.id
								WHERE a.id_shop = '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC ";
											
					$result = mysqli_query($connNew, $query);
				}

				if($tableName	=='fs_operator_master'){
					$query = "SELECT 
									a.name AS `Operator Title`,
									a.status AS Status,
									a.date_created AS `Creation Date`,
									a.last_modified AS `Modified Date`,
									b.name AS `Modified By`
									FROM `fs_operator_master` a
									LEFT JOIN  `fs_users` b ON a.last_modified_by = b.id
								WHERE a.id_shop = '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC ";
											
					$result = mysqli_query($connNew, $query);
				}

				if($tableName	=='fs_customer'){
					/* $query = "SELECT 
									b.name AS `Company`,
									a.first_name AS `First Name`,
									a.last_name  AS `Last Name`,
									des.name AS `Designation`,
									a.mobile AS Mobile,
									a.email AS 	Email,
									a.dob AS `DOB`,
									a.doa AS `DOA`,
									a.address AS Address,
									d.name AS `State`,
									a.city AS City,
									c.name AS `Country`,
									a.postcode AS Postcode,
									a.phone AS Phone,
									a.status AS Status,
									a.date_created AS `Creation Date`,
									a.last_modified AS `Modified Date`,
									e.name AS `Modified By`
									FROM `fs_customer` a
									LEFT JOIN `fs_company` b ON a.id_company = b.id_company
									LEFT JOIN `fs_country_lang` c ON a.id_country = 

									c.id_country
									LEFT JOIN `fs_state` d ON a.id_state = d.id_state
									LEFT JOIN  `fs_users` e ON a.last_modified_by = e.id
									LEFT JOIN `fs_designation_master` des ON a.designation=des.id
								WHERE a.id_shop = '".addslashes($_SESSION['shop'])."' AND b.name!='' AND type = 2 ORDER BY b.name";*/
							$query = "SELECT 
									b.name AS `Company`,
									a.first_name AS `First Name`,
									a.last_name  AS `Last Name`,
									des.name AS `Designation`,
									a.mobile AS Mobile,
									a.email AS 	Email,
									a.dob AS `DOB`,
									a.doa AS `DOA`,
									a.address AS Address,
									d.name AS `State`,
									a.city AS City,
									c.name AS `Country`,
									a.postcode AS Postcode,
									a.phone AS Phone,
									a.status AS Status,
									a.date_created AS `Creation Date`,
									a.last_modified AS `Modified Date`,
									e.name AS `Modified By`,
                                    
                                    y.name as `Executive Name`
									FROM `fs_customer` a
									LEFT JOIN `fs_company` b ON a.id_company = b.id_company
									LEFT JOIN `fs_country_lang` c ON a.id_country = 

									c.id_country
									LEFT JOIN `fs_state` d ON a.id_state = d.id_state
									LEFT JOIN  `fs_users` e ON a.last_modified_by = e.id
                                   
									LEFT JOIN `fs_designation_master` des ON a.designation=des.id
                                     LEFT JOIN `fs_areas_assign` x ON b.area=x.id
                                      LEFT JOIN  `fs_users` y ON x.user_id = y.id
								WHERE a.id_shop = '".addslashes($_SESSION['shop'])."' AND b.name!='' AND type = 2 ORDER BY b.name";
					$fileName = 'Contact Database As On ';						
					$result = mysqli_query($connNew, $query);
				}


				if($tableName	=='fs_areas_assign'){
					 $query = "SELECT 
								 a.name as Area,
								 b.name AS `Executive Name`,
								 a.date_created AS `Area Creation Date`,
								 a.last_modified AS `Area Modified date`,
								 b.name AS `Area Modified By`,
								 a.status AS Status 
								 FROM `fs_areas_assign` a 
								 LEFT JOIN `fs_users` b ON a.user_id = b.id
								WHERE a.`id_shop` ='".addslashes($_SESSION['shop'])."'
								ORDER BY a.name ASC";
								
					$result = mysqli_query($connNew, $query);
				}

				
				if($tableName	=='fs_cancellation_master'){
					$query = "SELECT 
							 a.name AS `Operator Title`,
							 a.status AS `Status`,
							 a.date_created AS `Creation Date`,
							 a.last_modified AS `Modified Date`,
							 b.name AS `Modified by`
							 FROM `fs_cancellation_master` a
							 LEFT JOIN  `fs_users` b ON a.last_modified_by = b.id
							 WHERE a.id_shop= '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC ";
					
					$result = mysqli_query($connNew, $query);
				}

				if($tableName	=='fs_amendment_remarks'){
					$query = "SELECT 
							 a.name AS `Amendment Remarks Title`,
							 a.status AS `Status`,
							 a.date_created AS `Creation Date`,
							 a.last_modified AS `Modified Date`,
							 b.name AS `Modified by`
							 FROM `fs_amendment_remarks` a
							 LEFT JOIN  `fs_users` b ON a.last_modified_by = b.id
							 WHERE a.id_shop= '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC ";
					
					$result = mysqli_query($connNew, $query);
				}

				if($tableName	=='fs_user_levels'){
					$query = "SELECT 
							 a.name AS `User Level Title`,
							 a.status AS `Status`,
							 a.date_created AS `Creation Date`,
							 a.last_modified AS `Modified Date`,
							 b.name AS `Modified By`
							 FROM `fs_user_levels` a
							 LEFT JOIN  `fs_users` b ON a.last_modified_by = b.id
							 WHERE a.id_shop= '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC ";
					
					$result = mysqli_query($connNew, $query);
				}

				if($tableName	=='fs_users'){
					$query = "SELECT 
							 a.name AS 'Name',
							 b.name AS `User Level`,
							 a.status AS `Status`,
							 a.email AS `Email`,
							 a.phone AS `Phone`,
							 a.mobile As `mobile`,
							 a.hotel_access AS `Hotel Access`,
							 a.company AS `Company Name`,
							 a.address AS `Address 1`,
							 a.address AS `Address 2`,
							 a.city AS `City`,
							 a.zip AS `ZIP Code`,
							 a.last_login `Last Login`,
							 a.last_logout `Last Logout`,
							 a.date_created AS `Creation Date`,
							 a.last_modified AS `Modified Date`,
							 c.name  AS `Modified By`
							 FROM `fs_users` a
							 LEFT JOIN `fs_user_levels` b ON a.user_level = b.id
							 LEFT JOIN `fs_users` c ON a.last_modified_by = c.id
							 WHERE a.id_shop = '".$_SESSION['shop']."'  ORDER BY a.name ASC ";
					
					$result = mysqli_query($connNew, $query);
				}

				if($tableName	=='fs_segment_master'){
					$query = "SELECT 
							 a.name AS `Segment Code`, a.description AS `Description`, a.status AS `Status`, a.date_created AS `Creation Date`, a.last_modified AS `Modified Date`, b.name AS `Modified By` FROM `fs_segment_master` a
							 LEFT JOIN  `fs_users` b ON a.last_modified_by = b.id
							 WHERE a.id_shop= '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC ";
					
					$result = mysqli_query($connNew, $query);
				}
				
				if($tableName	=='fs_user_permissions'){
					 $query = "SELECT 
							 b.name AS `User Level`,
							 c.name AS `Module`,
							 a.user_actions AS `User Can Perform Below Actions`,
							 a.date_created AS `Creation Date`,
							 a.last_modified As `Modified Date`,
							 a.status AS `Status`,
							 d.name AS `Modified By`
							 FROM `fs_user_permissions` a
							 LEFT JOIN `fs_user_levels` b ON a.user_level_id = b.id
							 LEFT JOIN `fs_modules` c ON a.module_id = c.id
							 LEFT JOIN `fs_users` d ON a.last_modified_by = d.id
							 WHERE a.id_shop = '".addslashes($_SESSION['shop'])."'
							 ";
					$result = mysqli_query($connNew, $query);
				}

				if($tableName == 'fs_budget_master'){
					$query = "SELECT 
					b.username AS `User Name` ,
					c.name AS `Hotel Name`,
					a.month AS `Month (01-mm-YYYY)`,
					a.qty AS `Room Nights`,
					a.month_value AS `Value (in Lacs)`
					FROM `fs_budget_master` a
					LEFT JOIN `fs_users` b ON a.id_user = b.id
					LEFT JOIN `fs_hotels` c ON a.id_hotel = c.id
					WHERE a.id_shop = ".$_SESSION['shop']." ORDER BY c.name,a.month";
					
					$result = mysqli_query($connNew, $query);
				}
				
				
				if (!$result) {
					die("Query to show fields from table failed");
				}
				$fields_num = mysqli_num_fields($result);
				//echo "<h1>Table: {$table}</h1>";
				if($fileType != 'csv'){

						$dataWrite .= "<table border='1'>
						<tr><td style='height:50px;font-weight:bold;text-align:center;font-size:1.5em;' colspan='".$fields_num."' >".$fileName.date('d-F-Y')."</td></tr>
						<tr>
						";
						// printing table headers
						for($i=0; $i<$fields_num; $i++){
							$field = mysqli_fetch_field($result);
							$dataWrite .= "<th>{$field->name}</th>";
						}
						$dataWrite .= "</tr>";
						// printing table rows
				
					while($row = mysqli_fetch_row($result)){
						//print_r($row); exit;

						if($tableName == 'inv_items'){
							$ids_mst_outlets = explode(',', $row[13]);
							$outlets = array();
							for($i= 0 ; $i < count($ids_mst_outlets); $i++){
								$sql = "SELECT name from `mst_outlets` WHERE id = '".$ids_mst_outlets[$i]."' ";
								$res =  mysqli_query($connNew, $sql);
								$name = mysqli_fetch_row($res);
								array_push($outlets,$name[0]);
							}
							$outlets_name = array();
							for($i=0 ; $i < count($outlets) ; $i++){
								$outlets_name = $outlets[$i][0];
							}
							$string = implode(',',$outlets);
							$row[13] = $string;
							//echo "<br/>"; exit;

						}


						if($tableName == 'mst_users'){						
							$hotel_access = explode(',',$row[13]);
							$hotels = array();
							for($i= 0 ; $i < count($hotel_access); $i++){
								$sql1 = "SELECT name from `mst_hotels` WHERE id = '".$hotel_access[$i]."' ";
								$res1 = mysqli_query($connNew, $sql1);
								$name = mysqli_fetch_row($res1);
								array_push($hotels,$name[0]);
							}
							$hotels_name = array();
							for($i=0 ; $i < count($hotels) ; $i++){
								$hotels_name = $hotels[$i][0];
							}
							$string = implode(',',$hotels);
							$row[13] = $string;
						}

						if($tableName == 'mst_users'){
							$ids_mst_outlets = explode(',', $row[14]);
							$outlets = array();
							for($i= 0 ; $i < count($ids_mst_outlets); $i++){
								$sql2 = "SELECT name from `mst_outlets` WHERE id = '".$ids_mst_outlets[$i]."' ";
								$res2 =  mysqli_query($connNew, $sql2);
								$name = mysqli_fetch_row($res2);
								array_push($outlets,$name[0]);
							}
							$outlets_name = array();
							for($i=0 ; $i < count($outlets) ; $i++){
								$outlets_name = $outlets[$i][0];
							}
							$string = implode(',',$outlets);
							$row[14] = $string;
							//echo "<br/>"; exit;

						}

						if($tableName == 'mst_users'){
							$ids_mst_team = explode(',', $row[16]);
							$teams = array();
							for($i= 0 ; $i < count($ids_mst_team); $i++){
								$sql3 = "SELECT name from `mst_team` WHERE id = '".$ids_mst_team[$i]."' ";
								$res3 =  mysqli_query($connNew, $sql3);
								$name = mysqli_fetch_row($res3);
								array_push($teams,$name[0]);
							}
							$teams_name = array();
							for($i=0 ; $i < count($teams) ; $i++){
								$teams_name = $teams[$i][0];
							}
							$string = implode(',',$teams);
							$row[16] = $string;
							//echo "<br/>"; exit;

						}

						if($tableName == 'mst_hotels'){
							$ids_mst_general = explode(',', $row[35]);
							$general = array();
							for($i= 0 ; $i < count($ids_mst_general); $i++){
								$sql3 = "SELECT name from `mst_hotel_general_services` WHERE id = '".$ids_mst_general[$i]."' ";
								$res3 =  mysqli_query($connNew, $sql3);
								$name = mysqli_fetch_row($res3);
								array_push($general,$name[0]);
							}
							$general_name = array();
							for($i=0 ; $i < count($general) ; $i++){
								$general_name = $general[$i][0];
							}
							$string = implode(',',$general);
							$row[35] = $string;
							//echo "<br/>"; exit;

						}

						if($tableName == 'mst_hotels'){
							$ids_mst_outdoor = explode(',', $row[36]);
							$outdoor = array();
							for($i= 0 ; $i < count($ids_mst_outdoor); $i++){
								$sql3 = "SELECT name from `mst_hotel_outdoor_services` WHERE id = '".$ids_mst_outdoor[$i]."' ";
								$res3 =  mysqli_query($connNew, $sql3);
								$name = mysqli_fetch_row($res3);
								array_push($outdoor,$name[0]);
							}
							$outdoor_name = array();
							for($i=0 ; $i < count($outdoor) ; $i++){
								$outdoor_name = $general[$i][0];
							}
							$string = implode(',',$outdoor);
							$row[36] = $string;
							//echo "<br/>"; exit;

						}

						if($tableName == 'mst_hotels'){
							$ids_mst_dining = explode(',', $row[37]);
							$dining = array();
							for($i= 0 ; $i < count($ids_mst_dining); $i++){
								$sql3 = "SELECT name from `mst_hotel_dining_services` WHERE id = '".$ids_mst_dining[$i]."' ";
								$res3 =  mysqli_query($connNew, $sql3);
								$name = mysqli_fetch_row($res3);
								array_push($dining,$name[0]);
							}
							$dining_name = array();
							for($i=0 ; $i < count($dining) ; $i++){
								$dining_name = $dining[$i][0];
							}
							$string = implode(',',$dining);
							$row[37] = $string;
							//echo "<br/>"; exit;

						}

						if($tableName == 'mst_hotels'){
							$ids_mst_kids = explode(',', $row[38]);
							$kids = array();
							for($i= 0 ; $i < count($ids_mst_kids); $i++){
								$sql3 = "SELECT name from `mst_hotel_kids_related_services` WHERE id = '".$ids_mst_kids[$i]."' ";
								$res3 =  mysqli_query($connNew, $sql3);
								$name = mysqli_fetch_row($res3);
								array_push($kids,$name[0]);
							}
							$kids_name = array();
							for($i=0 ; $i < count($kids) ; $i++){
								$kids_name = $kids[$i][0];
							}
							$string = implode(',',$kids);
							$row[38] = $string;
							//echo "<br/>"; exit;

						}

						if($tableName == 'mst_hotels'){
							$ids_mst_conference = explode(',', $row[39]);
							$conference = array();
							for($i= 0 ; $i < count($ids_mst_conference); $i++){
								$sql3 = "SELECT name from `mst_hotel_conference_services` WHERE id = '".$ids_mst_conference[$i]."' ";
								$res3 =  mysqli_query($connNew, $sql3);
								$name = mysqli_fetch_row($res3);
								array_push($conference,$name[0]);
							}
							$conference_name = array();
							for($i=0 ; $i < count($conference) ; $i++){
								$conference_name = $conference[$i][0];
							}
							$string = implode(',',$conference);
							$row[39] = $string;
							//echo "<br/>"; exit;

						}


						if($tableName == 'mst_user_permissions'){
							$permissions = explode(',',$row[2]);
							$values = array();
							for($i=0 ; $i < count($permissions) ; $i++){
								if($permissions[$i] == 1){
									array_push($values,'View');
								}
								elseif($permissions[$i] == 2){
									array_push($values,'Add');
								}
								elseif($permissions[$i] == 3){
									array_push($values,'Update');
								}
								elseif($permissions[$i] == 4){
									array_push($values,'Activate');
								}
								elseif($permissions[$i] == 5){
									array_push($values,'Deactivate');
								}
								elseif($permissions[$i] == 6){
									array_push($values,'Delete');
								}
								elseif($permissions[$i] == 7){
									array_push($values,'Export');
								}
								elseif($permissions[$i] == 8){
									array_push($values,'Import');
								}
							}
							$string = implode(',  ',$values);
							$row[2] = $string; 
						}

						//Replacing Ids with text
						if($tableName	=='mst_company'){

							if($row[13] == 1){
								$row[13] = "Credit Allowed";
							}
							elseif($row[13] == 2){
								$row[13] = "Credit Not Allowed";
							}

							if($row[14] == 0){
								$row[14] = "Inactive";
							}
							elseif($row[14] == 1){
								$row[14] = "Active";
							}

							if($row[9] == ""){
								$row[9] = "N/A";
							}
						}

						if( $tableName	=='fs_company_area'  OR $tableName	=='fs_operator_master' OR $tableName	=='fs_cancellation_master' OR $tableName	=='fs_amendment_remarks' OR $tableName	=='fs_user_levels' ){
							if($row[1] == 1 ){
								$row[1] = "Active";
							}
							elseif($row[1] == 0){
								$row[1] = "Inactive";
							}
						}

						if($tableName	=='fs_customer'){
							if($row[14] == 1 ){
								$row[14] = "Active";
							}
							elseif($row[14] == 0){
								$row[14] = "Inactive";
							}
						}

						if($tableName	=='fs_series_master' OR $tableName	=='fs_segment_master' OR $tableName	=='fs_users' ){
							if($row[2] == 1 ){
								$row[2] = "Active";
							}
							elseif($row[2] == 0){
								$row[2] = "Inactive";
							}
						}

						if($tableName	=='fs_company_group'){
							if($row[2] == 1 ){
								$row[2] = "TAX included";
							}
							elseif($row[2] == 0){
								$row[2] = "TAX excluded";
							}

							if($row[3] == 1 ){
								$row[3] = "Active";
							}
							elseif($row[3] == 0){
								$row[3] = "Inactive";
							}


						}

						if($tableName	=='fs_general_services' OR $tableName	=='fs_outdoor_activities' OR $tableName	=='fs_dining_services' OR $tableName	=='fs_hotel_services'){
							if($row[4] == 1 ){
								$row[4] = "Active";
							}
							elseif($row[4] == 0){
								$row[4] = "Inactive";
							}
						}

						if($tableName	=='fs_room_type'){
							if($row[5] == 1 ){
								$row[5] = "Active";
							}
							elseif($row[5] == 0){
								$row[5] = "Inactive";
							}

							if($row[1] == '' OR $row[1] == 0){
								$row[1] = "N/A";
							}
						}

						if($tableName	=='fs_hotel_type'){
							if($row[5] == 1 ){
								$row[5] = "Active";
							}
							elseif($row[5] == 0){
								$row[5] = "Inactive";
							}

							if($row[1] == '' OR $row[1] == 0){
								$row[1] = "N/A";
							}
						}

						if($tableName	=='mst_areas_assign' OR $tableName	=='mst_user_permissions'){
							if($row[5] == 1 ){
								$row[5] = "Active";
							}
							elseif($row[5] == 0){
								$row[5] = "Inactive";
							}
						}

						if($tableName	=='fs_hotels'){
							if($row[19] == 1 ){
								$row[19] = "Active";
							}
							elseif($row[19] == 0){
								$row[19] = "Inactive";
							}

							if($row[20] == 1 ){
								$row[20] = "Short Term";
							}
							elseif($row[20] == 2){
								$row[20] = "Long Term";
							}
							elseif($row[20] == 0){
								$row[20] = "N/A";
							}

							if($row[24] == 1 ){
								$row[24] = "Active";
							}
							elseif($row[24] == 0){
								$row[24] = "Inactive";
							}
						}

						//Ids replacement end

						$dataWrite .= "<tr>";
						foreach($row as $cell){
							$dataWrite .= "<td>$cell</td>";
						}	
					
						$dataWrite .= "</tr>";
					}
				//-------------------------------------------------
				}elseif($fileType == 'csv'){
					for($i=0; $i<$fields_num; $i++){
						$field = mysqli_fetch_field($result);
						$endOfLine = ($i == ($fields_num-1))? true:false;
						$dataWrite .= csvFieldFormating($field->name,$endOfLine,'');
					}
					while($row = mysqli_fetch_row($result)){

						if($tableName == 'inv_items'){
							$ids_mst_outlets = explode(',', $row[13]);
							$outlets = array();
							for($i= 0 ; $i < count($ids_mst_outlets); $i++){
								$sql = "SELECT name from `mst_outlets` WHERE id = '".$ids_mst_outlets[$i]."' ";
								$res =  mysqli_query($connNew, $sql);
								$name = mysqli_fetch_row($res);
								array_push($outlets,$name[0]);
							}
							$outlets_name = array();
							for($i=0 ; $i < count($outlets) ; $i++){
								$outlets_name = $outlets[$i][0];
							}
							$string = implode(',',$outlets);
							$row[13] = $string;
							//echo "<br/>"; exit;

						}

						if($tableName == 'mst_users'){						
							$hotel_access = explode(',',$row[13]);
							$hotels = array();
							for($i= 0 ; $i < count($hotel_access); $i++){
								$sql1 = "SELECT name from `mst_hotels` WHERE id = '".$hotel_access[$i]."' ";
								$res1 = mysqli_query($connNew, $sql1);
								$name = mysqli_fetch_row($res1);
								array_push($hotels,$name[0]);
							}
							$hotels_name = array();
							for($i=0 ; $i < count($hotels) ; $i++){
								$hotels_name = $hotels[$i][0];
							}
							$string = implode(',',$hotels);
							$row[13] = $string;
						}

						if($tableName == 'mst_users'){
							$ids_mst_outlets = explode(',', $row[14]);
							$outlets = array();
							for($i= 0 ; $i < count($ids_mst_outlets); $i++){
								$sql2 = "SELECT name from `mst_outlets` WHERE id = '".$ids_mst_outlets[$i]."' ";
								$res2 =  mysqli_query($connNew, $sql2);
								$name = mysqli_fetch_row($res2);
								array_push($outlets,$name[0]);
							}
							$outlets_name = array();
							for($i=0 ; $i < count($outlets) ; $i++){
								$outlets_name = $outlets[$i][0];
							}
							$string = implode(',',$outlets);
							$row[14] = $string;
							//echo "<br/>"; exit;

						}

						if($tableName == 'mst_users'){
							$ids_mst_team = explode(',', $row[16]);
							$teams = array();
							for($i= 0 ; $i < count($ids_mst_team); $i++){
								$sql3 = "SELECT name from `mst_team` WHERE id = '".$ids_mst_team[$i]."' ";
								$res3 =  mysqli_query($connNew, $sql3);
								$name = mysqli_fetch_row($res3);
								array_push($teams,$name[0]);
							}
							$teams_name = array();
							for($i=0 ; $i < count($teams) ; $i++){
								$teams_name = $teams[$i][0];
							}
							$string = implode(',',$teams);
							$row[16] = $string;
							//echo "<br/>"; exit;

						}

						if($tableName == 'mst_hotels'){
							$ids_mst_general = explode(',', $row[35]);
							$general = array();
							for($i= 0 ; $i < count($ids_mst_general); $i++){
								$sql3 = "SELECT name from `mst_hotel_general_services` WHERE id = '".$ids_mst_general[$i]."' ";
								$res3 =  mysqli_query($connNew, $sql3);
								$name = mysqli_fetch_row($res3);
								array_push($general,$name[0]);
							}
							$general_name = array();
							for($i=0 ; $i < count($general) ; $i++){
								$general_name = $general[$i][0];
							}
							$string = implode(',',$general);
							$row[35] = $string;
							//echo "<br/>"; exit;

						}

						if($tableName == 'mst_hotels'){
							$ids_mst_outdoor = explode(',', $row[36]);
							$outdoor = array();
							for($i= 0 ; $i < count($ids_mst_outdoor); $i++){
								$sql3 = "SELECT name from `mst_hotel_outdoor_services` WHERE id = '".$ids_mst_outdoor[$i]."' ";
								$res3 =  mysqli_query($connNew, $sql3);
								$name = mysqli_fetch_row($res3);
								array_push($outdoor,$name[0]);
							}
							$outdoor_name = array();
							for($i=0 ; $i < count($outdoor) ; $i++){
								$outdoor_name = $general[$i][0];
							}
							$string = implode(',',$outdoor);
							$row[36] = $string;
							//echo "<br/>"; exit;

						}

						if($tableName == 'mst_hotels'){
							$ids_mst_dining = explode(',', $row[37]);
							$dining = array();
							for($i= 0 ; $i < count($ids_mst_dining); $i++){
								$sql3 = "SELECT name from `mst_hotel_dining_services` WHERE id = '".$ids_mst_dining[$i]."' ";
								$res3 =  mysqli_query($connNew, $sql3);
								$name = mysqli_fetch_row($res3);
								array_push($dining,$name[0]);
							}
							$dining_name = array();
							for($i=0 ; $i < count($dining) ; $i++){
								$dining_name = $dining[$i][0];
							}
							$string = implode(',',$dining);
							$row[37] = $string;
							//echo "<br/>"; exit;

						}

						if($tableName == 'mst_hotels'){
							$ids_mst_kids = explode(',', $row[38]);
							$kids = array();
							for($i= 0 ; $i < count($ids_mst_kids); $i++){
								$sql3 = "SELECT name from `mst_hotel_kids_related_services` WHERE id = '".$ids_mst_kids[$i]."' ";
								$res3 =  mysqli_query($connNew, $sql3);
								$name = mysqli_fetch_row($res3);
								array_push($kids,$name[0]);
							}
							$kids_name = array();
							for($i=0 ; $i < count($kids) ; $i++){
								$kids_name = $kids[$i][0];
							}
							$string = implode(',',$kids);
							$row[38] = $string;
							//echo "<br/>"; exit;

						}

						if($tableName == 'mst_hotels'){
							$ids_mst_conference = explode(',', $row[39]);
							$conference = array();
							for($i= 0 ; $i < count($ids_mst_conference); $i++){
								$sql3 = "SELECT name from `mst_hotel_conference_services` WHERE id = '".$ids_mst_conference[$i]."' ";
								$res3 =  mysqli_query($connNew, $sql3);
								$name = mysqli_fetch_row($res3);
								array_push($conference,$name[0]);
							}
							$conference_name = array();
							for($i=0 ; $i < count($conference) ; $i++){
								$conference_name = $conference[$i][0];
							}
							$string = implode(',',$conference);
							$row[39] = $string;
							//echo "<br/>"; exit;

						}





						$counterCol = 0;
						foreach($row as $cell){
							$endOfLine = ($counterCol == ($fields_num-1))? true:false;
							$dataWrite .= csvFieldFormating($cell,$endOfLine,'');
							$counterCol++;	
						}
					}
					
				}
				//
				$file = @fopen($filename,"w");
				fwrite($file,$dataWrite);
				fclose($file);
				$savedFileName = $tableName."_".date("Y-m-d_h-i-s_".rand(11111,99999));
				//header($contentType);
				header("Content-type: application/octet-stream"); 
				header("Content-Disposition: attachment;Filename=".$savedFileName.".".$fileEnding."");
				// send file to browser
				header("Pragma: no-cache");
				header("Expires: 0");
				readfile($filename);
				unlink($filename);
			}else{
				$err++;
				$message = "Invalid file type.";
			}
		}else{
			$err++;
			$message = "Invalid table name.";
		}
	}else{
		$err++;
		$message = "Invalid database name.";
	}
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$db = new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
adminLoginCheck();
checkUserLevelPermission($_SESSION['userLevel'],$_REQUEST['tableName'],'export');
if($_REQUEST['fileType'] && $_REQUEST[tableName]){
	exportTable($DB_NAME, $_REQUEST['tableName'], $_REQUEST['fileType']);
}else{
	echo "Invalid input.";
}?>