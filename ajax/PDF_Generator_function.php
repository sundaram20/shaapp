<?php include_once("../config/auto_loader.php");  

$clicked_id = $_POST["clicked_id"];
$party_val = $_POST["party_val"];
$doc_type = $_POST["doc_type"];
$multiple_item_val = $_POST["multiple_item_val"];
$main_group_val = $_POST["main_group_val"];
$sub_group_val = $_POST["sub_group_val"];
$id_mst_attributes_department = $_POST["id_mst_attributes_department"];
$pia = $_POST["pia"];
$check_value = $_POST["check_value"];
$datefilter = $_POST["datefilter"]; 
$phpfun = $_POST["phpfun"]; 

if($phpfun == 'Stock_balance_reports'){
 Stock_balance_reports($clicked_id,$doc_type,$multiple_item_val,$main_group_val,$id_mst_attributes_department,$pia,$check_value,$datefilter,$party_val,$db);
}elseif ($phpfun == 'Stock_Ledger_reports') {
   Stock_Ledger_reports($clicked_id,$doc_type,$multiple_item_val,$main_group_val,$id_mst_attributes_department,$pia,$check_value,$datefilter,$party_val,$db);
}elseif ($phpfun == 'Stock_Statement_reports') {
   Stock_Statement_reports($clicked_id,$doc_type,$multiple_item_val,$main_group_val,$id_mst_attributes_department,$pia,$check_value,$datefilter,$party_val,$db);
}elseif ($phpfun == 'Requisition_note_reports') {
   Requisition_note_reports($clicked_id,$doc_type,$multiple_item_val,$main_group_val,$id_mst_attributes_department,$pia,$check_value,$datefilter,$party_val,$db);
}elseif ($phpfun == 'Requisition_note_Party_item_reports') {
   Requisition_note_Party_item_reports($clicked_id,$doc_type,$multiple_item_val,$main_group_val,$id_mst_attributes_department,$pia,$check_value,$datefilter,$party_val,$db);
}elseif ($phpfun == 'Purchase_order_reports') {
   Purchase_order_reports($clicked_id,$doc_type,$multiple_item_val,$main_group_val,$id_mst_attributes_department,$pia,$check_value,$datefilter,$party_val,$db);
}elseif ($phpfun == 'Purchase_order_reports_Party_item_reports') {
   Purchase_order_reports_Party_item_reports($clicked_id,$doc_type,$multiple_item_val,$main_group_val,$id_mst_attributes_department,$pia,$check_value,$datefilter,$party_val,$db);
}elseif ($phpfun == 'Purchase_Register_reports') {
   Purchase_Register_reports($clicked_id,$doc_type,$multiple_item_val,$main_group_val,$id_mst_attributes_department,$pia,$check_value,$datefilter,$party_val,$db);
}elseif ($phpfun == 'Purchase_Register_reports_item_reports') {
   Purchase_Register_reports_item_reports($clicked_id,$doc_type,$multiple_item_val,$main_group_val,$id_mst_attributes_department,$pia,$check_value,$datefilter,$party_val,$db);
}elseif ($phpfun == 'Store_Receipt_reports') {
   Store_Receipt_reports($clicked_id,$doc_type,$multiple_item_val,$main_group_val,$id_mst_attributes_department,$pia,$check_value,$datefilter,$party_val,$db);
}elseif ($phpfun == 'Store_Receipt_reports_Party_item_reports') {
   Store_Receipt_reports_Party_item_reports($clicked_id,$doc_type,$multiple_item_val,$main_group_val,$id_mst_attributes_department,$pia,$check_value,$datefilter,$party_val,$db);
}elseif ($phpfun == 'Store_Issue_note_reports') {
   Store_Issue_note_reports($clicked_id,$doc_type,$multiple_item_val,$main_group_val,$id_mst_attributes_department,$pia,$check_value,$datefilter,$party_val,$db);
}elseif ($phpfun == 'Store_Issue_note_Party_item_reports') {
   Store_Issue_note_Party_item_reports($clicked_id,$doc_type,$multiple_item_val,$main_group_val,$id_mst_attributes_department,$pia,$check_value,$datefilter,$party_val,$db);
}



//#################################################################
//Stock Balance Reports
//#################################################################


//Function Calling Section
function Stock_balance_reports($clicked_id,$doc_type,$multiple_item_val,$main_group_val,$id_mst_attributes_department,$pia,$check_value,$datefilter,$party_val,$db){  
$mdoc_no = '';  
 
$sql12 = " SELECT * FROM `".TBL_SHOP."` WHERE `id`='".addslashes($_SESSION['shop'])."' ";
$db->query($sql12);   
  while($row12 = $db->fetch_object()){ 
    $companyname= $row12->name;  
  } 

// Allocation Set Here
  if($minimum_date != '' && $maximum_date != ''){
      $datefilter =  "and inv_items.indent_date >='".$minimum_date."' and inv_items.indent_date <='".$maximum_date."' ";
  }else{
    $minimum_date = '2010-01-01';
    $maximum_date = date('Y-m-d');
    $datefilter ='';
  }
  //Department Based Search
  if($id_mst_attributes_department != '' ){
      $department =  "and inv_items.id_mst_attributes_department ='".$id_mst_attributes_department."'  ";
  }else{ 
    $department =  '';
  }
  //Multiple Items
  $multiple_item_array = explode(',', $multiple_item_val);

  if($multiple_item_val != '' ){
      for($i=0;$i<count($multiple_item_array);$i++){
        $val=$multiple_item_array[$i];
        $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and name = '".$val."' ");   
          while($row = $db->fetch_object2($resCat)){ 
            $array[$i] = $row->id;
          }
      }  
      $array = implode("','",$array); 
      $multiple =  "and inv_items.id IN ('".$array."') ";
  }else{ 
      $multiple =  '';
  } 
  //Main Group
  $main_group_array = explode(',', $main_group_val);
  if($main_group_val != '' ){
        //Attributes Table 
      for($i=0;$i<count($main_group_array);$i++){
        $val=$main_group_array[$i];
        $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_main' AND field_value = '".$val."' ");   
          while($row = $db->fetch_object2($resCat)){ 
            $array[$i] = $row->id;
          }
      } 
        //Items Table
      $k=0;   
      for($i=0;$i<count($array);$i++){
          $val=$array[$i];
          $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_main = '".$val."' ");
          while($row = $db->fetch_object2($resCat)){ 
            $array_s[$k] = $row->id;
            $k++;
          } 
        }
        var_dump($array_s);
        $array = implode("','",$array_s);   
        $main_group = "and inv_items.id IN ('".$array."') ";
  }else{ 
      $main_group =  '';
  } 

  //Sub Group
  $sub_group_array = explode(',', $sub_group_val);
  if($sub_group_val != '' ){
        //Attributes Table 
      for($i=0;$i<count($sub_group_array);$i++){
        $val=$sub_group_array[$i];
          $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_sub' AND field_value = '".$val."' ");   
            while($row = $db->fetch_object2($resCat)){ 
              $array[$i] = $row->id;
            }
      }
        //Items Table
      $k=0;   
        for($i=0;$i<count($array);$i++){
          $val=$array[$i];
          $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_sub = '".$val."' ");           
          while($row = $db->fetch_object2($resCat)){ 
            $array_s[$k] = $row->id;
            $k++;
          } 
        }
        $array = implode("','",$array_s);   
        $sub_group = "and inv_items.id IN ('".$array."') ";
  }else{ 
      $sub_group =  '';
  } 

//Popup Table Show  
  $where = " id_shop = '".addslashes($_SESSION['shop'])."' $datefilter  $department  $multiple $main_group $sub_group " ;


 $sql = "SELECT * FROM inv_items WHERE $where"; 
                        $db->query($sql);
                        $numRows= $db->num_rows(); 
                        //Table Section
                        $table = '<br><h4 style="padding-left:38%;">'.$companyname.'</h4><p  style="padding-left:15%;"><b>Stock Balance</b> From '.date('d-m-Y' , strtotime(addslashes($minimum_date))). ' To '.date('d-m-Y' , strtotime(addslashes($maximum_date))).' Show Zero Stock, Value Required</p>';

                        $i=1; 
                        $table .= '<table style="margin-top:1.5%;width:100%;" border="1" cellspacing="1"  cellpadding="1" > 
                        <thead>                     
                          <tr style="text-align: center; font-size: 12px;"> 
                              <th class="headings" style="width: 10%;">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ITEM CODE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                              <th style="width: 15%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ITEM DESCRIPTION &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                              <th style="width: 10%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; UNIT &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                              <th style="width: 10%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; MIN &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                              <th style="width: 10%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; STOCK &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>   
                              <th style="width: 10%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; VALUE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                          </tr>
                      </thead>'; 


                        $mylist = array();
                        while($row22 = $db->fetch_object()){  
                          //Unit Get Here
                          $unit_main  =  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row22->id_mst_attributes_unit_main)."' AND table_name='unit'");

                          //GRN DETAILS 
                          $grn_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='1' AND id_inv_items ='".$row22->id."'");
                          //Opening Balance
                          $openbal_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='100' AND id_inv_items ='".$row22->id."'");
                          //Physical Stock
                          $physicalstock_qty = selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='4' AND id_inv_items ='".$row22->id."'");
                          //Store Issue Note
                          $sin_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='3' AND id_inv_items ='".$row22->id."'");

                          $stock_in_hand = $grn_qty + $openbal_qty + $physicalstock_qty - $sin_qty;


                          $table .= '<tr style="text-align: center; font-size:14px;"><td>'; 
                              $table .= $row22->item_code; 
                              $table .= '</td><td>'; 
                              $table .= $row22->name; 
                              $table .= '</td><td>'; 
                              $table .= $unit_main;  
                              $table .= '</td><td>'; 
                              $table .= $row22->min_qty;
                              $table .= '</td><td>';
                              $table .=  $stock_in_hand;
                              $table .= '</td><td>';
                              $table .=  0;
                              $table .= '</td></tr>  '; 
                              
                              $mylist[$i] =$row22->item_code.','.$row22->name.','.$unit_main.','.$stock_in_hand;    
                              $i=$i+1; 
                        } 
                        $table .= '</table>';   
                        //Pdf Work Flow 
                        $dompdf = new DOMPDF(); 
                        $dompdf->set_paper('A4', 'A4');
                        $dompdf->load_html($table);
                        $dompdf->render();
                        $font = Font_Metrics::get_font("helvetica", "bold");
                        $dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0)); 
                        $gen = $dompdf->output(); 
                        file_put_contents('../pdf/Stock_Balance.pdf', $gen);
                        //Mail Attach
                        $Filename = '../pdf/Stock_Balance.pdf';      
                        $gen = $dompdf->output(); 
                        file_put_contents('/home/admingcs/public_html/sales/adminpanel/mailattach/'.$Filename.'.pdf', $gen);
      

echo ($table);   
}

//#################################################################
//Stock Ledger Reports
//#################################################################

function Stock_Ledger_reports($clicked_id,$doc_type,$multiple_item_val,$main_group_val,$id_mst_attributes_department,$pia,$check_value,$datefilter,$party_val,$db){ 

$mdoc_no = '';
$date = explode(' to ', $datefilter); 
$minimum_date = $date[0];
$maximum_date = $date[1]; 
 
$sql12 = " SELECT * FROM `".TBL_SHOP."` WHERE `id`='".addslashes($_SESSION['shop'])."' ";
$db->query($sql12);   
  while($row12 = $db->fetch_object()){ 
    $companyname= $row12->name;  
  } 
  $datefilter ='';
  $minimum_date = '2010-01-01';
  $maximum_date = date('Y-m-d'); 
  //Department Based Search
  if($id_mst_attributes_department != '' ){
      $department =  "and inv_items.id_mst_attributes_department ='".$id_mst_attributes_department."'  ";
  }else{ 
    $department =  '';
  }
  //Multiple Items
  $multiple_item_array = explode(',', $multiple_item_val);

  if($multiple_item_val != '' ){
      for($i=0;$i<count($multiple_item_array);$i++){
        $val=$multiple_item_array[$i];
        $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and name = '".$val."' ");   
          while($row = $db->fetch_object2($resCat)){ 
            $array[$i] = $row->id;
          }
      }  
      $array = implode("','",$array); 
      $multiple =  "and inv_items.id IN ('".$array."') ";
  }else{ 
      $multiple =  '';
  } 
  //Main Group
  $main_group_array = explode(',', $main_group_val);
  if($main_group_val != '' ){
        //Attributes Table 
      for($i=0;$i<count($main_group_array);$i++){
        $val=$main_group_array[$i];
        $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_main' AND field_value = '".$val."' ");   
          while($row = $db->fetch_object2($resCat)){ 
            $array[$i] = $row->id;
          }
      } 
        //Items Table
      $k=0;   
      for($i=0;$i<count($array);$i++){
          $val=$array[$i];
          $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_main = '".$val."' ");
          while($row = $db->fetch_object2($resCat)){ 
            $array_s[$k] = $row->id;
            $k++;
          } 
        }
        var_dump($array_s);
        $array = implode("','",$array_s);   
        $main_group = "and inv_items.id IN ('".$array."') ";
  }else{ 
      $main_group =  '';
  } 

  //Sub Group
  $sub_group_array = explode(',', $sub_group_val);
  if($sub_group_val != '' ){
        //Attributes Table 
      for($i=0;$i<count($sub_group_array);$i++){
        $val=$sub_group_array[$i];
          $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_sub' AND field_value = '".$val."' ");   
            while($row = $db->fetch_object2($resCat)){ 
              $array[$i] = $row->id;
            }
      }
        //Items Table
      $k=0;   
        for($i=0;$i<count($array);$i++){
          $val=$array[$i];
          $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_sub = '".$val."' ");           
          while($row = $db->fetch_object2($resCat)){ 
            $array_s[$k] = $row->id;
            $k++;
          } 
        }
        $array = implode("','",$array_s);   
        $sub_group = "and inv_items.id IN ('".$array."') ";
  }else{ 
      $sub_group =  '';
  } 

//Popup Table Show  
  $where = " id_shop = '".addslashes($_SESSION['shop'])."' $datefilter  $department  $multiple $main_group $sub_group " ;
 

 $sql = "SELECT * FROM inv_items WHERE $where"; 
                        $db->query($sql);
                        $numRows= $db->num_rows(); 
                        //Table Section
                        $table = '<br><h4 style="padding-left:38%;">'.$companyname.'</h4><p  style="padding-left:29%;"><b>Stock Ledger</b> From '.date('d-m-Y' , strtotime(addslashes($minimum_date))). ' To '.date('d-m-Y' , strtotime(addslashes($maximum_date))).'</p>';
                        $i=1;                                           

                        $mylist = array();
                        while($row22 = $db->fetch_object()){ 
                          $table .= '
                          <p style="margin-left:1.5%;" ><b>Item Code:</b> '.$row22->item_code.', <b>Item Descriptions: </b>'.$row22->name.'</p> 
                          <table style="margin-top:1.5%;width:100%;" border="1" cellspacing="1"  cellpadding="1" >';
                          
                          $table .= '
                          <thead>                     
                            <tr style="text-align: center; font-size: 14px; padding-top:20px;">
                                <th style="width: 10%;">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Date &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                                <th style="width: 10%;">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Particulars &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                                <th style="width: 10%;"  colspan="2">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Receipt &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                                <th style="width: 10%;"  colspan="2">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Issue &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                                <th style="width: 10%;"  colspan="2">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Balance &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                            </tr>
                            <tr>
                              <th></th> 
                              <th></th> 
                              <th>Qty</th>
                              <th>Amount</th>
                              <th>Qty</th>
                              <th>Amount</th>
                              <th>Qty</th>
                              <th>Amount</th>
                             </tr>
                        </thead>';
                          //Unit Get Here
                          $unit_main  =  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row22->id_mst_attributes_unit_main)."' AND table_name='unit'");

                          //GRN DETAILS 
                          $grn_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='1' AND id_inv_items ='".$row22->id."'");
                          //Opening Balance
                          $openbal_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='100' AND id_inv_items ='".$row22->id."'");
                          //Physical Stock
                          $physicalstock_qty = selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='4' AND id_inv_items ='".$row22->id."'");
                          //Store Issue Note
                          $sin_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='3' AND id_inv_items ='".$row22->id."'");

                          $stock_in_hand = $grn_qty + $openbal_qty + $physicalstock_qty - $sin_qty;


                          $table .= '<tr style="text-align: center; font-size:14px;"><td>'; 
                              $table .= $row22->item_code; 
                              $table .= '</td><td>'; 
                              $table .= '</td><td>'; 
                              $table .= $row22->name; 
                              $table .= '</td><td>'; 
                              $table .= $unit_main;  
                              $table .= '</td><td>'; 
                              $table .= $row22->min_qty;
                              $table .= '</td><td>';
                              $table .= ''; 
                              $table .= '</td><td>';
                              $table .=  $stock_in_hand;
                              $table .= '</td><td>';
                              $table .=  0;
                              $table .= '</td></tr>  '; 
                              
                              $mylist[$i] =$row22->item_code.','.$row22->name.','.$unit_main.','.$stock_in_hand;    
                              $i=$i+1; 
                        $table .= '</table>';
                        } 

                        //Pdf Work Flow                      
                        $dompdf = new DOMPDF(); 
                        $dompdf->set_paper('A4', 'A4');
                        $dompdf->load_html($table);
                        $dompdf->render();
                        $font = Font_Metrics::get_font("helvetica", "bold");
                        $dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0)); 
                        $gen = $dompdf->output(); 
                        file_put_contents('../pdf/Stock_Ledger.pdf', $gen);
                        //Mail Attach
                        $Filename = '../pdf/Stock_Ledger.pdf';      
                        $gen = $dompdf->output(); 
                        file_put_contents('/home/admingcs/public_html/sales/adminpanel/mailattach/'.$Filename.'.pdf', $gen); 
echo ($table);  

}


//#################################################################
//Stock_Statement_reports
//#################################################################
  function Stock_Statement_reports($clicked_id,$doc_type,$multiple_item_val,$main_group_val,$id_mst_attributes_department,$pia,$check_value,$datefilter,$party_val,$db){ 
      $mdoc_no = '';
      $date = explode(' to ', $datefilter); 
      $minimum_date = $date[0];
      $maximum_date = $date[1]; 
       
      $sql12 = " SELECT * FROM `".TBL_SHOP."` WHERE `id`='".addslashes($_SESSION['shop'])."' ";
      $db->query($sql12);   
        while($row12 = $db->fetch_object()){ 
          $companyname= $row12->name;  
        } 
        $datefilter ='';
        $minimum_date = '2010-01-01';
        $maximum_date = date('Y-m-d'); 
        //Department Based Search
        if($id_mst_attributes_department != '' ){
            $department =  "and inv_items.id_mst_attributes_department ='".$id_mst_attributes_department."'  ";
        }else{ 
          $department =  '';
        }
        //Multiple Items
        $multiple_item_array = explode(',', $multiple_item_val);

        if($multiple_item_val != '' ){
            for($i=0;$i<count($multiple_item_array);$i++){
              $val=$multiple_item_array[$i];
              $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and name = '".$val."' ");   
                while($row = $db->fetch_object2($resCat)){ 
                  $array[$i] = $row->id;
                }
            }  
            $array = implode("','",$array); 
            $multiple =  "and inv_items.id IN ('".$array."') ";
        }else{ 
            $multiple =  '';
        } 
        //Main Group
        $main_group_array = explode(',', $main_group_val);
        if($main_group_val != '' ){
              //Attributes Table 
            for($i=0;$i<count($main_group_array);$i++){
              $val=$main_group_array[$i];
              $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_main' AND field_value = '".$val."' ");   
                while($row = $db->fetch_object2($resCat)){ 
                  $array[$i] = $row->id;
                }
            } 
              //Items Table
            $k=0;   
            for($i=0;$i<count($array);$i++){
                $val=$array[$i];
                $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_main = '".$val."' ");
                while($row = $db->fetch_object2($resCat)){ 
                  $array_s[$k] = $row->id;
                  $k++;
                } 
              } 
              $array = implode("','",$array_s);   
              $main_group = "and inv_items.id IN ('".$array."') ";
        }else{ 
            $main_group =  '';
        } 

        //Sub Group
        $sub_group_array = explode(',', $sub_group_val);
        if($sub_group_val != '' ){
              //Attributes Table 
            for($i=0;$i<count($sub_group_array);$i++){
              $val=$sub_group_array[$i];
                $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_sub' AND field_value = '".$val."' ");   
                  while($row = $db->fetch_object2($resCat)){ 
                    $array[$i] = $row->id;
                  }
            }
              //Items Table
            $k=0;   
              for($i=0;$i<count($array);$i++){
                $val=$array[$i];
                $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_sub = '".$val."' ");           
                while($row = $db->fetch_object2($resCat)){ 
                  $array_s[$k] = $row->id;
                  $k++;
                } 
              }
              $array = implode("','",$array_s);   
              $sub_group = "and inv_items.id IN ('".$array."') ";
        }else{ 
            $sub_group =  '';
        } 

        //Popup Table Show  
          $where = " id_shop = '".addslashes($_SESSION['shop'])."' $datefilter  $department  $multiple $main_group $sub_group " ;
         

         $sql = "SELECT * FROM inv_items WHERE $where"; 
                        $db->query($sql);
                        $numRows= $db->num_rows(); 
                        //Table Section
                        $table = '<br><h4 style="padding-left:38%;">'.$companyname.'</h4><p  style="padding-left:28%;"><b>Stock Statement</b> From '.date('d-m-Y' , strtotime(addslashes($minimum_date))). ' To '.date('d-m-Y' , strtotime(addslashes($maximum_date))).'</p>';
                         
                         $table .= ' 
                          <table style="margin-top:1.5%;width:100%;" border="1" cellspacing="1"  cellpadding="1" >';
                          
                          $table .= '
                          <thead>                     
                            <tr style="text-align: center; font-size: 10px; padding-top:20px;">
                                <th style="width: 10%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Item Code &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </th> 
                                <th style="width: 10%;">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Item Description &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </th> 
                                <th style="width: 10%;">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Rate &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </th> 
                                <th style="width: 10%;"  colspan="2">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Receipt &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </th> 
                                <th style="width: 10%;"  colspan="2">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Issue &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </th> 
                                <th style="width: 10%;"  colspan="2">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Balance &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </th> 
                            </tr>
                            <tr style="text-align: center; font-size:10px;">
                              <th></th> 
                              <th></th> 
                              <th></th> 
                              <th>Qty</th>
                              <th>Amount</th>
                              <th>Qty</th>
                              <th>Amount</th>
                              <th>Qty</th>
                              <th>Amount</th>
                             </tr>
                        </thead>';                                         
                        $i=1; 
                        $mylist = array();
                        while($row22 = $db->fetch_object()){ 
                         
                          //Unit Get Here
                          $unit_main  =  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row22->id_mst_attributes_unit_main)."' AND table_name='unit'");

                          //GRN DETAILS 
                          $grn_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='1' AND id_inv_items ='".$row22->id."'");
                          //Opening Balance
                          $openbal_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='100' AND id_inv_items ='".$row22->id."'");
                          //Physical Stock
                          $physicalstock_qty = selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='4' AND id_inv_items ='".$row22->id."'");
                          //Store Issue Note
                          $sin_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='3' AND id_inv_items ='".$row22->id."'");

                          $stock_in_hand = $grn_qty + $openbal_qty + $physicalstock_qty - $sin_qty;


                          $table .= '<tr style="text-align: center; font-size:12px;"><td>'; 
                              $table .= $row22->item_code; 
                              $table .= '</td><td>'; 
                              $table .= $row22->name; 
                              $table .= '</td><td>'; 
                              $table .= '</td><td>'; 
                              $table .= '</td><td>'; 
                              $table .= $unit_main;  
                              $table .= '</td><td>'; 
                              $table .= $row22->min_qty;
                              $table .= '</td><td>';
                              $table .= ''; 
                              $table .= '</td><td>';
                              $table .=  $stock_in_hand;
                              $table .= '</td><td>';
                              $table .=  0;
                              $table .= '</td></tr>'; 
                              
                              $mylist[$i] =$row22->item_code.','.$row22->name.','.$unit_main.','.$stock_in_hand;    
                              $i=$i+1; 
                        } 
                        $table .= '</table>';

                        //Pdf Work Flow                       
                        $dompdf = new DOMPDF(); 
                        $dompdf->set_paper('A4', 'A4');
                        $dompdf->load_html($table);
                        $dompdf->render();
                        $font = Font_Metrics::get_font("helvetica", "bold");
                        $dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0)); 
                        $gen = $dompdf->output(); 
                        file_put_contents('../pdf/Stock_Statement.pdf', $gen);
                        //Mail Attach
                        $Filename = '../pdf/Stock_Statement.pdf';      
                        $gen = $dompdf->output(); 
                        file_put_contents('/home/admingcs/public_html/sales/adminpanel/mailattach/'.$Filename.'.pdf', $gen); 
echo ($table);
  }


//#################################################################
  //Requisition_note_reports
//#################################################################

    function Requisition_note_reports($clicked_id,$doc_type,$multiple_item_val,$main_group_val,$id_mst_attributes_department,$pia,$check_value,$datefilter,$party_val,$db){

        $mdoc_no = '';
          $date = explode(' to ', $datefilter); 
          $minimum_date = date('Y-m-d' , strtotime(addslashes($date[0])));
          $maximum_date = date('Y-m-d' , strtotime(addslashes($date[1]))); 
           
          $sql12 = " SELECT * FROM `".TBL_SHOP."` WHERE `id`='".addslashes($_SESSION['shop'])."' ";
          $db->query($sql12);   
            while($row12 = $db->fetch_object()){ 
              $companyname= $row12->name;  
            } 

          // Allocation Set Here
            if($minimum_date != '' && $maximum_date != ''){
                $datefilter =  "and inv_indent.indent_date >='".$minimum_date."' and inv_indent.indent_date <='".$maximum_date."' ";
            }else{
              $minimum_date = '2010-01-01';
              $maximum_date = date('Y-m-d');
              $datefilter ='';
            }
            //Department Based Search
            if($id_mst_attributes_department != '' ){
                $department =  "and inv_indent.id_mst_attributes_department ='".$id_mst_attributes_department."'  ";
            }else{ 
              $department =  '';
            }
            //Multiple Items
            $multiple_item_array = explode(',', $multiple_item_val);

            if($multiple_item_val != '' ){
                for($i=0;$i<count($multiple_item_array);$i++){
                  $val=$multiple_item_array[$i];
                  $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and name = '".$val."' ");   
                    while($row = $db->fetch_object2($resCat)){ 
                      $array[$i] = $row->id;
                    }
                }  
                $array = implode("','",$array); 
                $multiple =  "and inv_indent_details.id_inv_items IN ('".$array."') ";
            }else{ 
                $multiple =  '';
            } 
            //Main Group
            $main_group_array = explode(',', $main_group_val);
            if($main_group_val != '' ){
                  //Attributes Table 
                for($i=0;$i<count($main_group_array);$i++){
                  $val=$main_group_array[$i];
                  $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_main' AND field_value = '".$val."' ");   
                    while($row = $db->fetch_object2($resCat)){ 
                      $array[$i] = $row->id;
                    }
                } 
                  //Items Table
                $k=0;   
                for($i=0;$i<count($array);$i++){
                    $val=$array[$i];
                    $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_main = '".$val."' ");
                    while($row = $db->fetch_object2($resCat)){ 
                      $array_s[$k] = $row->id;
                      $k++;
                    } 
                  } 
                  $array = implode("','",$array_s);    
                  $main_group = "and inv_indent_details.id_inv_items IN ('".$array."') ";
            }else{ 
                $main_group =  '';
            } 

            //Sub Group
            $sub_group_array = explode(',', $sub_group_val);
            if($sub_group_val != '' ){
                  //Attributes Table 
                for($i=0;$i<count($sub_group_array);$i++){
                  $val=$sub_group_array[$i];
                    $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_sub' AND field_value = '".$val."' ");   
                      while($row = $db->fetch_object2($resCat)){ 
                        $array[$i] = $row->id;
                      }
                }
                  //Items Table
                $k=0;   
                  for($i=0;$i<count($array);$i++){
                    $val=$array[$i];
                    $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_sub = '".$val."' ");           
                    while($row = $db->fetch_object2($resCat)){ 
                      $array_s[$k] = $row->id;
                      $k++;
                    } 
                  }
                  $array = implode("','",$array_s);  
                  $sub_group = "and inv_indent_details.id_inv_items IN ('".$array."') ";
            }else{ 
                $sub_group =  '';
            } 
          $mylist = array();
          //Popup Table Show  
            $where = "mst_attributes.id=inv_indent.id_mst_attributes_department and inv_indent.id = inv_indent_details.id_inv_indent and inv_indent_details.id_inv_items = inv_items.id  and inv_indent.id_shop = '".addslashes($_SESSION['shop'])."' and inv_indent.doc_type = '".$doc_type."' $datefilter  $department  $multiple $main_group $sub_group" ;
           

           $sql = "SELECT inv_indent.indent_date, inv_indent.mdoc_no,  inv_indent.indent_no, 
                        inv_indent_details.qty,inv_indent_details.alt_qty, inv_indent_details.id,inv_indent_details.id_inv_indent, inv_indent_details.main_unit, inv_indent_details.alt_unit, inv_indent_details.bal_qty, inv_indent_details.ordered_qty, 
                        inv_items.item_code, inv_items.name, inv_items.conversion_qty, inv_items.id as item_id, 
                        mst_attributes.field_value 
                        FROM inv_items, mst_attributes, inv_indent_details, inv_indent WHERE $where";
                      
                        $db->query($sql);
                        $numRows= $db->num_rows(); 
                        //Table Section
                        $table = '<br><h4 style="padding-left:38%;">'.$companyname.'</h4><p  style="padding-left:20%;">Indent No Wise Requistion Note From '.date('d-m-Y' , strtotime(addslashes($minimum_date))). ' To '.date('d-m-Y' , strtotime(addslashes($maximum_date))).'</p>';
                        $i=1; 
                        $table .= '<table style="margin-top:1.5%;width:100%;" border="1" cellspacing="1"  cellpadding="1" > 
                        <thead>                     
                          <tr style="text-align: center; font-size: 11px;">
                              <th style="width: 10%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Indent No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                              <th style="width: 10%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                              <th style="width: 10%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Department &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                              <th  style="width: 10%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Item Code &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                              <th  style="width: 15%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Description &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                              <th  style="width: 10%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Qty &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                              <th style="width: 10%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Value &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                          </tr>
                      </thead>';
                        while($row22 = $db->fetch_object()){ 
                          
                          $table .= '<tr style="text-align: center; font-size:12px;"><td>';
                                if($mdoc_no != $row22->mdoc_no){
                                $table .= $row22->mdoc_no;
                              }
                              $table .= '</td><td>';
                              if($mdoc_no != $row22->mdoc_no){
                                $table .= date('d-m-Y' , strtotime(addslashes($row22->indent_date)));
                              }
                             $table .= '</td>
                              <td>';
                              if($mdoc_no != $row22->mdoc_no){
                                 
                             $table .= $row22->field_value;
                              }
                             $table .= '</td>
                              <td>';
                                $table .= $row22->item_code;
                               $table .= '</td>
                              <td>'; 
                                $table .=$row22->name;
                               $table .= '</td>
                              <td>';
                                 $table .=$row22->qty; 
                                $table .= '</td>
                              <td>';
                                $table .= 0;
                               $table .= '</td>
                               </tr>  '; 
                               $mylist[$i] =$row22->mdoc_no.','.date('d-m-Y' , strtotime(addslashes($row22->indent_date))).','.$row22->field_value.','. $row22->item_code.','.$row22->name.','.$row22->qty.','.'0'; 

                              $i++; $mdoc_no = $row22->mdoc_no;
                        } 
                        $table .= '</table>'; 
                        
                        //PDF Generate
                        $dompdf = new DOMPDF(); 
                        $dompdf->set_paper('A4', 'A4');
                        $dompdf->load_html($table);
                        $dompdf->render();
                        $font = Font_Metrics::get_font("helvetica", "bold");
                        $dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0)); 
                        $gen = $dompdf->output(); 
                        file_put_contents('../pdf/Requistion_Note.pdf', $gen);
                        //Mail Attach
                        $Filename = '../pdf/Requistion_Note.pdf';      
                        $gen = $dompdf->output(); 
                        file_put_contents('/home/admingcs/public_html/sales/adminpanel/mailattach/'.$Filename.'.pdf', $gen);
 
echo ($table); 
    }

//#################################################################
  //Requisition_note_Party_item_reports
//#################################################################

  function Requisition_note_Party_item_reports($clicked_id,$doc_type,$multiple_item_val,$main_group_val,$id_mst_attributes_department,$pia,$check_value,$datefilter,$party_val,$db){

      $mdoc_no = '';
      $date = explode(' to ', $datefilter); 
      $minimum_date = date('Y-m-d' , strtotime(addslashes($date[0])));
      $maximum_date = date('Y-m-d' , strtotime(addslashes($date[1]))); 
       
      $sql12 = " SELECT * FROM `".TBL_SHOP."` WHERE `id`='".addslashes($_SESSION['shop'])."' ";
      $db->query($sql12);   
        while($row12 = $db->fetch_object()){ 
          $companyname= $row12->name;  
        } 

      // Allocation Set Here
        if($minimum_date != '' && $maximum_date != ''){
            $datefilter =  "and inv_indent.indent_date >='".$minimum_date."' and inv_indent.indent_date <='".$maximum_date."' ";
        }else{
          $minimum_date = '2010-01-01';
          $maximum_date = date('Y-m-d');
          $datefilter ='';
        }
        //Department Based Search
        if($id_mst_attributes_department != '' ){
            $department =  "and inv_indent.id_mst_attributes_department ='".$id_mst_attributes_department."'  ";
        }else{ 
          $department =  '';
        }
        //Multiple Items
        $multiple_item_array = explode(',', $multiple_item_val);

        if($multiple_item_val != '' ){
            for($i=0;$i<count($multiple_item_array);$i++){
              $val=$multiple_item_array[$i];
              $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and name = '".$val."' ");   
                while($row = $db->fetch_object2($resCat)){ 
                  $array[$i] = $row->id;
                }
            }  
            $array = implode("','",$array); 
            $multiple =  "and inv_indent_details.id_inv_items IN ('".$array."') ";
        }else{ 
            $multiple =  '';
        } 
        //Main Group
        $main_group_array = explode(',', $main_group_val);
        if($main_group_val != '' ){
              //Attributes Table 
            for($i=0;$i<count($main_group_array);$i++){
              $val=$main_group_array[$i];
              $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_main' AND field_value = '".$val."' ");   
                while($row = $db->fetch_object2($resCat)){ 
                  $array[$i] = $row->id;
                }
            } 
              //Items Table
            $k=0;   
            for($i=0;$i<count($array);$i++){
                $val=$array[$i];
                $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_main = '".$val."' ");
                while($row = $db->fetch_object2($resCat)){ 
                  $array_s[$k] = $row->id;
                  $k++;
                } 
              } 
              $array = implode("','",$array_s);    
              $main_group = "and inv_indent_details.id_inv_items IN ('".$array."') ";
        }else{ 
            $main_group =  '';
        } 

        //Sub Group
        $sub_group_array = explode(',', $sub_group_val);
        if($sub_group_val != '' ){
              //Attributes Table 
            for($i=0;$i<count($sub_group_array);$i++){
              $val=$sub_group_array[$i];
                $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_sub' AND field_value = '".$val."' ");   
                  while($row = $db->fetch_object2($resCat)){ 
                    $array[$i] = $row->id;
                  }
            }
              //Items Table
            $k=0;   
              for($i=0;$i<count($array);$i++){
                $val=$array[$i];
                $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_sub = '".$val."' ");           
                while($row = $db->fetch_object2($resCat)){ 
                  $array_s[$k] = $row->id;
                  $k++;
                } 
              }
              $array = implode("','",$array_s);  
              $sub_group = "and inv_indent_details.id_inv_items IN ('".$array."') ";
        }else{ 
            $sub_group =  '';
        } 
      $mylist = array();
      //Popup Table Show  
        $where = "mst_attributes.id=inv_indent.id_mst_attributes_department and inv_indent.id = inv_indent_details.id_inv_indent and inv_indent_details.id_inv_items = inv_items.id  and inv_indent.id_shop = '".addslashes($_SESSION['shop'])."' and inv_indent.doc_type = '".$doc_type."' $datefilter  $department  $multiple $main_group $sub_group" ;
       

       $sql = "SELECT inv_indent.indent_date, inv_indent.mdoc_no,  inv_indent.indent_no, 
                        inv_indent_details.qty,inv_indent_details.alt_qty, inv_indent_details.id,inv_indent_details.id_inv_indent, inv_indent_details.main_unit, inv_indent_details.alt_unit, inv_indent_details.bal_qty, inv_indent_details.ordered_qty, 
                        inv_items.item_code, inv_items.name, inv_items.conversion_qty, inv_items.id as item_id, 
                        mst_attributes.field_value 
                        FROM inv_items, mst_attributes, inv_indent_details, inv_indent WHERE $where";
                      
                        $db->query($sql);
                        $numRows= $db->num_rows(); 
                        //Table Section
                        $table = '<br><h4 style="padding-left:38%;">'.$companyname.'</h4><p  style="padding-left:21%;">Product Wise Requistion Note From '.date('d-m-Y' , strtotime(addslashes($minimum_date))). ' To '.date('d-m-Y' , strtotime(addslashes($maximum_date))).'</p>';
                        $i=1; 
                        
                        while($row22 = $db->fetch_object()){
 
                          $table .= '
                          <p style="margin-left:1.5%;font-size:12px;" ><b>Item Code:</b> '.$row22->item_code.', <b>Item Descriptions: </b>'.$row22->name.'</p> 
                          <table style="width:100%;" border="1" cellspacing="1"  cellpadding="1" >';
                          $table .= '
                          <thead>                     
                            <tr style="text-align: center; font-size: 12px; padding-top:20px;">
                                <th style="width: 15%;">&nbsp;&nbsp;&nbsp; Indent No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                                <th style="width: 10%;">&nbsp; Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                                <th style="width: 15%;">&nbsp;Department &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                                <th style="width: 12%;"> Remarks &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                                <th style="width: 15%;">&nbsp;&nbsp;&nbsp; Indent Qty &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                                <th style="width: 15%;">&nbsp;&nbsp;&nbsp; Issued Qty &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                                <th style="width: 10%;"> Adjust &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                                <th style="width: 12%;">&nbsp; Balance &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                            </tr>
                        </thead>';
                          $table .= '<tr style="text-align: center; font-size:12px;"><td>';
                             
                                $table .= $row22->mdoc_no;
                              
                             $table .= '</td>
                               
                              <td>';  
                               $table .= date('d-m-Y' , strtotime(addslashes($row22->indent_date)));
                               
                             $table .= '</td>
                              <td>'; 
                               $table .= $row22->field_value; 
                             $table .= '</td>
                              <td>';
                                $table .= '';
                               $table .= '</td>
                              <td>'; 
                                $table .=$row22->qty;
                               $table .= '</td>
                              <td>';
                                 $table .=$row22->ordered_qty; 
                                $table .= '</td>
                              <td>';
                                $table .= '';
                               $table .= '</td>
                               <td>';
                                $table .= $row22->bal_qty;
                               $table .= '</td>
                               </tr> <tr  style="text-align: center; font-size:12px;"> '; 
                             $table .= '<td>';  $table .= '</td> <td>';  
                               $table .= '</td>
                              <td>';   $table .= '</td>
                              <td>';
                                $table .= 'Total';
                               $table .= '</td>
                              <td>'; 
                                $table .=$row22->qty;
                               $table .= '</td>
                              <td>';
                                 $table .=$row22->ordered_qty; 
                                $table .= '</td>
                              <td>';
                                $table .= '';
                               $table .= '</td>
                               <td>';
                                $table .= $row22->bal_qty;
                               $table .= '</td>';

                               $mylist[$i] =$row22->mdoc_no.','.date('d-m-Y' , strtotime(addslashes($row22->indent_date))).','.$row22->field_value.','. $row22->qty.','.$row22->ordered_qty.','.$row22->bal_qty.','.'0'; 

                              $i++; $mdoc_no = $row22->mdoc_no;
                        $table .= '</table>';
                        }  
                        
                        //PDF Generate
                        $dompdf = new DOMPDF(); 
                        $dompdf->set_paper('A4', 'A4');
                        $dompdf->load_html($table);
                        $dompdf->render();
                        $font = Font_Metrics::get_font("helvetica", "bold");
                        $dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0)); 
                        $gen = $dompdf->output(); 
                        file_put_contents('../pdf/Requistion_Note_party_items.pdf', $gen);
                        //Mail Attach
                        $Filename = '../pdf/Requistion_Note_party_items.pdf';      
                        $gen = $dompdf->output(); 
                        file_put_contents('/home/admingcs/public_html/sales/adminpanel/mailattach/'.$Filename.'.pdf', $gen);
echo ($table);
    }

//#################################################################
 //Purchase_order_reports
//#################################################################

  function Purchase_order_reports($clicked_id,$doc_type,$multiple_item_val,$main_group_val,$id_mst_attributes_department,$pia,$check_value,$datefilter,$party_val,$db){

      $mdoc_no = '';
      $date = explode(' to ', $datefilter); 
      $minimum_date = date('Y-m-d' , strtotime(addslashes($date[0])));
      $maximum_date = date('Y-m-d' , strtotime(addslashes($date[1]))); 
       
      $sql12 = " SELECT * FROM `".TBL_SHOP."` WHERE `id`='".addslashes($_SESSION['shop'])."' ";
      $db->query($sql12);   
        while($row12 = $db->fetch_object()){ 
          $companyname= $row12->name;  
        } 

      // Allocation Set Here
        if($minimum_date != '' && $maximum_date != ''){
            $datefilter =  "and inv_po.po_date >='".$minimum_date."' and inv_po.po_date <='".$maximum_date."' ";
        }else{
          $minimum_date = '2010-01-01';
          $maximum_date = date('Y-m-d');
          $datefilter ='';
        }
        //Department Based Search
        if($id_mst_attributes_department != '' ){
            $department =  "and inv_po.id_mst_attributes_department ='".$id_mst_attributes_department."'  ";
        }else{ 
          $department =  '';
        }
        //Multiple Items
        $multiple_item_array = explode(',', $multiple_item_val);

        if($multiple_item_val != '' ){
            for($i=0;$i<count($multiple_item_array);$i++){
              $val=$multiple_item_array[$i];
              $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and name = '".$val."' ");   
                while($row = $db->fetch_object2($resCat)){ 
                  $array[$i] = $row->id;
                }
            }  
            $array = implode("','",$array); 
            $multiple =  "and inv_po_details.id_inv_items IN ('".$array."') ";
        }else{ 
            $multiple =  '';
        } 
        //Main Group
        $main_group_array = explode(',', $main_group_val);
        if($main_group_val != '' ){
              //Attributes Table 
            for($i=0;$i<count($main_group_array);$i++){
              $val=$main_group_array[$i];
              $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_main' AND field_value = '".$val."' ");   
                while($row = $db->fetch_object2($resCat)){ 
                  $array[$i] = $row->id;
                }
            } 
              //Items Table
            $k=0;   
            for($i=0;$i<count($array);$i++){
                $val=$array[$i];
                $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_main = '".$val."' ");
                while($row = $db->fetch_object2($resCat)){ 
                  $array_s[$k] = $row->id;
                  $k++;
                } 
              } 
              $array = implode("','",$array_s);   
              $main_group = "and inv_po_details.id_inv_items IN ('".$array."') ";
        }else{ 
            $main_group =  '';
        } 

        //Sub Group
        $sub_group_array = explode(',', $sub_group_val);
        if($sub_group_val != '' ){
              //Attributes Table 
            for($i=0;$i<count($sub_group_array);$i++){
              $val=$sub_group_array[$i];
                $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_sub' AND field_value = '".$val."' ");   
                  while($row = $db->fetch_object2($resCat)){ 
                    $array[$i] = $row->id;
                  }
            }
              //Items Table
            $k=0;   
              for($i=0;$i<count($array);$i++){
                $val=$array[$i];
                $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_sub = '".$val."' ");           
                while($row = $db->fetch_object2($resCat)){ 
                  $array_s[$k] = $row->id;
                  $k++;
                } 
              }
              $array = implode("','",$array_s);   
              $sub_group = "and inv_po_details.id_inv_items IN ('".$array."') ";
        }else{ 
            $sub_group =  '';
        } 
        //Party
        $party_val_array = explode(',', $party_val);
        if($party_val != '' ){
              //Attributes Table 
           $k=0; 
            for($i=0;$i<count($party_val_array);$i++){
                $val=$party_val_array[$i];
              $resCat = selectSql(TBL_PARTY," where id_shop= '".addslashes($_SESSION['shop'])."'  AND company_name = '".$val."' ");   
                while($row = $db->fetch_object2($resCat)){ 
                  $array_s[$k] = $row->id;
                  $k++;
                }  
            } 
            $array = implode("','",$array_s); 
            $party = "and inv_po.id_mst_party_supplier IN ('".$array."') "; 
        }else{ 
            $party =  '';
        } 
      $mylist = array();
      //Popup Table Show  
        $where = "mst_party.id=inv_po.id_mst_party_supplier and inv_po.id = inv_po_details.id_inv_po and inv_po_details.id_inv_items = inv_items.id  and inv_po.id_shop = '".addslashes($_SESSION['shop'])."' and inv_po.doc_type = '".$doc_type."' $datefilter  $multiple $main_group $sub_group $party" ;
       

       $sql = "SELECT inv_po.po_date, inv_po.mdoc_no,  inv_po.po_no, 
                              inv_po_details.qty,inv_po_details.alt_qty, inv_po_details.id,inv_po_details.id_inv_po, inv_po_details.main_unit, inv_po_details.alt_unit, inv_po_details.bal_qty, inv_po_details.ordered_qty, inv_po_details.rate_per_main_unit, inv_po_details.item_remarks,
                        inv_items.item_code, inv_items.name, inv_items.conversion_qty, inv_items.id as item_id,  mst_party.company_name 
                        FROM inv_items, mst_party, inv_po_details, inv_po WHERE $where"; 
                        $db->query($sql);
                        $numRows= $db->num_rows(); 
                        //Table Section
                        $table = '<br><h4 style="padding-left:38%;">'.$companyname.'</h4><p  style="padding-left:20%;">PO No Wise Purchase Order From '.date('d-m-Y' , strtotime(addslashes($minimum_date))). ' To '.date('d-m-Y' , strtotime(addslashes($maximum_date))).'</p>';
                        $i=1; 
                        $table .= '<table style="margin-top:1.5%;width:100%;" border="1" cellspacing="1"  cellpadding="1" > 
                        <thead>                     
                          <tr style="text-align: center; font-size: 12px;">
                              <th style="width: 10%;">&nbsp;&nbsp; PO No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                              <th style="width: 10%;"> PO Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                              <th style="width: 10%;">&nbsp;&nbsp; Supplier &nbsp;&nbsp;&nbsp;&nbsp;</th> 
                              <th  style="width: 10%;"> &nbsp;Item Code &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                              <th  style="width: 15%;">&nbsp;&nbsp; Item Description &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                              <th  style="width: 10%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Qty &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                              <th style="width: 10%;">&nbsp; Value &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
                          </tr>
                      </thead>';
                        while($row22 = $db->fetch_object()){ 
                         $table .= '<tr style="text-align: center; font-size:12px;"><td>';
                                if($mdoc_no != $row22->mdoc_no){
                                $table .= $row22->mdoc_no;
                              }
                             $table .= '</td>
                               
                              <td>';
                              if($mdoc_no != $row22->mdoc_no){
                                 
                             $table .= date('d-m-Y' , strtotime(addslashes($row22->po_date)));
                               }
                             $table .= '</td>
                              <td>';                                 
                                $table .= $row22->company_name;
                              
                             $table .= '</td>
                              <td>';
                                $table .= $row22->item_code;
                               $table .= '</td>
                              <td>'; 
                                $table .=$row22->name;
                               $table .= '</td>
                              <td>';
                                 $table .=$row22->qty; 
                                $table .= '</td>
                              <td>';
                                $table .= $row22->rate_per_main_unit;;
                               $table .= '</td>
                               </tr>  '; 

                              $mylist[$i] =$row22->mdoc_no.','.date('d-m-Y' , strtotime(addslashes($row22->po_date))).','. $row22->company_name.','.$row22->item_code.','.$row22->name * $row22->qty.','.$row22->rate_per_main_unit; 

                              $i++; $mdoc_no = $row22->mdoc_no;
                        } 
                        $table .= '</table>'; 
                        //PDF Generate
                        $dompdf = new DOMPDF(); 
                        $dompdf->set_paper('A4', 'A4');
                        $dompdf->load_html($table);
                        $dompdf->render();
                        $font = Font_Metrics::get_font("helvetica", "bold");
                        $dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0)); 
                        $gen = $dompdf->output(); 
                        file_put_contents('../pdf/Purchase_Order.pdf', $gen);
                        //Mail Attach
                        $Filename = '../pdf/Purchase_Order.pdf';      
                        $gen = $dompdf->output(); 
                        file_put_contents('/home/admingcs/public_html/sales/adminpanel/mailattach/'.$Filename.'.pdf', $gen);
 
echo ($table); 

    }


//#################################################################
  //Purchase_order_reports_Party_item_reports
//#################################################################

   function Purchase_order_reports_Party_item_reports($clicked_id,$doc_type,$multiple_item_val,$main_group_val,$id_mst_attributes_department,$pia,$check_value,$datefilter,$party_val,$db){

      $mdoc_no = '';
        $date = explode(' to ', $datefilter); 
        $minimum_date = date('Y-m-d' , strtotime(addslashes($date[0])));
        $maximum_date = date('Y-m-d' , strtotime(addslashes($date[1]))); 
         
        $sql12 = " SELECT * FROM `".TBL_SHOP."` WHERE `id`='".addslashes($_SESSION['shop'])."' ";
        $db->query($sql12);   
          while($row12 = $db->fetch_object()){ 
            $companyname= $row12->name;  
          } 

        // Allocation Set Here
          if($minimum_date != '' && $maximum_date != ''){
              $datefilter =  "and inv_po.po_date >='".$minimum_date."' and inv_po.po_date <='".$maximum_date."' ";
          }else{
            $minimum_date = '2010-01-01';
            $maximum_date = date('Y-m-d');
            $datefilter ='';
          }
          //Department Based Search
          if($id_mst_attributes_department != '' ){
              $department =  "and inv_po.id_mst_attributes_department ='".$id_mst_attributes_department."'  ";
          }else{ 
            $department =  '';
          }
          //Multiple Items
          $multiple_item_array = explode(',', $multiple_item_val);

          if($multiple_item_val != '' ){
              for($i=0;$i<count($multiple_item_array);$i++){
                $val=$multiple_item_array[$i];
                $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and name = '".$val."' ");   
                  while($row = $db->fetch_object2($resCat)){ 
                    $array[$i] = $row->id;
                  }
              }  
              $array = implode("','",$array); 
              $multiple =  "and inv_po_details.id_inv_items IN ('".$array."') ";
          }else{ 
              $multiple =  '';
          } 
          //Main Group
          $main_group_array = explode(',', $main_group_val);
          if($main_group_val != '' ){
                //Attributes Table 
              for($i=0;$i<count($main_group_array);$i++){
                $val=$main_group_array[$i];
                $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_main' AND field_value = '".$val."' ");   
                  while($row = $db->fetch_object2($resCat)){ 
                    $array[$i] = $row->id;
                  }
              } 
                //Items Table
              $k=0;   
              for($i=0;$i<count($array);$i++){
                  $val=$array[$i];
                  $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_main = '".$val."' ");
                  while($row = $db->fetch_object2($resCat)){ 
                    $array_s[$k] = $row->id;
                    $k++;
                  } 
                } 
                $array = implode("','",$array_s);    
                $main_group = "and inv_po_details.id_inv_items IN ('".$array."') ";
          }else{ 
              $main_group =  '';
          } 

          //Sub Group
          $sub_group_array = explode(',', $sub_group_val);
          if($sub_group_val != '' ){
                //Attributes Table 
              for($i=0;$i<count($sub_group_array);$i++){
                $val=$sub_group_array[$i];
                  $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_sub' AND field_value = '".$val."' ");   
                    while($row = $db->fetch_object2($resCat)){ 
                      $array[$i] = $row->id;
                    }
              }
                //Items Table
              $k=0;   
                for($i=0;$i<count($array);$i++){
                  $val=$array[$i];
                  $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_sub = '".$val."' ");           
                  while($row = $db->fetch_object2($resCat)){ 
                    $array_s[$k] = $row->id;
                    $k++;
                  } 
                }
                $array = implode("','",$array_s);   
                $sub_group = "and inv_po_details.id_inv_items IN ('".$array."') ";
          }else{ 
              $sub_group =  '';
          } 
          //Party Wise
          $party_val_array = explode(',', $party_val);
          if($party_val != '' ){
                //Attributes Table 
             $k=0; 
              for($i=0;$i<count($party_val_array);$i++){
                  $val=$party_val_array[$i];
                $resCat = selectSql(TBL_PARTY," where id_shop= '".addslashes($_SESSION['shop'])."'  AND company_name = '".$val."' ");   
                  while($row = $db->fetch_object2($resCat)){ 
                    $array_s[$k] = $row->id;
                    $k++;
                  }  
              } 
              $array = implode("','",$array_s); 
              $party = "and inv_po.id_mst_party_supplier IN ('".$array."') "; 
          }else{ 
              $party =  '';
          } 
        $mylist = array();

        //Popup Table Show  
          $where = "mst_party.id=inv_po.id_mst_party_supplier and inv_po.id = inv_po_details.id_inv_po and inv_po_details.id_inv_items = inv_items.id  and inv_po.id_shop = '".addslashes($_SESSION['shop'])."' and inv_po.doc_type = '".$doc_type."' $datefilter  $multiple $main_group $sub_group $party" ;
         

         $sql = "SELECT inv_po.po_date, inv_po.mdoc_no,  inv_po.po_no, 
                                inv_po_details.qty,inv_po_details.alt_qty, inv_po_details.id,inv_po_details.id_inv_po, inv_po_details.main_unit, inv_po_details.alt_unit, inv_po_details.bal_qty, inv_po_details.ordered_qty, inv_po_details.rate_per_main_unit, inv_po_details.item_remarks,
                        inv_items.item_code, inv_items.name, inv_items.conversion_qty, inv_items.id as item_id,  mst_party.company_name 
                        FROM inv_items, mst_party, inv_po_details, inv_po WHERE $where"; 
                        $db->query($sql);
                        $numRows= $db->num_rows(); 
                        //Table Section
                        $table = '<br><h4 style="padding-left:38%;">'.$companyname.'</h4><p  style="padding-left:21%;">Product Wise Purchase Order From '.date('d-m-Y' , strtotime(addslashes($minimum_date))). ' To '.date('d-m-Y' , strtotime(addslashes($maximum_date))).'</p>';
                        $i=1; 
                        
                        while($row22 = $db->fetch_object()){
 
                          $table .= '
                          <p style="margin-left:1.5%;" ><b>Item Code:</b> '.$row22->item_code.', <b>Item Descriptions: </b>'.$row22->name.'</p> 
                          <table style="width:100%;" border="1" cellspacing="1"  cellpadding="1" >';
                          
                          $table .= '
                          <thead>                     
                            <tr style="text-align: center; font-size: 14px; padding-top:20px;">
                                <th style="width: 10%;">&nbsp;&nbsp;&nbsp; PO No &nbsp;&nbsp;&nbsp;</th> 
                                <th style="width: 10%;">&nbsp;&nbsp;&nbsp; PO Date &nbsp;&nbsp;&nbsp;</th> 
                                <th style="width: 10%;">&nbsp;&nbsp;&nbsp; Supplier &nbsp;&nbsp;&nbsp;</th> 
                                <th style="width: 10%;">&nbsp;&nbsp;&nbsp; Remarks &nbsp;&nbsp;&nbsp;</th> 
                                <th style="width: 15%;">&nbsp;&nbsp;&nbsp; PO Qty &nbsp;&nbsp;&nbsp;</th> 
                                <th style="width: 10%;">&nbsp;&nbsp;&nbsp; Issued Qty &nbsp;&nbsp;&nbsp;</th> 
                                <th style="width: 10%;">&nbsp;&nbsp;&nbsp; Adjust &nbsp;&nbsp;&nbsp;</th> 
                                <th style="width: 10%;">&nbsp;&nbsp;&nbsp; Balance &nbsp;&nbsp;&nbsp;</th> 
                            </tr>
                        </thead>';
                          $table .= '<tr>
                              
                              <td>';
                             
                                $table .= $row22->mdoc_no;
                              
                             $table .= '</td>
                               
                              <td>';  
                               $table .= date('d-m-Y' , strtotime(addslashes($row22->po_date)));
                               
                             $table .= '</td>
                              <td>'; 
                               $table .= $row22->company_name; 
                             $table .= '</td>
                              <td>';
                                $table .= $row22->item_remarks;
                               $table .= '</td>
                              <td>'; 
                                $table .=$row22->qty;
                               $table .= '</td>
                              <td>';
                                 $table .=$row22->ordered_qty; 
                                $table .= '</td>
                              <td>';
                                $table .= '';
                               $table .= '</td>
                               <td>';
                                $table .= $row22->bal_qty;
                               $table .= '</td>
                               </tr> <tr> '; 
                             $table .= '<td>';  $table .= '</td> <td>';  
                               $table .= '</td>
                              <td>';   $table .= '</td>
                              <td>';
                                $table .= 'Total';
                               $table .= '</td>
                              <td>'; 
                                $table .=$row22->qty;
                               $table .= '</td>
                              <td>';
                                 $table .=$row22->ordered_qty; 
                                $table .= '</td>
                              <td>';
                                $table .= '';
                               $table .= '</td>
                               <td>';
                                $table .= $row22->bal_qty;
                               $table .= '</td>';

                               $mylist[$i] =$row22->mdoc_no.','.date('d-m-Y' , strtotime(addslashes($row22->po_date))).','. $row22->company_name.','.$row22->item_remarks.','.$row22->qty * $row22->ordered_qty.','.$row22->bal_qty; 

                              $i++; $mdoc_no = $row22->mdoc_no;
                        $table .= '</table>';
                        }  
                        //PDF Generate
                        $dompdf = new DOMPDF(); 
                        $dompdf->set_paper('A4', 'A4');
                        $dompdf->load_html($table);
                        $dompdf->render();
                        $font = Font_Metrics::get_font("helvetica", "bold");
                        $dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0)); 
                        $gen = $dompdf->output(); 
                        file_put_contents('../pdf/Purchase_Order_party_items.pdf', $gen);
                        //Mail Attach
                        $Filename = '../pdf/Purchase_Order_party_items.pdf';      
                        $gen = $dompdf->output(); 
                        file_put_contents('/home/admingcs/public_html/sales/adminpanel/mailattach/'.$Filename.'.pdf', $gen);
 
echo ($table); 
   } 

//#################################################################
   //Purchase_Register_reports
//#################################################################

   function Purchase_Register_reports($clicked_id,$doc_type,$multiple_item_val,$main_group_val,$id_mst_attributes_department,$pia,$check_value,$datefilter,$party_val,$db){

      $mdoc_no = '';
$date = explode(' to ', $datefilter); 
$minimum_date = date('Y-m-d' , strtotime(addslashes($date[0])));
$maximum_date = date('Y-m-d' , strtotime(addslashes($date[1]))); 
 
$sql12 = " SELECT * FROM `".TBL_SHOP."` WHERE `id`='".addslashes($_SESSION['shop'])."' ";
$db->query($sql12);   
  while($row12 = $db->fetch_object()){ 
    $companyname= $row12->name;  
  } 

// Allocation Set Here
  if($minimum_date != '' && $maximum_date != ''){
      $datefilter =  "and inv_purch.po_date >='".$minimum_date."' and inv_purch.po_date <='".$maximum_date."' ";
  }else{
    $minimum_date = '2010-01-01';
    $maximum_date = date('Y-m-d');
    $datefilter ='';
  }
  //Department Based Search
  if($id_mst_attributes_department != '' ){
      $department =  "and inv_purch.id_mst_attributes_department ='".$id_mst_attributes_department."'  ";
  }else{ 
    $department =  '';
  }
  //Multiple Items
  $multiple_item_array = explode(',', $multiple_item_val);

  if($multiple_item_val != '' ){
      for($i=0;$i<count($multiple_item_array);$i++){
        $val=$multiple_item_array[$i];
        $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and name = '".$val."' ");   
          while($row = $db->fetch_object2($resCat)){ 
            $array[$i] = $row->id;
          }
      }  
      $array = implode("','",$array); 
      $multiple =  "and inv_purch_details.id_inv_items IN ('".$array."') ";
  }else{ 
      $multiple =  '';
  } 
  //Main Group
  $main_group_array = explode(',', $main_group_val);
  if($main_group_val != '' ){
        //Attributes Table 
      for($i=0;$i<count($main_group_array);$i++){
        $val=$main_group_array[$i];
        $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_main' AND field_value = '".$val."' ");   
          while($row = $db->fetch_object2($resCat)){ 
            $array[$i] = $row->id;
          }
      } 
        //Items Table
      $k=0;   
      for($i=0;$i<count($array);$i++){
          $val=$array[$i];
          $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_main = '".$val."' ");
          while($row = $db->fetch_object2($resCat)){ 
            $array_s[$k] = $row->id;
            $k++;
          } 
        } 
        $array = implode("','",$array_s);  
        $main_group = "and inv_purch_details.id_inv_items IN ('".$array."') ";
  }else{ 
      $main_group =  '';
  } 

  //Sub Group
  $sub_group_array = explode(',', $sub_group_val);
  if($sub_group_val != '' ){
        //Attributes Table 
      for($i=0;$i<count($sub_group_array);$i++){
        $val=$sub_group_array[$i];
          $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_sub' AND field_value = '".$val."' ");   
            while($row = $db->fetch_object2($resCat)){ 
              $array[$i] = $row->id;
            }
      }
        //Items Table
      $k=0;   
        for($i=0;$i<count($array);$i++){
          $val=$array[$i];
          $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_sub = '".$val."' ");           
          while($row = $db->fetch_object2($resCat)){ 
            $array_s[$k] = $row->id;
            $k++;
          } 
        }
        $array = implode("','",$array_s);   
        $sub_group = "and inv_purch_details.id_inv_items IN ('".$array."') ";
  }else{ 
      $sub_group =  '';
  } 
  //Party
  $party_val_array = explode(',', $party_val);
  if($party_val != '' ){
        //Attributes Table 
     $k=0; 
      for($i=0;$i<count($party_val_array);$i++){
          $val=$party_val_array[$i];
        $resCat = selectSql(TBL_PARTY," where id_shop= '".addslashes($_SESSION['shop'])."'  AND company_name = '".$val."' ");   
          while($row = $db->fetch_object2($resCat)){ 
            $array_s[$k] = $row->id;
            $k++;
          }  
      } 
      $array = implode("','",$array_s); 
      $party = "and inv_purch.id_mst_party_supplier IN ('".$array."') ";  
  }else{ 
      $party =  '';
  } 
$mylist = array();
//Popup Table Show  
  $where = "mst_party.id=inv_purch.id_mst_party_supplier and inv_purch.id = inv_purch_details.id_inv_purch and inv_purch_details.id_inv_items = inv_items.id  and inv_purch.id = inv_others_charges_purch.id_inv_purch and inv_purch.id_shop = '".addslashes($_SESSION['shop'])."' and inv_purch.doc_type = '".$doc_type."' $datefilter  $multiple $main_group $sub_group $party" ;
 

 $sql = "SELECT inv_purch.*,  inv_purch_details.*,inv_others_charges_purch.*,
                        inv_items.item_code, inv_items.name, inv_items.conversion_qty, inv_items.id as item_id,  mst_party.company_name 
                        FROM inv_items, mst_party, inv_purch_details, inv_purch, inv_others_charges_purch WHERE $where";  
                        $db->query($sql);
                        $numRows= $db->num_rows(); 
                        //Table Section
                        $table = '<br><h4 style="padding-left:40%;">'.$companyname.'</h4><p  style="padding-left:27%;">Serial No Wise Purchase Register From '.date('d-m-Y' , strtotime(addslashes($minimum_date))). ' To '.date('d-m-Y' , strtotime(addslashes($maximum_date))).'</p>';
                        $i=1; 
                        $table .= '<table  border="1" cellspacing="1"  cellpadding="1" style ="width:100%"> 
                        <thead>                     
                          <tr style="text-align: center; font-size: 10px;">
                              <th style="width: 10%;">Doc No</th> 
                              <th style="width: 10%;">Date</th> 
                              <th style="width: 10%;">Supplier</th> 
                              <th  style="width: 10%;">Bill No</th> 
                              <th  style="width: 15%;">Date</th> 
                              <th  style="width: 10%;">Item Code</th> 
                              <th style="width: 10%;">Item Descriptions</th> 
                              <th style="width: 10%;">Qty</th> 
                              <th style="width: 10%;">Rate</th> 
                              <th style="width: 10%;">Value</th> 
                              <th style="width: 10%;">Disc</th> 
                              <th style="width: 10%;">Service Tax</th> 
                              <th style="width: 10%;">Cess</th> 
                              <th style="width: 10%;">HCess</th> 
                              <th style="width: 10%;">TaxA/C</th> 
                              <th style="width: 10%;">Sales Tax</th> 
                              <th style="width: 10%;">Sreg</th> 
                              <th style="width: 10%;">Other Charges</th> 
                              <th style="width: 10%;">Amount</th> 
                              <th style="width: 10%;">Net Amount</th> 
                          </tr>
                      </thead>';
                        while($row22 = $db->fetch_object()){ 
                          //SGST 
                         $sgst =  selectColumn(TBL_CHARGES,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row22->id_mst_charges_sgst)."'");
                         $cgst =  selectColumn(TBL_CHARGES,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row22->id_mst_charges_cgst)."'");
                         $igst =  selectColumn(TBL_CHARGES,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row22->id_mst_charges_igst)."'");
                            $table .= '<tr style="text-align: center; font-size:10px;"><td>';
                                if($mdoc_no != $row22->mdoc_no){
                                $table .= $row22->po_no;
                              }
                             $table .= '</td>
                               
                              <td>';
                              if($mdoc_no != $row22->mdoc_no){
                                $table .= date('d-m-Y' , strtotime(addslashes($row22->po_date)));
                               }
                              $table .= '</td><td>';                                 
                              $table .= $row22->company_name;
                              $table .= '</td><td>';
                              $table .= '</td><td>';
                              $table .= '</td><td>';
                              $table .= $row22->item_code;
                              $table .= '</td> <td>'; 
                              $table .= $row22->name;
                              $table .= '</td><td>';
                              $table .= $row22->qty; 
                              $table .= '</td> <td>';
                              $table .= $row22->rate_per_main_unit;
                              $table .= '</td><td>'; 
                              $table .=  $row22->qty * $row22->rate_per_main_unit;
                              $table .= '</td><td>'; 
                              $table .=  $row22->discount_amount;
                              $table .= '</td><td>';
                              $table .= '</td><td>';
                              $table .= '</td><td>'; 
                              $table .= '</td><td>'; 
                              $table .=  $sgst.','.$cgst.','.$igst;
                              $table .= '</td><td>'; 
                              $table .= '</td><td>'; 
                              $table .= '</td><td>'; 
                              if($mdoc_no != $row22->mdoc_no){
                                $table .=  $row22->others_charges_amount;
                              }
                              $table .= '</td><td>'; 
                              $table .=  $row22->qty * $row22->rate_per_main_unit;
                              $table .= '</td><td>'; 
                              if($mdoc_no != $row22->mdoc_no){
                                $table .=  $row22->net_amount_items;
                              }
                              $table .= '</td>'; 

                              $mylist[$i] =$row22->po_no.','.date('d-m-Y' , strtotime(addslashes($row22->po_date))).','.$row22->company_name.','.$row22->item_code.','.$row22->name.','. $row22->qty.','.$row22->rate_per_main_unit.','.$row22->qty * $row22->rate_per_main_unit.','.$row22->discount_amount.','.$sgst.','.$cgst.','.$igst.','.$row22->others_charges_amount.','.$row22->net_amount_items; 


                              $i++; $mdoc_no = $row22->mdoc_no;
                        } 
                        $table .= '</table>'; 
                        //PDF Generate
                        $dompdf = new DOMPDF(); 
                        $dompdf->set_paper('landscape', 'landscape');
                        $dompdf->load_html($table);
                        $dompdf->render();
                        $font = Font_Metrics::get_font("helvetica", "bold");
                        $dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0)); 
                        $gen = $dompdf->output(); 
                        file_put_contents('../pdf/Purchase_Register.pdf', $gen);
                        //Mail Attach
                        $Filename = '../pdf/Purchase_Register.pdf';      
                        $gen = $dompdf->output(); 
                        file_put_contents('/home/admingcs/public_html/sales/adminpanel/mailattach/'.$Filename.'.pdf', $gen);
 
echo ($table); 

   }

//#################################################################
  //Purchase_Register_reports_item_reports
//#################################################################

   function Purchase_Register_reports_item_reports($clicked_id,$doc_type,$multiple_item_val,$main_group_val,$id_mst_attributes_department,$pia,$check_value,$datefilter,$party_val,$db){

    $mdoc_no = '';
    $date = explode(' to ', $datefilter); 
    $minimum_date = date('Y-m-d' , strtotime(addslashes($date[0])));
    $maximum_date = date('Y-m-d' , strtotime(addslashes($date[1]))); 
     
    $sql12 = " SELECT * FROM `".TBL_SHOP."` WHERE `id`='".addslashes($_SESSION['shop'])."' ";
    $db->query($sql12);   
      while($row12 = $db->fetch_object()){ 
        $companyname= $row12->name;  
      } 

    // Allocation Set Here
      if($minimum_date != '' && $maximum_date != ''){
          $datefilter =  "and inv_purch.po_date >='".$minimum_date."' and inv_purch.po_date <='".$maximum_date."' ";
      }else{
        $minimum_date = '2010-01-01';
        $maximum_date = date('Y-m-d');
        $datefilter ='';
      }
      //Department Based Search
      if($id_mst_attributes_department != '' ){
          $department =  "and inv_purch.id_mst_attributes_department ='".$id_mst_attributes_department."'  ";
      }else{ 
        $department =  '';
      }
      //Multiple Items
      $multiple_item_array = explode(',', $multiple_item_val);

      if($multiple_item_val != '' ){
          for($i=0;$i<count($multiple_item_array);$i++){
            $val=$multiple_item_array[$i];
            $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and name = '".$val."' ");   
              while($row = $db->fetch_object2($resCat)){ 
                $array[$i] = $row->id;
              }
          }  
          $array = implode("','",$array); 
          $multiple =  "and inv_purch_details.id_inv_items IN ('".$array."') ";
      }else{ 
          $multiple =  '';
      } 
      //Main Group
      $main_group_array = explode(',', $main_group_val);
      if($main_group_val != '' ){
            //Attributes Table 
          for($i=0;$i<count($main_group_array);$i++){
            $val=$main_group_array[$i];
            $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_main' AND field_value = '".$val."' ");   
              while($row = $db->fetch_object2($resCat)){ 
                $array[$i] = $row->id;
              }
          } 
            //Items Table
          $k=0;   
          for($i=0;$i<count($array);$i++){
              $val=$array[$i];
              $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_main = '".$val."' ");
              while($row = $db->fetch_object2($resCat)){ 
                $array_s[$k] = $row->id;
                $k++;
              } 
            } 
            $array = implode("','",$array_s);  
            $main_group = "and inv_purch_details.id_inv_items IN ('".$array."') ";
      }else{ 
          $main_group =  '';
      } 

      //Sub Group
      $sub_group_array = explode(',', $sub_group_val);
      if($sub_group_val != '' ){
            //Attributes Table 
          for($i=0;$i<count($sub_group_array);$i++){
            $val=$sub_group_array[$i];
              $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_sub' AND field_value = '".$val."' ");   
                while($row = $db->fetch_object2($resCat)){ 
                  $array[$i] = $row->id;
                }
          }
            //Items Table
          $k=0;   
            for($i=0;$i<count($array);$i++){
              $val=$array[$i];
              $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_sub = '".$val."' ");           
              while($row = $db->fetch_object2($resCat)){ 
                $array_s[$k] = $row->id;
                $k++;
              } 
            }
            $array = implode("','",$array_s);   
            $sub_group = "and inv_purch_details.id_inv_items IN ('".$array."') ";
      }else{ 
          $sub_group =  '';
      } 
      //Party
       $party_val_array = explode(',', $party_val);
      if($party_val != '' ){
            //Attributes Table 
         $k=0; 
          for($i=0;$i<count($party_val_array);$i++){
              $val=$party_val_array[$i];
            $resCat = selectSql(TBL_PARTY," where id_shop= '".addslashes($_SESSION['shop'])."'  AND company_name = '".$val."' ");   
              while($row = $db->fetch_object2($resCat)){ 
                $array_s[$k] = $row->id;
                $k++;
              }  
          } 
          $array = implode("','",$array_s); 
          $party = "and inv_purch.id_mst_party_supplier IN ('".$array."') ";  
      }else{ 
          $party =  '';
      } 
      $mylist = array();

    //Popup Table Show  
      $where = "mst_party.id=inv_purch.id_mst_party_supplier and inv_purch.id = inv_purch_details.id_inv_purch and inv_purch_details.id_inv_items = inv_items.id  and inv_purch.id_shop = '".addslashes($_SESSION['shop'])."' and inv_purch.doc_type = '".$doc_type."' $datefilter  $multiple $main_group $sub_group $party" ;
     

     $sql = "SELECT inv_purch.po_date, inv_purch.mdoc_no,  inv_purch.po_no, 
                        inv_purch_details.qty,inv_purch_details.alt_qty, inv_purch_details.id,inv_purch_details.id_inv_purch, inv_purch_details.main_unit, inv_purch_details.alt_unit, inv_purch_details.rate_per_main_unit, inv_purch_details.item_remarks,
                        inv_items.item_code, inv_items.name, inv_items.conversion_qty, inv_items.id as item_id,  mst_party.company_name 
                        FROM inv_items, mst_party, inv_purch_details, inv_purch WHERE $where"; 
                        $db->query($sql);
                        $numRows= $db->num_rows(); 
                        //Table Section
                        $table = '<br><h4 style="padding-left:38%;">'.$companyname.'</h4><p  style="padding-left:21%;">Product Wise Purchase Register From '.date('d-m-Y' , strtotime(addslashes($minimum_date))). ' To '.date('d-m-Y' , strtotime(addslashes($maximum_date))).'</p>';
                        $i=1; 
                        
                        while($row22 = $db->fetch_object()){
 
                          $table .= '
                          <p style="margin-left:1.5%;" ><b>Item Code:</b> '.$row22->item_code.', <b>Item Descriptions: </b>'.$row22->name.'</p> 
                          <table style="margin-top:1.5%;width: 100%;" border="1" cellspacing="1"  cellpadding="1" >';
                          
                          $table .= '
                          <thead>                     
                            <tr style="text-align: center; font-size: 14px;">
                                <th style="width: 20%;">&nbsp;&nbsp;&nbsp; Doc No &nbsp;&nbsp;&nbsp;</th> 
                                <th style="width: 20%;">&nbsp;&nbsp;&nbsp; Date &nbsp;&nbsp;&nbsp;</th> 
                                <th style="width: 20%;">&nbsp;&nbsp;&nbsp; Supplier &nbsp;&nbsp;&nbsp;</th> 
                                <th style="width: 20%;">&nbsp;&nbsp;&nbsp; Remarks &nbsp;&nbsp;&nbsp;</th> 
                                <th style="width: 20%;">&nbsp;&nbsp;&nbsp; Qty &nbsp;&nbsp;&nbsp;</th>  
                            </tr>
                        </thead>';
                          $table .= '<tr>
                              
                              <td>';
                                $table .= $row22->po_no;
                              $table .= '</td>                               
                              <td>';  
                              $table .=  date('d-m-Y' , strtotime(addslashes($row22->po_date)));                           
                              $table .= '</td><td>'; 
                              $table .= $row22->company_name; 
                              $table .= '</td><td>';
                              $table .= $row22->item_remarks;
                              $table .= '</td><td>'; 
                              $table .=$row22->qty;
                              $table .= '</td></tr> <tr> '; 
                              $table .= '<td>';  $table .= '</td> <td>';  
                               $table .= '</td>
                              <td>';   $table .= '</td>
                              <td>';
                                $table .= 'Total';
                               $table .= '</td>
                              <td>'; 
                                $table .=$row22->qty;
                               $table .= '</td></tr>'; 

                               $mylist[$i] =$row22->po_no.','. date('d-m-Y' , strtotime(addslashes($row22->po_date))).','.$row22->company_name.','. $row22->item_remarks.','.$row22->qty; 

                              $i++; $mdoc_no = $row22->mdoc_no;
                        $table .= '</table>';
                        }  
                        //PDF Generate
                        $dompdf = new DOMPDF(); 
                        $dompdf->set_paper('A4', 'A4');
                        $dompdf->load_html($table);
                        $dompdf->render();
                        $font = Font_Metrics::get_font("helvetica", "bold");
                        $dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0)); 
                        $gen = $dompdf->output(); 
                        file_put_contents('../pdf/Purchase_Register_items.pdf', $gen);
                        //Mail Attach
                        $Filename = '../pdf/Purchase_Register_items.pdf';      
                        $gen = $dompdf->output(); 
                        file_put_contents('/home/admingcs/public_html/sales/adminpanel/mailattach/'.$Filename.'.pdf', $gen);
 
echo ($table); 
   }


//#################################################################
  //Store_Receipt_reports
//#################################################################

   function Store_Receipt_reports($clicked_id,$doc_type,$multiple_item_val,$main_group_val,$id_mst_attributes_department,$pia,$check_value,$datefilter,$party_val,$db){

      $mdoc_no = '';
        $date = explode(' to ', $datefilter); 
        $minimum_date = date('Y-m-d' , strtotime(addslashes($date[0])));
        $maximum_date = date('Y-m-d' , strtotime(addslashes($date[1]))); 
         
        $sql12 = " SELECT * FROM `".TBL_SHOP."` WHERE `id`='".addslashes($_SESSION['shop'])."' ";
        $db->query($sql12);   
          while($row12 = $db->fetch_object()){ 
            $companyname= $row12->name;  
          } 

        // Allocation Set Here
          if($minimum_date != '' && $maximum_date != ''){
              $datefilter =  "and inv_purch.po_date >='".$minimum_date."' and inv_purch.po_date <='".$maximum_date."' ";
          }else{
            $minimum_date = '2010-01-01';
            $maximum_date = date('Y-m-d');
            $datefilter ='';
          }
          //Department Based Search
          if($id_mst_attributes_department != '' ){
              $department =  "and inv_purch.id_mst_attributes_department ='".$id_mst_attributes_department."'  ";
          }else{ 
            $department =  '';
          }
          //Multiple Items
          $multiple_item_array = explode(',', $multiple_item_val);

          if($multiple_item_val != '' ){
              for($i=0;$i<count($multiple_item_array);$i++){
                $val=$multiple_item_array[$i];
                $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and name = '".$val."' ");   
                  while($row = $db->fetch_object2($resCat)){ 
                    $array[$i] = $row->id;
                  }
              }  
              $array = implode("','",$array); 
              $multiple =  "and inv_purch_details.id_inv_items IN ('".$array."') ";
          }else{ 
              $multiple =  '';
          } 
          //Main Group
          $main_group_array = explode(',', $main_group_val);
          if($main_group_val != '' ){
                //Attributes Table 
              for($i=0;$i<count($main_group_array);$i++){
                $val=$main_group_array[$i];
                $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_main' AND field_value = '".$val."' ");   
                  while($row = $db->fetch_object2($resCat)){ 
                    $array[$i] = $row->id;
                  }
              } 
                //Items Table
              $k=0;   
              for($i=0;$i<count($array);$i++){
                  $val=$array[$i];
                  $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_main = '".$val."' ");
                  while($row = $db->fetch_object2($resCat)){ 
                    $array_s[$k] = $row->id;
                    $k++;
                  } 
                } 
                $array = implode("','",$array_s); 
                $main_group = "and inv_purch_details.id_inv_items IN ('".$array."') ";
          }else{ 
              $main_group =  '';
          } 

          //Sub Group
          $sub_group_array = explode(',', $sub_group_val);
          if($sub_group_val != '' ){
                //Attributes Table 
              for($i=0;$i<count($sub_group_array);$i++){
                $val=$sub_group_array[$i];
                  $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_sub' AND field_value = '".$val."' ");   
                    while($row = $db->fetch_object2($resCat)){ 
                      $array[$i] = $row->id;
                    }
              }
                //Items Table
              $k=0;   
                for($i=0;$i<count($array);$i++){
                  $val=$array[$i];
                  $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_sub = '".$val."' ");           
                  while($row = $db->fetch_object2($resCat)){ 
                    $array_s[$k] = $row->id;
                    $k++;
                  } 
                }
                $array = implode("','",$array_s);  
                $sub_group = "and inv_purch_details.id_inv_items IN ('".$array."') ";
          }else{ 
              $sub_group =  '';
          } 
          //Party
          $party_val_array = explode(',', $party_val);
          if($party_val != '' ){
                //Attributes Table 
             $k=0; 
              for($i=0;$i<count($party_val_array);$i++){
                  $val=$party_val_array[$i];
                $resCat = selectSql(TBL_PARTY," where id_shop= '".addslashes($_SESSION['shop'])."'  AND company_name = '".$val."' ");   
                  while($row = $db->fetch_object2($resCat)){ 
                    $array_s[$k] = $row->id;
                    $k++;
                  }  
              } 
              $array = implode("','",$array_s); 
              $party = "and inv_purch.id_mst_party_supplier IN ('".$array."') ";  
          }else{ 
              $party =  '';
          } 
          $mylist = array();

        //Popup Table Show  
          $where = "mst_party.id=inv_purch.id_mst_party_supplier and inv_purch.id = inv_purch_details.id_inv_purch and inv_purch_details.id_inv_items = inv_items.id  and inv_purch.id = inv_others_charges_purch.id_inv_purch and inv_purch.id_shop = '".addslashes($_SESSION['shop'])."' and inv_purch.doc_type = '".$doc_type."' $datefilter  $multiple $main_group $sub_group $party" ;
         

         $sql = "SELECT inv_purch.*,  inv_purch_details.*,inv_others_charges_purch.*,
                        inv_items.item_code, inv_items.name, inv_items.conversion_qty, inv_items.id as item_id,  mst_party.company_name 
                        FROM inv_items, mst_party, inv_purch_details, inv_purch, inv_others_charges_purch WHERE $where";  
                        $db->query($sql);
                        $numRows= $db->num_rows(); 
                        //Table Section
                        $table = '<br><h4 style="padding-left:40%;">'.$companyname.'</h4><p  style="padding-left:27%;">GRN No Wise Store Receipt Note From '.date('d-m-Y' , strtotime(addslashes($minimum_date))). ' To '.date('d-m-Y' , strtotime(addslashes($maximum_date))).'</p>';
                        $i=1; 
                        $table .= '<table style="margin-top:1.5%;width:100%;" border="1" cellspacing="1"  cellpadding="1" > 
                        <thead>                     
                          <tr style="text-align: center; font-size: 10px;">
                              <th style="width: 10%;">Doc No</th> 
                              <th style="width: 10%;">Date</th> 
                              <th style="width: 10%;">Supplier</th> 
                              <th  style="width: 10%;">Bill No</th> 
                              <th  style="width: 15%;">Date</th> 
                              <th  style="width: 10%;">Item Code</th> 
                              <th style="width: 10%;">Item Descriptions</th> 
                              <th style="width: 10%;">Qty</th> 
                              <th style="width: 10%;">Rate</th> 
                              <th style="width: 10%;">Value</th> 
                              <th style="width: 10%;">Disc</th> 
                              <th style="width: 10%;">Service Tax</th> 
                              <th style="width: 10%;">Cess</th> 
                              <th style="width: 10%;">HCess</th> 
                              <th style="width: 10%;">TaxA/C</th> 
                              <th style="width: 10%;">Sales Tax</th> 
                              <th style="width: 10%;">Sreg</th> 
                              <th style="width: 10%;">Other Charges</th> 
                              <th style="width: 10%;">Amount</th> 
                              <th style="width: 10%;">Net Amount</th> 
                          </tr>
                      </thead>';
                        while($row22 = $db->fetch_object()){ 
                          //SGST 
                         $sgst =  selectColumn(TBL_CHARGES,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row22->id_mst_charges_sgst)."'");
                         $cgst =  selectColumn(TBL_CHARGES,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row22->id_mst_charges_cgst)."'");
                         $igst =  selectColumn(TBL_CHARGES,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row22->id_mst_charges_igst)."'");
                          $table .= '<tr style="font-size:10px;"> <td>';
                                if($mdoc_no != $row22->mdoc_no){
                                $table .= $row22->po_no;
                              }
                             $table .= '</td>
                               
                              <td>';
                              if($mdoc_no != $row22->mdoc_no){
                                $table .=  date('d-m-Y' , strtotime(addslashes($row22->po_date)));
                               }
                              $table .= '</td><td>';                                 
                              $table .= $row22->company_name;
                              $table .= '</td><td>';
                              $table .= '</td><td>';
                              $table .= '</td><td>';
                              $table .= $row22->item_code;
                              $table .= '</td> <td>'; 
                              $table .= $row22->name;
                              $table .= '</td><td>';
                              $table .= $row22->qty; 
                              $table .= '</td> <td>';
                              $table .= $row22->rate_per_main_unit;
                              $table .= '</td><td>'; 
                              $table .=  $row22->qty * $row22->rate_per_main_unit;
                              $table .= '</td><td>'; 
                              $table .=  $row22->discount_amount;
                              $table .= '</td><td>';
                              $table .= '</td><td>';
                              $table .= '</td><td>'; 
                              $table .= '</td><td>'; 
                              $table .=  $sgst.','.$cgst.','.$igst;
                              $table .= '</td><td>'; 
                              $table .= '</td><td>'; 
                              $table .= '</td><td>'; 
                              if($mdoc_no != $row22->mdoc_no){
                                $table .=  $row22->others_charges_amount;
                              }
                              $table .= '</td><td>'; 
                              $table .=  $row22->qty * $row22->rate_per_main_unit;
                              $table .= '</td><td>'; 
                              if($mdoc_no != $row22->mdoc_no){
                                $table .=  $row22->net_amount_items;
                              }
                              $table .= '</td>'; 

                              $mylist[$i] =$row22->po_no.','.date('d-m-Y' , strtotime(addslashes($row22->po_date))).','.$row22->company_name.','.$row22->item_code.','. $row22->name.','.$row22->item_code.','.$row22->qty * $row22->rate_per_main_unit.','.$row22->qty * $row22->rate_per_main_unit.','.$row22->discount_amount.','.$sgst.','.$cgst.','.$igst.','.$row22->others_charges_amount; 

                              $i++; $mdoc_no = $row22->mdoc_no;
                        } 
                        $table .= '</table>'; 
                        //PDF Generate
                        $dompdf = new DOMPDF(); 
                        $dompdf->set_paper('landscape', 'landscape');
                        $dompdf->load_html($table);
                        $dompdf->render();
                        $font = Font_Metrics::get_font("helvetica", "bold");
                        $dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0)); 
                        $gen = $dompdf->output(); 
                        file_put_contents('../pdf/Store_Receipt_note.pdf', $gen);
                        //Mail Attach
                        $Filename = '../pdf/Store_Receipt_note.pdf';      
                        $gen = $dompdf->output(); 
                        file_put_contents('/home/admingcs/public_html/sales/adminpanel/mailattach/'.$Filename.'.pdf', $gen);
 
echo ($table); 


   }

//#################################################################
   //Store_Receipt_reports_Party_item_reports
//#################################################################

   function Store_Receipt_reports_Party_item_reports($clicked_id,$doc_type,$multiple_item_val,$main_group_val,$id_mst_attributes_department,$pia,$check_value,$datefilter,$party_val,$db){

      $mdoc_no = '';
    $date = explode(' to ', $datefilter); 
    $minimum_date = date('Y-m-d' , strtotime(addslashes($date[0])));
    $maximum_date = date('Y-m-d' , strtotime(addslashes($date[1]))); 
     
    $sql12 = " SELECT * FROM `".TBL_SHOP."` WHERE `id`='".addslashes($_SESSION['shop'])."' ";
    $db->query($sql12);   
      while($row12 = $db->fetch_object()){ 
        $companyname= $row12->name;  
      } 

    // Allocation Set Here
      if($minimum_date != '' && $maximum_date != ''){
          $datefilter =  "and inv_purch.po_date >='".$minimum_date."' and inv_purch.po_date <='".$maximum_date."' ";
      }else{
        $minimum_date = '2010-01-01';
        $maximum_date = date('Y-m-d');
        $datefilter ='';
      }
      //Department Based Search
      if($id_mst_attributes_department != '' ){
          $department =  "and inv_purch.id_mst_attributes_department ='".$id_mst_attributes_department."'  ";
      }else{ 
        $department =  '';
      }
      //Multiple Items
      $multiple_item_array = explode(',', $multiple_item_val);

      if($multiple_item_val != '' ){
          for($i=0;$i<count($multiple_item_array);$i++){
            $val=$multiple_item_array[$i];
            $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and name = '".$val."' ");   
              while($row = $db->fetch_object2($resCat)){ 
                $array[$i] = $row->id;
              }
          }  
          $array = implode("','",$array); 
          $multiple =  "and inv_purch_details.id_inv_items IN ('".$array."') ";
      }else{ 
          $multiple =  '';
      } 
      //Main Group
      $main_group_array = explode(',', $main_group_val);
      if($main_group_val != '' ){
            //Attributes Table 
          for($i=0;$i<count($main_group_array);$i++){
            $val=$main_group_array[$i];
            $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_main' AND field_value = '".$val."' ");   
              while($row = $db->fetch_object2($resCat)){ 
                $array[$i] = $row->id;
              }
          } 
            //Items Table
          $k=0;   
          for($i=0;$i<count($array);$i++){
              $val=$array[$i];
              $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_main = '".$val."' ");
              while($row = $db->fetch_object2($resCat)){ 
                $array_s[$k] = $row->id;
                $k++;
              } 
            } 
            $array = implode("','",$array_s);   
            $main_group = "and inv_purch_details.id_inv_items IN ('".$array."') ";
      }else{ 
          $main_group =  '';
      } 

      //Sub Group
      $sub_group_array = explode(',', $sub_group_val);
      if($sub_group_val != '' ){
            //Attributes Table 
          for($i=0;$i<count($sub_group_array);$i++){
            $val=$sub_group_array[$i];
              $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_sub' AND field_value = '".$val."' ");   
                while($row = $db->fetch_object2($resCat)){ 
                  $array[$i] = $row->id;
                }
          }
            //Items Table
          $k=0;   
            for($i=0;$i<count($array);$i++){
              $val=$array[$i];
              $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_sub = '".$val."' ");           
              while($row = $db->fetch_object2($resCat)){ 
                $array_s[$k] = $row->id;
                $k++;
              } 
            }
            $array = implode("','",$array_s);  
            $sub_group = "and inv_purch_details.id_inv_items IN ('".$array."') ";
      }else{ 
          $sub_group =  '';
      } 
      //Party
      $party_val_array = explode(',', $party_val);
      if($party_val != '' ){
            //Attributes Table 
         $k=0; 
          for($i=0;$i<count($party_val_array);$i++){
              $val=$party_val_array[$i];
            $resCat = selectSql(TBL_PARTY," where id_shop= '".addslashes($_SESSION['shop'])."'  AND company_name = '".$val."' ");   
              while($row = $db->fetch_object2($resCat)){ 
                $array_s[$k] = $row->id;
                $k++;
              }  
          } 
          $array = implode("','",$array_s); 
          $party = "and inv_purch.id_mst_party_supplier IN ('".$array."') ";  
      }else{ 
          $party =  '';
      } 
      $mylist = array(); 

    //Popup Table Show  
      $where = "mst_party.id=inv_purch.id_mst_party_supplier and inv_purch.id = inv_purch_details.id_inv_purch and inv_purch_details.id_inv_items = inv_items.id  and inv_purch.id_shop = '".addslashes($_SESSION['shop'])."' and inv_purch.doc_type = '".$doc_type."' $datefilter  $multiple $main_group $sub_group $party" ;
     

     $sql = "SELECT inv_purch.po_date, inv_purch.mdoc_no,  inv_purch.po_no, 
                        inv_purch_details.qty,inv_purch_details.alt_qty, inv_purch_details.id,inv_purch_details.id_inv_purch, inv_purch_details.main_unit, inv_purch_details.alt_unit, inv_purch_details.rate_per_main_unit, inv_purch_details.item_remarks,
                        inv_items.item_code, inv_items.name, inv_items.conversion_qty, inv_items.id as item_id,  mst_party.company_name 
                        FROM inv_items, mst_party, inv_purch_details, inv_purch WHERE $where"; 
                        $db->query($sql);
                        $numRows= $db->num_rows(); 
                        //Table Section
                        $table = '<br><h4 style="padding-left:38%;">'.$companyname.'</h4><p  style="padding-left:21%;">GRN No Wise Store Receipt Note From '.date('d-m-Y' , strtotime(addslashes($minimum_date))). ' To '.date('d-m-Y' , strtotime(addslashes($maximum_date))).'</p>';
                        $i=1; 
                        
                        while($row22 = $db->fetch_object()){
 
                          $table .= '
                          <p style="margin-left:1.5%;" ><b>Item Code:</b> '.$row22->item_code.', <b>Item Descriptions: </b>'.$row22->name.'</p> 
                          <table style="margin-top:1.5%;width:100%;" border="1" cellspacing="1"  cellpadding="1" >';
                          
                          $table .= '
                          <thead>                     
                            <tr style="text-align: center; font-size: 14px;">
                                <th style="width: 10%;">PO No</th> 
                                <th style="width: 10%;">PO Date</th> 
                                <th style="width: 10%;">Supplier</th> 
                                <th style="width: 10%;">Remarks</th> 
                                <th style="width: 15%;">PO Qty</th> 
                                <th style="width: 10%;">Issued Qty</th> 
                                <th style="width: 10%;">Adjust</th> 
                                <th style="width: 10%;">Balance</th> 
                            </tr>
                        </thead>';
                          $table .= '<tr><td>';
                             
                                $table .= $row22->mdoc_no;
                              
                             $table .= '</td>
                               
                              <td>';  
                               $table .= date('d-m-Y' , strtotime(addslashes($row22->po_date)));
                               
                             $table .= '</td>
                              <td>'; 
                               $table .= $row22->company_name; 
                             $table .= '</td>
                              <td>';
                                $table .= $row22->item_remarks;
                               $table .= '</td>
                              <td>'; 
                                $table .=$row22->qty;
                               $table .= '</td>
                              <td>';
                                 $table .=$row22->ordered_qty; 
                                $table .= '</td>
                              <td>';
                                $table .= '';
                               $table .= '</td>
                               <td>';
                                $table .= $row22->bal_qty;
                               $table .= '</td>
                               </tr> <tr> '; 
                             $table .= '<td>';  $table .= '</td> <td>';  
                               $table .= '</td>
                              <td>';   $table .= '</td>
                              <td>';
                                $table .= 'Total';
                               $table .= '</td>
                              <td>'; 
                                $table .=$row22->qty;
                               $table .= '</td>
                              <td>';
                                 $table .=$row22->ordered_qty; 
                                $table .= '</td>
                              <td>';
                                $table .= '';
                               $table .= '</td>
                               <td>';
                                $table .= $row22->bal_qty;
                               $table .= '</td>';

                               $mylist[$i] =$row22->mdoc_no.','.date('d-m-Y' , strtotime(addslashes($row22->po_date))).','. $row22->company_name.','.$row22->item_remarks.','.$row22->qty.','.$row22->ordered_qty.','.$row22->bal_qty; 


                              $i++; $mdoc_no = $row22->mdoc_no;
                        $table .= '</table>';
                        }  
                        //PDF Generate
                        $dompdf = new DOMPDF(); 
                        $dompdf->set_paper('A4', 'A4');
                        $dompdf->load_html($table);
                        $dompdf->render();
                        $font = Font_Metrics::get_font("helvetica", "bold");
                        $dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0)); 
                        $gen = $dompdf->output(); 
                        file_put_contents('../pdf/Store_Receipt_note_party_items.pdf', $gen);
                        //Mail Attach
                        $Filename = '../pdf/Store_Receipt_note_party_items.pdf';      
                        $gen = $dompdf->output(); 
                        file_put_contents('/home/admingcs/public_html/sales/adminpanel/mailattach/'.$Filename.'.pdf', $gen);
 
echo ($table); 

   }

//#################################################################
   //Store_Issue_note_reports
//#################################################################

   function Store_Issue_note_reports($clicked_id,$doc_type,$multiple_item_val,$main_group_val,$id_mst_attributes_department,$pia,$check_value,$datefilter,$party_val,$db){

    $mdoc_no = '';
      $date = explode(' to ', $datefilter); 
      $minimum_date = date('Y-m-d' , strtotime(addslashes($date[0])));
      $maximum_date = date('Y-m-d' , strtotime(addslashes($date[1])));
       
      $sql12 = " SELECT * FROM `".TBL_SHOP."` WHERE `id`='".addslashes($_SESSION['shop'])."' ";
      $db->query($sql12);   
        while($row12 = $db->fetch_object()){ 
          $companyname= $row12->name;  
        } 

      // Allocation Set Here
        if($minimum_date != '' && $maximum_date != ''){
            $datefilter =  "and inv_purch.po_date >='".$minimum_date."' and inv_purch.po_date <='".$maximum_date."' ";
        }else{
          $minimum_date = '2010-01-01';
          $maximum_date = date('Y-m-d');
          $datefilter ='';
        }
        //Department Based Search
        if($id_mst_attributes_department != '' ){
            $department =  "and inv_purch.id_mst_attributes_department ='".$id_mst_attributes_department."'  ";
        }else{ 
          $department =  '';
        }
        //Multiple Items
        $multiple_item_array = explode(',', $multiple_item_val);

        if($multiple_item_val != '' ){
            for($i=0;$i<count($multiple_item_array);$i++){
              $val=$multiple_item_array[$i];
              $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and name = '".$val."' ");   
                while($row = $db->fetch_object2($resCat)){ 
                  $array[$i] = $row->id;
                }
            }  
            $array = implode("','",$array); 
            $multiple =  "and inv_purch_details.id_inv_items IN ('".$array."') ";
        }else{ 
            $multiple =  '';
        } 
        //Main Group
         $main_group_array = explode(',', $main_group_val);
        if($main_group_val != '' ){
              //Attributes Table 
            for($i=0;$i<count($main_group_array);$i++){
              $val=$main_group_array[$i];
              $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_main' AND field_value = '".$val."' ");   
                while($row = $db->fetch_object2($resCat)){ 
                  $array[$i] = $row->id;
                }
            } 
              //Items Table
            $k=0;   
            for($i=0;$i<count($array);$i++){
                $val=$array[$i];
                $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_main = '".$val."' ");
                while($row = $db->fetch_object2($resCat)){ 
                  $array_s[$k] = $row->id;
                  $k++;
                } 
              } 
              $array = implode("','",$array_s); 
              $main_group = "and inv_purch_details.id_inv_items IN ('".$array."') ";
        }else{ 
            $main_group =  '';
        } 

        //Sub Group
        $sub_group_array = explode(',', $sub_group_val);
        if($sub_group_val != '' ){
              //Attributes Table 
            for($i=0;$i<count($sub_group_array);$i++){
              $val=$sub_group_array[$i];
                $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_sub' AND field_value = '".$val."' ");   
                  while($row = $db->fetch_object2($resCat)){ 
                    $array[$i] = $row->id;
                  }
            }
              //Items Table
            $k=0;   
              for($i=0;$i<count($array);$i++){
                $val=$array[$i];
                $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_sub = '".$val."' ");           
                while($row = $db->fetch_object2($resCat)){ 
                  $array_s[$k] = $row->id;
                  $k++;
                } 
              }
              $array = implode("','",$array_s);   
              $sub_group = "and inv_purch_details.id_inv_items IN ('".$array."') ";
        }else{ 
            $sub_group =  '';
        } 
      $mylist = array();
      //Popup Table Show  
        $where = "mst_attributes.id=inv_purch.id_mst_attributes_department and inv_purch.id = inv_purch_details.id_inv_purch and inv_purch_details.id_inv_items = inv_items.id  and inv_purch.id_shop = '".addslashes($_SESSION['shop'])."' and inv_purch.doc_type = '".$doc_type."' $datefilter  $department  $multiple $main_group $sub_group" ;
       

       $sql = "SELECT inv_purch.po_date, inv_purch.mdoc_no,  inv_purch.po_no, 
                        inv_purch_details.qty,inv_purch_details.alt_qty, inv_purch_details.id,inv_purch_details.id_inv_purch, inv_purch_details.main_unit, inv_purch_details.alt_unit, 
                        inv_items.item_code, inv_items.name, inv_items.conversion_qty, inv_items.id as item_id, 
                        mst_attributes.field_value 
                        FROM inv_items, mst_attributes, inv_purch_details, inv_purch WHERE $where"; 
                        $db->query($sql);
                        $numRows= $db->num_rows(); 
                        //Table Section
                        $table = '<br><h4 style="padding-left:38%;">'.$companyname.'</h4><p  style="padding-left:20%;">SIN No Wise Store Issue Note From '.date('d-m-Y' , strtotime(addslashes($minimum_date))). ' To '.date('d-m-Y' , strtotime(addslashes($maximum_date))).'</p>';
                        $i=1; 
                        $table .= '<table style="margin-top:1.5%;width:100%;" border="1" cellspacing="1"  cellpadding="1" > 
                        <thead>                     
                          <tr style="text-align: center; font-size: 14px;">
                              <th style="width: 10%;">SIN No</th> 
                              <th style="width: 10%;">Date</th> 
                              <th style="width: 10%;">Department</th> 
                              <th  style="width: 10%;">Item Code</th> 
                              <th  style="width: 15%;">Description</th> 
                              <th  style="width: 10%;">Qty</th> 
                              <th  style="width: 10%;">Rate</th> 
                              <th style="width: 10%;">Value</th> 
                          </tr>
                      </thead>';
                        while($row22 = $db->fetch_object()){ 
                          $table .= '<tr>
                              
                              <td>';
                                if($mdoc_no != $row22->mdoc_no){
                                $table .= $row22->mdoc_no;
                              }
                             $table .= '</td>
                               
                              <td>';
                              if($mdoc_no != $row22->mdoc_no){
                                 
                             $table .= date('d-m-Y' , strtotime(addslashes($row22->po_date)));
                               }
                             $table .= '</td>
                              <td>';
                              if($mdoc_no != $row22->mdoc_no){
                                $table .= $row22->field_value;
                              }
                             $table .= '</td>
                              <td>';
                                $table .= $row22->item_code;
                               $table .= '</td>
                              <td>'; 
                                $table .=$row22->name;
                               $table .= '</td>
                              <td>';
                                 $table .=$row22->qty; 
                                $table .= '</td>
                              <td>';
                                 $table .= 0; 
                                $table .= '</td>
                              <td>';
                                $table .= 0;
                               $table .= '</td>
                               </tr>  '; 

                              $mylist[$i] =$row22->mdoc_no.','.date('d-m-Y' , strtotime(addslashes($row22->po_date))).','. $row22->field_value.','.$row22->item_code.','.$row22->name.','.$row22->qty; 

                              $i++; $mdoc_no = $row22->mdoc_no;
                        } 
                        $table .= '</table>'; 

                         //PDF Generate
                          $dompdf = new DOMPDF(); 
                          $dompdf->set_paper('A4', 'A4');
                          $dompdf->load_html($table);
                          $dompdf->render();
                          $font = Font_Metrics::get_font("helvetica", "bold");
                          $dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0)); 
                          $gen = $dompdf->output(); 
                          file_put_contents('../pdf/Store_Issue_Note.pdf', $gen);
                          //Mail Attach
                          $Filename = '../pdf/Store_Issue_Note.pdf';      
                          $gen = $dompdf->output(); 
                          file_put_contents('/home/admingcs/public_html/sales/adminpanel/mailattach/'.$Filename.'.pdf', $gen);
 
echo ($table); 
   }

//#################################################################
   //Store_Issue_note_Party_item_reports
//#################################################################
   function Store_Issue_note_Party_item_reports($clicked_id,$doc_type,$multiple_item_val,$main_group_val,$id_mst_attributes_department,$pia,$check_value,$datefilter,$party_val,$db){

      $mdoc_no = '';
        $date = explode(' to ', $datefilter); 
        $minimum_date = date('Y-m-d' , strtotime(addslashes($date[0])));
        $maximum_date = date('Y-m-d' , strtotime(addslashes($date[1])));
         
        $sql12 = " SELECT * FROM `".TBL_SHOP."` WHERE `id`='".addslashes($_SESSION['shop'])."' ";
        $db->query($sql12);   
          while($row12 = $db->fetch_object()){ 
            $companyname= $row12->name;  
          } 

        // Allocation Set Here
          if($minimum_date != '' && $maximum_date != ''){
              $datefilter =  "and inv_purch.po_date >='".$minimum_date."' and inv_purch.po_date <='".$maximum_date."' ";
          }else{
            $minimum_date = '2010-01-01';
            $maximum_date = date('Y-m-d');
            $datefilter ='';
          }
          //Department Based Search
          if($id_mst_attributes_department != '' ){
              $department =  "and inv_purch.id_mst_attributes_department ='".$id_mst_attributes_department."'  ";
          }else{ 
            $department =  '';
          }
          //Multiple Items
          $multiple_item_array = explode(',', $multiple_item_val);

          if($multiple_item_val != '' ){
              for($i=0;$i<count($multiple_item_array);$i++){
                $val=$multiple_item_array[$i];
                $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and name = '".$val."' ");   
                  while($row = $db->fetch_object2($resCat)){ 
                    $array[$i] = $row->id;
                  }
              }  
              $array = implode("','",$array); 
              $multiple =  "and inv_purch_details.id_inv_items IN ('".$array."') ";
          }else{ 
              $multiple =  '';
          } 
          //Main Group
           $main_group_array = explode(',', $main_group_val);
          if($main_group_val != '' ){
                //Attributes Table 
              for($i=0;$i<count($main_group_array);$i++){
                $val=$main_group_array[$i];
                $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_main' AND field_value = '".$val."' ");   
                  while($row = $db->fetch_object2($resCat)){ 
                    $array[$i] = $row->id;
                  }
              } 
                //Items Table
              $k=0;   
              for($i=0;$i<count($array);$i++){
                  $val=$array[$i];
                  $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_main = '".$val."' ");
                  while($row = $db->fetch_object2($resCat)){ 
                    $array_s[$k] = $row->id;
                    $k++;
                  } 
                } 
                $array = implode("','",$array_s);   
                $main_group = "and inv_purch_details.id_inv_items IN ('".$array."') ";
          }else{ 
              $main_group =  '';
          } 

          //Sub Group
          $sub_group_array = explode(',', $sub_group_val);
          if($sub_group_val != '' ){
                //Attributes Table 
              for($i=0;$i<count($sub_group_array);$i++){
                $val=$sub_group_array[$i];
                  $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_sub' AND field_value = '".$val."' ");   
                    while($row = $db->fetch_object2($resCat)){ 
                      $array[$i] = $row->id;
                    }
              }
                //Items Table
              $k=0;   
                for($i=0;$i<count($array);$i++){
                  $val=$array[$i];
                  $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_sub = '".$val."' ");           
                  while($row = $db->fetch_object2($resCat)){ 
                    $array_s[$k] = $row->id;
                    $k++;
                  } 
                }
                $array = implode("','",$array_s);   
                $sub_group = "and inv_purch_details.id_inv_items IN ('".$array."') ";
          }else{ 
              $sub_group =  '';
          } 

        //Popup Table Show  
          $where = "mst_attributes.id=inv_purch.id_mst_attributes_department and inv_purch.id = inv_purch_details.id_inv_purch and inv_purch_details.id_inv_items = inv_items.id  and inv_purch.id_shop = '".addslashes($_SESSION['shop'])."' and inv_purch.doc_type = '".$doc_type."' $datefilter  $department  $multiple $main_group $sub_group" ;
         

         $sql = "SELECT inv_purch.po_date, inv_purch.mdoc_no,  inv_purch.po_no, 
                        inv_purch_details.qty,inv_purch_details.alt_qty, inv_purch_details.id,inv_purch_details.id_inv_purch, inv_purch_details.main_unit, inv_purch_details.alt_unit, 
                        inv_items.item_code, inv_items.name, inv_items.conversion_qty, inv_items.id as item_id, 
                        mst_attributes.field_value 
                        FROM inv_items, mst_attributes, inv_purch_details, inv_purch WHERE $where"; 
                        $db->query($sql);
                        $numRows= $db->num_rows(); 
                        //Table Section
                        $table = '<br><h4 style="padding-left:38%;">'.$companyname.'</h4><p  style="padding-left:21%;">Product Wise Store Issue Note From '.date('d-m-Y' , strtotime(addslashes($minimum_date))). ' To '.date('d-m-Y' , strtotime(addslashes($maximum_date))).'</p>';
                        $i=1; 
                        
                        while($row22 = $db->fetch_object()){
 
                          $table .= '
                          <p style="margin-left:1.5%;" ><b>Item Code:</b> '.$row22->item_code.', <b>Item Descriptions: </b>'.$row22->name.'</p> 
                          <table style="margin-top:1.5%;width:100%;" border="1" cellspacing="1"  cellpadding="1" >';
                          
                          $table .= '
                          <thead>                     
                            <tr style="text-align: center; font-size: 14px; padding-top:20px;">
                                <th style="width: 10%;">Indent No</th> 
                                <th style="width: 10%;">Date</th> 
                                <th style="width: 10%;">Department</th> 
                                <th style="width: 10%;">Remarks</th> 
                                <th style="width: 15%;">Qty</th>   
                            </tr>
                        </thead>';
                          $table .= '<tr>
                              
                              <td>';
                             
                                $table .= $row22->mdoc_no;
                              
                             $table .= '</td>
                               
                              <td>';  
                               $table .= date('d-m-Y' , strtotime(addslashes($row22->po_date)));
                               
                             $table .= '</td>
                              <td>'; 
                               $table .= $row22->field_value; 
                             $table .= '</td>
                              <td>';
                                $table .= '';
                               $table .= '</td>
                              <td>'; 
                                $table .=$row22->qty;
                               $table .= '</td> 
                               </tr> <tr> '; 
                               $table .= '<td></td>';  
                               $table .= '<td></td>';  
                               $table .= '<td></td>';
                               $table .= '<td>Total</td>';
                               $table .= '<td>'; 
                                $table .=$row22->qty;
                               $table .= '</td>';

                               $mylist[$i] =$row22->mdoc_no.','.date('d-m-Y' , strtotime(addslashes($row22->po_date))).','. $row22->field_value.','.$row22->qty;

                              $i++; $mdoc_no = $row22->mdoc_no;
                        $table .= '</tr></table>';
                        }  
                       //PDF Generate
                        $dompdf = new DOMPDF(); 
                        $dompdf->set_paper('A4', 'A4');
                        $dompdf->load_html($table);
                        $dompdf->render();
                        $font = Font_Metrics::get_font("helvetica", "bold");
                        $dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0)); 
                        $gen = $dompdf->output(); 
                        file_put_contents('../pdf/Store_Issue_Note_party_items.pdf', $gen);
                        //Mail Attach
                        $Filename = '../pdf/Store_Issue_Note_party_items.pdf';      
                        $gen = $dompdf->output(); 
                        file_put_contents('/home/admingcs/public_html/sales/adminpanel/mailattach/'.$Filename.'.pdf', $gen);
 
echo ($table); 

   }

?>  