<!-- Left side column. contains the logo and sidebar -->
<?php


if($_SESSION['userLevel']!=1){

  $ids_user_menu_access=array();
  $ids_user_sub_menu=array();

  $sqlUserMenu="SELECT id_menu FROM ".TBL_MENU_ACCESS." WHERE id_user_level='".$_SESSION['userLevel']."'AND FIND_IN_SET(id_module,'".$_SESSION['module_access']."') AND status=1  AND id_shop='".$_SESSION['shop']."' ";

  $resUserMenu = mysqli_query($connNew,$sqlUserMenu);
  while($rowUserMenu = mysqli_fetch_object($resUserMenu))
    array_push($ids_user_menu_access,$rowUserMenu->id_menu);

  $ids_user_menu_access=implode(',',$ids_user_menu_access);

  $sqlUserSubMenu="SELECT id_sub_menu FROM ".TBL_USER_PERMISSIONS." WHERE id_user_level='".$_SESSION['userLevel']."'AND FIND_IN_SET(id_module,'".$_SESSION['module_access']."') AND FIND_IN_SET(id_menu,'".$ids_user_menu_access."')  AND status=1  AND id_shop='".$_SESSION['shop']."' ";
  $resUserSubMenu = mysqli_query($connNew,$sqlUserSubMenu);
  

  while($rowUserSubMenu = mysqli_fetch_object($resUserSubMenu))
    array_push($ids_user_sub_menu,$rowUserSubMenu->id_sub_menu);

  $ids_user_sub_menu=implode(',',$ids_user_sub_menu);

  $menuCond=" AND FIND_IN_SET(id,'".$ids_user_menu_access."') ";
  $subMenuCond = " AND FIND_IN_SET(id,'".$ids_user_sub_menu."')";

}
else{
  $menuCond='';
  $subMenuCond='';
}


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
      
      <?php 
      
      $sqlModulesMenu = "SELECT * FROM ".APP_MODULE." WHERE id IN (".$_SESSION['module_access'].") ORDER BY display_order" ;
      $resModuleMenu = mysqli_query($appConnect,$sqlModulesMenu);
      while($rowModuleMenu = mysqli_fetch_object($resModuleMenu)){
      ?>
      
      <li class="treeview"> <a href="#"> <i class="fa <?php echo $rowModuleMenu->icon;?> "></i> <span><?php echo strtoupper($rowModuleMenu->name); ?></span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
         <?php 
              if($_SESSION['userLevel']!=1){
                $ids_user_menu_access=array();
                $sqlUserMenu="SELECT id_menu FROM ".TBL_MENU_ACCESS." WHERE id_user_level='".$_SESSION['userLevel']."'AND id_module='".$rowModuleMenu->id."' AND status=1  AND id_shop='".$_SESSION['shop']."' ";

                $resUserMenu = mysqli_query($connNew,$sqlUserMenu);
                while($rowUserMenu = mysqli_fetch_object($resUserMenu))
                  array_push($ids_user_menu_access,$rowUserMenu->id_menu);

                $ids_user_menu_access=implode(',',$ids_user_menu_access);
                $menuCond=" AND FIND_IN_SET(id,'".$ids_user_menu_access."') ";
              }
              else{
                $menuCond="";
              }
            $sqlMenu ="SELECT  *  FROM ".APP_MENU." WHERE FIND_IN_SET(".$rowModuleMenu->id.",ids_module) ".$menuCond."  order by display_order " ;
                        
            $resMenu=mysqli_query($appConnect,$sqlMenu);
            while($rowMenu = mysqli_fetch_object($resMenu)){
                
                
              ?>
                <li class="treeview"> <a href="#"> <i class="fa <?php echo $rowMenu->icon;?>"></i> <span><?php echo ucwords(strtoupper($rowMenu->name)); ?></span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
                  <ul class="treeview-menu">
                    <?php
                      $sqlSubMenu="SELECT * FROM ".APP_SUB_MENU." WHERE id_menu='".$rowMenu->id."' ".$subMenuCond." AND id_module='".$rowModuleMenu->id."' order by display_order";

                      
                      
                      $resSubMenu = mysqli_query($appConnect,$sqlSubMenu);
                      while($rowSubMenu = mysqli_fetch_object($resSubMenu)){
                    ?>
                    <li><a href="<?php echo $SITE_URL.'/'.$rowModuleMenu->name.'/'.$rowSubMenu->file_name; ?>" rel="<?php echo $rowSubMenu->file_name ?>"><i class="fa fa-circle-o"></i><?php echo ucwords(strtolower($rowSubMenu->name)) ; ?></a></li>
                    
                    <?php } ?>
                  </ul>
                </li>    

                

              <?php
            }
          ?>
        </ul>
      </li>
      <?php } ?>  
        
      <!---------->
    </ul>
  </section>
  <!-- /.sidebar --> </aside>
  
