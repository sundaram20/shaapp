<?php  
  include_once("../config/auto_loader.php"); 

  //Company Check Here


  function CheckTransactionsPOSGuest($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {    
       //$numrows =selectColumn( $table_name[$i],'id',"WHERE   `id` = '".$id."' ") ;
 		$numrows =selectColumn($table,'id',"   WHERE  `id` = '".$id."'  AND ids_pos_purch!='' ") ;
       //echo $numrows;         
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    }
    return $checking_data; 
}

 function CheckTransactionsCompany($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {    
       $numrows =selectColumn( $table_name[$i],'id_mst_company',"WHERE id_shop = '".$_SESSION['shop']."' and  `id_mst_company` = '".$id."' ") ;

       //echo $numrows;         
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    }
    return $checking_data; 
}

//Hotels Check Here

 function CheckTransactionsHotels($id, $table_name, $table_name1){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {    
       $numrows =selectColumn( $table_name[$i],'ids_mst_hotels',"WHERE id_shop = '".$_SESSION['shop']."' and  `ids_mst_hotels` = '".$id."' ") ;
       $numrows1 =selectColumn( $table_name1[$i],'id_mst_hotels',"WHERE   `id_mst_hotels` = '".$id."' ") ;

       //echo $numrows;         
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
	  if($numrows1 !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    }
    return $checking_data; 
}

//HotelTypes Check Here

 function CheckTransactionsHotelTypes($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {    
      $numrows =selectColumn( $table_name[$i],'id_mst_hotel_category','WHERE id_shop = "'.$_SESSION['shop'].'" and  `id_mst_hotel_category` = "'.$id.'" ') ;

       //echo $numrows;         
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    }
    return $checking_data; 
}

//MAster Items Check Here

 function CheckTransactionsItems($Id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {    
       $numrows =selectColumn( $table_name[$i],'id_mst_items','WHERE `id_mst_items` = "'.$Id.'" ') ;

      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    }
    return $checking_data; 
}

//RoomTypes Check Here

 function CheckTransactionsRoomTypes($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {    
       $numrows =selectColumn( $table_name[$i],'id_mst_room_types','WHERE `id_mst_room_types` = "'.$id.'" ') ;

       //echo $numrows;         
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    }
    return $checking_data; 
}

//GeneralServices Check Here

 function CheckTransactionsGeneralServices($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {    
       $numrows =selectColumn( $table_name[$i],'ids_mst_hotel_general_services','WHERE `ids_mst_hotel_general_services` = "'.$id.'" ') ;

       //echo $numrows;         
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    }
    return $checking_data; 
}

 function CheckTransactionsRoomBlock($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {    
       $numrows =selectColumn( $table_name[$i],'id_mst_hotel_room_block','WHERE `id_mst_hotel_room_block` = "'.$id.'" ') ;

       //echo $numrows;         
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    }
    return $checking_data; 
}

//DiningServices Check Here

 function CheckTransactionsDiningServices($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {    
       $numrows =selectColumn( $table_name[$i],'ids_mst_hotel_dining_services','WHERE `ids_mst_hotel_dining_services` = "'.$id.'" ') ;

       //echo $numrows;         
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    }
    return $checking_data; 
}

//DiningServices Check Here

 function CheckTransactionsOutdoorActivities($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {    
       $numrows =selectColumn( $table_name[$i],'ids_mst_hotel_outdoor_services','WHERE `ids_mst_hotel_outdoor_services` = "'.$id.'" ') ;

       //echo $numrows;         
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    }
    return $checking_data; 
}

//ConferenceServices Check Here

 function CheckTransactionsConferenceServices($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {    
       $numrows =selectColumn( $table_name[$i],'ids_mst_hotel_conference_services','WHERE `ids_mst_hotel_conference_services` = "'.$id.'" ') ;

       //echo $numrows;         
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    }
    return $checking_data; 
}

//Room Amenities Check Here

 function CheckTransactionsRoomAmenities($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {    
       $numrows =selectColumn( $table_name[$i],'ids_mst_room_amenities','WHERE `ids_mst_room_amenities` = "'.$id.'" ') ;

       //echo $numrows;         
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    }
    return $checking_data; 
}

//Country Check Here

 function CheckTransactionsCountry($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {    
       $numrows =selectColumn( $table_name[$i],'id_mst_country_lang','WHERE id_shop = "'.$_SESSION['shop'].'" and  `id_mst_country_lang` = "'.$id.'" ') ;

       //echo $numrows;         
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    }
    return $checking_data; 
}


//State Check Here

function CheckTransactionsState($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {         
       $numrows =selectColumn( $table_name[$i],'id_mst_state','WHERE id_shop = "'.$_SESSION['shop'].'" and  `id_mst_state` = "'.$id.'" ') ;          
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    }
    return $checking_data; 
}

//Zonal Check Here

function CheckTransactionsZonal($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {         
       $numrows =selectColumn( $table_name[$i],'id_mst_zonal','WHERE id_shop = "'.$_SESSION['shop'].'" and  `id_mst_zonal` = "'.$id.'" ') ;          
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    }
    return $checking_data; 
}

//Department Check Here
function CheckTransactionsDepartment($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {         
       $numrows =selectColumn( $table_name[$i],'id_mst_attributes_department','WHERE id_shop = "'.$_SESSION['shop'].'" and  `id_mst_attributes_department` = "'.$id.'" ') ;          
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    }
    return $checking_data; 
}

//outlet Check Here
function CheckTransactionsOutlets($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {         
       $numrows =selectColumn( $table_name[$i],'ids_mst_outlet','WHERE id_shop = "'.$_SESSION['shop'].'" and  `ids_mst_outlet` = "'.$id.'" ') ;          
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    }
    return $checking_data; 
}

//Company Group Check Here

function CheckTransactionsCompanyGroup($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {    
       $numrows =selectColumn( $table_name[$i],'id_mst_attributes_company_group','WHERE id_shop = "'.$_SESSION['shop'].'" and  `id_mst_attributes_company_group` = "'.$id.'" ') ;        
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    }
    return $checking_data; 
}

//Company Domain Check Here

function CheckTransactionsCompanyAreas($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {         
       $numrows =selectColumn( $table_name[$i],'deals_in','WHERE id_shop = "'.$_SESSION['shop'].'" and  `deals_in` = "'.$id.'" ') ;          
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    } 
    return $checking_data;
}

//Company Domain Check Here

function CheckTransactionsPortfolio($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {         
       $numrows =selectColumn( $table_name[$i],'id_mst_portfolio_account','WHERE id_shop = "'.$_SESSION['shop'].'" and  `id_mst_portfolio_account` = "'.$id.'" ') ;          
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    } 
    return $checking_data;
}

//UserLevel Check Here

function CheckTransactionsUserLevel($id, $table_name){   
    $checking_data='0';
    //print_r($table_name);
    $i = 0;
    foreach($table_name as $table) { 
      //echo $table_name[$i];

      //echo $table_name[$i].'id_mst_user_levels'.'WHERE id_shop = "'.$_SESSION['shop'].'" and  `id_mst_user_levels` = "'.$id.'" '."<br/>";
          
       $numrows =selectColumn( $table_name[$i],'id_mst_user_levels','WHERE id_shop = "'.$_SESSION['shop'].'" and  `id_mst_user_levels` = "'.$id.'" ') ;          
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++; 
    }
    return $checking_data; 
}

function CheckTransactionsTeam($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) { 
          
      $numrows =selectColumn( $table_name[$i],'id_mst_team',"WHERE id_shop = '".$_SESSION['shop']."' and  `id_mst_team` = '".$id."' or `ids_mst_team` = '".$id."' " ) ;          
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++; 
    }
    return $checking_data; 
}

//Party Category Check Here

function CheckTransactionsPartyCategoery($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {         
       $numrows =selectColumn( $table_name[$i],'id_mst_attributes_party_category','WHERE id_shop = "'.$_SESSION['shop'].'" and  `id_mst_attributes_party_category` = "'.$id.'" ') ;          
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    } 
    return $checking_data;
}

//Designations Check Here

function CheckTransactionsDesignations($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {         
       $numrows =selectColumn( $table_name[$i],'id_mst_attributes_designations','WHERE id_shop = "'.$_SESSION['shop'].'" and  `id_mst_attributes_designations` = "'.$id.'" ') ;          
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    } 
    return $checking_data;
}

//Grade Check Here

function CheckTransactionsGrade($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {         
       $numrows =selectColumn( $table_name[$i],'id_mst_attributes_grade','WHERE id_shop = "'.$_SESSION['shop'].'" and  `id_mst_attributes_grade` = "'.$id.'" ') ;          
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    } 
    return $checking_data;
}

//Unit Check Here

function CheckTransactionsUnit($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {         
       $numrows =selectColumn( $table_name[$i],'id_mst_attributes_unit_main','WHERE id_shop = "'.$_SESSION['shop'].'" and  `id_mst_attributes_unit_main` = "'.$id.'" ') ;          
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    } 
    return $checking_data;
}

//Main Group Check Here

function CheckTransactionsMainGroup($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {         
       $numrows =selectColumn( $table_name[$i],'id_mst_attributes_group_main','WHERE id_shop = "'.$_SESSION['shop'].'" and  `id_mst_attributes_group_main` = "'.$id.'" ') ;          
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    }
    return $checking_data; 
}


//Sub Group Check Here

function CheckTransactionsSubGroup($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {         
       $numrows =selectColumn( $table_name[$i],'id_mst_attributes_group_sub','WHERE id_shop = "'.$_SESSION['shop'].'" and  `id_mst_attributes_group_sub` = "'.$id.'" ') ;          
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    } 
    return $checking_data;
}

//Printer Check Here

function CheckTransactionsPrinter($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {         
       $numrows =selectColumn( $table_name[$i],'id_mst_attributes_printer','WHERE id_shop = "'.$_SESSION['shop'].'" and  `id_mst_attributes_printer` = "'.$id.'" ') ;          
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    }
    return $checking_data; 
}

//Store Check Here

function CheckTransactionsStore($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {         
       $numrows =selectColumn( $table_name[$i],'id_mst_attributes_store','WHERE id_shop = "'.$_SESSION['shop'].'" and  `id_mst_attributes_store` = "'.$id.'" ') ;          
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    } 
    return $checking_data;
}


//Charges Master Check Here

function CheckTransactionsChargesMaster($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) { 
 
      $numrows = selectColumn( $table_name[$i],'id_mst_charges_sales_local'," where id_shop='".$_SESSION['shop']."' and id_mst_charges_sales_local = '".$id."' or id_mst_charges_sales_interstate = '".$id."' or id_mst_charges_purchase_local = '".$id."' or id_mst_charges_purchase_interstate = '".$id."' "); 

      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    } 
    return $checking_data;
}

//ItemType Check Here

function CheckTransactionsItemType($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) { 
 
       $numrows =selectColumn( $table_name[$i],'id_mst_attributes_item_type','WHERE id_shop = "'.$_SESSION['shop'].'" and  `id_mst_attributes_item_type` = "'.$id.'" ') ; 
       
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    } 
    return $checking_data;
}

//Currency Check Here

function CheckTransactionsCurrency($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) { 
 
       $numrows =selectColumn( $table_name[$i],'transaction_currency_code','WHERE id_shop = "'.$_SESSION['shop'].'" and  `transaction_currency_code` = "'.$id.'" ') ; 
       
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    } 
    return $checking_data;
}

//Steward Check Here

function CheckTransactionsSteward($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) { 
 
       $numrows =selectColumn( $table_name[$i],'id_attribute_steward','WHERE id_shop = "'.$_SESSION['shop'].'" and  id_attribute_steward = "'.$id.'" ') ; 
       
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    } 
    return $checking_data;
}

//TableGroup Check Here

function CheckTransactionsTableGroup($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) { 
 
       $numrows =selectColumn( $table_name[$i],'id_attribute_table_group','WHERE id_shop = "'.$_SESSION['shop'].'" and  id_attribute_table_group = "'.$id.'" ') ; 
       
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    } 
    return $checking_data;
}

//Table Master Check Here

function CheckTransactionsTableMaster($id, $table_name){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) { 
 
       $numrows =selectColumn( $table_name[$i],'id_attribute_table','WHERE id_shop = "'.$_SESSION['shop'].'" and  id_attribute_table = "'.$id.'" ') ; 
       
      if($numrows !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    } 
    return $checking_data;
}



 function CheckTransactionsItemss($id, $table_name, $table_name3, $table_name4){   
    $checking_data='0';
    $i = 0;
    foreach($table_name as $table) {    
       $numrows1 =selectColumn( $table_name[$i],'id_inv_items',"WHERE `id_inv_items` = '".$id."' ") ;
       $numrows2 =selectColumn( $table_name3[$i],'id_mst_items',"WHERE `id_mst_items` = '".$id."' ") ;
       $numrows3 =selectColumn( $table_name4[$i],'id_inv_items',"WHERE `id_inv_items` = '".$id."' ") ;

       //echo $numrows;         
      if($numrows1 !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
	  if($numrows2 !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
	  if($numrows3 !=''){
        $checking_data='1';
        return $checking_data;
      }else{
        $checking_data='0';
      }
      $i++;
    }
    return $checking_data; 
}





?>