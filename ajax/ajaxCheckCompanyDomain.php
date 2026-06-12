<?php include_once("../../config/auto_loader.php");

	/***********

	Craeted By : Hitesh Aloney 

	Info : This Ajax file is to check whether transaction exists or not for all masters before deleting.

	Don't misunderstood with the file name .. this is the common file

	************/

	if(isset($_REQUEST['id_domain'])){

		$sql = "SELECT * FROM `fs_company` WHERE id_default_group = ".$_REQUEST['id_domain']." AND id_shop = ".$_SESSION['shop']."";

		$res = executeSql($sql);

		$num = num_rows($res);

		if($num > 0){

			echo json_encode(1);

		}

		else{

			echo json_encode(2);

		}

	}

	if(isset($_REQUEST['id_paymentRemark'])){

		$sql = "SELECT * FROM `fs_orders` WHERE payment_status =".$_REQUEST['id_paymentRemark']." ";

		$res = executeSql($sql);

		$num = num_rows($res);

		if($num > 0){

			echo json_encode(1);

		}

		else{

			echo json_encode(2);

		}

	}



	if(isset($_REQUEST['id_hotel'])){

		$sql = "SELECT * FROM `fs_orders` WHERE id_hotel = ".$_REQUEST['id_hotel']." ";

		$res = executeSql($sql);

		$num = num_rows($res);



		$sql1 = "SELECT * FROM `fs_assign_hotel_room` WHERE hotel_id = ".$_REQUEST['id_hotel']." ";

		$res1 = executeSql($sql1);

		$num1 = num_rows($res1);



		if($num > 0 || $num1 > 0){

			echo json_encode(1);

		}

		else{

			echo json_encode(2);

		}

	}



	if(isset($_REQUEST['id_hotel_cat'])){

		$sql = "SELECT * FROM `fs_hotels` WHERE hotel_category = ".$_REQUEST['id_hotel_cat']." AND id_shop = ".$_SESSION['shop']."";

		$res = executeSql($sql);

		$num = num_rows($res);

		if($num > 0 ){

			echo json_encode(1);

		}

		else{

			echo json_encode(2);

		}

	}



	if(isset($_REQUEST['id_room_type'])){

		$sql = "SELECT * FROM `fs_room_mapping` WHERE room_id = ".$_REQUEST['id_room_type']." ";

		$res = executeSql($sql);

		$num = num_rows($res);

		if($num > 0 ){

			echo json_encode(1);

		}

		else{

			echo json_encode(2);

		}

	}



	if(isset($_REQUEST['id_gen_ser'])){

		$sql = "SELECT * FROM `fs_hotels` WHERE hotel_services = ".$_REQUEST['id_gen_ser']." AND id_shop = ".$_SESSION['shop']."";

		$res = executeSql($sql);

		$num = num_rows($res);

		if($num > 0 ){

			echo json_encode(1);

		}

		else{

			echo json_encode(2);

		}

	}





	if(isset($_REQUEST['id_company'])){

			$sql = "SELECT * FROM `fs_customer` WHERE id_company = ".$_REQUEST['id_company']." AND id_shop = ".$_SESSION['shop']."";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_customer'])){

			$sql = "SELECT * FROM `fs_customer_live` WHERE id_customer = ".$_REQUEST['id_customer']." AND id_shop = ".$_SESSION['shop']."";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_cmp_grp'])){

		$sql = "SELECT * FROM `fs_company` WHERE id_default_group = ".$_REQUEST['id_cmp_grp']." AND id_shop = ".$_SESSION['shop']."";

		$res = executeSql($sql);

		$num = num_rows($res);

		if($num > 0){

			echo json_encode(1);

		}

		else{

			echo json_encode(2);

		}

	}



	if(isset($_REQUEST['id_cmp_area'])){

		$sql = "SELECT * FROM `fs_company` WHERE area = ".$_REQUEST['id_cmp_area']." AND id_shop = ".$_SESSION['shop']."";

		$res = executeSql($sql);

		$num = num_rows($res);

		if($num > 0){

			echo json_encode(1);

		}

		else{

			echo json_encode(2);

		}

	}



	if(isset($_REQUEST['id_guest'])){

			$sql = "SELECT * FROM `fs_customer_live` WHERE id_customer = ".$_REQUEST['id_guest']." AND id_shop = ".$_SESSION['shop']."";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_rate'])){

			$sql = "SELECT * FROM `fs_rate_details` WHERE rate_id = ".$_REQUEST['id_rate']." ";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_rate_letter'])){

			$sql = "SELECT * FROM `fs_rate_assign_details` WHERE rate_id = ".$_REQUEST['id_rate_letter']." ";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_rate_level'])){

			$sql = "SELECT * FROM `fs_rate` WHERE rate_level_id = ".$_REQUEST['id_rate_level']." ";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_rate_plan'])){

			$sql = "SELECT * FROM `fs_rate_details` WHERE rate_plan_id = ".$_REQUEST['id_rate_plan']." ";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_rate_sea'])){

			$sql = "SELECT * FROM `fs_rate` WHERE seasonId = ".$_REQUEST['id_rate_sea']." ";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_rate_mkt'])){

			$sql = "SELECT * FROM `fs_rate` WHERE market = ".$_REQUEST['id_rate_mkt']." ";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_rate_pnt'])){

			$sql = "SELECT * FROM `fs_rate` WHERE rate_points = ".$_REQUEST['id_rate_pnt']." ";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_gen_term'])){

			$sql = "SELECT * FROM `fs_rate` WHERE generalterms = ".$_REQUEST['id_gen_term']." ";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_bgt_yr'])){

			$sql = "SELECT * FROM `fs_budget_master` WHERE seasonId = ".$_REQUEST['id_bgt_yr']." AND id_shop = ".$_SESSION['shop']."";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_segment'])){

			$sql = "SELECT * FROM `fs_orders` WHERE segment_id = ".$_REQUEST['id_segment']." AND id_shop = ".$_SESSION['shop']."";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_series'])){

			$sql = "SELECT * FROM `fs_orders` WHERE series_id = ".$_REQUEST['id_series']." AND id_shop = ".$_SESSION['shop']."";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_operator'])){

			$sql = "SELECT * FROM `fs_orders` WHERE operator_id = ".$_REQUEST['id_operator']." AND id_shop = ".$_SESSION['shop']."";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_cancel'])){

			$sql = "SELECT * FROM `fs_orders` WHERE cancellation_reason_id = ".$_REQUEST['id_cancel']." AND id_shop = ".$_SESSION['shop']."";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_amd'])){

			$sql = "SELECT * FROM `fs_orders` WHERE amendment_remarks_id = ".$_REQUEST['id_amd']." AND id_shop = ".$_SESSION['shop']."";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_shop'])){

			$sql = "SELECT * FROM `fs_orders` WHERE  id_shop = ".$_REQUEST['id_shop']."";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_user_lvl'])){

			$sql = "SELECT * FROM `fs_users` WHERE user_level = ".$_REQUEST['id_user_lvl']." AND id_shop = ".$_SESSION['shop']."";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}

	

	if(isset($_REQUEST['Evoucher_id'])){

			$sql = "SELECT * FROM ".TBL_PROMO_CODE_DETAILS." WHERE promo_code_id = ".$_REQUEST['Evoucher_id']." AND id_shop = ".$_SESSION['shop']."";

			$res = executeSql($sql);

			$num = num_rows($res);

			if($num > 0 ){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



	if(isset($_REQUEST['id_user'])){

			$sql = "SELECT * FROM `fs_areas_assign` WHERE user_id = ".$_REQUEST['id_user']." AND id_shop = ".$_SESSION['shop']."";

			$res = executeSql($sql);

			$num = num_rows($res);



			$sql1 = "SELECT * FROM `fs_orders` WHERE last_modified_by = ".$_REQUEST['id_user']." AND id_shop = ".$_SESSION['shop']."";

			$res1 = executeSql($sql1);

			$num1 = num_rows($res1);



			if($num > 0 AND $num1 > 0){

				echo json_encode(1);

			}

			else{

				echo json_encode(2);

			}

	}



?>