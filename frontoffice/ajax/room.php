 
			
			<?php include_once("../../config/auto_loader.php");
                $sqlRoom="SELECT id,name FROM ".TBL_ROOM_TYPE." WHERE id_shop='".$_SESSION['shop']."' && status='1' ";
                $resRoom=mysqli_query($connNew,$sqlRoom);

                ?>				
			<tr>
				<td>
				<input type="text" class="form-control" id="roomtype" name="roomtype" /> 
                                                     
				<!--	<select class="select2 form-control" id="roomtype" name="roomtype" style="width: 140px;">
						<option value="">-SELECT ROOM-</option>
						<?php
					/*	while($objRoom=mysqli_fetch_object($resRoom)){
							if(isset($_REQUEST['id_room']) && $_REQUEST['id_room']==$objRoom->id){
								$selected="selected";
							}
							else{
								$selected="";
							}
							echo "<option ".$selected." value='".$objRoom->name."'>".$objRoom->name."</option>";
						}*/ ?>
					</select> -->
				</td>
				
				<td>
					<select class="form-control parsley-error" name="plan" id="plan" data-parsley-required 
					style="width: 100px;">
						<option selected="selected" value="">Plan</option>
						<option value="Plan1">Plan1</option><option class="Plan2">Plan2</option>
					</select>
				</td>

				<td class="form-group"><input type="text" class="form-control parsley-error" style="width: 100px;" name="noofRooms" id="noofRooms" data-parsley-type="digits" data-parsley-required/></td>
				<td><input type="text" class="form-control parsley-error" style="width: 100px;" name="adultperperson" id="adultperperson" data-parsley-type="digits" data-parsley-required/></td>
				<td><input type="text" class="form-control parsley-error" style="width: 100px;" name="childperperson" id="childperperson" data-parsley-type="digits" data-parsley-required/></td>
				<td><input type="text" class="form-control parsley-error" style="width: 120px;" name="extrachild" id="extrachild" data-parsley-required/></td>
				<td><input type="text" class="form-control parsley-error" style="width: 100px;" name="tariffperperson" id="tariffperperson" data-parsley-required/></td>
				<td><input type="text" class="form-control parsley-error" style="width: 80px;" name="taxes" id="taxes" data-parsley-required/></td>
				<td><input type="text" class="form-control parsley-error" style="width: 80px;" name="chargespernight" id="chargespernight" data-parsley-required/></td>

				<td><button class="btn btn-danger roomsRates_remove" type="button"><i class="fa fa-trash"></i></button></td>
			</tr>
        
			