<?php
include_once("../../config/auto_loader.php");
	  

if($_POST['id_menu'] !='' && $_POST['id_module']!=''){

	$moduleName=mysqli_fetch_object(mysqli_query($appConnect,"SELECT name FROM ".APP_MENU." WHERE id='".$_POST['id_menu']."' "))->name;

	$chkMenuChecked =mysqli_fetch_object(mysqli_query($connNew,"SELECT status FROM ".TBL_MENU_ACCESS." WHERE id_menu='".$_POST['id_menu']."' "))->status;

	if($chkMenuChecke==0)
		$menuCheckedInAct="checked='checked'";
	else
		$menuCheckedAct="";

	$return = '<table  class="table table-bordered  text-center text-white">
				<thead>
					<tr  style="background-color:#3C8DBC;color:#fff;">
						<th colspan="9" >'.strtoupper($moduleName).' :  &nbsp;<label>Active</label>&nbsp;<input value="1" '.$menuCheckedInAct.' type="radio" name="menu_access" class="flat-red"/>&nbsp;<label>Inactive</label>&nbsp;<input value="0" type="radio" name="menu_access" class="flat-red"/></th>
					</tr>
					<tr  style="background-color:#3C8DBC;color:#fff;">
						<th rowspan="2" style="width:10%;vertical-align: middle;">S.No.</th>
						<th rowspan="2"  style="width:10%;vertical-align: middle;" >Name</th>
						<th colspan="7" style="width:80%;" >Permissions</th>
					</tr>
					
					<tr style="background-color:#252525;color:#fff;">
						<th style="width:12.5%;" >View <br>
							<input name="view" onclick="checkAllPer(this.id,\'chk-view\');" type="checkbox" id="per_view"/>
						</th>
						<th style="width:12.5%;" >Add <br>
							<input name="add" onclick="checkAllPer(this.id,\'chk-add\');" type="checkbox" id="per_add"/>
						</th>
						<th style="width:12.5%;" >Update <br>
							<input name="edit" onclick="checkAllPer(this.id,\'chk-edit\');" type="checkbox" id="per_edit"/>
						</th>
						<th style="width:12.5%;" >Change Status <br>
							<input name="status" onclick="checkAllPer(this.id,\'chk-status\');" type="checkbox" id="per_status"/>
						</th>
						<th style="width:12.5%;" >Delete <br>
							<input name="delete" onclick="checkAllPer(this.id,\'chk-delete\');" type="checkbox" id="per_delete"/>
						</th>
						<th style="width:12.5%;" >Import <br>
							<input name="import" onclick="checkAllPer(this.id,\'chk-import\');" type="checkbox" id="per_import"/>
						</th>
						<th style="width:12.5%;" >Export <br>
							<input name="export" onclick="checkAllPer(this.id,\'chk-export\');"  type="checkbox" id="per_export"/>
						</th>
					</tr>
				</thead><tbody>';	

	$sqlModule = "SELECT * FROM ".APP_SUB_MENU." WHERE id_module = ".$_POST['id_module']." AND id_menu=".$_POST['id_menu']." AND status=1 ORDER BY display_order";
	$resModule = mysqli_query($appConnect,$sqlModule);
	$sno=1;
	while($rowModule = mysqli_fetch_object($resModule)){

		$viewChk="";
		$addChk="";
		$editChk="";
		$statusChk="";
		$deleteChk="";
		$importChk="";
		$exportChk="";

		//Checking Boxes which already having permissions

		if(mysqli_num_rows(mysqli_query($connNew,"SELECT * FROM ".TBL_USER_PERMISSIONS." WHERE id_mst_modules='".$_POST['id_module']."' AND id_shop='".$_SESSION['shop']."' AND id_menu='".$_POST['id_menu']."' AND id_sub_menu='".$rowModule->id."' AND id_mst_user_levels='".$_POST['id_user_level']."' AND FIND_IN_SET(1,ids_user_actions) ")) > 0)
			$viewChk="checked='checked'";

		if(mysqli_num_rows(mysqli_query($connNew,"SELECT * FROM ".TBL_USER_PERMISSIONS." WHERE id_mst_modules='".$_POST['id_module']."' AND id_shop='".$_SESSION['shop']."' AND id_menu='".$_POST['id_menu']."' AND id_sub_menu='".$rowModule->id."' AND id_mst_user_levels='".$_POST['id_user_level']."' AND  FIND_IN_SET(2,ids_user_actions) ")) > 0)
			$addChk="checked='checked'";

		if(mysqli_num_rows(mysqli_query($connNew,"SELECT * FROM ".TBL_USER_PERMISSIONS." WHERE id_mst_modules='".$_POST['id_module']."' AND id_shop='".$_SESSION['shop']."' AND id_menu='".$_POST['id_menu']."' AND id_sub_menu='".$rowModule->id."' AND id_mst_user_levels='".$_POST['id_user_level']."' AND  FIND_IN_SET(3,ids_user_actions) ")) > 0)
			$editChk="checked='checked'";

		if(mysqli_num_rows(mysqli_query($connNew,"SELECT * FROM ".TBL_USER_PERMISSIONS." WHERE id_mst_modules='".$_POST['id_module']."' AND id_shop='".$_SESSION['shop']."' AND id_menu='".$_POST['id_menu']."' AND id_sub_menu='".$rowModule->id."' AND id_mst_user_levels='".$_POST['id_user_level']."' AND  FIND_IN_SET(4,ids_user_actions) ")) > 0)
			$statusChk="checked='checked'";

		if(mysqli_num_rows(mysqli_query($connNew,"SELECT * FROM ".TBL_USER_PERMISSIONS." WHERE id_mst_modules='".$_POST['id_module']."' AND id_shop='".$_SESSION['shop']."' AND id_menu='".$_POST['id_menu']."' AND id_sub_menu='".$rowModule->id."' AND id_mst_user_levels='".$_POST['id_user_level']."' AND FIND_IN_SET(6,ids_user_actions) ")) > 0)
			$deleteChk="checked='checked'";

		if(mysqli_num_rows(mysqli_query($connNew,"SELECT * FROM ".TBL_USER_PERMISSIONS." WHERE id_mst_modules='".$_POST['id_module']."' AND id_shop='".$_SESSION['shop']."' AND id_menu='".$_POST['id_menu']."' AND id_sub_menu='".$rowModule->id."' AND id_mst_user_levels='".$_POST['id_user_level']."' AND  FIND_IN_SET(7,ids_user_actions) ")) > 0)
			$importChk="checked='checked'";

		if(mysqli_num_rows(mysqli_query($connNew,"SELECT * FROM ".TBL_USER_PERMISSIONS." WHERE id_mst_modules='".$_POST['id_module']."' AND id_shop='".$_SESSION['shop']."' AND id_menu='".$_POST['id_menu']."' AND id_sub_menu='".$rowModule->id."' AND id_mst_user_levels='".$_POST['id_user_level']."' AND  FIND_IN_SET(8,ids_user_actions) ")) > 0)
			$exportChk="checked='checked'";
		//Checked permission end


		$return .='<tr>
						<th style="width:12.5%;">'.$sno.'</th>
						<th style="width:25%;">'.$rowModule->name.'</th>
						
						<th style="width:12.5%;" ><input '.$viewChk.' name="'.$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_view'.'" class="chk-view" value="1"  type="checkbox" onclick="submenuget(this.name)"/></th>

						<th style="width:12.5%;" ><input '.$addChk.' name="'.$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_add'.'" class="chk-add" value="2"  type="checkbox" onclick="submenuget(this.name)"/></th>
						<th style="width:12.5%;" ><input '.$editChk.' name="'.$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_edit'.'" class="chk-edit" value="3"  type="checkbox" onclick="submenuget(this.name)"/></th>
						<th style="width:12.5%;" ><input '.$statusChk.' name="'.$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_status'.'" class="chk-status" value="4"  type="checkbox" onclick="submenuget(this.name)"/></th>
						<th style="width:12.5%;" ><input '.$deleteChk.' name="'.$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_delete'.'" class="chk-delete" value="6"  type="checkbox" onclick="submenuget(this.name)"/></th>
						<th style="width:12.5%;" ><input '.$importChk.' name="'.$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_import'.'" class="chk-import" value="7"  type="checkbox" onclick="submenuget(this.name)"/></th>
						<th style="width:12.5%;" ><input '.$exportChk.' name="'.$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_export'.'" class="chk-export" value="8"  type="checkbox" onclick="submenuget(this.name)"/></th>
					</tr>' ;
		$sno++;			
	}
	$return .= '</tbody></table>';
}
else{
	$return='';
}
echo $return ;
mysqli_close($connNew);
mysqli_close($appConnect);
?>
