<?php include_once("../../config/auto_loader.php");
/////////////////////////////////////////////////////////////////////////////////////////////////////

include_once("../includes/header.php");



if($_REQUEST['followup_status']==0){

echo '|||';
?>
<script>
  $( function() {	 
    $( ".datepickertest").datepicker();
  } );
  
  

  </script>
<?php
$OtherChargesuniqueCode = 'FOLLOWUPS'.rand(0000,9999);
$availableData = '<div class="btn btn-default" style="width:100%"><div id="'.$OtherChargesuniqueCode.'" class="ajaxAddRoom">
<input type="hidden" name="followupCode[]" id="followupCode" value="'.$OtherChargesuniqueCode.'">';



$availableData .='<div class="form-group"><select name="followup_hotel_id['.$OtherChargesuniqueCode.']" id="followup_hotel_id|'.$OtherChargesuniqueCode.'" class="form-control select2" data-parsley-required data-parsley-errors-container="#hotelError" >
											    <option value="">Select Hotel</option>';
											  $resCat_rooms = selectSql(TBL_HOTELS," where status='1' AND id_shop='".addslashes($_SESSION['shop'])."'".$_SESSION['HotelPerHotel']." ",' ORDER BY `name`');
											  
								while($rowInclusion = $db->fetch_object2($resCat_rooms)){
												
								$availableData .= '<option  value="'.$rowInclusion->id.'">'.ucfirst($rowInclusion->name).'</option>';
												
											  }
								$availableData .= '</select></div>';
												 
 $availableData .=' <div class="form-group"><input type="text" class="form-control"  name="followup_description['.$OtherChargesuniqueCode.']" id="followup_description|'.$OtherChargesuniqueCode.'" value=""  placeholder="follow Up Description." data-parsley-required></div>';
												 
		
			 $availableData .='<div class="form-group"><input type="text" class="form-control datepickertest" placeholder="Enter date" id="followup_date|'.$OtherChargesuniqueCode.'" name="followup_date['.$OtherChargesuniqueCode.']" value="'.date('d-m-Y').'"  data-parsley-required></div>';
 
$availableData .='<div class="form-group">
                          
                           <select name="followupstatus" id="followupstatus" class="form-control">
                            <option value="">Select Follow up Status</option>
                            <option value="close">Close</option>
                            <option value="open" >Open</option>
                            
                          </select>
                         </div>';
		

		$availableData .='<div class="form-group" style="float:right;"><a class="btn btn-danger btn-sm" href="javascript:void(0);"  id="'.$OtherChargesuniqueCode.'" onclick="ajaxOtherChargesRemove($(this).attr(\'id\'));");">
				  <i class="fa fa-trash-o fa-lg"></i> </a></div>              
                </div>';
				
				
				
				$availableData .='</div><br><br>';
                




echo $availableData.'|||';


}else{
	
echo '|||';

?>
<script>
  $( function() {	 
    $( ".datepickertest").datepicker();
  } );
  
  

  </script>
<?php
$OtherChargesuniqueCode = 'FEEDBACK'.rand(0000,9999);
$availableData = '<div class="btn btn-default" style="width:100%"><div id="'.$OtherChargesuniqueCode.'" class="ajaxAddRoom">
<input type="hidden" name="feedbackCode[]"  id="feedbackCode" value="'.$OtherChargesuniqueCode.'">';


$availableData .='<div class="form-group"><select name="feedback_hotel_id['.$OtherChargesuniqueCode.']" id="feedback_hotel_id|'.$OtherChargesuniqueCode.'" class="form-control select2" data-parsley-required data-parsley-errors-container="#hotelError" >
						<option value="">Select Hotel</option>';
					  $resCat_rooms = selectSql(TBL_HOTELS," where status='1' AND id_shop='".addslashes($_SESSION['shop'])."'".$_SESSION['HotelPerHotel']." ",' ORDER BY `name`');
					  
						while($rowInclusion = $db->fetch_object2($resCat_rooms)){
							
							
							$availableData .= '<option  value="'.$rowInclusion->id.'">'.ucfirst($rowInclusion->name).'</option>';
						
					  }
						 $availableData .= '</select></div>';
						 
 $availableData .=' <div class="form-group"><input type="text" class="form-control"  name="feedback_description['.$OtherChargesuniqueCode.']" id="feedback_description|'.$OtherChargesuniqueCode.'" value=""  placeholder="follow Up Description." data-parsley-required></div>';
												 
			 $availableData .='<div class="form-group"><input type="text" class="form-control datepickertest" placeholder="Enter date" id="feedback_date|'.$OtherChargesuniqueCode.'" name="feedback_date['.$OtherChargesuniqueCode.']" value="'.date('d-m-Y').'"  data-parsley-required></div>';
 
 	 
				  $availableData .='<div class="form-group" style="float:right;"><a class="btn btn-danger btn-sm" href="javascript:void(0);"  id="'.$OtherChargesuniqueCode.'" onclick="ajaxOtherChargesRemove($(this).attr(\'id\'));");">
				  <i class="fa fa-trash-o fa-lg"></i> </a></div>              
                </div></div><br><br>';
                




echo $availableData.'|||';
	
}






?>