<?php include_once("../../config/auto_loader.php"); ?>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-success">
			<div class="box-header">
                <div class="row">
                    <div class="col-md-2"><h3 class="box-title">Room</h3></div>
                    <div class="col-md-4">
                        <div class="input-group date">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
							<select class="form-control first-input select2" style="width:100% !important;" data-folio_type="main" name="id_fo_bill" id="id_fo_bill" onChange="getFolioInvoiceDetails(this);">
								<option value="0">Select Room </option>
								<?php
								$resCat = mysqli_query($connNew, "SELECT * FROM `fo_folio` WHERE folio_status = '0' and status='1' and id_parent_folio = '0' order by doc_no desc");

								if (mysqli_num_rows($resCat)) {
									while ($resultCat = mysqli_fetch_object($resCat)) {
										$id_mst_attributes_title = selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$resultCat->id_mst_guest."'");
										$Title = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title."'");
										$Firstname = selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$resultCat->id_mst_guest."'");
										$Lastname =	selectColumn(TBL_GUEST,'last_name'," WHERE `id` = '".$resultCat->id_mst_guest."'");
										$guestName = $Title.' '.ucwords(strtolower($Firstname)).' '.ucwords(strtolower($Lastname));

										$id_mst_room_no_allocation = selectColumn('fo_bill','id_owner_room'," WHERE `id` = '".$resultCat->id_fo_bill."'");
										echo $id_mst_room_no_allocation."<br/>";
										$roomNumber = selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$id_mst_room_no_allocation."'");
										if ($resultCat->id == $_REQUEST['ID']) {
											$selected = 'selected="selected"';
										} else {
											$selected = '';
										}

										echo $categoryDropDown = '<option '.$selected.'  value="'.$resultCat->id.'">'.$resultCat->mdoc_no.'---    Room No:'.$roomNumber.' ---  Guest: '.$guestName.'</option>';
									}
								}
								?>
							</select>
                        </div>
                    </div>
					<div class="col-md-4" id="sub_folio_id"></div>
                </div>
            </div>
            <div id="ShowInvoiceDetails"></div>  
        </div>
    </div>
</div>
<script>$(".select2").select2();</script>