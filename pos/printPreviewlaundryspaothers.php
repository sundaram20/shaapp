<?php include_once("../config/auto_loader.php");

if($_GET['submenu']==213 & $_GET['session']==26){
include_once("include/Spafunction.php"); 
  $invociePOSWidth = '80mm';
  $pre = 'background:#f5f5f5important';
  $preFontSize = '12px!important;';

} else{
  include_once("include/function1.php"); 
  $invociePOSWidth = '220mm';
    $pre = 'background:#fff!important';
  $preFontSize = '13px!important;';


}
include_once("include/inv_function.php");  ?>
<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>


<style>
 body{font: 13px 'Segoe UI', Tahoma, Arial, Helvetica, sans-serif;}   
#invoice-POS{
	 
  box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
  padding:2mm;
  margin: 0 auto;
 width: <?php echo $invociePOSWidth;?>; 

  background: #FFF;
  
  
::selection {background: #f31544; color: #FFF;}
::moz-selection {background: #f31544; color: #FFF;}
pre{
  background:<?php echo $pre;?>;
}
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
  padding: 5px 0 5px 15px;
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

#bot pre{
	overflow: hidden;
  font-size:<?php echo $preFontSize;?>
}
</style>



<?php 
$value = $_REQUEST['value'];
if($_REQUEST['printPreviewid']!=''){
$pos_purch_id_array[]	=	encryptor(decrypt, $_REQUEST['printPreviewid']);
$printgroup			  = 	fetchdataprint($pos_purch_id_array,$grouparray=0);
}

?>





  <div class="content-wrapper"> 
    
    <!-- Content Header (Page header) -->
    <?php $session = $_REQUEST['session'];
session_start();
 $mydata = $_SESSION["myid"] + 1;
			

if($session=='25'){
	$print = "Laundry ";
}else if($session=='26'){
	$print = "Spa and Health Club";
}else if($session=='29'){
	$print = "Others Print";
}

?>
	

	
    <section class="content-header">
     <!-- <h1> <?php echo $print; ?> </h1>-->
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo $print; ?> </li>
      </ol>
    </section>
    
    <!-- Main content -->
    
    <section class="content">
   
    <div class="row">




<?php

//echo $printgroup;
//echo $value;

if($_REQUEST['printPreviewid']==''){
	$id=$_REQUEST['updateid'];
}else {
	$id=$_REQUEST['printPreviewid'];
}
//echo encryptor(encrypt, $mydata);
?>


<div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
  <a href="editOutletBill.php?session=<?php echo $_SESSION['id_document']; ?>&submenu=<?php echo $_GET['submenu']; ?>">
    <div class="btn c-btn btn-block" style="margin-right:15px" ><i class="fa fa-pencil fa-1x"></i> Add</div >
   </a>
</div>

<?php if($_REQUEST['printPreviewid']!='' || $_REQUEST['updateid']!=''){  ?>

<div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
	<a href="editOutletBill.php?updateid=<?php echo $id ?>&session=<?php echo $session ?>&submenu=<?php echo $_REQUEST['submenu'] ?> ">
		<div class="btn c-btn btn-block" style="margin-right:15px" ><i class="fa fa-pencil-square-o fa-1x"></i> Edit</div >
	</a>
</div>

<?php } else {?>
<div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
	<a href="editOutletBill.php?updateid=<?php echo encryptor(encrypt, $mydata) ?>&session=<?php echo $session ?>&submenu=<?php echo $_REQUEST['submenu'] ?> ">
		<div class="btn c-btn btn-block" style="margin-right:15px" ><i class="fa fa-pencil-square-o fa-1x"></i> Edit</div >
	</a>
</div>	
		
<?php } ?>
        



<div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
	<a href="manageOutletBill.php?session=<?php echo $_REQUEST['session'] ?>&submenu=<?php echo $_REQUEST['submenu'] ?> ">
	  <div class="btn c-btn btn-block" style="margin-right:15px" ><i class="fa fa-list fa-1x"></i> List</div >
	 </a>
</div>
 
<div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
<button class="btn c-btn btn-block" style="margin-right:15px" onClick="printdiv('div_print');"><i class="fa fa-print fa-1x"></i> Print</button >
</div>


      <div class="col-xs-12"> 
        
        <!-- /.box -->
        
        <div class="box">
          <div class="box-header">
           
          </div>
          <div id="printTable"> 
            <div id="invoice-POS" > 
              <!--End InvoiceTop--> 
              
              <!--End Invoice Mid-->
              
						<div  id="bot" > <?php echo '<pre>'; echo date("d-m-Y H:i:s");
							echo $printer	=	printPreview($printgroup);
							echo '</pre>';
						?> </div>
              
              <!--<div id="legalcopy">
						<p class="legal"><strong>Thank you ...</strong> 
						</p>
					</div>--> 
              
            </div>
          </div>
          
          <!--End InvoiceBot--> 
        </div>
		
	<!--End Invoice-->
        
        <?php


?>
        <!-- /.box-body --> 
        
      </div>
      
      <!-- /.box --> 
      
    </div>
    
    <!-- /.col --> 
    
  </div>
  
  <!-- /.row -->
  
  </section>
  
  <!-- /.content --> 
  
</div>
<script language="javascript">
       
      function printData()
        {
           var divToPrint=document.getElementById("printTable");
           newWin= window.open("");
           newWin.document.write(divToPrint.outerHTML);
           newWin.print();
           newWin.close();
        } 

        $('button').on('click',function(){
        printData();
        });
    </script>
<?php include_once("../includes/footer.php")?>
