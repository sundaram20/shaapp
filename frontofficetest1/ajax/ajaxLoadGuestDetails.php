<?php 
include_once("../../config/auto_loader.php");

?>
<style>
.error {
	color: #F00;
	font-size: 12px;
}
.deleteBox {
	width: 35px;
	height: 35px;
	background-color: #fff;
	/* White background by default */
	display: flex;
	align-items: center;
	justify-content: center;
	cursor: pointer;
	transition: background-color 0.3s;
	border: 1px solid #d2d6de !important;/* margin-top : 7px; */

}
.deleteBox:hover {
	background-color: #db3434;/* Blue color on hover */
}
.deleteBox:active {
	background-color: #2980b9;/* Darker blue color when clicked */
}
.deleteBox i {
	color: #db3434;
	/* Blue color for the icon by default */
	font-size: 15px;
	transition: color 0.3s;
}
.deleteBox:hover i {
	color: #fff;/* White color for the icon on hover */
}
.deleteBox:active i {
	color: #fff;/* White color for the icon when clicked */
}
#EditReservationModal .modal-dialog {
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
}
#EditReservationModal {
	padding: 0px !important;
	min-height: 50vh !important;
}
#EditReservationModal .modal-content {
	min-height: 50vh !important;
}
.input-validation-error ~ .select2 .select2-selection__rendered {
	border: 1px solid red;
}
</style>
<script>
//$('.select2').select2();
$('.select2').each(function() {
    $(this).select2({
        dropdownParent: $(this).parent(), // fix select2 search input focus bug
    })
})

// fix select2 bootstrap modal scroll bug
$(document).on('select2:close', '.select2', function(e) {
    var evt = "scroll.select2"
    $(e.target).parents().off(evt)
    $(window).off(evt)
})
</script>

<?php 
$Array	=	explode('_',$_REQUEST['id_room_edit']);


$id_mst_guest= $Array['1'];

?>

      <div class="form-group col-sm-6">
    
    
      <label for="checkin" style="float:left;" readonly="readonly">Guest Name</label>
      <?php 
              
              
              $categoryDropDown = '<select class="form-control select2" name="id_mst_guest_form" id="id_mst_guest_form" >

                          <option value="">Select Guest</option>';

                            $SQL = "select *  from ".TBL_GUEST." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."'";
            
            $query=mysqli_query($connNew, $SQL);
            
            
            
              while($resultCat=mysqli_fetch_assoc($query)){

                            if($id_mst_guest == $resultCat['id']){

                              $selected = 'selected="selected"';

                            }else{

                              $selected = '';

                            }
$Title=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$resultCat['id_mst_attributes_title']."'"); 				
	
                            $categoryDropDown .= '<option value="'.$resultCat['id'].'"  '.$selected.' >'.$resultCat['guest_reg_no'] . ' - Name : '.ucfirst($resultCat->title).''.ucfirst($resultCat['first_name']).' '.ucfirst($resultCat['last_name']).' | Email : '.$resultCat['email'].' | Mobile : '.$resultCat['primary_mobile'].'</option>';

                          }

                        

                          echo $categoryDropDown .= '</select>';

                          ?>
      <p class="error id_mst_guest_form-error"></p>
    </div>
    
     <div class="input-group-addon" data-toggle="modal" data-target="#guestNewaddeditModal" style="width: auto;
    border: 1px solid #fefefe;float:right;margin-top:28px;"> <a href="javascript:void(0);" style="color:black;" id="res_guestAddId" onclick="GetAddNewGuestDetail();"><i
                    class="fa fa-plus"></i> </a> </div>
                    <div class="input-group-addon" data-toggle="modal" data-target="#guestNewaddeditModal" style="width: auto;
    border: 1px solid #fefefe; float:right;margin-top:28px;"> <a href="javascript:void(0);" style="color:black;" id="res_guestAddId" onclick="GetEditGuestDetail(<?php echo $id_mst_guest; ?>);"><i class="fa fa-pencil"></i></div>
    