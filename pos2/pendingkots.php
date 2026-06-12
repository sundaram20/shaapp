<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_PURCH,'view');
include_once("include/pos_function.php");
include_once("include/function.php"); ?>
<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
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
  border: 1px solid #EEE
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
<div class="form-group col-xs-3 col-md-1 col-sm-2 c-box">
	<a href="kds.php?submenu=178">
	  <div class="btn c-btn btn-block" title="Kitchen Display System"  style="margin-right:15px" > <i class="fas fa-tv"></i> KDS </div >
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

  <div class=" col-xs-12 col-md-3 pksearch">
    <div id="myTable_filter" class="dataTables_filter"><label>Search Table : 
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
	<?php /*?><input type="search" class="" placeholder="" aria-controls="myTable"><?php */?></label></div>
   </div>
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
      <div class="col-xs-12"> 
        
        <!-- /.box -->
      <!--  <div class="col-md-3 c-box2" style="margin-top:10px;">

	<input type="submit" value="Go To Bill" class="btn btn-block  o-btn" name="Billing" ></input>

	 </div>-->
 
        <div class="box pb-70">  

        	 <div class="row">




        	 	<!--tab col starts-->

	        	 <div id="listPendingKot"></div>
	        	 <!--tab col ends-->

	        	 	<!--tab col starts-->

	        	 
	        	 <!--tab col ends-->

	        	 	<!--tab col starts-->

	        	 
	        	 <!--tab col ends-->

	        	 	<!--tab col starts-->

	        	 
	        	 <!--tab col ends-->


        	 	


	        	 

           </div>
 <!--END OF ROW-->       <!-- /.box-body --> 
        
      </div>
      <br><br><br>
      
      <!-- /.box --> 
      
    </div>
    
    <!-- /.col --> 
    
  </div>
  
  <!-- /.row -->
  
  </section>
  
  <!-- /.content --> 
  
</div>
<div class="modal fade" id="tableModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     
      <div class="modal-body">
    
        <div id="myDIV">
            <div class="row">

            

            <div class="col-md-5" id="hideGroup">
                <div class="form-group mb-0">
                <label for="name">Select Outlet </label>
                <div class="box-body table-responsive " style="padding-left: 0px;padding-bottom: 0px;padding-top: 0px;padding-right: 4px;">
                    <div id="MyTableGroupID">
                     <table id="myTableTableList" class="table table-fixedTableGroup table-striped table-bordered dataTable no-footer" cellspacing="0" >
                        <tbody >
                        <?php 


$resCat = selectSql(mst_outlets," where id_shop='".$_SESSION['shop']."' AND  status = '1' and outlettype='1' ",'');
			  if($db->num_rows2($resCat)){
				while($resultCat = $db->fetch_object2($resCat)){

										if($i==1){

											$ClassName='btn tablegroupbtn activetablegroup';

										}else{

											//$ClassName='';

											$ClassName='btn tablegroupbtn';

											}

											

echo $categoryDropDown ='<tr ><td><a href="#" name="outletid" id="outletid" type="button" class="btn c-btn btn-block" value="'.ucfirst($resultCat->id).'" style="width: 100% !important;" onclick="SelectOutlet('.ucfirst($resultCat->id).');" >'.ucfirst($resultCat->name).'</a></td></tr>';

										

									}

								  }

								 	//echo $categoryDropDown;

								  ?>
                      </tbody>
                      </table>
                  </div>
                  </div>
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
				
				
function updateVerifiedStatus(id_pos_purch_details){	

	$.ajax({
			type: "POST",
			url: 'ajax/ajaxUpdateVerifiedStatus.php?id_pos_purch_details='+id_pos_purch_details,			 
			success: function (result) {
				data = JSON.parse(result);
				if(data.Status=='1'){
					listPendingKot();
					alert(data.Msg);
					
					}				
				//$("#kdstableview").html(data);
				}

		});
	}	
	
	 
				
				
</script>
