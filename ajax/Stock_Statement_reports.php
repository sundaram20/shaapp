<?php include_once("../config/auto_loader.php"); 

$clicked_id = $_POST["clicked_id"];
$doc_type = $_POST["doc_type"];
$multiple_item_val = $_POST["multiple_item_val"];
$main_group_val = $_POST["main_group_val"];
$sub_group_val = $_POST["sub_group_val"];
$id_mst_attributes_department = $_POST["id_mst_attributes_department"];
$pia = $_POST["pia"];
$check_value = $_POST["check_value"];
$datefilter = $_POST["datefilter"]; 

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
// Allocation Set Here
  // if($minimum_date != '' && $maximum_date != ''){
  //     $datefilter =  "and inv_items.indent_date >='".$minimum_date."' and inv_items.indent_date <='".$maximum_date."' ";
  // }else{
  //   $minimum_date = '2010-01-01';
  //   $maximum_date = date('Y-m-d');
  //   $datefilter ='';
  // }
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
                        $table = '<br><h4 style="padding-left:38%;">'.$companyname.'</h4><p  style="padding-left:30%;"><b>Stock Statement</b> From '.date('d-m-Y' , strtotime(addslashes($minimum_date))). ' To '.date('d-m-Y' , strtotime(addslashes($maximum_date))).'</p>';
                         
                         $table .= ' 
                          <table style="margin-top:1.5%;margin-right:1.5%;margin-left:1.5%;" border="1" cellspacing="1"  cellpadding="1" >';
                          
                          $table .= '
                          <thead>                     
                            <tr style="text-align: center; font-size: 14px; padding-top:20px;">
                                <th style="width: 10%;">Item Code</th> 
                                <th style="width: 10%;">Item Description</th> 
                                <th style="width: 10%;">Rate</th> 
                                <th style="width: 10%;"  colspan="2">Receipt</th> 
                                <th style="width: 10%;"  colspan="2">Issue</th> 
                                <th style="width: 10%;"  colspan="2">Balance</th> 
                            </tr>
                            <tr>
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


                          $table .= '<tr><td>'; 
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
                        ob_start();
                        $body = ob_get_clean();
                        $body = iconv("UTF-8", "UTF-8//IGNORE", $body);
                        include_once('../phplib/mpdf/mpdf.php');
                        $mpdf = new mPDF('c','A4','','' , 0 , 0 , 0 , 0 , 0 , 0); 
                        $mpdf->WriteHTML($table);
                        $mpdf->Output('../pdf/Stock_Statement.pdf','F');
                      
                        //Excel Sheet Work 
                         $file_pointer = fopen("../excel/Stock_Statement.csv","w");
                         foreach ($mylist as $line) {
                            fputcsv($file_pointer,explode(',',$line));
                         }
                         fclose($file_pointer);
echo ($table);  
if($clicked_id == 'getdataexcel'){
?> 
<a href="../excel/Stock_Statement.csv"><button class="btn btn-danger btn-sm btn-labeled" id="download" type="button">
    <span class="btn-label">
      <span class="icon icon-download icon-lg icon-fw"></span>
    </span>
    Download CSV
  </button></a>

  <script type="text/javascript">
    document.getElementById('download').click();
  </script>
<?php } ?>