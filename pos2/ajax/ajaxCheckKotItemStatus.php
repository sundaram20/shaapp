<?php include_once("../../config/auto_loader.php");

	//debugData($_REQUEST);
	$cookStatusReadDisable='onclick="ajaxRemoveKotEditItemList(\''.$_REQUEST['uniqueCode'].'\',\'removeOne\','.$_REQUEST['id_pos_purch'].',\''.$_REQUEST['UniqueCodeGen'].'\',\''.$_REQUEST['id_purch_details'].'\');"';
	
	
$CheckBlockedTable_Sql = mysqli_query($connNew,"SELECT ppp.* , 
	   (case when COALESCE(ppp.qty-ppp.adj_qty)=0 then 'Billed'
        	when COALESCE(ppp.qty-ppp.adj_qty)>0 then 'Pending'
        
        end) as kot_status
             
	    FROM pos_purch_details as ppp WHERE ppp.`id_pos_purch`='".$_REQUEST['id_pos_purch']."' and  ppp.id='".$_REQUEST['id_purch_details']."' ORDER BY kot_status desc");
			   //$db->query($CheckBlockedTable_Sql); 
			   $i=1;
			  $RowCount	=	mysqli_num_rows($CheckBlockedTable_Sql);	
			   $ResultBlockedtable1 = mysqli_fetch_object($CheckBlockedTable_Sql);
				  
$item_description	=	$ResultBlockedtable1->item_description;
  				 
				 $serve_status 	= $ResultBlockedtable1->serve_status;
 				$cook_status	= $ResultBlockedtable1->cook_status;
if($serve_status==1){
$serve_status = "Preparing";
}elseif($serve_status==2){
$serve_status = "Served";
} elseif($serve_status==0){
$serve_status = "Pending";
}
if($cook_status==0){
$cook_status = "Pending";

}elseif($cook_status==1){
$cook_status = "Ready";

 }	   
		       
					
		


?><div class="modal-body" > 
<div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">
  <table id="myTableOrder" class="table  table-striped table-bordered dataTable no-footer" cellspacing="0">
    <thead>
      <tr>
       
        <th>Items Name</th>
   
        <th>Cook Status</th>
        <th>Served</th>
        
      </tr>
    </thead>
    <tbody>
      <tr>
       
        <td><?php echo $item_description;?></td>

        <td><?php echo $cook_status;?></td>
        <td><?php echo $serve_status;?></td>
        
      </tr>
      
    </tbody>
  </table>
</div>
</div>
 <div class="row">
      	<div class="col-md-12">
      		      	<!--cancel pop start-->
		  <div id="cancelpop" class="well p-4" style="margin:0 15px"> 
        Are you sure you want to Remove this Item ?
        </div>
        </div></div>
        <div class="modal-footer">
        <button class="btn btn-primary" <?php echo $cookStatusReadDisable;?>>Delete</button>
            <button class="btn btn-secondary" data-dismiss="modal" aria-label="close">Close</button>
        </div>

