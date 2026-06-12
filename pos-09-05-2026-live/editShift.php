<?php include_once("../config/auto_loader.php");

//$image_path='images/steward/';
//$image_display_path='images/steward/';

if($_REQUEST['eId']=='')
	checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'add');
else
	checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'edit');
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){


	$err = 0; 



	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add

			$sql = " SELECT * FROM `".TBL_ATTRIBUTES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `field_value` = '".addslashes(trim($_POST['field_value']))."' and `table_name` = 'shift' ";
			$db->query($sql);
			$numRows= $db->num_rows();
			if($numRows == '0'){

			checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'add');
			$addSql = "   	INSERT INTO `".TBL_ATTRIBUTES."` SET

							`table_name` = 'shift',
							`field_name` = 'shift_name',
							`field_value` = '".addslashes(trim($_POST['field_value']))."',
							`field_description` = '".addslashes($_POST['field_description'])."',
						
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";

						
			//	print_r($lastInsertId);
							executeSql($addSql);
							$lastInsertId= $db->insert_id();
							$addSql= "INSERT INTO `mst_attributes_shift` SET
							`id_mst_attributes_shift` = '".addslashes($lastInsertId)."',  
							`shift_from` = '".addslashes(trim($_POST['shift_from']))."',
							`shift_to` = '".addslashes(trim($_POST['shift_to']))."' ";

			if(executeSql($addSql)){
				//unset($_POST);
				//$lastInsertId= $db->insert_id();
				
	
			//executeSql($addSql2);

				$_SESSION['successMsg'] = 'New Shift details has been added sucessfully.';
				header("location:manageShift.php?eId=".encryptor(encrypt,$lastInsertId)."&submenu=".$_GET['submenu']."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Shift details has not been saved. Please make corrections below.';
			}
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Shift Master Name Already Exist.';
			}
		}

		//Update Section Here

		else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
		
		
			checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'update');
			 $editSql = "   	UPDATE `".TBL_ATTRIBUTES."` SET 
							`table_name` = 'shift',
							`field_name` = 'shift_name',
							`field_value` = '".addslashes(trim($_POST['field_value']))."',
							`field_description` = '".addslashes($_POST['field_description'])."',
								`image` = '".addslashes($_POST['image'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."'";
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."' 
							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'";

							executeSql($editSql);
							//$lastInsertId= $db->insert_id();
							
		$SelectSQL = "SELECT * FROM `mst_attributes_shift`where id_mst_attributes_shift='".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";
				$res=mysqli_query($connNew,$SelectSQL); 
				$NumRows = mysqli_num_rows($res);
			if($NumRows>0){
	
	
							$editSql= "UPDATE  `mst_attributes_shift` SET
							`shift_from` = '".date('H:i:s',(strtotime($_POST['shift_from'])))."',
							`shift_to` = '".date('H:i:s',(strtotime($_POST['shift_to'])))."' WHERE 	`id_mst_attributes_shift` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'    ";
							//echo $editSql;die;
			}else{
				$editSql= "INSERT INTO `mst_attributes_shift` SET
							`id_mst_attributes_shift` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."',  
							`shift_from` = '".addslashes(trim($_POST['shift_from']))."',
							`shift_to` = '".addslashes(trim($_POST['shift_to']))."' ";
				
				}
							//echo $editSql;die;	
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  'id' = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has been updated sucessfully.';
				header("location:manageShift.php?eId=".$_GET['eId']."&submenu=".$_GET['submenu']."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  'id' = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Shift details has not been saved. Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	/*$sql = "  SELECT * FROM `".TBL_ATTRIBUTES."` AS A INNER JOIN `mst_attributes_shift` AS B on
								 A.id=B.id_mst_attributes_shift WHERE A.`id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";*/


$sql = "  SELECT * FROM `".TBL_ATTRIBUTES."` AS A  WHERE A.`id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
								//echo $sql;
	 $db->query($sql);
	
	if($db->num_rows() > 0){
		$row = $db->fetch_object(); 
$from	=	selectColumn('mst_attributes_shift','shift_from'," WHERE  id_mst_attributes_shift='".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ");
$to	=	selectColumn('mst_attributes_shift','shift_to'," WHERE  id_mst_attributes_shift='".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ");
	}						
}	
							

?>
<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<style>
    *{
    margin:0;
    box-sizing:border-box;
}

body{
    background: rgb(2,0,36);
    background: linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(9,9,121,1) 35%, rgba(0,212,255,1) 100%);
}

.input_div{text-align: center;margin-top: 15em;}

.input_div input{
    padding: 10px;
    border-radius: 5px;
    border: none;
}

.modal-content{
    margin: 9em auto;
    width: fit-content;
    background-color: white;
}

.timepicker_wrapper{
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    background-color: #00000082;
    width: 100%;
    height: 100vh;
    z-index: 99;
}

.timepicker_hour,
.timepicker_minute,
.timepicker_ampm{
    scroll-behavior: smooth;
    -ms-overflow-style: none;
    scrollbar-width: none;
    border: 1px solid #80808080;
    color: #0000009e;
    font-weight: bold;
}

.timepicker_hour::-webkit-scrollbar,
.timepicker_minute::-webkit-scrollbar,
.timepicker_ampm::-webkit-scrollbar{ 
    display: none;  
}

.timepicker_hour option,
.timepicker_minute option,
.timepicker_ampm option{
    font-weight: bold;
    padding: 5px 25px;
}

.timepicker_control{text-align: end;margin-top: 5px;margin-bottom: 10px;}

.timepicker_control button{
    padding: 7px 15px;
    border: none;
    font-weight: bold;
    background-color: green;
    color: white;
    margin-left: 8px;
}
.timepicker_wrapper_main{
    width: fit-content;
    border: 1px solid gray;
    padding: 0px 12px;
}

.timepicker_control button:first-child{background-color: #ff0000db;color: white;margin-right: 15px;}

.timepicker_header{text-align: center;color: #0000008a;margin: 5px 0px;}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
	
	 	
   <?php  $session=$_GET['submenu']; ?>
    <section class="content-header">
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
		<?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>
      <?php echo breadCrumbs(); ?>
    </section>
	
    <section class="content">
	
	
			
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
         
           
			 <div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
			   <li class="active" ><a href="#tab_1" data-toggle="tab">Overview</a></li>  
            </ul>
			<div class="box-header with-border">
             
				 <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation_id($session)['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo $row->field_value ?> </span>
            </div>
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="form1"  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off" >
                <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />
					<div class="form-group has-error" align="center">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div>
              <div class="box-body">
                
				 <div class="form-group col-md-6 col-sm-12">
                  <label for="name">Shift Name<font color="#FF0000">*</font></label>
            
              			
                  <input type="text" class="form-control" placeholder="Enter Shift Name" id="field_value" name="field_value" value="<?php if($_POST) echo $_POST['field_value'];else echo stripslashes($row->field_value);?>"  data-parsley-required>
				<?php echo $err_unit_name;?>
                </div>
				
				<div class="form-group col-md-6 col-sm-12">
                  <label for="name">Description </label>
                
                  <input type="text" class="form-control" placeholder="Enter Description" id="field_description" name="field_description" value="<?php if($_POST) echo $_POST['field_field_description'];else echo stripslashes($row->field_description);?>"  >
				<?php echo $err_unit_field_description;?>
                </div>

					
				<div class="form-group col-md-6 col-sm-12">
                  <label for="name">From </label>

                  <input type="text"  onclick="timepicker(this,'a')" class="form-control" placeholder="From" id="shift_from" name="shift_from" value="<?php if($_POST){ 
                  	echo $_POST['shift_from'];
                  }
                  else
                   { 
	                  	if($from!=''){
	                  		echo stripslashes(date('h:i A',strtotime($from)));
	                    }else{echo '';}
	                }?>
                  "  >
				<?php echo $err_unit_shift_from;?>
                </div>

				<div class="form-group col-md-6 col-sm-12">
                  <label for="name">To </label>
                
              		
                  <input type="text"   onclick="timepicker(this,'a')" class="form-control" placeholder="To" id="shift_to" name="shift_to" value="<?php if($_POST){ 
                       echo $_POST['shift_to'];
                  }
                  else
                   { 
	                  	if($to!=''){
	                  		echo stripslashes(date('h:i A',strtotime($to)));
	                    }else{echo '';}
	                }?>">
				<?php echo $err_unit_field_description;?>
                </div>
				
                <?php 
		        	if($row->status == ''){
		        		$status = 1;
		        	}else{
		        		$status = $row->status;
		        	}
		        ?>
           				   
           			


				<div class="form-group col-sm-12">
                  <label for="status">Status</label>
                 <input class="flat-red" type="radio"  <?php if($_POST['status'] == '1'){echo "checked";}else{if($status == 1)echo "checked";}?> value="1" name="status"/> Active
				 <input class="flat-red" type="radio" <?php if($_POST['status'] == '0'){echo "checked";}else{if($status == 0)echo "checked";}?> value="0" name="status"/> Inactive
				 <?php echo $err_status;?>
                </div>
				
				<?php if($row->date_created){?>
				  
				<div class="form-group col-md-6 col-sm-12">
                  <label for="date_created">Date Created</label>
                  <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($row->date_created));?>">				
                </div> 
				
				<div class="form-group col-md-6 col-sm-12">
                  <label for="last_modified">Last Updated</label>
                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($row->last_modified));?>">				
                </div> 

                <div class="form-group col-md-6 col-sm-12">
                  <label for="last_modified_by">Created By</label>
				   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_created_by.'" ');?>
                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
                </div>  
				
				<div class="form-group col-md-6 col-sm-12">
                  <label for="last_modified_by">Last Updated By</label>
				   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_modified_by.'" ');?>
                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
                </div>  
				  
				  <?php } ?>            
              </div>
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Close' class="btn btn-danger" onclick='location.replace("manageShift.php?submenu=269&session=0"); '>
			 </div>
            </form>			
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->
    </section>

    <!-- /.content -->
  </div>

<div class="timepicker_wrapper" >
    <div class="modal-content">
        <div class="timepicker_wrapper_main">
            <p class="timepicker_header">
                <b>12</b>:<b>00</b>
                <b>AM</b>
            </p>
            <div class="timepicker_data_select">
                <select onchange="changeTimepickerheader(this,'1')" size="5" class="timepicker_hour"></select>
                <select onchange="changeTimepickerheader(this,'2')" size="5"  class="timepicker_minute"></select>
                <select onchange="changeTimepickerheader(this,'3')" size="5" class="timepicker_ampm">
                    <option value="AM">AM</option><option value="PM">PM</option>
                </select>
            </div>
            <div class="timepicker_control">
                <button onclick="timepicker(this,'x')">Close</button><button onclick="timepicker(this,'c')">Clear</button><button onclick="timepicker(this,'s')">Set</button>
            </div>
        </div>
    </div>
</div>							
<?php include_once("../includes/footer.php")?>

<script>
    var c_t = "";
function timepicker(el,S){
    var div = document.querySelector('.timepicker_wrapper')
    function pad(n) {
        var len = 2 - (''+n).length;
        return (len > 0 ? new Array(++len).join('0') : '') + n
      }
      
    if (S == 'a'){
        html = "";
        for(i=1;i<=12;i++){
            html += '<option value="'+pad(i)+'">'+pad(i)+'</option>'
        }
        document.querySelector('.timepicker_hour').innerHTML = html

        html = "";
        for(i=0;i<=59;i++){
            html += '<option value="'+pad(i)+'">'+pad(i)+'</option>'
        }
        document.querySelector('.timepicker_minute').innerHTML = html

        c_t = "";
        c_t = el;
        document.querySelector('.timepicker_wrapper').style.display = "block";
        
    }
    if(S == 'c'){
        document.querySelector('.timepicker_hour').value = "";
        document.querySelector('.timepicker_minute').value = "";
        document.querySelector('.timepicker_ampm').value = "";
        c_t.value = "";
    }
    if(S == 'x'){
        div.style.display = "none";
    }
    if(S == 's'){
        var hr = document.querySelector('.timepicker_hour').value;
        var min = document.querySelector('.timepicker_minute').value;
        var am = document.querySelector('.timepicker_ampm').value;
        if(hr != "" && min != "" && am != ""){
            c_t.value = hr+":"+min+" "+am;
            div.style.display = "none";
        }
        
        
    }
}

function changeTimepickerheader(el ,S){
    var k = document.querySelectorAll('.timepicker_header b')
    if(S == '1'){
        k[0].innerHTML = el.value
    }
    if(S == '2'){
        k[1].innerHTML = el.value
    }
    if(S == '3'){
        k[2].innerHTML = el.value
    }
}
  </script>
<script>


	/*$('.shift_from').timepicker({
		timeFormat:'h:mm p',
		interval:60,
		minTime:'10',
		maxTime:'6:00pm',
		defaulTime:'11',
		startTime:'10:00',
		dynamic:false,
		dropdown:true,
		scrollbar:true
	})

	*/


</script>



