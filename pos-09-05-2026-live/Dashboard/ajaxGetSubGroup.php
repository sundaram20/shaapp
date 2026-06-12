<?php include_once("../../config/auto_loader.php");?>
<?php 
//debugData($_REQUEST);
//echo $_REQUEST['selectSubgroup'].'<br>';
//echo $_REQUEST['subid'].'<br>';
 
$sql_purch 	= mysqli_query($connNew," SELECT * FROM `".TBL_PURCH."` WHERE `id` = '".$_REQUEST['id_pos_purch']."' ");
$purch_row	= mysqli_fetch_object($sql_purch);
$sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$purch_row->id_mst_user_created_by.'" ');
$sqlUserDetail1 = selectColumn(TBL_USERS,'name','where id="'.$purch_row->id_mst_user_modified_by.'" ');

?>


<script>

$(".discountvalue").keyup(function() {
    var $this = $(this);
    $this.val($this.val().replace(/[^\d.]/g, ''));        
});



function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
       return false;
	  // alert();
    }
    return true;
}

</script>



<script>

var headerThree = document.getElementById("MyNumOfPax");
var btnsThree = headerThree.getElementsByClassName("noofpaxbtn");

for (var j = 0; j < btnsThree.length; j++) {

  btnsThree[j].addEventListener("click", function() {
  var currentThree = document.getElementsByClassName("activenoofpaxbtn");

  if (currentThree.length > 0) { 

    currentThree[0].className = currentThree[0].className.replace(" activenoofpaxbtn", "");

  }

  this.className += " activenoofpaxbtn";

  });

}



var headerFour = document.getElementById("MyStewardSelect");

var btnsFour = headerFour.getElementsByClassName("noofpaxbtn");

for (var j = 0; j < btnsFour.length; j++) {

  btnsFour[j].addEventListener("click", function() {

  var currentFour = document.getElementsByClassName("activestewardbtn");

  if (currentFour.length > 0) { 

    currentFour[0].className = currentFour[0].className.replace(" activestewardbtn", "");

  }

  this.className += " activestewardbtn";

  });

}

</script>
<?php

////////////////////////////////////////////////////////////////////////

$remove = $_REQUEST['remove'];
$UniqueCodeGen = 'UNIC'.rand(0000,9999);

//print_r($_REQUEST);

if($_POST['listsubgroup']==1){ //Sub Group

$UniqueCodeGen=$_REQUEST['UniqueCodeGen'];

 $subGroup.='<div class="col-md-4" style="padding-right: 0px;">          

            <div class="form-group" style="margin-bottom: 1px;">		   

			<form name="listingForm" action="" method="post">               

            <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">

            	<table id="myTableFirst" class="table table-fixedsubmenu table-striped table-bordered dataTable no-footer" cellspacing="0" >

		        <thead>

		            <tr>

                  		<th style="padding: 4px 10px;"> Sub Group</th>

                    </tr>

		        </thead>

		        <tbody>';

                if($_POST['selectmaingroup']!=''){

					$SubMenuSql	="   AND  id_mst_attributes_group_main='".$_POST['selectmaingroup']."' ";

					} 



	$resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' AND `id_mst_attributes_item_type` = 16 $SubMenuSql GROUP BY id_mst_attributes_group_sub"); 

	  while($row = $db->fetch_object2($resCat)){ 

	  	

				 $SqlAttrbute = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'item_group_sub' AND id= '".addslashes($row->id_mst_attributes_group_sub)."'");

								  if($db->num_rows2($SqlAttrbute)){									  

								  	$resultAttrbute = $db->fetch_object2($SqlAttrbute);										

									$subGroup .='<tr><td style="padding:0px;"><input name="selectitemlist" id="selectitemlist_'.$resultAttrbute->id.'" type="button" class="btn mainmenu_btn" value="'.ucfirst($resultAttrbute->field_value).'"  onclick="getItemlist(this.id,\''.$UniqueCodeGen.'\');" style="margin-bottom:5px;padding: 4px 11px;" >&nbsp;</td>';
										$subGroup .='</tr>';
								  }

					  }

                $subGroup .='</tbody>

                

                </table>

                </div>

                

                </form>

                </div></div>

 	 

	 

	 <div class="col-md-8" id="listitemName" style="padding-right: 0px;padding-left: 0px;">

          

         <div class="form-group" style="margin-bottom: 1px;">

		   

			<form name="listingForm" action="" method="post">

               

            <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">





            	<table id="myTableSecond" class="table table-fixedsubmenu table-striped table-bordered dataTable no-footer" cellspacing="0" >

		        <thead>

                <tr>

                  <th style="padding: 4px 10px;"> Menu &nbsp; <input type="text" name="keywordsearch" id="keywordsearch"  placeholder="Search Menu"  onKeyUp="keysearch(this.value)" ></th>

                </tr>

              </thead>

             

              <tbody id="SearchResult">';

              	$id_item_type=selectColumn(TBL_ATTRIBUTES,'id','WHERE table_name="items_type" AND field_value="Menu" '); 

                if($_POST['selectmaingroup']!=''){

					$MenuSql	=" AND id_mst_attributes_item_type='".$id_item_type."' AND  id_mst_attributes_group_main='".$_POST['selectmaingroup']."'";

					}

   				   	 $SqlItemList = selectSql(TBL_INV_ITEMS," where id_shop='".addslashes($_SESSION['shop'])."' AND id_mst_attributes_item_type='".$id_item_type."' $MenuSql AND status='1' "); 

					  while($row = $db->fetch_object2($SqlItemList)){ 

					  if($_SESSION['POSKOT']['itemID']){

										if (in_array($row->id, $_SESSION['POSKOT'][$UniqueCodeGen]['itemID'])){

											$ClassName='btn mainmenu_btn activeset';

										}else{

											$ClassName='btn mainmenu_btn';

											}

					  }else{

											$ClassName='btn mainmenu_btn';

											}
											
											
				$resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' AND  id='".$row->id."'  AND status='1' ");
			    $row2 = $db->fetch_object2($resCat);
				$itemNameSelectSQL = "SELECT * FROM `".TBL_INV_ITEMS_DETAILS."` WHERE id_item='".$row->id."' AND enabled='1' ";
				$resitemName=mysqli_query($connNew,$itemNameSelectSQL); 
				$itemNameNumRows = mysqli_num_rows($resitemName);
				if($itemNameNumRows>0){
					while($rowitemName = mysqli_fetch_object($resitemName)){
					}	
					
					$subGroup .='<tr><td style="padding:0px;"><input name="addItemList" id="'.$row->id.'" type="button" class="'.$ClassName.'" value="'.ucfirst($row->name).'"  onclick="selectsubitem(this.id,\''.$_REQUEST['UniqueCodeGen'].'\');" style="margin-bottom:5px;padding: 4px 11px;"></td></tr>';
					
				}else{
					$subGroup .='<tr><td style="padding:0px;"><input name="addItemList" id="addItemList_'.$row->id.'" type="button" class="'.$ClassName.'" value="'.ucfirst($row->name).'"  onclick="AddgetItemlist(this.id,\''.$_REQUEST['UniqueCodeGen'].'\');" style="margin-bottom:5px;padding: 4px 11px;"></td></tr>';
				}	
											

							//$subGroup .='<tr><td style="padding:0px;"><input name="addItemList" id="addItemList_'.$row->id.'" type="button" class="'.$ClassName.'" value="'.ucfirst($row->name).'"  onclick="AddgetItemlist(this.id);" style="margin-bottom:5px;padding: 4px 11px;"></td></tr>';

							 }

                $subGroup .='</tbody>

                

                </table>

                </div>

                

                </form>

                </div></div>

 	 

';

						


//$subGroup .='<div id="listitemName"></div>';

	  

echo $subGroup;



}





if($_POST['listsubgroup']==2){

$UniqueCodeGen=$_REQUEST['UniqueCodeGen'];

	$ItemList.='<div id="listitemName">

	
	  <div class="form-group" style="margin-bottom: 1px;">

			<form name="listingForm" action="" method="post">

               

 <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">

 <table id="myTableSecond" class="table table-fixedsubmenu table-striped table-bordered dataTable no-footer" cellspacing="0" >

 <thead>

 <tr>                  

 <th style="padding: 4px 10px;"> Menu &nbsp; 

 <input type="text" name="keywordsearch" id="keywordsearch" onKeyUp="keysearch(this.value,\''.$_REQUEST['UniqueCodeGen'].'\')" placeholder="Search Menu" value="'.$_POST['keywordsearch'].'"></th>

                

                </tr>

		          </thead>

		        <tbody>';
				
				
				

				if($_POST['selectSubgroup']!=''){

					$MenuSql	=	"AND  id_mst_attributes_group_sub='".$_POST['selectSubgroup']."' ";

					}

				if($_POST['keywordsearch']!=''){

					$MenuSql	=	"AND  name like '%".$_POST['keywordsearch']."%' ";

					}						
				$id_item_type=selectColumn(TBL_ATTRIBUTES,'id','WHERE table_name="items_type" AND field_value="Menu" '); 	
 "where id_shop= '".addslashes($_SESSION['shop'])."' and status = '1' AND id_mst_attributes_item_type='".$id_item_type."' $MenuSql ";
                $SqlItemList = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' and status = '1' AND id_mst_attributes_item_type='".$id_item_type."' $MenuSql "); 

					    while($row = $db->fetch_object2($SqlItemList)){ 

					        if($_SESSION['POSKOT'][$UniqueCodeGen]['itemID']){
								
								
				$itemNameSelectSQL = "SELECT * FROM `".TBL_INV_ITEMS_DETAILS."` WHERE id_item='".$_POST['selectSubgroup']."' and enabled='1' ";
				$resitemName=mysqli_query($connNew,$itemNameSelectSQL); 
				$itemNameNumRows = mysqli_num_rows($resitemName);
				if($itemNameNumRows>0){
					$rowitemName = mysqli_fetch_object($resitemName);
					$itemid = $rowitemName->id;
				}else{
					$itemid = $row->id;
				}

								if (in_array($itemid, $_SESSION['POSKOT'][$UniqueCodeGen]['itemID'])){

									$ClassName='btn mainmenu_btn activeset';

								}else{

									$ClassName='btn mainmenu_btn';

									}
								}else{
									
									$ClassName='btn mainmenu_btn';

									}
									
									
				$resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' AND  id='".$row->id."' and status='1' ");
			    $row2 = $db->fetch_object2($resCat);
				$itemNameSelectSQL = "SELECT * FROM `".TBL_INV_ITEMS_DETAILS."` WHERE id_item='".$row->id."' and enabled='1' ";
				$resitemName=mysqli_query($connNew,$itemNameSelectSQL); 
				$itemNameNumRows = mysqli_num_rows($resitemName);
				if($itemNameNumRows>0){
					while($rowitemName = mysqli_fetch_object($resitemName)){
					}	
					
					$ItemList .='<tr><td style="padding:0px;"><input name="addItemList" id="'.$row->id.'" type="button" class="'.$ClassName.'" value="'.ucfirst($row->name).'"  onclick="selectsubitem(this.id,\''.$_REQUEST['UniqueCodeGen'].'\');" style="margin-bottom:5px;padding: 4px 11px;"></td></tr>';
					
				}else{
					$ItemList .='<tr><td style="padding:0px;"><input name="addItemList" id="addItemList_'.$row->id.'" type="button" class="'.$ClassName.'" value="'.ucfirst($row->name).'"  onclick="AddgetItemlist(this.id,\''.$_REQUEST['UniqueCodeGen'].'\');" style="margin-bottom:5px;padding: 4px 11px;"></td></tr>';
				}
					   

					   }

                $ItemList .='</tbody>

                

                </table>

                </div>

                

                </form>

                </div>';

				

    echo $ItemList ;

				

	

}



if($_REQUEST['listsubgroup']==3){ // Add Menu ITEM

	//echo  'qqq';
	$UniqueCodeGen=$_REQUEST['UniqueCodeGen'];
	
	if($remove == 'removeOne'){

				$OrderUniqueID	= $_REQUEST['OrderUniqueID'];

				unset($_SESSION['POSKOT'][$UniqueCodeGen]['name'][$OrderUniqueID]);

				unset($_SESSION['POSKOT'][$UniqueCodeGen]['itemID'][$OrderUniqueID]);

				unset($_SESSION['POSKOT'][$UniqueCodeGen]['price'][$OrderUniqueID]);

				unset($_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$OrderUniqueID]);
				
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['id_outlet'][$OrderUniqueID]);
				
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['id_sale_local'][$OrderUniqueID]);
		}

	$ItemList2	 ='<div class="box1 box-primary1">

        <div class="box-header with-border" style="padding-bottom:2px; padding-top:0px;">

          <h3 class="box-title">Current Order </h3>

        </div>

            

		<form name="listingForm" action="" method="post">
            <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">
            	<table id="myTableOrder" class="table  table-striped table-bordered dataTable no-footer" cellspacing="0" >
		        <thead>
		            <tr>
						  <th width="10%"> S.No.&nbsp;</th>
						  <th>Items Name</th>
						  <th>Quantity</th>
						  <th>Price</th>
						  <th>Amount</th>
						  <th>Action</th>
					</tr>
		        </thead>

		        <tbody>'; 

				//print_r($_SESSION['POSKOT']['itemID']);

				if(!is_array($_SESSION['POSKOT'][$UniqueCodeGen]['itemID'])){
					$_SESSION['POSKOT'][$UniqueCodeGen]['itemID']=array();
				}
				
				
				$itemNameSelectSQLnew = "SELECT * FROM `".TBL_INV_ITEMS_DETAILS."` WHERE id_item='".$_POST['selectSubgroup']."'  and enabled='1' ";
				$resitemNamenew=mysqli_query($connNew,$itemNameSelectSQLnew); 
				$itemNameNumRowsnew = mysqli_num_rows($resitemNamenew);
				if($itemNameNumRowsnew>0){
					$ids = $_POST['subid'];
				}	else{
					$ids = $_POST['selectSubgroup'];
				}


				if (!in_array($ids, $_SESSION['POSKOT'][$UniqueCodeGen]['itemID'])){
					
				
				
				$resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' AND  id='".$_POST['selectSubgroup']."' and status='1' ");
					$row = $db->fetch_object2($resCat);
				
					$itemNameSelectSQL = "SELECT * FROM `".TBL_INV_ITEMS_DETAILS."` WHERE id_item='".$_POST['selectSubgroup']."' and enabled='1' ";
					$resitemName=mysqli_query($connNew,$itemNameSelectSQL); 
					$itemNameNumRows = mysqli_num_rows($resitemName);
					if($itemNameNumRows>0){
						
					$itemNameSelectSQL1 = "SELECT * FROM `".TBL_INV_ITEMS_DETAILS."` WHERE id='".$_POST['subid']."' and enabled='1' ";
					$resitemName1=mysqli_query($connNew,$itemNameSelectSQL1); 
						
						//while($rowitemName = mysqli_fetch_object($resitemName)){
						$rowitemName1 = mysqli_fetch_object($resitemName1);
							$AddsuniqueCode = 'POSKOT'.rand(0000,9999);
							if($remove != 'removeOne'){
								$_SESSION['POSKOT'][$UniqueCodeGen]['name'][$AddsuniqueCode]	  = ucwords($row->name.' - '.$rowitemName1->name);
								$_SESSION['POSKOT'][$UniqueCodeGen]['itemID'][$AddsuniqueCode]	=$rowitemName1->id;
								$_SESSION['POSKOT'][$UniqueCodeGen]['itemID1'][$AddsuniqueCode]	=$row->id;
								$_SESSION['POSKOT'][$UniqueCodeGen]['price'][$AddsuniqueCode]	= $rowitemName1->rate;
								$_SESSION['POSKOT'][$UniqueCodeGen]['id_outlet'][$AddsuniqueCode]	= $row->ids_mst_outlet;
								$_SESSION['POSKOT'][$UniqueCodeGen]['id_sale_local'][$AddsuniqueCode]	= $row->id_mst_charges_sales_local;
								$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$AddsuniqueCode]=1;
							}	
						//}
					}else{
						$AddsuniqueCode = 'POSKOT'.rand(0000,9999);
						if($remove != 'removeOne'){
							$_SESSION['POSKOT'][$UniqueCodeGen]['name'][$AddsuniqueCode]	  = ucwords($row->name);
							$_SESSION['POSKOT'][$UniqueCodeGen]['itemID'][$AddsuniqueCode]	=$row->id;
							$_SESSION['POSKOT'][$UniqueCodeGen]['price'][$AddsuniqueCode]	= $row->sale_rate;
							$_SESSION['POSKOT'][$UniqueCodeGen]['id_outlet'][$AddsuniqueCode]	= $row->ids_mst_outlet;
							$_SESSION['POSKOT'][$UniqueCodeGen]['id_sale_local'][$AddsuniqueCode]	= $row->id_mst_charges_sales_local;
							$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$AddsuniqueCode]=1;
						}
					} 
				}
				
				else{

				array_reverse($_SESSION['POSKOT'][$UniqueCodeGen]['itemID']);

				$x = array_reverse($_SESSION['POSKOT'][$UniqueCodeGen]['itemID'],true);

				$reverseItemID = array_reverse($_SESSION['POSKOT'][$UniqueCodeGen]['itemID']);

					foreach($reverseItemID as $uniqueCode =>$dataCode){
		
						$itemNameSelectSQLcode = "SELECT * FROM `".TBL_INV_ITEMS_DETAILS."` WHERE id_item='".$_POST['selectSubgroup']."' and enabled='1' ";
						$resitemNamecode=mysqli_query($connNew,$itemNameSelectSQLcode); 
						$itemNameNumRows = mysqli_num_rows($resitemNamecode);
						if($itemNameNumRows>0){
							$rowitemNamecode = mysqli_fetch_object($resitemNamecode);
							 if($_SESSION['POSKOT'][$UniqueCodeGen]['itemID'][$uniqueCode]==$_REQUEST['subid']){
								$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode]	= $_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode]+1;
							}
						}else{
							if($_SESSION['POSKOT'][$UniqueCodeGen]['itemID'][$uniqueCode]== $_REQUEST['selectSubgroup']){
								$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode]	= $_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode]+1;
							}
						}
			 
				}

				}



			array_reverse($_SESSION['POSKOT'][$UniqueCodeGen]['itemID']);

			$x = array_reverse($_SESSION['POSKOT'][$UniqueCodeGen]['itemID'],true);

			$reverseItemID = array_reverse($_SESSION['POSKOT'][$UniqueCodeGen]['itemID']);

$i=1;

foreach($reverseItemID as $uniqueCode =>$dataCode){

	

	if($_SESSION['POSKOT'][$UniqueCodeGen]['itemID'][$uniqueCode]==$_REQUEST['selectSubgroup'] && in_array($_POST['selectSubgroup'], $_SESSION['POSKOT'][$UniqueCodeGen]['itemID']) ){

		// $_SESSION['POSKOT']['quantity'][$uniqueCode]	= $_SESSION['POSKOT']['quantity'][$uniqueCode]+1;

		}

	$Total1	+=	($_SESSION['POSKOT'][$UniqueCodeGen]['price'][$uniqueCode]*$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode]);
$Total = number_format($Total1,2);
	$SubTotal1	=	($_SESSION['POSKOT'][$UniqueCodeGen]['price'][$uniqueCode]*$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode]);
 $_SESSION['POSKOT'][$UniqueCodeGen]['id_outlet'][$uniqueCode];
	$SubTotal = number_format($SubTotal1 ,2);

$ItemList2 .='<tr>

<td>'.$i++.'</td>

<td>'.$_SESSION['POSKOT'][$UniqueCodeGen]['name'][$uniqueCode].'</td>

<td>

<a class="btn n-btn btn-sm discountvalue" href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="calculateQuantityPrice($(this).attr(\'id\'),\'0\','.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].',\''.$_REQUEST['UniqueCodeGen'].'\');" style="width: 40px;float: left;padding: 1px 12px;height:24px; 24px;display:flex;align-items:center;">

 <i class="fa fa-minus fa-lg"></i> </a>
				  

<input type="text" class="form-control discountvalue"  name="quantity|'.$uniqueCode.'" id="quantity|'.$uniqueCode.'" value="'.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].'" onkeypress="return isNumber(event)"   style="width: 40px;float: left;padding: 1px 12px;height: 24px;" onKeyUp="calculateQuantityPrice(\''.$uniqueCode.'\',\'2\',this.value,\''.$_REQUEST['UniqueCodeGen'].'\');">


<a class="btn n-btn btn-sm discountvalue" style="width: 40px;float: left;padding: 1px 12px;height: 24px;display:flex;align-items:center;" href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="calculateQuantityPrice($(this).attr(\'id\'),\'1\','.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].',\''.$_REQUEST['UniqueCodeGen'].'\');" >



				  <i class="fa fa-plus fa-lg"></i> </a>
				  </td>

				  <td>'.$_SESSION['POSKOT'][$UniqueCodeGen]['price'][$uniqueCode].'</td>

				  <td>'.$SubTotal.'</td>

				  

<td><a class="btn btn-danger btn-sm" style="width: 40px;float: left;padding: 1px 12px;height: 24px;display:flex;align-items:center;" href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="ajaxRemoveItemList($(this).attr(\'id\'),\'removeOne\',\''.$_REQUEST['UniqueCodeGen'].'\');" >

		<i class="fas fa-trash-alt"></i> </a></td>

</tr>';



}				  

				  

$ItemList2 .='</tbody>

		    </table>  
            </div>
			
		  </form>



            <!-- /.box-body -->

          </div>';

		  $ItemList2 .='<div class="col-md-6">

		  </div>


		  <div class="col-md-6">

		  <div class="box1 "><div class="box-header with-border"><h3 class="box-title text-success">Total : 

              <i class="fa fa-inr sm"></i> &nbsp;'.$Total.'</h3>

            </div></div></div>';


    $ItemList2 .='<div class="col-md-9"></div>';

		  $ItemList2 .='<div class="col-md-3 c-box"><a class="btn btn-block  c-btn" href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="ajaxUpdateKot($(this).attr(\'id\'),\''.$_REQUEST['UniqueCodeGen'].'\');">

				  <i class="fa fa-save"></i> Save3 </a></div> ';

	 
	if($remove == 'removeOne'){

////////////////////////SubMenu List Reset Session Removed Item

	$ItemList.='<div id="listitemName">

	

	  <div class="form-group" style="margin-bottom: 1px;">

		   

			<form name="listingForm" action="" method="post">

               

            <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">





            	<table id="myTableSecond" class="table table-fixedsubmenu table-striped table-bordered dataTable no-footer" cellspacing="0" >

		        <thead>

		            <tr>

                  

                  <th style="padding: 4px 10px;"> Menu</th>

				 

                </tr>

		          </thead>

		        <tbody>';

                $SqlItemList = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' AND status=1 "); 

					  while($row = $db->fetch_object2($SqlItemList)){ 

					   if($_SESSION['POSKOT'][$UniqueCodeGen]['itemID']){

										if (in_array($row->id, $_SESSION['POSKOT'][$UniqueCodeGen]['itemID'])){

											$ClassName='btn mainmenu_btn activeset';

										}else{

											$ClassName='btn mainmenu_btn';

											}

					   }else{

											$ClassName='btn mainmenu_btn';

											}
											
				$resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' AND  id='".$row->id."' and status='1' ");
			    $row2 = $db->fetch_object2($resCat);
				$itemNameSelectSQL = "SELECT * FROM `".TBL_INV_ITEMS_DETAILS."` WHERE id_item='".$row->id."' and enabled='1' ";
				$resitemName=mysqli_query($connNew,$itemNameSelectSQL); 
				$itemNameNumRows = mysqli_num_rows($resitemName);
				if($itemNameNumRows>0){
					while($rowitemName = mysqli_fetch_object($resitemName)){
					}	
					
					$ItemList .='<tr><td style="padding:0px;"><input name="addItemList" id="'.$row->id.'" type="button" class="'.$ClassName.'" value="'.ucfirst($row->name).'"  onclick="selectsubitem(this.id,\''.$_REQUEST['UniqueCodeGen'].'\');" style="margin-bottom:5px;padding: 4px 11px;"></td></tr>';
					
				}else{
					$ItemList .='<tr><td style="padding:0px;"><input name="addItemList" id="addItemList_'.$row->id.'" type="button" class="'.$ClassName.'" value="'.ucfirst($row->name).'"  onclick="AddgetItemlist(this.id,\''.$_REQUEST['UniqueCodeGen'].'\');" style="margin-bottom:5px;padding: 4px 11px;"></td></tr>';
				}						

									//	$ItemList .='<tr><td style="padding:0px;"><input name="addItemList" id="addItemList_'.$row->id.'" type="button" class="'.$ClassName.'" value="'.ucfirst($row->name).'"  onclick="AddgetItemlist(this.id,\''.$_REQUEST['UniqueCodeGen'].'\');" style="margin-bottom:5px;padding: 4px 11px;"></td></tr>';
								

					  }

                $ItemList .='</tbody>

                

                </table>

                </div>

                

                </form>

                </div>';

				}else{

					$ItemList='';

					}

				

					echo $ItemList2.'__________'.$ItemList;

	////////////////////////SubMenu List Reset Session Removed Item

	}

if($_REQUEST['listsubgroup']==4){

	

	$ItemList2	 ='

        <div class="box-header with-border" style="padding-bottom:2px; padding-top:0px;">

          <h3 class="box-title">Current Order </h3>

        </div>

            <div class="form-group" style="margin-bottom: 1px;">

				<form name="listingForm" action="" method="post">

               

            <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">





            	<table id="myTableOrder" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" >

		        <thead>

		            <tr>

                  <th width="10%"> S.No.&nbsp;</th>

                  <th>Items Name</th>

				  <th>Quantity</th>

                  <th>Price</th>

				  <th>Amount</th>

				  <th>Action</th>

                </tr>

		          </thead>

		        <tbody>'; 

				//print_r($_SESSION['POSKOT']['itemID']);

		$UniqueCodeGen=$_REQUEST['UniqueCodeGen'];		

//echo '<pre>';  print_r($_SESSION['POSKOT']);echo '</pre>';POSKOT37491

$selecteduniqueCode	=	$_REQUEST['selecteduniqueCode'];

$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$selecteduniqueCode]=$_REQUEST['quantity'];

$i=1;

			array_reverse($_SESSION['POSKOT'][$UniqueCodeGen]['itemID']);

			$x = array_reverse($_SESSION['POSKOT'][$UniqueCodeGen]['itemID'],true);

			$reverseItemID = array_reverse($_SESSION['POSKOT'][$UniqueCodeGen]['itemID']);

foreach($reverseItemID as $uniqueCode =>$dataCode){

	

	

$Total1	+=	($_SESSION['POSKOT'][$UniqueCodeGen]['price'][$uniqueCode]*$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode]);
$Total = number_format($Total1,2);
$SubTotal1	=	($_SESSION['POSKOT'][$UniqueCodeGen]['price'][$uniqueCode]*$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode]);

	$SubTotal = number_format($SubTotal1 ,2);

$ItemList2 .='<tr>

<td>'.$i++.'</td>

<td>'.$_SESSION['POSKOT'][$UniqueCodeGen]['name'][$uniqueCode].'</td>

<td>



<a class="btn n-btn btn-sm discountvalue" href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="calculateQuantityPrice($(this).attr(\'id\'),\'0\','.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].',\''.$_REQUEST['UniqueCodeGen'].'\');" style="width: 40px;float: left;padding: 1px 12px;height: 24px;display:flex;align-items:center;">

	<i class="fa fa-minus fa-lg"></i> </a>
				  

<input type="text" class="form-control discountvalue"  name="quantity|'.$uniqueCode.'" id="quantity|'.$uniqueCode.'" value="'.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].'"  onkeypress="return isNumber(event)"  style="width: 40px;float: left;padding: 1px 12px;height: 24px;" onKeyUp="calculateQuantityPrice(\''.$uniqueCode.'\',\'2\',this.value,\''.$_REQUEST['UniqueCodeGen'].'\');">

				  

<a class="btn n-btn btn-sm discountvalue" style="width: 40px;float: left;padding: 1px 12px;height: 24px;display:flex;align-items:center;" href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="calculateQuantityPrice($(this).attr(\'id\'),\'1\','.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].',\''.$_REQUEST['UniqueCodeGen'].'\');" >

				  <i class="fa fa-plus fa-lg"></i> </a>

				  

				  </td>

				  <td>'.$_SESSION['POSKOT'][$UniqueCodeGen]['price'][$uniqueCode].'</td>

				  <td>'.$SubTotal.'</td>

<td><a class="btn btn-danger btn-sm" style="width: 40px;float: left;padding: 1px 12px;height: 24px;display:flex;align-items:center;" href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="ajaxRemoveItemList($(this).attr(\'id\'),\'removeOne\',\''.$_REQUEST['UniqueCodeGen'].'\');" >

				  <i class="fas fa-trash-alt"></i> </a></td>

</tr>';



}				  

				  

$ItemList2 .='</tbody>

		    </table>   

            </div>

			

		  </form>



            <!-- /.box-body -->

          </div>';

		  $ItemList2 .='<div class="col-md-6">

		 </div>

			

			

			

		  <div class="col-md-6">

		  

		  <div class="box1 box-primary"><div class="box-header with-border"><h3 class="box-title text-success">Total : 

              <i class="fa fa-inr sm"></i> &nbsp;'.$Total.'</h3>

            </div></div></div>
               ';

						  


    $ItemList2 .='<div class="col-md-9"></div>

                       ';

		  $ItemList2 .='<div class="col-md-3 c-box"><a class="btn btn-block  c-btn" href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="ajaxUpdateKot($(this).attr(\'id\'),\''.$_REQUEST['UniqueCodeGen'].'\');">

				  <i class="fa fa-save "></i> Save </a></div>';

				  

				 /* $ItemList2 .='<div class="col-md-12" style="margin-top:10px;"><a class="btn btn-block btn-lg bg-maroon " href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="ajaxComplteBill($(this).attr(\'id\'));");">

				  <i class="fa fa-money fa-lg"></i> Billing </a></div>

				  ';
*/
//				  echo '<pre>';print_r($_SESSION['POSKOT']); echo '</pre>';

	echo $ItemList2;

	}	

	

	

if($_POST['listsubgroup']==5){

$UniqueCodeGen=$_REQUEST['UniqueCodeGen'];

	$ItemList.='

				<div id="SearchResult">

		        <tbody>';

				if($_POST['selectSubgroup']!=''){
					$MenuSql	=	"AND  id_mst_attributes_group_sub='".$_POST['selectSubgroup']."' ";
					}

				if($_POST['keywordsearch']!=''){
					$MenuSql	=	"AND  name like '%".$_POST['keywordsearch']."%' ";
					}		


$id_item_type=selectColumn(TBL_ATTRIBUTES,'id','WHERE table_name="items_type" AND field_value="Menu" '); 

                $SqlItemList = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' AND id_mst_attributes_item_type='".$id_item_type."' $MenuSql AND status='1'"); 

					  while($row = $db->fetch_object2($SqlItemList)){ 

					   if($_SESSION['POSKOT'][$UniqueCodeGen]['itemID']){
							if (in_array($row->id, $_SESSION['POSKOT'][$UniqueCodeGen]['itemID'])){
								$ClassName='btn mainmenu_btn activeset';
							}else{
								$ClassName='btn mainmenu_btn';
								}

						}else{
							$ClassName='btn mainmenu_btn';

							}
											
											
				$resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' AND  id='".$row->id."' and status='1' ");
			    $row2 = $db->fetch_object2($resCat);
				$itemNameSelectSQL = "SELECT * FROM `".TBL_INV_ITEMS_DETAILS."` WHERE id_item='".$row->id."' and enabled='1' ";
				$resitemName=mysqli_query($connNew,$itemNameSelectSQL); 
				$itemNameNumRows = mysqli_num_rows($resitemName);
				if($itemNameNumRows>0){
					while($rowitemName = mysqli_fetch_object($resitemName)){
					}	
					
					$ItemList .='<tr><td style="padding:0px;"><input name="addItemList" id="'.$row->id.'" type="button" class="'.$ClassName.'" value="'.ucfirst($row->name).'"  onclick="selectsubitem(this.id,\''.$_REQUEST['UniqueCodeGen'].'\');" style="margin-bottom:5px;padding: 4px 11px;"></td></tr>';
					
				}else{
					$ItemList .='<tr><td style="padding:0px;"><input name="addItemList" id="addItemList_'.$row->id.'" type="button" class="'.$ClassName.'" value="'.ucfirst($row->name).'"  onclick="AddgetItemlist(this.id,\''.$_REQUEST['UniqueCodeGen'].'\');" style="margin-bottom:5px;padding: 4px 11px;"></td></tr>';
				}					
				
									//	$ItemList .='<tr><td style="padding:0px;"><input name="addItemList" id="addItemList_'.$row->id.'" type="button" class="'.$ClassName.'" value="'.ucfirst($row->name).'"  onclick="AddgetItemlist(this.id);" style="margin-bottom:5px;padding: 4px 11px;"></td></tr>';

						  }

                $ItemList .='</tbody>

                </div>

                ';

    echo $ItemList ;

}

	if($_POST['listsubgroup']==6){
		$UniqueCodeGen=$_REQUEST['UniqueCodeGen'];

  $CheckBlockedTable_Sql = "SELECT * FROM pos_purch_details WHERE qty-adj_qty>0 AND id_pos_purch  IN  (SELECT id FROM pos_purch WHERE  id_shop='".$_SESSION['shop']."'   AND pos_bill_type= '1' and cancelled=0 AND id_attribute_table= '".$_REQUEST['id_attribute_table']."')";

	                   $db->query($CheckBlockedTable_Sql); 

	                  $ResultBlockedtable1 = $db->fetch_object();

						  

					  $NumOfPax = selectColumn(TBL_PURCH,'pax'," WHERE `id` = '".$ResultBlockedtable1->id_pos_purch."'");

					  $id_attribute_shift	=	 selectColumn(TBL_PURCH,'id_attribute_shift'," WHERE `id` = '".$ResultBlockedtable1->id_pos_purch."'");

					  $id_attribute_steward	=	selectColumn(TBL_PURCH,'id_attribute_steward'," WHERE `id` = '".$ResultBlockedtable1->id_pos_purch."'");

					  

  //No Of PAX===============================================================================

  $Pax	='<div class="col-md-2">

        <div class="form-group" style="margin-bottom: 0px !important;">

          <label for="name">No Of Paxs <font color="#FF0000">*</font> </label>

          <div class="box-body table-responsive" style="padding: 0px;">

            <div id="MyNumOfPax">

              <table id="myTableTest" class="table table-fixed table-striped table-bordered dataTabletest no-footer" cellspacing="0" >

                <tbody>';

				

				

                  for ($i=1; $i<=50; $i++)

    				{

						//$class	=	'activenoofpaxbtn';

						if($i==$NumOfPax){

							$class	=	' activenoofpaxbtn';

						}else{

							$class	=	'';

							}

								$Pax .= '<tr><td style="width:20% !important;" class="noofpaxbtn'.$class.'" id="'.$i.'" onclick="SelectNoPaxs(this.id);">'.$i.'</td></tr>';

								  }

								 	

								 $Pax	.='</tbody>

              </table>

            </div>

          </div>

        </div>

      </div>';
	  

	  $Pax .='<div class="col-md-2">

        <div class="form-grou2p">

          <label for="name">Shift <font color="#FF0000">*</font> </label>

          <div class="input-group1">

           <select class="form-control select2" name="id_attribute_shift" data-parsley-required data-parsley-errors-container="#id_shiftError">

									<option value="">Select Shift</option>';

								  $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'shift'."' ",' ORDER BY `field_value`');

								  if($db->num_rows2($resCat)){

								  	while($resultCat = $db->fetch_object2($resCat)){

										if($id_attribute_shift == $resultCat->id){

											$selected = 'selected="selected"';

										}else{

											$selected = '';

										}

										$Pax .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';

									}

								  }

								 	 $Pax .= '</select>';

								  

          $Pax .=  '</div>

          <span id="id_shiftError"></span> </div>

      </div>';

	  

	  $Pax .=  '<div class="col-md-2">

        <div class="form-group" style="margin-bottom: 0px !important;">

          <label for="name">Steward <font color="#FF0000">*</font> </label>

          <div class="box-body table-responsive" style="padding: 0px;">

            <div id="MyStewardSelect">

              <table id="myTableTest" class="table table-fixedsteward table-striped table-bordered dataTabletest no-footer" cellspacing="0" >

                <tbody>';
				
				
'<select class="form-control select2" name="id_attribute_steward" id="id_attribute_steward" data-parsley-required data-parsley-errors-container="#id_stewardError">

									<option value="">Select Shift</option>';
				

								  $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'steward'."' ",' ORDER BY `field_value`');

								  if($db->num_rows2($resCat)){

								  	while($resultCat = $db->fetch_object2($resCat)){

										if($id_attribute_steward == $resultCat->id){

											$selected = 'activestewardbtn';

										}else{

											$selected = '';

										}

										$Pax .=  '<tr><td class="noofpaxbtn '.$selected.'" id="'.$resultCat->id.'_'.$resultCat->field_value.'" onclick="SelectSteward(this.id);">'.ucfirst($resultCat->field_value).'</td></tr>';

									}

								  }

							 $Pax .= '</select>';	 	

                $Pax .=  '</tbody>

              </table>

            </div>

          </div>

          

        </div>

      </div>';

	  
	  

	  				$id_attribute_shift_name	=	 selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'shift'."' AND `id` = '".$id_attribute_shift."'");

					$id_attribute_steward_name	=	selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'steward'."' AND `id` = '".$id_attribute_steward."'");

  

	echo $Pax.'EXPLODE'.$NumOfPax.'_'.$id_attribute_shift_name.'_'.$id_attribute_steward.'_'.$id_attribute_steward_name;

		}	

		

if($_POST['listsubgroup']==7){

		$UniqueCodeGen=$_REQUEST['UniqueCodeGen'];

  $GetPrevious	='

     <div class="box-header with-border" style="padding-bottom:2px; padding-top:0px;">

      <h3 class="box-title">Previous Order </h3>

    </div>

    <div class="form-group" style="margin-bottom: 1px;" >

      

        <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">

          <table id="myTableOrder1" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" >

            <thead>

              <tr>

                <th> S.No.&nbsp;</th>

                <th>Items Name</th>

                <th>Qty</th>

                <th>Price</th>

              </tr>

            </thead>

            <tbody>';


   $CheckBlockedTable_Sql = "SELECT * FROM pos_purch_details WHERE qty-adj_qty>0 AND id_pos_purch  IN  (SELECT id FROM pos_purch WHERE pos_bill_type= '1' and cancelled=0 AND id_attribute_table= '".$_REQUEST['id_attribute_table']."')";

	                   $db->query($CheckBlockedTable_Sql); $i=1;
	                  while($ResultBlockedtable1 = $db->fetch_object()){

		   $GetPrevious	.='<tr>

<td>'.$i++.'</td>';
$GetPrevious	.='<td>'.ucwords($ResultBlockedtable1->item_description).'</td>';
/*<td><a  href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="editKOT('.$ResultBlockedtable1->id_pos_purch.');">'.ucwords($ResultBlockedtable1->item_description).'</a></td>*/

$GetPrevious	.='<td>'.round($ResultBlockedtable1->qty).'</td>

<td>'.$ResultBlockedtable1->item_amount.'</td>

</tr>';
			}

		   $GetPrevious	.='</tbody>

          </table>

        </div>

      

    </div>

	<div class="col-md-3 c-box2" style="margin-top:10px;">

	<input type="submit" value="Go To Bill" class="btn btn-block  o-btn" name="Billing" ></input>

	 </div>  ';
//btn btn-block btn-primary btn-lg

	echo $GetPrevious;

		}	


if($_REQUEST['listsubgroup']==8){ 
$UniqueCodeGen=$_REQUEST['UniqueCodeGen'];
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['name']);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['itemID']);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['itemID1']);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['price']);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['quantity']);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['id_outlet']);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['id_sale_local']);
		

echo $ItemList5	 ='<div class="box1 box-primary1">

        <div class="box-header with-border" style="padding-bottom:2px; padding-top:0px;">

          <h3 class="box-title">Current Order </h3>

        </div>

            

				<form name="listingForm" action="" method="post">

               

            <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">





            	<table id="myTableOrder" class="table  table-striped table-bordered dataTable no-footer" cellspacing="0" >

		        <thead>

		            <tr>

                  <th width="10%"> S.No.&nbsp;</th>

                  <th>Items Name</th>

				  <th>Quantity</th>

                  <th>Price</th>

				  <th>Amount</th>

				  <th>Action</th>

                </tr>

		          </thead>

		        ';





		}
	
	if($_REQUEST['listsubgroup']==9){   //EDIT KOT
				$UniqueCodeGen=$_REQUEST['UniqueCodeGen'];
				$OrderUniqueID	= $_REQUEST['OrderUniqueID'];
				/*unset($_SESSION['POSKOT'][$UniqueCodeGen]['name']);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['itemID']);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['price']);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['quantity']);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['id_outlet']);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['id_sale_local']);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['kot_status']);*/
		

	$ItemList2	 ='<div class="box1 box-primary1">
        <!--<div class="box-header with-border" style="padding-bottom:2px; padding-top:0px;">
          <h3 class="box-title">Edit KOT </h3>
        </div>-->
		<form name="listingForm" action="" method="post"> 
			<input type="hidden" value="'.$_REQUEST['id_pos_purch'].'" name="id_pos_purch" id="id_pos_purch">
            <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">
            	<table id="myTableOrder" class="table  table-striped table-bordered dataTable no-footer" cellspacing="0" >
		        <thead>
		            <tr>
                  <th width="10%"> S.No.&nbsp;</th>
                  <th>Items Name</th>
				  <th>Quantity</th>
                  <th>Price</th>
				  <th>Amount</th>
				  <th>Action</th>
                </tr>
	          </thead>
		        <tbody>'; 
//echo "SELECT * FROM pos_purch_details WHERE qty-adj_qty>0 AND id_pos_purch  IN  (SELECT id FROM pos_purch WHERE pos_bill_type= '1' AND id_pos_purch= '".$_REQUEST['id_pos_purch']."')";	

			
	$CheckBlockedTable_Sql = mysqli_query($connNew,"SELECT ppp.* , 
	   (case when COALESCE(ppp.qty-ppp.adj_qty)=0 then 'Billed'
        	when COALESCE(ppp.qty-ppp.adj_qty)>0 then 'Pending'
        
        end) as kot_status
             
	    FROM pos_purch_details as ppp WHERE ppp.id_pos_purch=  '".$_REQUEST['id_pos_purch']."' ORDER BY kot_status desc");
			   //$db->query($CheckBlockedTable_Sql); 
			   $i=1;
			  $RowCount	=	mysqli_num_rows($CheckBlockedTable_Sql);	
			   while($ResultBlockedtable1 = mysqli_fetch_object($CheckBlockedTable_Sql)){
				   $ResultBlockedtable1->Pending;				  
				  $AddsuniqueCode = 'POSKOT'.rand(0000,9999);

  				  $_SESSION['POSKOT'][$UniqueCodeGen]['kot_status'][$AddsuniqueCode]	=$ResultBlockedtable1->kot_status;
				  //if($ResultBlockedtable1->kot_status=='Pending'){
			      $_SESSION['POSKOT'][$UniqueCodeGen]['name'][$AddsuniqueCode]	   = ucwords($ResultBlockedtable1->item_description);
				  $_SESSION['POSKOT'][$UniqueCodeGen]['itemID'][$AddsuniqueCode]	=$ResultBlockedtable1->id;
				  $_SESSION['POSKOT'][$UniqueCodeGen]['price'][$AddsuniqueCode]	    = $ResultBlockedtable1->item_amount;
				  $_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$AddsuniqueCode]  = round($ResultBlockedtable1->qty);			
				  $_SESSION['POSKOT'][$UniqueCodeGen]['id_outlet'][$AddsuniqueCode]	= $ResultBlockedtable1->id_mst_outlet;
				  $_SESSION['POSKOT'][$UniqueCodeGen]['id_sale_local'][$AddsuniqueCode]	= $ResultBlockedtable1->id_mst_charges_sales_local;
				  $_SESSION['POSKOT'][$UniqueCodeGen]['adj_qty'][$AddsuniqueCode]	= $ResultBlockedtable1->adj_qty;
				  //}
			  }
			
							

			array_reverse($_SESSION['POSKOT'][$UniqueCodeGen]['itemID']);
			$x = array_reverse($_SESSION['POSKOT'][$UniqueCodeGen]['itemID'],true);
			$reverseItemID = array_reverse($_SESSION['POSKOT'][$UniqueCodeGen]['itemID']);
			$i=1;

	foreach($reverseItemID as $uniqueCode =>$dataCode){

	if($_SESSION['POSKOT'][$UniqueCodeGen]['itemID'][$uniqueCode]==$_REQUEST['selectSubgroup'] && in_array($_POST['selectSubgroup'], $_SESSION['POSKOT'][$UniqueCodeGen]['itemID']) ){

		// $_SESSION['POSKOT']['quantity'][$uniqueCode]	= $_SESSION['POSKOT']['quantity'][$uniqueCode]+1;

		}

	$Total1	+=	($_SESSION['POSKOT'][$UniqueCodeGen]['price'][$uniqueCode]*$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode]);
$Total = number_format($Total1,2);
	$SubTotal1	=	($_SESSION['POSKOT'][$UniqueCodeGen]['price'][$uniqueCode]*$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode]);
	
	$SubTotal = number_format($SubTotal1 ,2);

	$_SESSION['POSKOT'][$UniqueCodeGen]['id_outlet'][$uniqueCode];
	$_SESSION['POSKOT'][$UniqueCodeGen]['id_sale_local'][$uniqueCode];
	$_SESSION['POSKOT'][$UniqueCodeGen]['adj_qty'][$uniqueCode];

$ItemList2 .='<tr>';


if($_SESSION['POSKOT'][$UniqueCodeGen]['kot_status'][$uniqueCode]=='Pending'){
//$ItemList2 .='<td>'.$i++.$uniqueCode.'</td> 
$ItemList2 .='<td>'.$i++.'</td>
<td>'.$_SESSION['POSKOT'][$UniqueCodeGen]['name'][$uniqueCode].'</td>';
$ItemList2 .='<td>
<a class="btn n-btn btn-sm discountvalue" href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="calculateQuantityPriceEdit($(this).attr(\'id\'),\'0\','.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].','.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');");" style="width: 40px;float: left;padding: 1px 12px;height: 24px;display:flex;align-items:center;">
  <i class="fa fa-minus fa-lg"></i> </a>
<input type="text" class="form-control discountvalue"  name="quantity|'.$uniqueCode.'" id="quantity|'.$uniqueCode.'" value="'.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].'"  onkeypress="return isNumber(event)"  style="width: 40px;float: left;padding: 1px 12px;height: 24px;" onKeyUp="calculateQuantityPriceEdit(\''.$uniqueCode.'\',\'2\',this.value,'.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');");">

<a class="btn n-btn btn-sm discountvalue" style="width: 40px;float: left;padding: 1px 12px;height: 24px;display:flex;align-items:center;" href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="calculateQuantityPriceEdit($(this).attr(\'id\'),\'1\','.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].','.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');");" >
				  <i class="fa fa-plus fa-lg"></i> </a>
				  </td>';
$ItemList2 .='<td>'.$_SESSION['POSKOT'][$UniqueCodeGen]['price'][$uniqueCode].'</td>

 <td>'.$SubTotal.'</td>';

$ItemList2 .='<td>

<a class="btn btn-danger btn-sm" style="width: 40px;float: left;padding: 1px 12px;height: 24px;display:flex;align-items:center;" href="javascript:void(0);"  id="'.$uniqueCode.'"  onclick="ajaxRemoveEditItemList($(this).attr(\'id\'),\'removeOne\','.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');" >

				  <i class="fas fa-trash-alt"></i> </a>
</td>';



}else{
$ItemList2 .='<td>'.$i++.'</td>
<td>'.$_SESSION['POSKOT'][$UniqueCodeGen]['name'][$uniqueCode].'</td>';
$ItemList2 .='<td>

<input type="text" class="form-control discountvalue" disabled="disabled"  name="quantity|'.$uniqueCode.'" id="quantity|'.$uniqueCode.'" value="'.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].'" onkeypress="return isNumber(event)"   style="width: 40px;float: left;padding: 1px 12px;height: 24px;" onKeyUp="calculateQuantityPriceEdit(\''.$uniqueCode.'\',\'2\',this.value,'.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');">


				  </td>';
$ItemList2 .='<td >'.$_SESSION['POSKOT'][$UniqueCodeGen]['price'][$uniqueCode].'</td>

 <td>'.$SubTotal.'</td>';

$ItemList2 .='<td>

<a class="btn btn-danger btn-sm" href="javascript:void(0);" disabled  id="'.$uniqueCode.'" >

				  <i class="fa fa-trash-o fa-lg"></i> </a>
</td>';
	
	}


$ItemList2 .='</tr>';

}				  
		  

$ItemList2 .='</tbody>

		    </table>   

            </div>

			

		  </form>

          </div>';

				  
		  $ItemList2 .=' <div class="col-md-2 c-box"><a class="btn btn-block c-btn " href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="ajaxUpdateKot($(this).attr(\'id\'),\''.$_REQUEST['UniqueCodeGen'].'\');"); ">

								  <i class="far fa-save"></i> Save </a></div>
								  <div class="col-md-2 c-box">
					<a type="button" value="Close" class="btn c-btn"  onclick="javascript:history.go(-1);" >
					<i class="far fa-window-close"></i> Close
					</a>
					</div>   ';
				    $ItemList2 .='<div class="col-md-2 c-box2">
	

				  <a class="btn btn-block cancelpop_open o-btn" href="javascript:void(0);" onClick="ajaxKOTcancel('.$_REQUEST['id_pos_purch'].');"  id="'.$uniqueCode.'"  >

				  <i class="fa fa-times fa-lg"></i> Cancel Kot </a>

				 
	</div>;

		  <div class="col-md-6 pull-right">
		  <div class="box1 box-primary"><div class="box-header with-border"><h3 class="box-title text-success">Total : 

              <i class="fas fa-rupee-sign"></i> &nbsp;'.$Total.'</h3>

            </div></div></div>';

  
               $ItemList2 .='<div class="row">

				    
	
     </div><br><br>';


	 $ItemList2 .='	<div class="row">

	 <div class="form-group col-md-3">
		                	<label for="date_created">Date Created</label>
		                	<input type="text" disabled="disabled" class="form-control" id="date_created"  value="'.dateformat($purch_row->date_created).'">				
		                </div> 

		                <div class="form-group col-md-3">
		                  <label for="last_modified_by">Created By</label>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="'.$sqlUserDetail.'">				
		                </div>  
				
						<div class="form-group col-md-3">
		                  <label for="last_modified">Last Updated</label>
		                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="'.dateformat($purch_row->last_modified).'">				
		                </div> 
				
						<div class="form-group col-md-3">
		                  <label for="last_modified_by">Last Updated By</label>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by" value="'.$sqlUserDetail1.'"">				
		                </div>
	
	</div>';  	
			
			
			
   // <!--$ItemList2 .='<div class="col-md-2">
	//</div>';-->


	
	$ItemList2 .='<div class="col-md-2">
	 <input type="button" value="Audit Trail" class="btn btn-success btn-block"  onclick="audittrial(this.value);" style="float:right">
	</div>';

	
   				
//'__________'

    echo $ItemList2.$ItemList;

	////////////////////////SubMenu List Reset Session Removed Item

	}
	


if($_REQUEST['listsubgroup']==10){

	

	$ItemList2	 ='

        <!--<div class="box-header with-border" style="padding-bottom:2px; padding-top:0px;">

          <h3 class="box-title">Edit KOT </h3>

        </div>-->

            <div class="form-group" style="margin-bottom: 1px;">

				<form name="listingForm" action="" method="post">

               
<input type="hidden" value="'.$_REQUEST['id_pos_purch'].'" name="id_pos_purch" id="id_pos_purch">
            <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">


            	<table id="myTableOrder" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" >

		        <thead>

		            <tr>

                  <th width="10%"> S.No.&nbsp;</th>

                  <th>Items Name</th>

				  <th>Quantity</th>

                  <th>Price</th>

				  <th>Amount</th>

				  <th>Action</th>

                </tr>

		          </thead>

		        <tbody>'; 

				//print_r($_SESSION['POSKOT']['itemID']);

				

//echo '<pre>';  print_r($_SESSION['POSKOT']);echo '</pre>';POSKOT37491

$selecteduniqueCode	=	$_REQUEST['selecteduniqueCode'];
$UniqueCodeGen=$_REQUEST['UniqueCodeGen'];
$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$selecteduniqueCode]=$_REQUEST['quantity'];

$i=1;

			array_reverse($_SESSION['POSKOT'][$UniqueCodeGen]['itemID']);

			$x = array_reverse($_SESSION['POSKOT'][$UniqueCodeGen]['itemID'],true);

			$reverseItemID = array_reverse($_SESSION['POSKOT'][$UniqueCodeGen]['itemID']);
//debugData($reverseItemID);
foreach($reverseItemID as $uniqueCode =>$dataCode){
	
// '<br>'.$uniqueCode;
	

$Total1	+=	($_SESSION['POSKOT'][$UniqueCodeGen]['price'][$uniqueCode]*$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode]);
$Total = number_format($Total1,2);
$SubTotal1	=	($_SESSION['POSKOT'][$UniqueCodeGen]['price'][$uniqueCode]*$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode]);
$SubTotal = number_format($SubTotal1 ,2);
	
$ItemList2 .='<tr>';


if($_SESSION['POSKOT'][$UniqueCodeGen]['kot_status'][$uniqueCode]=='Pending'){
$ItemList2 .='<td>'.$i++.'</td>
<td>'.$_SESSION['POSKOT'][$UniqueCodeGen]['name'][$uniqueCode].'</td>';
$ItemList2 .='<td>
<a class="btn n-btn btn-sm discountvalue" href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="calculateQuantityPriceEdit($(this).attr(\'id\'),\'0\','.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].','.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');");" style="width: 40px;float: left;padding: 1px 12px;height: 24px;display:flex;align-items:center;">
  <i class="fa fa-minus fa-lg"></i> </a>
  
  
<input type="text" class="form-control discountvalue"  name="quantity|'.$uniqueCode.'" id="quantity|'.$uniqueCode.'" value="'.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].'"   style="width: 40px;float: left;padding: 1px 12px;height: 24px!important;"  onkeypress="return isNumber(event)" onKeyUp="calculateQuantityPriceEdit(\''.$uniqueCode.'\',\'2\',this.value,'.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');">


<a class="btn n-btn btn-sm discountvalue" style="width: 40px;float: left;padding: 1px 12px;height: 24px;display:flex;align-items:center;" href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="calculateQuantityPriceEdit($(this).attr(\'id\'),\'1\','.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].','.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');");" >
				  <i class="fa fa-plus fa-lg"></i> </a>
				  </td>';
$ItemList2 .='<td>'.$_SESSION['POSKOT'][$UniqueCodeGen]['price'][$uniqueCode].'</td>

 <td>'.$SubTotal.'</td>';

$ItemList2 .='<td>

<a class="btn btn-danger btn-sm" style="width: 40px;float: left;padding: 1px 12px;height: 24px;display:flex;align-items:center;" href="javascript:void(0);"  id="'.$uniqueCode.'"  onclick="ajaxRemoveEditItemList($(this).attr(\'id\'),\'removeOne\','.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');" >

				  <i class="fas fa-trash-alt"></i> </a>
</td>';

}else{
$ItemList2 .='<td>'.$i++.'</td>
<td>'.$_SESSION['POSKOT'][$UniqueCodeGen]['name'][$uniqueCode].'</td>';
$ItemList2 .='<td>

<input type="text" class="form-control discountvalue" disabled="disabled"  name="quantity|'.$uniqueCode.'" id="quantity|'.$uniqueCode.'" value="'.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].'"  onkeypress="return isNumber(event)"  style="width: 40px;float: left;padding: 1px 12px;height: 24px;" onKeyUp="calculateQuantityPriceEdit(\''.$uniqueCode.'\',\'2\',this.value,'.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');");">


				  </td>';
$ItemList2 .='<td>'.$_SESSION['POSKOT'][$UniqueCodeGen]['price'][$uniqueCode].'</td>

 <td>'.$SubTotal.'</td>';

$ItemList2 .='<td>

<a class="btn btn-danger btn-sm" href="javascript:void(0);" disabled  id="'.$uniqueCode.'" >

				  <i class="fa fa-trash-o fa-lg"></i> </a>
</td>';
	
	}





$ItemList2 .='</tr>';




}				  

				  

$ItemList2 .='</tbody>

		    </table>   

            </div>

			

		  </form>



            <!-- /.box-body -->

          </div>';


		  $ItemList2 .=' <div class="col-md-2 c-box"><a class="btn btn-block c-btn " href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="ajaxUpdateKot($(this).attr(\'id\'),\''.$UniqueCodeGen.'\');">

<i class="far fa-save fa-lg"></i> Save </a></div>
<div class="col-md-2 c-box">
	<a type="button" value="Close" class="btn c-btn"  onclick="javascript:history.go(-1);" ><i class="far fa-window-close"></i> Close
	</a>
	</div>
		  <div class="col-md-6 pull-right">

		
		  <div class="box1 box-primary"><div class="box-header with-border"><h3 class="box-title text-success">Total : 

              <i class="fa fa-inr sm"></i> &nbsp;'.$Total.'</h3>

            </div></div></div>

		  

               ';

     $ItemList2 .='<div class="row">
    ';
$ItemList2 .='';
	  $ItemList2 .='</div><br><br>';

	 $ItemList2 .='	<div class="row">
	 <div class="form-group col-md-3">
		                	<label for="date_created">Date Created</label>
		                	<input type="text" disabled="disabled" class="form-control" id="date_created"  value="'.dateformat($purch_row->date_created).'">				
		                </div>


		                <div class="form-group col-md-3">
		                  <label for="last_modified_by">Created By</label>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="'.$sqlUserDetail.'">				
		                </div>  						
				
						<div class="form-group col-md-3">
		                  <label for="last_modified">Last Updated</label>
		                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="'.dateformat($purch_row->last_modified).'">				
		                </div> 
				
						<div class="form-group col-md-3">
		                  <label for="last_modified_by">Last Updated By</label>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by" value="'.$sqlUserDetail1.'"">				
		                </div>
	
	</div>'; 
    


   


	$ItemList2 .='<div class="col-md-2 pull-right">
	 <a type="button" value="Alteration History" class="btn o-btn"  onclick="audittrial(this.value);" style="float:right"> <i class="fas fa-history"></i> Alteration History</a>
	</div>';
	
//				  echo '<pre>';print_r($_SESSION['POSKOT']); echo '</pre>';

	echo $ItemList2;

}	
	
	
	
if($_REQUEST['listsubgroup']==11){

$UniqueCodeGen = $_REQUEST['UniqueCodeGen'];
	 $ArrayCountItem	=	count($_SESSION['POSKOT'][$UniqueCodeGen]['itemID']);
		
	if($remove == 'removeOne' && $ArrayCountItem>1){
		

				$OrderUniqueID	= $_REQUEST['OrderUniqueID'];
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['name'][$OrderUniqueID]);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['itemID'][$OrderUniqueID]);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['price'][$OrderUniqueID]);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$OrderUniqueID]);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['id_outlet'][$OrderUniqueID]);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['id_outlet'][$OrderUniqueID]);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['id_sale_local'][$OrderUniqueID]);
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['adj_qty'][$OrderUniqueID]);
				
				
		}

	$ItemList2	 ='<div class="box1 box-primary1">

        <div class="box-header with-border" style="padding-bottom:2px; padding-top:0px;">

          <h3 class="box-title">Current Order </h3>

        </div>

            

				<form name="listingForm" action="" method="post">

               
<input type="hidden" value="'.$_REQUEST['id_pos_purch'].'" name="id_pos_purch" id="id_pos_purch">
            <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">





            	<table id="myTableOrder" class="table  table-striped table-bordered dataTable no-footer" cellspacing="0" >

		        <thead>

		            <tr>

                  <th width="10%"> S.No.&nbsp;</th>

                  <th>Items Name</th>

				  <th>Quantity</th>

                  <th>Price</th>

				  <th>Amount</th>

				  <th>Action</th>

                </tr>

		          </thead>

		        <tbody>'; 

				//print_r($_SESSION['POSKOT']['itemID']);

				if(!is_array($_SESSION['POSKOT'][$UniqueCodeGen]['itemID'])){

					$_SESSION['POSKOT'][$UniqueCodeGen]['itemID']=array();

					

					}

				if (!in_array($_POST['selectSubgroup'], $_SESSION['POSKOT'][$UniqueCodeGen]['itemID'])){

				 $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' AND  id='".$_POST['selectSubgroup']."' and status='1' "); 

				 $row = $db->fetch_object2($resCat);



			$AddsuniqueCode = 'POSKOT'.rand(0000,9999);

			if($remove != 'removeOne'){


			$_SESSION['POSKOT'][$UniqueCodeGen]['name'][$AddsuniqueCode]	  = $row->name;

			$_SESSION['POSKOT'][$UniqueCodeGen]['itemID'][$AddsuniqueCode]	=$row->id;

			$_SESSION['POSKOT'][$UniqueCodeGen]['price'][$AddsuniqueCode]	= $row->sale_rate;
			
			$_SESSION['POSKOT'][$UniqueCodeGen]['id_outlet'][$AddsuniqueCode]	= $row->ids_mst_outlet;
			
			$_SESSION['POSKOT'][$UniqueCodeGen]['id_sale_local'][$AddsuniqueCode]	= $row->id_mst_charges_sales_local;

			$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$AddsuniqueCode]=1;

			
			}
			

			

}else{

			array_reverse($_SESSION['POSKOT'][$UniqueCodeGen]['itemID']);

			$x = array_reverse($_SESSION['POSKOT'][$UniqueCodeGen]['itemID'],true);

			$reverseItemID = array_reverse($_SESSION['POSKOT'][$UniqueCodeGen]['itemID']);

			

	foreach($reverseItemID as $uniqueCode =>$dataCode){

	

			if($_SESSION['POSKOT'][$UniqueCodeGen]['itemID'][$uniqueCode]==$_REQUEST['selectSubgroup']){

			$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode]	= $_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode]+1;

			}

	}

	}



			array_reverse($_SESSION['POSKOT'][$UniqueCodeGen]['itemID']);

			$x = array_reverse($_SESSION['POSKOT'][$UniqueCodeGen]['itemID'],true);

			$reverseItemID = array_reverse($_SESSION['POSKOT'][$UniqueCodeGen]['itemID']);

$i=1;

foreach($reverseItemID as $uniqueCode =>$dataCode){

	

	if($_SESSION['POSKOT'][$UniqueCodeGen]['itemID'][$uniqueCode]==$_REQUEST['selectSubgroup'] && in_array($_POST['selectSubgroup'], $_SESSION['POSKOT'][$UniqueCodeGen]['itemID']) ){

		// $_SESSION['POSKOT']['quantity'][$uniqueCode]	= $_SESSION['POSKOT']['quantity'][$uniqueCode]+1;

		}

$Total1	+=	($_SESSION['POSKOT'][$UniqueCodeGen]['price'][$uniqueCode]*$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode]);

$Total = number_format($Total1,2);


	$SubTotal1	=	($_SESSION['POSKOT'][$UniqueCodeGen]['price'][$uniqueCode]*$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode]);
	$SubTotal = number_format($SubTotal1 ,2);

$ItemList2 .='<tr>';	
if($_SESSION['POSKOT'][$UniqueCodeGen]['kot_status'][$uniqueCode]=='Billed'){
 
 $ItemList2 .='<td>'.$i++.'</td>
<td>'.$_SESSION['POSKOT'][$UniqueCodeGen]['name'][$uniqueCode].'</td>';
$ItemList2 .='<td>

<input type="text" class="form-control discountvalue" disabled="disabled"  name="quantity|'.$uniqueCode.'" id="quantity|'.$uniqueCode.'" value="'.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].'"  onkeypress="return isNumber(event)"  style="width: 40px;float: left;padding: 1px 12px;height: 24px;" onKeyUp="calculateQuantityPriceEdit(\''.$uniqueCode.'\',\'2\',this.value,'.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');">

 </td>';
 
 
 
 
 
 
$ItemList2 .='<td>'.$_SESSION['POSKOT'][$UniqueCodeGen]['price'][$uniqueCode].'</td>

 <td>'.$SubTotal.'</td>';

$ItemList2 .='<td>

<a class="btn btn-danger btn-sm" href="javascript:void(0);" disabled  id="'.$uniqueCode.'" >
  <i class="fa fa-trash-o fa-lg"></i> </a>
</td>';
 }else{

$ItemList2 .='<td>'.$i++.'</td>
<td>'.$_SESSION['POSKOT'][$UniqueCodeGen]['name'][$uniqueCode].'</td>
<td>
<a class="btn n-btn btn-sm discountvalue" href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="calculateQuantityPriceEdit($(this).attr(\'id\'),\'0\','.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].','.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');" style="width: 40px;float: left;padding: 1px 12px;height: 24px;display:flex;align-items:center;">

				  <i class="fa fa-minus fa-lg"></i> </a>

<input type="text" class="form-control  discountvalue"  name="quantity|'.$uniqueCode.'" id="quantity|'.$uniqueCode.'" value="'.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].'"  onkeypress="return isNumber(event)"  style="width: 40px;float: left;height:24px; onKeyUp="calculateQuantityPriceEdit(\''.$uniqueCode.'\',\'2\',this.value,'.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');");">

				  

<a class="btn n-btn btn-sm discountvalue" href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="calculateQuantityPriceEdit($(this).attr(\'id\'),\'1\','.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].','.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');");" style="width: 40px;float: left;padding: 1px 12px;height: 24px;display:flex;align-items:center;">

				  <i class="fa fa-plus fa-lg"></i> </a> </td>

				  <td>'.$_SESSION['POSKOT'][$UniqueCodeGen]['price'][$uniqueCode].'</td>	

				  <td>'.$SubTotal.'</td>

<td><a class="btn btn-danger btn-sm"  style="width: 40px;float: left;padding: 1px 12px;height: 24px;display:flex;align-items:center;" href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="ajaxRemoveEditItemList($(this).attr(\'id\'),\'removeOne\','.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');" >

				  <i class="fas fa-trash-alt"></i> </a></td>';
				  
 }

$ItemList2 .='</tr>';


$_SESSION['POSKOT'][$UniqueCodeGen]['id_outlet'][$uniqueCode];
}				  

				  

$ItemList2 .='</tbody>

		    </table>   

            </div>

			

		  </form>



            <!-- /.box-body -->

          </div>';
		  
		  
		  
		  
		  

		  $ItemList2 .='<div class="col-md-2 c-box"><a class="btn  c-btn" href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="ajaxUpdateKot($(this).attr(\'id\'),\''.$_REQUEST['UniqueCodeGen'].'\');");">

				  <i class="fa fa-save "></i> Save </a></div> ;

	<div class="col-md-2 c-box">
	<a type="button" value="Close" class="btn c-btn"  onclick="javascript:history.go(-1);" >
		<i class="far fa-window-close"></i> Close
	</a>
	</div>

		  <div class="col-md-6 pull-right">

		  <div class="box1 box-primary"><div class="box-header with-border"><h3 class="box-title text-success">Total : 

              <i class="fa fa-inr sm"></i> &nbsp;'.$Total.'</h3>

            </div></div></div>';
			
			
		  

    $ItemList2 .='<br><br><br>';

	 $ItemList2 .='	<div class="row">
	 <div class="form-group col-md-3">
		                	<label for="date_created">Date Created</label>
		                	<input type="text" disabled="disabled" class="form-control" id="date_created"  value="'.dateformat($purch_row->date_created).'">				
		                </div>


		                <div class="form-group col-md-3">
		                  <label for="last_modified_by">Created By</label>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="'.$sqlUserDetail.'">				
		                </div>  						
				
						<div class="form-group col-md-3">
		                  <label for="last_modified">Last Updated</label>
		                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="'.dateformat($purch_row->last_modified).'">				
		                </div> 
				
						<div class="form-group col-md-3">
		                  <label for="last_modified_by">Last Updated By</label>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by" value="'.$sqlUserDetail1.'"">				
		                </div>
	
	</div>'; 		
			
    

 
	
	$ItemList2 .='<div class="col-md-2 pull-right">
	 <a type="button" value="Alteration History" class="btn o-btn"  onclick="audittrial(this.value);" style="float:right">
	 <i class="fas fa-history"></i> Alteration History</a>
	</div>';

	$ItemList2;

	if($remove == 'removeOne'){

if($ArrayCountItem>1){
////////////////////////SubMenu List Reset Session Removed Item

	$ItemList.='<div id="listitemName">

	

	  <div class="form-group" style="margin-bottom: 1px;">

		   

			<form name="listingForm" action="" method="post">

               

            <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">





            	<table id="myTableSecond" class="table table-fixedsubmenu table-striped table-bordered dataTable no-footer" cellspacing="0" >

		        <thead>

		            <tr>

                  

                  <th style="padding: 4px 10px;"> Menu</th>

				 

                </tr>

		          </thead>

		        <tbody>';

                $SqlItemList = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' AND status=1 "); 

					  while($row = $db->fetch_object2($SqlItemList)){ 

					   if($_SESSION['POSKOT'][$UniqueCodeGen]['itemID']){

										if (in_array($row->id, $_SESSION['POSKOT'][$UniqueCodeGen]['itemID'])){

											$ClassName='btn mainmenu_btn activeset';

										}else{

											$ClassName='btn mainmenu_btn';

											}

					   }else{

											$ClassName='btn mainmenu_btn';

											}
											
											
				$resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' AND  id='".$row->id."' and status='1' ");
			    $row2 = $db->fetch_object2($resCat);
				$itemNameSelectSQL = "SELECT * FROM `".TBL_INV_ITEMS_DETAILS."` WHERE id_item='".$row->id."' ";
				$resitemName=mysqli_query($connNew,$itemNameSelectSQL); 
				$itemNameNumRows = mysqli_num_rows($resitemName);
				if($itemNameNumRows>0){
					while($rowitemName = mysqli_fetch_object($resitemName)){
					}	
					
					$ItemList .='<tr><td style="padding:0px;"><input name="addItemList" id="'.$row->id.'" type="button" class="'.$ClassName.'" value="'.ucfirst($row->name).'"  onclick="selectsubitem(this.id,\''.$_REQUEST['UniqueCodeGen'].'\');" style="margin-bottom:5px;padding: 4px 11px;"></td></tr>';
					
				}else{
					$ItemList .='<tr><td style="padding:0px;"><input name="addItemList" id="addItemList_'.$row->id.'" type="button" class="'.$ClassName.'" value="'.ucfirst($row->name).'"  onclick="AddgetItemlist(this.id,\''.$_REQUEST['UniqueCodeGen'].'\');" style="margin-bottom:5px;padding: 4px 11px;"></td></tr>';
				}				
											

									//	$ItemList .='<tr><td style="padding:0px;"><input name="addItemList" id="addItemList_'.$row->id.'" type="button" class="'.$ClassName.'" value="'.ucfirst($row->name).'"  onclick="AddgetItemlist(this.id,\''.$_REQUEST['UniqueCodeGen'].'\');" style="margin-bottom:5px;padding: 4px 11px;"></td></tr>';

									

								

					  }

                $ItemList .='</tbody>

                

                </table>

                </div>

                

                </form>

                </div>';
		}else{
			
			echo '<div style="text-align:center;color:red;font-weight:bold;">We Must have at least one Item in List.</div>';
			}
				}else{

					$ItemList='';

					}

				

    echo $ItemList2.'__________'.$ItemList;

	////////////////////////SubMenu List Reset Session Removed Item

	}
	
	
	if($_REQUEST['listsubgroup']==12){  //View KOT 

	$OrderUniqueID	= $_REQUEST['OrderUniqueID'];
		

	$ItemList2	 ='<div class="box1 box-primary1">
        <div class="box-header with-border" style="padding-bottom:2px; padding-top:0px;">
          <h3 class="box-title">Billed KOT </h3>
        </div>
		<form name="listingForm" action="" method="post"> 
			<input type="hidden" value="'.$_REQUEST['id_pos_purch'].'" name="id_pos_purch" id="id_pos_purch">
            <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">
            	<table id="myTableOrder" class="table  table-striped table-bordered dataTable no-footer" cellspacing="0" >
		        <thead>
		            <tr>
                  <th width="10%"> S.No.&nbsp;</th>
                  <th>Items Name</th>
				  <th>Quantity</th>
                  <th>Price</th>
				  <th>Amount</th>
				  <th>Bill No</th>
				  
                </tr>
	          </thead>
		        <tbody>'; 

		
			  
		$CheckBlockedTable_Sql = mysqli_query($connNew,"SELECT ppp.* , 
	   (case when COALESCE(ppp.qty-ppp.adj_qty)=0 then 'Billed'
        	when COALESCE(ppp.qty-ppp.adj_qty)>0 then 'Pending'
        
        end) as kot_status
             
	    FROM pos_purch_details as ppp WHERE ppp.id_pos_purch=  '".$_REQUEST['id_pos_purch']."'");
		
		//$db->query($CheckBlockedTable_Sql); 
			   $i=1;
			  $RowCount	=	mysqli_num_rows($CheckBlockedTable_Sql);	
			   while($ResultBlockedtable1 = mysqli_fetch_object($CheckBlockedTable_Sql)){				  
				  $AddsuniqueCode = 'POSKOT'.rand(0000,9999);
				  $ResultBlockedtable1->kot_status;
				  if($ResultBlockedtable1->kot_status=='Billed'){
					  
			//	echo "select mdoc_no from pos_purch WHERE id_shop='".$_SESSION['shop']."'  and kot_doc_no=  '".$_REQUEST['id_pos_purch']."' AND FIND_IN_SET('".$ResultBlockedtable1->id."',id_pos_details_split)";	  
					 
				  $mdoc_no=selectColumn('pos_purch','mdoc_no'," WHERE id_shop='".$_SESSION['shop']."'  AND FIND_IN_SET('".$ResultBlockedtable1->id."',id_pos_details_split)  "); 
				 // $mdoc_no=selectColumn('pos_purch','mdoc_no'," WHERE id_shop='".$_SESSION['shop']."'  and kot_doc_no=  '".$_REQUEST['id_pos_purch']."' AND FIND_IN_SET('".$ResultBlockedtable1->id."',id_pos_details_split)  "); 
					
				$Total1		+=	($ResultBlockedtable1->item_amount*round($ResultBlockedtable1->qty));
				
				$Total = number_format($Total1,2);
				
				$SubTotal1	  =	round(($ResultBlockedtable1->item_amount*round($ResultBlockedtable1->qty)),2);	  
				$SubTotal = number_format($SubTotal1 ,2);
				$ItemList2 .='<tr>
				<td>'.$i++.'</td>
				<td>'.ucwords($ResultBlockedtable1->item_description).'</td>';
				$ItemList2 .='<td>
				<input type="text" class="form-control discountvalue"  name="quantityview" id="quantityview" disabled="disabled" value="'.round($ResultBlockedtable1->qty).'"  onkeypress="return isNumber(event)"  style="width: 40px;float: left;padding: 1px 12px;height: 24px;" onKeyUp="calculateQuantityPriceEdit(\''.$uniqueCode.'\',\'2\',this.value,'.$_REQUEST['id_pos_purch'].');");">
				
				</td>';
				
				
				$ItemList2 .='<td>'.$ResultBlockedtable1->item_amount.'</td>
				
				<td>'.$SubTotal.'</td>
				
				<td>'.$mdoc_no.'</td>
				
				';
				
				
				
				$ItemList2 .='
				
				</tr>';
				
				  
				  }
			  }

					  

$ItemList2 .='</tbody>
		    </table>   
            </div>
		  </form>



            <!-- /.box-body -->

          </div>';

		  $ItemList2 .='<div class="col-md-6">
<input type="button" value="Close" class="btn c-btn"  onclick="javascript:history.go(-1);" >
			  <!-- <input type="button" value="Audit Trail" class="btn o-btn "  onclick="audittrial(this.value);" >-->
		  </div>

		  <div class="col-md-6">

		  

		  <div class="box1 box-primary"><div class="box-header with-border"><h3 class="box-title">Total : 

              <i class="fas fa-rupee-sign"></i> &nbsp;'.$Total.'</h3>
			  
			  

            </div></div></div><br><br><br>';
			
			
			 $ItemList2 .='	<div class="row">
	 <div class="form-group col-md-3">
		                	<label for="date_created">Date Created</label>
		                	<input type="text" disabled="disabled" class="form-control" id="date_created"  value="'.dateformat($purch_row->date_created).'">				
		                </div>


		                <div class="form-group col-md-3">
		                  <label for="last_modified_by">Created By</label>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="'.$sqlUserDetail.'">				
		                </div>  						
				
						<div class="form-group col-md-3">
		                  <label for="last_modified">Last Updated</label>
		                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="'.dateformat($purch_row->last_modified).'">				
		                </div> 
				
						<div class="form-group col-md-3">
		                  <label for="last_modified_by">Last Updated By</label>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by" value="'.$sqlUserDetail1.'"">				
		                </div>
		                <div class="col-md-3 pull-right">
			   <input type="button" value="Audit Trail" class="btn o-btn "  onclick="audittrial(this.value);" >
		  </div>
	
	</div>'; 	
			
			 $ItemList2 .='<div class="col-md-6"></div>';

			

    echo $ItemList2;

	////////////////////////SubMenu List Reset Session Removed Item
	}
	
	
	
	if($_REQUEST['listsubgroup']==13){  //View KOT 
	$OrderUniqueID	= $_REQUEST['id_pos_purch'];
		

	$ItemList2	 ='<div class="box1 box-primary1">
        <div class="box-header with-border" style="padding-bottom:2px; padding-top:0px;">
          <h3 class="box-title">Cancelled KOT item</h3>
        </div>
		<form name="listingForm" action="" method="post"> 
			<input type="hidden" value="'.$_REQUEST['id_pos_purch'].'" name="id_pos_purch" id="id_pos_purch">
            <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">
            	<table id="myTableOrder" class="table  table-striped table-bordered dataTable no-footer" cellspacing="0" >
		        <thead>
		            <tr>
                  <th width="10%"> S.No.&nbsp;</th>
                  <th>Items Name</th>
				  <th>Quantity</th>
                  <th>Price</th>
				  <th>Amount</th>
				  
				  
                </tr>
	          </thead>
		        <tbody>'; 
				
/*SELECT ppp.* , 
	   (case when COALESCE(pp.cancelled)=1 then 'cancelled'
	   when COALESCE(ppp.qty-ppp.adj_qty)=0 then 'Billed'
        	when COALESCE(ppp.qty-ppp.adj_qty)>0 then 'Pending'
        
        end) as kot_status
             
	    FROM pos_purch_details as ppp WHERE ppp.id_pos_purch=  '".$_REQUEST['id_pos_purch']."'"*/
		 // $CheckBlockedTable_Sql = mysqli_query($connNew,"SELECT * FROM pos_purch_details WHERE id_pos_purch  IN  (SELECT id FROM pos_purch WHERE pos_bill_type= '1' AND id_pos_purch= '".$_REQUEST['id_pos_purch']."')");
			  
		$CheckBlockedTable_Sql = mysqli_query($connNew,"SELECT *
             
	    FROM pos_purch_details as ppp WHERE ppp.id_pos_purch=  '".$_REQUEST['id_pos_purch']."'");
		
		//$db->query($CheckBlockedTable_Sql); 
			   $i=1;
			  $RowCount	=	mysqli_num_rows($CheckBlockedTable_Sql);	
			   while($ResultBlockedtable1 = mysqli_fetch_object($CheckBlockedTable_Sql)){				  
				  $AddsuniqueCode = 'POSKOT'.rand(0000,9999);
				  $ResultBlockedtable1->kot_status;
				  
					 
				  $mdoc_no=selectColumn('pos_purch','mdoc_no'," WHERE id_shop='".$_SESSION['shop']."'  and kot_doc_no=  '".$_REQUEST['id_pos_purch']."' AND FIND_IN_SET('".$ResultBlockedtable1->id."',id_pos_details_split)  "); 
					
				$Total1		+=	($ResultBlockedtable1->item_amount*round($ResultBlockedtable1->qty));
				$Total = number_format($Total1,2);
				$SubTotal1	  =	($ResultBlockedtable1->item_amount*round($ResultBlockedtable1->qty));	  
				$SubTotal = number_format($SubTotal1 ,2);
				$ItemList2 .='<tr>
				<td>'.$i++.'</td>
				<td>'.ucwords($ResultBlockedtable1->item_description).'</td>';
				$ItemList2 .='<td>
				<input type="text" class="form-control discountvalue"  name="quantityview" id="quantityview" disabled="disabled" value="'.round($ResultBlockedtable1->qty).'"  onkeypress="return isNumber(event)"  style="width: 40px;float: left;padding: 1px 12px;height: 24px;" onKeyUp="calculateQuantityPriceEdit(\''.$uniqueCode.'\',\'2\',this.value,'.$_REQUEST['id_pos_purch'].');");">
				
				</td>';
				
				
				$ItemList2 .='<td>'.$ResultBlockedtable1->item_amount.'</td>
				
				<td>'.$SubTotal.'</td>
				
				
				
				';
				
				$ItemList2 .='
				
				</tr>';
				  
			  }

					  

$ItemList2 .='</tbody>
		    </table>   
            </div>
		  </form>



            <!-- /.box-body -->

          </div>';

		  $ItemList2 .='<div class="col-md-6">
			   <input type="button" value="Close" class="btn c-btn"  onclick="javascript:history.go(-1);" >
			   <!--<input type="button" value="Audit Trail" class="btn c-btn"  onclick="audittrial(this.value);" >-->
		  </div>

		  

			
		  <div class="col-md-6">

		  

		  <div class="box1 box-primary"><div class="box-header with-border"><h3 class="box-title">Total : 

              <i class="fa fa-inr sm"></i> &nbsp;'.$Total.'</h3>
			  
			  

            </div>
			
			
			
			</div></div><br><br><br>';
			
			
		 $ItemList2 .='	<div class="row">
	 <div class="form-group col-md-3">
		                	<label for="date_created">Date Created</label>
		                	<input type="text" disabled="disabled" class="form-control" id="date_created"  value="'.dateformat($purch_row->date_created).'">				
		                </div>


		                <div class="form-group col-md-3">
		                  <label for="last_modified_by">Created By</label>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="'.$sqlUserDetail.'">				
		                </div>  						
				
						<div class="form-group col-md-3">
		                  <label for="last_modified">Last Updated</label>
		                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="'.dateformat($purch_row->last_modified).'">				
		                </div> 
				
						<div class="form-group col-md-3">
		                  <label for="last_modified_by">Last Updated By</label>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by" value="'.$sqlUserDetail1.'"">				
		                </div>
		                <div class="col-md-2 pull-right">
			   <a type="button" value="Alteration History" class="btn o-btn"  onclick="audittrial(this.value);" >
			    <i class="fas fa-history"></i> Alteration History</a>
		  </div>
	
	</div>'; 		
			
			
			
    echo $ItemList2;

	////////////////////////SubMenu List Reset Session Removed Item

	
	}
?>
