
<?php
include_once("../../config/auto_loader.php");

 $id_room=$_POST['id'];
 
 $UniqueCodeGen = $_REQUEST['UniqueCodeGen'];

        $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' AND  id='".$id_room."' and status='1' ");
			$row = $db->fetch_object2($resCat);
				$itemNameSelectSQL = "SELECT * FROM `".TBL_INV_ITEMS_DETAILS."` WHERE id_item='".$id_room."' and enabled='1' ";
				$resitemName=mysqli_query($connNew,$itemNameSelectSQL); 
				$itemNameNumRows = mysqli_num_rows($resitemName);
				 $itemNameNumRows;
				if($itemNameNumRows>0){
					 $i=1;
				
					 
					while($rowitemName = mysqli_fetch_object($resitemName)){
						
						if($i==1){
						   $dataArr[] = '<tr>';
						} 
							 
						 $dataArr[] =  '<td style="padding:0px;"><a name="addItemList" id="addItemList_'.$row->id.'"  class="btn mainmenu_btn btn-success" value="'.ucfirst($rowitemName->name).'"  onclick="AddgetItemlist2(this.id,'.$rowitemName->id.',\''.$_REQUEST['UniqueCodeGen'].'\');" style="padding: 4px 11px;margin-right:5px;">'.ucfirst($row->name).' - '.ucfirst($rowitemName->name).'</a></td> ';
						
						if($i==1){ $i=1;
						
						$dataArr[] = '</tr>';
						
						}else{ $i++; }
					}
				}


echo json_encode($dataArr);


/*$dataArr[] =  '<tr> <td style="padding:0px 15px"> '.$row->name.' - '.$rowitemName->name.' </td>
							<td style="padding:0px 15px"> '.$rowitemName->rate.' </td>	 <td style="padding:0px 15px"><input type="checkbox" id="addItemList_'.$id_room.'" name="subitem" class="subitem" onclick="AddgetItemlist2(this.id,'.$rowitemName->id.');" style="height:17px;width:20px"> </td> </tr>'; */

	
 ?>


