<?php include_once("../config/auto_loader.php");
include_once("include/pos_function.php");
include_once("include/function.php"); ?>
<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<link rel="stylesheet" href="<?php echo $SITE_URL; ?>/pos/css/style.css"/>

<style>
 body{font: 12px 'Segoe UI', Tahoma, Arial, Helvetica, sans-serif;}   

#invoice-POS{
	 
  box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
  padding:2mm;
  margin: 0 auto;
  width: 80mm;
  background: #FFF;
  
  
::selection {background: #f31544; color: #FFF;}
::moz-selection {background: #f31544; color: #FFF;}
h1{
  font-size: 1.5em;
  color: #222;
}
h2{font-size: .9em;}
h3{
  font-size: 1.2em;
  font-weight: 300;
  line-height: 2em;
}
h4{
  font-size: 3px;
  font-weight: bold;
  line-height: 2em;
}
p{
  font-size: .7em;
  color: #666;
  line-height: 1.2em;
}
 
#top, #mid,#bot{ /* Targets all id with 'col-' */
  border-bottom: 1px solid #EEE;
}

#top{min-height: 100px;}
#mid{min-height: 80px;} 
#bot{ min-height: 50px;}

#top .logo{
  //float: left;
	height: 60px;
	width: 60px;
	background: url(http://michaeltruong.ca/images/logo1.png) no-repeat;
	background-size: 60px 60px;
}
.clientlogo{
  float: left;
	height: 60px;
	width: 60px;
	background: url(http://michaeltruong.ca/images/client.jpg) no-repeat;
	background-size: 60px 60px;
  border-radius: 50px;
}
.info{
  display: block;
  //float:left;
  margin-left: 0;
}
.title{
  float: right;
}
.title p{text-align: right;} 
table{
  width: 100%;
  border-collapse: collapse;
}
td{
  p
  ing: 5px 0 5px 15px;
  border: 1px solid #EEE;
}
.tabletitle{
  padding: 5px;
  font-size: .5em;
  background: #EEE;
}
.service{border-bottom: 1px solid #EEE;}
.item{width: 24mm;}
.itemtext{font-size: .5em;}

#legalcopy{
  margin-top: 5mm;
}

}
.kw-box {
    height: 100%;
    overflow-y: scroll;
    overflow-x: hidden;
}

input:checked + .slider {
    background-color: #3cbc58;
}
.noresult{
	padding: 20px 0;
    font-size: 18px;
    font-weight: 500;
    color: #f56616;
}
@media only screen and (max-width:575px){
	
 #searchForm{
    overflow-y: scroll;
    /* position: fixed; */
    left: 11;
    z-index: 11;
    display: -webkit-inline-box;
    margin-top: -30px;
    margin-left: -15px;
    padding: 12px;
    padding-bottom: 0;
}
}
</style>
<?php 
$pos_purch_id	=	encryptor(decrypt, $_REQUEST['printPreviewid']);
?>
  <div class="content-wrapper"> 
    
    <!-- Content Header (Page header) -->
    
    <section class="content-header">
	
	<?php //echo encryptor(decrypt, $_REQUEST['printPreviewid']); ?>
      <!--<h1> KOT Print  </h1>-->
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Table View </li>
      </ol>
    </section>
    
    <!-- Main content -->
    
    <section class="content pt-0">
  
    <div class="row">

<?php
if($session=='178'){
	$list = 'manageKot.php';
}
if($session=='179'){
	$list = 'manageKotNc.php';
}
$NewKot	='managePosKot.php';
?>

 <div class="form-group col-xs-3 col-md-1 col-sm-2 c-box">
	<a href="<?php echo $NewKot; ?>?submenu=<?php echo $_REQUEST['submenu'] ?>&session=<?php echo $_REQUEST['session'] ?> ">
	  <div class="btn c-btn btn-block" style="margin-right:15px" ><i class="fa fa-pencil fa-1x"></i> Add</div >
	 </a>
</div>



<!--<div class="form-group col-xs-3 col-md-1 col-sm-2 c-box">

<?php
if($_REQUEST['printPreviewid']==''){
	$id=$_REQUEST['updateid'];
}else{
	$id=$_REQUEST['printPreviewid'];
}
?>
	<!--<a href="editKot.php?editKotid=<?php echo $id ?>&submenu=<?php echo $_REQUEST['submenu'] ?>&staus=edit&session=<?php echo $_REQUEST['session'] ?> ">
		<div class="btn c-btn btn-block" style="margin-right:15px" ><i class="fa fa-pencil-square-o fa-1x"></i> Edit</div >
	</a>--
</div>	-->
				
<div class="form-group col-xs-3 col-md-1 col-sm-2 c-box">
	<a href="<?php echo $list ?>?submenu=<?php echo $_REQUEST['submenu'] ?>&session=<?php echo $_REQUEST['session'] ?> ">
	  <div class="btn c-btn btn-block" style="margin-right:15px" ><i class="fa fa-list fa-1x"></i> List</div >
	 </a>
</div>

<!--
   <div class="form-group col-xs-3 col-md-1 col-sm-2 c-box">
<button class="btn c-btn btn-block" style="margin-right:15px" onClick="printdiv('div_print');"><i class="fa fa-print fa-1x"></i> Print &nbsp;&nbsp;&nbsp;</button>
</div>  -->
<!--
<div class="form-group col-xs-12 col-md-3  ">
<div class="btn-group " style="margin-left:6px;" >&nbsp; <a type="button" class="btn c-btn2" href="javascript:void(0)"><i class="fa fa-fw fa-cloud-download"></i> Export</a>
    <a type="button" class="btn o-btn dropdown-toggle" data-toggle="dropdown" > 
    <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </a>
    <ul class="dropdown-menu " role="menu">
      <li><a title="Export to excel file" onClick="downloadExcelPdf(2);" href="javascript:void(0)"><img src="../images/excel-icon.jpg" width="20" height="20">&nbsp;Excel</a></li>
      <li><a title="Export to pdf file" onClick="downloadExcelPdf(3);" href="javascript:void(0)"><img src="../images/pdf.jpg" width="20" height="20">&nbsp;Pdf</a></li>
       <li><a title="Export to JPG file" onClick="downloadExcelPdf(3);" href="javascript:void(0)"><i class="fas fa-file-image"></i>&nbsp;JPG</a></li>
    </ul>
  </div>

<div class="btn-group" style="margin-left:-4px;"> <a type="button" class="btn c-btn2" href="javascript:void(0)"><i class="fa fa-fw fa-cloud-download"></i> Share</a>
    <a type="button" class="btn o-btn dropdown-toggle" data-toggle="dropdown" > 
    <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </a>
    <ul class="dropdown-menu " role="menu">
      <li><a title="Share on Email" onClick="downloadExcelPdf(2);" href="javascript:void(0)"><i class="fas fa-envelope-open-text"></i>&nbsp;Email</a></li>
      <li><a title="Share on Whatsapp" onClick="downloadExcelPdf(3);" href="javascript:void(0)"><i class="fab fa-whatsapp"></i>&nbsp;Whatsapp</a></li>
    </ul>
  </div>
  &nbsp;&nbsp;  </div>-->
  
<script>  
	$(document).ready(function(){
		$('.without_search').show();
		$('.with_search').hide();
	});	

	function search_form(clicked_id){
		if(clicked_id.length==0){
			document.getElementById("search_div").innerHTML='';
			return;
		}else{
			var search_name = document.getElementById("search_name").value;
			var table = document.getElementById("id_attribute_table_search").value;
			var order = document.getElementById("order").value;
			var fstatus = document.getElementById("status").value;
			
			$('.without_search').hide();
			$('.with_search').show();
        	var formData = $("#searchForm").serialize();
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if(this.readyState == 4 && this.status == 200){
					document.getElementById("search_div").innerHTML = this.responseText;
				}
			};
			xmlhttp.open("GET", "ajax/ajaxUpdatestatus.php?search_name=" + search_name + "&id_attribute_table_search=" + table + "&order=" + order + "&fstatus=" + fstatus, true);
			xmlhttp.send();
		}
	}
</script>

  
<form name="searchForm" id="searchForm">
    <input type="hidden" value="1" name="searchFormSubmit" />
    <input type="hidden" value="<?php echo $_GET['session'] ?>" name="session" />
    <input type="hidden" value="<?php echo $_GET['submenu'] ?>" name="submenu" />
	
	<div class="kw-filter" >
		<div id="myTable_filter" class="dataTables_filter"><!--<label>Search Table : </label>-->
		<?php $categoryDropDown = '<select class="form-control select2" name="id_attribute_table_search" id="id_attribute_table_search" onChange="listPendingKot();" >
					<option value="">All Table</option>';
				  $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'table'."' ",' ORDER BY `id` asc');
				  if($db->num_rows2($resCat)){
					while($resultCat = $db->fetch_object2($resCat)){
						if($_REQUEST['id_attribute_table'] == $resultCat->id){
							$selected = 'selected="selected"';
						}else{
							$selected = '';
						}
						$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
					}
				  }
					echo $categoryDropDown .= '</select>';
	 ?>
		<?php /*?><input type="search" class="" placeholder="" aria-controls="myTable"><?php */?></div>
	 
		<div class="kw-status">
		  <!--  <label>Status :</label>--> 
			   <select class="form-control" name="status" id="status">
					<option value="Pending">Pending</option>
					<option value="Preparing">Preparing</option>
					<option value="Ready">Ready</option>
			  </select>
		  
	  </div>

		<div class="kw-priority">
			<!-- <label>Order By : </label>-->
				 <select class="form-control" name="order" id="order">
					<option value="Newest">Newest</option>
					<option value="Oldest">Oldest</option>
					<option value="Top Priority">Top Priority</option>
				 </select>
			
		</div>
	 
		<div class="kw-search">
			<!--<label>KOT NO :  </label>-->
			   <input type="text" name="search_name" id="search_name" class="form-control" placeholder="KOT NO">
		</div>
		
		 <div class="">	
			<input name="Search" type="button" class="btn o-btn" value="Apply" onclick="search_form(this.value);" style="padding:7px 13px;" /> 
		 </div>
	</div>
</form>


<?php //debugData($_REQUEST);?>
<!--drop down-->
<!--<div class="btn-group  pull-right"  >
		  <a type="button" class="btn c-btn2" href="#" ><i class="fas fa-share-square"></i> Share</a>
			<button type="button" style="background: #f56616;" class="btn  dropdown-toggle" data-toggle="dropdown">
			<span class="caret"></span>
			<span class="sr-only">Toggle Dropdown</span>
		  </button>
		  <ul class="dropdown-menu" role="menu">
			<?php ?><li><a title="Share on Email" href=""><i class="fas fa-envelope-open-text"></i>&nbsp;Email</a></li>
	            <li><a title="Share on Whatsapp" href=""><i class="fab fa-whatsapp"></i>&nbsp;Whatsapp</a></li>
			<?php ?>
		  </ul> 
		</div>
 			<div class="btn-group  pull-right" style="margin-right: 15px;">
		  <a type="button" class="btn c-btn2" href="#" ><i class="fas fa-file-download"></i> Export</a>
			<button type="button" style="background: #f56616;" class="btn  dropdown-toggle" data-toggle="dropdown">
			<span class="caret"></span>
			<span class="sr-only">Toggle Dropdown</span>
		  </button>
		  <ul class="dropdown-menu" role="menu">
			<?php ?><li><a title="Export to excel file" href=""><i class="far fa-file-excel"></i>&nbsp;Excel</a></li>
	            <li><a title="Export to csv file" href=""><i class="far fa-file-pdf"></i>&nbsp;Pdf</a></li>
	             <li><a title="Export to csv file" href=""><i class="far fa-file-image"></i>&nbsp;JPG</a></li>
	             
			<?php ?>
		  </ul> 
		</div>&nbsp;&nbsp;-->
<!--End of button-->

<div id="search_div" class="with_search"> </div>

    <div class="col-xs-12 without_search">
		<!--  <div class="col-md-3 c-box2" style="margin-top:10px;">
			<input type="submit" value="Go To Bill" class="btn btn-block  o-btn" name="Billing" ></input>
		</div>-->

	<script>  
	
	
const curr_user = Math.random().toString(32).substring(2,10)+Math.random().toString(32).substring(2,30);
console.log(curr_user);
	
		$(document).ready(function(){
			alert('1');
			var websocket = new WebSocket("wss://echo.websocket.org"); 
			//var websocket = new WebSocket("wss://3.111.0.237:8443"); 
			websocket.onopen = function(event) { 
				console.log("Connection is established!");		
			}
			
			websocket.onmessage = function(event) {
				var Data = JSON.parse(event.data);
				console.log(Data);
				if(Data.chat_user == curr_user){
					window.location.reload();
					return;
				}
				if((Data.chat_user != null) && (Data.message_type == 'event')){
					 //window.appCalendar.refetchEvents();
					//alert('hlo');
				}
			};
			
			websocket.onerror = function(event){
				console.log("Problem due to some Error");
			};
			websocket.onclose = function(event){
				console.log("Connection Closed");
			};
			
			var messageJSON = {
				chat_user: curr_user,
				chat_message: 'new event added'
			};
			websocket.send(JSON.stringify(messageJSON));
			
			
			$('.clickmenu').hide();
			$('.hidemenu').show();
			
		});	

		function showHint(clicked_id, qty, old_qty){
			//alert();
			var substr = clicked_id.split('-');
			var str = substr[0];
			var pos_purch_details_id = substr[1];
			var check_id = $('#detail_status-'+pos_purch_details_id).is(':checked');
			
			if(check_id){
				var status = '1';
			}else{
				var status = '0';
			}
			
			if(clicked_id.length==0){
				document.getElementById("txtHint").innerHTML='';
				return;
			}else{
				$('.hidemenu').hide();
				$('.clickmenu').show();
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if(this.readyState == 4 && this.status == 200){
						document.getElementById("txtHint").innerHTML = this.responseText;
					}
				};
				alert('111');
				xmlhttp.open("GET", "ajax/ajaxGetkotwise.php?qty=" + qty + "&id=" + pos_purch_details_id + "&status=" + status + "&old_qty=" + old_qty, true);
				xmlhttp.send();
				alert('check');
			//var websocket = new WebSocket("wss://3.111.0.237:8443");
			var websocket = new WebSocket("ws://echo.websocket.org"); 
			websocket.onopen = function(event) { 
				console.log("Connection is established!");		
			}
			
			websocket.onmessage = function(event) {
				var Data = JSON.parse(event.data);
				console.log(Data);
				if(Data.chat_user == curr_user){
					window.location.reload();
					return;
				}
				if((Data.chat_user != null) && (Data.message_type == 'event')){
					alert('click');
					xmlhttp.open("GET", "ajax/ajaxGetkotwise.php?qty=" + qty + "&id=" + pos_purch_details_id + "&status=" + status + "&old_qty=" + old_qty, true);
					xmlhttp.send();
				}
			};
			
			websocket.onerror = function(event){
				console.log("Problem due to some Error");
			};
			websocket.onclose = function(event){
				console.log("Connection Closed");
			};
				
			var messageJSON = {
				chat_user: curr_user,
				chat_message: 'new event added'
			};
			websocket.send(JSON.stringify(messageJSON));
			
			
			}
		}		
	</script>
	
        <div class="box pb-70">  
			<div class="row kw-cons">
				<div class="col-md-10 kw-leftbar">
					<?php 
					$menudate = "DATE(`doc_date`) BETWEEN '".date('Y-m-d',strtotime('-3 days'))."' And '".date('Y-m-d')."'";
					//$menudate = "DATE(`doc_date`) BETWEEN '2022-08-05' And '".date('Y-m-d')."'";
					
					$menudate_main = " AND DATE(`doc_date`) BETWEEN '".date('Y-m-d',strtotime('-3 days'))."' And '".date('Y-m-d')."'";
					//$menudate_main = " AND DATE(`doc_date`) BETWEEN '2022-08-05' And '".date('Y-m-d')."' ";
					
					if($_REQUEST['search_name'] != ''){
						$searchDocumentType = " AND pp.`mdoc_no` ='".addslashes($_REQUEST['search_name'])."'";

					}
						
					/*$SQL="SELECT * from
					( select pp.*,  
						   (case  when COALESCE(pp.cancelled)=1 then 'cancelled'
								  when COALESCE(ppp.qty-ppp.adj_qty)>0 then 'Pending'
								 when COALESCE(ppp.qty-ppp.adj_qty)=0 then 'Billed' end) as kot_status
					 
					 from pos_purch pp left join pos_purch_details ppp on ppp.id_pos_purch=pp.id 
					 where id_shop= '".addslashes($_SESSION['shop'])."' AND pp.pos_bill_type=1 AND pp.doc_type=22 
					 $searchDocumentType 
					 group by pp.id ORDER BY pp.`last_modified` desc
					 
					 )as managekotlist WHERE id!=0 ".$menudate_main." 
					";*/
					
					$SQL="SELECT *  from
(select pp.*, ppp.id_mst_items ,ppp.item_description,ppp.qty,ppp.id as id_pos_purch_details,ppp.old_qty,ppp.check_orderis_ready,
	   (case  when COALESCE(pp.cancelled)=1 then 'cancelled'
	   		  when COALESCE(ppp.qty-ppp.adj_qty)>0 then 'Pending'
	         when COALESCE(ppp.qty-ppp.adj_qty)=0 then 'Billed' end) as kot_status
 
 from pos_purch pp right join pos_purch_details ppp on ppp.id_pos_purch=pp.id 
 where id_shop= '".addslashes($_SESSION['shop'])."' AND pp.pos_bill_type=1 AND pp.doc_type=22 
 $searchDocumentType 
  ORDER BY pp.`last_modified` desc
 
 )as managekotlist WHERE id!=0 ".$menudate_main." 
";

					//echo $SQL;

						$SqlKotList = mysqli_query($connNew, $SQL); 
						$numRows =	mysqli_num_rows($SqlKotList);

						$i=1;
						$listPrintArray = array();
						$listprintHeaderArray = array();
						$pendingKotArray = array();
						while($row = mysqli_fetch_object($SqlKotList)){ 
											  
							$table_name=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'table' AND id= '".$row->id_attribute_table."'"); 
							$shift_name=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'shift' AND id= '".$row->id_attribute_shift."'"); 
							$steward_name=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'steward' AND id= '".$row->id_attribute_steward."'"); 
							$row->mdoc_no;
							$datetime = new DateTime($row->date_created);
							$time = $datetime->format('h:i A');
							
											
							$pendingKotArray[$row->id_attribute_table]['table_name']=$table_name;
							$pendingKotArray[$row->id_attribute_table]['steward_name']=$steward_name;	
							$pendingKotArray[$row->id_attribute_table]['mdoc_no']=$row->mdoc_no;	
							$pendingKotArray[$row->id_attribute_table]['time']=$time;	
							
							
							
							$qty = $row->qty;
							$old_qty = $row->old_qty;
							$check_orderis_ready = (int)$row->check_orderis_ready;
							
							if($check_orderis_ready>0){
								$checked = "checked";
							}else{
								$checked = "";
							}
							if($qty=='0' || $old_qty>'0'){
								$tot_qty = $old_qty;
							}else{
								$tot_qty = $qty;
							}
							
							$listPrintArray[$row->id_attribute_table][$row->id_pos_purch_details]['item_description']=$row->item_description;
							$listPrintArray[$row->id_attribute_table][$row->id_pos_purch_details]['id_pos_purch_details']=$row->id_pos_purch_details;
							$listPrintArray[$row->id_attribute_table][$row->id_pos_purch_details]['check_orderis_ready']=$row->check_orderis_ready;
							$listPrintArray[$row->id_attribute_table][$row->id_pos_purch_details]['qty']=$row->qty;
							$listPrintArray[$row->id_attribute_table][$row->id_pos_purch_details]['old_qty']=$old_qty;
							$listPrintArray[$row->id_attribute_table][$row->id_pos_purch_details]['checked']=$checked;
							$listPrintArray[$row->id_attribute_table][$row->id_pos_purch_details]['tot_qty']=$tot_qty;
							$listPrintArray[$row->id_attribute_table][$row->id_pos_purch_details]['KotNo']=$row->mdoc_no;
							$listPrintArray[$row->id_attribute_table][$row->id_pos_purch_details]['id_attribute_table']=$row->id_attribute_table;
							} 
							 
							foreach($pendingKotArray as $Table=>$TableDetails){ 
						?>			  
			  
		        	 	<!--tab col starts-->
						<div class="col-md-2 kw-con">
						  <div class="tab-container">
						    <div class="tabbox">
						      <div class="tabheading" id="checkcont">
						        <div class=" d-flex">
							          <div class="tbsteward">
								            <div class="statusbox">
									           <!-- <div class="d-flex ">
									            	<h4><button class="kotwiseModal"  id="kwstatus" ><?php echo $row->kot_status; ?></button></h4>
									            	<input type="hidden" id="pos_id" value="<?php echo $row->id; ?>"> 
												</div>-->
								            </div>
								            <h4 TITLE="Table"><?php echo $TableDetails['table_name']; ?></h4> <h4 title="Time"><?php echo $TableDetails['time']; ?></h4>     
							          </div>
						         </div>        
						      </div>
						      <!--table heading ends-->
						      <div class="tabcontent" id="kwbox" > 
						        <table class="table table-responsive table table-striped table-bordered dataTable no-footer songs-table">
									<thead style="display:none;">
										<tr>
										  <th>  Items Name</th>
										  <th style="width:10%;"> Qty </th>
										</tr>
									</thead>
									<tbody>
										<?php

										foreach($listPrintArray as $Dataset=>$TableData){
										   if($Table== $Dataset){
											 foreach($TableData as $value){
										
											/*$item_sql = "select * from pos_purch_details where id_pos_purch=".$row->id;
											$item_SqlKotList = mysqli_query($connNew, $item_sql); 
											$numRows=	mysqli_num_rows($item_SqlKotList);
											$i=1;
											while($item_row = mysqli_fetch_object($item_SqlKotList)){*/
											
												$qty = (int)$value['qty'];
												$check_orderis_ready = $value['check_orderis_ready'];
											$old_qty = $value['old_qty'];
												
												/*if($check_orderis_ready>0){
													$checked = "checked";
												}else{
													$checked = "";
												}
												if($qty=='0' && $old_qty>'0'){
													$tot_qty = $old_qty;
												}else{
													$tot_qty = $qty;
												}*/
										?>
										<tr>
											<td><span><?php echo (int)$value['tot_qty'].'x'; ?> </span>&nbsp; <?php echo $value['item_description']; ?> 
												<span style="display:block;font-weight:600;margin-left:24px;">ADD SALT</span> </td>
								            
											<td style="width:50px;">
												<label class="switchCheck">
													<input type="checkbox" <?php echo $value['checked']; ?> class="check_class" value="" id="detail_status-<?php echo $value['id_pos_purch_details']; ?>" name="detail_status" onclick="showHint(this.id,<?php echo $value['tot_qty']; ?>,<?php echo $value['old_qty']; ?>)"><span class="slider round"></span>
													<input type="hidden"  value="<?php echo $value['id_pos_purch_details']; ?>" class="check">
												</label>
											</td>
											
											<!--<td style="width:50px;">
												<label class="switchCheck">
													<input type="checkbox"  value="" id="detail_status-<?php echo $value['id_pos_purch_details']; ?>" checked="checked" name="detail_status" onclick="test(this.id);">
													<input type="hidden"  value="<?php echo $value['id_pos_purch_details']; ?>" class="check">
													<span class="slider round"></span>
												</label>
											</td>
										</tr>-->
										   <?php }}} ?>		
										
									</tbody>
						        </table>
						      </div>

						      <!--tabcontent ends-->
						        <div class="tabheading" id="checkcont2">
						        <div class=" d-flex" id="checkboxs">
						          <div class="tbsteward">
						            <h4 title="Steward"><?php echo $TableDetails['steward_name']; ?></h4> <h4 title="KOT"><?php echo '#'.$TableDetails['mdoc_no']; ?></h4>   
						          </div>
						           
						          <div class="tbname">
						           <input type="checkbox" >
						          </div>

						          </div>
						         
						      </div>
						      <!--table heading ends-->
						    </div>
						  </div>
						</div>
			        	<!--tab col ends-->
							<?php  } //echo "select "; ?>	
	        	</div>
				
				<div id="txtHint" class="clickmenu"> </div>

				<div class="hidemenu">		
					<?php
					// $MSQL = "select *, sum(qty) as max_qty,pos_purch.doc_date from pos_purch_details join pos_purch on pos_purch.id = pos_purch_details.id_pos_purch where $menudate group by item_description order by max_qty desc";
					
					//pos_purch_details.item_description    //inv_items.id_mst_attributes_group_main
						
					$MSQL = "select pos_purch_details.*, sum(pos_purch_details.qty) as max_qty, pos_purch.doc_date, inv_items.id_mst_attributes_group_main from pos_purch_details join pos_purch on pos_purch.id = pos_purch_details.id_pos_purch join inv_items on inv_items.id = pos_purch_details.id_mst_items where $menudate group by pos_purch_details.item_description order by max_qty desc";
						
						$MSqlKotList = mysqli_query($connNew, $MSQL); 
						$numRows =	mysqli_num_rows($MSqlKotList);
					?>				
					<div id="kwbox2" class="col-md-2 kw-sidebar floating">
						<div class="kw-box">
						
							<table class="table table-responsive sidebar-h">
								<thead>
									<th width="90%">Menu Itemwise</th>
									<th>Qty</th>
									
								</thead>
							</table>
							
							<?php
								$listPrintArra1y=array();
								
								while($Mrow = mysqli_fetch_object($MSqlKotList)){ 
								//id_shop='".$_SESSION['shop']."'
								$id_main_menu = selectColumn('inv_items','id_mst_attributes_group_main'," WHERE  status = '1' AND id= '".$Mrow->id_mst_items."'"); 
								$main_menu_name = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE  status = '1' and `table_name` = 'item_group_main' AND id= '".$id_main_menu."'"); 
								
									$listPrintArra1y[$Mrow->inv_items][$Mrow->id_mst_items]['item_description']=$Mrow->item_description;
									$listPrintArra1y[$Mrow->inv_items][$Mrow->id_mst_items]['main_menu_name']=$main_menu_name;
									$listPrintArra1y[$Mrow->inv_items][$Mrow->id_mst_items]['max_qty']=$Mrow->max_qty;
								}
								//debugData($listPrintArra1y);
							?>
							
							<table class="table table-responsive">
								<?php  
									foreach($listPrintArra1y as $Datasett=>$TableData1){
									if($Table1== $Datasett){
									foreach($TableData1 as $value1){
								?>
								<thead>
									<th colspan="2" class="text-center"><?php echo $value1['main_menu_name']; ?></th>
								</thead>
								
								<tbody>
									<tr>
										<td><?php echo $value1['item_description']; ?></td>
										<td><span><?php echo round($value1['max_qty']); ?></span></td>
									</tr>
								</tbody>
								<?php } } } ?>
							</table>
							
						</div>
					</div>	
				</div>
			</div>
		<!--END OF ROW-->  
		</div>
		
      <br><br><br>
      
    </div>
	
</div>
  
  <!-- /.row -->
  
  </section>
  
  <!-- /.content --> 
  
</div>
<div class="modal fade outletmodal" id="kotwiseModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     
      <div class="modal-body">
    
        <div id="myDIV">
            <div class="row">

            

            <div class="col-md-12" id="hideGroup">
                <div class="form-group mb-0">
                <label for="name">Select Status </label>
                <div class="box-body table-responsive">
                    <div id="MyTableGroupID">
                     <table id="myTableTableList" class="table table-fixedTableGroup table-striped table-bordered dataTable no-footer" >

											
                         <tbody>

								<tr ><td><a href="#" name="outletid" id="outletid" type="button" class="btn n-btn btn-block" value="" style="width: 100% !important;" onclick="">Preparing</a></td></tr>
														<tr ><td><a href="#" name="outletid" id="outletid" type="button" class="btn n-btn btn-block" value="" style="width: 100% !important;" onclick="">Ready</a></td></tr>
                      </tbody>
                      </table>
                  </div>
                  </div>

				<a type="button" class="btn o-btn" data-dismiss="modal"> <span class="glyphicon glyphicon-off"></span> Close </a>
              </div>
              </div>
            
            
          </div>
             
          </div>
        
        <!-----------------Table Part END-------------------->
         </div>
    
    </div>
  </div>
</div>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">

<?php include_once("../includes/footer.php")?>
<script language="javascript">
       
	   window.onload = function() {
			
		listPendingKot();
		}
	   
	   
	   function listPendingKot(){
	
	var id_attribute_table_search=$("#id_attribute_table_search").val();
	
	
	//$('.loading').show();
	
	

		$.ajax({
			type: "POST",
			url: 'ajax/ajaxListPendingKot.php?id_attribute_table='+id_attribute_table_search,			 
			success: function (result) {
				$("#listPendingKot").html(result);
				}

		});

	

}
    
    </script>

<script>
function GoToBill(tableid){
			$('#tableModal').modal('show');				
			  $('#tableandoutlet'+tableid).html('<input type="hidden" name="id_attribute_table" id="id_attribute_table" value="'+tableid+'"/>');
			  // $('#id_attribute_table').val(tableid);
			   }
			   
			function SelectOutlet(id){
				var id_attribute_table=$("#id_attribute_table").val();
				//alert(id_attribute_table);
				//alert(id);
				$('#tableOutlet2'+id_attribute_table).html('<input type="hidden" name="outlet" id="outlet" value="'+id+'"/>');
				//$('#outlet').val(id);
				$('#tableModal').hide();
				document.getElementById("FormPosKot_"+id_attribute_table).submit();// Form submission
				}  



				//CHECKBOX SCRIPT FOR KOTWISE 

              // document.getElementById('checkcont').style.background="red";
				$('#checkboxs').click(function(){
					var checkbox = $(this).find('input[type=checkbox]');
					checkbox.prop("checked",!checkbox.prop("checked"));
				});


				var bgcolor = document.getElementById('checkcont');
				var bgcolor2 = document.getElementById('checkcont2');
				bgcolor.style.background="#752A07";
				bgcolor2.style.background="#752A07";
				
				
			$(document).ready(function(){
				//$('#kwbox').trigger("click");
				var kstatus =  $('#kwstatus').text();
				if(kstatus=="Pending"){
					bgcolor.style.background="#752A07";
					bgcolor2.style.background="#752A07";
				}
				else if(kstatus=="Preparing"){
					bgcolor.style.background="rgb(231 176 84)";
					bgcolor2.style.background="rgb(231 176 84)";				
				}else if(kstatus=="Ready"){
					bgcolor.style.background="#5C8F22";
					bgcolor2.style.background="#5C8F22";
				}
			});	
			
				$('#kwbox, #checkcont').click(function(){
					//alert();
					//var kwstatus =  document.getElementById('kwstatus');
					//kwstatus.innerHTML="Prepare";
				    var kstatus =  $('#kwstatus').text();
				    // alert(kstatus);
					if(kstatus=="Pending"){
						kstatus = "Preparing";
					    bgcolor.style.background="rgb(231 176 84)";
					    bgcolor2.style.background="rgb(231 176 84)";
					}
					else if(kstatus=="Preparing"){
						kstatus = "Ready";
						//  var kstatus =  $('#kwstatus').text();
						bgcolor.style.background="#5C8F22";
						bgcolor2.style.background="#5C8F22";				
					}else if(kstatus=="Ready"){
						//alert();
						kstatus = "Pending";
						// var kstatus =  $('#kwstatus').text();
						bgcolor.style.background="#752A07";
						bgcolor2.style.background="#752A07";
					}
					$('#kwstatus').text(kstatus);
					//var pos_id =  document.getElementById('pos_id').value;
					var pos_id =  '6';
					/*$.ajax({
						type: "POST",
						url: 'ajax/ajaxUpdatestatus.php',
						data: "status=" + kstatus + "&id=" + pos_id, 
						success: function(data){
							
						},
					});*/
							
				});
				
/*				
const curr_user = Math.random().toString(32).substring(2,10)+Math.random().toString(32).substring(2,30);
	console.log(curr_user);
	$(document).ready(function(){
		var websocket = new WebSocket("ws://ls-b2e60044536f2eec0addbe53dd9287ba11700950.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com"); 
		
		websocket.onopen = function(event) { 
			console.log("Connection is established!");		
		}
		
		websocket.onmessage = function(event) {
			var Data = JSON.parse(event.data);
			//console.log(Data);
			if(Data.chat_user == curr_user){
				window.location.reload();
				return;
			}
			if((Data.chat_user != null) && (Data.message_type == 'event')){
				 window.appCalendar.refetchEvents();
			}
		};
		
		websocket.onerror = function(event){
			//console.log("Problem due to some Error");
		};
		websocket.onclose = function(event){
			//console.log("Connection Closed");
		}; 
		
		var messageJSON = {
			chat_user: curr_user,
			chat_message: 'new event added'
		};
		websocket.send(JSON.stringify(messageJSON));

	});*/
	
	

	
	
</script>

<script type="text/javascript">
/* var test = "<?php echo $item_row->id; ?>";	

		
$('#detail_status').click(function(){

var cur = $('#detail_status').index($(this));
var test2 = $('.check').eq(cur).val();
	
alert(test2);
	var pos_id =  document.getElementById('detail_status_'+test).value;
//alert(pos_id);
var kstatus =  $('#kwstatus').text();

$('#kwstatus').text(kstatus);
var pos_id =  document.getElementById('pos_id').value;
$.ajax({
	type: "POST",
	url: 'ajax/ajaxUpdatestatus.php',
	data: "status=" + kstatus + "&id=" + pos_id, 
	success: function(data){
		
	},
});
		
});	*/	

	

//script for right sidebar sticky and fixed
var kwmatch = window.matchMedia("(min-width:991px)");
kwSidebar(kwmatch);
kwmatch.addListner(kwSidebar);
function kwSidebar(kwmatch){
	if(kwmatch.matches){
		window.onscroll = function() {
			var nav = document.getElementById('kwbox2');
			if ( window.pageYOffset > 100 ) {
				nav.classList.add("kw-sidebar-fixed");
			} else {
				nav.classList.remove("kw-sidebar-fixed");
			}
		}
	}
}

 </script>			
