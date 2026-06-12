<?php include_once("../../config/auto_loader.php");

	//debugData($_REQUEST);
	
	
	?>
   
    <?php
	
$CheckBlockedTable_Sql = mysqli_query($connNew,"SELECT ppp.* , 
	   (case when COALESCE(ppp.qty-ppp.adj_qty)=0 then 'Billed'
        	when COALESCE(ppp.qty-ppp.adj_qty)>0 then 'Pending'
        
        end) as kot_status
             
	    FROM pos_purch_details as ppp WHERE ppp.`id_pos_purch`='".$_REQUEST['id_pos_purch']."' ORDER BY kot_status desc");
			   //$db->query($CheckBlockedTable_Sql); 
			   $i=1;
			  $RowCount	=	mysqli_num_rows($CheckBlockedTable_Sql);
			  $i=0;	
			   while($ResultBlockedtable1 = mysqli_fetch_object($CheckBlockedTable_Sql)){
				  
$item_description	=	$ResultBlockedtable1->item_description;
  				 
				 $serve_status 	= $ResultBlockedtable1->serve_status;
 				$cook_status	= $ResultBlockedtable1->cook_status;
if($serve_status==1){
$serve_status = "Preparing";
$color='gray';
}elseif($serve_status==2){
$serve_status = "Served";
$color='red;font-weight: 600;';
} elseif($serve_status==0){
$serve_status = "Pending";
$color='gray';
}
if($cook_status==0){
$cook_status = "Pending";
$color2='gray';
}elseif($cook_status==1){
$cook_status = "Ready";
$color2='red;font-weight: 600;';
 }	   
		       
					
		
if( ($cook_status=='Ready') || ($serve_status=='Served')){
$i++;

  $itemlist .='  <tr>
       
        <td>'.$item_description.'</td>

        <td style="color:'.$color2.'">'.$cook_status.'</td>
        <td style="color'.$color.'">'.$serve_status.'</td>
        
      </tr>';
      
    
 
 } } 
 
 ?>
 <?php  if($i>0){?>
  <div class="modal-body" > 
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
    <?php echo $itemlist;?>
 
 </tbody>
  </table>
</div>
</div>

<?php } ?>
