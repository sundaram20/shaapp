<?php include_once("../../config/auto_loader.php");
	
//debugData($_REQUEST);`id_mst_attributes_shift` = '".addslashes($lastInsertId)."', 
$ArrayData=array(); 
						$sqlconn=	" AND TIME(`shift_from`) <= '".addslashes(date('H:i:00'))."' ";
						$sqlconn.= "AND '".addslashes(date('H:i:00'))."' <= TIME(`shift_to`)";
							//echo date('H:i');
		$SelectSQL = "SELECT * FROM `mst_attributes_shift`";
				$res=mysqli_query($connNew,$SelectSQL); 
				$NumRows = mysqli_num_rows($res);
			if($NumRows>0){	
									
	  $itemNameSelectSQL = "SELECT * FROM `mst_attributes_shift` WHERE `id_mst_attributes_shift`='".$_REQUEST['id_attribute_shift']."' $sqlconn ";
				$resitemName=mysqli_query($connNew,$itemNameSelectSQL); 
				$itemNameNumRows = mysqli_num_rows($resitemName);
				if($itemNameNumRows>0){
					$ArrayData['status']='0';
					$ArrayData['msg']= 'Shift On Time';//. $itemNameSelectSQL;
				}else{
					$ArrayData['status']='1';
					$ArrayData['msg']= 'Please Change the Shift';//. $itemNameSelectSQL;
					}
					
			}else{
				$ArrayData['status']='0';
				$ArrayData['msg']= '';
				
				}
					
					
					echo json_encode($ArrayData);
					?>