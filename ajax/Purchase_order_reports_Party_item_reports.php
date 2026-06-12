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
                          <table style="margin-top:1.5%;margin-right:1.5%;margin-left:1.5%;" border="1" cellspacing="1"  cellpadding="1" >';
                          
                          $table .= '
                          <thead>                     
                            <tr style="text-align: center; font-size: 14px; padding-top:20px;">
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
                        ob_start();
                        $body = ob_get_clean();
                        $body = iconv("UTF-8", "UTF-8//IGNORE", $body);
                        include_once('../phplib/mpdf/mpdf.php');
                        $mpdf = new mPDF('c','A4','','' , 0 , 0 , 0 , 0 , 0 , 0); 
                        $mpdf->WriteHTML($table);
                        $mpdf->Output('../pdf/Purchase_Order_party_items.pdf','F');
                         //Excel Sheet Work 
                         $file_pointer = fopen("../excel/Purchase_Order_party_items.csv","w");
                         foreach ($mylist as $line) {
                            fputcsv($file_pointer,explode(',',$line));
                         }
                         fclose($file_pointer);
 
echo ($table); 
if($clicked_id == 'getdataexcel'){
?>

 
<a href="../excel/Purchase_Order_party_items.csv"><button class="btn btn-danger btn-sm btn-labeled" id="download" type="button">
    <span class="btn-label">
      <span class="icon icon-download icon-lg icon-fw"></span>
    </span>
    Download CSV
  </button></a>

  <script type="text/javascript">
    document.getElementById('download').click();
  </script>
<?php } ?>