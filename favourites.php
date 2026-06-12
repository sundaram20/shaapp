<?php include_once("config/auto_loader.php"); ?>
<?php include_once("includes/header.php"); ?>
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
    <br>
    <style>
  /* General Box Styling */
  .cstmSeriesBox {
    border: 1px solid #f56616;
    padding: 10px 20px;
    border-radius: 12px;
    background: linear-gradient(to right, #ffffff, #f3f4f6);
    box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1), 0px 6px 6px rgba(0, 0, 0, 0.1);
    max-width: 300px;
    transition: transform 0.3s ease-in-out;
  }

  /* Hover Effect for the Whole Box */
  .cstmSeriesBox:hover {
    transform: translateY(-10px);
  }

  /* List Styling */
  .cstmSeriesBox ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
  }

  /* List Item Styling */
  .cstmSeriesBox ul li {
    margin-bottom: 20px;
    position: relative;
  }

  /* Link Button Styling */
  .cstmSeriesBtn {
    display: flex;
    align-items: center;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    color: #333;
    padding: 12px 15px;
    border-radius: 8px;
    background-color: #f8f9fa;
    transition: all 0.3s ease-in-out;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    position: relative;
    z-index: 1;
  }

  /* Add subtle 3D effect */
  .cstmSeriesBtn::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, #f56616 0%, #f98733 100%);
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
    border-radius: 8px;
    z-index: -1;
  }

  .cstmSeriesBtn:hover::before {
    opacity: 1;
  }

  .cstmSeriesBtn i {
    margin-right: 10px;
    font-size: 20px;
    color: #007bff;
    transition: color 0.3s ease-in-out;
  }

  /* Hover Effects */
  .cstmSeriesBtn:hover {
    color: #fff;
    transform: translateY(-3px);
  }

  .cstmSeriesBtn:hover i {
    color: #fff;
  }

  /* Optional: Add a small glow effect on hover */
  .cstmSeriesBtn:hover {
    box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.2), 0px 12px 20px rgba(255, 102, 22, 0.2);
  }

  /* Mobile responsiveness */
  @media (max-width: 768px) {
   .row{
    flex-wrap : wrap!important;
    justify-content : center;
   }

    .thisIsCstmShotBx{

      width : 80%!important;
    }

    .cstmSeriesBox {
      max-width: 90%;
      margin-bottom: 20px;
    }

    .cstmSeriesBtn {
      font-size: 14px;
      padding: 10px 12px;
    }

    .cstmSeriesBtn i {
      font-size: 18px;
      margin-right: 8px;
    }
  }

  @media (max-width: 480px) {
    .cstmSeriesBtn {
    
      
      padding: 10px;
    }

    .cstmSeriesBtn i {
      margin-bottom: 8px;
      margin-right: 0;
      font-size: 16px;
    }

    .cstmSeriesBox {
      padding: 10px;
    }

   .cstmSeriesBox h3 {
      font-size: 18px;
    }

    .cstmSeriesBtn i{
      margin-right : 0.8rem!important;

    }
  }
</style>
    <div class="row d-flex justify-center">
      <div class="col-md-3 col-sm-12 thisIsCstmShotBx">
        <div class="cstmSeriesBox">
          <h3 style="margin-top: 0 !important; font-weight: 500; color: #3d3d3d; border-bottom : 1px solid #eee; padding : 5px 0px 10px 0px;"> Dashboard</h3>
          <ul>
            <li> <a type="button"  class="cstmSeriesBtn" href="frontoffice/fo_dashboard.php?submenu=179&doc_type=nc"> <i class="fas fa-chart-line"></i>Front Office </a> </li>
            <li> <a type="button"  class="cstmSeriesBtn" href="pos/Dashboard/Dashboard.php"> <i class="fas fa-chart-line"></i>POS</a> </li>
          </ul>
        </div>
      </div>
      <?php 
  
  $AccessArray	=	 explode(',',$_SESSION['module_access']);
  
     $FrontOfficesearchValue = 8;

// Check if the value exists in the array
if (in_array($FrontOfficesearchValue, $AccessArray)) { ?>
      <div class="col-md-3 col-sm-12 thisIsCstmShotBx">
        <div class="cstmSeriesBox">
          <h3 style="margin-top: 0 !important; font-weight: 500; color: #3d3d3d; border-bottom : 1px solid #eee; padding : 5px 0px 10px 0px;"> Front Office</h3>
          <ul>
            <li> <a type="button"  class="cstmSeriesBtn" href="frontoffice/onewindow.php"> <i class="fas fa-plus"></i>One Window </a> </li>
            <!--<li> <a type="button" class="cstmSeriesBtn" href="frontoffice/postTariff.php"> <i class="fas fa-plus"></i>Post Tariff </a> </li>-->
            <li> <a type="button" title="Kitchen Display System" class="cstmSeriesBtn" href="frontoffice/manageFoBill.php"> <i class="fas fa-list "></i>FO Bill </a> </li>
            <!-- <li>
          <a type="button"  class="cstmSeriesBtn" href="frontoffice/manageReceipt.php">
            <i class="fas fa-list "></i>Receipts
          </a>
        </li>-->
            <li> <a type="button"  class="cstmSeriesBtn" href="frontoffice/manageReservation.php"> <i class="fas fa-list "></i>Reservations </a> </li>
            
			   <li> <a type="button"  class="cstmSeriesBtn" href="frontoffice/ExpectedArrivalsReport.php?submenu=290&session=0" target="_blank"> <i class="fas fa-list "></i>&nbsp;Expected Arrivals</a> </li>
			  
			  <li> <a type="button"  class="cstmSeriesBtn" href="frontoffice/roomView.php" target="_blank"> <i class="fas fa-list "></i>&nbsp;Room View </a> </li>
			  
			  
			  
          </ul>
        </div>
      </div>
       <?php } 
  $PossearchValue = 2;

// Check if the value exists in the array
if (in_array($PossearchValue, $AccessArray)) { ?>
      <div class="col-md-3 col-sm-12 thisIsCstmShotBx">
        <div class="cstmSeriesBox">
          <h3 style="margin-top: 0 !important; font-weight: 500; color: #3d3d3d; border-bottom : 1px solid #eee; padding : 5px 0px 10px 0px;"> POS</h3>
          <ul>
            <li> <a type="button"  class="cstmSeriesBtn" href="pos/managePosKot.php?submenu=178"> <i class="fas fa-plus"></i>KOT </a> </li>
            <li> <a type="button"  class="cstmSeriesBtn" href="pos/manageKotNc.php?submenu=179&session=24 "> <i class="fas fa-plus"></i>KOT NC </a> </li>
            <li> <a type="button" class="cstmSeriesBtn" href="pos/manageKot.php"> <i class="fas fa-list"></i>KOT </a> </li>
            <li> <a type="button"  class="cstmSeriesBtn" href="pos/kotbilling.php?submenu=177&session=21"> <i class="fas fa-plus "></i>&nbsp;Bill </a> </li>
            <li> <a type="button"  class="cstmSeriesBtn" href="pos/manageOutletBilling.php?submenu=177&session=21"> <i class="fas fa-list "></i>&nbsp;Bill </a> </li>
            <li> <a type="button"  class="cstmSeriesBtn" href="frontoffice/roomView.php" target="_blank"> <i class="fas fa-list "></i>&nbsp;Room View </a> </li>
            <li> <a type="button"  class="cstmSeriesBtn" href="pos/posFlashReport.php?submenu=266&session=0" target="_blank"> <i class="fas fa-chart-line "></i>&nbsp;Flash Report </a> </li>
          </ul>
        </div>
      </div>
      <?php } 
      
      $invSearchValue = 6; //temp value

      // Check if the value exists in the array
if (in_array($invSearchValue, $AccessArray)) { ?>
      <div class="col-md-3 col-sm-12 thisIsCstmShotBx">
        <div class="cstmSeriesBox">
          <h3 style="margin-top: 0 !important; font-weight: 500; color: #3d3d3d; border-bottom : 1px solid #eee; padding : 5px 0px 10px 0px;"> Inventory</h3>
          <ul>
            <li> <a type="button"  class="cstmSeriesBtn" href="inventory/manageIndent.php?submenu=96&session=1"> <i class="fas fa-list"></i>Requestion Note</a> </li>
            <li> <a type="button"  class="cstmSeriesBtn" href="inventory/manageStoreIssueNote.php?submenu=102&session=6"> <i class="fas fa-list"></i>Store Issue Note</a> </li>
			  <li> <a type="button"  class="cstmSeriesBtn" href="inventory/manageGRN.php?submenu=100&session=4"> <i class="fas fa-list"></i>Goods Reciept Note</a> </li>
			  <li> <a type="button"  class="cstmSeriesBtn" href="inventory/StockReport.php?submenu=244&session=0"> <i class="fas fa-chart-line"></i>Stock Report</a> </li>
			  <li> <a type="button"  class="cstmSeriesBtn" href="inventory/VoucherReport.php?submenu=245&session=0"> <i class="fas fa-chart-line"></i>Voucher Report</a> </li>
          </ul>
        </div>
      </div>
      <?php }    
      ?>
    </div>
   
    <div class="row d-none">
      <div class="col-md-12 dash-box">
        <div class="btn-group  "> <a type="button"  class="btn n-btn n-btn-l pull-right"
            href="pos/managePosKot.php?submenu=178"><i class="fas fa-plus"></i>&nbsp;KOT </a> </div>
        <div class="btn-group  "> <a type="button"  class="btn n-btn n-btn-l pull-right"
            href="pos/managePosKot.php?submenu=179&doc_type=nc"><i class="fas fa-plus"></i>&nbsp;KOT NC</a> </div>
        <div class="btn-group"> <a type="button" title="Kitchen Display System" class="btn n-btn n-btn-l pull-right"
            href="pos/kds.php?submenu=178"><i class="fas fa-tv"></i>&nbsp;KDS </a> </div>
        <div class="btn-group"> <a type="button"  class="btn n-btn n-btn-l pull-right"
            href="pos/pendingkots.php?submenu=178"> <i class="fas fa-table"></i>&nbsp;KOT</a> </div>
        <div class="btn-group"> <a type="button" title="Add Bill" class="btn n-btn n-btn-l pull-right"
            href="pos/kotbilling.php?submenu=177&session=21"><i class="fas fa-plus "></i>&nbsp;Bill </a> </div>
        <div class="btn-group"> <a type="button" title="List Bill" class="btn n-btn n-btn-l  pull-right"
            href="pos/manageOutletBilling.php?submenu=177&session=21"> <i class="fas fa-list "></i>&nbsp;Bill</a> </div>
        <?php //debugData($_SESSION);
					if($_SESSION['database']=='mmr_pms' || $_SESSION['database']=='adl'){ ?>
        <div class="btn-group"> <a type="button" href="pos2/PosOneWindow1.php?submenu=178" title="List KOT"
            class="btn n-btn n-btn-l pull-right" style="width:352px!important;"> <i class="fas fa-plus"></i>&nbsp; POS
          Onewindow </a> </div>
        <?php } ?>
        <div class="btn-group"> <a type="button" onClick="AddPosGuest();" title="List KOT"
            class="btn n-btn n-btn-l pull-right"> <i class="fas fa-plus"></i>&nbsp;POS Guest </a> </div>
      </div>
    </div>
    
    
  </section>
</div>
<!-- /.content-wrapper -->

<?php include_once("includes/footer.php")?>
