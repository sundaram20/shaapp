<?php include_once("config/auto_loader.php"); ?>

<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="form-group has-error mb-0" align="center">
          <?php if($_SESSION['errorMsg']){?>
          <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
          <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
          <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
          <?php unset($_SESSION['successMsg']);}?>
        </div>
    	<br><br>
    
     
	     	<div class="row d-flex">
	     		<div class="col-md-12 dash-box">
<?php	//	if($_SESSION['database']!='icon' ){ ?>
	     			  <div class="btn-group  "> <a type="button"  title="Add KOT" class="btn n-btn n-btn-l pull-right" href="pos/managePosKot.php?submenu=178" ><i class="fas fa-plus"></i>&nbsp;KOT </a> </div>
				<?php //} ?>	<div class="btn-group  "> <a type="button"  title="Add KOT" class="btn n-btn n-btn-l pull-right" href="pos/managePosKot.php?submenu=179&doc_type=nc" ><i class="fas fa-plus"></i>&nbsp;KOT NC</a> </div>
	     		      <div class="btn-group"> <a type="button"  title="Kitchen Display System" class="btn n-btn n-btn-l pull-right" href="pos/kds.php?submenu=178" ><i class="fas fa-tv"></i>&nbsp;KDS </a> </div>    <?php		if($_SESSION['database']!='icon' && $_SESSION['database']!='jungle_home'){ ?>
	     			 <div class="btn-group"> <a type="button"  title="KOT Table View" class="btn n-btn n-btn-l pull-right" href="pos/pendingkots.php?submenu=178" > <i class="fas fa-table"></i>&nbsp;KOT</a> </div>
					<?php  } ?>
	     		
	     		

	     			 <div class="btn-group"> <a type="button"  title="Add Bill" class="btn n-btn n-btn-l pull-right" href="pos/kotbilling.php?submenu=177&session=21" ><i class="fas fa-plus "></i>&nbsp;Bill </a> </div>     
	     			 <div class="btn-group"> <a type="button"  title="List Bill" class="btn n-btn n-btn-l  pull-right" href="pos/manageOutletBilling.php?submenu=177&session=21" > <i class="fas fa-list "></i>&nbsp;Bill</a> </div>
					
					 <div class="btn-group"> <a type="button"  title="List KOT" class="btn n-btn n-btn-l  pull-right" href="pos/manageKot.php?submenu=178&session=22" > <i class="fas fa-list "></i>&nbsp;KOT</a> </div>
					
					
	     			<?php //debugData($_SESSION);
					if($_SESSION['database']=='mmr_pms' || $_SESSION['database']=='adl' || $_SESSION['database']=='icon00' ||  $_SESSION['database']=='demo' ||  $_SESSION['database']=='demo_pos' ){ ?>
                    <div class="btn-group"> <a type="button" href="pos1/PosOneWindow1.php?submenu=178" title="List KOT" class="btn n-btn n-btn-l pull-right" style="width:352px!important;"> &nbsp; POS  Onewindow </a> </div>   

<?php } ?>
	    <div class="btn-group"> <a type="button" onClick="AddPosGuest();" title="List KOT" class="btn n-btn n-btn-l pull-right"> <i class="fas fa-plus"></i>&nbsp;POS Guest </a> </div>    

	     		</div>


	     
	     </div>   	
     </section>	
          

  </div>
  <!-- /.content-wrapper -->
   
<?php include_once("includes/footer.php")?>
