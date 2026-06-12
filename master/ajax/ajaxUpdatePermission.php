<?php
include_once("../../config/auto_loader.php");

$sql = "SELECT ids_module_access FROM ".TBL_USER_LEVELS." WHERE id='".$_POST['id_user_level']."' ";
	$res = mysqli_query($connNew,$sql);

	$ids_module = mysqli_fetch_object($res)->ids_module_access;

	$sqlModule = "SELECT * FROM ".APP_MODULE." WHERE id IN (".$ids_module.") AND status=1 ORDER BY display_order";
	$resModule = mysqli_query($appConnect,$sqlModule);
	
	

	while($rowModule = mysqli_fetch_object($resModule)){
		$module = strtoupper($rowModule->name) ;
	}

	$sqlModule = "SELECT * FROM ".APP_MENU." WHERE id = ".$_POST['menu_s']." AND status=1 ORDER BY display_order ";
	$resModule = mysqli_query($appConnect,$sqlModule);
	
	

	while($rowModule = mysqli_fetch_object($resModule)){
		$menu = strtoupper($rowModule->name) ;
	}


 

$sqlModule = "SELECT * FROM ".APP_SUB_MENU."  WHERE status=1 AND id_module='".$_POST['id_module']."' AND id_menu='".$_POST['id_menu']."'  ";

$resModule = mysqli_query($appConnect,$sqlModule);



if($_POST['eId']==''){

	$sql="SELECT * FROM ".TBL_MENU_ACCESS."  WHERE id_mst_user_levels='".$_POST['id_user_level']."' AND id_menu='".$_POST['id_menu']."' AND id_mst_modules='".$_POST['id_module']."' AND id_shop='".$_SESSION['shop']."' ";

	$chkExisting=mysqli_num_rows(mysqli_query($connNew,$sql));

	

	if($chkExisting == 0){
		if($_POST['menu_access']==1){
			$sqlInsert="INSERT INTO ".TBL_MENU_ACCESS." SET
						id_shop='".$_SESSION['shop']."',
						id_mst_user_levels='".$_POST['id_user_level']."',
						id_mst_modules='".$_POST['id_module']."',
						id_menu='".$_POST['id_menu']."',
						status=1
					 ";
			if(mysqli_query($connNew,$sqlInsert)){
				while($rowModule=mysqli_fetch_object($resModule)){

					$permissionsArr = array();
					$status=0;
					if(isset($_REQUEST[$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_view'])){
						array_push($permissionsArr,1);
						$status=1;
					}

					if(isset($_REQUEST[$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_add']))
						array_push($permissionsArr,2);
					if(isset($_REQUEST[$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_edit']))
						array_push($permissionsArr,3);
					if(isset($_REQUEST[$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_status']))
						array_push($permissionsArr,4);
					if(isset($_REQUEST[$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_delete']))
						array_push($permissionsArr,6);
					if(isset($_REQUEST[$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_import']))
						array_push($permissionsArr,7);
					if(isset($_REQUEST[$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_export']))
						array_push($permissionsArr,8);



					$sqlInsertPermission="INSERT INTO ".TBL_USER_PERMISSIONS." SET
						id_shop='".$_SESSION['shop']."',
						id_mst_user_levels='".$_POST['id_user_level']."',
						id_mst_modules='".$_POST['id_module']."',
						id_menu='".$_POST['id_menu']."',
						id_sub_menu='".$rowModule->id."',
						ids_user_actions='".implode(',',$permissionsArr)."',
						status='".$status."'
					 ";
					
					mysqli_query($connNew,$sqlInsertPermission); 
				}
				echo "Permission Added Successfully";
				exit;
			}		 
		}
		else{
			echo "Menu Decativated";
			exit;
		}	
	}
	else{
		echo "Permissions are already added for this menu and user level.Please Use edit option to modify";
		exit;
	}
}
else{

	//Audit Trail Section Begain
	/*for($i=0; $i<count($submenu);$i++){
		if($submenu[$i] !=''){
			echo $i;*/
		$auditquery = "SELECT * FROM ".TBL_USER_PERMISSIONS."  WHERE id_mst_user_levels='".$_POST['id_user_level']."' AND id_menu='".$_POST['id_menu']."' AND id_mst_modules='".$_POST['id_module']."' AND id_shop='".$_SESSION['shop']."' ";

			  $auditresSQL = mysqli_query($connNew, $auditquery);	
				while($auditrow = mysqli_fetch_object($auditresSQL)){ 				 
				  $old_array['permission'][]  = $auditrow ->ids_user_actions; 
				  $old_array['id_sub_menu'][] = $auditrow ->id_sub_menu; 
				}


		/*}
	}*/
			 
	//Audit Trail Section End

	$sql="SELECT * FROM ".TBL_MENU_ACCESS."  WHERE id_mst_user_levels='".$_POST['id_user_level']."' AND id_menu='".$_POST['id_menu']."' AND id_mst_modules='".$_POST['id_module']."' AND id_shop='".$_SESSION['shop']."' ";

	

	$userid=$_SESSION['userId'];
	

		$user="SELECT * FROM mst_users  WHERE id='".$userid."' ";
		$username = mysqli_query($connNew,$user);

		$rowUUsername = mysqli_fetch_object($username);
		$namenew=$rowUUsername->name;

	$chkExisting=mysqli_num_rows(mysqli_query($connNew,$sql));

	if($chkExisting > 0){
		if($_POST['menu_access']==1){
			$sqlUpdate="UPDATE ".TBL_MENU_ACCESS." SET
						status=1
						WHERE
						id_shop='".$_SESSION['shop']."'
						AND
						id_mst_user_levels='".$_POST['id_user_level']."'
						AND
						id_mst_modules='".$_POST['id_module']."'
						AND
						id_menu='".$_POST['id_menu']."'
					 ";
				$i=0;
			if(mysqli_query($connNew,$sqlUpdate)){
				while($rowModule=mysqli_fetch_object($resModule))

				{

					$permissionsArr = array();
					$status=0;
					if(isset($_REQUEST[$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_view'])){
						array_push($permissionsArr,1);
						$status=1; 
					}

					if(isset($_REQUEST[$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_add'])){
						array_push($permissionsArr,2);
						
					}
					if(isset($_REQUEST[$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_edit']))
						array_push($permissionsArr,3);
					if(isset($_REQUEST[$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_status']))
						array_push($permissionsArr,4);
					if(isset($_REQUEST[$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_delete']))
						array_push($permissionsArr,6);
					if(isset($_REQUEST[$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_import']))
						array_push($permissionsArr,7);
					if(isset($_REQUEST[$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_export']))
						array_push($permissionsArr,8);

$SQLExist = mysqli_query($connNew,"SELECT * FROM ".TBL_USER_PERMISSIONS."  WHERE id_mst_user_levels='".$_POST['id_user_level']."' AND id_menu='".$_POST['id_menu']."' AND id_mst_modules='".$_POST['id_module']."' AND id_shop='".$_SESSION['shop']."' and id_sub_menu='".$rowModule->id."' ");

		if(mysqli_num_rows($SQLExist)>0){
				
				
					 $sqlInsertPermission="UPDATE  ".TBL_USER_PERMISSIONS." SET
						ids_user_actions='".implode(',',$permissionsArr)."',
						status='".$status."'
						WHERE 
						id_shop='".$_SESSION['shop']."' AND
						id_mst_user_levels='".$_POST['id_user_level']."' AND
						id_mst_modules='".$_POST['id_module']."' AND
						id_menu='".$_POST['id_menu']."' AND
						id_sub_menu='".$rowModule->id."'
					 ";
				mysqli_query($connNew,$sqlInsertPermission); 
		}else{
			
			$sqlInsertPermission="INSERT INTO ".TBL_USER_PERMISSIONS." SET
						id_shop='".$_SESSION['shop']."',
						id_mst_user_levels='".$_POST['id_user_level']."',
						id_mst_modules='".$_POST['id_module']."',
						id_menu='".$_POST['id_menu']."',
						id_sub_menu='".$rowModule->id."',
						ids_user_actions='".implode(',',$permissionsArr)."',
						status='".$status."'
					 ";
					
					mysqli_query($connNew,$sqlInsertPermission);
			}

				}

				$auditquery = "SELECT * FROM ".TBL_USER_PERMISSIONS."  WHERE id_mst_user_levels='".$_POST['id_user_level']."' AND id_menu='".$_POST['id_menu']."' AND id_mst_modules='".$_POST['id_module']."' AND id_shop='".$_SESSION['shop']."' ";

				  $auditresSQL = mysqli_query($connNew, $auditquery);	
					while($auditrow = mysqli_fetch_object($auditresSQL)){ 				 
					  $new_array['permission'][] = $auditrow ->ids_user_actions; 
					  $new_array['id_sub_menu'][] = $auditrow ->id_sub_menu; 
					}
			 	 
			 	 for($i=0;$i<count($old_array['permission']);$i++){			 	 	
			 	 	if($old_array['permission'][$i] != $new_array['permission'][$i]){
			 	 		$old=explode(',', $old_array['permission'][$i]) ;
			 	 		$new=explode(',', $new_array['permission'][$i]) ; 
			 	 		$old_sub_menu=explode(',', $old_array['id_sub_menu'][$i]) ; 
			 	 		$new_sub_menu=explode(',', $new_array['id_sub_menu'][$i]) ; 
 						 			 	 					 	 		
			 	 		$old_count = count($old);
			 	 		$new_count = count($new);
			 	 		
			 	 		if($old_count >= $new_count){
			 	 			$k=0; $m=0;
			 	 			for($j=0;$j<$old_count;$j++){			 	 				 
			 	 				if($old[$j] == $new[$k]){
			 	 					$k++;

			 	 				}else{
			 	 					$val = $old[$j]; 
			 	 					$submeus = $old_sub_menu[$m]; $m++;
			 	 					$sqlModule_s = "SELECT * FROM ".APP_SUB_MENU." WHERE id = ".$submeus." ";
									$resModule_s = mysqli_query($appConnect,$sqlModule_s);
									$sno=1;
									while($rowModule_s = mysqli_fetch_object($resModule_s)){
										 $addname = $rowModule_s->name;							 
									}

									
									if($val == 1){
										$view .= "Module ".$module." Menu ".$menu." ".$addname." Details  View Disabled | ";
									}
									if($val == 2){
										$add .= "Module ".$module." Menu ".$menu." ".$addname." Details  Add Disabled | ";
									}
									if($val == 3){
										$edit .= "Module ".$module." Menu ".$menu." ".$addname." Details  Update Disabled | ";
									}
									if($val == 4){
										$status .= "Module ".$module." Menu ".$menu." ".$addname." Details  Change Status Disabled | ";
									}
									if($val == 6){
										$delete .= "Module ".$module." Menu ".$menu." ".$addname." Details  Delete Disabled | ";
									}
									if($val == 7){
										$import .= "Module ".$module." Menu ".$menu." ".$addname." Details  Import Disabled | ";
									}
									if($val == 8){
										$export .= "Module ".$module." Menu ".$menu." ".$addname." Details  Export Disabled | ";
									}
			 	 				}
			 	 			}
			 	 			 
			 	 		}else{
			 	 			$k=0;$m=0;
			 	 			for($j=0;$j<$new_count;$j++){			 	 				 
			 	 				if($new[$j] == $old[$k]){
			 	 					$k++;

			 	 				}else{
			 	 					$val = $new[$j];
			 	 					$submeus = $new_sub_menu[$m]; $m++;
			 	 					$sqlModule_ss = "SELECT * FROM ".APP_SUB_MENU." WHERE id = ".$submeus."";
									$resModule_ss = mysqli_query($appConnect,$sqlModule_ss);
									$sno=1;
									while($rowModule_ss= mysqli_fetch_object($resModule_ss)){
										$addname = $rowModule_ss->name;							 
									}

									if($val == 1){
										$view .= "Module ".$module." Menu ".$menu." ".$addname." Details  View Permitted | ";
									}
									if($val == 2){
										$add .= "Module ".$module." Menu ".$menu." ".$addname." Details  Add Permitted | ";
									}
									if($val == 3){
										$edit .= "Module ".$module." Menu ".$menu." ".$addname." Details  Update Permitted | ";
									}
									if($val == 4){
										$status .= "Module ".$module." Menu ".$menu." ".$addname." Details  Change Status Permitted | ";
									}
									if($val == 6){
										$delete .= "Module ".$module." Menu ".$menu." ".$addname." Details  Delete Permitted | ";
									}
									if($val == 7){
										$import .= "Module ".$module." Menu ".$menu." ".$addname." Details  Import Permitted | ";
									}
									if($val == 8){
										$export .= "Module ".$module." Menu ".$menu." ".$addname." Details  Export Permitted | ";
									}
			 	 				}
			 	 			}
			 	 		} 
			 	 		
			 	 		
			 	 	}
			 	 }

				
					 $auditeditSql = " INSERT audit_trail SET 
			                `voucher_id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."',
							`tables_name` = 'mst_user_permissions',
							`form_code` = 'Manage Permissions',
							`changes` =  '".addslashes($view).",".addslashes($add).",".addslashes($edit).",".addslashes($status).",".addslashes($delete).",".addslashes($import).",".addslashes($export)."',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 2 ";

if($view=='' && $add=='' && $edit=='' && $status=='' && $delete=='' && $import=='' && $export==''  ){
	
}else{							
	mysqli_query($connNew,$auditeditSql); 
}	 
					
					
					
					

				echo "2 Permissions Updated Successfully !";
				$view='';
				$add='';
				$edit='';
				$status='';
				$delete='';
				$import='';
				$export='';
				
			}		 
		}
		else{

			$sqlUpdate="UPDATE ".TBL_MENU_ACCESS." SET
						status=0
						WHERE
						id_shop='".$_SESSION['shop']."'
						AND
						id_mst_user_levels='".$_POST['id_user_level']."'
						AND
						id_mst_modules='".$_POST['id_module']."'
						AND
						id_menu='".$_POST['id_menu']."'
					 ";
			
			mysqli_query($connNew,$sqlUpdate);		 

			echo "Menu Decativated";
			exit;
		}
	}
	else{
		if($_POST['menu_access']==1){
			$sqlInsert="INSERT INTO ".TBL_MENU_ACCESS." SET
						id_shop='".$_SESSION['shop']."',
						id_mst_user_levels='".$_POST['id_user_level']."',
						id_mst_modules='".$_POST['id_module']."',
						id_menu='".$_POST['id_menu']."',
						status=1
					 ";
			if(mysqli_query($connNew,$sqlInsert)){
				while($rowModule=mysqli_fetch_object($resModule)){

					$permissionsArr = array();
					$status=0;
					if(isset($_REQUEST[$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_view'])){
						array_push($permissionsArr,1);
						$status=1;
					}

					if(isset($_REQUEST[$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_add']))
						array_push($permissionsArr,2);
					if(isset($_REQUEST[$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_edit']))
						array_push($permissionsArr,3);
					if(isset($_REQUEST[$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_status']))
						array_push($permissionsArr,4);
					if(isset($_REQUEST[$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_delete']))
						array_push($permissionsArr,6);
					if(isset($_REQUEST[$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_import']))
						array_push($permissionsArr,7);
					if(isset($_REQUEST[$_POST['id_module'].'_'.$_POST['id_menu'].'_'.$rowModule->id.'_export']))
						array_push($permissionsArr,8);



					$sqlInsertPermission="INSERT INTO ".TBL_USER_PERMISSIONS." SET
						id_shop='".$_SESSION['shop']."',
						id_mst_user_levels='".$_POST['id_user_level']."',
						id_mst_modules='".$_POST['id_module']."',
						id_menu='".$_POST['id_menu']."',
						id_sub_menu='".$rowModule->id."',
						ids_user_actions='".implode(',',$permissionsArr)."',
						status='".$status."'
					 ";
					
					mysqli_query($connNew,$sqlInsertPermission); 
				}
				echo "3 Permission Updated Successfully";
				exit;
			}		 
		}
		else{
			echo "Menu Decativated";
			exit;
		}	
	}	
}
mysqli_close($appConnect);
mysqli_close($connNew);
?>