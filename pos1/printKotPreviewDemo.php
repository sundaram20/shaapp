<?php include_once("../config/auto_loader.php");
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
        <li class="active">KOT View </li>
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



<div class="form-group col-xs-3 col-md-1 col-sm-2 c-box">

<?php
if($_REQUEST['printPreviewid']==''){
	$id=$_REQUEST['updateid'];
}else{
	$id=$_REQUEST['printPreviewid'];
}
?>
	<a href="editKot.php?editKotid=<?php echo $id ?>&submenu=<?php echo $_REQUEST['submenu'] ?>&staus=edit&session=<?php echo $_REQUEST['session'] ?> ">
		<div class="btn c-btn btn-block" style="margin-right:15px" ><i class="fa fa-pencil-square-o fa-1x"></i> Edit</div >
	</a>
</div>	
				
<div class="form-group col-xs-3 col-md-1 col-sm-2 c-box">
	<a href="<?php echo $list ?>?submenu=<?php echo $_REQUEST['submenu'] ?>&session=<?php echo $_REQUEST['session'] ?> ">
	  <div class="btn c-btn btn-block" style="margin-right:15px" ><i class="fa fa-list fa-1x"></i> List</div >
	 </a>
</div>		
   <div class="form-group col-xs-3 col-md-1 col-sm-2 c-box">
<button class="btn c-btn btn-block" style="margin-right:15px" onClick="printdiv('div_print');"><i class="fa fa-print fa-1x"></i> Print &nbsp;&nbsp;&nbsp;</button>
</div>  

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
  &nbsp;&nbsp;  </div>

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
        
        <div class="box pb-70">
        	 <div class="row">
          <div class="col-md-9 col-lg-10">
        
 <?php
 $id_mst_doc_type_config = selectColumn(TBL_PURCH,'id_doc_type_configuration'," WHERE `id_shop` = '".$_SESSION['shop']."' AND `id` = '".$pos_purch_id."'  ");
 $EnableSplitPrint = selectColumn(TBL_DOC_TYPE_CONFIG,'enablesplitprint'," WHERE `id_shop` = '".$_SESSION['shop']."' AND `id` = '".$id_mst_doc_type_config."'  ");


 /*echo $sqlDIffPrint = "SELECT C.id_mst_attributes_printer AS id_printer FROM ".TBL_PURCH." A  LEFT JOIN ".TBL_PURCH_DETAILS." B ON A.id=B.id_pos_purch LEFT JOIN ".TBL_INV_ITEMS." C ON B.id_mst_items=C.id WHERE A.id_shop='".$_SESSION['shop']."' AND A.id='".$pos_purch_id."'  GROUP BY id_printer ORDER BY id_printer";	*/
 
	$sqlDIffPrint = "SELECT A.* FROM ".TBL_PURCH." A  WHERE A.id_shop='".$_SESSION['shop']."' AND A.id='".$pos_purch_id."'  ";
$resDiffPrint = mysqli_query($connNew,$sqlDIffPrint);

$rowDiffPrint = mysqli_fetch_object($resDiffPrint);


	$sqlToPrint = "SELECT * From `".TBL_PURCH_DETAILS."` WHERE id_pos_purch = '".encryptor(decrypt, $_REQUEST['printPreviewid'])."' ";
	
	$resToPrint = mysqli_query($connNew,$sqlToPrint);
	$listPrintArray=array();
	$listprintHeaderArray=array();
	while($rowToPrint = mysqli_fetch_object($resToPrint)){
		
	$id_mst_attributes_printer = selectColumn(TBL_INV_ITEMS,'id_mst_attributes_printer'," WHERE `id_shop` = '".$_SESSION['shop']."' AND `id` = '".$rowToPrint->id_mst_items."'  ");
	if($EnableSplitPrint=='0'){
		$id_mst_attributes_printer='1';
		}else{
			$orginalCopy_printer='orginalCopy00017812';
	$listPrintArray['printview'][$orginalCopy_printer][$rowToPrint->id_mst_items]['item_description']=$rowToPrint->item_description;
	$listPrintArray['printview'][$orginalCopy_printer][$rowToPrint->id_mst_items]['qty']=$rowToPrint->qty;	
	$printdivvalue[$orginalCopy_printer]='printTable'.$orginalCopy_printer;
			}
	$listPrintArray['printview'][$id_mst_attributes_printer][$rowToPrint->id_mst_items]['item_description']=$rowToPrint->item_description;
	$listPrintArray['printview'][$id_mst_attributes_printer][$rowToPrint->id_mst_items]['qty']=$rowToPrint->qty;	
	$printdivvalue[$id_mst_attributes_printer]='printTable'.$id_mst_attributes_printer;
	
	

	}
	
	
	
	$Table_no	=	selectColumn(TBL_ATTRIBUTES,'field_value','WHERE table_name="table" AND id="'.$rowDiffPrint->id_attribute_table.'" ');
	$doc_date = $rowDiffPrint->doc_date;
	$timestamp = strtotime($doc_date);
	$doc_date = date('d-M-Y', $timestamp);
	$doc_time = date('h:i A', $timestamp);
	$steward	= selectColumn(TBL_ATTRIBUTES,'field_value','WHERE table_name="steward" AND  id="'.$rowDiffPrint->id_attribute_steward.'" ');
	$listprintHeaderArray['printHeaderview']['kot_no']=$rowDiffPrint->mdoc_no;	
	$listprintHeaderArray['printHeaderview']['doc_date']=$doc_date;	
	$listprintHeaderArray['printHeaderview']['doc_time']=$doc_time;
	$listprintHeaderArray['printHeaderview']['table_no']=$Table_no;
	$listprintHeaderArray['printHeaderview']['pax']=$rowDiffPrint->pax;
	$listprintHeaderArray['printHeaderview']['steward']=$steward;	
	
	 //$printdivvalue=implode(',',$printdivvalue);
	//debugData($listprintHeaderArray);
	//print_r($printdivvalue);
 ?>       
        
      <?php 
	  
	 
		foreach($listPrintArray as $maintitle=>$GroupArray){  
		
		foreach($GroupArray as $id_printer => $GroupNameArray){ 
			 $printer='';?>
        <br>
        <div id="printTable<?php echo $id_printer; ?>">
            <div id="invoice-POS" > 
              <!--End InvoiceTop--> 
              
              <!--End Invoice Mid-->
              
              <div  id="bot" > <?php  echo '<pre>';
			  
	
			
	$printer .='<div style="text-align:center;" ><img src='.$SITE_URL.'/uploaded_files/outlets/small-'.$logoImage.'  alt=""></div>';
			
			
	
	$printer.=("<br>");
	$printer .= (str_pad("KOT NO:".$listprintHeaderArray['printHeaderview']['kot_no'],19," "));	
	$printer .=('Date:'.$listprintHeaderArray['printHeaderview']['doc_date']."\n");
	$printer.=(str_pad('Table No:'.$listprintHeaderArray['printHeaderview']['table_no'],19," "));
	$printer.=('Time: '.$listprintHeaderArray['printHeaderview']['doc_time']."\n");
	$printer.=("<br>");
	

	$printer.=(str_pad('Steward:'.$listprintHeaderArray['printHeaderview']['steward'],30," "));
	$printer.=('Pax:'.$listprintHeaderArray['printHeaderview']['pax']);
	$printer.=("<br>");
	$printer.=("--------------------------------------\n");
	$printer.=(str_pad("S.no.  Description ",32," "));
	//$printer.=("<br>");
	$printer.=("Qty");
	$printer.=("\n-----------------------------------\n");
	$sno=1;
	
	foreach($GroupNameArray  as $k1=>  $itemlist){
              	$printer.=("<br>");
		
		$printer.=(columnify($sno++,trim(strtoupper($itemlist['item_description'])),1,27,4));
	$printer.=(round($itemlist['qty'],0)."\n");
	
	}
				//debugData($GroupNameArray);
			
			
							echo $printer;//'</pre>';
						?> </div>
              
              
              
            </div>
              <!--End Invoice-->
          </div>
			  
		<?php  } }?>  
        
   <?php /*?>     
	while($rowToPrint = mysqli_fetch_object($resToPrint)){
		
		
		$printer.=("<br>");
		
		$printer.=(columnify($sno++,trim(strtoupper($rowToPrint->item_description)),1,27,4));
		$printer.=(round($rowToPrint->qty,0)."\n");
	}
	
	
							echo $printer;//'</pre>';
						?> </div>
              
              
              
            </div>
              <!--End Invoice-->
          </div><?php */?>
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
      
          
          
  
      
		
		
			


   
        <?php

/*
if(isset($printer)){
$print_output= $printer;
}
try
{
    $fp=pfsockopen("192.168.1.33", 9100);
    fputs($fp, $print_output);
    fclose($fp);

    echo 'Successfully Printed'.$printer;
}
catch (Exception $e) 
{
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}*/
?>
</div>
<!--END OF COL-->
 <div class="col-md-3 col-lg-2 order-sm-first">
        <div class="rightbtn">
	          <div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
	          <button class="btn c-btn btn-block" style="margin-right:15px" onClick="printdiv('div_print');"><i class="fa fa-print fa-1x"></i> Select Printer</button >
	          </div> 
	          <div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
	          <button class="btn c-btn btn-block" style="margin-right:15px" onClick="printdiv('div_print');"><i class="fa fa-print fa-1x"></i> Print Copies</button >
	          </div> 
	          <div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
	          <button class="btn c-btn btn-block" style="margin-right:15px" onClick="printdiv('div_print');"> Go to Bill</button >
	          </div> 
        </div>
 </div>

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
<?php include_once("../includes/footer.php")?>
<script language="javascript">
       
      function printData()
        {
			
			var items = [<?php echo '"'.implode('","', $printdivvalue).'"' ?>];
        //alert(items);
  
       items.forEach(function (itemddd) {
           // alert(itemddd);
		var divToPrint=document.getElementById(itemddd);
           newWin= window.open("");
           newWin.document.write(divToPrint.outerHTML);
           newWin.print();
           newWin.close();
        });
          
		   
		   
        } 

        $('button').on('click',function(){
        printData();
        });
    </script>





<?php

	/*			
				echo $resCat = "SELECT * From `".TBL_PURCH."` WHERE id = '".encryptor(decrypt, $_REQUEST['printPreviewid'])."' ";
					$itemsql2 = mysqli_query($connNew, $resCat);	
						$row = mysqli_fetch_object($itemsql2);	
					
				
					 $itemsql = "SELECT * From `".TBL_INV_ITEMS_DETAILS."` WHERE id = '".$row->id_mst_items."' ";
					  $itemsql1 = mysqli_query($connNew, $itemsql);	
						$itemsqllist = mysqli_fetch_object($itemsql1);
						    $check = $itemsqllist->id_item;
				
						$itemNameSelectSQL = "SELECT * FROM `".TBL_INV_ITEMS_DETAILS."` WHERE id_item='".$check."' ";
						$resitemName=mysqli_query($connNew,$itemNameSelectSQL); 
						$itemNameNumRows = mysqli_num_rows($resitemName);
						
						if($itemNameNumRows>0){
							
							 $sqlToPrint = "SELECT B.qty,C.name,C.id_mst_attributes_printer AS id_printer FROM ".TBL_PURCH." A  LEFT JOIN ".TBL_PURCH_DETAILS." B ON A.id=B.id_pos_purch LEFT JOIN ".TBL_INV_ITEMS_DETAILS." C ON B.id_mst_items=C.id WHERE A.id_shop='".$_SESSION['shop']."' AND A.id='".$pos_purch_id."' ";
							
						}else{
							
							 $sqlToPrint = "SELECT B.qty,C.name,C.id_mst_attributes_printer AS id_printer FROM ".TBL_PURCH." A  LEFT JOIN ".TBL_PURCH_DETAILS." B ON A.id=B.id_pos_purch LEFT JOIN ".TBL_INV_ITEMS." C ON B.id_mst_items=C.id WHERE A.id_shop='".$_SESSION['shop']."' AND A.id='".$pos_purch_id."' ";
							
						}
							
					$resToPrint = mysqli_query($connNew,$sqlToPrint);
					$rowToPrint = mysqli_fetch_object($resToPrint);
		
	//	echo $rowToPrint->name;
		//echo "hi";
//exit;

*/
?>