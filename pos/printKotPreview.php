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
		$nctype='&doc_type=nc';
}
$NewKot	='managePosKot.php';
?>

 <div class="form-group col-xs-3 col-md-1 col-sm-2 c-box">
	<a href="<?php echo $NewKot; ?>?submenu=<?php echo $_REQUEST['submenu'] ?>&session=<?php echo $_REQUEST['session'] ?><?php echo $nctype; ?> ">
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
<div class="form-group has-error mb-0" align="center">
          <?php if($_SESSION['errorMsg']){?>
          <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
          <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
          <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
          <?php unset($_SESSION['successMsg']);}?>
        </div>
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
          <div class="col-md-9 col-lg-10">
        
 <?php
 $id_mst_doc_type_config = selectColumn(TBL_PURCH,'id_doc_type_configuration'," WHERE `id_shop` = '".$_SESSION['shop']."' AND `id` = '".$pos_purch_id."'  ");
 $EnableSplitPrint = selectColumn(TBL_DOC_TYPE_CONFIG,'enable_split_print'," WHERE `id_shop` = '".$_SESSION['shop']."' AND `id` = '".$id_mst_doc_type_config."'  ");
$EnablePrintAfterSave = selectColumn(TBL_DOC_TYPE_CONFIG,'enable_print_after_save'," WHERE `id_shop` = '".$_SESSION['shop']."' AND `id` = '".$id_mst_doc_type_config."'  ");

$id_mst_printer_default = selectColumn(TBL_DOC_TYPE_CONFIG,'id_mst_printer'," WHERE `id_shop` = '".$_SESSION['shop']."' AND `id` = '".$id_mst_doc_type_config."'  ");

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
	
		$id_printer	= selectColumn(TBL_ATTRIBUTES,'id','WHERE table_name="printer" AND  id="'.$id_mst_attributes_printer.'" and field_category=2');
	$Printer_ip2=selectColumn(TBL_ATTRIBUTES,'field_description','WHERE table_name="printer"  and id="'.$id_printer.'"');
	
	if($EnableSplitPrint=='0'){
		$id_mst_attributes_printer='1';
		}else{
		//	$orginalCopy_printer='orginalCopy00017812';
	$orginalCopy_printer=selectColumn(TBL_ATTRIBUTES,'id','WHERE table_name="printer"  and field_category=1');
		
		$orginalCopy_printer =$id_mst_printer_default;
	if($orginalCopy_printer=='0'){
		$orginalCopy_printer=selectColumn(TBL_ATTRIBUTES,'id','WHERE table_name="printer"  and field_category=1');
	}
	$Printer_ip=selectColumn(TBL_ATTRIBUTES,'field_description','WHERE table_name="printer"  and id="'.$orginalCopy_printer.'"');
	
	$listPrintArray['printview'][$orginalCopy_printer][$rowToPrint->id_mst_items]['item_description']=$rowToPrint->item_description;
	$listPrintArray['printview'][$orginalCopy_printer][$rowToPrint->id_mst_items]['qty']=$rowToPrint->qty;	
	if($Printer_ip==''){
		$printdivvalue[$orginalCopy_printer]='printTable'.$orginalCopy_printer;
	}
			}
	$listPrintArray['printview'][$id_mst_attributes_printer][$rowToPrint->id_mst_items]['item_description']=$rowToPrint->item_description;
	$listPrintArray['printview'][$id_mst_attributes_printer][$rowToPrint->id_mst_items]['item_special_request']=$rowToPrint->item_special_request;

	$listPrintArray['printview'][$id_mst_attributes_printer][$rowToPrint->id_mst_items]['qty']=$rowToPrint->qty;	
	if($Printer_ip2==''){
		$printdivvalue[$id_mst_attributes_printer]='printTable'.$id_mst_attributes_printer;
	}
	
	

	}
	
	
	
	$Table_no	=	selectColumn(TBL_ATTRIBUTES,'field_value','WHERE table_name="table" AND id="'.$rowDiffPrint->id_attribute_table.'" ');
	$id_table_Group_Type	=	selectColumn(TBL_ATTRIBUTES,'id_table_group','WHERE table_name="table" AND id="'.$rowDiffPrint->id_attribute_table.'" ');
	$Table_Group_Type	=	selectColumn(TBL_ATTRIBUTES,'id_table_group','WHERE table_name="table_group" AND id="'.$id_table_Group_Type.'" ');
	if($Table_Group_Type=='1'){
		$TableGroupName='Room No';
		}else{
			$TableGroupName='Table No';
			}		  
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
			  $listprintHeaderArray['printHeaderview']['TableGroupName']=$TableGroupName;
	 $listprintHeaderArray['printHeaderview']['remarks']=$rowDiffPrint->remarks;
	 //$printdivvalue=implode(',',$printdivvalue);
	//debugData($listprintHeaderArray);
	//print_r($printdivvalue);
	
	$SqlCheckKotStatus1="SELECT kot_status  from
( select pp.*,  
	   (case  when COALESCE(pp.cancelled)=1 then 'cancelled'
	   		  when COALESCE(ppp.qty-ppp.adj_qty)>0 then 'Pending'
	         when COALESCE(ppp.qty-ppp.adj_qty)=0 then 'Billed' end) as kot_status
 
 from pos_purch pp left join pos_purch_details ppp on ppp.id_pos_purch=pp.id 
 where id_shop= '".addslashes($_SESSION['shop'])."' AND pp.pos_bill_type=1 AND pp.doc_type=22 AND pp.id = '".encryptor(decrypt, $_REQUEST['printPreviewid'])."' 
 
 group by pp.id ORDER BY pp.`last_modified` desc
 
 )as managekotlist WHERE id!=0  
";
$POSCurrentStartDate = date('d-m-Y',strtotime("-3 day", strtotime(date('d-m-Y'))));
	$POSCurrentEndDate 	= 	date('Y-m-d');
  $SqlCheckKotStatus ="SELECT id_attribute_table,sum(total_qty) as total_qty, sum(total_adj_qty) as total_adj_qty FROM `pos_purch` WHERE `pos_bill_type` = 1 and cancelled!=1 and doc_type='22' and total_qty-total_adj_qty>0 and id = '".encryptor(decrypt, $_REQUEST['printPreviewid'])."' ";
			  
			  
$SqlKotList = mysqli_query($connNew, $SqlCheckKotStatus); 
$rowObject=	mysqli_fetch_object($SqlKotList);

 ?>       
        
      <?php 
	  
	 
		foreach($listPrintArray as $maintitle=>$GroupArray){  
		
		foreach($GroupArray as $id_printer => $GroupNameArray){ 
//echo $id_printer;
			$printer_type=selectColumn(TBL_ATTRIBUTES,'field_category','WHERE table_name="printer"  and id="'.$id_printer.'"');
$printer_name=selectColumn(TBL_ATTRIBUTES,'field_value','WHERE table_name="printer"  and id="'.$id_printer.'"');
$Printer_ip=selectColumn(TBL_ATTRIBUTES,'field_description','WHERE table_name="printer"  and id="'.$id_printer.'"');

			 $printer='';?>
        <br>
        <div id="printTable<?php echo $id_printer; ?>">
            <div id="invoice-POS" > 
              <!--End InvoiceTop--> 
              
              <!--End Invoice Mid-->
              
              <div  id="bot" > <?php  echo '<pre><br>';
			  
	
			if($logoImage!=''){
	$printer .='<div style="text-align:center;" ><img src='.$SITE_URL.'/uploaded_files/outlets/small-'.$logoImage.'  alt=""></div>';
			}
		if($printer_type==2){	
	$printer .= (str_pad("Printer:".strtoupper($printer_name),19," "));	
		}
	
	$TableGroupName	= $listprintHeaderArray['printHeaderview']['TableGroupName'];
	$printer.=("<br>");
	$printer.=("<br>");
	$printer .= (str_pad("KOT NO:".$listprintHeaderArray['printHeaderview']['kot_no'],19," "));	
	$printer .=('Date:'.$listprintHeaderArray['printHeaderview']['doc_date']."\n");
	$printer.=(str_pad($TableGroupName.':'.$listprintHeaderArray['printHeaderview']['table_no'],19," "));
	$printer.=('Time: '.$listprintHeaderArray['printHeaderview']['doc_time']."\n");
	$printer.=("<br>");
	

	$printer.=(str_pad('Steward:'.$listprintHeaderArray['printHeaderview']['steward'],30," "));
	$printer.=('Pax:'.$listprintHeaderArray['printHeaderview']['pax']);
	$printer.=("<br>");
	$printer.=("-----------------------------------\n");
	$printer.=(str_pad("S.no.  Description ",32," "));
	//$printer.=("<br>");
	$printer.=("Qty");
	$printer.=("\n-----------------------------------\n");
	$sno=1;
	
	foreach($GroupNameArray  as $k1=>  $itemlist){
              	$printer.=("<br>");
		
		$printer.=(columnify($sno++,trim(strtoupper($itemlist['item_description'])),1,27,4));
	$printer.=(round($itemlist['qty'],0)."\n");
	 if($itemlist['item_special_request']!=''){


	$printer.=(columnify('','<span style="font-size:10px;display: block;font-size: 10px;width: 174px;margin-top:-18px; white-space: break-spaces; margin-left: 38px;">'.trim(strtoupper($itemlist['item_special_request'])).'</span>',1,100,4));
	}
	 //'<div style="font-size:8px">'..'</div>';
	}
				//debugData($GroupNameArray);
			$printer.="\n".$listprintHeaderArray['printHeaderview']['remarks'];
			
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

<!--START OF COL-->
<?php if($rowObject->total_adj_qty=='0'){?>
<div class="col-md-3 col-lg-2 order-sm-first">
        <div class="rightbtn">
	          
	          <div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
              <form name="FormPendingKot" id="FormPendingKot" action="pendingkots.php?submenu=178" method="post">
            <input type="hidden" value="1" name="FormSubmitPosKot" />
            <input type="hidden" value="<?php echo $_REQUEST['submenu'];?>" name="submenu1" id="submenu1">
            <input type="hidden" name="id_attribute_table" id="id_attribute_table" value="<?php echo $rowDiffPrint->id_attribute_table;?>"/>
            
	           <input type="submit" name="submit" class="btn n-btn btn-block" style="margin-right:15px" value="KOT Table View" >
               </form>
	          </div> 
	          <div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
	          
            <form name="FormPosKot" id="FormPosKot" action="kotbilling.php?submenu=177" method="post">
            <input type="hidden" value="1" name="FormSubmitPosKot" />
            <input type="hidden" value="<?php echo $_REQUEST['submenu'];?>" name="submenu1" id="submenu1">
            <input type="hidden" name="id_attribute_table" id="id_attribute_table" value="<?php echo $rowDiffPrint->id_attribute_table;?>"/>
            <input type="hidden" name="outlet" id="outlet" value="<?php echo $rowDiffPrint->outlet;?>"/>
            
            
            
            <a class="btn n-btn btn-block " style="margin-right:15px;width:104px!important;" onClick="GoToBill();"> Go to Bill</a >
            </form>
				  
				 <a href="kds.php?submenu=178" class="btn n-btn btn-block " style="margin-right:15px;width:104px!important;" > KDS</a >  
	          </div> 
        </div>
 </div>
<?php } ?>
 <?php /*?><div class="col-md-3 col-lg-2 order-sm-first">
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
 </div><?php */?>



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


<div class="modal fade outletmodal" id="tableModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     
      <div class="modal-body">
    
        <div id="myDIV">
            <div class="row">

            

            <div class="col-md-12" id="hideGroup">
                <div class="form-group mb-0 text-center">
                <label for="name">Select Outlet </label>
                <div class="box-body table-responsive">
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

											

echo $categoryDropDown ='<tr ><td><a href="#" name="outletid" id="outletid" type="button" class="btn n-btn btn-block" value="'.ucfirst($resultCat->id).'" style="width: 100% !important;" onclick="SelectOutlet('.ucfirst($resultCat->id).');" >'.ucfirst($resultCat->name).'</a></td></tr>';


										

									}

								  }

								 	//echo $categoryDropDown;

								  ?>
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
<?php include_once("../includes/footer.php")?>
<script language="javascript">
         <?php if($_REQUEST['setPrint']==1 && $EnablePrintAfterSave ==1){?>
	   window.onload = function() {
			
		 printData();
		 ///alert('KOT Printed successfully. ');
				window.location.href = "managePosKot.php?submenu=<?php echo $_REQUEST['submenu'] ?> ";
		 	
		}
	   
	   
	   <?php } ?>
      function printData()
        {
			
			var items = [<?php echo '"'.implode('","', $printdivvalue).'"' ?>];
        //alert(items);
   if(items!=''){
       items.forEach(function (itemddd) {
           // alert(itemddd);
		var divToPrint=document.getElementById(itemddd);
           newWin= window.open("");
           newWin.document.write(divToPrint.outerHTML);
           newWin.print();
           newWin.close();
        });
   }   
		   // Print Network Printer  
		 printNetWorkPrinter(<?php echo encryptor(decrypt, $_REQUEST['printPreviewid']); ?>); 
		  // // Print Network Printer 
		   
        } 

        $('button').on('click',function(){
        printData();
        });
		function GoToBill(){
			$('#tableModal').modal('show');				
			   
			   }
			   
			function SelectOutlet(id){
				
				$('#outlet').val(id);
				$('#tableModal').hide();
				 document.getElementById("FormPosKot").submit();// Form submission
				}   
    </script>


<script type="text/javascript">
	function printNetWorkPrinter(printPurchID){

		$.ajax({

		type: "GET",

		url: 'ajax/printNetWorkFunction.php',

		data: 'print_posid='+printPurchID, 

		success: function (result) {

			//alert('Printed Successfully');				

				

	 	}

	});



	}
</script>