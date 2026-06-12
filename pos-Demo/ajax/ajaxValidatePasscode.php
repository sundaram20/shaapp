<?php include_once("../../config/auto_loader.php");

$id_attribute_steward = $_REQUEST['id_attribute_steward'];


 $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."' and field_description  = '".$_REQUEST['passcode']."' and id='".$id_attribute_steward."'  and status = '1' AND table_name ='".'steward'."' ",' ORDER BY `field_value`');

                  if($db->num_rows2($resCat)){

                    echo '1';

				  }else{
					  echo '0';
					  
					  }
/*** printing end ***/


