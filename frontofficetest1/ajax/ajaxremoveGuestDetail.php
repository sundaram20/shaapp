<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'add');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		
		
		if($_REQUEST['id_fo_reservations']!=='' &&   $_REQUEST['id_mst_room_no_allocation']!=='' && $_REQUEST['order_by_room']!='' && $_REQUEST['id_multiple_guest']!=''){
			//$id_shared_guest=array();
			$id_shared_guest	= selectColumn(FO_RESERVATIONS_DETAILS,'id_shared_guest','WHERE `id_fo_reservations` = "'.$_REQUEST['id_fo_reservations'].'"  and order_by_room = "'.$_REQUEST['order_by_room'].'" ');
			
			
			$id_shared_guest2=array();
			if($id_shared_guest!=''){
			$id_shared_guest2 =explode(',',$id_shared_guest);
			
			
			$key = array_search($_REQUEST['id_multiple_guest'], $id_shared_guest2);

if ($key !== false) {
    // Remove the element at the found index
    unset($id_shared_guest2[$key]);
}

// Re-index array if needed
$id_shared_guest2 = array_values($id_shared_guest2);
			
			}
			
			$id_shared_guest =implode(',',$id_shared_guest2);
			
			 $roomdetails = " UPDATE  `".FO_RESERVATIONS_DETAILS."` SET				 
						
						`id_shared_guest`='".$id_shared_guest."'					
						
						
						WHERE  
						`id_fo_reservations` = '".$_REQUEST['id_fo_reservations']."'  and order_by_room = '".$_REQUEST['order_by_room']."' 
						
						";
				mysqli_query($connNew,$roomdetails);
		}
		
		
?>  <table id="foliotable" class="table table-bordered table-striped datatable" cellspacing="0" width="100%" style="    background-color: #F5F5F5;">
    <thead><?php 
	  
	  if($id_shared_guest!=''){
		 $id_shared_guest	= explode(',',$id_shared_guest);
		 foreach($id_shared_guest as $sharecount=>$shareGuestid){
			 
			 $SQL_ShareGuest = "select *  from ".TBL_GUEST." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and id='".$shareGuestid."'";
            
            $query_ShareGuest=mysqli_query($connNew, $SQL_ShareGuest);
            
              $ShareGuestCheckout = "select *  from fo_shared_guest where shared_guest_status='1' and `id_mst_shops` = '".addslashes($_SESSION['shop'])."' and id_fo_folio_to='".$_REQUEST['id_folio']."' and id_shared_guest='".$shareGuestid."'";
            
            $queryShareGuestCheckout =mysqli_query($connNew, $ShareGuestCheckout);
			$checkoutNumRows =mysqli_num_rows($queryShareGuestCheckout);
           if($checkoutNumRows==1){?>
		<?php $csscolor="color: red"; }else{$csscolor="";
			}?>
            <tr><td style="<?php echo $csscolor;?>"><?php
            
              $result_ShareGuest=mysqli_fetch_assoc($query_ShareGuest);
			   $Title_ShareGuest=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$result_ShareGuest['id_mst_attributes_title']."'"); 	
			   echo $result_ShareGuest['guest_reg_no'] . ' - Name : '.ucfirst($Title_ShareGuest).''.ucfirst($result_ShareGuest['first_name']).' '.ucfirst($result_ShareGuest['last_name']).' |  Mobile : '.$result_ShareGuest['primary_mobile'];
			   ?></td><td>
				 <?php if($checkoutNumRows==0){?>
				<!--<a href="javascript:void(0);" style="color:black;" id="res_guestAddId" onclick="removeGuestDetail(<?php echo $_REQUEST['id_fo_reservations'].','.$_REQUEST['id_mst_room_no_allocation'].','.$_REQUEST['order_by_room'].',0,'.$result_ShareGuest['id']; ?>);"><button type="button" value="Remove" class="deleteBox"> <i class="fas fa-trash"></i></button></a> </a>-->
				
					 <div class="flex space-x-2">
    <button class="text-red-600 hover:text-red-800" title="Remove Guest" onclick="removeGuestDetail(<?php echo addslashes($_REQUEST['id_fo_reservations']).','.$_REQUEST['id_mst_room_no_allocation'].','.$_REQUEST['order_by_room'].','.$_REQUEST['room_no'].','.$result_ShareGuest['id'].','.$_REQUEST['id_folio']; ?>)">🗑</button>
    <button class="text-gray-700 hover:text-gray-900" title="Make Room Owner" onclick="guestTransferSharedToMain(<?php echo addslashes($_REQUEST['id_fo_reservations']).','.$_REQUEST['id_mst_room_no_allocation'].','.$_REQUEST['order_by_room'].','.$_REQUEST['room_no'].','.$result_ShareGuest['id'].','.$_REQUEST['id_folio']; ?>)">🏠 Room Owner</button>
    <button class="text-blue-600 hover:text-blue-800" title="Pax Checkout"  onclick="promptCheckinTimeAndCheckout(<?php echo addslashes($_REQUEST['id_fo_reservations']).','.$_REQUEST['id_mst_room_no_allocation'].','.$_REQUEST['order_by_room'].','.$_REQUEST['room_no'].','.$result_ShareGuest['id'].','.$_REQUEST['id_folio']; ?>)">📤 Pax Checkout</button>
  </div>  <?php } ?></td>
            </tr><?php
		 }
			 } 
		  
		  
	  
	  ?></thead></table>