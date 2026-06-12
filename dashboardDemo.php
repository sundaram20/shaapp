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

	     			  <div class="btn-group  "> <a type="button"  title="Add KOT" class="btn n-btn n-btn-l pull-right" href="pos/managePosKot.php?submenu=178" ><i class="fas fa-plus"></i>&nbsp;KOT </a> </div>
	     		      <div class="btn-group"> <a type="button"  title="Kitchen Display System" class="btn n-btn n-btn-l pull-right" href="pos/kds.php?submenu=178" ><i class="fas fa-tv"></i>&nbsp;KDS </a> </div>    
	     			 <div class="btn-group"> <a type="button"  title="KOT Table View" class="btn n-btn n-btn-l pull-right" href="pos/pendingkots.php?submenu=178" > <i class="fas fa-table"></i>&nbsp;KOT</a> </div>
	     		
	     		

	     			 <div class="btn-group"> <a type="button"  title="Add Bill" class="btn n-btn n-btn-l pull-right" href="pos/kotbilling.php?submenu=177&session=21" ><i class="fas fa-plus "></i>&nbsp;Bill </a> </div>     
	     			 <div class="btn-group"> <a type="button"  title="List Bill" class="btn n-btn n-btn-l  pull-right" href="pos/manageOutletBilling.php?submenu=177&session=21" > <i class="fas fa-list "></i>&nbsp;Bill</a> </div>
	     				    <div class="btn-group"> <a type="button" onClick="AddPosGuest();" title="List KOT" class="btn n-btn n-btn-l pull-right"> <i class="fas fa-plus"></i>&nbsp;POS Guest </a> </div>    

	     		</div>


	     
	     </div>   	
     </section>	
            <!--add guest starts-->

		  <div id="addPosGuestModal" class="well p-4" style="width:300px;margin:0 15px;display: none;"> 
		  <form id="FormAddPosGuest" autocomplete="on">
          
		 <!-- <input type="hidden" id="pos_purch_id" name="pos_purch_id" value="<?php echo encryptor(decrypt, $_REQUEST['editKotid']); ?>">
            <div id="kot_mdoc_no"> </div>-->
		 	<div class="form-group">
		      <label for="title">Guest Name</label>
		      
		      <input type="text" class="form-control input-sm" placeholder="Enter Guest Name" id="name" name="name" value=""/>
		    </div>
		    <div class="form-group">
		      <label for="title">Mobile No</label>
		      
		      <input type="text" class="form-control input-sm" placeholder="Enter Guest Mobile No" id="mobile" name="mobile"  required value=""/>
		    </div>
			
			
			
			<div class="form-group">
				 <label for="btn">&nbsp;<br><br></label>
             
				<button class="btn c-btn" onclick="ajaxAddPosGuest();" type="button"><i class="far fa-save"></i> Add</button>
				<a  href="" class="cancelpop_close btn c-btn"><i class="far fa-window-close"></i> Close</a>
			</div>
       
			
		  </form>
		</div>


		
		<!--pos guest  popup ends-->

  </div>
  <!-- /.content-wrapper -->
   
<?php include_once("includes/footer.php")?>

<script>
	function ajaxAddPosGuest(){

var form=$("#FormAddPosGuest");	
	var name=$("#name").val();
	//var id_pos_purch=$("#id_pos_purch").val();
	
	var mobile=$("#mobile").val();
	

	$('.loading').show();
    if(form.parsley().validate()){

		$.ajax({
			type: "GET",
			url: 'pos/ajax/ajaxAddPosGuest.php',
			data: 'name='+name+'&mobile='+mobile, 
			success: function (result) {
			      //  console.log(result);
			       window.location.reload();
			        data = JSON.parse(result);
			      
					//$( "#GetItemListView" ).html('');
					//getPreviousOrder(data.purch_id);	
				//	alert(data.msg);

					
					 /*if(submenu1=='179'){
						window.location.href="manageKotNc.php?submenu="+submenu1;
					}else{
						window.location.href="manageKot.php?submenu=178&session=22";
					}  */
					
      	}

		});

	}


}


function AddPosGuest(){
	
	//$("#cancelled").addClass("bookedby_open");
	$('#addPosGuestModal').popup({
        			transition: 'all 0.3s',
           			 autoopen: true,            
        			});
	//$("#pos_purch_id").val(posid);
	//$("#kot_mdoc_no").html(' KOT No: '+mdoc_no);				
	}
</script>
