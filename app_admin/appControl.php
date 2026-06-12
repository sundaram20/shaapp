<?php 
error_reporting(E_ALL);	

include("config/data.config.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/msgs.inc.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");

$connMenu = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,'app');

if(isset($_REQUEST['moduleForm'])){
   
  $sql="INSERT INTO ".APP_MODULE." SET 
        name='".$_REQUEST['name']."',
        icon='".$_REQUEST['icon']."',
        display_order='".$_REQUEST['display_order']."' ";



  if(mysqli_query($connMenu,$sql)){
    echo "<script>alert('Inserted')</script>";
  }
  else{
     echo "<script>alert('Failed')</script>";
  }

}
else if(isset($_POST['menuForm'])){

  echo $sql="INSERT INTO ".APP_MENU." SET 
        name='".$_REQUEST['name']."',
        ids_module='".implode(',',$_REQUEST['ids_module'])."',
        icon='".$_REQUEST['icon']."',
        display_order='".$_REQUEST['display_order']."' ";



  if(mysqli_query($connMenu,$sql)){
    echo "<script>alert('Inserted')</script>";
  }
  else{
     echo "<script>alert('Failed')</script>";
  }
}
else if(isset($_POST['submenuForm'])){

  echo $sql="INSERT INTO ".APP_SUB_MENU." SET 
        name='".$_REQUEST['name']."',
        id_menu='".$_REQUEST['id_menu']."',
        id_module='".$_REQUEST['id_module']."',
        file_name='".$_REQUEST['file_name']."',
        related_table_name='".$_REQUEST['table_name']."',
        display_order='".$_REQUEST['display_order']."' ";



  if(mysqli_query($connMenu,$sql)){
    echo "<script>alert('Inserted')</script>";
  }
  else{
     echo "<script>alert('Failed')</script>";
  }

}


?>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
<style type="text/css">
  input{
    width:120px;
  }
  .moduleDiv,.menuDiv,.submenuDiv{
    padding: 20px 0px 20px 10px;
    border:1px solid black;
  }
  
  .moduleDiv{
    background-color: #6db8f2;
  }
  .menuDiv{
    background-color: #00ffdd;
  }
    
  .submenuDiv{
    background-color: #f7e842;
  }
  
  .editDiv{
    background-color: #E3E3E3;
  }

</style>

<div class="moduleDiv">
  <h4>Add Module</h4>
  <form action="" method="POST">
    <label>Name : </label><input required="required" name="name" type="text">
    <label>Icon : </label><input required="required" name="icon" type="text">
    <label>Display Order : </label><input required="required" name="display_order" type="number">
    <input type="submit" name="moduleForm">
  </form>
</div>

<div class="menuDiv">
  <h4>Add Menu</h4>
  <form action="" method="POST">
   <label>Name : </label> <input name="name" required="required" type="text">
    <label>Select Modules: </label>
    <select multiple="multiple" required="" name="ids_module[]" id="ids_module">
      <option value="">---Select Module(s)---</option>
      <?php
      $sqlModulesMenu = "SELECT * FROM ".APP_MODULE." ORDER BY display_order" ;
      $resModuleMenu = mysqli_query($connMenu,$sqlModulesMenu);
      while($rowModuleMenu = mysqli_fetch_object($resModuleMenu)){
      ?>

      <option value="<?php echo $rowModuleMenu->id; ?>"><?php echo $rowModuleMenu->name; ?></option>

    <?php } ?>
    </select>
    <label>Icon : </label><input name="icon" required="required" type="text">
    <label>Display Order : </label><input name="display_order" type="text">
    <input type="submit" name="menuForm">
  </form>
</div>

<div class="submenuDiv">
  <h4>Add Submenu</h4>
  <form action="" method="POST">
    <label>Name : </label><input name="name" required="required" type="text">
     
     <select  required="" name="id_module" id="id_module">
       <option value="">---Select Module(s)---</option>
       <?php
       $sqlModulesMenu = "SELECT * FROM ".APP_MODULE." ORDER BY display_order" ;
       $resModuleMenu = mysqli_query($connMenu,$sqlModulesMenu);
       while($rowModuleMenu = mysqli_fetch_object($resModuleMenu)){
       ?>

       <option value="<?php echo $rowModuleMenu->id; ?>"><?php echo $rowModuleMenu->name; ?></option>

     <?php } ?>
     </select> 


    <label>select menu : </label>
    
    <select name="id_menu" id="id_menu">
      <option>--Select Menu---</option>
    </select>
    
    <label>File Name: </label><input name="file_name" required="required" type="text">
    <label>Related Table: </label><input name="table_name" required="required" type="text">
    <label>Display Order : </label><input name='display_order' required="required" type="text">
    <input type="submit" name="submenuForm">
  </form>
</div>





<div class="editDiv">
  <?php
  $sqlModulesMenu = "SELECT * FROM ".APP_MODULE." ORDER BY display_order" ;
  $resModuleMenu = mysqli_query($connMenu,$sqlModulesMenu);
      
      echo '<br>------Edit Values-------<br>';
      echo '<ul>';
      while($rowModuleMenu = mysqli_fetch_object($resModuleMenu)){  ?>
            
            

        <li>
           <?php
            echo $rowModuleMenu->name.' ( id : '.$rowModuleMenu->id.' )<ul>';
            $sqlMenu ="SELECT  *  FROM ".APP_MENU." WHERE FIND_IN_SET(".$rowModuleMenu->id.",ids_module) order by display_order " ;
                         
                $resMenu=mysqli_query($connMenu,$sqlMenu);
                while($rowMenu = mysqli_fetch_object($resMenu)){
          ?>
          <li>
            <?php echo $rowMenu->name.' ( id : '.$rowMenu->id.' )<ul>';
           
            $sqlSubMenu="SELECT * FROM ".APP_SUB_MENU." WHERE id_menu='".$rowMenu->id."' AND id_module='".$rowModuleMenu->id."' ORDER BY display_order";
                        
                $resSubMenu = mysqli_query($connMenu,$sqlSubMenu);
                  while($rowSubMenu = mysqli_fetch_object($resSubMenu)){  
                        
            
              ?>
                <li>
                 <label>Name : </label>
                 <input  onchange="updateMe('<?php echo $rowSubMenu->id; ?>',this.name,this.value);" type="text" name="name" value="<?php echo $rowSubMenu->name ;?>">
                
                <label>Module id:</label>
                <input onchange="updateMe('<?php echo $rowSubMenu->id; ?>',this.name,this.value);" type="number" name="id_module" value="<?php echo $rowSubMenu->id_module ;?>">

                <label>Menu id: </label>
                <input onchange="updateMe('<?php echo $rowSubMenu->id; ?>',this.name,this.value);" type="number" name="id_menu" value="<?php echo $rowSubMenu->id_menu;?>">
                
                <label>File Name: </label>
                <input style="width:300px;" onchange="updateMe('<?php echo $rowSubMenu->id; ?>',this.name,this.value);" required="required" name="file_name" type="text" value="<?php echo $rowSubMenu->file_name ;?>">

                
                <label>Display Order : </label>
                <input onchange="updateMe('<?php echo $rowSubMenu->id; ?>',this.name,this.value);" required="required" name="display_order" type="text" value="<?php echo $rowSubMenu->display_order ;?>">


                <input onclick="deleteMe('<?php echo $rowSubMenu->id; ?>');"  name="delete" type="button" value="delete">
                </li>

            <?php } echo '</ul>'; ?>
          
          </li>

          <?php } echo '</ul>';?>
        </li>

  <?php  } echo '</ul>'; ?>
</div>

<script type="text/javascript">
  
  $(document).ready(function(){
      $("#id_module").change(function(){
        var id = $(this).val();
        console.log(id);
        $.ajax({
          url:'fetchMenu.php',
          data:'id_module='+id,
          type:'GET',
          success:function(data){
            $("#id_menu").html(data);
            //console.log(data);
          }
        })
      });
  });

  function updateMe(id,filed,value){
    $.ajax({
      url:'updateSubMenu.php',
      data:'id='+id+'&filed='+filed+'&value='+value,
      type:'GET',
      success:function(data){
        alert(data);
      }
    });
  }

  function deleteMe(id){
    if(confirm('Are You Sure ? ')){
      $.ajax({
        url:'updateSubMenu.php',
        data:'id='+id+'&del=set',
        type:'GET',
        success:function(data){
          alert(data);
        }
      });
    }
    else{
      return ;
    }  
  }
</script>
