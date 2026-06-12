<!-- Left side column. contains the logo and sidebar -->
<?php

$connMenu = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,'app');

/*
all module ids
id  name
0 means, it will show on entry page 
1 crs
2 pms
3 be
4 website
5 master
6 report
7 sales

*/



?>
<aside class="main-sidebar"> <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar"> <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image"> <img src="<?php echo $SITE_URL; ?>/uploaded_files/shop/<?php echo $resLogo; ?>" class="img-circle" alt="User Image"> </div>
      <div class="pull-left info">
        <p><?php echo $_SESSION['userName'];?> </p>
      </div>
    </div>
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MAIN NAVIGATION</li>
      
      
      
      <li class="treeview"> <a href="#"> <i class="fa fa-laptop"></i> <span>Entry</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
         <?php 
            $sqlEntryMenu ="SELECT  *  FROM ".APP_SUB_MENU." WHERE FIND_IN_SET(`id_module`,'".$_SESSION['module_access']."') AND `id_menu` = 0 " ;
            $sqlEntryMenu=mysqli_query($connMenu,$sqlEntryMenu);
            while($rowEntryMenu = mysqli_fetch_object($sqlEntryMenu)){
                $sqlDir = "SELECT name FROM ".APP_MODULE." WHERE id='".$rowEntryMenu->id_module."' ";
                $directory=mysqli_fetch_object(mysqli_query($connMenu,$sqlDir));
              ?>

                <li><a href="<?php echo $SITE_URL.'/'.$directory->name.'/'.$rowEntryMenu->file_name; ?>" rel="editShop.php"><i class="fa fa-circle-o"></i> <?=$rowEntryMenu->name;?></a></li>

              <?php
            }
          ?>
        </ul>
      </li>

      <!--<li class="treeview"> <a href="#"> <i class="fa fa-dollar"></i> <span>Rates</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
         
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageRateLetters.php" rel="editRateLetters.php"><i class="fa fa-circle-o"></i> Rate Letters </a></li>
          
        </ul>
      </li>-->

      <!--<li class="treeview"> <a href="#"> <i class="fa fa-file-excel-o"></i> <span>Reports</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
          <li class="treeview"> <a href="#"> <i class="fa fa-file-excel-o"></i> <span>Sales Report</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/ManagervisitReport.php" rel="editCompany.php"><i class="fa fa-circle-o"></i>Daily Sales Report</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/ManagerFollowupReport.php" rel="ManagerFollowupReport.php"><i class="fa fa-circle-o"></i> Follow up</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/ManagerFeedBackReport.php" rel="ManagerFeedBackReport.php"><i class="fa fa-circle-o"></i> Feed Back</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageConveyance.php" rel="manageConveyance.php"><i class="fa fa-circle-o"></i>Conveyance Report</a></li>
            <li><a href="<?php echo $SITE_URL; ?>/adminpanel/companyPortfolio.php" rel="companyPortfolio.php.php"><i class="fa fa-circle-o"></i>Company Portfolio</a></li>
            <li><a href="<?php echo $SITE_URL; ?>/adminpanel/executiveMtdYtd.php" rel="executiveMtdYtd.php.php"><i class="fa fa-circle-o"></i>Executive Wise Report</a></li>
            </ul>
          </li>

          <li class="treeview"> <a href="#"> <i class="fa fa-file-excel-o"></i> <span>Rate Report</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageHotelwiseContractReport.php" rel="editHotelwiseContractReport.php"><i class="fa fa-circle-o"></i>Hotelwise Contract Report</a></li>
            </ul>
          </li>
        </ul>
      </li>-->
      <!--Booking Engine-->
       <?php

            $sqlMasterMenu ="SELECT  *  FROM ".APP_MENU." WHERE FIND_IN_SET(`id_module`,'".$_SESSION['module_access']."') AND FIND_IN_SET(`id_module`,3)";

            $resMasterMenu = mysqli_query($connMenu,$sqlMasterMenu);
            while($rowMasterMenu = mysqli_fetch_object($resMasterMenu)){
          ?>
             
            <li class="treeview"><a href="#" rel=""><i class="fa fa-circle-o"></i><span><?=$rowMasterMenu->name;?></span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span></a>
              <ul class="treeview-menu"> 
          
            <?php
              $sqlLink = "SELECT * FROM ".APP_SUB_MENU." WHERE id_module = '".$rowMasterMenu->id_module."' AND id_menu = '".$rowMasterMenu->id."' ORDER BY display_order";

              $resMasterSubMenu = mysqli_query($connMenu,$sqlLink);
              while($rowMasterSub = mysqli_fetch_object($resMasterSubMenu)){
                $sqlDir = "SELECT name FROM ".APP_MODULE." WHERE id='".$rowMasterMenu->id_module."' ";

                $directory=mysqli_fetch_object(mysqli_query($connMenu,$sqlDir));
            ?>
               <li><a href="<?php echo $SITE_URL.'/'.$directory->name.'/'.$rowMasterSub->file_name; ?>" rel="editShop.php"><i class="fa fa-circle-o"></i> <?=$rowMasterSub->name;?></a></li>
                              
          <?php }
          echo '</ul></li>';
           }
            ?>




      <!--Masters-->
      <li class="treeview"> <a href="#"> <i class="fa fa-bars"></i> <span>Masters</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
               
          
          <?php

            $sqlMasterMenu ="SELECT  *  FROM ".APP_MENU." WHERE FIND_IN_SET(`id_module`,'".$_SESSION['module_access']."') AND FIND_IN_SET(`id_module`,5)";

            $resMasterMenu = mysqli_query($connMenu,$sqlMasterMenu);
            while($rowMasterMenu = mysqli_fetch_object($resMasterMenu)){
          ?>
             
            <li class="treeview"><a href="#" rel=""><i class="fa fa-circle-o"></i><span><?=$rowMasterMenu->name;?></span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span></a>
              <ul class="treeview-menu"> 
          
            <?php
              $sqlLink = "SELECT * FROM ".APP_SUB_MENU." WHERE id_module = '".$rowMasterMenu->id_module."' AND id_menu = '".$rowMasterMenu->id."'";

              $resMasterSubMenu = mysqli_query($connMenu,$sqlLink);
              while($rowMasterSub = mysqli_fetch_object($resMasterSubMenu)){
                $sqlDir = "SELECT name FROM ".APP_MODULE." WHERE id='".$rowMasterMenu->id_module."' ";

                $directory=mysqli_fetch_object(mysqli_query($connMenu,$sqlDir));
            ?>
               <li><a href="<?php echo $SITE_URL.'/'.$directory->name.'/'.$rowMasterSub->file_name; ?>" rel="editShop.php"><i class="fa fa-circle-o"></i> <?=$rowMasterSub->name;?></a></li>
                              
          <?php }
          echo '</ul></li>';
           }
            ?>
        </ul>
      </li>
      
      <!---------->
    </ul>
  </section>
  <!-- /.sidebar --> </aside>
  <?php mysqli_close($connMenu);?>
