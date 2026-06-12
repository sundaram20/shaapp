<?php include_once("../config/auto_loader.php");

$multiple_item_val = $_POST["multiple_item_val"];
$main_group_val = $_POST["main_group_val"];
$sub_group_val = $_POST["sub_group_val"];
$party_val = $_POST["party_val"];
$clicked_id = $_POST["clicked_id"];

 

//Popup Table Show
 
 if($multiple_item_val){

	$sql ="SELECT * FROM inv_items";
   	$db->query($sql);
   	$numRows= $db->num_rows();

   	//Table Section
    
   	$table =  '';
   	$i=1;
   	$table = '<thead id="thead">					        	 	
            <tr style="font-size: 14px;">
                <th style="width: 18%;text-align: center;">S.NO</th>    
                <th style="width: 32%;text-align: center;">ITEM CODE</th>  
                <th style="width: 41%;text-align: center">ITEM DESCRIPTIONS</th>  
                <th style="text-align: center">SELECT</th> 
            </tr>
        </thead><tbody id="tbody">';

   	while($row22 = $db->fetch_object()){  

   		$table .= '<tr class="table-row" style="text-align: center;">
        		<td style="width: 18%;"">
        			<input type="text" class="form-control" id="wsno" name="wsno"  value="'.$i.'" readonly>
        		</td>  
            <td style="width: 32%;">';
              $table .= '<span>'.$row22->item_code.'</span>';
            $table .= '</td> 
        		<td style="width: 41%;">'; 
            		$table .= '<span>'.$row22->name.'</span>';
            		$table .= '<input type="text" class="form-control" id="witem_description'.$i.'" name="witem_description'.$i.'"  value="'.$row22->name.'" readonly style="display:none;"><input type="text" class="form-control" id="wmain_group'.$i.'" name="wmain_group'.$i.'"  value="" readonly style="display:none;"><input type="text" class="form-control" id="wsub_group'.$i.'" name="wsub_group'.$i.'"  value="" readonly style="display:none;"><input type="text" class="form-control" id="wparty'.$i.'" name="wparty'.$i.'"  value="" readonly style="display:none;">';
            	 
        		$table .= '</td> 
            
        		<td> 
        			<input type="text" class="form-control" id="wcounts" name="wcounts"  value="'.$numRows.'" style="display: none;">
        			 <input type="checkbox" class="checkbox_size" name="wcheckbox'.$i.'" id="wcheckbox'.$i.'"  onclick="checkboxs(this.id);">
        			<input type="text" class="form-control" id="wselect'.$i.'" name="wselect'.$i.'"  value="" style="display: none;"> 
        		</td>  
        	</tr>  '; 
           	$i++; 
   		} 
echo ($table); 
	}
if($main_group_val){

	$sql ="SELECT * FROM mst_attributes WHERE `table_name` = 'item_group_main'";
   	$db->query($sql);
   	$numRows= $db->num_rows();

   	//Table Section
   	$table =  '';
   	$i=1;
   	$table = '<thead  id="thead">					        	 	
            <tr style="font-size: 14px;">
                <th style="width: 11%;text-align: center;">S.NO</th>   
                <th style="width: 44%;text-align: center;">MAIN GROUPS</th>  
                <th style="width:5%;text-align: center;">SELECT</th> 
            </tr>
        </thead><tbody id="tbody">';
   	while($row22 = $db->fetch_object()){   
    	 

   		$table .= ' <tr class="table-row" style="text-align: center;">
        		<td style="width: 18%;"">
        			<input type="text" class="form-control" id="wsno" name="wsno"  value="'.$i.'" readonly>
        		</td>  
        		<td style="width: 72%;"">'; 
            		$table .= '<span>'.$row22->field_value.'</span>';
            		$table .= '<input type="text" class="form-control" id="wmain_group'.$i.'" name="wmain_group'.$i.'"  value="'.$row22->field_value.'" readonly style="display:none;"><input type="text" class="form-control" id="wsub_group'.$i.'" name="wsub_group'.$i.'"  value="" readonly style="display:none;"><input type="text" class="form-control" id="witem_description'.$i.'" name="witem_description'.$i.'"  value="" readonly style="display:none;"><input type="text" class="form-control" id="wparty'.$i.'" name="wparty'.$i.'"  value="" readonly style="display:none;">';
            	 
        		$table .= '</td> 
        		<td style="width:9%;"> 
        			<input type="text" class="form-control" id="wcounts" name="wcounts"  value="'.$numRows.'" style="display: none;">
        			 <input type="checkbox" class="checkbox_size" name="wcheckbox'.$i.'" id="wcheckbox'.$i.'"  onclick="checkboxs(this.id);">
        			<input type="text" class="form-control" id="wselect'.$i.'" name="wselect'.$i.'"  value="" style="display: none;"> 
        		</td>  
        	</tr>  '; 
           	$i++; 
   		} 
echo ($table); 
	}

if($sub_group_val){

	$sql ="SELECT * FROM mst_attributes WHERE `table_name` = 'item_group_sub'";
   	$db->query($sql);
   	$numRows= $db->num_rows();

   	//Table Section
   	$table =  '';
   	$i=1;
   	$table = '<thead  id="thead">					        	 	
            <tr style="font-size: 14px;">
                <th style="width: 11%;text-align: center;">S.NO</th>    
                <th style="width: 44%;text-align: center;">SUB GROUPS</th>  
                <th style="width:5%;text-align: center;">SELECT</th> 
            </tr>
        </thead><tbody id="tbody">';
   	while($row22 = $db->fetch_object()){   
    	 

   		$table .= ' <tr class="table-row" style="text-align: center;">
        		<td style="width: 18%;">
        			<input type="text" class="form-control" id="wsno" name="wsno"  value="'.$i.'" readonly>
        		</td> 
        		<td style="width: 72%;">'; 
            		$table .= '<span>'.$row22->field_value.'</span>';
            		$table .= '<input type="text" class="form-control" id="wsub_group'.$i.'" name="wsub_group'.$i.'"  value="'.$row22->field_value.'" readonly style="display:none;"><input type="text" class="form-control" id="witem_description'.$i.'" name="witem_description'.$i.'"  value="" readonly style="display:none;"><input type="text" class="form-control" id="wmain_group'.$i.'" name="wmain_group'.$i.'"  value="" readonly style="display:none;"><input type="text" class="form-control" id="wparty'.$i.'" name="wparty'.$i.'"  value="" readonly style="display:none;">';
            	 
        		$table .= '</td> 
        		<td style="width: 9%;"> 
        			<input type="text" class="form-control" id="wcounts" name="wcounts"  value="'.$numRows.'" style="display: none;">
        			 <input type="checkbox" class="checkbox_size" name="wcheckbox'.$i.'" id="wcheckbox'.$i.'"  onclick="checkboxs(this.id);">
        			<input type="text" class="form-control" id="wselect'.$i.'" name="wselect'.$i.'"  value="" style="display: none;"> 
        		</td>  
        	</tr>  '; 
           	$i++; 
   		} 
echo ($table); 
	}

  //Party Wise
if($party_val){

  $sql ="SELECT * FROM mst_party ";
    $db->query($sql);
    $numRows= $db->num_rows();

    //Table Section
    $table =  '';
    $i=1;
    $table = '<thead>                     
            <tr style="font-size: 14px;">
                <th style="width: 11%;text-align: center;">S.NO</th>    
                <th style="width: 44%;text-align: center;">Party Name</th>  
                <th style="width:5%;text-align: center;">SELECT</th> 
            </tr>
        </thead><tbody>';
    while($row22 = $db->fetch_object()){   
       

      $table .= ' <tr class="table-row" style="text-align: center;">
            <td style="width:18%;">
              <input type="text" class="form-control" id="wsno" name="wsno"  value="'.$i.'" readonly>
            </td> 
            <td style="width:72%;">'; 
                $table .= '<span>'.$row22->company_name.'</span>';
                $table .= '<input type="text" class="form-control" id="wparty'.$i.'" name="wparty'.$i.'"  value="'.$row22->company_name.'" readonly style="display:none;"><input type="text" class="form-control" id="witem_description'.$i.'" name="witem_description'.$i.'"  value="" readonly style="display:none;"><input type="text" class="form-control" id="wmain_group'.$i.'" name="wmain_group'.$i.'"  value="" readonly style="display:none;"><input type="text" class="form-control" id="wsub_group'.$i.'" name="wsub_group'.$i.'"  value="" readonly style="display:none;">';
               
            $table .= '</td> 
            <td style="width:9%;"> 
              <input type="text" class="form-control" id="wcounts" name="wcounts"  value="'.$numRows.'" style="display: none;">
               <input type="checkbox" class="checkbox_size" name="wcheckbox'.$i.'" id="wcheckbox'.$i.'"  onclick="checkboxs(this.id);">
              <input type="text" class="form-control" id="wselect'.$i.'" name="wselect'.$i.'"  value="" style="display: none;"> 
            </td>  
          </tr>  '; 
            $i++; 
      } 
echo ($table); 
  }
		                    
 
?>
</div> 
<?php 
  if($clicked_id == 'checkall'){ 
    for($k=1;$k< $i;$k++){
  ?>
    <script type="text/javascript">
      var match = '<?php echo $k;?>';   
      $("#wcheckbox"+match).attr('checked', true);
      document.getElementById("wselect"+match).value = '1';
    </script>
<?php } }else{ 
  for($k=1;$k< $i;$k++){
  ?>
    <script type="text/javascript">
      var match = '<?php echo $k;?>';   
      $("#wcheckbox"+match).attr('checked', false);
      document.getElementById("wselect"+match).value = '0';
    </script>
<?php } }
?>
<script type="text/javascript">
	function checkboxs(clicked_id){
		var regex = /[+-]?\d+(?:\.\d+)?/g;
		var match = parseInt(regex.exec(clicked_id));
		var wcheckbox = document.getElementById("wcheckbox"+match).checked;

		if(wcheckbox == true){
			document.getElementById("wselect"+match).value = '1';
		}else if(wcheckbox == false){
			document.getElementById("wselect"+match).value = '0';
		}
	}
//Code
	function myFunction() {
	  var input, filter, table, tr, td, i, txtValue;
	  input = document.getElementById("myInput");
	  filter = input.value.toUpperCase();
	  table = document.getElementById("myTables");
	  tr = table.getElementsByTagName("tr");
	  for (i = 0; i < tr.length; i++) {
	    td = tr[i].getElementsByTagName("td")[1];
	    if (td) {
	      txtValue = td.textContent || td.innerText;
	      if (txtValue.toUpperCase().indexOf(filter) > -1) {
	        tr[i].style.display ="";
	      } else {
	        tr[i].style.display ="none";
	      }
	    }       
	  }
	}
  //Description
  function myFunction_des() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("myInput1");
    filter = input.value.toUpperCase();
    table = document.getElementById("myTables");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td")[2];
      if (td) {
        txtValue = td.textContent || td.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          tr[i].style.display ="";
        } else {
          tr[i].style.display ="none";
        }
      }       
    }
  }
  //Check All
  $("#checkAll").click(function () {
     $('input:wcheckbox').not(this).prop('checked', this.checked);
  });
</script>