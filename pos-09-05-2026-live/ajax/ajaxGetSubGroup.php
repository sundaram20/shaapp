<?php include_once("../../config/auto_loader.php");
include_once("../include/function.php");
$image_path="images/steward/";

?>
<style>
	.discountvalue{border-radius:1px;width: 40px;float: left;padding: 1px 13px;height: 24px;display:flex;align-items:center;background-color: #585c5c14!important;
}
#listitemName{
	margin-top:5px;
}

#SearchResult{
display:flex;
flex-wrap:wrap;
}

#SearchResult  input {
    padding: 3px!important;
    font-size: 12px;
    margin-right: 1px;
    margin-bottom:1px!important;
    	  height:70px;
  width:83px;
      display: flex;
    justify-content: center;
    flex-wrap: wrap;
    align-items: center;
    text-align: center;
    white-space:break-spaces;
}
.table-fixeditem tbody tr td{

  padding:0!important;
  overflow:hidden;
  margin-right:0px!important;

}
.table-fixeditem tbody tr td a,.table-fixeditem tbody tr td input{
	  height:70px;
  margin-bottom: 0;
	  height:70px;
  width:83px;
  margin-bottom: 0;
 padding:3px!important;
 white-space: pre-wrap!important;
 font-size:12px!important;
margin-bottom: 0!important;
display:flex;
justify-content:center;
align-items:center;
}
 @media only screen and (min-width:768px)  and (max-width:991px){
  .discountvalue{
          height: 36px!important;
    font-size: 24px;
    width: 32px!important;
  }
.btn-danger {
       width:40px!important;
    height:36px!important;
    font-size: 24px!important;

    }

}
 @media only screen and (min-width:991px)  and (max-width:1200px){

.discountvalue{
 height: 23px!important;
    font-size: 18px!important;
    width: 23px!important;
}
.btn-danger{
	width: 40px!important;
    height: 27px!important;
    font-size: 17px!important;

}
 }
</style>
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
function checkreadystatus(){
		alert("Item name already Ready, can't Modify or Delete");
		}
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

 $subGroup.='<div class="col-md-12 p-0">   
              <div class="hr-box">
                  <hr class="m-0">
                  <div class="grouptitle subtext">Sub
                  </div>
               </div>         

            <div class="form-group mb-0">		   

			<form name="listingForm" action="" method="post">               

            <div class="box-body  main2-boxbody table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">

            	<table id="myTableFirst" class="table table-fixedsubmenu table-striped table-bordered dataTable no-footer" cellspacing="0" >

		        <thead style="display:none;">

		            <tr>

                  		<th style="padding: 5px 10px;">Sub</th>

                    </tr>

		        </thead>

		        <tbody class="main2 main2tab col-md-12 owl-carousel" id="mySubGroupCarousel">';

                if($_POST['selectmaingroup']!=''){

					$SubMenuSql	="   AND  id_mst_attributes_group_main='".$_POST['selectmaingroup']."' ";

					} 



	$resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' AND `id_mst_attributes_item_type` = 16 $SubMenuSql GROUP BY id_mst_attributes_group_sub"); 

	  while($row = $db->fetch_object2($resCat)){ 

	  	

				 $SqlAttrbute = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'item_group_sub' AND id= '".addslashes($row->id_mst_attributes_group_sub)."' ORDER BY display_order ASC  ");

								  if($db->num_rows2($SqlAttrbute)){									  

								  	$resultAttrbute = $db->fetch_object2($SqlAttrbute);										

									$subGroup .='<tr><td style="padding:0px;"><input name="selectitemlist" id="selectitemlist_'.$resultAttrbute->id.'" type="button" class="btn btn-success mainmenu_btn" value="'.ucfirst($resultAttrbute->field_value).'" title="'.ucfirst($resultAttrbute->field_value).'" onclick="getItemlist(this.id,\''.$UniqueCodeGen.'\');" style="margin-bottom:5px;padding: 5px 10px;" ></td>';
										$subGroup .='</tr>';
								  }

					  }

                $subGroup .='</tbody>

                

                </table>

                </div>

                

                </form>

                </div></div>

 	 

	 

	 <div class="col-md-12 listgroup1" id="listitemName" style="padding-right: 0px;padding-left: 0px;">

          

         <div class="form-group" style="margin-bottom: 1px;">

		   

			<form name="listingForm" action="" method="post">

               

            <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">





            	<table id="myTableSecond" class="table table-fixeditem table-striped table-bordered dataTable no-footer" cellspacing="0" >

		        <thead>

                <tr>

                  <th class="input-box input-box_menu2" title="Search Menu"> <input type="text" name="keywordsearch" id="keywordsearch"  placeholder="Search Menu"  onKeyUp="keysearch(this.value,\''.$UniqueCodeGen.'\')" >

                     	 <span class="icon">
                              <i class="fas fa-search"></i>
                         </span>
                             <i class="fas fa-close close-icon"></i>
                  </th>

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
					
					$subGroup .='<tr><td style="padding:0px;"><input name="addItemList" id="'.$row->id.'" type="button" class="'.$ClassName.' mainmenu_btn" title="'.ucfirst($row->name).'" value="'.ucfirst($row->name).'"  onclick="selectsubitem(this.id,\''.$_REQUEST['UniqueCodeGen'].'\');" style="margin-bottom:5px;padding: 5px 10px;"></td></tr>';
					
				}else{
					$subGroup .='<tr><td style="padding:0px;"><input name="addItemList" id="addItemList_'.$row->id.'" type="button" class="'.$ClassName.' mainmenu_btn" title="'.ucfirst($row->name).'" value="'.ucfirst($row->name).'"  onclick="AddgetItemlist(this.id,\''.$_REQUEST['UniqueCodeGen'].'\');" style="margin-bottom:5px;padding: 5px 10px;"></td></tr>';
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

 <table id="myTableSecond" class="table table-fixeditem table-striped table-bordered dataTable no-footer" cellspacing="0" >

 <thead>

 <tr>                  

 <th class="input-box input-box_menu3" title="Search Menu">

 <input type="text" name="keywordsearch" id="keywordsearch" onKeyUp="keysearch(this.value,\''.$_REQUEST['UniqueCodeGen'].'\')" placeholder="Search Menu" value="'.$_POST['keywordsearch'].'">
   <span class="icon">
                              <i class="fas fa-search"></i>
                               </span>
                             <i class="fas fa-close close-icon"></i>
      </th>

                

                </tr>

		          </thead>

              <tbody id="SearchResult">';
				
				
				

				if($_POST['selectSubgroup']!=''){

					$MenuSql	=	"AND  id_mst_attributes_group_sub='".$_POST['selectSubgroup']."' ";

					}

				if($_POST['keywordsearch']!=''){

					$MenuSql	=	"AND  name like '%".$_POST['keywordsearch']."%' ";

					}
					if($_POST['id_maingroup']!=''){

					$MenuSql	.=	"AND  id_mst_attributes_group_main='".$_POST['id_maingroup']."' ";

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
					
					$ItemList .='<tr><td style="padding:0px;"><input name="addItemList" id="'.$row->id.'" type="button" class="'.$ClassName.' mainmenu_btn" title="'.ucfirst($row->name).'" value="'.ucfirst($row->name).'"  onclick="selectsubitem(this.id,\''.$_REQUEST['UniqueCodeGen'].'\');" style="margin-bottom:5px;padding: 5px 10px;"></td></tr>';
					
				}else{
					$ItemList .='<tr><td style="padding:0px;"><input name="addItemList" id="addItemList_'.$row->id.'" type="button" class="'.$ClassName.' mainmenu_btn" title="'.ucfirst($row->name).'" value="'.ucfirst($row->name).'"  onclick="AddgetItemlist(this.id,\''.$_REQUEST['UniqueCodeGen'].'\');" style="margin-bottom:5px;padding: 5px 10px;"></td></tr>';
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
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['item_special_request'][$OrderUniqueID]);
				
		}

	$ItemList2	 ='<div class="box1 box-primary1">

        <div class="box-header with-border" style="padding: 2px;
    /* padding-top: 3px; */
    background-color: #e5e2e2;
    text-align: center;
    margin-top: 4px;">

          <h3 class="box-title">Current Order </h3>

        </div>

            

		<form name="listingForm" action="" method="post">
            <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">
            	<table id="myTableOrder" class="table  table-striped table-bordered dataTable no-footer" cellspacing="0" >
		        <thead>
		            <tr>
						  <th width="1%">#</th>
						  <th>Items Name</th>
						  <th width="1%" class="qnty">Qty</th>
						  <th width="1%">Price</th>
						  <th width="1%" >Amount</th>
						  <th width="1%">Action</th>
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
								$_SESSION['POSKOT'][$UniqueCodeGen]['itemID'][$AddsuniqueCode]	=$row->id;
								$_SESSION['POSKOT'][$UniqueCodeGen]['itemID1'][$AddsuniqueCode]	=$rowitemName1->id;
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
							$_SESSION['POSKOT'][$UniqueCodeGen]['itemID1'][$AddsuniqueCode]	=$rowitemName1->id;
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
//$_SESSION['POSKOT'][$UniqueCodeGen]['item_special_request'][$uniqueCode]='Testing';
$k= "'".$uniqueCode."','".$UniqueCodeGen."'";
$ItemList2 .='<tr>

<td>'.$i++.'</td>

<td   class="cursor-pointer" data-toggle="modal" title="Add Special Request" data-target="#itemModalRemarks" onClick="addFormtest('.$k.');">'.$_SESSION['POSKOT'][$UniqueCodeGen]['name'][$uniqueCode].' <div class="itemRemarksLabel" id="itemRemarksLabel'.$uniqueCode.'" style="color:#1874d3">'.$_SESSION['POSKOT'][$UniqueCodeGen]['special_request_name'][$uniqueCode].'</div><div id="itemRemarksLabelInput'.$uniqueCode.'">';


if($_SESSION['POSKOT'][$UniqueCodeGen]['special_request_name'][$uniqueCode]!=''){
	
	$ItemList2 .='<input type="hidden" class="form-control"  name="special_request_name|'.$uniqueCode.'" id="special_request_name|'.$uniqueCode.'" value="'.$_SESSION['POSKOT'][$UniqueCodeGen]['special_request_name'][$uniqueCode].'" >';
	}

$ItemList2 .='</div>


</td>

<td style="display:-webkit-box;">

<a class="btn n-btn btn-sm discountvalue" href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="calculateQuantityPrice($(this).attr(\'id\'),\'0\','.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].',\''.$_REQUEST['UniqueCodeGen'].'\');" style="">

 <i class="fa fa-minus fa-lg"></i> </a>
				  

<input type="text" class="form-control discountvalue quant"  name="quantity|'.$uniqueCode.'" id="quantity|'.$uniqueCode.'" value="'.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].'" onkeypress="return isNumber(event)"   onKeyUp="calculateQuantityPrice(\''.$uniqueCode.'\',\'2\',this.value,\''.$_REQUEST['UniqueCodeGen'].'\');">


<a class="btn n-btn btn-sm discountvalue"  href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="calculateQuantityPrice($(this).attr(\'id\'),\'1\','.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].',\''.$_REQUEST['UniqueCodeGen'].'\');" >



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
$ItemList2 .='<input type="hidden" class="form-control"  name="qty_item_count_'.$uniqueCode.'" id="qty_item_count_'.$uniqueCode.'" value="'.($i-1).'" >';

		  $ItemList2 .='<div class="col-md-6">

		  </div>


		  <div class="col-md-6">

		  <div class="box1 "><div class="box-header with-border"><h3 class="box-title text-success">Total : 

              <i class="fa fa-inr sm"></i> &nbsp;'.$Total.'</h3>

            </div></div></div>';


    $ItemList2 .='<div class="col-md-9"></div>';

		  $ItemList2 .='<div class="col-md-3 c-box"><a class="btn btn-block  c-btn" href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="ajaxUpdateKot($(this).attr(\'id\'),\''.$_REQUEST['UniqueCodeGen'].'\');">

				  <i class="fa fa-save"></i> Save </a></div> ';

	 
	if($remove == 'removeOne'){

////////////////////////SubMenu List Reset Session Removed Item

	$ItemList.='<div id="listitemName">

	

	  <div class="form-group" style="margin-bottom: 1px;">

		   

			<form name="listingForm" action="" method="post">

               

            <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">





            	<table id="myTableSecond" class="table table-fixedsubmenu table-striped table-bordered dataTable no-footer" cellspacing="0" >

		        <thead>

		            <tr>

                  

                  <th style="padding: 5px 10px;"> Menu</th>

				 

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
					
					$ItemList .='<tr><td style="padding:0px;"><input name="addItemList" id="'.$row->id.'" type="button" class="'.$ClassName.' mainmenu_btn" value="'.ucfirst($row->name).'"  onclick="selectsubitem(this.id,\''.$_REQUEST['UniqueCodeGen'].'\');" style="margin-bottom:5px;padding: 5px 10px;"></td></tr>';
					
				}else{
					$ItemList .='<tr><td style="padding:0px;"><input name="addItemList" id="addItemList_'.$row->id.'" type="button" class="'.$ClassName.' mainmenu_btn" value="'.ucfirst($row->name).'"  onclick="AddgetItemlist(this.id,\''.$_REQUEST['UniqueCodeGen'].'\');" style="margin-bottom:5px;padding: 5px 10px;"></td></tr>';
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

				

					echo $ItemList2.'<p style="display:none">__________</p>'.$ItemList;

	////////////////////////SubMenu List Reset Session Removed Item

	}

if($_REQUEST['listsubgroup']==4){

	

	$ItemList2	 ='

        <div class="box-header with-border" style="padding: 2px;
    /* padding-top: 3px; */
    background-color: #e5e2e2;
    text-align: center;
    margin-top: 4px;">

          <h3 class="box-title">Current Order </h3>

        </div>

            <div class="form-group" style="margin-bottom: 1px;">

				<form name="listingForm" action="" method="post">

               

            <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">





            	<table id="myTableOrder" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" >

		        <thead>

		            <tr>

                  <th width="5%">#</th>

                  <th>Items Name</th>

				  <th>Qty</th>

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
$k= "'".$uniqueCode."','".$UniqueCodeGen."'";
	$SubTotal = number_format($SubTotal1 ,2);

$ItemList2 .='<tr>

<td>'.$i++.'</td>

<td   class="cursor-pointer" data-toggle="modal" title="Add Special Request" data-target="#itemModalRemarks" onClick="addFormtest('.$k.');">'.$_SESSION['POSKOT'][$UniqueCodeGen]['name'][$uniqueCode].' <div class="itemRemarksLabel" id="itemRemarksLabel'.$uniqueCode.'" style="color:#1874d3">'.$_SESSION['POSKOT'][$UniqueCodeGen]['special_request_name'][$uniqueCode].'</div><div id="itemRemarksLabelInput'.$uniqueCode.'">';


if($_SESSION['POSKOT'][$UniqueCodeGen]['special_request_name'][$uniqueCode]!=''){
	
	$ItemList2 .='<input type="hidden" class="form-control"  name="special_request_name|'.$uniqueCode.'" id="special_request_name|'.$uniqueCode.'" value="'.$_SESSION['POSKOT'][$UniqueCodeGen]['special_request_name'][$uniqueCode].'" >';
	}

$ItemList2 .='</div>


</td>

<td style="display:-webkit-box;">



<a class="btn n-btn btn-sm discountvalue" href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="calculateQuantityPrice($(this).attr(\'id\'),\'0\','.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].',\''.$_REQUEST['UniqueCodeGen'].'\');" style="">

	<i class="fa fa-minus fa-lg"></i> </a>
				  

<input type="text" class="form-control discountvalue quant"  name="quantity|'.$uniqueCode.'" id="quantity|'.$uniqueCode.'" value="'.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].'"  onkeypress="return isNumber(event)"  style="" onKeyUp="calculateQuantityPrice(\''.$uniqueCode.'\',\'2\',this.value,\''.$_REQUEST['UniqueCodeGen'].'\');">

				  

<a class="btn n-btn btn-sm discountvalue" href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="calculateQuantityPrice($(this).attr(\'id\'),\'1\','.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].',\''.$_REQUEST['UniqueCodeGen'].'\');" >

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
					
					$ItemList .='<tr><td style="padding:0px;"><input name="addItemList" id="'.$row->id.'" type="button" class="'.$ClassName.'" value="'.ucfirst($row->name).'"  onclick="selectsubitem(this.id,\''.$_REQUEST['UniqueCodeGen'].'\');" style="margin-bottom:5px;padding: 5px 10px;"></td></tr>';
					
				}else{
					$ItemList .='<tr><td style="padding:0px;"><input name="addItemList" id="addItemList_'.$row->id.'" type="button" class="'.$ClassName.'" value="'.ucfirst($row->name).'"  onclick="AddgetItemlist(this.id,\''.$_REQUEST['UniqueCodeGen'].'\');" style="margin-bottom:5px;padding: 5px 10px;"></td></tr>';
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
		

$POSCurrentStartDate = date('d-m-Y',strtotime("-3 day", strtotime(date('d-m-Y'))));
		$POSCurrentEndDate 	= 	date('Y-m-d');
	   $CheckBlockedTable_Sql ="SELECT id as id_pos_purch,id_attribute_table,sum(total_qty) as total_qty, sum(total_adj_qty) as total_adj_qty FROM `pos_purch` WHERE id_shop='".$_SESSION['shop']."'   AND `pos_bill_type` = 1  AND doc_type!='24' and cancelled!=1 and (DATE(date_created) BETWEEN '".$POSCurrentStartDate."' and '".$POSCurrentEndDate."' ) and doc_type!='24' and total_qty-total_adj_qty>0 AND id_attribute_table= '".$_REQUEST['id_attribute_table']."' ";
		
  //$CheckBlockedTable_Sql = "SELECT * FROM pos_purch_details WHERE qty-adj_qty>0 AND id_pos_purch  IN  (SELECT id FROM pos_purch WHERE  id_shop='".$_SESSION['shop']."'   AND pos_bill_type= '1'  AND doc_type!='24'  and cancelled=0 AND id_attribute_table= '".$_REQUEST['id_attribute_table']."')";

	                   $db->query($CheckBlockedTable_Sql); 

	                  $ResultBlockedtable1 = $db->fetch_object();

						  

					  $NumOfPax = selectColumn(TBL_PURCH,'pax'," WHERE `id` = '".$ResultBlockedtable1->id_pos_purch."'");

					  $id_attribute_shift	=	 selectColumn(TBL_PURCH,'id_attribute_shift'," WHERE `id` = '".$ResultBlockedtable1->id_pos_purch."'");

					  $id_attribute_steward	=	selectColumn(TBL_PURCH,'id_attribute_steward'," WHERE `id` = '".$ResultBlockedtable1->id_pos_purch."'");

		$id_mst_country_lang	=	selectColumn(TBL_PURCH,'id_mst_country_lang'," WHERE `id` = '".$ResultBlockedtable1->id_pos_purch."'");
			  

  //No Of PAX===============================================================================


  $Pax	='<div class="col-md-12">

        <div class="form-group" style="margin-bottom: 0px !important;">

          <label class="paxlabel" for="name">Paxs<font color="#FF0000">*</font> </label>

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

								$Pax .= '<tr class="paxloadmore"><td class="noofpaxbtn'.$class.'" id="'.$i.'" onclick="SelectNoPaxs(this.id);">'.$i.'</td></tr>';

								  }

						      $Pax .= '<tr class="paxloadbtn" ><td  class="btn" onclick="Paxload()"><i class="fa fa-plus"></i></td></tr>';
 	

								 $Pax	.='</tbody>

              </table>

            </div>

          </div>

        </div>

      </div>';



	  
	  $id_attribute_shiftDefault = selectColumn(TBL_ATTRIBUTES,'id'," WHERE table_name ='shift' and `field_category` = 'default'  ");
	  
	  /*$Pax .='<div class="col-md-3">

        <div class="form-group">

          <!--<label for="name">Shift <font color="#FF0000">*</font> </label>-->

          <div class="input-group1">

           <select class="form-control select2" name="id_attribute_shift" data-parsley-required data-parsley-errors-container="#id_shiftError">

									<option value="">Select Shift</option>';

								  $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'shift'."' ",' ORDER BY `field_value`');

								  if($db->num_rows2($resCat)){

								  	while($resultCat = $db->fetch_object2($resCat)){

										
										if($id_attribute_shiftDefault == $resultCat->id){

											$selected = 'selected="selected"';

										}elseif($id_attribute_shift == $resultCat->id){

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

      </div>'; */

	  

	  $Pax .=  '<div class="col-md-11">

        <div class="form-group" style="margin-bottom: 0px !important;">

         <!-- <label for="name">Steward <font color="#FF0000">*</font> </label>-->

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

											if($resultCat->image!=''){
										$image =  $image_path.$resultCat->image;
										}else{
											$image = "images/steward.png";
										}

										$Pax .=  '<tr><td class="noofpaxbtn bt '.$selected.'" id="'.$resultCat->id.'_'.$resultCat->field_value.'" onclick="SelectSteward(this.id);">'.ucfirst($resultCat->field_value).'<img src="'.$image.'" style="height:53px;border-radius:50%"></td></tr>';

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

  

	$id_mst_country_lang_name	=	selectColumn(TBL_COUNTRY_LANG,'name'," WHERE   status = '1' AND `id_lang` = '1' AND nationality!='' AND `id_country` = '".$id_mst_country_lang."'");

  

	echo $Pax.'EXPLODE'.$NumOfPax.'_'.$id_attribute_shift_name.'_'.$id_attribute_steward.'_'.$id_attribute_steward_name.'_'.$id_mst_country_lang_name.'_'.$id_mst_country_lang.'_text';

		}	

		

if($_POST['listsubgroup']==7){

				 $UniqueCodeGen=$_REQUEST['UniqueCodeGen'];
			
 
		
 $CheckBlockedTable_Sql = mysqli_query($connNew,"SELECT * FROM pos_purch_details WHERE qty-adj_qty>0 AND id_pos_purch  IN  (SELECT id FROM pos_purch WHERE pos_bill_type= '1' and cancelled=0  AND doc_type!='24'  AND id_attribute_table= '".$_REQUEST['id_attribute_table']."')");

	                  // $db->query($CheckBlockedTable_Sql);
					   
					    $i=1;
					  $numRowsExistitem	=	 mysqli_num_rows($CheckBlockedTable_Sql);
					  
					  $Checkgetpurchid_sql = mysqli_query($connNew,"SELECT * FROM pos_purch_details WHERE qty-adj_qty>0 AND id_pos_purch  IN  (SELECT id FROM pos_purch WHERE pos_bill_type= '1'  AND doc_type!='24'  and cancelled=0 AND id_attribute_table= '".$_REQUEST['id_attribute_table']."') group by id_pos_purch");
					  $purchidforshift=array();
					  while($Checkgetpurchid_record = mysqli_fetch_object($Checkgetpurchid_sql)){
						  $purchidforshift[]=$Checkgetpurchid_record->id_pos_purch;
					  }
					  $purchidforshift= implode(',',$purchidforshift);
					  
  	$GetPrevious	='
     <div class="box-header with-border" style="padding-bottom:2px; padding-top:0px;">

     <!-- <h3 class="box-title">Previous Order </h3>-->';
   /* if($numRowsExistitem>0) {
		
		 $ft="openshiftTable('".$_REQUEST['id_attribute_table']."','".$purchidforshift."');";
      $GetPrevious	.=' &nbsp;<button type="button" class="btn btn-primary" onClick="'.$ft.'"   data-toggle="modal" data-target="#adminsOfferform" >
Shift Table
	</button>
	
	  ';
} */
      	$GetPrevious	.='</div>
 
      </div><!--end row-->
 	 <!--popup ends-->
    </div>

    <div class="form-group" style="margin-bottom: 1px;" >

       <div class="box-header with-border" style="padding: 2px;
    /* padding-top: 3px; */
    background-color: #e5e2e2;
    text-align: center;
    margin-top: 4px;">

          <h3 class="box-title">Previous Order </h3>

        </div>

        <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">

          <table id="myTableOrder1" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" >

            <thead>

              <tr>

                <th width="2%"> S.No.&nbsp;</th>

                <th>Items Name</th>

                <th>Qty</th>

                <th>Price</th>

              </tr>

            </thead>

            <tbody>

             ';
//$CheckBlockedTable_Sql = mysqli_query($connNew,

  
	                  while($ResultBlockedtable1 = mysqli_fetch_object($CheckBlockedTable_Sql)){

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

      

    </div';


if($numRowsExistitem>0){
	 $GetPrevious	.='<div class="col-md-3 c-box2" style="margin-top:10px;">

	<input type="submit" value="Go To Bill" class="btn   o-btn" name="Billing" ></input>

	  ';
}



  if($numRowsExistitem>0) {
		
		 $ft="openshiftTable('".$_REQUEST['id_attribute_table']."','".$purchidforshift."');";
      $GetPrevious	.=' &nbsp;<button type="button" class="btn btn-primary" onClick="'.$ft.'"   data-toggle="modal" data-target="#adminsOfferform" >
Shift Table
	</button>
	</div> 
	  ';
} 
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
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['item_special_request']);
		

echo $ItemList5	 ='<div class="box1 box-primary1">

        <div class="box-header with-border" style="padding: 2px;
    /* padding-top: 3px; */
    background-color: #e5e2e2;
    text-align: center;
    margin-top: 4px;">

          <h3 class="box-title">Current Order </h3>

        </div>

            

				<form name="listingForm" action="" method="post">

               

            <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">





            	<table id="myTableOrder" class="table  table-striped table-bordered dataTable no-footer" cellspacing="0" >

		        <thead>

		            <tr>

                  <th width="10%"> S.No.&nbsp;</th>

                  <th>Items Name</th>

				  <th class="qnty">Qty</th>

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
				  <th>Qty</th>
                  <th>Price</th>
				  <th>Amount</th>
				   <th>Cook Status</th>
				   <th>Served</th>

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
				  $_SESSION['POSKOT'][$UniqueCodeGen]['serve_status'][$AddsuniqueCode]	= $ResultBlockedtable1->serve_status;
 				  $_SESSION['POSKOT'][$UniqueCodeGen]['cook_status'][$AddsuniqueCode]	= $ResultBlockedtable1->cook_status;
				   $_SESSION['POSKOT'][$UniqueCodeGen]['id_purch_details'][$AddsuniqueCode]	=$ResultBlockedtable1->id;
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

 $serve_status = $_SESSION['POSKOT'][$UniqueCodeGen]['serve_status'][$uniqueCode];
 $cook_status = $_SESSION['POSKOT'][$UniqueCodeGen]['cook_status'][$uniqueCode];
 if($serve_status==1){
 	$serve_status = "Preparing";
 }elseif($serve_status==2){
 	$serve_status = "Served";
 } elseif($serve_status==0){
	 $serve_status = "Pending";
 }
if($cook_status==0){
 	$cook_status = "Pending";
	$cookStatusReadDisable='onclick="checkItemReadyStatus($(this).attr(\'id\'),\'removeOne\','.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\',\''.$_SESSION['POSKOT'][$UniqueCodeGen]['id_purch_details'][$uniqueCode].'\');"';
	
	//$LinkPlus = 'onclick="calculateQuantityPriceEdit($(this).attr(\'id\'),\'1\','.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].','.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');");"';
	$LinkPlus = '';
	
	 $LinkMinus= 'onclick="calculateQuantityPriceEdit($(this).attr(\'id\'),\'0\','.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].','.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');"';
 }elseif($cook_status==1){
 	$cook_status = "Ready";
	$cookStatusReadDisable='onclick="checkItemReadyStatus($(this).attr(\'id\'),\'removeOne\','.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\',\''.$_SESSION['POSKOT'][$UniqueCodeGen]['id_purch_details'][$uniqueCode].'\');"';
	$LinkPlus = 'onclick="checkreadystatus();"';
	$LinkMinus='onclick="checkreadystatus();"';
 } 
 
$ItemList2 .='<tr>';


if($_SESSION['POSKOT'][$UniqueCodeGen]['kot_status'][$uniqueCode]=='Pending'){
//$ItemList2 .='<td>'.$i++.$uniqueCode.'</td> 
$ItemList2 .='<td>'.$i++.'</td>
<td>'.$_SESSION['POSKOT'][$UniqueCodeGen]['name'][$uniqueCode].'</td>';
$ItemList2 .='<td style="display:-webkit-box;">
<a class="btn n-btn btn-sm discountvalue" href="javascript:void(0);"  id="'.$uniqueCode.'" '.$LinkMinus.');" style="" >
  <i class="fa fa-minus fa-lg"></i> </a>
<input type="text" class="form-control discountvalue quant"  name="quantity|'.$uniqueCode.'" id="quantity|'.$uniqueCode.'" value="'.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].'"  onkeypress="return isNumber(event)"  style="" onKeyUp="calculateQuantityPriceEdit(\''.$uniqueCode.'\',\'2\',this.value,'.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');");">

<a class="btn n-btn btn-sm discountvalue" style="" href="javascript:void(0);"  id="'.$uniqueCode.'" '.$LinkPlus.' >
				  <i class="fa fa-plus fa-lg"></i> </a>
				  </td>';
$ItemList2 .='<td>'.$_SESSION['POSKOT'][$UniqueCodeGen]['price'][$uniqueCode].'</td>

 <td>'.$SubTotal.'</td>';


 
 
 
 $ItemList2 .='<td>'.$cook_status.'</td>';
 $ItemList2 .='<td>'.$serve_status.'</td>';

$ItemList2 .='<td>

<a class="btn btn-danger btn-sm" style="width: 40px;float: left;padding: 1px 12px;height: 24px;display:flex;align-items:center;" href="javascript:void(0);"  id="'.$uniqueCode.'" '.$cookStatusReadDisable.'  >

				  <i class="fas fa-trash-alt"></i> </a>
</td>';

	



}else{
	
$ItemList2 .='<td>'.$i++.'</td>
<td>'.$_SESSION['POSKOT'][$UniqueCodeGen]['name'][$uniqueCode].'</td>';
$ItemList2 .='<td style="display:-webkit-box;">

<input type="text" class="form-control discountvalue quant" disabled="disabled"  name="quantity|'.$uniqueCode.'" id="quantity|'.$uniqueCode.'" value="'.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].'" onkeypress="return isNumber(event)"   style="" onKeyUp="calculateQuantityPriceEdit(\''.$uniqueCode.'\',\'2\',this.value,'.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');">


				  </td>';
$ItemList2 .='<td >'.$_SESSION['POSKOT'][$UniqueCodeGen]['price'][$uniqueCode].'</td>

 <td>'.$SubTotal.'</td>';

 
 
 
 $ItemList2 .='<td>'.$cook_status.'</td>';
 $ItemList2 .='<td>'.$serve_status.'</td>';
$ItemList2 .='<td>

<a class="btn btn-danger btn-sm" href="javascript:void(0);" disabled  id="'.$uniqueCode.'" >

				 <i class="fas fa-trash-alt"></i> </a>
</td>';
	
	}


$ItemList2 .='</tr>';

}				  
		  

$ItemList2 .='</tbody>

		    </table>   

            </div>

			

		  </form>

          </div>';
		  $PaymentStatus	=	checkKOTStatus($_REQUEST['id_pos_purch']);
	if($PaymentStatus!='Pending'){
		$PaymentStatusDisable='disabled';
		$ShowPointStyle='pointer-events:none';
			$StatusOfPaymentis="<div class='content'  style='min-height:0px;'><div class='timeline-footer'>
                  <a class='btn btn-primary btn-xs'>KOT Status:  ".$PaymentStatus."</a>
                 
                </div></div>";
	}
	
	

 $ItemList2 .=$StatusOfPaymentis;
		$UserAccessStatus	= checkUserLevelPermissionButton($_SESSION['userLevel'],TBL_PURCH,'status','managekot.php');
		

		  $ItemList2 .=' <div class="col-md-6 box-cont d-flex"><div class=" c-box">
		  <a class="btn  c-btn " href="javascript:void(0);"  style="'.$ShowPointStyle.'"  '.$PaymentStatusDisable.'  id="'.$uniqueCode.'" onclick="ajaxUpdateKotEdit($(this).attr(\'id\'),\''.$_REQUEST['UniqueCodeGen'].'\');"); ">

								  <i class="far fa-save"></i> Save </a></div>
								  <div class=" c-box">
					<a type="button" value="Close" class="btn c-btn"  onclick="javascript:history.go(-1);" >
					<i class="far fa-window-close"></i> Close
					</a>
					</div>';
				  if($UserAccessStatus=='1'){
					  $ItemList2 .='<div class=" c-box2">	
				<a class="btn btn-block cancelpop_open o-btn" href="javascript:void(0);" onClick="ajaxKOTcancel('.$_REQUEST['id_pos_purch'].');"  id="'.$uniqueCode.'"  >
				<i class="fa fa-times fa-lg"></i> Cancel Kot </a>
				</div>';
				  }
				 
	$ItemList2 .='</div> <!--end of box-cont-->

		  <div class="col-md-6 pull-right">
		  <div class="box1 box-primary"><div class="box-header with-border"><h3 class="box-title text-success">Total : 

              <i class="fa fa-inr"></i> &nbsp;'.$Total.'</h3>

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


	
	$ItemList2 .='<div class="col-md-2 pull-right">
	 <a type="button" value="Alteration History" class="btn o-btn"  onclick="audittrial(this.value);" style="float:right"> <i class="fas fa-history"></i> Alteration History</a>
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

				  <th>Qty</th>

                  <th>Price</th>

				  <th>Amount</th>
				  <th>Cook Status</>
                  <th>Served</>
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
$serve_status = $_SESSION['POSKOT'][$UniqueCodeGen]['serve_status'][$uniqueCode];
 $cook_status = $_SESSION['POSKOT'][$UniqueCodeGen]['cook_status'][$uniqueCode];
 if($serve_status==1){
 	$serve_status = "Preparing";
 }elseif($serve_status==2){
 	$serve_status = "Served";
 } elseif($serve_status==0){
	 $serve_status = "Pending";
 }
if($cook_status==0){
 	$cook_status = "Pending";
	$cookStatusReadDisable='onclick="ajaxRemoveKotEditItemList($(this).attr(\'id\'),\'removeOne\','.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\',\''.$_SESSION['POSKOT'][$UniqueCodeGen]['id_purch_details'][$uniqueCode].'\');"';
	
	//$LinkPlus = 'onclick="calculateQuantityPriceEdit($(this).attr(\'id\'),\'1\','.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].','.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');");"';
	$LinkPlus = '';
	 $LinkMinus= 'onclick="calculateQuantityPriceEdit($(this).attr(\'id\'),\'0\','.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].','.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');"';
 }elseif($cook_status==1){
 	$cook_status = "Ready";
	$cookStatusReadDisable='onclick="checkreadystatus();"';
	$LinkPlus = 'onclick="checkreadystatus();"';
	$LinkMinus='onclick="checkreadystatus();"';
 } 	
$ItemList2 .='<tr>';


if($_SESSION['POSKOT'][$UniqueCodeGen]['kot_status'][$uniqueCode]=='Pending'){
$ItemList2 .='<td>'.$i++.'</td>
<td>'.$_SESSION['POSKOT'][$UniqueCodeGen]['name'][$uniqueCode].'</td>';
$ItemList2 .='<td>
<a class="btn n-btn btn-sm discountvalue" href="javascript:void(0);"  id="'.$uniqueCode.'" '.$LinkMinus.');" style="">
  <i class="fa fa-minus fa-lg"></i> </a>
  
  
<input type="text" class="form-control discountvalue quant"  name="quantity|'.$uniqueCode.'" id="quantity|'.$uniqueCode.'" value="'.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].'"   style=""  onkeypress="return isNumber(event)" onKeyUp="calculateQuantityPriceEdit(\''.$uniqueCode.'\',\'2\',this.value,'.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');">


<a class="btn n-btn btn-sm discountvalue" style="" href="javascript:void(0);"  id="'.$uniqueCode.'" '.$LinkPlus.' >
				  <i class="fa fa-plus fa-lg"></i> </a>
				  </td>';
$ItemList2 .='<td>'.$_SESSION['POSKOT'][$UniqueCodeGen]['price'][$uniqueCode].'</td>

 <td>'.$SubTotal.'</td>';


 
 
 
 $ItemList2 .='<td>'.$cook_status.'</td>';
 $ItemList2 .='<td>'.$serve_status.'</td>';
 
$ItemList2 .='<td>

<a class="btn btn-danger btn-sm" style="width: 40px;float: left;padding: 1px 12px;height: 24px;display:flex;align-items:center;" href="javascript:void(0);"  id="'.$uniqueCode.'"  '.$cookStatusReadDisable.' >

				  <i class="fas fa-trash-alt"></i> </a>
</td>';
 
}else{
$ItemList2 .='<td>'.$i++.'</td>
<td>'.$_SESSION['POSKOT'][$UniqueCodeGen]['name'][$uniqueCode].'</td>';
$ItemList2 .='<td>

<input type="text" class="form-control discountvalue quant" disabled="disabled"  name="quantity|'.$uniqueCode.'" id="quantity|'.$uniqueCode.'" value="'.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].'"  onkeypress="return isNumber(event)"  style="width: 40px;float: left;padding: 1px 12px;height: 24px;" onKeyUp="calculateQuantityPriceEdit(\''.$uniqueCode.'\',\'2\',this.value,'.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');");">


				  </td>';
$ItemList2 .='<td>'.$_SESSION['POSKOT'][$UniqueCodeGen]['price'][$uniqueCode].'</td>

 <td>'.$SubTotal.'</td>';

$ItemList2 .='<td>

<a class="btn btn-danger btn-sm" href="javascript:void(0);" disabled  id="'.$uniqueCode.'" >

				   <i class="fas fa-trash-alt"></i> </a>
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


		  $ItemList2 .=' <div class="col-md-6 box-cont d-flex"><div class=" c-box"><a class="btn btn-block c-btn " href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="ajaxUpdateKotEdit($(this).attr(\'id\'),\''.$UniqueCodeGen.'\');">

<i class="far fa-save fa-lg"></i> Save </a></div>
<div class="col-md-2 c-box">
	<a type="button" value="Close" class="btn c-btn"  onclick="javascript:history.go(-1);" ><i class="far fa-window-close"></i> Close
	</a>
	</div>
	</div>
	<!--end of box-cont-->
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
				unset($_SESSION['POSKOT'][$UniqueCodeGen]['item_special_request'][$OrderUniqueID]);
		       unset($_SESSION['POSKOT'][$UniqueCodeGen]['id_purch_details'][$OrderUniqueID]);
				
		}

	$ItemList2	 ='<div class="box1 box-primary1">

        <div class="box-header with-border" style="padding: 2px;
    /* padding-top: 3px; */
    background-color: #e5e2e2;
    text-align: center;
    margin-top: 4px;">

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

				  <th>Qty</th>

                  <th>Price</th>

				  <th>Amount</th>
				  <th>Cook Status</th>
                   <th>Served </th>
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
	
	
$serve_status = $_SESSION['POSKOT'][$UniqueCodeGen]['serve_status'][$uniqueCode];
 $cook_status = $_SESSION['POSKOT'][$UniqueCodeGen]['cook_status'][$uniqueCode];
 if($serve_status==1){
 	$serve_status = "Preparing";
 }elseif($serve_status==2){
 	$serve_status = "Served";
 } elseif($serve_status==0){
	 $serve_status = "Pending";
 }
 if($cook_status==0){
 	$cook_status = "Pending";
	$cookStatusReadDisable='onclick="ajaxRemoveKotEditItemList($(this).attr(\'id\'),\'removeOne\','.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\',\''.$_SESSION['POSKOT'][$UniqueCodeGen]['id_purch_details'][$uniqueCode].'\');"';
	
	//$LinkPlus = 'onclick="calculateQuantityPriceEdit($(this).attr(\'id\'),\'1\','.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].','.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');");"';
	
	$LinkPlus = '';
	 $LinkMinus= 'onclick="calculateQuantityPriceEdit($(this).attr(\'id\'),\'0\','.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].','.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');"';
 }elseif($cook_status==1){
 	$cook_status = "Ready";
	$cookStatusReadDisable='onclick="checkreadystatus();"';
	$LinkPlus = 'onclick="checkreadystatus();"';
	$LinkMinus='onclick="checkreadystatus();"';
 } 
$ItemList2 .='<tr>';	
if($_SESSION['POSKOT'][$UniqueCodeGen]['kot_status'][$uniqueCode]=='Billed'){
 
 $ItemList2 .='<td>'.$i++.'</td>
<td>'.$_SESSION['POSKOT'][$UniqueCodeGen]['name'][$uniqueCode].'</td>';
$ItemList2 .='<td>

<input type="text" class="form-control discountvalue quant" disabled="disabled"  name="quantity|'.$uniqueCode.'" id="quantity|'.$uniqueCode.'" value="'.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].'"  onkeypress="return isNumber(event)" ; onKeyUp="calculateQuantityPriceEdit(\''.$uniqueCode.'\',\'2\',this.value,'.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');">

 </td>';
 
 
 
 
 
 
$ItemList2 .='<td>'.$_SESSION['POSKOT'][$UniqueCodeGen]['price'][$uniqueCode].'</td>

 <td>'.$SubTotal.'</td>';

 
 
 
 $ItemList2 .='<td>'.$cook_status.'</td>';
 $ItemList2 .='<td>'.$serve_status.'</td>';
$ItemList2 .='<td>

<a class="btn btn-danger btn-sm" href="javascript:void(0);" disabled  id="'.$uniqueCode.'" >
  <i class="fas fa-trash-alt"></i> </a>
</td>';
 }else{

$ItemList2 .='<td>'.$i++.'</td>
<td>'.$_SESSION['POSKOT'][$UniqueCodeGen]['name'][$uniqueCode].'</td>
<td style="display:-webkit-box;" disabled="disabled">
<a class="btn n-btn btn-sm discountvalue" href="javascript:void(0);"  id="'.$uniqueCode.'" '.$LinkMinus.' >

				  <i class="fa fa-minus fa-lg"></i> </a>

<input type="text" class="form-control  discountvalue quant"  name="quantity|'.$uniqueCode.'" id="quantity|'.$uniqueCode.'" value="'.$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].'"  onkeypress="return isNumber(event)"  onKeyUp="calculateQuantityPriceEdit(\''.$uniqueCode.'\',\'2\',this.value,'.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\');");">

				  

<a class="btn n-btn btn-sm discountvalue" href="javascript:void(0);"  id="'.$uniqueCode.'" '.$LinkPlus.'  >

				  <i class="fa fa-plus fa-lg"></i></a> </td>

				  <td>'.$_SESSION['POSKOT'][$UniqueCodeGen]['price'][$uniqueCode].'</td>	

				  <td>'.$SubTotal.'</td>';

 
 
 
 
 $ItemList2 .='<td>'.$cook_status.'</td>';
 $ItemList2 .='<td>'.$serve_status.'</td>';

 $ItemList2 .='<td><a class="btn btn-danger btn-sm"  style="width: 40px;float: left;padding: 1px 12px;height: 24px;display:flex;align-items:center;" href="javascript:void(0);"  id="'.$uniqueCode.'"   >

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
		  
		  
		  
		  
		  

		  $ItemList2 .='<div class="col-md-6 box-cont d-flex"><div class=" c-box"><a class="btn  c-btn" href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="ajaxUpdateKotEdit($(this).attr(\'id\'),\''.$_REQUEST['UniqueCodeGen'].'\');");">

				  <i class="fa fa-save "></i> Save </a></div> ;

	<div class="c-box">
	<a type="button" value="Close" class="btn c-btn"  onclick="javascript:history.go(-1);" >
		<i class="far fa-window-close"></i> Close
	</a>
	</div>
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





            	<table id="myTableSecond" class="table table-fixeditem table-striped table-bordered dataTable no-footer" cellspacing="0" >

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
					
					$ItemList .='<tr><td style="padding:0px;"><input name="addItemList" id="'.$row->id.'" type="button" class="'.$ClassName.'" value="'.ucfirst($row->name).'"  onclick="selectsubitem(this.id,\''.$_REQUEST['UniqueCodeGen'].'\');" style="margin-bottom:5px;padding: 5px 10px;"></td></tr>';
					
				}else{
					$ItemList .='<tr><td style="padding:0px;"><input name="addItemList" id="addItemList_'.$row->id.'" type="button" class="'.$ClassName.'" value="'.ucfirst($row->name).'"  onclick="AddgetItemlist(this.id,\''.$_REQUEST['UniqueCodeGen'].'\');" style="margin-bottom:5px;padding: 5px 10px;"></td></tr>';
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
				  <th>Qty</th>
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
				$ItemList2 .='<td style="">
				<input type="text" class="form-control discountvalue quant"  name="quantityview" id="quantityview" disabled="disabled" value="'.round($ResultBlockedtable1->qty).'"  onkeypress="return isNumber(event)"   onKeyUp="calculateQuantityPriceEdit(\''.$uniqueCode.'\',\'2\',this.value,'.$_REQUEST['id_pos_purch'].');");">
				
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

              <i class="fa fa-inr"></i> &nbsp;'.$Total.'</h3>
			  
			  

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
		               <div class="col-md-2 pull-right">
	 <a type="button" value="Alteration History" class="btn o-btn"  onclick="audittrial(this.value);" style="float:right"> <i class="fas fa-history"></i> Alteration History</a>
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
				  <th class="qnty">Qty</th>
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
				<input type="text" class="form-control discountvalue quant"  name="quantityview" id="quantityview" disabled="disabled" value="'.round($ResultBlockedtable1->qty).'"  onkeypress="return isNumber(event)"  style="width: 40px;float: left;padding: 1px 12px;height: 24px;" onKeyUp="calculateQuantityPriceEdit(\''.$uniqueCode.'\',\'2\',this.value,'.$_REQUEST['id_pos_purch'].');");">
				
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

echo $ItemList2 = '
<script>
	jQuery(document).ready(function($){
  $(".paxloadbtn").click(function(e){
    $(".paxloadmore:hidden").slice(0,10).fadeIn();
    if ($(".paxloadmore:hidden").length < 1) $(this).fadeOut();
  })
})
</script>



<script>
  var $owl = $("#mySubGroupCarousel");
var owl = $owl.owlCarousel({
  autoplay: false,
  dots: false,
  loop: false,
  autoWidth:true,
  nav: true,

  navText: [ "<i class=\"fa fa-chevron-left\"></i>",
             "<i class=\"fa fa-chevron-right\"></i>" ],
 // responsiveBaseElement: ".main2",
  responsive : {
    0 : {
      items: 3,
      slideBy:3
    },
    400 : {
      items: 4,
      slideBy:4
    },

     505 : {
      items: 5,
      slideBy:5
    },
   
    575 : {
      items: 7,
      slideBy: 7
    },

    769 : {
      items: 8,
      slideBy: 8
    },
    992 : {
      items:8,
      slideBy:8
    },
    1200 : {
     items: 10,
     slideBy:10
    },
      1500 : {
     items: 12,
     slideBy:12
    },
  },
});

//menu searchbar 
    var inputBox22 = document.querySelector(".input-box"),
                searchIcon22 = document.querySelector(".icon"),
                closeIcon22 = document.querySelector(".close-icon");

            searchIcon22.addEventListener("click", () => inputBox22.classList.add("open"));
            closeIcon22.addEventListener("click", () => inputBox22.classList.remove("open"));

 function searchMenu(x) {
  if (x.matches) { 
      inputBox22.classList.add("open"); 
     }
}

var x = window.matchMedia("(max-width: 991px)");
searchMenu(x);
x.addListener(searchMenu); 
</script>';


?>
