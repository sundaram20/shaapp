<div class="col-md-9 col-sm-9 col-xs-9" style="display : flex; flex-direction : column;  border : 1px solid #d2d6de;">
         
          
       <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="well table-responsive" style="padding: 0px 0px; height : auto!important ;">
      <table class="table order-list1 table-hover table-striped table-bordered" cellspacing="0">
        <thead>
          <tr>
            <th>Type	</th>
            <th>Remarks</th>
			
            <th></th>
          </tr>
        </thead>
         <tbody id="tableAddRemarkBody">
         
               

              
            
          
         
          
     
          
        
                    
</div>
           
          </div></tbody></table>
              
            <div class="input-group-addon"  style="border: 1px solid #fefefe;" title="add"> <a href="#" onClick="AddRemarksTextBox();"> <i class="fas fa-plus"></i> Add Remarks</a> </div>
           </div></div>
     <?php 			
											
		$resCat = selectSql(TBL_ATTRIBUTES," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND table_name='remark_type' ",' ORDER BY `field_value` ');
		  if($db->num_rows2($resCat)){
			while($resultCat = $db->fetch_object2($resCat)){				
				$remark_type .= '<option  value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
			}
		  }
															
                    ?>     
           <script>
	function AddRemarksTextBox() {
	
	
	   
    var add1 = 0;
    var add = 1;
    var order_by_roomRowCount = Number(add1) + Number(add);




days = DiffDays;
    var DiffDays = Math.round(days);
    var uncodeRoomCode = Math.floor(Math.random() * 1000) + 5;
	var roomCount=0;
    var CategoryCount=0;
		
		
       

          
           
            var uncode = Math.floor(Math.random() * 500) + 1; //Math.floor(Math.random() * 15);


           // var div = document.createElement('DIV');
            //div.setAttribute('id', uncode);
            row = GetDynamicRemarksTextBox(uncode, uncodeRoomCode);
				
				CategoryCount ='5';
            

            $('#tableAddRemarkBody').append(row);
            document.getElementById(uncodeRoomCode).style.borderBottom = "solid #7FB3E0";

            



        //$('#order_by_roomRowCount').val(order_by_roomRowCount);
		//order_by_roomRowCount=Number(order_by_roomRowCount) +  Number(add);
       
	
	
	
}

	   
	function  GetDynamicRemarksTextBox(uncode,  uncodeRoomCode) { 
    //var res_date = $("#res_date").val();

   
	 var res_room_type_new='0';
	 
	 var cssborder='style="border-top:3px solid #7FB3E0;"';

    var Utext = '<tr data-reservation-id="' + uncodeRoomCode + '" '+cssborder+'>';
	
	
  	
		Utext += '<td style="width: 180px;"><select name="PostChargesDataArray[' + res_room_type_new + '][' + uncodeRoomCode +
        '][ledger_id][] "id="ledger_id_' + uncode +
        '" data-parsley-required  class="form-control ledger_id_' + uncode +
        '" ><option value="">Select Remark Type</option><?php echo $remark_type; ?></select></td>';
		
		Utext +=
        '<td ><input type="text" class="form-control" id="res_date" name="PostChargesDataArray[' +
        res_room_type_new + '][' + uncodeRoomCode + '][resdate][]" value=""></td>';
	
		 Utext += '<td style="width: 83px;"><button type="button" value="Remove" onclick = "RemoveTextBoxRemarks(' + uncodeRoomCode +
            ')" class="deleteBox"> <i class="fas fa-trash"></i></button></td>';
			
   
    Utext += '</tr>';



    return Utext;
}
     function RemoveTextBoxRemarks(uncode) {



const rows = document.querySelectorAll(`tr[data-reservation-id="${uncode}"]`);
            
            // Iterate over all selected rows and remove them
            			rows.forEach(row => row.remove());

    /*const parentElement = document.getElementById('TextBoxContainerForm');

    // Get the child element
    const childElement = document.getElementById(uncode);

    // Check if both parent and child elements exist
    if (parentElement && childElement) {
        // Remove the child element from the parent
        parentElement.removeChild(childElement);
        TotoalTarffiData();
    } else {
        console.log('Parent or child element not found.');
    }*/
}      </script>