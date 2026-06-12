<?php include_once("../config/auto_loader.php");

 $country_id = $_POST["country_id"];
 
 if($country_id != ''){

$sql = " SELECT * FROM `".TBL_ATTRIBUTES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id_country`='".$country_id."'";
	       $db->query($sql); 
	       while($row = $db->fetch_object()){   
 
	       	?> 
	      		<option value="<?php echo $row->id; ?>"> <?php echo $row->field_value; ?></option>

	    <?php  	}
 }
?>