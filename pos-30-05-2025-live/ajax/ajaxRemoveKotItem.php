<?php include_once("../../config/auto_loader.php");

	//debugData($_REQUEST);
	$returnArray=array();
	
	 checkKotIsBilledOrNot($_REQUEST['id_pos_purch']);
	 
	 
		
		//checkKotIsBilled($_REQUEST['id_pos_purch']);
		
	$UniqueCodeGen = $_REQUEST['UniqueCodeGen'];
	 $ArrayCountItem	=	count($_SESSION['POSKOT'][$UniqueCodeGen]['itemID']);
		$remove = $_REQUEST['remove'];
	if($remove == 'removeOne' && $ArrayCountItem>1){		
		
		executeSql("DELETE from `".TBL_PURCH_DETAILS."` where `id_pos_purch`='".$_REQUEST['id_pos_purch']."' and  id='".$_REQUEST['id_purch_details']."' ");
		
		
				$OrderUniqueID	= $_REQUEST['OrderUniqueID'];
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['name'][$OrderUniqueID]);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['itemID'][$OrderUniqueID]);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['price'][$OrderUniqueID]);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$OrderUniqueID]);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['id_outlet'][$OrderUniqueID]);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['id_outlet'][$OrderUniqueID]);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['id_sale_local'][$OrderUniqueID]);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['adj_qty'][$OrderUniqueID]);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['item_special_request'][$OrderUniqueID]);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['id_purch_details'][$OrderUniqueID]);
		       
			$returnArray['msg']=' One Item Removed Sucessfully';
			unset($_SESSION['POSKOT'][$UniqueCodeGen]);	
			echo json_encode($returnArray);
			die;		
		}


?>


