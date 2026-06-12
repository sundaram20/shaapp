<?php


///////////////// Frontoffice Tabels ///////////////////////
define('FO_PREFIX','fo_');
define('FO_RESERVATIONS',FO_PREFIX.'reservations');
define('FO_RESERVATIONS_DETAILS',FO_PREFIX.'reservations_details');
define('FO_INVENTORY',FO_PREFIX.'inventory');
define('TBL_BEST_AVAILABLE_RATE',FO_PREFIX.'best_available_rate');
define('TBL_RATE_PLAN',FO_PREFIX.'rate_plan');
define('TBL_ROOM_PLAN_LINKS',FO_PREFIX.'room_plan_links');
define('FO_HOUSE_KEEPING',FO_PREFIX.'house_keeping');
define('FO_RESERVATION_PAYMENT_DETAILS',FO_PREFIX.'reservations_payment_details');
define('FO_RESERVATION_ADDONS_DETAILS',FO_PREFIX.'reservations_addons_details');
define('FO_BILL',FO_PREFIX.'bill');
 
define('FO_RECEIPT',FO_PREFIX.'receipt');
define('FO_PAIDOUT',FO_PREFIX.'paidout');

///////////////// App Tabels ///////////////////////
define('APP_PREFIX','app_');
define('APP_SHOP',APP_PREFIX.'shops');
define('APP_MODULE',APP_PREFIX.'modules');
define('APP_MENU',APP_PREFIX.'menus');
define('APP_SUB_MENU',APP_PREFIX.'sub_menus');
define('APP_USERS',APP_PREFIX.'users');
define('APP_MIGRATIONS',APP_PREFIX.'migrations');
define('APP_DOCTYPE',APP_PREFIX.'doc_type');
define('APP_COLOR_CONFIG',APP_PREFIX.'color_config');

////////////////// Master Tables/////////////////////
define('POS_PREFIX','pos_');
define('TBL_PURCH',POS_PREFIX.'purch');
define('TBL_PURCH_DETAILS',POS_PREFIX.'purch_details');
define('TBL_PURCH_PAY',POS_PREFIX.'purch_pay');
define('TBL_POS_GUEST',POS_PREFIX.'guest');


////////////////// Master Tables/////////////////////
define('MST_PREFIX','mst_');
define('MST_COMPANY',MST_PREFIX.'company');
define('TBL_MODULES',MST_PREFIX.'modules');
define('TBL_MENU_ACCESS',MST_PREFIX.'menu_access');
define('TBL_SEASONS',MST_PREFIX.'seasons');
define('TBL_TAX_DATE_RULE',MST_PREFIX.'tax_date_rule');
define('TBL_TAX_RULE',MST_PREFIX.'tax_rule');
define('TBL_TAX_CONFIGURATION_TWO',MST_PREFIX.'tax_configuration_two');
define('TBL_RATE_SEASON',MST_PREFIX.'rate_season');
define('TBL_COMPANY_CONTACTS',MST_PREFIX.'company_contacts');
define('TBL_GUEST',MST_PREFIX.'guest');
define('TBL_CHANNEL_MANAGER','fs_channel_manager');
define('TBL_HOTEL_MAPPING','fs_hotel_mapping');
define('TBL_COMPANY_MAPPING','fs_company_mapping');

///////////////////////////////user master//////////////////////////////
define('TBL_USERS',MST_PREFIX.'users');
define('TBL_USER_LEVELS',MST_PREFIX.'user_levels');
define('TBL_USER_PERMISSIONS',MST_PREFIX.'user_permissions');
define('TBL_USER_ACTIONS',MST_PREFIX.'user_actions');
define('TBL_SHOP',MST_PREFIX.'shops');
define('TBL_TEAM',MST_PREFIX.'team');

/////// COMPANY MASTER //////////////////////////
define('TBL_COMPANY',MST_PREFIX.'company');
define('TBL_PORTFOLIO_ACCOUNT',MST_PREFIX.'portfolio_account');
define('TBL_COMPANY_AREA',MST_PREFIX.'company_area');
define('TBL_GROUP',MST_PREFIX.'company_group');

///////////////////////////////hotel master//////////////////////////////
define('TBL_ROOM_ALLOCATION',MST_PREFIX.'room_allocation');
define('TBL_HOTEL_ROOM_BLOCK',MST_PREFIX.'hotel_room_block');
define('TBL_ROOM_TYPE',MST_PREFIX.'room_types');
define('TBL_ROOMNO',MST_PREFIX.'room_no_allocation');
define('TBL_HOTELS',MST_PREFIX.'hotels');
define('TBL_HOTEL_CATEGORY',MST_PREFIX.'hotel_category');
define('TBL_ASSIGN_HOTEL_ROOM',MST_PREFIX.'assign_hotel_rooms');
define('TBL_HTL_BOOKING_STATUS',MST_PREFIX.'htl_booking_status');
define('TBL_RATE_MAPPING','fs_rate_mapping');

define('TBL_UNIQUE_EXPERIENCES',MST_PREFIX.'hotel_unique_experiences');

define('TBL_GENERAL_SERVICES',MST_PREFIX.'hotel_general_services');
define('TBL_OUTDOOR_ACTIVITIES',MST_PREFIX.'hotel_outdoor_services');
define('TBL_DINING_SERVICES',MST_PREFIX.'hotel_dining_services');
define('TBL_KIDS_SERVICES',MST_PREFIX.'hotel_kids_related_services');
define('TBL_CONFERENCE_SERVICES',MST_PREFIX.'hotel_conference_services');
define('TBL_ROOM_AMENITIES',MST_PREFIX.'room_amenities');
define('TBL_HOTEL_GALLERY',MST_PREFIX.'hotel_galleries');
define('TBL_ROOM_GALLERY',MST_PREFIX.'room_galleries');
define('TBL_VIDEO_GALLERY',MST_PREFIX.'video_galleries');

////////////////rate taable define/////////////////////////////////////////
define('TBL_RATE',MST_PREFIX.'rate');
define('TBL_RATE_DETAILS',MST_PREFIX.'rate_details');
define('TBL_RATE_ASSIGN_DETAILS',MST_PREFIX.'rate_assign_details');
define('TBL_RATE_INCLUSION',MST_PREFIX.'rate_inclusion');
define('TBL_RATE_LEVEL',MST_PREFIX.'rate_level');
define('TBL_RATE_SEASON',MST_PREFIX.'rate_season');
define('TBL_RATE_MARKET',MST_PREFIX.'rate_market');
define('TBL_RATE_POINTS',MST_PREFIX.'rate_points');


////// Location MAster///////////////////////
define('TBL_ZONAL',MST_PREFIX.'zonal');
define('TBL_COUNTRY_LANG',MST_PREFIX.'country_lang');
define('TBL_COUNTRY_DATA',MST_PREFIX.'country_data');
define('TBL_STATE',MST_PREFIX.'state');



///////////////// Website Master ////////////////////////
define('WEB_PREFIX','web_');
define('TBL_LANDING_PAGE',WEB_PREFIX.'landing_page_details');
define('TBL_BANNER_IMAGE',WEB_PREFIX.'banner_images');


///////////////// Booking Engine ///////////////////////
define('BE_PREFIX','be_');
define('TBL_BASE_RATE',BE_PREFIX.'base_rate_inventories');

define('TBL_PACKAGE_LINKING',BE_PREFIX.'room_plan_links');
define('TBL_ROOM_PLAN_LINKS',BE_PREFIX.'room_plan_links');// EX NAME IS TBL_PACKAGE_LINKING

define('TBL_OFFER',BE_PREFIX.'offers');
define('TBL_OFFER_MASTER',BE_PREFIX.'offer_master');
define('TBL_OFFER_DETAILS',BE_PREFIX.'offer_details');
define('TBL_BE_INVENTORY',FO_PREFIX.'inventory');
define('TBL_BE_RESERVATION_QUERY',BE_PREFIX.'reservations');



//////////////////////// Report Config ///////////////
define('REPORT_PREFIX','report_');

define('TBL_REPORT',REPORT_PREFIX.'config');


////////////database  main tables/////////////////////////////////


//////////////// Inventory Section Here //////////////////////////

/////////////// Master Unit Table ////////////////////////////////
define('TBL_UNIT',MST_PREFIX.'unit');
define('TBL_ITEM_GROUP',MST_PREFIX.'item_group');
define('TBL_CHARGES',MST_PREFIX.'charges');
define('TBL_ITEM_TYPE',MST_PREFIX.'item_type'); 
define('TBL_INV_ITEMS','inv_items');  	
define('TBL_INV_ITEMS_DETAILS','inv_items_details');
define('TBL_ATTRIBUTES',MST_PREFIX.'attributes'); 	 
define('TBL_PARTY',MST_PREFIX.'party'); 	 
define('TBL_CONTACTS',MST_PREFIX.'contacts'); 	 
define('TBL_DOC_TYPE_CONFIG',MST_PREFIX.'doc_type_configuration');   
define('TBL_DOC_TYPE_CONFIG_DETAIL',MST_PREFIX.'doc_type_configuration_detail'); 
define('TBL_INV_INDENT','inv_indent');	 	
define('TBL_INV_INDENT_DETAILS','inv_indent_details');	 	
//Purchase Order Tables
define('TBL_INV_PO','inv_po');	 
define('TBL_INV_PO_DETAILS','inv_po_details');	 	
define('TBL_INV_OTHERS_CHARGES','inv_others_charges');	 	
define('TBL_INV_TERMS_AND_CONDITIONS','inv_po_terms_and_conditions');
//Goods Receipt Note Tables	
define('TBL_INV_PURCH','inv_purch');	 
define('TBL_INV_PURCH_DETAILS','inv_purch_details');	 	
define('TBL_INV_OTHERS_CHARGES_PURCH','inv_others_charges_purch');

///// OULET MASTER////
define('TBL_OUTLETS',MST_PREFIX.'outlets'); 
define('TBL_BUDGET_YEAR',MST_PREFIX.'financial_year');

//PRICE MATRIX TABLES
define('TBL_PRICE_MATRIX','inv_price_matrix');	 	

define('TBL_PRICE_MATRIX_DETAILS','inv_price_matrix_details');	 	



/*
define('TBL_UPLOADED_FILES',PREFIX.'uploaded_files');


///////////////////////////////hotel manager//////////////////////////////
define('TBL_OTHERCHARGES_DETAIL',PREFIX.'othercharges_detail');
define('TBL_BUDGET_YEAR',PREFIX.'budget_year');
define('TBL_BUDGET_MASTER',PREFIX.'budget_master');

define('TBL_TAX_DATE_RULE',PREFIX.'tax_date_rule');
define('TBL_TAX_RULE',PREFIX.'tax_rule');

define('TBL_MEALS_MASTER',PREFIX.'meals_master');
define('TBL_PROMO_CODE',PREFIX.'promo_code');
define('TBL_PROMO_CODE_DETAILS',PREFIX.'promo_code_details');
define('TBL_AMENDMENT_COUNT',PREFIX.'amendment_count');

define('TBL_DAILYVISIT_FEEDBACK',PREFIX.'daily_visit_feedback');
define('TBL_DAILYVISIT_FOLLOWUP',PREFIX.'daily_visit_followup');
define('TBL_DAILYVISIT',PREFIX.'daily_visit');
define('TBL_ZONAL',PREFIX.'zonal');



define('TBL_PROMO_CODE',PREFIX.'promo_code');
define('TBL_PROMO_CODE_DETAILS',PREFIX.'promo_code_details');



///////////////////////////////attributes manager/////////////////////////
define('TBL_GENERAL_SERVICES',PREFIX.'general_services');
define('TBL_OUTDOOR_ACTIVITIES',PREFIX.'outdoor_activities');
define('TBL_DINING_SERVICES',PREFIX.'dining_services');
define('TBL_HOTEL_SERVICES',PREFIX.'hotel_services');

define('TBL_INVENTORY',PREFIX.'inventory');
define('TBL_HOTEL_MAPPING',PREFIX.'hotel_mapping');
define('TBL_ROOM_MAPPING',PREFIX.'room_mapping');
define('TBL_RATE_MAPPING',PREFIX.'rate_mapping');
define('TBL_COMPANY_MAPPING',PREFIX.'company_mapping');



////////////////order & booking status tables define//////////////////////
define('TBL_ORDER_STATE',PREFIX.'payment_status_master');
//define('TBL_ORDER_STATE',PREFIX.'order_state');
define('TBL_ORDERS',PREFIX.'orders');
define('TBL_ORDER_DETAIL',PREFIX.'order_detail');





////////////////customer taable define/////////////////////////////////////
define('TBL_CUSTOMER',PREFIX.'customer');

////////////////customer taable define/////////////////////////////////////
define('TBL_GENERAL_TERMS',PREFIX.'general_points');
define('TBL_CANCEL_MASTER',PREFIX.'cancellation_master');
define('TBL_AMENDMENT_REMARKS',PREFIX.'amendment_remarks');
define('TBL_TAX_CONFIGURATION_TWO',PREFIX.'tax_configuration_two');

////////////////rate taable define/////////////////////////////////////////
define('TBL_RATE',PREFIX.'rate');
define('TBL_RATE_DETAILS',PREFIX.'rate_details');
define('TBL_RATE_ASSIGN_DETAILS',PREFIX.'rate_assign_details');
define('TBL_RATE_PLAN',PREFIX.'rate_plan');
define('TBL_RATE_INCLUSION',PREFIX.'rate_inclusion');
define('TBL_RATE_LEVEL',PREFIX.'rate_level');
define('TBL_RATE_SEASON',PREFIX.'rate_season');
define('TBL_RATE_MARKET',PREFIX.'rate_market');
define('TBL_RATE_POINTS',PREFIX.'rate_points');

////////////////safari booking define//////////////////////////////////////
define('TBL_SAFARI_BOOKING',PREFIX.'safari_booking');
define('TBL_SAFARI_DETAILS',PREFIX.'safari_details');
////////////channel database tables////////////////////////////////////////////
define('TBL_CHANNEL_MANAGER',PREFIX.'channel_manager');


////////////////segment and series and operator table define//////////////////////////////////////
define('TBL_SEGMENT_MASTER',PREFIX.'segment_master');
define('TBL_SERIES_MASTER',PREFIX.'series_master');
define('TBL_OPERATOR_MASTER',PREFIX.'operator_master');
////////////end database tables////////////////////////////////////////////
*/
?>