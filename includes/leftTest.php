<!-- Left side column. contains the logo and sidebar -->
<?php


if($_SESSION['userLevel']!=1){

  $ids_user_menu_access=array();
  $ids_user_sub_menu=array();

  $sqlUserMenu="SELECT id_menu FROM ".TBL_MENU_ACCESS." WHERE id_mst_user_levels='".$_SESSION['userLevel']."'AND FIND_IN_SET(id_mst_modules,'".$_SESSION['module_access']."') AND status=1  AND id_shop='".$_SESSION['shop']."' ";

  $resUserMenu = mysqli_query($connNew,$sqlUserMenu);
  while($rowUserMenu = mysqli_fetch_object($resUserMenu))
    array_push($ids_user_menu_access,$rowUserMenu->id_menu);

  $ids_user_menu_access=implode(',',$ids_user_menu_access);

  $sqlUserSubMenu="SELECT id_sub_menu FROM ".TBL_USER_PERMISSIONS." WHERE id_mst_user_levels='".$_SESSION['userLevel']."'AND FIND_IN_SET(id_mst_modules,'".$_SESSION['module_access']."') AND FIND_IN_SET(id_menu,'".$ids_user_menu_access."')  AND status=1  AND id_shop='".$_SESSION['shop']."' ";
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
$CheckCode	=	selectColumn(TBL_SHOP,'short_code'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");
if($CheckCode=='ICON'){
	
	
	
	//echo '===================';
		
		$last_login	= selectColumn('mst_users','last_login'," WHERE `id` = '".addslashes($_SESSION['userId'])."'");
	
	

$new_time = date("Y-m-d H:i:s", strtotime('+30 minutes', strtotime(date("Y-m-d H:i:s",strtotime($last_login)))));
	//echo '==='.date("Y-m-d H:i:s");
	if(strtotime($new_time)<strtotime(date("Y-m-d H:i:s"))){
	//echo 'Sucess';
				
		
	}else{
	//echo 'Fail';
	
	}
	
	
	
	
	if($_SESSION['userId']=='251'){
				
		
	}
//echo 'ICON';
	//print_r($_SESSION);
}
echo $_SERVER['DOCUMENT_ROOT'].'/uploaded_files/shop/'.$resLogo;


?>
<aside class="main-sidebar"> <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar"> <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image"> 
		<?php 
		if((file_exists($_SERVER['DOCUMENT_ROOT'].'/uploaded_files/shop/'.$resLogo )) && $resLogo !=''){  
		  
		  
		  ?>  
		  <img src="<?php echo $SITE_URL; ?>/uploaded_files/shop/<?php echo $resLogo; ?>" class="img-circle" alt="User Image"> 
	<?php 	}

?>
		
		</div>
      <div class="pull-left info">
        <p><button data-toggle="modal" data-target="#addShopDetailsModal" style="text-decoration:underline;background-color: transparent;border: none;" onclick="addShopDetails();"> <?php //echo $_SESSION['userName'];?>
         <?php echo selectColumn(TBL_SHOP,'short_code'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");?></button></p>
      </div>
    </div>
    <div>
      <input data-list="#myMenu" class="form-control awesomplete " type="text" name="menuSearch" id="menuSearch" placeholder="Search Menu...">
      <datalist id="myMenu" class="awesomplete-selectcomplete">
          <?php
            $sqlSubMenu="SELECT * FROM ".APP_SUB_MENU." WHERE 1=1 and status='1'  ".$subMenuCond."   order by display_order";
           
            $resSubMenu = mysqli_query($appConnect,$sqlSubMenu);

            while($rowSubMenu = mysqli_fetch_object($resSubMenu)){
              $moduleName = selectField(APP_MODULE,'name','WHERE id="'.$rowSubMenu->id_module.'"',$appConnect); 
           ?>
           <option value="<?php echo $SITE_URL.'/'.$moduleName.'/'.$rowSubMenu->file_name.'?submenu='.$rowSubMenu->id; ?>"><?php echo ucwords(strtolower($rowSubMenu->name)) ; ?></option>
        <?php } ?>
      </datalist> 
      <!--<ul class="awesomplete-selectcomplete"  id="myMenu" style="display: none;" >
        <?php
          $sqlSubMenu="SELECT * FROM ".APP_SUB_MENU." WHERE 1=1 ".$subMenuCond."   order by display_order";
         
          $resSubMenu = mysqli_query($appConnect,$sqlSubMenu);
          while($rowSubMenu = mysqli_fetch_object($resSubMenu)){
         ?>
         <a href="<?php echo $SITE_URL.'/'.$rowModuleMenu->name.'/'.$rowSubMenu->file_name; ?>" rel="<?php echo $rowSubMenu->file_name ?>"><?php echo ucwords(strtolower($rowSubMenu->name)) ; ?></a>
      <?php } ?>
      </ul> -->
    </div>
    
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MAIN NAVIGATION</li>
      
      <?php 
      
      $sqlModulesMenu = "SELECT * FROM ".APP_MODULE." WHERE id IN (".$_SESSION['module_access'].") ORDER BY display_order" ;
      $resModuleMenu = mysqli_query($appConnect,$sqlModulesMenu);
      while($rowModuleMenu = mysqli_fetch_object($resModuleMenu)){
      ?>
      
      <li class="treeview"  > <a style="color:<?php echo $rowModuleMenu->color; ?>" href="#"> <i  class="fa <?php echo $rowModuleMenu->icon;?> "></i> <span><?php echo strtoupper($rowModuleMenu->name); ?></span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
         <?php 
              if($_SESSION['userLevel']!=1){
                $ids_user_menu_access=array();
                $sqlUserMenu="SELECT id_menu FROM ".TBL_MENU_ACCESS." WHERE id_mst_user_levels='".$_SESSION['userLevel']."'AND id_mst_modules='".$rowModuleMenu->id."' AND status=1  AND id_shop='".$_SESSION['shop']."' ";

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
                      $sqlSubMenu="SELECT * FROM ".APP_SUB_MENU." WHERE status='1' and type='1' and id_menu='".$rowMenu->id."' ".$subMenuCond." AND id_module='".$rowModuleMenu->id."' order by display_order";

                      $resSubMenu = mysqli_query($appConnect,$sqlSubMenu);
                      while($rowSubMenu = mysqli_fetch_object($resSubMenu)){
                    ?>
                    <li><a href="<?php echo $SITE_URL.'/'.$rowModuleMenu->name.'/'.$rowSubMenu->file_name.'?submenu='.$rowSubMenu->id; ?>&session=<?php echo $rowSubMenu->id_document; ?>" rel="<?php echo $rowSubMenu->file_name ?>"><i class="fa fa-arrow-circle-right"></i><?php if(strtolower($rowSubMenu->name)=='pos'){ echo strtoupper($rowSubMenu->name); }else{ echo ucwords(strtolower($rowSubMenu->name)); } ?></a></li>
                    
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

  <!--Guest Modal Starts-->

      <div id="addShopDetailsModal"  role="dialog" tabinex="-1" class="modal well p-4" > 
        <div class="modal-dialog">
           <form id="" autocomplete="on">
                <?php
                $name = selectColumn(TBL_SHOP,'name'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");
                $short_code = selectColumn(TBL_SHOP,'short_code'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");
                $address = selectColumn(TBL_SHOP,'address'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");
               $email = selectColumn(TBL_SHOP,'email'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");
               $city = selectColumn(TBL_SHOP,'city'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");
                ?>
                 <h4 class="title">Shop Details</h4>

                  <div class="form-group">
                    <label for="title">Shop Name</label>
                    
                    <input type="text" class="form-control input-sm" value="<?=$name?>"/>
                  </div>

                  <div class="form-group">
                    <label for="title">Shop Code</label>
                    
                    <input type="text" class="form-control input-sm" value="<?=$short_code?>"/>
                  </div>
                  <div class="form-group">
                    <label for="title">Address</label>
                    
                    <textarea type="text" rows="3" cols="3" class="form-control input-sm"><?=$address?></textarea>
                  </div>
                  <div class="form-group">
                      <label for="title">City</label>
                     <input type="text" class="form-control input-sm" value="<?=$city?>"/>
                 </div> 
                  <div class="form-group">   
                    <a  data-dismiss="modal"  class="cancelpop_close btn c-btn"><i class="far fa-window-close"></i> Close</a>
                  </div>
       
      
              </form>
            </div><!--end of modal-dilog-->  
    </div>
    <!--Guest Modal Ends-->


  

  
  
