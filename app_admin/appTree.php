<?php session_start();	
include("config/data.config.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/msgs.inc.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");

$connMenu = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,'app');

$sqlModulesMenu = "SELECT * FROM ".APP_MODULE." ORDER BY display_order" ;
$resModuleMenu = mysqli_query($connMenu,$sqlModulesMenu);
    
    echo '<br>------APP MAP-------<br>';
    echo '<ul>';
    while($rowModuleMenu = mysqli_fetch_object($resModuleMenu)){  ?>

    	<li>
    		 <?php
    			echo $rowModuleMenu->name.'<ul>';
    			$sqlMenu ="SELECT  *  FROM ".APP_MENU." WHERE FIND_IN_SET(".$rowModuleMenu->id.",ids_module) order by display_order " ;
                       
           		$resMenu=mysqli_query($connMenu,$sqlMenu);
            	while($rowMenu = mysqli_fetch_object($resMenu)){
    		?>
				<li>
					<?php	echo $rowMenu->name.'<ul>';
    			$sqlSubMenu="SELECT * FROM ".APP_SUB_MENU." WHERE id_menu='".$rowMenu->id."' AND id_module='".$rowModuleMenu->id."' ";
                      
                      $resSubMenu = mysqli_query($connMenu,$sqlSubMenu);
                      while($rowSubMenu = mysqli_fetch_object($resSubMenu)){	
                      echo '<li>'.$rowSubMenu->name.'</li>';	
    				?>
    				
    			<?php } echo '</ul>'; ?>
				
				</li>

    		<?php } echo '</ul>';?>
    	</li>

<?php  } echo '</ul>'; ?>