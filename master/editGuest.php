<?php include_once("../config/auto_loader.php");?>
<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>  
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>  <span style="color: #f25e74;"> Guest Manager </span> <small>Manage Guest</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Manage Guest</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
    	<div class="col-md-12">
    		<?php include_once("../actions/editGuestForm.php"); ?>
    	</div>

    </div>
  </section>
  <!-- /.content -->
</div>
<script>
 /* window.onload = function() { getState(<?php if($_REQUEST['id_country']){echo "'".$_REQUEST['id_country']."'";}elseif($row->id_country != ''){echo "'".$row->id_country."'";}else { echo "'"."'";} ?>,<?php if($_REQUEST['id_state']){echo "'".$_REQUEST['id_state']."'";}elseif($row->id_state != ''){echo "'".$row->id_state."'";}else { echo "'"."'";} ?>,<?php if($_REQUEST['other_state'] != ''){echo "'".$_REQUEST['other_state']."'";}elseif($row->other_state != ''){echo "'".$row->other_state."'";}else { echo "'"."'";} ?>); }; */
  </script>
<?php include_once("../includes/footer.php")?>





<script type="text/javascript">
	function openPage(){
		window.location = "manageGuests.php";
	}
</script>

<script type="text/javascript">
	//jump
	$("document").ready(function(){
		$("#importCredit").click(function(){
        $("#creditimport").submit(function(e){
          e.preventDefault();	
          var fileName = $("#creditImport").val();
          console.log(fileName);
          if(fileName == ""){
          	alert("Kindly Select a file.");
          }  
          else{
            $.ajax({
            type        : 'POST',
            contentType : false,
            processData : false,
            dataType	:'json', 
            url         : '../ajax/ajaxCreditFormUpload.php', 
            data        : new FormData(this),
            success     : function(data){
              $("#returnTxt").html(data[0]);
              $("#credithidden").val(data[1]);
            } 
           })
          }
        });
      });

	/*$(".fa-cloud-download").click(function(){
		var fileName = $("#credithidden").val();
		console.log(fileName);
		if(fileName == ""){
			alert("Credit Form not uploaded yet !")
		}
		else{
			$.ajax({
            type        : 'POST', 
            url         : 'ajax/ajaxCreditFormDownload.php', 
            data        : 'fileName='+fileName,
            success     : function(data){
              alert()
            } 
           })
		}
	});*/
	});
	
</script>

