<?php include_once("../../config/auto_loader.php");

	
	   	$SQL = "select *  from ".TBL_GUEST." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  `id` = '".addslashes($_REQUEST['id_mst_guest'])."' ";
		
		$query=mysqli_query($connNew, $SQL);
		
		//$query=mysqli_query($connNew, "select * from ".TBL_COMPANY." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and name !=''  AND name LIKE '%{$key}%' order BY name LIMIT 0,25");
		
	    $row=mysqli_fetch_assoc($query);
	    
echo '<option value="'.$row['id'].'" selected="selected">'.$row['guest_reg_no'] . ' - ' . $row['first_name'].' '. $row['last_name'].' - '.$row['email'].' - ' . $phone . ' - ' . $row['city'].'</option>';
													
	    	
		  
		  
		 			
	   
		
