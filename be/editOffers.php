<?php include_once("../config/auto_loader.php");?>

<?php include_once("../includes/header.php")?>

<?php include_once("../includes/left.php")?>

<style type="text/css">
    table {
      table-layout: fixed; 
      width: 100%;
      *margin-left: -100px;/*ie7*/

    }
    td, th {
      vertical-align: top;
      border-top: 1px solid #ccc;
      padding:10px;
      width:100px;
       text-align: center;
    }
    th {
    /*  position:absolute;
      *position: relative; /*ie7*/
    /*  left:0; */
      width:100px;
    }
    .hard_left {
      position:absolute;
      *position: relative; /*ie7*/
      left:0; 
      margin-right: 0px;
      width:100px;
    }
    /*.next_left {
      position:absolute;
      *position: relative; 
      left:100px; 
      width:100px;
    }*/
    .outer {
        position:relative;
        overflow-x:hidden;
    }
    .inner {
      overflow-x:scroll;
      overflow-y:visible;
      width:100%; 
      margin-left:100px;
    }
    tbody{
        padding: 0px 0px 0px 0px;
    }

    .inputGrid{
        margin-right:5px;
        text-align:center;
        margin-left:5px;
        width:50px;
        height:30px;
    }
    
    .arrows:hover{
        color:green;
        font-size:20px;
    }
    
</style>

<?php

    if($_REQUEST['eId']!=''){
      $editSql="SELECT A.offer_type,A.valid_from,A.valid_till,B.id_hotel,B.id_offer_master FROM ".TBL_OFFER_MASTER." A LEFT JOIN ".TBL_OFFER." B
            ON A.id=B.id_offer_master
        WHERE B.id='".encryptor(decrypt,$_REQUEST['eId'])."' ";
        
        $res = mysqli_query($connNew,$editSql);
        $row = mysqli_fetch_object($res); 

        $disabled='disabled="disabled"';
        $hide='style="display:none"';

    }      
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
	
	<section class="content-header">
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
        <?php echo '<span style="color:'.currentNavigation()['color'].'">&nbsp;<i class="fa '.currentNavigation()['icon'].'"></i> '.currentNavigation()['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>
      <?php echo breadCrumbs(); ?>
    </section>
	
   <!-- <section class="content-header">
      <h1>
        Booking Engine
        <small>Manage Offers</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i>Booking Engine</a></li>
        <li class="active">Manage Offers</li>
      </ol>
    </section> -->
    <!-- Main content -->
    <section class="content">       
    <div class="box box-success">
     <div class="form-group has-error" align="center">
        <?php if($_SESSION['errorMsg']){?>
         <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
        <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
        <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
        <?php unset($_SESSION['successMsg']);}?>
        </div>
		
		
		
			<div class="box-header with-border">
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation()['submenu']; ?> : <span style="color:#3c8dbc"> <?php 
			  
			  $resHotel1 = selectSql(TBL_HOTELS," WHERE  id='".$row->id_hotel."' ") ;
                if($db->num_rows2($resHotel1)){
                    $rowHotel1 = $db->fetch_object2($resHotel1);
					echo $rowHotel1->name ;
				}
			  ?> </span>
            </div>
		
		
        
        <!-- /.box-header -->
        <form enctype='multipart/form-data' name="searchForm" id="searchForm" action="" method="get">
          <input type="hidden" id="eId" name="eId" value="<?php echo encryptor(decrypt,$_REQUEST['eId']);?>">  
        <div class="box-body">
          <div class="row">

            <!-- from to date -->
              <div class="col-md-4">
                <div class="form-group">
                    <label>Offer</label>
                    <?php
                     $offerSql = "SELECT id,offer_name FROM ".TBL_OFFER_MASTER." WHERE id_shop=".$_SESSION['shop']." AND '".date('Y-m-d')."' < valid_till order by display_order";
                    $resOffer=mysqli_query($connNew,$offerSql);

                    ?>
                    <select onChange="fetchOfferDescription(this.value);" <?php echo $disabled; ?> required="required" class="form-control select2" id="offer_master" name="offer_master">
                        <option value="">---SELECT OFFER---</option>
                        <?php
                        while($rowOffer=mysqli_fetch_object($resOffer)){
                            if($row->id_offer_master==$rowOffer->id){
                                $selected="selected='selected'";
                            }
                            else{
                                $selected="";
                            }
                         
                            echo '<option '.$selected.' value="'.$rowOffer->id.'">'.$rowOffer->offer_name.'</option>';
                         }?> 
                    </select>
                </div>
              </div>  
                    
            


            <div class="col-md-4">
              <div class="form-group">
        <label for="hotel">Hotel</label>
            <?php $categoryDropDown = '<select '.$disabled.' class="form-control select2" onchange="fetchRoomPlanLink(this.value,\'id_link\',\''.$row->id_room_plan_link.'\');fetchOfferGrid(this.value);"  required name="id_hotel" id="id_hotel">
                <option value="">Select Hotel</option>';
                $resHotel = selectSql(TBL_HOTELS," WHERE `status` = '1' AND id_shop='".$_SESSION['shop']."' ",' ORDER BY `name`');
                if($db->num_rows2($resHotel)){
                    while($rowHotel = $db->fetch_object2($resHotel)){
                        if($row->id_hotel == $rowHotel->id){
                            $selected = 'selected="selected"';
                        }else{
                            $selected = '';
                        }
                        $categoryDropDown .= '<option '.$selected.' value="'.$rowHotel->id.'">'.ucfirst($rowHotel->name).'</option>';
                    }
                }
                echo $categoryDropDown .= '</select>';
            ?>
        
         </div>
            </div>
            <?php if($_REQUEST['eId']!=""){ ?>
            <div class="col-md-4">
                <div class="form-group">
              <label for="link_id">Room-Plan-Link</label><!--&nbsp;&nbsp;&nbsp;<label>Select All</label>&nbsp;<input value="1" onchange="fetchAllRoomPlanLink();"  type="radio" name="room_plan_all"  class="flat-red">&nbsp;&nbsp;<label>Deselect All</label><input value="0" checked="checked" onchange="fetchAllRoomPlanLink();" type="radio" name="room_plan_all" class="flat-red">-->
              <?php if(isset($_REQUEST['eId'])){ ?>
              <input type="hidden" value="<?php echo $row->id_room_plan_link; ?>" name="id_link[]">     
              <input type="hidden" value="<?php echo $row->id_hotel; ?>" name="id_hotel">       
              <?php } ?>    
              <select  class="form-control select2" required name="id_link[]" onchange="fetchEditOfferGrid();" id="id_link" multiple="multiple">
                             
              </select>     
            </div>
            </div>
        <?php } ?>
           
        </div>
          <div class="row" id="offerDescriptionBox">
              
          </div>
         <section <?php echo $hide; ?> id="rowGrid" class="content">
                
         </section>

          
          <div class="row">
                <div class="col-md-4" id="validBeforeBox">
                <div class="form-group">
                    <label for="offer_type">Note : All Fields Are Required</label>
                                 
                </div>
                </div>
   
          </div>  
        </div>
        <!-- /.box-body -->
        <div class="box-footer">

        <input type="hidden" value="<?php echo ($_REQUEST['eId']!=''?'edit':'add'); ?>" name="operation"/>

        <?php if($_REQUEST['eId']==''){ ?>
        <input name="addOffer" id="addOffer" type="submit" class="btn btn-primary" value="Add" />
        <?php }else{ ?>
        <input name="updateOffer" id="updateOffer" type="button" class="btn btn-success" onclick="saveOfferGrid();" value="Update" />
        <?php  } ?>

        

        <a href="manageOffers.php" class="btn btn-warning">Cancel</a>
        <span  id="loadMe" style="display:none;font-weight:bold;color:red;">Please Wait...</span>
        </div>
        </form>     
      
    </section>
        <hr width="100%">
        <section style="margin-top:-60px; " id="gridLayout" class="content">
                
         </section>
    <!-- /.content -->
  </div>
<script type="text/javascript">
    var dayExtend=<?php echo ($effective_date==''?0:($effective_date)) ;?>;
       
</script>                                   
<?php include_once("../includes/footer.php")?>  



<script type="text/javascript">
           
    

    function saveOfferGrid(){
        
        var id_link=[];
        id_link = $('#id_link_hidden').val().split(",");
        id_link.pop();

        
        var completeData='';
        for(let i=0;i<id_link.length;i++){
            completeData += '&'+$('#form'+id_link[i]).serialize();
        }
        
        $.ajax({
                type: "POST",
                url: 'ajax/ajaxSaveOffers.php',
                data: completeData+'&'+$("#searchForm").serialize()+'&operation=edit'+'&eId='+$('#eId').val()+'&imageName='+$('#imageName').val()+'&offer_type='+'<?php echo $row->offer_type ;?>'+'&id_hotel='+'<?php echo $row->id_hotel; ?>'+'&id_link='+id_link,
                success:function(data){
                   console.log(data);
                   //window.location.href="manageOffers.php";
                },
            });
    }


    $("#searchForm").submit(function(e){
        e.preventDefault();

        if($('#offer_type').val()==4 && $('#promo_code').val()==''){
            alert("Promo Code Can't be blank");
            return;
        }

        var formData = $("#searchForm").serialize()+'&imageName='+$('#imageName').val();

        $.ajax({
            type: "POST",
            url: 'ajax/ajaxSaveOffers.php',
            data: formData,
            success: function(data){
                alert(data);
               window.location.href="manageOffers.php"; 
            },
        }); 
        
    });
     
    function inputDisplay(val){
        if(val==3){
            $('#validBeforeBox').css('visibility','visible');
            $('#promoCodeBox').css('visibility','hidden');
        }
        else if(val==4){
            $('#promoCodeBox').css('visibility','visible');
            $('#validBeforeBox').css('visibility','hidden');
            $('#valid_before').val(0);
        }
        else{
            $('#validBeforeBox').css('visibility','hidden');
            $('#promoCodeBox').css('visibility','hidden');
            $('#valid_before').val(0);
        }       
    }    

    function fetchOfferGrid(id_hotel=''){
        $.ajax({
            type: "POST",
            url: 'ajax/ajaxFetchOfferGrid.php',
            data: 'id_hotel='+id_hotel,
            success: function(data){
                //console.log(data);    
                $("#rowGrid").html(data);
            },
        }); 
    }
    
   

    $(document).ajaxStart(function(){
        $('#loadMe').show();
    });

    $(document).ajaxComplete(function(){
        $('#loadMe').hide();
    });

    function fillLeft(from,till,id){

        var fillVal = $("input[name='"+id+"_"+till+"']").val();

        var end = new Date(till);
        var  start = new Date(from);
        
        while(start <=end){
            var day = ("0" + (start.getDate())).slice(-2);
            var month = ("0" + (start.getMonth() + 1)).slice(-2);
            var year = start.getFullYear();

            var date = (year+'-'+month+'-'+day);

            $("input[name='"+id+"_"+date+"']").val(fillVal);

            start.setDate(start.getDate() + 1);

        }
    }

    function fillRight(from,till,id){
        var fillVal = $("input[name='"+id+"_"+from+"']").val();
        var end = new Date(till);
        var  start = new Date(from);
                
        while(start <=end){
            var day = ("0" + (start.getDate())).slice(-2);
            var month = ("0" + (start.getMonth() + 1)).slice(-2);
            var year = start.getFullYear();

            var date = (year+'-'+month+'-'+day);

            $("input[name='"+id+"_"+date+"']").val(fillVal);

            start.setDate(start.getDate() + 1);

        }
    }
    
    function fillUp(start,end){
        let put = $("#disVal_"+start).val();
        while(start>=end){
            $("#disVal_"+start).val(put);
            start--;
        }
    }

    function fillDown(start,end){
        let put = $("#disVal_"+start).val();
        while(start<=end){
            $("#disVal_"+start).val(put);
            start++;
        }
    }
   
    
    
    function fetchEditOfferGrid(id_link){
        var eId = $('#eId').val();

        var id_link=[];
        var id_offer_master=$("#offer_master").val();
        id_link=$("#id_link").val(); 
        

        if(eId !=""){  
          var formData = $("#searchForm").serialize();  
          $.ajax({
            type: "POST",
            url: 'ajax/ajaxFetchOfferGrid.php',
            data: formData+'&eId='+eId+'&id_link='+id_link+'&id_offer_master='+id_offer_master,
            success: function(data){
                $('#loadMe').show();
                $('#gridLayout').html(data); 

            },
            complete:function(){
                $('#loadMe').hide();

            }
        });   
        } 
        
    }

    function fetchOfferDescription(id_offer){
        
        $.ajax({
            url:"ajax/ajaxFetchOfferDescription.php",
            type:"post",
            data:"id_offer="+id_offer,
            success:function(data){
                //console.log(data);
                $("#offerDescriptionBox").html(data);
            },
        })
    }

</script>

<?php
    if(isset($_REQUEST['eId'])){
?>
    <script type="text/javascript">
        var id_hotel = $("#id_hotel").val();
        fetchRoomPlanLink(id_hotel,'id_link',"");
        var id_offer_master = $("#offer_master").val();
        fetchOfferDescription(id_offer_master);
        </script>
<?php } ?>