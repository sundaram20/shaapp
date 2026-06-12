<?php include_once("../config/auto_loader.php");

$doc_type = $_POST["doc_type"];
$multiple_item_val = $_POST["multiple_item_val"];
$main_group_val = $_POST["main_group_val"];
$sub_group_val = $_POST["sub_group_val"];
$id_mst_attributes_department = $_POST["id_mst_attributes_department"];
$pia = $_POST["pia"];
$check_value = $_POST["check_value"];
$datefilter = $_POST["datefilter"]; 

$mdoc_no = '';
$date = explode('/', $datefilter); 
$minimum_date = $date[0];
$maximum_date = $date[1]; 
 
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
  if($main_group_val != '' ){
        //Attributes Table 
        $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_main' AND field_value = '".$main_group_val."' ");   
          while($row = $db->fetch_object2($resCat)){ 
            $id = $row->id;
          }
        //Items Table
        $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_main = '".$id."' ");
        $i=0;   
        while($row = $db->fetch_object2($resCat)){ 
          $array[$i] = $row->id;
          $i++;
        } 
        $array = implode("','",$array);   
        $main_group = "and inv_indent_details.id_inv_items IN ('".$array."') ";
  }else{ 
      $main_group =  '';
  } 

  //Sub Group
  if($sub_group_val != '' ){
        //Attributes Table 
        $resCat = selectSql(TBL_ATTRIBUTES," where id_shop= '".addslashes($_SESSION['shop'])."' and table_name = 'item_group_sub' AND field_value = '".$sub_group_val."' ");   
          while($row = $db->fetch_object2($resCat)){ 
            $id = $row->id;
          }
        //Items Table
        $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and id_mst_attributes_group_sub = '".$id."' ");
        $i=0;   
        while($row = $db->fetch_object2($resCat)){ 
          $array[$i] = $row->id;
          $i++;
        } 
        $array = implode("','",$array);   
        $sub_group = "and inv_indent_details.id_inv_items IN ('".$array."') ";
  }else{ 
      $sub_group =  '';
  } 

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
                          <p style="margin-left:1.5%;" ><b>Item Code:</b> '.$row22->item_code.', <b>Item Descriptions: </b>'.$row22->name.'</p> 
                          <table style="margin-top:1.5%;margin-right:1.5%;margin-left:1.5%;" border="1" cellspacing="1"  cellpadding="1" >';
                          
                          $table .= '
                          <thead>                     
                            <tr style="text-align: center; font-size: 14px; padding-top:20px;">
                                <th style="width: 10%;">Indent No</th> 
                                <th style="width: 10%;">Date</th> 
                                <th style="width: 10%;">Department</th> 
                                <th style="width: 10%;">Remarks</th> 
                                <th style="width: 15%;">Indent Qty</th> 
                                <th style="width: 10%;">Issued Qty</th> 
                                <th style="width: 10%;">Adjust</th> 
                                <th style="width: 10%;">Balance</th> 
                            </tr>
                        </thead>';
                          $table .= '<tr>
                              
                              <td>';
                             
                                $table .= $row22->mdoc_no;
                              
                             $table .= '</td>
                               
                              <td>';  
                               $table .= $row22->indent_date;
                               
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

                              $i++; $mdoc_no = $row22->mdoc_no;
                        $table .= '</table>';
                        } 
                        ob_start();
                        $body = ob_get_clean();
                        $body = iconv("UTF-8", "UTF-8//IGNORE", $body);
                        include_once('../phplib/mpdf/mpdf.php');
                        $mpdf = new mPDF('c','A4','','' , 0 , 0 , 0 , 0 , 0 , 0); 
                        $mpdf->WriteHTML($table);
                        $mpdf->Output('../pdf/Stock_Balance_items.pdf','F');
                        
 
echo ($table); 
?>
 
