<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_PURCH,'view');
include_once("include/pos_function.php");
include_once("include/function.php"); ?>
<?php include_once("../includes/header.php");
if($_SESSION['outlet_access']!=''){
$outletFilter	="  AND mst_outlets.`id` IN ('".addslashes($_SESSION['outlet_access'])."')";
$outletFilterPurch	="  AND ppp.`id_mst_outlet` IN ('".addslashes($_SESSION['outlet_access'])."')";
}
?>
<script>
	window.setTimeout( function() {
  window.location.reload();
}, 30000);
</script>
<?php include_once("../includes/left.php")?>
    <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/pos/css/style.css"/>

<style>
 body{
	 font: 12px 'Segoe UI', Tahoma, Arial, Helvetica, sans-serif;}   

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
@media only screen and (min-width:767px) and (max-width:1000px) {
	.content{
		margin-top:24px;
	}
     .kw-filter{
     	margin-right: 10px;
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
  
    <div class="row kdsbtnbox">

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
	  <div class="btn c-btn btn-block"  ><i class="fa fa-pencil fa-1x"></i> Add</div >
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
	  <div class="btn c-btn btn-block mr-15" ><i class="fa fa-list fa-1x"></i> List</div >
	 </a>
</div>
<div class="form-group col-xs-3 col-md-1 col-sm-2 c-box">
	<a onclick="reload" href="kds.php?submenu=<?php echo $_REQUEST['submenu'] ?>&session=<?php echo $_REQUEST['session'] ?> ">
	  <div class="btn c-btn btn-block mr-15"  ><i class="fa-solid  fa-arrows-rotate fa-1x"></i> Refresh</div >
	 </a>
</div>
<div class="form-group col-xs-3 col-md-1 col-sm-2 c-box">
	<a onclick="reload" href="kds.php?submenu=<?php echo $_REQUEST['submenu'] ?>&session=<?php echo $_REQUEST['session'] ?> ">
	  <div class="btn o-btn mr-15" ><i class="fas fa-scroll"></i> All Items </div >
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

	/*function search_form(clicked_id){
		if(clicked_id.length==0){
			document.getElementById("search_div").innerHTML='';
			return;
		}else{
			var search_name = document.getElementById("search_name").value;
			var table = document.getElementById("id_attribute_table_search").value;
			var order = document.getElementById("order").value;
			var fstatus = document.getElementById("status").value;
			var outlet = document.getElementById("outlet").value;
			
			$('.without_search').hide();
			$('.with_search').show();
        	var formData = $("#searchForm").serialize();
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if(this.readyState == 4 && this.status == 200){
					document.getElementById("search_div").innerHTML = this.responseText;
				}
			};//ajaxUpdatestatus
			xmlhttp.open("GET", "ajax/ajaxLoadKotDigitalSystem.php?search_name=" + search_name + "&outlet=" + outlet + "&id_attribute_table_search=" + table + "&order=" + order + "&fstatus=" + fstatus, true);
			xmlhttp.send();
		}
	}*/
	
	
	
		function item_test(clicked_id){
			if(clicked_id.length==0){
				document.getElementById("search_div").innerHTML='';
				return;
			}else{
				var item_name = clicked_id;
				
				$('.without_search').hide();
				$('.with_search').show();
				//var formData = $("#searchForm").serialize();
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if(this.readyState == 4 && this.status == 200){
						document.getElementById("search_div").innerHTML = this.responseText;
					}
				};
				//alert(item_name);
				xmlhttp.open("GET", "ajax/ajaxUpdatestatus.php?item=" + item_name, true);
				xmlhttp.send();
			}
		}
	
</script>
<?php
//debugData($_SESSION);
	if($_SESSION['userPrinter']!=''){
					 $PrintertFilterPurch	="  AND `id` IN (".addslashes($_SESSION['userPrinter']).")";
					}	 
?>
  
<form name="searchForm" id="searchForm">
    <input type="hidden" value="1" name="searchFormSubmit" />
    <input type="hidden" value="<?php echo $_GET['session'] ?>" name="session" />
    <input type="hidden" value="<?php echo $_GET['submenu'] ?>" name="submenu" />
	
	<div class="kw-filter" >
		<div id="myTable_filter" class="dataTables_filter"><!--<label>Search Table : </label>-->
		<?php $categoryDropDown = '<select class="form-control select2" name="id_attribute_table_search" id="id_attribute_table_search" onChange="search_form(this.id);" >
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
	  <?php 
	  
	$categoryDropDown = '<select class="form-control select2" name="printer" id="printer" data-parsley-required data-parsley-errors-container="#printerError" onChange="search_form(this.id);" >
				<option value="">Select Printer</option>';
			  $resCat = selectSql(TBL_ATTRIBUTES," where id_shop='".$_SESSION['shop']."' AND  status = '1' AND `table_name` = 'printer' $PrintertFilterPurch ",'');
			  if($db->num_rows2($resCat)){
				while($resultCat = $db->fetch_object2($resCat)){
					if($_REQUEST['outlet'] == $resultCat->id){
						$selected = 'selected="selected"';
					}else{
						$selected = '';
					}
					$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
				}
			  }
				echo $categoryDropDown .= '</select>';
 ?></div>
		<div class="kw-status">
		  <!--  <label>Status :</label>--> 
			   <select class="form-control" name="status" id="status" onChange="search_form(this.id);">
					<option value="Pending">Pending</option>
					<!--<option value="Preparing">Preparing</option>-->
					<option value="Ready">Served</option>
			  </select>
		  
	  </div>

		<div class="kw-priority">
			<!-- <label>Order By : </label>-->
				 <select class="form-control" name="order" id="order" onChange="search_form(this.id);">
					<option value="Newest">Newest</option>
					<option value="Oldest">Oldest</option>
					<?php /*?><option value="Top Priority">Top Priority</option><?php */?>
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
<!--<div id="search_div" class="with_search1"> New order received </div>-->

    <div class="col-xs-12 without_search">
		<!--  <div class="col-md-3 c-box2" style="margin-top:10px;">
			<input type="submit" value="Go To Bill" class="btn btn-block  o-btn" name="Billing" ></input>
		</div>-->

<script>		
	
		$(document).ready(function(){
			$('.clickmenu').hide();
			$('.hidemenu').show();
		});	

		
			
	</script>
	
   
                            
             <div id="listkds"></div>		
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
       
function viewkds(){
	var search_name = document.getElementById("search_name").value;
			var table = document.getElementById("id_attribute_table_search").value;
			var order = document.getElementById("order").value;
			var fstatus = document.getElementById("status").value;
			var printer = document.getElementById("printer").value;	
	$.ajax({
			type: "POST",
			url: "ajax/ajaxLoadKotDigitalSystem.php?search_name=" + search_name + "&printer=" + printer + "&id_attribute_table_search=" + table + "&order=" + order + "&fstatus=" + fstatus,
			success: function (data) { 			
				$("#listkds").html(data);
				}

		});
	}
	function viewItemRightDetails(){
		
		$.ajax({
			type: "POST",
			url: 'ajax/ajaxLoadKotItemCountWise.php',			 
			success: function (data) {				
				$("#kdsItemCountview").html(data);
				}

		});
		
		
		}
function listItemWise(id_mst_items){	

	$.ajax({
			type: "POST",
			url: 'ajax/ajaxLoadKotTableWise.php?id_mst_items='+id_mst_items,			 
			success: function (data) {				
				$("#kdstableview").html(data);
				}

		});
	}	
	
	 window.onload = function() {
			
		viewkds();
		}
		
		
	function search_form(clicked_id){
		
			
		
			var search_name = document.getElementById("search_name").value;
			var table = document.getElementById("id_attribute_table_search").value;
			var order = document.getElementById("order").value;
			var fstatus = document.getElementById("status").value;
			var printer = document.getElementById("printer").value;
			var id_mst_items = document.getElementById("id_mst_items").value;
			
			//$('.without_search').hide();
			//$('.with_search').show();
        	//var formData = $("#searchForm").serialize();
			$.ajax({
			type: "POST",
			url: "ajax/ajaxLoadKotDigitalSystem.php?search_name=" + search_name + "&printer=" + printer + "&id_attribute_table_search=" + table + "&order=" + order + "&fstatus=" + fstatus,			 
			success: function (data) { 
			
							
				$("#listkds").html(data);
				}

		});
			/*var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if(this.readyState == 4 && this.status == 200){
					document.getElementById("search_div").innerHTML = this.responseText;
				}
			};//ajaxUpdatestatus
			xmlhttp.open("GET", "ajax/ajaxLoadKotDigitalSystem.php?search_name=" + search_name + "&outlet=" + outlet + "&id_attribute_table_search=" + table + "&order=" + order + "&fstatus=" + fstatus, true);
			xmlhttp.send();*/
		
	}	
		function ChangeCookStatus(clicked_id, qty, old_qty){
			//alert();
			var substr = clicked_id.split('-');
			var str = substr[0];
			var pos_purch_details_id = substr[1];
			var check_id = $('#detail_status-'+pos_purch_details_id).is(':checked');
			var id_mst_items = document.getElementById("id_mst_items").value;
			
			if(check_id){
				var status = '1';
			}else{
				var status = '0';
			}
			$.ajax({
			type: "POST",
			url: 'ajax/ajaxChangeCookStatus.php?qty=' + qty + '&id=' + pos_purch_details_id + '&status=' + status + '&old_qty=' + old_qty+'&id_mst_items='+id_mst_items,			 
			success: function (data) {
				 
				//$("#listkds").html(data);
				viewItemRightDetails();
				}

		});
			
		}	
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		//========================================================
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
		var websocket = new WebSocket("ws://localhost:8090"); 
		
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
function CheckServerStatus(mdoc_no,id,id_mst_outlet){
	var id_serve_status = document.getElementById("ServerStatus").value;
	$.ajax({
			type: "POST",
			url: 'ajax/ajaxUpdateServeStatus.php?mdoc_no='+mdoc_no+'&id='+id+'&id_mst_outlet='+id_mst_outlet+'&id_serve_status='+id_serve_status,			 
			success: function (data) {
				 result = JSON.parse(data);
				 //alert(result.status);
				 if(result.status == 0){
//alert(mdoc_no+'==='+id+'==='+id_mst_outlet);
					$("#mdoc_no_new").val(mdoc_no);
					$("#id_new").val(id);
					$("#id_mst_outlet_new").val(id_mst_outlet);
					$("#ServerStatusWithoutCook").val('1');
					$("#serve_status_"+mdoc_no+'_'+id_mst_outlet).prop("checked",false);
				
					servedModal();
				
					//$('#servedModal').modal('show');
				//alert(result.Msg);
				 	$("#serve_status_"+mdoc_no+'_'+id_mst_outlet).prop("checked",false);


			   }else{
				   
				     location.reload();
				   }
				
				//search_form();
				
				//alert('1');
				//$("#listPendingKot").html(result);
				}

		});
	}
	
function CheckServerStatusWithoutCook(){
	var mdoc_no = document.getElementById("mdoc_no_new").value;
			var id = document.getElementById("id_new").value;
			var id_mst_outlet = document.getElementById("id_mst_outlet_new").value;
			//var id_mst_items = document.getElementById("id_mst_items").value;
			var id_serve_status = document.getElementById("ServerStatus").value;
	$.ajax({
			type: "POST",
			url: 'ajax/ajaxUpdateServeStatus.php?mdoc_no='+mdoc_no+'&id='+id+'&id_mst_outlet='+id_mst_outlet+'&ServerStatusWithoutCook=1&id_serve_status='+id_serve_status,			 
			success: function (data) {
				 result = JSON.parse(data);
				 
				   //alert('Done');
				     location.reload();
				  // }
				
				//search_form();
				
				//alert('1');
				//$("#listPendingKot").html(result);
				}

		});
	}
	
	
	function servedModal(){
		$('#servedModal').modal('show');
	}

function updatekotPreparing(mdoc_no,id_pos_purch){
	
	
	
	$.ajax({
			type: "POST",
			url: 'ajax/ajaxUpdatekotPreparing.php?mdoc_no='+mdoc_no+'&id_pos_purch='+id_pos_purch,			 
			success: function (data) {
				 result = JSON.parse(data);
				 
				   //alert('Done');
				     location.reload();
				  // }
				
				//search_form();
				
				//alert('1');
				//$("#listPendingKot").html(result);
				}

		});
	}
	  
	   
 </script>		